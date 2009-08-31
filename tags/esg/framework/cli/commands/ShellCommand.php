<?php
/**
 * ShellCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @version $Id: ShellCommand.php 1180 2009-06-26 21:16:33Z qiang.xue $
 */

/**
 * ShellCommand executes the specified Web application and provides a shell for interaction.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: ShellCommand.php 1180 2009-06-26 21:16:33Z qiang.xue $
 * @package system.cli.commands
 * @since 1.0
 */
class ShellCommand extends CConsoleCommand
{
	/**
	 * @return string the help information for the shell command
	 */
	public function getHelp()
	{
		return <<<EOD
USAGE
  yiic shell [entry-script | config-file]

DESCRIPTION
  This command allows you to interact with a Web application
  on the command line. It provides tools to automatically
  generate new controllers, views and data models.

  It is recommended that you execute this command under
  the directory that contains the entry script file of
  the Web application.

PARAMETERS
 * entry-script | config-file: optional, the path to
   the entry script file or the configuration file for
   the Web application. If not given, it is assumed to be
   the 'index.php' file under the current directory.

EOD;
	}

	/**
	 * Execute the action.
	 * @param array command line parameters specific for this command
	 */
	public function run($args)
	{
		if(!isset($args[0]))
			$args[0]='index.php';
		$entryScript=isset($args[0]) ? $args[0] : 'index.php';
		if(($entryScript=realpath($args[0]))===false || !is_file($entryScript))
			$this->usageError("{$args[0]} does not exist or is not an entry script file.");

		// fake the web server setting
		$cwd=getcwd();
		chdir(dirname($entryScript));
		$_SERVER['SCRIPT_NAME']='/'.basename($entryScript);
		$_SERVER['REQUEST_URI']=$_SERVER['SCRIPT_NAME'];
		$_SERVER['SCRIPT_FILENAME']=$entryScript;
		$_SERVER['HTTP_HOST']='localhost';
		$_SERVER['SERVER_NAME']='localhost';
		$_SERVER['SERVER_PORT']=80;

		// reset context to run the web application
		restore_error_handler();
		restore_exception_handler();
		Yii::setApplication(null);
		Yii::setPathOfAlias('application',null);

		ob_start();
		$config=require($entryScript);
		ob_end_clean();

		// oops, the entry script turns out to be a config file
		if(is_array($config))
		{
			chdir($cwd);
			$_SERVER['SCRIPT_NAME']='/index.php';
			$_SERVER['REQUEST_URI']=$_SERVER['SCRIPT_NAME'];
			$_SERVER['SCRIPT_FILENAME']=$cwd.DIRECTORY_SEPARATOR.'index.php';
			Yii::createWebApplication($config);
		}

		restore_error_handler();
		restore_exception_handler();

		$yiiVersion=Yii::getVersion();
		echo <<<EOD
Yii Interactive Tool v1.0 (based on Yii v{$yiiVersion})
Please type 'help' for help. Type 'exit' to quit.
EOD;
		$this->runShell();
	}

	/**
	 * Reads input via the readline PHP extension if that's available, or fgets() if readline is not installed.
	 * @param string prompt to echo out before waiting for user input
	 * @return mixed line read as a string, or false if input has been closed
	 */
	protected function readline($prompt)
	{
		if (extension_loaded('readline'))
		{
			$input = readline($prompt);
			readline_add_history($input);
			return $input;
		}
		else
		{
			echo $prompt;
			return fgets(STDIN);
		}
	}

	protected function runShell()
	{
		// disable E_NOTICE so that the shell is more friendly
		error_reporting(E_ALL ^ E_NOTICE);

		$_runner_=new CConsoleCommandRunner;
		$_runner_->addCommands(dirname(__FILE__).'/shell');
		$_runner_->addCommands(Yii::getPathOfAlias('application.commands.shell'));
		$_commands_=$_runner_->commands;

		while(($_line_=$this->readline("\n>> "))!==false)
		{
			$_line_=trim($_line_);
			try
			{
				$_args_=preg_split('/[\s,]+/',rtrim($_line_,';'),-1,PREG_SPLIT_NO_EMPTY);
				if(isset($_args_[0]) && isset($_commands_[$_args_[0]]))
				{
					$_command_=$_runner_->createCommand($_args_[0]);
					array_shift($_args_);
					$_command_->run($_args_);
				}
				else
					echo eval($_line_.';');
			}
			catch(Exception $e)
			{
				if($e instanceof ShellException)
					echo $e->getMessage();
				else
					echo $e;
			}
		}
	}
}

class ShellException extends CException
{
}
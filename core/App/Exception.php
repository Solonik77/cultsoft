<?php
/**
 * Application Exception Class
 * $Id$
 *
 * @package Core
 * @author Denysenko Dmytro
 * @copyright (c) 2009 CultSoft
 * @license http://cultsoft.org.ua/platform/license.html
 */
class App_Exception extends Zend_Exception
{

    /**
     * Creates a new exception.
     *
     * @param string $ error message
     * @param array $ translation variables
     * @return void
     */
    public function __construct ($message, array $variables = null, $code = 0)
    {
        // Sets $this->message the proper way
        parent::__construct($message, $code);
    }

    /**
     * Enable Application exception handling.
     *
     * @return void
     */
    public static function enable ()
    {
        set_exception_handler(array(__CLASS__ , 'handle'));
    }

    /**
     * Disable Application exception handling.
     *
     * @return void
     */
    public static function disable ()
    {
        restore_exception_handler();
    }

    /**
     * Exception handler.
     *
     * @param object $ Exception instance
     * @return void
     */
    public static function handle ($code, $error = 0, $file = '', $line = 0, $context = null)
    {
        // Display (and log the error message)
        echo $code;
        // Exceptions must halt execution
        exit();
    }

    /**
     * Outputs an inline error message.
     *
     * @return string
     */
    public function __toString ()
    {
        try {
            if (method_exists($this, 'sendHeaders') and ! headers_sent()) {
                // Send the headers if they have not already been sent
                $this->sendHeaders();
            }
            // Load the error message information
            if (is_numeric($this->code)) {
                $errors = array(E_RECOVERABLE_ERROR => array(1 , 'Recoverable Error' , 'An error was detected which prevented the loading of this page. If this problem persists, please contact the website administrator.') , E_ERROR => array(1 , 'Fatal Error' , '') , E_USER_ERROR => array(1 , 'Fatal Error' , '') , E_PARSE => array(1 , 'Syntax Error' , '') , E_WARNING => array(1 , 'Warning Message' , '') , E_USER_WARNING => array(1 , 'Warning Message' , '') , E_STRICT => array(2 , 'Strict Mode Error' , '') , E_NOTICE => array(2 , 'Runtime Message' , ''));
                if (! empty($errors[$this->code])) {
                    list ($level, $type, $description) = $errors[$this->code];
                } else {
                    $level = 1;
                    $type = 'Unknown Error';
                    $description = '';
                }
            } else {
                // Custom error message, this will never be logged
                $level = 5;
                $type = $this->code;
                $description = '';
            }
            // Log the error
            App::log('Uncaught ' . $type . ' ' . $this->message . ' in file ' . $this->debug_path($this->file) . ' on line ' . $this->line, Zend_Log::ERR);
            $layout = new Zend_Layout();
            $view = $layout->getView();
            $view->getHelper('headTitle')->headTitle("System Error");
            $view->getHelper('headStyle')->headStyle(file_get_contents(STATIC_PATH . 'system/css/error' . ((APPLICATION_ENV != 'development') ? '_disabled' : '') . '.css'));
            $layout->setLayoutPath(STATIC_PATH . 'system/')->setLayout('system_error');
            $message = $this->message;
            $file = $this->debug_path($this->file);
            $line = $this->line;
            if (! empty($this->file)) {
                // Lines to read from the source
                $start_line = $line - 4;
                $end_line = $line + 3;
                $file_source = fopen($this->file, 'r');
                $file_line = 1;
                // Source code
                $source = '';
                // Lines to read from the source
                $start_line = $line - 4;
                $end_line = $line + 3;
                $file_source = fopen($this->file, 'r');
                $file_line = 1;
                while ($read_line = fgets($file_source)) {
                    if ($file_line >= $start_line) {
                        if ($file_line === $line) {
                            // Wrap the text of this line in <span> tags, for highlighting
                            $read_line = '<span>' . v_helper_html::specialchars($read_line) . '</span>';
                        } else {
                            $read_line = v_helper_html::specialchars($read_line);
                        }
                        $source .= $read_line;
                    }
                    if (++ $file_line > $end_line) {
                        // Stop reading lines
                        fclose($file_source);
                        break;
                    }
                }
                $layout->type = $type;
                $layout->file = $file;
                $layout->line = $line;
                $layout->message = $this->message;
                $layout->source = $source;
                $trace = $this->getTrace();
                // Read trace
                $layout->trace = $this->read_trace($trace);
                $layout->content = "";
            }
            return $layout->render();
        } catch (Exception $e) {
            // This shouldn't happen unless core files are missing
            if (APPLICATION_ENV == 'development') {
                exit('Exception thrown inside ' . __CLASS__ . ': ' . $e->getMessage());
            } else {
                exit('Unknown Error');
            }
        }
    }

    /**
     * Simplifies [back trace][ref-btr] information.
     *
     * [ref-btr]: http://php.net/debug_backtrace
     *
     * @param array $ backtrace generated by an exception or debug_backtrace
     * @return string
     */
    public function read_trace (array $trace_array)
    {
        $file = null;
        $ouput = array();
        foreach ($trace_array as $trace) {
            if (isset($trace['file'])) {
                $line = '<strong>' . $this->debug_path($trace['file']) . '</strong>';
                if (isset($trace['line'])) {
                    $line .= ', line <strong>' . $trace['line'] . '</strong>';
                }
                $output[] = $line;
            }
            if (isset($trace['function'])) {
                // Is this an inline function?
                $inline = in_array($trace['function'], array('require' , 'require_once' , 'include' , 'include_once' , 'echo' , 'print'));
                $line = array();
                if (isset($trace['class'])) {
                    $line[] = $trace['class'];
                    if (isset($trace['type'])) {
                        $line[] .= $trace['type'];
                    }
                }
                $line[] = $trace['function'] . ($inline ? ' ' : '(');
                $args = array();
                if (! empty($trace['args'])) {
                    foreach ($trace['args'] as $arg) {
                        if (is_string($arg) and file_exists($arg)) {
                            // Sanitize path
                            $arg = $this->debug_path($arg);
                        }
                        $args[] = '<code>' . V_Helper_Text::limit_chars(v_helper_html::specialchars(self::debug_var($arg)), 50, '...') . '</code>';
                    }
                }
                $line[] = implode(', ', $args) . ($inline ? '' : ')');
                $output[] = "\t" . implode('', $line);
            }
        }
        return $output;
    }

    /**
     * Removes APPLICATION_PATH, CORE_PATH and DOC_ROOT from filenames, replacing
     * them with the plain text equivalents.
     *
     * @param string $ path to sanitize
     * @return string
     */
    public function debug_path ($file)
    {
        if (strpos($file, APPLICATION_PATH) === 0) {
            $file = 'APPLICATION_PATH/' . substr($file, strlen(APPLICATION_PATH));
        } elseif (strpos($file, CORE_PATH) === 0) {
            $file = 'CORE_PATH/' . substr($file, strlen(CORE_PATH));
        } elseif (strpos($file, DOC_ROOT) === 0) {
            $file = 'DOC_ROOT/' . substr($file, strlen(DOC_ROOT));
        }
        return $file;
    }

    /**
     * Similar to print_r or var_dump, generates a string representation of
     * any variable.
     *
     * @param mixed $ variable to dump
     * @param boolean $ internal recursion
     * @return string
     */
    public static function debug_var ($var, $recursion = false)
    {
        static $objects;
        if ($recursion === false) {
            $objects = array();
        }
        switch (gettype($var)) {
            case 'object':
                // Unique hash of the object
                $hash = spl_object_hash($var);
                $object = new ReflectionObject($var);
                $more = false;
                $out = 'object ' . $object->getName() . ' { ';
                if ($recursion === true and in_array($hash, $objects)) {
                    $out .= '*RECURSION*';
                } else {
                    // Add the hash to the objects, to detect later recursion
                    $objects[] = $hash;
                    foreach ($object->getProperties() as $property) {
                        $out .= ($more === true ? ', ' : '') . $property->getName() . ' => ';
                        if ($property->isPublic()) {
                            $out .= self::debug_var($property->getValue($var), true);
                        } elseif ($property->isPrivate()) {
                            $out .= '*PRIVATE*';
                        } else {
                            $out .= '*PROTECTED*';
                        }
                        $more = true;
                    }
                }
                return $out . ' }';
            case 'array':
                $more = false;
                $out = 'array (';
                foreach ((array) $var as $key => $val) {
                    if (! is_int($key)) {
                        $key = self::debug_var($key, true) . ' => ';
                    } else {
                        $key = '';
                    }
                    $out .= ($more ? ', ' : '') . $key . self::debug_var($val, true);
                    $more = true;
                }
                return $out . ')';
            case 'string':
                return "'$var'";
            case 'float':
                return number_format($var, 6) . '&hellip;';
            case 'boolean':
                return $var === true ? 'TRUE' : 'FALSE';
            default:
                return (string) $var;
        }
    }

    /**
     * Sends an Internal Server Error header.
     *
     * @return void
     */
    public function sendHeaders ()
    {
        // Send the 500 header
        header('HTTP/1.1 500 Internal Server Error');
    }

    public function __destruct ()
    {
    }
}

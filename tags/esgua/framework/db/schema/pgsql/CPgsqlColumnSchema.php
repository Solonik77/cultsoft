<?php
/**
 * CPgsqlColumnSchema class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * CPgsqlColumnSchema class describes the column meta data of a PostgreSQL table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CPgsqlColumnSchema.php 434 2008-12-30 23:14:31Z qiang.xue $
 * @package system.db.schema.pgsql
 * @since 1.0
 */
class CPgsqlColumnSchema extends CDbColumnSchema
{
	/**
	 * Extracts the PHP type from DB type.
	 * @param string DB type
	 */
	protected function extractType($dbType)
	{
		if(strpos($dbType,'integer')!==false || strpos($dbType,'oid')===0)
			$this->type='integer';
		else if(strpos($dbType,'bool')!==false)
			$this->type='boolean';
		else if(preg_match('/(real|float|double)/',$dbType))
			$this->type='double';
		else
			$this->type='string';
	}

	/**
	 * Extracts the default value for the column.
	 * The value is typecasted to correct PHP type.
	 * @param mixed the default value obtained from metadata
	 */
	protected function extractDefault($defaultValue)
	{
		if($defaultValue==='true')
			$this->defaultValue=true;
		else if($defaultValue==='false')
			$this->defaultValue=false;
		else if(strpos($defaultValue,'nextval')===0)
			$this->defaultValue=null;
		else if(preg_match('/\'(.*)\'::/',$defaultValue,$matches))
			$this->defaultValue=$this->typecast(str_replace("''","'",$matches[1]));
		else if(preg_match('/^-?\d+(\.\d*)?$/',$defaultValue,$matches))
			$this->defaultValue=$this->typecast($defaultValue);
		// else is null
	}
}
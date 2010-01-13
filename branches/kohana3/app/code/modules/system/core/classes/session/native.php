<?php defined('DOC_ROOT') or exit('No direct script access.');
/**
 * Native PHP session class.
 *
 * @package    Session
 */
class Session_Native extends Kohana_Session_Native {
    protected $_save_path;

    protected function __construct(array $config = NULL, $id = NULL)
    {
        if(isset($config['save_path']) and is_dir($config['save_path']) and is_writeable($config['save_path']))
        {
            $this->_save_path = $config['save_path'];
        }

        parent::__construct();
    }

	protected function _read($id = NULL)
	{
        if($this->_save_path){
            ini_set('session.save_path', $this->_save_path);
            session_save_path($this->_save_path);
        }
	    parent::_read($id);
	}

} // End Session_Native

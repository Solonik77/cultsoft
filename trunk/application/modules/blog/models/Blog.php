<?php
/**
* Blog model
*/
class Blog extends App_Model_Abstract {
    protected $_table = 'Blog_DbTable_Blog';

    public function __construct()
    {
        parent::__construct();
    }

    /*
	public function find($id = 0, $langId = null)
    {
        $select = $this->_i18n_blog->select();
        $select->where('blog_id = ?', (int) $id)->order('blog_id DESC');
        if ($langId) {
            $select->where('lang_id = ?', (int) $langId)->limit(1, 0);
        } else {
            $select->limit(2, 0);
        }
        $i18n = $this->_i18n_blog->fetchAll($select)->toArray();
        $return = array();
        foreach($i18n as $content) {
            $return['langid_' . $content['lang_id'] . '_title'] = $content['title'];
            $return['langid_' . $content['lang_id'] . '_description'] = $content['description'];
        }
        $blog = $this->_blog->find($id);
        return ($blog->current()) ? array_merge($return, $blog->current()->toArray()) : array();
    }
    */

/*
    public function fetchBlogsDataGrid($sortByField = 'id' , $sortOrder = 'asc')
    {
        $sortOrder = strtoupper((($sortOrder === 'asc') ? $sortOrder : 'desc'));
        $cols = array();
        $cols['blog'] = array('id', 'type', 'created');
        $cols['i18n_blog'] = array('title');
        $fields = array_merge($cols['blog'], $cols['i18n_blog']);
        if (!in_array($sortByField, $fields)) {
            $sortByField = (string) $fields[0];
        }

        $select = $this->_blog->select()->setIntegrityCheck(false);
        $select->from(DB_TABLE_PREFIX . 'blog', $cols['blog']);
        $select->join(DB_TABLE_PREFIX . 'i18n_blog', DB_TABLE_PREFIX . 'blog.id = ' . DB_TABLE_PREFIX . 'i18n_blog.blog_id', $cols['i18n_blog'])->where(DB_TABLE_PREFIX . 'i18n_blog.lang_id = ?', App::front()->getParam('requestLangId'))->order($sortByField . ' ' . $sortOrder);

        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(App::config()->items_per_page);
        $paginator->setCurrentPageNumber(App::front()->getRequest()->getParam('page', 1));
        return $paginator;
    }
    */
}

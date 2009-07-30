<?php
/**
 * Blog service
 *
 * @author Dmytro Denysenko
 */
class Blog_Model_Service
{
    private $_blog;
    private $_i18n_blog;

    public function __construct()
    {
        $this->_blog = new Blog_Model_DbTable_Blog();
        $this->_i18n_blog = new Blog_Model_DbTable_I18n_Blog();
    }

    public function getBlogsList()
    {
        return $this->_i18n_blog->fetchAll(
        $this->_i18n_blog->select()->where('lang_id = ?', App::front()->getParam('requestLangId'))->order('blog_id ASC')->limit(10, 0));
    }
}
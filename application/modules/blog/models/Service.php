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
    private $_blog_member;

    public function __construct()
    {
        $this->_blog = new Blog_Model_DbTable_Blog();
        $this->_i18n_blog = new Blog_Model_DbTable_I18n_Blog();
        $this->_blog_member = new Blog_Model_DbTable_Blog_Member();
    }

    public function createBlog()
    {
        $post = App::front()->getRequest()->getPost();
        $default_i18n = App::front()->getRequest()->getI18n(App::config()->languages->default_id);
        $i18n = App::front()->getRequest()->getI18n();
        try
        {
            App::db()->beginTransaction();
            // Save common data for blog
            $blogId = $this->_blog->insert(array('created' => V_Helper_Date::now() , 'slug' => V_Helper_Text::slug($default_i18n['title']) , 'type' => $post['type']));
            // Save blog member
            $this->_blog_member->insert(array('member_id' => App_Member::getInstance()->getId() , 'blog_id' => $blogId , 'is_moderator' => 1 , 'is_administrator' => 1));
            //Save i18n content for blog
            foreach($i18n as $value)
            {
                $this->_i18n_blog->insert(array('blog_id' => $blogId , 'lang_id' => $value['lang_id'] , 'title' => $value['title'] , 'description' => $value['description']));
            }
            App::db()->commit();
            return true;
        }
        catch(Exception $e)
        {
            App::db()->rollBack();
            App::log($e->getMessage(), 3);
            return false;
        }
    }

    public function getBlogsList()
    {
        return $this->_i18n_blog->fetchAll($this->_i18n_blog->select()->where('lang_id = ?', App::front()->getParam('requestLangId'))->order('blog_id ASC')->limit(10, 0));
    }
}
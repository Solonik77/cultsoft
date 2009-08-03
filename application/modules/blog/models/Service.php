<?php
/**
* Blog service
*
* @author Dmytro Denysenko
*/
class Blog_Model_Service {
    private $_blog;
    private $_i18n_blog;
    private $_blog_member;

    public function __construct()
    {
        $this->_blog = new Blog_Model_DbTable_Blog();
        $this->_i18n_blog = new Blog_Model_DbTable_I18n_Blog();
        $this->_blog_member = new Blog_Model_DbTable_Blog_Member();
    }

    public function saveBlog()
    {
        $post = App::front()->getRequest()->getPost();
        $default_i18n = App::front()->getRequest()->getI18n(App::config()->languages->default_id);
        $i18n = App::front()->getRequest()->getI18n();
        $blogId = $requestId = App::front()->getRequest()->getParam('id');
        App::db()->beginTransaction();
        try {
            $commonData = array('fancy_url' => V_Helper_Text::fancy_url((! empty($post['fancy_url'])) ? $post['fancy_url'] : $default_i18n['title']) , 'type' => $post['type']);
            if ($blogId) {
                // Update common data for blog
                $commonData['updated'] = V_Helper_Date::now();
                $this->_blog->update($commonData, 'id = ' . intval($blogId));
            } else {
                // Save common data for blog
                $commonData['created'] = $commonData['updated'] = V_Helper_Date::now();
                $blogId = $this->_blog->insert($commonData);
                // Save blog member
                $this->_blog_member->insert(
                    array('member_id' => App_Member::getInstance()->getId() , 'blog_id' => $blogId , 'is_moderator' => 1 , 'is_administrator' => 1));
            }
            // Save i18n content for blog
            foreach($i18n as $value) {
                if ($requestId) {
                    $this->_i18n_blog->update(array('title' => $value['title'] , 'description' => $value['description']),
                        array('blog_id = ' . intval($requestId) , 'lang_id = ' . intval($value['lang_id'])));
                } else {
                    $this->_i18n_blog->insert(array('blog_id' => $blogId , 'lang_id' => $value['lang_id'] , 'title' => $value['title'] , 'description' => $value['description']));
                }
            }
            App::db()->commit();
            return true;
        }
        catch(Exception $e) {
            App::db()->rollBack();
            App::log($e->getMessage(), 3);
            return false;
        }
    }

    public function deleteBlog($id = 0)
    {
        $id = (int) ($id != 0) ? $id : App::front()->getRequest()->getParam('id');
        try {
            $where = $this->_blog->getAdapter()->quoteInto('id = ?', $id);
            $this->_blog->delete($where);
            return true;
        }
        catch(Exception $e) {
            return false;
        }
    }

    public function findBlog($id = 0, $langId = null)
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

    public function fetchBlogsList()
    {
        $select = $this->_blog->select()->setIntegrityCheck(false)->from(DB_TABLE_PREFIX . 'blog', '*');
        $select->join(DB_TABLE_PREFIX . 'i18n_blog', DB_TABLE_PREFIX . 'blog.id = ' . DB_TABLE_PREFIX . 'i18n_blog.blog_id')->where(DB_TABLE_PREFIX . 'i18n_blog.lang_id = ?', App::front()->getParam('requestLangId'))->order(
            DB_TABLE_PREFIX . 'blog.id DESC');
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber(App::front()->getRequest()->getParam('page', 1));
        return $paginator;
    }
}

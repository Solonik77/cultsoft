<?php
/**
 * Admin blog
 *
 * @author Denysenko Dmytro
 */
class Blog_AdminController extends App_Controller_Action
{
    CONST BACKOFFICE_CONTROLLER = true;

    public function init()
    {
        $this->view->pageTitle = 'Blogs';
        $this->view->pageDescription = 'Create, edit, delete posts. Manage communities.';
        $this->view->headTitle(__($this->view->pageTitle));
    }

    public function preDispatch()
    {
    }

    /**
     * Basic information about blogs
     */
    public function indexAction()
    {
        $blogModel = new Blog_Model_BlogTest;
        $blogModel = new Blog_Model_Blog();        
        $test = $blogModel->findByPk(1);
        $searchBlogFrom = new Blog_Form_Simple_Search_Blog();
        if($this->_request->isPost()){
            $formData = $this->_request->getPost();
            $searchBlogFrom->populate($formData);
            if(! $searchBlogFrom->isValid($formData)){
                // Errors in input data
                $this->view->searchBlogFrom = $searchBlogFrom;
                return $this->render();
            }
        }
        $this->view->searchBlogFrom = $searchBlogFrom;
    }

    /**
     * Manage blogs
     */
    public function manageBlogsAction()
    {
        $blogModel = new Blog_Model_Blog();
        $this->view->pageDescription = 'Manage blogs.';
        $this->view->blogTypes = $blogModel->getBlogTypes();
        $this->view->blogsDataGrid = $blogModel->loadBlogs($this->_request->getParam('sort-by'), $this->_request->getParam('sort-order'));
    }

    /**
     * Create new blog
     */
    public function createBlogAction()
    {
        $this->view->pageDescription = 'Create new blog';
        $this->view->headTitle($this->view->pageDescription);
        $blogModel = new Blog_Model_Blog();
        $form = new Blog_Form_Blog();
        $form->setBlogTypes($blogModel->getBlogTypes());
        $form->compose();
        if($this->_request->isPost()){
            $postParams = $this->_request->getPost('blog');
            $blogModel->setAttributes($postParams);
            $formData = $this->_request->getPost();
            $form->populate($formData);
            if(! $form->isValid($formData)){
                // Errors in input data
                $this->view->form = $form;
                return $this->render();
            }
            else{
                $blogModel->setMemberId(App_Member::getInstance()->getId());
                $blogModel->setDateUpdated(Vendor_Helper_Date::now());
                $blogModel->setDateCreated(Vendor_Helper_Date::now());
                if(null == ($fancy_url = $postParams['fancy_url'])){
                    $firstLangKey = current(array_keys($postParams['i18n_blog']));
                    $fancy_url = isset($postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title']) ? $postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title'] : $postParams['i18n_blog'][$firstLangKey]['title'];
                }
                $blogModel->setFancyUrl(Vendor_Helper_Text::fancy_url($fancy_url));
                $blogModel->setType($postParams['type']);
                // Saving new blog
                if($blogModel->save()){
                    $moduleLangs = App::i18n()->getModuleLanguages();
                    if(count($moduleLangs) > 0){
                        foreach($moduleLangs as $lang){
                            if(isset($postParams['i18n_blog'][$lang['id']])){
                                $blogI18nModel = new Blog_Model_I18n_Blog();
                                $blogI18nModel->setAttributes($postParams['i18n_blog'][$lang['id']]);
                                $blogI18nModel->setLangId($lang['id']);
                                $blogI18nModel->setBlogId($blogModel->getId());
                                $blogI18nModel->save();
                            }
                        }
                    }
                    // Set message to view
                    $this->_helper->messages('New blog successfully created', 'success', true);
                    // Clear post
                    $this->_redirect('blog/admin/manage-blogs');
                }
                else{
                    // Set message to view
                    $this->_helper->messages('Error in creation new blog', 'error', true);
                    // Clear post
                    $this->_selfRedirect();
                }
            }
        }
        $this->view->form = $form;
    }

    /**
     * Edit blog
     */
    public function updateBlogAction()
    {
        $this->view->pageDescription = 'Edit blog';
        $this->view->headTitle($this->view->pageDescription);
        $blogModel = new Blog_Model_Blog();
        $form = new Blog_Form_Blog();
        $form->setIsUpdate(true);
        $form->setBlogTypes($blogModel->getBlogTypes());
        $form->setCurrentBlogType($blogModel->getType());
        $form->compose();
        // Get blog content
        $blogModel->findByPK($this->_request->getParam('id'));
        if($blogModel->getDbRow()){
            $i18nBlog = $blogModel->getDbRow()->findDependentRowset('Blog_DbTable_I18n_Blog')->toArray();
            $formData = $blogModel->getDbRow()->toArray();
            foreach($i18nBlog as $row){
                $formData['lang_' . $row['lang_id']] = $row;
            }
            $formData = (! $this->_request->isPost()) ? $formData : $this->_request->getPost();
            $form->populate($formData);
            if($this->_request->isPost()){
                $postParams = $this->_request->getPost('blog');
                if(isset($postParams['delete_blog'])){
                    // Delete blog
                    if($blogModel->delete()){
                        $this->_helper->messages('Blog deleted successfully', 'success', true);
                        $this->_redirect('blog/admin/manage-blogs');
                    }
                }
                if(! $form->isValid($formData)){
                    // Errors in input data
                    $this->view->form = $form;
                    return $this->render();
                }
                else{
                    $blogModel->setAttributes($postParams);
                    $blogModel->setDateUpdated(Vendor_Helper_Date::now());
                    if(null == ($fancy_url = $postParams['fancy_url'])){
                        $firstLangKey = current(array_keys($postParams['i18n_blog']));
                        $fancy_url = isset($postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title']) ? $postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title'] : $postParams['i18n_blog'][$firstLangKey]['title'];
                    }
                    $blogModel->setFancyUrl(Vendor_Helper_Text::fancy_url($fancy_url));
                    $blogModel->setType($postParams['type']);
                    // Saving new blog
                    if($blogModel->save()){
                        $moduleLangs = App::i18n()->getModuleLanguages();
                        if(count($moduleLangs) > 0){
                            foreach($moduleLangs as $lang){
                                if(isset($postParams['i18n_blog'][$lang['id']])){
                                    $blogI18nModel = new Blog_Model_I18n_Blog();
                                    $blogI18nModel->findByCondition(array('lang_id = ?' => $lang['id'] , 'blog_id = ?' => $blogModel->getId()));
                                    $blogI18nModel->setAttributes($postParams['i18n_blog'][$lang['id']]);
                                    $blogI18nModel->setLangId($lang['id']);
                                    $blogI18nModel->setBlogId($blogModel->getId());
                                    $blogI18nModel->save();
                                }
                            }
                        }
                        // Set message to view
                        $this->_helper->messages('Changes for blog successfully saved', 'success', true);
                        // Clear post
                        $this->_redirect('blog/admin/manage-blogs');
                    }
                    else{
                        // Set message to view
                        $this->_helper->messages('Error editing blog', 'error', true);
                        // Clear post
                        $this->_selfRedirect();
                    }
                }
            }
            $this->view->form = $form;
        }
        else{
            throw new App_Exception('Page not found');
        }
    }

    /**
     * Delete blog
     */
    public function deleteBlogAction()
    {
        $blogModel = new Blog_Model_Blog();
        $blogModel->findByPK($this->_request->getParam('id', 0));
        if(! $blogModel->getDbRow()){
            throw new App_Exception('Page not found');
        }
        else{
            if($blogModel->delete()){
                $this->_helper->messages('Blog deleted successfully', 'success', true);
            }
        }
        $this->_redirect('blog/admin/manage-blogs');
    }
}

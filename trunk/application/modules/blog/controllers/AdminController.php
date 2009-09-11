<?php
/**
* Admin blog
*
* @author Denysenko Dmytro
* @copyright (c) 2009 CultSoft
* @license http://cultsoft.org.ua/engine/license.html
*/
class Blog_AdminController extends App_Controller_Action {
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
        $searchBlogFrom = new Blog_Form_Simple_Search_Blog;
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            $searchBlogFrom->populate ($formData);
            if (! $searchBlogFrom->isValid ($formData)) {
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
        $blogModel = new Blog;
        $this->view->pageDescription = 'Manage blogs.';
        $this->view->types = array(1 => __('Personal blog') , 2 => __('Collaborative blog (community)'));
        $this->view->blogsDataGrid = $blogModel->loadBlogs($this->_request->getParam('sort-by'),
            $this->_request->getParam('sort-order'));
    }

    /**
    * Create new blog
    */
    public function createBlogAction()
    {
        $this->view->pageDescription = 'Create new blog';
        $this->view->headTitle ($this->view->pageDescription);
        $form = new Blog_Form_Blog;
        $form->compose();
        if ($this->_request->isPost()) {
            $blogModel = new Blog;
            $postParams = $this->_request->getPost('blog');
            $blogModel->setAttributes($postParams);

            $formData = $this->_request->getPost();
            $form->populate($formData);
            if (! $form->isValid ($formData)) {
                // Errors in input data
                $this->view->form = $form;
                return $this->render();
            } else {
                $blogModel->setDateUpdated(Vendor_Helper_Date::now());
                $blogModel->setDateCreated(Vendor_Helper_Date::now());

                if (null == ($fancy_url = $postParams['fancy_url'])) {
                    $firstLangKey = current(array_keys($postParams['i18n_blog']));
                    $fancy_url = isset($postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title']) ? $postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title'] : $postParams['i18n_blog'][$firstLangKey]['title'];
                }
                $blogModel->setFancyUrl(Vendor_Helper_Text::fancy_url($fancy_url));
                $blogModel->setType($postParams['type']);
                // Saving new blog
                if ($blogModel->save()) {
                    $moduleLangs = App::i18n()->getModuleLanguages();
                    if (count($moduleLangs) > 0) {
                        foreach($moduleLangs as $lang) {
                            if (isset($postParams['i18n_blog'][$lang['id']])) {
                                $blogI18nModel = new I18n_Blog;
                                $blogI18nModel->setAttributes($postParams['i18n_blog'][$lang['id']]);
                                $blogI18nModel->setLangId($lang['id']);
                                $blogI18nModel->setBlogId($blogModel->getId());
                                $blogI18nModel->save();
                            }
                        }
                    }
                    // Set message to view
                    $this->_helper->messages ('New blog successfully created', 'success', true);
                    // Clear post
                    $this->_redirect('blog/admin/manage-blogs');
                } else {
                    // Set message to view
                    $this->_helper->messages ('Error in creation new blog', 'error', true);
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
        if (! $blogId = $this->_request->getParam ('id')) {
            throw new App_Exception('Page not found');
        }
        $this->view->pageDescription = 'Edit blog';
        $this->view->headTitle ($this->view->pageDescription);
        $blogModel = new Blog;
        $form = new Blog_Form_Blog;
        $form->setIsUpdate(true);
        $form->compose();
        // Get blog content
        $blogRow = $blogModel->findByPK($blogId);
        $i18nBlog = $blogRow->findDependentRowset('Blog_DbTable_I18n_Blog')->toArray();
        $formData = $blogRow->toArray();
        foreach($i18nBlog as $row) {
            $formData['lang_' . $row['lang_id']] = $row;
        }
        $formData = (! $this->_request->isPost()) ? $formData : $this->_request->getPost();

        $form->populate ($formData);
        if ($this->_request->isPost()) {
            $postParams = $this->_request->getPost('blog');
            if ($this->_request->getParam('delete_blog')) {
                // Delete blog
                if ($blogModel->delete()) {
                    $this->_helper->messages ('Blog deleted successfully', 'success', true);
                    $this->_redirect ('blog/admin/manage-blogs');
                }
            }
            if (! $form->isValid($formData)) {
                // Errors in input data
                $this->view->form = $form;
                return $this->render();
            } else {
                $blogModel->setAttributes($postParams);
                $blogModel->setDateUpdated(Vendor_Helper_Date::now());

                if (null == ($fancy_url = $postParams['fancy_url'])) {
                    $firstLangKey = current(array_keys($postParams['i18n_blog']));
                    $fancy_url = isset($postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title']) ? $postParams['i18n_blog'][$this->_getDefaultSiteLanguageId()]['title'] : $postParams['i18n_blog'][$firstLangKey]['title'];
                }
                $blogModel->setFancyUrl(Vendor_Helper_Text::fancy_url($fancy_url));
                $blogModel->setType($postParams['type']);
                // Saving new blog
                if ($blogModel->save()) {
                    $moduleLangs = App::i18n()->getModuleLanguages();
                    if (count($moduleLangs) > 0) {
                        foreach($moduleLangs as $lang) {
                            if (isset($postParams['i18n_blog'][$lang['id']])) {
                                $blogI18nModel = new I18n_Blog;
                                $blogI18nModel->findByCondition(array('lang_id = ?' => $lang['id'], 'blog_id = ?' => $blogModel->getId()));                                
								$blogI18nModel->setAttributes($postParams['i18n_blog'][$lang['id']]);
								$blogI18nModel->setLangId($lang['id']);
                                $blogI18nModel->setBlogId($blogModel->getId());
                                $blogI18nModel->save();
                            }
                        }
                    }
                    // Set message to view
                    $this->_helper->messages ('Changes for blog successfully saved', 'success', true);
                    // Clear post
                    $this->_redirect ('blog/admin/manage-blogs');
                } else {
                    // Set message to view
                    $this->_helper->messages('Error editing blog', 'error', true);
                    // Clear post
                    $this->_selfRedirect();
                }
            }
        }
        $this->view->form = $form;
    }

    /**
    * Delete blog
    */
    public function deleteBlogAction()
    {
        if (! $blogId = $this->_request->getParam ('id')) {
            throw new App_Exception('Page not found');;
        } else {
            if ($blogModel->delete()) {
                $this->_helper->messages ('Blog deleted successfully', 'success', true);
            }
        }
        $this->_redirect ('blog/admin/manage-blogs');
    }
}

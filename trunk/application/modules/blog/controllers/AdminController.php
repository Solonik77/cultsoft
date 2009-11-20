<?php
/**
 * Admin controller for blog
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
        $searchBlogFrom = new Blog_Form_Simple_Search_Blog();
        /*
         $table = new Blog_Model_DbTable_Blog();
         $table->findAllByCondition(array('member_id = ?' => 1));
         print_r($table->getCollection()->getData());
         $collection = $table->createCollection(); // creates a rowset collection with zero rows
         $row = $table->createCollectionItem(); // creates one row with unset values
         $row->setMemberId(App_Member::getInstance()->getId());
         $row->setDateUpdated(Vendor_Helper_Date::now());
         $row->setDateCreated(Vendor_Helper_Date::now());
         $row->setType('private');
         $collection->addItem($row); // adds one row to the rowset
         $collection->save(); // iterates over the set of rows, calling save() on each row
         */
         /*
        $i18nCollection = App_Db_Table::collectionFactory('Blog_Model_DbTable_Blog_I18n');
        $row = $i18nCollection->createItem();
        $i18nCollection->addItem($row);
        $i18nCollection->save();
        */
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
        $blogModel = new Blog_Model_DbTable_Blog();
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
        $blogTable = new Blog_Model_DbTable_Blog();
        $newBlogRow = $blogTable->createRow();
        $form = new Blog_Form_Blog();
        $form->setBlogTypes($blogTable->getBlogTypes());
        $form->compose();
        if($this->_request->isPost()){
            $postParams = $this->_request->getPost('blog');
            $formData = $this->_request->getPost();
            $form->populate($formData);
            if(! $form->isValid($formData)){
                // Errors in input data
                $this->view->form = $form;
                return $this->render();
            }
            else{
                $newBlogRow->setAttributes($postParams);
                $newBlogRow->setMemberId(App_Member::getInstance()->getId());
                $newBlogRow->setDateUpdated(Vendor_Helper_Date::now());
                $newBlogRow->setDateCreated(Vendor_Helper_Date::now());
                if(null == ($fancy_url = $postParams['fancy_url'])){
                    $firstLangKey = current(array_keys($postParams['blog_i18n']));
                    $fancy_url = isset($postParams['blog_i18n'][$this->_getDefaultSiteLanguageId()]['title']) ? $postParams['blog_i18n'][$this->_getDefaultSiteLanguageId()]['title'] : $postParams['blog_i18n'][$firstLangKey]['title'];
                }
                $newBlogRow->setFancyUrl(Vendor_Helper_Text::fancy_url($fancy_url));
                $newBlogRow->setType($postParams['type']);
                // Saving new blog
                if($newBlogRow->save()){
                    $moduleLangs = App::i18n()->getModuleLanguages();
                    $i18nCollection = App_Db_Table::collectionFactory('Blog_Model_DbTable_Blog_I18n');
                    if(count($moduleLangs) > 0){
                        foreach($moduleLangs as $lang){
                            if(isset($postParams['blog_i18n'][$lang['id']])){
                                $newBlogI18nRow = $i18nCollection->createItem();
                                $newBlogI18nRow->setAttributes($postParams['blog_i18n'][$lang['id']]);
                                $newBlogI18nRow->setLangId($lang['id']);
                                $newBlogI18nRow->setBlogId($newBlogRow->getId());
                                $i18nCollection->addItem($newBlogI18nRow);
                            }
                        }
                        $i18nCollection->save();
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
     * Edit blog data
     */
    public function updateBlogAction()
    {
        $this->view->pageDescription = 'Edit blog';
        $this->view->headTitle($this->view->pageDescription);
        $blogTable = new Blog_Model_DbTable_Blog();
        // Get blog content
        $blogTable->find($this->_request->getParam('id'));
        $currentBlog = $blogTable->getCollection()->getFirstItem();
        if($currentBlog){
            $form = new Blog_Form_Blog();
            $form->setIsUpdate(true);
            $form->setBlogTypes($blogTable->getBlogTypes());
            $form->setCurrentBlogType($currentBlog->getType());
            $form->compose();
            $i18nBlog = $currentBlog->findDependentRowset('Blog_Model_DbTable_Blog_I18n');
            $i18nBlogArray = $i18nBlog->toArray();
            $formData = $currentBlog->toArray();
            foreach($i18nBlogArray as $row){
                $formData['lang_' . $row['lang_id']] = $row;
            }
            $formData = (! $this->_request->isPost()) ? $formData : $this->_request->getPost();
            $form->populate($formData);
            if($this->_request->isPost()){
                $postParams = $this->_request->getPost('blog');
                if(isset($postParams['delete_blog'])){
                    // Delete blog
                    if($blogTable->delete()){
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
                    $currentBlog->setAttributes($postParams);
                    $currentBlog->setDateUpdated(Vendor_Helper_Date::now());
                    if(null == ($fancy_url = $postParams['fancy_url'])){
                        $firstLangKey = current(array_keys($postParams['blog_i18n']));
                        $fancy_url = isset($postParams['blog_i18n'][$this->_getDefaultSiteLanguageId()]['title']) ? $postParams['blog_i18n'][$this->_getDefaultSiteLanguageId()]['title'] : $postParams['blog_i18n'][$firstLangKey]['title'];
                    }
                    $currentBlog->setFancyUrl(Vendor_Helper_Text::fancy_url($fancy_url));
                    $currentBlog->setType($postParams['type']);
                    // Update blog
                    if($currentBlog->save()){
                        $moduleLangs = App::i18n()->getModuleLanguages();
                        if(count($moduleLangs) > 0){
                        $i18nBlog->rewind();
                            foreach($moduleLangs as $lang){
                                if(isset($postParams['blog_i18n'][$lang['id']])){
                                    $currentBlogI18n = $i18nBlog->current();
                                    $currentBlogI18n->setAttributes($postParams['blog_i18n'][$lang['id']]);
                                    $currentBlogI18n->setLangId($lang['id']);
                                    $currentBlogI18n->setBlogId($currentBlog->getId());                                    
                                    $currentBlogI18n->save();
                                    $i18nBlog->next();
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
        $blogTable = new Blog_Model_DbTable_Blog();
        $blogTable->find($this->_request->getParam('id', 0));
        if(! $blogTable->getCollection()->getFirstItem()){
            throw new App_Exception('Page not found');
        }
        else{
            if($blogTable->delete()){
                $this->_helper->messages('Blog deleted successfully', 'success', true);
            }
        }
        $this->_redirect('blog/admin/manage-blogs');
    }
}

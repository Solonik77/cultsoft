<?php

class ManagestaticpagesController extends ESGController {
    const PAGE_SIZE = 10;

    public $defaultAction = 'admin';
    public $crumbs = array();
    private $_model;

    /**
    * Manages all static pages
    */
    public function actionAdmin()
    {
        $this->processAdminCommand();
        $criteria = new CDbCriteria;
        $pages = new CPagination(static_pages::model()->with('static_pages_content', 'users')->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('static_pages');
        $sort->applyOrder($criteria);

        $models = static_pages::model()->with('static_pages_content', 'users')->findAll($criteria);

        $this->setPageTitle('Управление статичными страницами');
        $this->crumbs = array(array('name' => $this->getPageTitle()));
        $this->render('admin', array('models' => $models,
                'pages' => $pages,
                'sort' => $sort,
                ));
    }

    /**
    * Creates a new Static Page.
    * If creation is successful, the browser will be redirected to the 'show' page.
    */
    public function actionCreate()
    {
        // Create model for base static page data fields
        $modelStaticPages = new static_pages;
        // Create model for static page localized data fields
        $modelStaticPagesContent = array();
        foreach($this->websiteLanguages as $languageCode => $value) {
            $modelStaticPagesContent[$languageCode] = new static_pages_content;
        }
        // Do post query
        if (isset($_POST['static_pages'])) {
            $_POST['static_pages']['user_id'] = yii::app()->user->id;
            $modelStaticPages->attributes = $_POST['static_pages'];
            $modelStaticPages->date_created = new CDbExpression('NOW()');
            $modelStaticPages->date_updated = new CDbExpression('NOW()');
            // Validate data for static_pages
            if ($modelStaticPages->validate()) {
                $valid = true;
                // Validate data for static_pages_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelStaticPagesContent[$languageCode]->attributes = $_POST['static_pages_Content'][$languageCode];
                    $valid = $valid && $modelStaticPagesContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['static_pages']['fancy_url'])) {
                    $modelStaticPages->fancy_url = text::fancyUrl($modelStaticPagesContent['uk']->title);
                } else {
                    $modelStaticPages->fancy_url = text::fancyUrl($modelStaticPages->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelStaticPages->save() == true)) {
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelStaticPagesContent[$languageCode]->page_id = $modelStaticPages->id;
                        $modelStaticPagesContent[$languageCode]->lang = $languageCode;
                        $modelStaticPagesContent[$languageCode]->save();
                    }
                    $db = Yii::app()->db;
                    $db->createCommand("UPDATE `files_for_content` SET `element_id` = '" . $modelStaticPages->id . "' WHERE `element_id` = '0'")->execute();
                    Yii::app()->user->setFlash('success', "Страница '" . $_POST['static_pages_Content']['uk']['title'] . "' успешно создана");
                    // Redirect to page viewer
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Создание страницы');
        $this->crumbs = array(array('name' => 'Управление статичными страницами', 'url' => array('/admin/Managestaticpages/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('create', array('modelStaticPages' => $modelStaticPages, 'modelStaticPagesContent' => $modelStaticPagesContent, 'attachedFilesCount' => $this->_getAttachedFilesCount(), 'attachedFiles' => $this->_getAttachedFiles()));
    }

    /**
    * Updates a particular model.
    * If update is successful, the browser will be redirected to the 'show' page.
    */
    public function actionUpdate()
    {
        $this->processAdminCommand();
        // Create model for base static page data fields
        $modelStaticPages = $this->loadStaticPages();
        // Create model for static page localized data fields
        $modelStaticPagesContent = array();
        foreach($modelStaticPages->static_pages_content as $value) {
            $modelStaticPagesContent[$value->lang] = $value;
        }
        // Do post query
        if (isset($_POST['static_pages'])) {
            $_POST['static_pages']['user_id'] = yii::app()->user->id;
            $modelStaticPages->attributes = $_POST['static_pages'];
            // Set updated date
            $modelStaticPages->date_updated = new CDbExpression('NOW()');
            // Validate data for static_pages
            if ($modelStaticPages->validate()) {
                $valid = true;
                // Validate data for static_pages_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelStaticPagesContent[$languageCode]->attributes = $_POST['static_pages_Content'][$languageCode];
                    $valid = $valid && $modelStaticPagesContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['static_pages']['fancy_url'])) {
                    $modelStaticPages->fancy_url = text::fancyUrl($modelStaticPagesContent['uk']->title);
                } else {
                    $modelStaticPages->fancy_url = text::fancyUrl($modelStaticPages->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelStaticPages->save() == true)) {
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelStaticPagesContent[$languageCode]->page_id = $modelStaticPages->id;
                        $modelStaticPagesContent[$languageCode]->lang = $languageCode;
                        $modelStaticPagesContent[$languageCode]->save();
                    }
                    if (isset($_POST['sitetreeParent'])) {

						$treeParent = sitetree::model()->findByPK(intval($_POST['sitetreeParent']));
                        $currentPage = sitetree::model()->find('page_id = :page_id', array(':page_id' => $modelStaticPages->id));

						if ($treeParent) {
							if (!$currentPage) {
                                $newNode = new sitetree;
                                $newNode->page_id = $modelStaticPages->id;
                                $newNode->element_type = 'static_page';
                                $treeParent->appendChild($newNode);
                            } else {
                                //  @todo change position
                            }
                        }
                    }
                    Yii::app()->user->setFlash('success', "Изменения страницы '" . $_POST['static_pages_Content']['uk']['title'] . "' сохранены успешно");
                    // Redirect to pages list
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Редактирование страницы ' . $modelStaticPagesContent['uk']->title);
        $this->crumbs = array(array('name' => 'Управление статичными страницами', 'url' => array('/admin/Managestaticpages/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('update', array('modelStaticPages' => $modelStaticPages, 'modelStaticPagesContent' => $modelStaticPagesContent, 'attachedFilesCount' => $this->_getAttachedFilesCount(), 'attachedFiles' => $this->_getAttachedFiles()));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadStaticPages()->delete();
            $this->redirect(array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /*
	* 	AJAX action, adding files for page
	*/
    public function actionAttachFile()
    {
        $params = Yii::app()->request;
        if ($params->getIsAjaxRequest()) {
            $this->_attachFile($params->getPost('elementType'), intval($params->getPost('elementId', 0)), $params->getPost('fileUrl'));
            echo $this->_getAttachedFiles();
            return;
        }
    }

    public function actionDetachFile()
    {
        $params = Yii::app()->request;
        if ($params->getIsAjaxRequest()) {
            $this->_detachFile(intval($params->getPost('fileId', 0)));
            echo $this->_getAttachedFiles();
            return;
        }
    }

    public function actionGetAttachedFilesCount()
    {
        $params = Yii::app()->request;
        if ($params->getIsAjaxRequest()) {
            echo $this->_getAttachedFilesCount(intval($params->getPost('id', 0)));
            return;
        }
    }

    public function actionUpdateFileData()
    {
        $params = Yii::app()->request;
        if ($params->getIsAjaxRequest()) {
            $this->_updateFile(intval($params->getPost('fileId', 0)), $params->getPost('lang', 'uk'), $params->getPost('fileName'), $params->getPost('description'));
            echo $this->_getAttachedFiles();
            return;
        }
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    *
    * @param integer $ the primary key value. Defaults to null, meaning using the 'id' GET variable
    */
    public function loadStaticPages($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = static_pages::model()->with('static_pages_content')->findbyPk($id !== null ? $id : $_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

    protected function _getAttachedFilesCount($elementId = null)
    {
        return files_for_content::model()->count('element_id = :elementId', array(':elementId' => ($elementId) ? $elementId : Yii::app()->request->getParam('id', 0)));
    }

    protected function _attachFile($elementType, $elementId, $fileUrl)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'element_type = :elementType AND element_id = :elementId AND file_url = :fileUrl';
        $criteria->params = array(':elementId' => $elementId, ':elementType' => $elementType, ':fileUrl' => $fileUrl);
        $files = files_for_content::model()->find($criteria);
        if (!$files) {
            if (file_exists(DOC_ROOT . $fileUrl)) {
                $file = new files_for_content;
                $file->user_id = yii::app()->user->id;
                $file->date_attach = new CDbExpression('NOW()');
                $file->file_size = filesize(DOC_ROOT . $fileUrl);
                $file->file_url = trim(str_replace('\\', '/', $fileUrl));
                $file->element_id = $elementId;
                $file->element_type = $elementType;

                $mimes = include APP_PATH . 'data/mimes.php';
                $fileExt = end(explode('.', $fileUrl));
                $fileMime = 'unknown';
                if (isset($mimes[$fileExt])) {
                    $fileMime = current($mimes[$fileExt]);
                }
                $file->file_type = $fileMime;
                if ($file->save()) {
                    foreach($this->websiteLanguages as $key => $value):
                    $dfile = new files_for_content_description;
                    $dfile->file_id = $file->id;
                    $dfile->lang = $key;
                    $dfile->save();
                    endforeach;
                    return true;
                }
            }
        }
        return false;
    }

    protected function _detachFile($id)
    {
        $file = files_for_content::model()->findByPk($id);
        if ($file) {
            $file->delete();
        }
    }

    protected function _getAttachedFiles()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "element_type = 'static_page' AND element_id = :elementId" ;
        $criteria->params = array(':elementId' => Yii::app()->request->getParam('id', 0));
        $files = files_for_content::model()->with('files_for_content_description')->findAll($criteria);
        $html = '<table>';
        if ($files) {
            $html .= '<tr><th>#</th><th>Название для файла</th><th>Описание</th>
		<th>Тип</th>
		<th>Размер</th>
		<th>Имя</th><th>Действия</th></tr>';

            $i = 1;
            foreach($files as $file) {
                $html .= '<tr>';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td>';

                $i = 0;
                foreach($this->websiteLanguages as $key => $value):
                $html .= '<br/><img src="' . Yii::app()->request->getBaseUrl(true) . "/images/lang_icons/$key.gif\" /> <br />";
                $html .= '<input type="text" id="at_file_name_' . $key . '_' . $file->id . '" name="at_file_name_'
                 . $file->id . '" value="' . $file->files_for_content_description[$i]->file_name . '" />';
                $i++;
                endforeach;

                $html .= '</td><td>';

                $i = 0;
                foreach($this->websiteLanguages as $key => $value):
                $html .= '<br /><img src="' . Yii::app()->request->getBaseUrl(true) . "/images/lang_icons/$key.gif\" /> <br />";
                $html .= '<input type="text" id="at_file_description_' . $key . '_' . $file->id . '" name="at_file_description_'
                 . $file->id . '" value="' . $file->files_for_content_description[$i]->description . '" /><br />';
                $i++;
                endforeach;

                $html .= '</td><td>' . $file->file_type . '</td>
				<td>' . format::size($file->file_size) . '</td>
				<td>' . end(explode('/', $file->file_url)) . '</td>
				<td nowrap><a href="#" onclick="saveFileData(\'' . $file->id . '\'); return false;">Сохранить изменения</a> <br /> <a href="#" onclick="detachFile(\'' . $file->id . '\'); return false;">Удалить из списка</a></td>
				';
                $html .= '<tr>';
                $i++;
            }
        } else {
            $html .= '<tr><th>Файлы к странице пока не прикреплялись</th></tr>';
        }
        $html .= '</table>';
        return $html;
    }

    protected function _updateFile($id, $lang, $fileName, $description)
    {
        $file = files_for_content_description::model()->find('file_id = :fileID AND lang = :lang', array(':fileID' => $id, ':lang' => $lang));
        if ($file) {
            $file->file_name = $fileName;
            $file->description = $description;
            $file->save();
        }
    }

    /**
    * Executes any command triggered on the admin page.
    */
    protected function processAdminCommand()
    {
        if (isset($_POST['command'], $_POST['id']) && $_POST['command'] === 'delete') {
            $this->loadStaticPages($_POST['id'])->delete();
            Yii::app()->user->setFlash('success', "Страница удалена");
            // reload the current page to avoid duplicated delete actions
            $this->redirect(array('admin'));
        }
    }

    /**
    *
    * @return array action filters
    */
    public function filters()
    {
        return array('accessControl');
    }

    /**
    * Specifies the access control rules.
    * This method is used by the 'accessControl' filter.
    *
    * @return array access control rules
    */
    public function accessRules()
    {
        return array(
            array('allow', 'roles' => array('admin')),
            array('deny', 'users' => array('*')),
            );
    }
}

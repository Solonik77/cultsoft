<?php

class ManagepartnersController extends ESGController {
    const PAGE_SIZE = 10;

    public $defaultAction = 'admin';
    public $crumbs = array();
    private $_model;

    /**
    * Manages all partnerss
    */
    public function actionAdmin()
    {
        $this->processAdminCommand();
        $criteria = new CDbCriteria;
        $pages = new CPagination(partners::model()->with('partners_content', 'users')->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('partners');
        $sort->applyOrder($criteria);

        $models = partners::model()->with('partners_content', 'users')->findAll($criteria);

        $this->setPageTitle('Управление партнёрами');
        $this->crumbs = array(array('name' => $this->getPageTitle()));
        $this->render('admin', array('models' => $models,
                'pages' => $pages,
                'sort' => $sort,
                ));
    }

    /**
    * Creates a partners.
    */
    public function actionCreate()
    {
        // Create model for base partners data fields
        $modelpartners = new partners;
        // Create model for partners localized data fields
        $modelpartnersContent = array();
        foreach($this->websiteLanguages as $languageCode => $value) {
            $modelpartnersContent[$languageCode] = new partners_content;
        }
        // Do post query
        if (isset($_POST['partners'])) {
            $_POST['partners']['user_id'] = yii::app()->user->id;
            $modelpartners->attributes = $_POST['partners'];
            $modelpartners->date_created = new CDbExpression('NOW()');
            $modelpartners->date_updated = new CDbExpression('NOW()');
            $modelpartners->logo = CUploadedFile::getInstance($modelpartners, 'logo');
            // Validate data for partners
            if ($modelpartners->validate()) {
                $valid = true;
                // Validate data for partners_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelpartnersContent[$languageCode]->attributes = $_POST['partners_content'][$languageCode];
                    $valid = $valid && $modelpartnersContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['partners']['fancy_url'])) {
                    $modelpartners->fancy_url = text::fancyUrl($modelpartnersContent['uk']->name);
                } else {
                    $modelpartners->fancy_url = text::fancyUrl($modelpartners->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelpartners->save() == true)) {
                    if ($modelpartners->logo) {
                        $this->processLogoFile($modelpartners->logo->getTempName(), $modelpartners->id);
                    }
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelpartnersContent[$languageCode]->partners_id = $modelpartners->id;
                        $modelpartnersContent[$languageCode]->lang = $languageCode;
                        $modelpartnersContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Партнёр '" . $_POST['partners_content']['uk']['name'] . "' успешно занесён в базу");
                    // Redirect to page viewer
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Добавление партнёра');
        $this->crumbs = array(array('name' => 'Управление партнёрами', 'url' => array('/admin/Managepartners/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('create', array('modelpartners' => $modelpartners, 'modelpartnersContent' => $modelpartnersContent));
    }

    /**
    * Updates a particular model.
    */
    public function actionUpdate()
    {
        $this->processAdminCommand();
        // Create model for base partners data fields
        $modelpartners = $this->loadpartners();
        // Create model for partners localized data fields
        $modelpartnersContent = array();
        foreach($modelpartners->partners_content as $value) {
            $modelpartnersContent[$value->lang] = $value;
        }
        // Do post query
        if (isset($_POST['partners'])) {
            $_POST['partners']['user_id'] = yii::app()->user->id;
            $modelpartners->attributes = $_POST['partners'];
            // Set updated date
            $modelpartners->date_updated = new CDbExpression('NOW()');
            $modelpartners->logo = CUploadedFile::getInstance($modelpartners, 'logo');
            // Validate data for partners
            if ($modelpartners->validate()) {
                $valid = true;
                // Validate data for partners_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelpartnersContent[$languageCode]->attributes = $_POST['partners_content'][$languageCode];
                    $valid = $valid && $modelpartnersContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['partners']['fancy_url'])) {
                    $modelpartners->fancy_url = text::fancyUrl($modelpartnersContent['uk']->name);
                } else {
                    $modelpartners->fancy_url = text::fancyUrl($modelpartners->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelpartners->save() == true)) {
                    if ($modelpartners->logo) {
                        $this->processLogoFile($modelpartners->logo->getTempName(), $modelpartners->id);
                    }

                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelpartnersContent[$languageCode]->partners_id = $modelpartners->id;
                        $modelpartnersContent[$languageCode]->lang = $languageCode;
                        $modelpartnersContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Изменения в данных партнёра'" . $_POST['partners_content']['uk']['name'] . "' сохранены успешно");
                    // Redirect to pages list
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Редактирование данных о партнёре ' . $modelpartnersContent['uk']->name);
        $this->crumbs = array(array('name' => 'Управление партнёрами', 'url' => array('/admin/Managepartners/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('update', array('modelpartners' => $modelpartners, 'modelpartnersContent' => $modelpartnersContent));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadpartners()->delete();
            $this->redirect(array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /*
	* Convert and save photo image
	*/
    public function processLogoFile($originalFile, $recordId)
    {
        $image = Yii::app()->image->load($originalFile);
        $image->resize(400, 100)->quality(90);
        $image->save(Yii::app()->params['storage']['partner_logos'] . $recordId . '.jpg');
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    *
    * @param integer $ the primary key value. Defaults to null, meaning using the 'id' GET variable
    */
    public function loadpartners($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = partners::model()->with('partners_content')->findbyPk($id !== null ? $id : $_GET['id']);
            if ($this->_model === null)
                throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $this->_model;
    }

    /**
    * Executes any command triggered on the admin page.
    */
    protected function processAdminCommand()
    {
        if (isset($_POST['command'], $_POST['id']) && $_POST['command'] === 'delete') {
            $this->loadpartners($_POST['id'])->delete();
            Yii::app()->user->setFlash('success', "Партнёр удален");
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

<?php

class ManageteamController extends ESGController {
    const PAGE_SIZE = 10;

    public $defaultAction = 'admin';
    public $crumbs = array();
    private $_model;

    /**
    * Manages all teams
    */
    public function actionAdmin()
    {
        $this->processAdminCommand();
        $criteria = new CDbCriteria;
        $pages = new CPagination(team::model()->with('team_content', 'users')->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('team');
        $sort->applyOrder($criteria);

        $models = team::model()->with('team_content', 'users')->findAll($criteria);

        $this->setPageTitle('Управление информацией о команде');
        $this->crumbs = array(array('name' => $this->getPageTitle()));
        $this->render('admin', array('models' => $models,
                'pages' => $pages,
                'sort' => $sort,
                ));
    }

    /**
    * Creates a team.
    */
    public function actionCreate()
    {
        // Create model for base team data fields
        $modelteam = new team;
        // Create model for team localized data fields
        $modelteamContent = array();
        foreach($this->websiteLanguages as $languageCode => $value) {
            $modelteamContent[$languageCode] = new team_content;
        }
        // Do post query
        if (isset($_POST['team'])) {
            $_POST['team']['user_id'] = yii::app()->user->id;
            $modelteam->attributes = $_POST['team'];
            $modelteam->date_created = new CDbExpression('NOW()');
            $modelteam->date_updated = new CDbExpression('NOW()');
            $modelteam->photo = CUploadedFile::getInstance($modelteam, 'photo');
            // Validate data for team
            if ($modelteam->validate()) {
                $valid = true;
                // Validate data for team_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelteamContent[$languageCode]->attributes = $_POST['team_content'][$languageCode];
                    $valid = $valid && $modelteamContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['team']['fancy_url'])) {
                    $modelteam->fancy_url = text::fancyUrl($modelteamContent['uk']->name);
                } else {
                    $modelteam->fancy_url = text::fancyUrl($modelteam->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelteam->save() == true)) {
                    if($modelteam->photo) {
                        $this->processPhotoFile($modelteam->photo->getTempName(), $modelteam->id);
                    }
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelteamContent[$languageCode]->team_id = $modelteam->id;
                        $modelteamContent[$languageCode]->lang = $languageCode;
                        $modelteamContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Сотрудник '" . $_POST['team_content']['uk']['name'] . "' добавлен");
                    // Redirect to page viewer
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Добавление нового сотрудника');
        $this->crumbs = array(array('name' => 'Управление информацией о команде', 'url' => array('/admin/Manageteam/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('create', array('modelteam' => $modelteam, 'modelteamContent' => $modelteamContent));
    }

    /**
    * Updates a particular model.
    */
    public function actionUpdate()
    {
        $this->processAdminCommand();
        // Create model for base team data fields
        $modelteam = $this->loadteam();
        // Create model for team localized data fields
        $modelteamContent = array();
        foreach($modelteam->team_content as $value) {
            $modelteamContent[$value->lang] = $value;
        }
        // Do post query
        if (isset($_POST['team'])) {
            $_POST['team']['user_id'] = yii::app()->user->id;
            $modelteam->attributes = $_POST['team'];
            // Set updated date
            $modelteam->date_updated = new CDbExpression('NOW()');
            $modelteam->photo = CUploadedFile::getInstance($modelteam, 'photo');
            // Validate data for team
            if ($modelteam->validate()) {
                $valid = true;
                // Validate data for team_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelteamContent[$languageCode]->attributes = $_POST['team_content'][$languageCode];
                    $valid = $valid && $modelteamContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['team']['fancy_url'])) {
                    $modelteam->fancy_url = text::fancyUrl($modelteamContent['uk']->name);
                } else {
                    $modelteam->fancy_url = text::fancyUrl($modelteam->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelteam->save() == true)) {
					if($modelteam->photo) {
                        $this->processPhotoFile($modelteam->photo->getTempName(), $modelteam->id);
                    }
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelteamContent[$languageCode]->team_id = $modelteam->id;
                        $modelteamContent[$languageCode]->lang = $languageCode;
                        $modelteamContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Изменения данных о сотруднике '" . $_POST['team_content']['uk']['name'] . "' сохранены успешно");
                    // Redirect to pages list
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Редактирование данных о сотруднике ' . $modelteamContent['uk']->name);
        $this->crumbs = array(array('name' => 'Управление информацией о команде', 'url' => array('/admin/Manageteam/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('update', array('modelteam' => $modelteam, 'modelteamContent' => $modelteamContent));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadteam()->delete();
            $this->redirect(array('admin'));
        } else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }}

    /*
	* Convert and save photo image
	*/
    public function processPhotoFile($originalFile, $recordId)
    {
        $image = Yii::app()->image->load($originalFile);
        $image->resize(400, 100)->quality(90);
        $image->save(Yii::app()->params['storage']['team_photos'] . $recordId . '.jpg');
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    *
    * @param integer $ the primary key value. Defaults to null, meaning using the 'id' GET variable
    */
    public function loadteam($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = team::model()->with('team_content')->findbyPk($id !== null ? $id : $_GET['id']);
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
            $this->loadteam($_POST['id'])->delete();
            Yii::app()->user->setFlash('success', "Сотрудник удален из списка");
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

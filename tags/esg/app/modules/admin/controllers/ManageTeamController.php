<?php

class ManageTeamController extends ESGController {
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
        $pages = new CPagination(Team::model()->with('team_content', 'users')->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('Team');
        $sort->applyOrder($criteria);

        $models = Team::model()->with('team_content', 'users')->findAll($criteria);

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
        $modelTeam = new Team;
        // Create model for team localized data fields
        $modelTeamContent = array();
        foreach($this->websiteLanguages as $languageCode => $value) {
            $modelTeamContent[$languageCode] = new Team_content;
        }
        // Do post query
        if (isset($_POST['Team'])) {
            $_POST['Team']['user_id'] = yii::app()->user->id;
            $modelTeam->attributes = $_POST['Team'];
            $modelTeam->date_created = new CDbExpression('NOW()');
            $modelTeam->date_updated = new CDbExpression('NOW()');
            $modelTeam->photo = CUploadedFile::getInstance($modelTeam, 'photo');
            // Validate data for Team
            if ($modelTeam->validate()) {
                $valid = true;
                // Validate data for Team_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelTeamContent[$languageCode]->attributes = $_POST['Team_content'][$languageCode];
                    $valid = $valid && $modelTeamContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['Team']['fancy_url'])) {
                    $modelTeam->fancy_url = text::fancyUrl($modelTeamContent['uk']->name);
                } else {
                    $modelTeam->fancy_url = text::fancyUrl($modelTeam->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelTeam->save() == true)) {
                    if($modelTeam->photo) {
                        $this->processPhotoFile($modelTeam->photo->getTempName(), $modelTeam->id);
                    }
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelTeamContent[$languageCode]->team_id = $modelTeam->id;
                        $modelTeamContent[$languageCode]->lang = $languageCode;
                        $modelTeamContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Сотрудник '" . $_POST['Team_content']['uk']['name'] . "' добавлен");
                    // Redirect to page viewer
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Добавление нового сотрудника');
        $this->crumbs = array(array('name' => 'Управление информацией о команде', 'url' => array('/admin/manageTeam/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('create', array('modelTeam' => $modelTeam, 'modelTeamContent' => $modelTeamContent));
    }

    /**
    * Updates a particular model.
    */
    public function actionUpdate()
    {
        $this->processAdminCommand();
        // Create model for base team data fields
        $modelTeam = $this->loadTeam();
        // Create model for team localized data fields
        $modelTeamContent = array();
        foreach($modelTeam->team_content as $value) {
            $modelTeamContent[$value->lang] = $value;
        }
        // Do post query
        if (isset($_POST['Team'])) {
            $_POST['Team']['user_id'] = yii::app()->user->id;
            $modelTeam->attributes = $_POST['Team'];
            // Set updated date
            $modelTeam->date_updated = new CDbExpression('NOW()');
            $modelTeam->photo = CUploadedFile::getInstance($modelTeam, 'photo');
            // Validate data for Team
            if ($modelTeam->validate()) {
                $valid = true;
                // Validate data for Team_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelTeamContent[$languageCode]->attributes = $_POST['Team_content'][$languageCode];
                    $valid = $valid && $modelTeamContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['Team']['fancy_url'])) {
                    $modelTeam->fancy_url = text::fancyUrl($modelTeamContent['uk']->name);
                } else {
                    $modelTeam->fancy_url = text::fancyUrl($modelTeam->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelTeam->save() == true)) {
					if($modelTeam->photo) {
                        $this->processPhotoFile($modelTeam->photo->getTempName(), $modelTeam->id);
                    }
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelTeamContent[$languageCode]->team_id = $modelTeam->id;
                        $modelTeamContent[$languageCode]->lang = $languageCode;
                        $modelTeamContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Изменения данных о сотруднике '" . $_POST['Team_content']['uk']['name'] . "' сохранены успешно");
                    // Redirect to pages list
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Редактирование данных о сотруднике ' . $modelTeamContent['uk']->name);
        $this->crumbs = array(array('name' => 'Управление информацией о команде', 'url' => array('/admin/manageTeam/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('update', array('modelTeam' => $modelTeam, 'modelTeamContent' => $modelTeamContent));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadTeam()->delete();
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
    public function loadTeam($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = Team::model()->with('team_content')->findbyPk($id !== null ? $id : $_GET['id']);
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
            $this->loadTeam($_POST['id'])->delete();
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

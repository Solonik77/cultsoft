<?php

class ManagenewsController extends ESGController {
    const PAGE_SIZE = 10;

    public $defaultAction = 'admin';
    public $crumbs = array();
    private $_model;

    /**
    * Manages all newss
    */
    public function actionAdmin()
    {
        $this->processAdminCommand();
        $criteria = new CDbCriteria;
        $pages = new CPagination(news::model()->with('news_content', 'users')->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('news');
        $sort->applyOrder($criteria);

        $models = news::model()->with('news_content', 'users')->findAll($criteria);

        $this->setPageTitle('Управление новостями');
        $this->crumbs = array(array('name' => $this->getPageTitle()));
        $this->render('admin', array('models' => $models,
                'pages' => $pages,
                'sort' => $sort,
                ));
    }

    /**
    * Creates a news.
    */
    public function actionCreate()
    {
        // Create model for base news data fields
        $modelnews = new news;
        $modelnews->date_publish = date::now();
        // Create model for news localized data fields
        $modelnewsContent = array();
        foreach($this->websiteLanguages as $languageCode => $value) {
            $modelnewsContent[$languageCode] = new news_content;
        }
        // Do post query
        if (isset($_POST['news'])) {
            $_POST['news']['user_id'] = yii::app()->user->id;
            $modelnews->attributes = $_POST['news'];
            $modelnews->date_created = new CDbExpression('NOW()');
            $modelnews->date_updated = new CDbExpression('NOW()');
            // Validate data for news
            if ($modelnews->validate()) {
                $valid = true;
                // Validate data for news_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelnewsContent[$languageCode]->attributes = $_POST['news_content'][$languageCode];
                    $valid = $valid && $modelnewsContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['news']['fancy_url'])) {
                    $modelnews->fancy_url = text::fancyUrl($modelnewsContent['uk']->title);
                } else {
                    $modelnews->fancy_url = text::fancyUrl($modelnews->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelnews->save() == true)) {
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelnewsContent[$languageCode]->news_id = $modelnews->id;
                        $modelnewsContent[$languageCode]->lang = $languageCode;
                        $modelnewsContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Новость '" . $_POST['news_content']['uk']['title'] . "' успешно создана");
                    // Redirect to page viewer
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Создание новости');
        $this->crumbs = array(array('name' => 'Управление новостями', 'url' => array('/admin/Managenews/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('create', array('modelnews' => $modelnews, 'modelnewsContent' => $modelnewsContent));
    }

    /**
    * Updates a particular model.
    */
    public function actionUpdate()
    {
        $this->processAdminCommand();
        // Create model for base news data fields
        $modelnews = $this->loadnews();
        // Create model for news localized data fields
        $modelnewsContent = array();
        foreach($modelnews->news_content as $value) {
            $modelnewsContent[$value->lang] = $value;
        }
        // Do post query
        if (isset($_POST['news'])) {
            $_POST['news']['user_id'] = yii::app()->user->id;
            $modelnews->attributes = $_POST['news'];
            // Set updated date
            $modelnews->date_updated = new CDbExpression('NOW()');
            // Validate data for news
            if ($modelnews->validate()) {
                $valid = true;
                // Validate data for news_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelnewsContent[$languageCode]->attributes = $_POST['news_content'][$languageCode];
                    $valid = $valid && $modelnewsContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['news']['fancy_url'])) {
                    $modelnews->fancy_url = text::fancyUrl($modelnewsContent['uk']->title);
                } else {
                    $modelnews->fancy_url = text::fancyUrl($modelnews->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelnews->save() == true)) {
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelnewsContent[$languageCode]->news_id = $modelnews->id;
                        $modelnewsContent[$languageCode]->lang = $languageCode;
                        $modelnewsContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Изменения новости '" . $_POST['news_content']['uk']['title'] . "' сохранены успешно");
                    // Redirect to pages list
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Редактирование новости ' . $modelnewsContent['uk']->title);
        $this->crumbs = array(array('name' => 'Управление новостями', 'url' => array('/admin/Managenews/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('update', array('modelnews' => $modelnews, 'modelnewsContent' => $modelnewsContent));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadnews()->delete();
            $this->redirect(array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    *
    * @param integer $ the primary key value. Defaults to null, meaning using the 'id' GET variable
    */
    public function loadnews($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = news::model()->with('news_content')->findbyPk($id !== null ? $id : $_GET['id']);
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
            $this->loadnews($_POST['id'])->delete();
            Yii::app()->user->setFlash('success', "Новость удалена");
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

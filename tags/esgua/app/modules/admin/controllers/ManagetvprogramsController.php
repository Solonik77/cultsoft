<?php

class ManagetvprogramsController extends ESGController {
    const PAGE_SIZE = 10;

    public $defaultAction = 'admin';
    public $crumbs = array();
    private $_model;
    private $_promo_video;

    /**
    * Manages all tvprogramss
    */
    public function actionAdmin()
    {
        $this->processAdminCommand();
        $criteria = new CDbCriteria;
        $pages = new CPagination(tvprograms::model()->with('tvprograms_content', 'users')->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('tvprograms');
        $sort->applyOrder($criteria);

        $models = tvprograms::model()->with('tvprograms_content', 'users')->findAll($criteria);

        $this->setPageTitle('Управление телевизионными программами');
        $this->crumbs = array(array('name' => $this->getPageTitle()));
        $this->render('admin', array('models' => $models,
                'pages' => $pages,
                'sort' => $sort,
                ));
    }

    /**
    * Creates a tvprograms.
    */
    public function actionCreate()
    {
        // Create model for base tvprograms data fields
        $modeltvprograms = new tvprograms;
        // Create model for tvprograms localized data fields
        $modeltvprogramsContent = array();
        foreach($this->websiteLanguages as $languageCode => $value) {
            $modeltvprogramsContent[$languageCode] = new tvprograms_content;
        }
        // Do post query
        if (isset($_POST['tvprograms'])) {
            $_POST['tvprograms']['user_id'] = yii::app()->user->id;
            $modeltvprograms->attributes = $_POST['tvprograms'];
            $modeltvprograms->date_created = new CDbExpression('NOW()');
            $modeltvprograms->date_updated = new CDbExpression('NOW()');
            $this->_promo_video = $modeltvprograms->promo_video = CUploadedFile::getInstance($modeltvprograms, 'promo_video');
            // Validate data for tvprograms
            if ($modeltvprograms->validate()) {
                $valid = true;
                // Validate data for tvprograms_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modeltvprogramsContent[$languageCode]->attributes = $_POST['tvprograms_content'][$languageCode];
                    $valid = $valid && $modeltvprogramsContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['tvprograms']['fancy_url'])) {
                    $modeltvprograms->fancy_url = text::fancyUrl($modeltvprogramsContent['uk']->name);
                } else {
                    $modeltvprograms->fancy_url = text::fancyUrl($modeltvprograms->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modeltvprograms->save() == true)) {
                    if ($modeltvprograms->promo_video) {
                        $this->processPromoVideoFile($modeltvprograms->promo_video->getTempName(), $modeltvprograms->id);
                    }
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modeltvprogramsContent[$languageCode]->tvprograms_id = $modeltvprograms->id;
                        $modeltvprogramsContent[$languageCode]->lang = $languageCode;
                        $modeltvprogramsContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Партнёр '" . $_POST['tvprograms_content']['uk']['name'] . "' успешно занесён в базу");
                    // Redirect to page viewer
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Добавление программы');
        $this->crumbs = array(array('name' => 'Управление ТВ-программами', 'url' => array('/admin/Managetvprograms/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('create', array('modeltvprograms' => $modeltvprograms, 'modeltvprogramsContent' => $modeltvprogramsContent));
    }

    /**
    * Updates a particular model.
    */
    public function actionUpdate()
    {
        $this->processAdminCommand();
        // Create model for base tvprograms data fields
        $modeltvprograms = $this->loadtvprograms();
        // Create model for tvprograms localized data fields
        $modeltvprogramsContent = array();
        foreach($modeltvprograms->tvprograms_content as $value) {
            $modeltvprogramsContent[$value->lang] = $value;
        }
        // Do post query
        if (isset($_POST['tvprograms'])) {
            $_POST['tvprograms']['user_id'] = yii::app()->user->id;
            $modeltvprograms->attributes = $_POST['tvprograms'];
            // Set updated date
            $modeltvprograms->date_updated = new CDbExpression('NOW()');
            $this->_promo_video = $modeltvprograms->promo_video = CUploadedFile::getInstance($modeltvprograms, 'promo_video');
            // Validate data for tvprograms
            if ($modeltvprograms->validate()) {
                $valid = true;
                // Validate data for tvprograms_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modeltvprogramsContent[$languageCode]->attributes = $_POST['tvprograms_content'][$languageCode];
                    $valid = $valid && $modeltvprogramsContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['tvprograms']['fancy_url'])) {
                    $modeltvprograms->fancy_url = text::fancyUrl($modeltvprogramsContent['uk']->name);
                } else {
                    $modeltvprograms->fancy_url = text::fancyUrl($modeltvprograms->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modeltvprograms->save() == true)) {
                    if ($modeltvprograms->promo_video) {
                        $this->processPromoVideoFile($modeltvprograms->promo_video->getTempName(), $modeltvprograms->id);
                    }

                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modeltvprogramsContent[$languageCode]->tvprograms_id = $modeltvprograms->id;
                        $modeltvprogramsContent[$languageCode]->lang = $languageCode;
                        $modeltvprogramsContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Изменения в данных программы'" . $_POST['tvprograms_content']['uk']['name'] . "' сохранены успешно");
                    // Redirect to pages list
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Редактирование данных о партнёре ' . $modeltvprogramsContent['uk']->name);
        $this->crumbs = array(array('name' => 'Управление телевизионными программами', 'url' => array('/admin/Managetvprograms/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('update', array('modeltvprograms' => $modeltvprograms, 'modeltvprogramsContent' => $modeltvprogramsContent));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadtvprograms()->delete();
            $this->redirect(array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /*
	* Convert and save photo image
	*/
    public function processPromoVideoFile($originalFile, $recordId)
    {
        if ($this->_promo_video) {
            $videoFile = Yii::app()->params['storage']['tv_program_promo_videos'] . $recordId . '.flv';
            $this->_promo_video->saveAs($videoFile);
            if (!class_exists('ffmpeg_movie', false)) {
                return false;
            }
            try {
                $movie = new ffmpeg_movie($videoFile);
                $frame = false;
                $i = 0;
                while (!$frame && $i < 100) {
                    $i++;
                    $frame = $movie->getFrame($i);
                }
                if ($frame) {
                    $imageFile = Yii::app()->params['storage']['tv_program_images'] . 'promo/' . $recordId . '.jpg';
                    @imagejpeg($frame->toGDImage(), $imageFile);
                    @imagedestroy($frame->toGDImage());
                    $image = Yii::app()->image->load($imageFile);
                    $image->resize(400, 100)->quality(90);
                    $image->save();
                }
                return true;
            }
            catch(Exception $e) {
                return false;
            }
        }
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    *
    * @param integer $ the primary key value. Defaults to null, meaning using the 'id' GET variable
    */
    public function loadtvprograms($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = tvprograms::model()->with('tvprograms_content')->findbyPk($id !== null ? $id : $_GET['id']);
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
            $this->loadtvprograms($_POST['id'])->delete();
            Yii::app()->user->setFlash('success', "Программа удалена");
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

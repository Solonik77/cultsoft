<?php

class ManageTVProgramsController extends ESGController {
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
        $pages = new CPagination(Tvprograms::model()->with('tvprograms_content', 'users')->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('Tvprograms');
        $sort->applyOrder($criteria);

        $models = Tvprograms::model()->with('tvprograms_content', 'users')->findAll($criteria);

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
        $modelTvprograms = new Tvprograms;
        // Create model for tvprograms localized data fields
        $modelTvprogramsContent = array();
        foreach($this->websiteLanguages as $languageCode => $value) {
            $modelTvprogramsContent[$languageCode] = new Tvprograms_content;
        }
        // Do post query
        if (isset($_POST['Tvprograms'])) {
            $_POST['Tvprograms']['user_id'] = yii::app()->user->id;
            $modelTvprograms->attributes = $_POST['Tvprograms'];
            $modelTvprograms->date_created = new CDbExpression('NOW()');
            $modelTvprograms->date_updated = new CDbExpression('NOW()');
            $this->_promo_video = $modelTvprograms->promo_video = CUploadedFile::getInstance($modelTvprograms, 'promo_video');
            // Validate data for Tvprograms
            if ($modelTvprograms->validate()) {
                $valid = true;
                // Validate data for Tvprograms_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelTvprogramsContent[$languageCode]->attributes = $_POST['Tvprograms_content'][$languageCode];
                    $valid = $valid && $modelTvprogramsContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['Tvprograms']['fancy_url'])) {
                    $modelTvprograms->fancy_url = text::fancyUrl($modelTvprogramsContent['uk']->name);
                } else {
                    $modelTvprograms->fancy_url = text::fancyUrl($modelTvprograms->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelTvprograms->save() == true)) {
                    if ($modelTvprograms->promo_video) {
                        $this->processPromoVideoFile($modelTvprograms->promo_video->getTempName(), $modelTvprograms->id);
                    }
                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelTvprogramsContent[$languageCode]->tvprograms_id = $modelTvprograms->id;
                        $modelTvprogramsContent[$languageCode]->lang = $languageCode;
                        $modelTvprogramsContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Партнёр '" . $_POST['Tvprograms_content']['uk']['name'] . "' успешно занесён в базу");
                    // Redirect to page viewer
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Добавление программы');
        $this->crumbs = array(array('name' => 'Управление ТВ-программами', 'url' => array('/admin/ManageTVPrograms/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('create', array('modelTvprograms' => $modelTvprograms, 'modelTvprogramsContent' => $modelTvprogramsContent));
    }

    /**
    * Updates a particular model.
    */
    public function actionUpdate()
    {
        $this->processAdminCommand();
        // Create model for base tvprograms data fields
        $modelTvprograms = $this->loadTvprograms();
        // Create model for tvprograms localized data fields
        $modelTvprogramsContent = array();
        foreach($modelTvprograms->tvprograms_content as $value) {
            $modelTvprogramsContent[$value->lang] = $value;
        }
        // Do post query
        if (isset($_POST['Tvprograms'])) {
            $_POST['Tvprograms']['user_id'] = yii::app()->user->id;
            $modelTvprograms->attributes = $_POST['Tvprograms'];
            // Set updated date
            $modelTvprograms->date_updated = new CDbExpression('NOW()');
            $this->_promo_video = $modelTvprograms->promo_video = CUploadedFile::getInstance($modelTvprograms, 'promo_video');
            // Validate data for Tvprograms
            if ($modelTvprograms->validate()) {
                $valid = true;
                // Validate data for Tvprograms_content
                foreach($this->websiteLanguages as $languageCode => $value) {
                    $modelTvprogramsContent[$languageCode]->attributes = $_POST['Tvprograms_content'][$languageCode];
                    $valid = $valid && $modelTvprogramsContent[$languageCode]->validate();
                }
                // Create fancy URL for SEO
                if (empty($_POST['Tvprograms']['fancy_url'])) {
                    $modelTvprograms->fancy_url = text::fancyUrl($modelTvprogramsContent['uk']->name);
                } else {
                    $modelTvprograms->fancy_url = text::fancyUrl($modelTvprograms->fancy_url);
                }
                // Save data in database
                if (($valid == true) and ($modelTvprograms->save() == true)) {
                    if ($modelTvprograms->promo_video) {
                        $this->processPromoVideoFile($modelTvprograms->promo_video->getTempName(), $modelTvprograms->id);
                    }

                    foreach($this->websiteLanguages as $languageCode => $value) {
                        $modelTvprogramsContent[$languageCode]->tvprograms_id = $modelTvprograms->id;
                        $modelTvprogramsContent[$languageCode]->lang = $languageCode;
                        $modelTvprogramsContent[$languageCode]->save();
                    }
                    Yii::app()->user->setFlash('success', "Изменения в данных программы'" . $_POST['Tvprograms_content']['uk']['name'] . "' сохранены успешно");
                    // Redirect to pages list
                    $this->redirect(array('admin'));
                }
            }
        }
        $this->setPageTitle('Редактирование данных о партнёре ' . $modelTvprogramsContent['uk']->name);
        $this->crumbs = array(array('name' => 'Управление телевизионными программами', 'url' => array('/admin/ManageTVPrograms/')),
            array('name' => $this->getPageTitle()));
        // Send models to view and form
        $this->render('update', array('modelTvprograms' => $modelTvprograms, 'modelTvprogramsContent' => $modelTvprogramsContent));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadTvprograms()->delete();
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
    public function loadTvprograms($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = Tvprograms::model()->with('tvprograms_content')->findbyPk($id !== null ? $id : $_GET['id']);
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
            $this->loadTvprograms($_POST['id'])->delete();
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

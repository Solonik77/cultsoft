<?php

class UsersController extends ESGController {
    const PAGE_SIZE = 10;

    /**
    *
    * @var string specifies the default action to be 'list'.
    */
    public $defaultAction = 'admin';

    /**
    *
    * @var CActiveRecord the currently loaded data model instance.
    */
    private $_model;

    /**
    *
    * @return array action filters
    */
    public function filters()
    {
        return array('accessControl', // perform access control for CRUD operations
            );
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
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'roles' => array('admin'),
                ),
            array('deny', // deny all users
                'users' => array('*'),
                ),
            );
    }

    /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'show' page.
    */
    public function actionCreate()
    {
        $model = new Users;
        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->save())
                $this->redirect(array('admin'));
        }
        $this->render('create', array('model' => $model));
    }

    /**
    * Updates a particular model.
    * If update is successful, the browser will be redirected to the 'show' page.
    */
    public function actionUpdate()
    {
        $model = $this->loadUsers();
        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->save())
                $this->redirect(array('admin'));
        }
        $this->render('update', array('model' => $model));
    }

    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'list' page.
    */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadUsers()->delete();
            $this->redirect(array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
    * Manages all models.
    */
    public function actionAdmin()
    {
        $this->processAdminCommand();

        $criteria = new CDbCriteria;

        $pages = new CPagination(Users::model()->count($criteria));
        $pages->pageSize = self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort = new CSort('Users');
        $sort->applyOrder($criteria);

        $models = Users::model()->findAll($criteria);

        $this->render('admin', array('models' => $models,
                'pages' => $pages,
                'sort' => $sort,
                ));
    }

    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    *
    * @param integer $ the primary key value. Defaults to null, meaning using the 'id' GET variable
    */
    public function loadUsers($id = null)
    {
        if ($this->_model === null) {
            if ($id !== null || isset($_GET['id']))
                $this->_model = Users::model()->findbyPk($id !== null ? $id : $_GET['id']);
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
            $this->loadUsers($_POST['id'])->delete();
            // reload the current page to avoid duplicated delete actions
            $this->refresh();
        }
    }
}
<?php

class MessagesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function init()
	{
		parent::init();
		Yii::app()->language = Yii::app()->session['Language']['UserLang'];
	}
	
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update','admin','delete'),
				'users'=>array('*'),
			),
			/*
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
							'actions'=>array('create','update'),
							'users'=>array('@'),
						),
						array('allow', // allow admin user to perform 'admin' and 'delete' actions
							'actions'=>array('admin','delete'),
							'users'=>array('admin'),
						),*/
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		Login::UserAuth('Messages','View');
		Login::ChkBuSess();	
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		Login::UserAuth('Messages','Create');
		Login::ChkBuSess();	
		$model=new Messages;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Messages']))
		{
			$_POST['Messages'] = CI_Security::ChkPost($_POST['Messages']);
			
			$model->attributes=$_POST['Messages'];
			
			$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
			$model->buid = $Buid;
			if($model->save())
				$this->redirect(array('view','id'=>$model->mid));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		Login::UserAuth('Messages','Update');
		Login::ChkBuSess();	
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Messages']))
		{
			$_POST['Messages'] = CI_Security::ChkPost($_POST['Messages']);
			
			$model->attributes=$_POST['Messages'];
			$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
			$model->buid = $Buid;
			if($model->save())
				$this->redirect(array('view','id'=>$model->mid));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		Login::UserAuth('Messages','Delete');
		Login::ChkBuSess();
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
		{
			// $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			if(isset($_POST['returnUrl'])){
				// $_POST['returnUrl'];
				$_POST['returnUrl'] = CI_Security::ChkPost($_POST['returnUrl']);
								
			}else{
				$this->redirect(array('admin'));
			}
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Login::UserAuth('Messages','Index');
		Login::ChkBuSess();
		$dataProvider=new CActiveDataProvider('Messages');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('Messages','Admin');
		Login::ChkBuSess();	
		$model=new Messages('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Messages']))
		{
			$_GET['Messages'] = CI_Security::ChkPost($_GET['Messages']);
			$model->attributes=$_GET['Messages'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Messages the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Messages::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Messages $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

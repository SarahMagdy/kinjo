<?php

class PdConfigController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','ApplyToCat','ajaxApplyToCat'),
				'users'=>array('*'),
			),
			// array('allow', // allow authenticated user to perform 'create' and 'update' actions
				// 'actions'=>array('create','update'),
				// 'users'=>array('@'),
			// ),
			// array('allow', // allow admin user to perform 'admin' and 'delete' actions
				// 'actions'=>array('admin','delete'),
				// 'users'=>array('admin'),
			// ),
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
		Login::UserAuth('PdConfig','View');
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
		Login::UserAuth('PdConfig','Create');
		Login::ChkBuSess();
		$model=new PdConfig;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PdConfig']))
		{
			$_POST['PdConfig'] = CI_Security::ChkPost($_POST['PdConfig']);
			
			$model->attributes = $_POST['PdConfig'];
			$model->conf_buid = Yii::app()->session['User']['UserBuid'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->cfg_id));
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
		Login::UserAuth('PdConfig','Update');
		Login::ChkBuSess();
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['PdConfig']))
		{
			$_POST['PdConfig'] = CI_Security::ChkPost($_POST['PdConfig']);
			
			$model->attributes=$_POST['PdConfig'];
			$model->conf_buid = Yii::app()->session['User']['UserBuid'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->cfg_id));
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
		Login::UserAuth('PdConfig','Delete');
		Login::ChkBuSess();
		$this->loadModel($id)->delete();
 
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
		{
			// $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			if(isset($_POST['returnUrl'])){
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
		Login::UserAuth('PdConfig','Index');
		Login::ChkBuSess();
		// $dataProvider=new CActiveDataProvider('PdConfig');
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('conf_buid', Yii::app()->session['User']['UserBuid']);
		$dataProvider = new CActiveDataProvider('PdConfig', array('criteria'=>$criteria));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('PdConfig','Admin');
		Login::ChkBuSess();
		$model=new PdConfig('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PdConfig']))
		{
			$_GET['PdConfig'] = CI_Security::ChkPost($_GET['PdConfig']);
			$model->attributes = $_GET['PdConfig'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PdConfig the loaded model
	 * @throws CHttpException
	 */
	
	public function actionApplyToCat($id)
	{
		//Login::UserAuth('PdConfig','ApplyToCat');
		//Login::ChkBuSess();
		$Data = array();
		$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;	
		//-----------------Cats
		$CatArr = array();
		$CatSQL = " SELECT * FROM catsub WHERE catsub_buid =".$Buid;
		$CatData = Yii::app()->db->createCommand($CatSQL)->queryAll();
		foreach($CatData AS $CatKey=>$CatRow){
			if($CatRow['parent_id'] > 0){
				$CatArr[$CatRow['parent_id']]['Subs'][$CatRow['csid']]['CatID'] = $CatRow['csid'];
				$CatArr[$CatRow['parent_id']]['Subs'][$CatRow['csid']]['CatN'] = $CatRow['title'];
			} else {
				$CatArr[$CatRow['csid']]['CatID'] = $CatRow['csid'];
				$CatArr[$CatRow['csid']]['CatN'] = $CatRow['title'];
			}
		}
		//-----------------Confs
		$ConfArr = array();
		$ConfType = '';
		
		$ChkSQL = " SELECT * FROM pd_config WHERE cfg_id =".$id;
		$ChkRow = Yii::app()->db->createCommand($ChkSQL)->queryRow();
		if(!empty($ChkRow)){
				
			$ChkRow['parent_id'] > 0?$ConfType = 'ch':$ConfType = 'par';
			if($ConfType == 'ch'){
				$ParSQL = " SELECT * FROM pd_config WHERE cfg_id =".$ChkRow['parent_id'];
				$ParRow = Yii::app()->db->createCommand($ParSQL)->queryRow();
				if(!empty($ParRow)){
					$ConfArr['ParID'] = $ParRow['cfg_id'];
					$ConfArr['ParN'] = $ParRow['name'];
					
					$ConfArr['Subs'][$ChkRow['cfg_id']]['SubID'] = $ChkRow['cfg_id'];
					$ConfArr['Subs'][$ChkRow['cfg_id']]['SubN'] = $ChkRow['name'];
				}
				
			}
			
			if($ConfType == 'par'){
				$ConfArr['ParID'] = $ChkRow['cfg_id'];
				$ConfArr['ParN'] = $ChkRow['name'];	
				$SubSQL = " SELECT * FROM pd_config WHERE parent_id =".$id;
			    $SubData = Yii::app()->db->createCommand($SubSQL)->queryAll();
				foreach ($SubData as $Skey => $Srow) {
					$ConfArr['Subs'][$Srow['cfg_id']]['SubID'] = $Srow['cfg_id'];
					$ConfArr['Subs'][$Srow['cfg_id']]['SubN'] = $Srow['name'];
				}
			}
				
		}
		
		$Data['Cats']= $CatArr;
		$Data['ConfType']= $ConfType;
		$Data['ConfID']= $id;
		$Data['Conf']= $ConfArr;
		
		$this->render('ApplyToCat',array('Data'=>$Data));
		
	}
	
	public function actionAjaxApplyToCat()
	{
		$_POST = CI_Security::ChkPost($_POST);
		
		$ConfArr = array();$CatArr = array();
		
		$ConfArr = $_POST['Conf'];
		$CatArr = $_POST['Cat'];
		
		$ConfSQL = " SELECT cfg_id,IFNULL((CASE WHEN value = ''THEN 0 ELSE value END),0) AS value,conf_chkrad
					 FROM pd_config WHERE cfg_id IN (".$_POST['ConfID'].",".implode(",",$ConfArr[$_POST['ConfID']]).") ";
		$ConfData = Yii::app() -> db -> createCommand($ConfSQL) -> queryAll();
		
		foreach ($ConfData as $Confkey => $ConfRow) {
				
			$SQL = " INSERT INTO pd_conf_v (pdconfv_pid,pdconfv_confid,pdconfv_value,pdconfv_chkrad)
					 SELECT pid , ".$ConfRow['cfg_id'].",".$ConfRow['value'].", ".$ConfRow['conf_chkrad']."
					 FROM products 
					 WHERE csid IN (".implode(",",$CatArr).") 
					 AND pid NOT IN (SELECT pdconfv_pid FROM pd_conf_v WHERE pdconfv_confid = ".$ConfRow['cfg_id']." AND pdconfv_pid IN (SELECT pid FROM products WHERE csid IN (".implode(",",$CatArr)."))) ";
					
			Yii::app() -> db -> createCommand($SQL) -> execute();
			
		}
		
		
	}
	
	public function loadModel($id)
	{
		$model=PdConfig::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PdConfig $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{ 
		if(isset($_POST['ajax']) && $_POST['ajax']==='pd-config-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
}

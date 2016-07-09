<?php

class OffersController extends Controller
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
				'actions'=>array('index','view' ,'create','update' ,'admin','delete',
				'OpenNotify','ajaxChkOfferValid','AjaxSubmitNotify'),
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
		Login::UserAuth('Offers','View');
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
		Login::UserAuth('Offers','Create');
		Login::ChkBuSess();
		$model=new Offers;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Offers']))
		{
			$_POST['Offers'] = CI_Security::ChkPost($_POST['Offers']);
			
			$model->attributes = $_POST['Offers'];
			
			if($model->save()){
				$OffRow = $this->ChkOfferValid(0,$model->pid,$model->from,$model->to);
				if(!empty($OffRow) && $model->active == 1){
					
					Yii::app() -> db -> createCommand('UPDATE offers SET active = 0 WHERE ofid = '.$OffRow['ofid']) -> execute();
				}
				$this->redirect(array('view','id'=>$model->ofid));
			}
				
			
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
		Login::UserAuth('Offers','Update');
		Login::ChkBuSess();
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Offers']))
		{
			$_POST['Offers'] = CI_Security::ChkPost($_POST['Offers']);
			
			$model->attributes=$_POST['Offers'];
			
			if($model->save()){
				$OffRow = $this->ChkOfferValid($model->ofid,$model->pid,$model->from,$model->to);
				
				if(!empty($OffRow) && $model->active == 1){
					
					Yii::app() -> db -> createCommand('UPDATE offers SET active = 0 WHERE ofid ='.$OffRow['ofid']) -> execute();
				}
				$this->redirect(array('view','id'=>$model->ofid));
			}
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
		Login::UserAuth('Offers','Delete');
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
		Login::UserAuth('Offers','Index');
		Login::ChkBuSess();	
		$dataProvider=new CActiveDataProvider('Offers',array('criteria'=>array('with'=>array('p'),'condition'=>'buid='.Yii::app()->session['User']['UserBuid'])));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('Offers','Admin');
		Login::ChkBuSess();		
		$model=new Offers('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Offers']))
		{
			$_GET['Offers'] = CI_Security::ChkPost($_GET['Offers']);
			$model->attributes = $_GET['Offers'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Offers the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Offers::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Offers $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='offers-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionOpenNotify($id){
			
		Login::UserAuth('Offers','OpenNotify');
		Login::ChkBuSess();
		$model = $this->loadModel($id);
		
		$_GET = CI_Security::ChkPost($_GET);
		
		$type = 0;
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}
		
		if($type == 1){
			
			$SQL = " SELECT * FROM messages_log WHERE mid = ".$model->ofid." AND is_group = 4  AND DATE(`date`) = CURDATE()";
			$MessL = Yii::app()->db->createCommand($SQL)->queryAll();
			if(count($MessL) > 0){
					
				$type = 3;
				
			}
		}
		
		$this->render('notify_1',array(
			'model'=>$model,
			'type'=>$type,
		));
		
	}
	
	public function actionAjaxSubmitNotify(){
			
		Login::UserAuth('Offers','OpenNotify');	
		Login::ChkBuSess();
		$RegsArr = array();	
		
		$_POST = CI_Security::ChkPost($_POST);
		
		$CatSQl = "SELECT csid FROM products WHERE pid = ".$_POST['ProdID'];
		$CatID = Yii::app()->db->createCommand($CatSQl)->queryRow();
		$CatID = !empty($CatID)?$CatID['csid']:0;
		
		$SQL = " SELECT puid,gcm_regid,cid 
				 FROM 
				(SELECT puid,gcm_regid,push_notifications.cid 
				 FROM push_notifications 
				 WHERE cid IN (SELECT DISTINCT subscriptions.cid  
				 			   FROM subscriptions 
				 			   LEFT JOIN customers ON customers.cid = subscriptions.cid 
				 			   WHERE csid = ".$CatID." AND  SUBSTRING(notify_enable,5,1) = 0)
				 ORDER BY count_dev DESC )AS T_Push GROUP BY cid ";
				 
		$CustRegs = Yii::app()->db->createCommand($SQL)->queryAll();
		
		$ResMess = '';
		
		if(count($CustRegs) > 0){
			
			$SQLMess = " INSERT INTO messages_log (mid,cid,puid,is_group) VALUES ";
			
			foreach ($CustRegs as $key => $row) {
				
				array_push($RegsArr,$row['gcm_regid']);
				
				$SQLMess .= " (".$_POST['OffID'].",".$row['cid'].",".$row['puid'].", 4),";
			}
			
			$SQLMess = substr($SQLMess, 0, -1);
			
			Yii::app()->db->createCommand($SQLMess)->execute();
			
			$ResArr = array();
			$ResArr['Type']= 'Offer';
			$ResArr['Mess']= trim($_POST['MessTxt']);
			$ResArr['Data']= CustLib::actionGetProdDetailsByOfferID(array('OfID'=>$_POST['OffID']));
			
			$ResGCM  = GCM::SendNotification($RegsArr, json_encode($ResArr));
			$ResGCM =	json_decode($ResGCM,TRUE);
				
			if($ResGCM['failure'] == '0'&& $ResGCM['success'] > 0){
				
				$ResMess = 'Send Notification';
				
			}else{
					
				$ResMess = 'Invalid Notification';
			}
			
		}else{ $ResMess = 'Invalid Notifications';}
		
		echo $ResMess;
	}

	public function actionAjaxChkOfferValid()
	{
		$_POST = CI_Security::ChkPost($_POST);	
		
		/*
		$OffSQL = " SELECT * FROM offers WHERE ofid != ".$_POST['ID']." AND active = 0 AND pid = ".$_POST['ProID']." 
							AND (`from` BETWEEN '".$_POST['Frm']."' AND '".$_POST['To']."' OR `to` BETWEEN '".$_POST['Frm']."' 
							AND '".$_POST['To']."') ";
		 $OffData = Yii::app()->db->createCommand($OffSQL)->queryAll();*/
		
		$OffRow = $this->ChkOfferValid($_POST['ID'],$_POST['ProID'],$_POST['Frm'],$_POST['To']);
		
		$Chk = 'FALSE';
		
		if(!empty($OffRow)){
					
			$Chk = 'TRUE';	
		}
		
		echo $Chk;
	}
	
	private function ChkOfferValid($ID,$ProID,$Frm,$To)
	{
		$OffSQL = " SELECT * FROM offers WHERE ofid != ".$ID." AND active = 1 AND pid = ".$ProID." 
				    AND ((`from` BETWEEN '".$Frm."' AND '".$To."' OR `to` BETWEEN '".$Frm."' AND '".$To."') 
				    OR ('".$Frm."' BETWEEN `from` AND `to` OR '".$To."' BETWEEN `from` AND `to` ))";
		
		$OffRow = Yii::app()->db->createCommand($OffSQL)->queryRow();	
		
		return $OffRow;	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}

<?php

class CreditCardsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete' , 'ajaxAddValue' ),
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
		Login::UserAuth('CreditCards','View');	
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
		Login::UserAuth('CreditCards','Create');	
		$model=new CreditCards;
		$model->cr_card_owner_id = Yii::app()->session['User']['UserOwnerID'];
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['CreditCards']))
		{
			$_POST['CreditCards'] = CI_Security::ChkPost($_POST['CreditCards']);
			
			$model->attributes=$_POST['CreditCards'];
			
			$model->cr_card_owner_id = Yii::app()->session['User']['UserOwnerID'];
			
			$cipher = new Cipher('secret passphrase');
		
			$Credit = $_POST['CreditCards']['cr_card_credit'];
			$Cvv = $_POST['CreditCards']['cr_card_cvv'];
			
			$EnCredit = $cipher->encrypt($Credit);
			$EnCvv = $cipher->encrypt($Cvv);
			
			$model->cr_card_credit = $EnCredit;
			$model->cr_card_cvv = $EnCvv;
			if($model->save())
				$this->redirect(array('view','id'=>$model->cr_card_id));
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
		Login::UserAuth('CreditCards','Update');	
		$model=$this->loadModel($id);
		
		$cipher = new Cipher('secret passphrase');
		$model->cr_card_credit = $cipher->decrypt($model->cr_card_credit);
		$model->cr_card_cvv = $cipher->decrypt($model->cr_card_cvv);
		 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['CreditCards']))
		{
			$_POST['CreditCards'] = CI_Security::ChkPost($_POST['CreditCards']);
			
			$model->attributes=$_POST['CreditCards'];
			
			$model->cr_card_owner_id = Yii::app()->session['User']['UserOwnerID'];
			
			$cipher = new Cipher('secret passphrase');
		
			$Credit = $_POST['CreditCards']['cr_card_credit'];
			$Cvv = $_POST['CreditCards']['cr_card_cvv'];
			
			$EnCredit = $cipher->encrypt($Credit);
			$EnCvv = $cipher->encrypt($Cvv);
			
			$model->cr_card_credit = $EnCredit;
			$model->cr_card_cvv = $EnCvv;
			
			if($model->save())
				$this->redirect(array('view','id'=>$model->cr_card_id));
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
		Login::UserAuth('CreditCards','Delete');	
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
		Login::UserAuth('CreditCards','Index');	
		$dataProvider=new CActiveDataProvider('CreditCards');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('CreditCards','Admin');	
		$model=new CreditCards('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CreditCards']))
		{
			$_GET['CreditCards'] = CI_Security::ChkPost($_GET['CreditCards']);
			$model->attributes=$_GET['CreditCards'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	
	protected function getCreditCard($data,$row)
	{
     	// var_dump($data->cr_card_credit);
		$cipher = new Cipher('secret passphrase');
		$CrN = $cipher->decrypt($data->cr_card_credit);
		$CrN = substr($CrN, 0, strlen($CrN) - 8).'********';
		
        return $CrN;   
    } 
	
	
	public function actionAjaxAddValue($id)
	{
		Login::UserAuth('CreditCards','AddValue');
		$Detail = Yii::app()->db->createCommand("SELECT cr_d_id , cr_d_val , cr_d_type , cr_d_date
												 FROM credit_details
												 WHERE cr_d_credit_id = ".$id)->queryAll();		
		$total = 0;
		$DetailArr = array();	
		foreach ($Detail as $key => $row) 
		{
			$DetailArr[$row['cr_d_id']]['cr_d_id'] = $row['cr_d_id'];
			$DetailArr[$row['cr_d_id']]['cr_d_val'] = $row['cr_d_val'];
			$DetailArr[$row['cr_d_id']]['cr_d_type'] = $row['cr_d_type'];
			$DetailArr[$row['cr_d_id']]['cr_d_date'] = $row['cr_d_date'];
			$total += $row['cr_d_val'];
		}


		$CurrSQL= "SELECT distinct currency_code,currency_name FROM country WHERE currency_code != '' GROUP BY currency_code ORDER BY currency_code";
		$CurrData = Yii::app()->db->createCommand($CurrSQL)->queryAll();
		$Data['CurrData']= $CurrData;
		
		
		
		if( isset($_POST['Cr_val']) && !empty($_POST['Cr_val'])){
			// print_r($_POST['currency']);
			$MyCurr =  Currency::ConvertCurrency($_POST['currency'] , 'USD', $_POST['Cr_val']);
			 // print_r($MyCurr['ValTo']); 
			 // return;
			// $_POST = CI_Security::ChkPost($_POST);
			
			Twocheckout::privateKey(PRIVATE_KEY);
			Twocheckout::sellerId(SELLER_ID);
			Twocheckout::verifySSL(false);
			Twocheckout::sandbox(true);
			Twocheckout::format('json');
			$Token = $_POST['Token'];
			$PayTotal = round($MyCurr['ValTo'] , 3);	
			
			try {
				
				$charge = Twocheckout_Charge::auth(array("merchantOrderId" => $_POST['CrCardID'], 
														 "token" => $Token, 
														 "currency" => 'USD',// $_POST['currency'], 
														 "total" => $PayTotal, 
														 "billingAddr" => array("name" => 'Testing Tester', 
														 						"addrLine1" => '123 Test St', 
														 						"city" => 'Columbus', 
														 						"state" => 'OH', 
														 						"zipCode" => '43123', 
														 						"country" => 'USA', 
														 						"email" => 'example@2co.com', 
														 						"phoneNumber" => '03-32-48078')));
		
		
		
				$chargeJson = json_decode($charge);
				
				// print_r($chargeJson->response->responseCode);return;	
				if ($chargeJson->response->responseCode == 'APPROVED') {
			
					$Sql = "INSERT INTO credit_details (cr_d_credit_id , cr_d_Tch_id , cr_d_val , cr_d_type , cr_d_date) 
							VALUES (".$id." , ".$chargeJson->response->orderNumber." ,'".$PayTotal."' , 0 , '".date('Y-m-d H:i:s')."')";
					Yii::app()->db->createCommand($Sql)->execute();
					
					echo $charge;
				}
			}catch (Twocheckout_Error $e) {
				print_r($e->getMessage());
			}
		
		}

		$this->render('creditDetail',array(
			'model'=>$this->loadModel($id),
			'DetailArr'=>$DetailArr , 'total'=>$total , 'Data'=>$Data
		));	
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return CreditCards the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=CreditCards::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CreditCards $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='credit-cards-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

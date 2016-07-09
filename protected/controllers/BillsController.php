<?php

class BillsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete',
								 'PayBill','ajaxChBuBillCurr','AjaxChSpBillCurr','AjaxSubmitPayBill',
								 'AjaxSubmitPayBillBUAll','AjaxGetCreditData','OnSiteCommisionRep',
								 'PendingBills','NotifyRep'),
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
		Login::UserAuth('Bills','View');		
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
		Login::UserAuth('Bills','Create');	
		$model=new Bills;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Bills']))
		{
			$_POST['Bills'] = CI_Security::ChkPost($_POST['Bills']);
			$model->attributes=$_POST['Bills'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->bill_id));
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
		Login::UserAuth('Bills','Update');	
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Bills']))
		{
			$_POST['Bills'] = CI_Security::ChkPost($_POST['Bills']);
			
			$model->attributes=$_POST['Bills'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->bill_id));
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
		Login::UserAuth('Bills','Delete');		
		$this->loadModel($id)->delete();
 
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
		{
			// $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			if(isset($_POST['returnUrl'])){
				// $_POST['returnUrl'];
				$_POST['returnUrl'] = CI_Security::ChkPost($_POST['returnUrl']);
			}else{
				$this->redirect( array('admin') );
			}
		}
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Login::UserAuth('Bills','Index');	
		$dataProvider=new CActiveDataProvider('Bills');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('Bills','Admin');	
		$model=new Bills('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Bills'])){
			$_GET['Bills'] = CI_Security::ChkPost($_GET['Bills']);
			$model->attributes=$_GET['Bills'];
		}
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Bills the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Bills::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Bills $model the model to be validated
	 */
	
	//----------------------------- BillS ----------------------------------
	
	public function actionPayBill()
	{
		Login::UserAuth('Bills','PayBill');	
		$this->layout = "column1";	
		
		$Data = array();$Disc = 0;$TotalBill = 0;$finalTotal = 0;$dfinalTotal = 0;
		$OwnerID = 0;
		$RoleID = isset(Yii::app()->session['User']['UserRoleID'])?Yii::app()->session['User']['UserRoleID']:0;
		
		$Data['RoleID']= $RoleID;
		
		if($RoleID == 1){
			$OwnerID = isset($_GET['OwnerID'])?($_GET['OwnerID'] > 0 ? $_GET['OwnerID']: 0 ):0;
		}
		if($RoleID == 2){
			$OwnerID = isset(Yii::app()->session['User']['UserOwnerID'])?(Yii::app()->session['User']['UserOwnerID'] > 0 ? Yii::app()->session['User']['UserOwnerID']: 0 ):0;
		}
		
		$OSQL = "SELECT * FROM bu_accounts 
				 LEFT JOIN country ON bu_accounts.country_id = country.country_id
				 WHERE accid = ".$OwnerID;
		$OData = Yii::app()->db->createCommand($OSQL)->queryRow();
		$Data['Owner']= $OData;
		
		$CurrSQL= "SELECT distinct currency_code,currency_name FROM country WHERE currency_code != '' GROUP BY currency_code ORDER BY currency_code";
		$CurrData = Yii::app()->db->createCommand($CurrSQL)->queryAll();
		$Data['CurrData']= $CurrData;
		
		
		$CrSQL = " SELECT * FROM credit_cards WHERE cr_card_owner_id = ".$OwnerID." ORDER BY cr_card_rank ";
		$CrDataD = Yii::app()->db->createCommand($CrSQL)->queryAll();
		$CrData = array();
		$cipher = new Cipher('secret passphrase');
		foreach ($CrDataD as $key => $row) {
			$CrData[$row['cr_card_id']]['CrID'] = $row['cr_card_id'];
			$CrN = $cipher->decrypt($row['cr_card_credit']);
			$CrN = substr($CrN, 0, strlen($CrN) - 8).'********';
			$CrData[$row['cr_card_id']]['CrN'] = $CrN;
		}
		
		$Data['CrData']= $CrData;
		
		$ComSqlRep = " SELECT ad_setting_name,ad_setting_val FROM ad_setting 
					   WHERE ad_setting_name iN('OnsiteCommision','GSMLimit','GSMExtraVal') ";
		
		$ComDataRep = Yii::app()->db->createCommand($ComSqlRep)->queryAll();
		
		$ONSc = 0; $GSMLimit = 0; $GSMExtraVal = 0;
		
		foreach ($ComDataRep as $key => $row) {
			
				if($row['ad_setting_name'] == 'OnsiteCommision'){$ONSc = $row['ad_setting_val'];}
				if($row['ad_setting_name'] == 'GSMLimit'){$GSMLimit = $row['ad_setting_val'];}
				if($row['ad_setting_name'] == 'GSMExtraVal'){$GSMExtraVal = $row['ad_setting_val'];}
		}
		
		$Data['ONSc'] = $ONSc;
		
		if($OData['special_deal_id'] > 0){
				
			$SpSQL = " SELECT b_stats_BuName,b_stats_PkgN,b_stats_bill_LASTDate,b_stats_OnSiteOrdTotal,
							  ((b_stats_OnSiteOrdTotal)* ".$ONSc." /100) AS Commision,b_stats_DueDate,
						(CASE WHEN b_stats_bc_type = 0 THEN CONCAT(b_stats_bc_duration, ' Days') 
							  WHEN b_stats_bc_type = 1 THEN CONCAT(b_stats_bc_duration, ' Months') 
							  WHEN b_stats_bc_type = 2 THEN CONCAT(b_stats_bc_duration, ' Years') 
					    END) AS Duration,b_stats_Amount,b_stats_Curr,sp_d_description,b_stats_GCMCount
					  FROM bill_stats 
					  LEFT JOIN special_deals ON b_stats_PkgID = sp_d_id
					  WHERE b_stats_is_pay = 0 AND b_stats_buType = 1 AND b_stats_buid = ".$OwnerID;
			
			/*
			$SpSQL = "SELECT * FROM special_deals 
								 LEFT JOIN billing_cycle ON sp_d_bill_cycle_id = bcid
								 WHERE sp_d_id = ".$OData['special_deal_id'];*/
			
			$Sp_Data = Yii::app()->db->createCommand($SpSQL)->queryRow();		 
			/*
			
						$SQL = "SELECT currrency_symbol FROM country WHERE currency_code = '".$Sp_Data['sp_d_currency']."'";
						$Symbol = Yii::app()->db->createCommand($SQL)->queryRow();*/
			if(!empty($Sp_Data)){
			
				$SpData = array();
				$SpData['sp_d_title']= $Sp_Data['b_stats_PkgN'];
				$SpData['sp_d_amount']= $Sp_Data['b_stats_Amount'];
				$SpData['sp_d_currency']= $Sp_Data['b_stats_Curr'];
				$SpData['sp_d_description']= $Sp_Data['sp_d_description'];
				
				//---------- CalCulate Due Date
					
				//$RowDue = $this->CalSPBuDueDate($OData['special_deal_id'],$OData['start_date'],$Sp_Data['bc_duration'],$Sp_Data['bc_type'],1);
				$RowDue = $this->CalSPBuDueDate($Sp_Data['b_stats_DueDate']);
				$SpData['DueDate']= $Sp_Data['b_stats_DueDate'];
				$SpData['class']= $RowDue['class'];
				//---------------------------------------	
				/*
				$CurrArr = Currency::ConvertCurrency($Sp_Data['b_stats_Curr'],$Data['Owner']['currency_code'],$Sp_Data['b_stats_Amount']);
				$CurrVal = round($CurrArr['ValTo'],3);
				$SpData['sp_d_curramount']= $CurrVal;*/
				
				//------------- Delay Fees
				$DelayFees = 0;
				if($RowDue['class'] == 'DelayFees'){
					$DelayFees = $RowDue['delay'];
				}
				$SpData['DelayFees']= $DelayFees;
				/*
				if($DelayFees > 0){
					$DfCurrArr = Currency::ConvertCurrency('USD',$Data['Owner']['currency_code'],$DelayFees);
					$DelayFees = $DfCurrArr['ValTo'];
				}*/
				
				//------------- Extra Fees
				$ExtraFees = 0;
				if($Sp_Data['b_stats_GCMCount'] > $GSMLimit){
					$ExtraFees = round(($Sp_Data['b_stats_GCMCount'] - $GSMLimit) * $GSMExtraVal,2);
				}
				
				$SpData['ExtraFees']= $ExtraFees;
				/*
				if($ExtraFees > 0){
					$EfCurrArr = Currency::ConvertCurrency('USD',$Data['Owner']['currency_code'],$ExtraFees);
					$ExtraFees = $EfCurrArr['ValTo'];
				}*/
				
				//------------- OnSite Commision 
				$OnSiteCommision = $Sp_Data['Commision'];
				$SpData['OnSiteCommision']= $OnSiteCommision;
				/*
				if($OnSiteCommision > 0){
					$OCCurrArr = Currency::ConvertCurrency('USD',$Data['Owner']['currency_code'],$OnSiteCommision);
					$OnSiteCommision = $OCCurrArr['ValTo'];
				}*/
				
				
				//-------------------------------------------
				
				if($SpData['class']== 'ToDay'||$SpData['class']== 'GracePeriod'||$SpData['class']== 'DelayFees'){
					$TotalBill = $Sp_Data['b_stats_Amount'] + $DelayFees + $ExtraFees + $OnSiteCommision;
					$dfinalTotal = $TotalBill;
					$SpData['dollor_total'] = $TotalBill;
					$TCurrArr = Currency::ConvertCurrency('USD',$Data['Owner']['currency_code'],$TotalBill);
					$TotalBill = round($TCurrArr['ValTo'],3);
				}
				$SpData['Bill_C']= $Sp_Data['Duration'];
				$Data['SpData']= $SpData;
			}
		}else{
				
			//---------------- BU
			
			/*
			$BuSQL= "SELECT buid AS BU_ID,business_unit.title AS BU_T,DATE(business_unit.created) As BU_Date,
										pkg_id AS Pkg_ID,packages.title AS Pkg_T,amount AS Pkg_Am,
										packages.currency AS Pkg_Curr,
										(SELECT distinct currrency_symbol FROM country WHERE currency_code = packages.currency)AS Pkg_Curr_S,
										bc_duration,bc_type
								 FROM business_unit
								 LEFT JOIN packages 
									   LEFT JOIN billing_cycle ON pkg_bill_cycleID = bcid
								 ON pkg_id = pkgid
								 WHERE accid = ".$OwnerID;*/
			$Frm = 'USD';
			$To = $Data['Owner']['currency_code'];
			$Val = 1;
			$CurrArr = Currency::ConvertCurrency($Frm,$To,$Val);
			$Rate =	$CurrArr['ValTo'];	 
			
			$BuSQL = " SELECT b_stats_buid,b_stats_BuName,b_stats_PkgID,b_stats_PkgN,b_stats_bill_LASTDate,b_stats_OnSiteOrdTotal,
							  ((b_stats_OnSiteOrdTotal)* ".$ONSc." /100) AS Commision,b_stats_DueDate,
							  (CASE WHEN b_stats_bc_type = 0 THEN CONCAT(b_stats_bc_duration, ' Days') 
							        WHEN b_stats_bc_type = 1 THEN CONCAT(b_stats_bc_duration, ' Months') 
							        WHEN b_stats_bc_type = 2 THEN CONCAT(b_stats_bc_duration, ' Years') 
						       END) AS Duration,b_stats_Amount,b_stats_Curr,b_stats_GCMCount
					  	   FROM bill_stats 
					  	   WHERE b_stats_is_pay = 0 AND b_stats_buType = 0 
					  	   AND b_stats_buid IN (SELECT buid FROM business_unit WHERE accid = ".$OwnerID.")";
			
			$Bu_Data = Yii::app()->db->createCommand($BuSQL)->queryAll();
			$BuData = array();
			foreach ($Bu_Data as $key => $row) {
					
				$BuData[$row['b_stats_buid']]['BU_ID']= $row['b_stats_buid'];
				$BuData[$row['b_stats_buid']]['BU_T']= $row['b_stats_BuName'];
				$BuData[$row['b_stats_buid']]['Pkg_ID']= $row['b_stats_PkgID'];
				$BuData[$row['b_stats_buid']]['Pkg_T']= $row['b_stats_PkgN'];
				$BuData[$row['b_stats_buid']]['Pkg_Am']= $row['b_stats_Amount'];
				$BuData[$row['b_stats_buid']]['DueDate']= $row['b_stats_DueDate'];
				$BuData[$row['b_stats_buid']]['Commision']= $row['Commision'];
				
				//$BuData[$row['BU_ID']]['PayDate']= date('Y-m-d');
				//---------- CalCulate Due Date
				
				//$RowDue = $this->CalSPBuDueDate($row['BU_ID'],$row['BU_Date'],$row['bc_duration'],$row['bc_type'],0);
				$RowDue = $this->CalSPBuDueDate($row['b_stats_DueDate']);
				//$BuData[$row['BU_ID']]['DueDate']= $RowDue['DueDate'];
				$BuData[$row['b_stats_buid']]['class']= $RowDue['class'];
				//----------- Delay Fees
				$DelayFees = 0;
				if($RowDue['class'] == 'DelayFees'){
					$DelayFees = $RowDue['delay'];
				}
				$BuData[$row['b_stats_buid']]['DelayFees']= $DelayFees;
				/*
				if($DelayFees > 0){
					$DfCurrArr = Currency::ConvertCurrency('USD',$Data['Owner']['currency_code'],$DelayFees);
					$DelayFees = $DfCurrArr['ValTo'];
				}*/
				
				//---------- Extra Fees
				$ExtraFees = 0;
				if($row['b_stats_GCMCount'] > $GSMLimit){
					$ExtraFees = round(($row['b_stats_GCMCount'] - $GSMLimit) * $GSMExtraVal,2);
				}
				$BuData[$row['b_stats_buid']]['ExtraFees']= $ExtraFees;
				/*
				if($ExtraFees > 0){
					$EfCurrArr = Currency::ConvertCurrency('USD',$Data['Owner']['currency_code'],$ExtraFees);
					$ExtraFees = $EfCurrArr['ValTo'];
				}*/
				//------------- OnSite Commision 
				$OnSiteCommision = $row['Commision'];
				
				//----------- Convert Currency
				/*
				$Frm = $row['Pkg_Curr'];
				$To = $Data['Owner']['currency_code'];
				$Val = $row['Pkg_Am'];
				$CurrArr = Currency::ConvertCurrency($Frm,$To,$Val);*/
				
				
				//$CurrArrEF = Currency::ConvertCurrency('USD',$To,0);
				
				//$CurrArrEF = Currency::ConvertCurrency('USD',$To,0);
				
				$TotalRow = $row['b_stats_Amount'] + $DelayFees + $ExtraFees + $OnSiteCommision;
				$TotalRow = round($TotalRow , 3);
				$dfinalTotal += $TotalRow;
				
				$BuData[$row['b_stats_buid']]['$Total']= $TotalRow;
				
				$CTotalRow = $TotalRow * $Rate ;
				$CTotalRow = round($CTotalRow , 3);
				
				$BuData[$row['b_stats_buid']]['CTotal']= $CTotalRow;
				
				if($BuData[$row['b_stats_buid']]['class']== 'ToDay'||$BuData[$row['b_stats_buid']]['class']== 'GracePeriod'||$BuData[$row['b_stats_buid']]['class']== 'DelayFees'){
					$TotalBill += $CTotalRow;
				}
				
			}
			$Data['BuData']= $BuData;
		}
	
		
		$Data['Disc']= $Disc;
		$Data['TotalBill']= $TotalBill;
		$Disc = round(($TotalBill*$Disc)/100 , 2);
		$finalTotal = $TotalBill - $Disc;
		$Data['finalTotal']= $finalTotal;
		$dDisc = round(($dfinalTotal*$Disc)/100 , 2);
		$dfinalTotal = $dfinalTotal - $dDisc;
		$Data['dfinalTotal']= $dfinalTotal;
		
		$this->render('paybill',array('Data'=>$Data));
		
	}
	//public function CalSPBuDueDate($BUSPID = 0,$BUSPDate = '',$BcDur = 0,$BcType = 0,$BuOrSp = 0)
	public function CalSPBuDueDate($BUSPDate = '')
	{
		$ResArr = array();	
		
		/*
		$MaxSql= " SELECT DATE(MAX(bill_d_pay_date)) AS LastpayDate FROM bills_details WHERE bill_d_bu_sp_id = ".$BUSPID." AND bill_d_bu_or_sp = ".$BuOrSp." ";
				
				$MaxData = Yii::app()->db->createCommand($MaxSql)->queryRow();
				
				$Type = $BcType == 0 ? 'days' : ( $BcType == 1 ? 'months' : 'years' );
				
				if($MaxData['LastpayDate']!= null){
					
					$StrDate = $MaxData['LastpayDate'];
				}else{
					//$StrDate = strtotime($BU_Date);
					$StrDate = $BUSPDate;
				}*/
		
		$DueDate = $BUSPDate;
		
		//$DueDate = date('Y-m-d', strtotime('+'.$BcDur.' '.$Type.'', strtotime($StrDate)) );
		
		
		
		//------------------------------
		$GracePeriod = 0;$DelayFees = 0;
		$AdSql= " SELECT * FROM ad_setting WHERE (ad_setting_name = 'GracePeriod' OR ad_setting_name = 'DelayFees' )";
		$AdData = Yii::app()->db->createCommand($AdSql)->queryAll();
		foreach ($AdData as $key => $row) {
					
			if($row['ad_setting_name'] == 'GracePeriod'){$GracePeriod = $row['ad_setting_val'];}
			if($row['ad_setting_name'] == 'DelayFees')  {$DelayFees = $row['ad_setting_val'];}
		}	
		//-------------------------------
		$NDate = date('Y-m-d', strtotime('+'.$GracePeriod.' days ', strtotime($DueDate)) );
		$PDate = date('Y-m-d', strtotime('-'.$GracePeriod.' days ', strtotime(date('Y-m-d'))));
		
		if ($DueDate == date('Y-m-d')) {
			
			$ResArr = array('DueDate'=>$DueDate,'class'=>'ToDay');
	
		}elseif($DueDate > date('Y-m-d')){
				
			$ResArr = array('DueDate'=>$DueDate,'class'=>'NoPay');
		
		}elseif(($PDate < $DueDate && $DueDate <= date('Y-m-d'))||$NDate == date('Y-m-d')){
			
			$ResArr = array('DueDate'=>$DueDate,'class'=>'GracePeriod');
		
		}elseif(date('Y-m-d') > $NDate){
			
			$ResArr = array('DueDate'=>$DueDate,'class'=>'DelayFees','delay'=>$DelayFees);
		}
		
		return $ResArr;
	}
	
	public function actionAjaxSubmitPayBill()
	{
		// $_POST = CI_Security::ChkPost($_POST);
		Login::UserAuth('Bills','PayBill');
		$_POST = CI_Security::ChkPost($_POST);
		
		$AdminID = 0;
		$Type = isset(Yii::app()->session['User']['UserType'])?Yii::app()->session['User']['UserType']:'';
		if($Type == 'admin'){
			$AdminID = isset(Yii::app()->session['User']['UserID'])?Yii::app()->session['User']['UserID']:0;
		}
		
		$BSQL = " INSERT INTO bills(bill_owner_id,bill_amount,bill_disc,bill_currency_code,bill_notes,bill_is_admin) 
				  VALUES (".$_POST['OwnerID'].",".$_POST['RowTotal'].",".$_POST['Disc'].",'".$_POST['currency']."','".$_POST['Notes']."',".$AdminID.")";
					
		Yii::app()->db->createCommand($BSQL)->execute();
		$BillID = Yii::app()->db->getLastInsertID();
					
		
		$ChkRes = '';
		
		if($Type == 'admin' ){
			
			$ChkRes = 'TRUE';
		}
		if($Type == 'owner' ){
			
			$ChkRes = $this->SubmitCheckOut($_POST['Token'],$_POST['dRowTotal'],$BillID,$_POST['OwnerID']);
		}
		if($ChkRes == 'TRUE'){
			
			$BDSQL = " INSERT INTO bills_details(bill_d_due_date,bill_d_bill_id,bill_d_bu_sp_id,bill_d_bu_or_sp,bill_d_amount,bill_d_extrafees,bill_d_delayfees,bill_d_OnSiteCom) 
					   VALUES ('".$_POST['DueDate']."',".$BillID.",".$_POST['BUSPID'].",".$_POST['Type'].",".$_POST['RowTotal'].",".$_POST['ExtraFees'].",".$_POST['DelayFees'].",".$_POST['Commision'].")";
		
			Yii::app()->db->createCommand($BDSQL)->execute();
			
			$StatsBuid = $_POST['Type'] == 0?$_POST['BUSPID']:$_POST['OwnerID'];
			
			$BSSQL= " UPDATE bill_stats SET b_stats_is_pay = 1 
					  WHERE b_stats_buid = ".$StatsBuid." AND b_stats_buType = ".$_POST['Type']." AND b_stats_DueDate = '".$_POST['DueDate']."' AND b_stats_is_pay = 0 ";
			
			Yii::app()->db->createCommand($BSSQL)->execute();
		
			echo 'TRUE';
			
		}else{
			
			$DBSQL = " DELETE FROM bills WHERE bill_id = ".$BillID;
					
			Yii::app()->db->createCommand($DBSQL)->execute();
			
			print_r($ChkRes);
		}
		
		
		
	}
	
	public function actionAjaxSubmitPayBillBUAll()
	{
		Login::UserAuth('Bills','PayBill');	
		$_POST = CI_Security::ChkPost($_POST);
			
		$ArrD = $_POST['Arrpay'];
		
		$AdminID = 0;
		$Type = isset(Yii::app()->session['User']['UserType'])?Yii::app()->session['User']['UserType']:'';
		if($Type == 'admin'){
			$AdminID = isset(Yii::app()->session['User']['UserID'])?Yii::app()->session['User']['UserID']:0;
		}
		
		$BSQL = " INSERT INTO bills(bill_owner_id,bill_amount,bill_disc,bill_currency_code,bill_notes,bill_is_admin) 
				  VALUES (".$_POST['OwnerID'].",".$_POST['TotalBill'].",".$_POST['Disc'].",'".$_POST['currency']."','".$_POST['Notes']."',".$AdminID.")";
		
		Yii::app()->db->createCommand($BSQL)->execute();
		$BillID = Yii::app()->db->getLastInsertID();
		
		$ChkRes = '';
		
		if($Type == 'admin' ){
			
			$ChkRes = 'TRUE';
		}
		if($Type == 'owner' ){
			
			$ChkRes = $this->SubmitCheckOut($_POST['Token'],$_POST['dRowTotal'],$BillID,$_POST['OwnerID']);
		}
			
		if($ChkRes == 'TRUE'){
			
			$BDSQL = " INSERT INTO bills_details(bill_d_due_date,bill_d_bill_id,bill_d_bu_sp_id,bill_d_bu_or_sp,bill_d_amount,bill_d_extrafees,bill_d_delayfees) 
					   VALUES ";
		
			foreach ($ArrD as $key => $row) {
				
				$BDSQL .= "('".$row['DueDate']."',".$BillID.",".$row['BUSPID'].",".$_POST['Type'].",".$row['RowTotal'].",".$row['ExtraFees'].",".$row['DelayFees']."),";
				$BSSQL= " UPDATE bill_stats SET b_stats_is_pay = 1 
						  WHERE b_stats_buid = ".$row['BUSPID']." AND b_stats_buType = ".$_POST['Type']." AND b_stats_DueDate = '".$row['DueDate']."' AND b_stats_is_pay = 0 ";
				Yii::app()->db->createCommand($BSSQL)->execute();
			}
			
			$BDSQL = rtrim($BDSQL,',');
			
			Yii::app()->db->createCommand($BDSQL)->execute();
		
			echo 'TRUE';
			
		}else{
				
			$DBSQL = " DELETE FROM bills WHERE bill_id = ".$BillID;
					
			Yii::app()->db->createCommand($DBSQL)->execute();
			
			print_r($ChkRes);
			
		}
		
	}
	
	public function actionAjaxChBuBillCurr()
	{
		Login::UserAuth('Bills','PayBill');	
		$_POST = CI_Security::ChkPost($_POST);
		$ARRCh = $_POST['DaArr'];
		$Frm = $_POST['Frm'];
		$To = $_POST['to'];
		$CurrArr = Currency::ConvertCurrency($Frm,$To,1);
		$Rate = round($CurrArr['ValTo'],3);
		$SQL = "SELECT currrency_symbol FROM country WHERE currency_code = '".$To."'";
		$Symbol = Yii::app()->db->createCommand($SQL)->queryRow();
		$Symbol = $Symbol['currrency_symbol'];
		$ResArr = array();
		foreach ($ARRCh as $key => $value) {
			/*
			$CurrArr = Currency::ConvertCurrency($Frm,$To,$value);
			$CurrVal = round($CurrArr['ValTo'],3);*/
			
			$CurrVal = round(($value * $Rate),3);
			array_push($ResArr,array('BUID'=>$key,'NewTotal'=>$CurrVal,'Symbol'=>$Symbol));
		}
		echo json_encode($ResArr);
	}
	
	public function actionAjaxChSpBillCurr()
	{
		Login::UserAuth('Bills','PayBill');	
		$_POST = CI_Security::ChkPost($_POST);
		$Amount = $_POST['Amount'];
		$Frm = $_POST['Frm'];
		$To = $_POST['to'];
			
		$SQL = "SELECT currrency_symbol FROM country WHERE currency_code = '".$To."'";
		$Symbol = Yii::app()->db->createCommand($SQL)->queryRow();
		$Symbol = $Symbol['currrency_symbol'];
		
		$CurrArr = Currency::ConvertCurrency($Frm,$To,$Amount);
		$CurrVal = round($CurrArr['ValTo'],3);
		
		$ResArr = array('CurrVal'=>$CurrVal,'Symbol'=>$Symbol);
		echo json_encode($ResArr);
	}
	
	public function SubmitCheckOut($Token,$Total,$BillID,$OwnerID)
	{
		Twocheckout::privateKey(PRIVATE_KEY);
		Twocheckout::sellerId(SELLER_ID);
		Twocheckout::verifySSL(false);
		Twocheckout::sandbox(true);
		Twocheckout::format('json');
		$PayTotal = round($Total,3);
		$Trans = '0';
		try {
			$charge = Twocheckout_Charge::auth(array("merchantOrderId" => $BillID, 
													 "token" => $Token, 
													 "currency" => 'USD', 
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
			
			if ($chargeJson -> response -> responseCode == 'APPROVED') {
					
					
				$SQL = " INSERT INTO bill_payments (bill_pay_owner_id , bill_pay_bill_id , bill_pay_Tch_id , bill_pay_total , bill_pay_trans )
					 	 VALUES(".$OwnerID.",
					 	 		".$chargeJson->response->merchantOrderId." , 
					 	 		".$chargeJson->response->orderNumber." ,
					 	 		".$chargeJson->response->total." ,
					 	 		".$Trans.")";
					 	 		
				Yii::app()->db->createCommand($SQL)->execute();
					
				//echo $charge;
				return 'TRUE';
			
			}else{
				
				return 'FALSE';
			}
		
		
		
		} catch (Twocheckout_Error $e) {
			//print_r($e -> getMessage());
			return $e -> getMessage();
		}
	}
	
	public function actionAjaxGetCreditData()
	{
		Login::UserAuth('Bills','PayBill');	
		$_POST = CI_Security::ChkPost($_POST);
			
		$CrSQL = " SELECT cr_card_credit,cr_card_cvv,Year(cr_card_expirationDate)AS YearExp,Month(cr_card_expirationDate)AS MonthExp
				   FROM credit_cards WHERE cr_card_id = ".$_POST['CardID'];
		$CrData = Yii::app()->db->createCommand($CrSQL)->queryRow();
		
		$cipher = new Cipher('secret passphrase');
			
		$EnCredit = $CrData['cr_card_credit'];
		$EnCvv = $CrData['cr_card_cvv'];
		
		$DeCredit = $cipher->decrypt($EnCredit);
		$DeCvv = $cipher->decrypt($EnCvv);
		
		$ResArr = array();
		
		$ResArr['Credit']= $DeCredit;
		$ResArr['Cvv']= $DeCvv;
		$ResArr['YearExp']= $CrData['YearExp'];
		$ResArr['MonthExp']= $CrData['MonthExp'];
		
		echo json_encode($ResArr);
	}
	
	//-------------------------- Reports ----------------------------------
	
	//----------------- OnSite Commision 
	
	public function actionOnSiteCommisionRep ()
	{
		Login::UserAuth('Bills','OnSiteCommisionRep');	
		$this->layout = "column1";	
		$DataRep = array();
		
		//------ Onsite Commision
		
		$ComSqlRep = " SELECT ad_setting_val FROM ad_setting WHERE ad_setting_name = 'OnsiteCommision' ";
		
		$ComDataRep = Yii::app()->db->createCommand($ComSqlRep)->queryRow();
		
		$ONSc = $ComDataRep['ad_setting_val'];
		
		$DataRep['ONSc'] = $ONSc;
		
		//------------------ Packages -----------
		
		$PkgSqlRep = " SELECT b_stats_BuName, b_stats_PkgN,b_stats_bill_LASTDate,b_stats_OnSiteOrdTotal,
							  ((b_stats_OnSiteOrdTotal)* ".$ONSc." /100) AS Commision,b_stats_DueDate,
						(CASE WHEN b_stats_bc_type = 0 THEN CONCAT(b_stats_bc_duration, ' Days') 
							  WHEN b_stats_bc_type = 1 THEN CONCAT(b_stats_bc_duration, ' Months') 
							  WHEN b_stats_bc_type = 2 THEN CONCAT(b_stats_bc_duration, ' Years') 
					    END) AS Duration
					   FROM bill_stats WHERE b_stats_is_pay = 0 AND b_stats_buType = 0 ";
		
		$PkgDataRep = Yii::app()->db->createCommand($PkgSqlRep)->queryAll();
		
		$DataRep['PkgDataRep'] = $PkgDataRep;
		
		//--------------------- Special Deals --------
		
		$SPSqlRep = " SELECT b_stats_BuName, b_stats_PkgN,b_stats_bill_LASTDate,b_stats_OnSiteOrdTotal,
							  ((b_stats_OnSiteOrdTotal)* ".$ONSc." /100) AS Commision,b_stats_DueDate,
						(CASE WHEN b_stats_bc_type = 0 THEN CONCAT(b_stats_bc_duration, ' Days') 
							  WHEN b_stats_bc_type = 1 THEN CONCAT(b_stats_bc_duration, ' Months') 
							  WHEN b_stats_bc_type = 2 THEN CONCAT(b_stats_bc_duration, ' Years') 
					    END) AS Duration 
					  FROM bill_stats WHERE b_stats_is_pay = 0 AND b_stats_buType = 1 ";
		
		$SPDataRep = Yii::app()->db->createCommand($SPSqlRep)->queryAll();
		
		$DataRep['SPDataRep'] = $SPDataRep;
		
		$this->render('onsiteComRep',array('DataRep'=>$DataRep));
		
	}
	
	public function actionPendingBills()
	{
		Login::UserAuth('Bills','PendingBills');	
		$this->layout = "column1";
		$Data = array();
		
		$AccSQL = "	SELECT business_unit.accid AS AccID , CONCAT(fname,' ',lname) AS AccName ,'Bu Packages' AS AccType
					FROM bill_stats 
					LEFT JOIN business_unit 
						 LEFT JOIN bu_accounts ON business_unit.accid = bu_accounts.accid
					ON b_stats_buid = buid
					WHERE b_stats_buType = 0 AND b_stats_is_pay = 0
					GROUP BY business_unit.accid
					UNION ALL
					SELECT b_stats_buid AS AccID , b_stats_BuName AS AccName ,'Special Deals' AS AccType
					FROM bill_stats 
					WHERE b_stats_buType = 1 AND b_stats_is_pay = 0 ";
		
		$AccData = Yii::app()->db->createCommand($AccSQL)->queryAll();
		
		$Data['AccData'] = $AccData;
		
		$this->render('pendingbill',array('Data'=>$Data));	
	}

	public function actionNotifyRep()
	{
		Login::UserAuth('Bills','NotifyRep');	
		$this->layout = "column1";	
		$DataRep = array();
		
		//------------------ Packages -----------
		
		$PkgSqlRep = " SELECT b_stats_BuName, b_stats_PkgN,b_stats_bill_LASTDate,b_stats_DueDate,b_stats_GCMCount,
							  (CASE WHEN b_stats_bc_type = 0 THEN CONCAT(b_stats_bc_duration, ' Days') 
								  WHEN b_stats_bc_type = 1 THEN CONCAT(b_stats_bc_duration, ' Months') 
								  WHEN b_stats_bc_type = 2 THEN CONCAT(b_stats_bc_duration, ' Years') 
						      END) AS Duration
					   FROM bill_stats WHERE b_stats_is_pay = 0 AND b_stats_buType = 0 ";
		
		$PkgDataRep = Yii::app()->db->createCommand($PkgSqlRep)->queryAll();
		
		$DataRep['PkgDataRep'] = $PkgDataRep;
		
		//--------------------- Special Deals --------
		
		$SPSqlRep = " SELECT b_stats_BuName, b_stats_PkgN,b_stats_bill_LASTDate,b_stats_DueDate,b_stats_GCMCount,
							 (CASE WHEN b_stats_bc_type = 0 THEN CONCAT(b_stats_bc_duration, ' Days') 
								  WHEN b_stats_bc_type = 1 THEN CONCAT(b_stats_bc_duration, ' Months') 
								  WHEN b_stats_bc_type = 2 THEN CONCAT(b_stats_bc_duration, ' Years') 
						     END) AS Duration 
					  FROM bill_stats WHERE b_stats_is_pay = 0 AND b_stats_buType = 1 ";
		
		$SPDataRep = Yii::app()->db->createCommand($SPSqlRep)->queryAll();
		
		$DataRep['SPDataRep'] = $SPDataRep;
		
		$this->render('notifyRep',array('DataRep'=>$DataRep));
		
	}
	//-------------------------------------------------------------------------
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bills-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

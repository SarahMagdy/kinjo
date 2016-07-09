<?php

class OrdersDetailsController extends Controller
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
								 'customGrid','customView','ajaxCustomGrid','CloseOrder','AjaxCloseOrder',
								 'AjaxAssignToDB','AjaxDeleteDBAssign','OrdAssignsHistory'),
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
		Login::UserAuth('OrdersDetails','View');
		$Model = $this->loadModel($id);
		
		//--------------------------------
		$BillingAddr = '';$ShippingAddr = '';
		$BillRes = Yii::app()->db->createCommand('SELECT * FROM customer_add LEFT JOIN country ON cust_add_country_id = country_id
												  WHERE cust_add_id ='.$Model->cust_billingAddr)->queryRow();
		$ShipRes = Yii::app()->db->createCommand('SELECT * FROM customer_add LEFT JOIN country ON cust_add_country_id = country_id
												  WHERE cust_add_id ='.$Model->cust_shipAddr)->queryRow();
		if(!empty($BillRes)){
			$BillingAddr = $BillRes['cust_add_street'].' '.$BillRes['cust_add_region'].' '.$BillRes['cust_add_city'].' '.$BillRes['name'];
		}
		if(!empty($ShipRes)){
			$ShippingAddr = $ShipRes['cust_add_street'].' '.$ShipRes['cust_add_region'].' '.$ShipRes['cust_add_city'].' '.$ShipRes['name'];
		}
		//--------------------------------
		$ColorRes = Yii::app()->db->createCommand("SELECT * FROM orders_detail_conf LEFT JOIN prod_colors ON ord_de_conf_co_id = color_id
												   WHERE ord_de_conf_type = 'color' AND ord_de_conf_de_id =".$Model->ord_det_id)->queryRow();
		$Color = '';
		if(!empty($ColorRes)){
			$Color = $ColorRes['color_code'];
		}
		//--------------------------------
		$ConfRes = Yii::app()->db->createCommand(" SELECT ord_de_conf_co_id AS SubID,Sub.name AS SubName,
												   Par.cfg_id AS ParID,Par.name AS ParName
												   FROM orders_detail_conf 
												   LEFT JOIN pd_config AS Sub 
												   	   LEFT JOIN pd_config AS Par ON Sub.parent_id = Par.cfg_id 
												   ON ord_de_conf_co_id = Sub.cfg_id
												   WHERE ord_de_conf_type = 'conf' AND ord_de_conf_de_id =".$Model->ord_det_id)->queryAll();
		$Conf = array();
		foreach ($ConfRes as $confkey => $confrow) {
			$Conf[$confrow['ParID']]['ParID'] = $confrow['ParID'];
			$Conf[$confrow['ParID']]['ParName'] = $confrow['ParName'];
			$Conf[$confrow['ParID']]['Sub'][$confrow['SubID']]['SubID'] = $confrow['SubID'];
			$Conf[$confrow['ParID']]['Sub'][$confrow['SubID']]['SubName'] = $confrow['SubName'];
		}
		//--------------------------------
		$this->render('view',array(
			'model'=>$Model,
			'BillingAddr'=>$BillingAddr,
			'ShippingAddr'=>$ShippingAddr,
			'Color'=>$Color,
			'Conf'=>$Conf,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		Login::UserAuth('OrdersDetails','Create');
		$model=new OrdersDetails;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OrdersDetails']))
		{
			$_POST['OrdersDetails'] = CI_Security::ChkPost($_POST['OrdersDetails']);
			
			$model->attributes = $_POST['OrdersDetails'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ord_det_id));
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
		Login::UserAuth('OrdersDetails','Update');
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['OrdersDetails']))
		{
			$model->attributes=$_POST['OrdersDetails'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ord_det_id));
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Login::UserAuth('OrdersDetails','Index');
		$dataProvider=new CActiveDataProvider('OrdersDetails', array('criteria' => array('condition' => 'ord_buid='.Yii::app()->session['User']['UserBuid'])));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('OrdersDetails','Admin');
		$model=new OrdersDetails('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['OrdersDetails']))
			$model->attributes=$_GET['OrdersDetails'];

		$this->render('admin',array(
			'model'=>$model,
		));
		
		
	}
	
	public function actionAjaxCustomGrid()
	{
		Login::UserAuth('OrdersDetails','CustomGrid');
			
		$SqlWhere = ' ';$SqlLimit = ' ';	
		
		if(isset($_POST['open'])){	
			if($_POST['open'] == 'Search'){
				
				if($_POST['ord_det_id'] != ''){$SqlWhere .= ' AND ord_det_id = '.$_POST['ord_det_id'];}
				if($_POST['ord_id'] != ''){$SqlWhere .= ' AND orders_details.ord_id = '.$_POST['ord_id'];}
				if($_POST['pid'] != ''){$SqlWhere .= ' AND pid = '.$_POST['pid'];}
				if($_POST['from'] != ''){$SqlWhere .= " AND orders_details.created >= '".$_POST['from']."'";}
				if($_POST['to'] != ''){$SqlWhere .= " AND orders_details.created <= '".$_POST['to']."'";}
				
			}	
		}
		
		if(isset($_POST['page'])){
				
			$OFFSET = ($_POST['page'] - 1)*10;
			
			$SqlLimit = ' LIMIT 10 OFFSET '.$OFFSET;
		}

		$SQL = " SELECT ord_det_id , orders_details.pid AS ProID , products.title AS ProName , 
						qnt , disc , orders_details.price AS price, fees ,
						(CASE WHEN close_date IS NULL THEN '--' ELSE DATE(close_date) END)AS CloseDate,
					    final_price , date( orders_details.created ) AS detaildate,
						orders_details.ord_id as ordid,concat(fname,' ',lname) as custname,orders.cid as custid,
						(CASE WHEN orders.status = 0 THEN 'Opened' ELSE 'Closed' END)as ord_type
				FROM orders_details
				LEFT JOIN orders
					LEFT JOIN customers ON customers.cid = orders.cid
				ON orders.ord_id = orders_details.ord_id 
				LEFT JOIN products ON orders_details.pid = products.pid
				WHERE orders_details.ord_buid = ".Yii::app()->session['User']['UserBuid'].$SqlWhere;
				
		$Data = Yii::app()->db->createCommand($SQL)->queryAll();
        //Array to store the options passed to the CArrayDataProvider
        $dataProviderOptions = array();

        //Setting the keyField for the Data Provider. The default keyField is `id`. 
        //Since `id` is not present in our array, we need to specify this value.
        //keyField is used to uniquely identitfy each record on the grid view.
        $dataProviderOptions['keyField'] = 'ord_det_id';

        //Enable Sorting on CGridView
        $sort = new CSort();
        $sort->attributes = array(
            'ord_det_id','ordid','item'
        );
        $dataProviderOptions['sort'] = $sort;
        //End Sorting

        //Custom Pagination on CGridView
        
        $pagination = new CPagination(count($Data));
        $pagination->pageSize = 10;
       // $dataProviderOptions['pagination'] = $pagination;
        //End Pagination

        //Creating the CArrayDataProvider with our data and the options
       
        $PageData = Yii::app()->db->createCommand($SQL.$SqlLimit)->queryAll();
        $arrayDP = new CArrayDataProvider($PageData, $dataProviderOptions);
		
		 //Rendering the view page
       	$ResData = $arrayDP->getData();
		$this->renderPartial('grid', array('ResData' => $ResData,'pages'=>$pagination));
		
	}

	public function actionCustomGrid()
	{
		Login::UserAuth('OrdersDetails','CustomGrid');	

        $this->render('customgrid');
	}

	public function actionCustomView()
	{
		Login::UserAuth('OrdersDetails','CustomView');
		
		$SQL = " SELECT ord_det_id , pid , item , qnt , disc , price , fees ,
				final_price , date( orders_details.created ) AS detaildate,
				orders_details.ord_id as ordid,concat(fname,' ',lname) as custname,orders.cid as custid,
				(CASE WHEN orders.status = 0 THEN 'Opened' ELSE 'Closed' END)as ord_type
				
				FROM orders_details
				LEFT JOIN orders
					LEFT JOIN customers ON customers.cid = orders.cid
				ON orders.ord_id = orders_details.ord_id WHERE orders_details.ord_buid = ".Yii::app()->session['User']['UserBuid'];
				
		$Data = Yii::app()->db->createCommand($SQL)->queryAll();
       	$ResData = array();
		$DataProvOption = array();
		$sort = new CSort();
        $sort->attributes = array(
            'ordid'
        );
        $DataProvOption['sort'] = $sort;
		
	    foreach($Data AS $key=>$row){
        	
			$ResData[$row['ordid']]['ordid']=$row['ordid'];
			$ResData[$row['ordid']]['custname']=$row['custname'];
			$ResData[$row['ordid']]['ord_type']=$row['ord_type'];
			
			if(isset($ResData[$row['ordid']]['ord_total'])){
				
				$ResData[$row['ordid']]['ord_total'] += $row['final_price'];
				
			}else{
				
				$ResData[$row['ordid']]['ord_total'] = $row['final_price'];
			}
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['detail_id']= $row['ord_det_id'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['pid']= $row['pid'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['item']= $row['item'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['qnt']= $row['qnt'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['disc']= $row['disc'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['price']= $row['price'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['fees']= $row['fees'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['final_price']= $row['final_price'];
			$ResData[$row['ordid']]['details'][$row['ord_det_id']]['detaildate']= $row['detaildate'];
			
        }
		$count = count($ResData);
		$pages=new CPagination($count);
        $pages->pageSize = 10;
		
        $DataProvOption['pagination'] = $pages;
		
 		$FResData = new CArrayDataProvider($ResData, $DataProvOption);
		$FResData = $FResData->getData();
		
        $this->render('customview', array('ResData' => $FResData ,'pages'=>$pages));
	}

	public function actionCloseOrder()
	{
		Login::UserAuth('OrdersDetails','CloseOrder');	
		$Data = array();
			
		$OwnerID = isset(Yii::app()->session['User']['UserOwnerID'])?(Yii::app()->session['User']['UserOwnerID'] > 0 ? Yii::app()->session['User']['UserOwnerID']: 0 ):0;
		$BuID = isset(Yii::app()->session['User']['UserBuid'])?(Yii::app()->session['User']['UserBuid'] > 0 ? Yii::app()->session['User']['UserBuid']: 0 ):0;
		
		/*
		$OSQL = " SELECT orders_details.ord_id AS OrdID ,
								 orders.cid AS CustID,CONCAT(customers.fname,' ',customers.lname)AS CustName,
								 SUM(final_price)AS BuTotal,
								 (CASE WHEN app_type = 0 THEN 'Mobile APP'
										WHEN app_type = 1 THEN 'Online Site'
									   WHEN app_type = 2 THEN 'Facebook App' END)AS AppType,
								 (SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 1)AS CurrS
						  FROM orders_details 
						  LEFT JOIN orders 
								 LEFT JOIN customers ON orders.cid = customers.cid
						  ON orders_details.ord_id = orders.ord_id
						  LEFT JOIN business_unit ON ord_buid = business_unit.buid
						  WHERE pay_type = 1 AND business_unit.active = 0 AND orders_details.close_date IS NULL 
								  AND business_unit.accid = ".$OwnerID." AND ord_buid = ".$BuID." 
						  GROUP BY orders_details.ord_id ";*/
		
		$OSQL = "SELECT orders_details.ord_id AS OrdID, orders.cid AS CustID, CONCAT(customers.fname, ' ', customers.lname) AS CustName, 
     				    SUM( final_price ) AS BuTotal, (CASE WHEN app_type =0 THEN 'Mobile APP'
                                              				 WHEN app_type =1 THEN 'Online Site' 
                                            				 WHEN app_type =2 THEN 'Facebook App' END ) AS AppType,
      					(SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 1) AS CurrS, ord_assign_derv_id , 
       					(CASE WHEN ord_assign_derv_id IS NOT NULL THEN 'TRUE' ELSE 'FALSE' END ) AS has_DB , DB_name ,ord_assign_id
       					
				 FROM orders_details LEFT JOIN orders
				 LEFT JOIN customers ON orders.cid = customers.cid 
					 ON orders_details.ord_id = orders.ord_id
				 LEFT JOIN business_unit ON ord_buid = business_unit.buid
				 LEFT JOIN (SELECT ord_assign_id , ord_assign_derv_id , ord_assign_buid ,ord_assign_ordid , CONCAT(fname , ' ' , lname) AS DB_name
				 			FROM orders_assign LEFT JOIN cpanel ON ord_assign_derv_id = cp_id ) AS orders_assign
				 ON ord_assign_buid = business_unit.buid AND ord_assign_ordid = orders_details.ord_id
				 WHERE pay_type =1 AND business_unit.active =0
								   AND orders_details.close_date IS NULL
								   AND business_unit.accid =".$OwnerID."
								   AND ord_buid =".$BuID."
				 GROUP BY orders_details.ord_id";
		
		
		
		$OData = Yii::app()->db->createCommand($OSQL)->queryAll();
		
		$Data['OData']=$OData;
		
		$DSQL = "SELECT cp_id , buid , username , email , fname , lname
				 FROM cpanel WHERE role_id = 5 AND buid = ".$BuID;
		$DData = Yii::app()->db->createCommand($DSQL)->queryAll();
		$Data['DData'] = $DData;
		// var_dump($DData);return;
		$this->render('closeorder', array('Data' => $Data));
	}
	
	public function actionAjaxCloseOrder()
	{
		Login::UserAuth('OrdersDetails','CloseOrder');	
		
		$_POST = CI_Security::ChkPost($_POST);
		
		$BuID = isset(Yii::app()->session['User']['UserBuid'])?(Yii::app()->session['User']['UserBuid'] > 0 ? Yii::app()->session['User']['UserBuid']: 0 ):0;
		$UserID = isset(Yii::app()->session['User']['UserID'])?(Yii::app()->session['User']['UserID'] > 0 ? Yii::app()->session['User']['UserID']: 0 ):0;
	
		$UpSql = " UPDATE orders_details SET close_date = now() WHERE pay_type = 1 AND ord_id = ".$_POST['OrdID']." AND ord_buid = ".$BuID;
		$ResUp = Yii::app()->db->createCommand($UpSql)->execute();
		
		Orders::BuTotalClose($_POST['OrdID'],$BuID,$UserID,1);
		
		if($ResUp > 0){ echo 'True';}
		else{echo 'False';}
	}
	
	public function actionAjaxAssignToDB()
	{
		Login::UserAuth('OrdersDetails','CloseOrder');
		
		$_POST = CI_Security::ChkPost($_POST);
		
		$BuID = isset(Yii::app()->session['User']['UserBuid'])?(Yii::app()->session['User']['UserBuid'] > 0 ? Yii::app()->session['User']['UserBuid']: 0 ):0;
		
		$insSql = "INSERT INTO orders_assign(ord_assign_buid , ord_assign_ordid , ord_assign_derv_id ) VALUES ";
		for($i=0 ; $i < sizeof($_POST['ordArr']) ; $i++){
			$insSql .= "(".$BuID." , ".$_POST['ordArr'][$i]." , ".$_POST['DB_ID']." ),";
		}
		
		$insSql = substr($insSql, 0, -1);
		Yii::app()->db->createCommand($insSql)->execute();
		
		// var_dump($insSql);
		// return;
	}
	
	public function actionAjaxDeleteDBAssign()
	{
		Login::UserAuth('OrdersDetails','CloseOrder');
		
		$_POST = CI_Security::ChkPost($_POST);
		
		$BuID = isset(Yii::app()->session['User']['UserBuid'])?(Yii::app()->session['User']['UserBuid'] > 0 ? Yii::app()->session['User']['UserBuid']: 0 ):0;
		
		$SQL = " DELETE FROM orders_assign WHERE ord_assign_id = ".$_POST['ord_assign_id']." AND ord_assign_buid=".$BuID;
		Yii::app()->db->createCommand($SQL)->execute();
	}
	
	public function actionOrdAssignsHistory()
	{
		$OwnerID = isset(Yii::app()->session['User']['UserOwnerID'])?(Yii::app()->session['User']['UserOwnerID'] > 0 ? Yii::app()->session['User']['UserOwnerID']: 0 ):0;
		$BuID = isset(Yii::app()->session['User']['UserBuid'])?(Yii::app()->session['User']['UserBuid'] > 0 ? Yii::app()->session['User']['UserBuid']: 0 ):0;
		
		// var_dump($_GET['cp_id']);return;
		$where = "";$Res['cp_id'] ="";
		if(isset($_GET['cp_id']) && !empty($_GET['cp_id']) ){
			$where = " AND ord_bu_total_user_id = ".$_GET['cp_id'];
			$Res['cp_id'] = $_GET['cp_id'];
		}
		
		$DSQL = "SELECT cp_id , buid , username , email , fname , lname
				 FROM cpanel WHERE role_id = 5 AND buid = ".$BuID;
		$DData = Yii::app()->db->createCommand($DSQL)->queryAll();
		$Res['DData'] = $DData;
		
		
		$OwnerSQL = "SELECT cp_id , buid , username , email , fname , lname
					 FROM cpanel WHERE role_id = 2 AND buid = ".$OwnerID;
		$OwnerData = Yii::app()->db->createCommand($OwnerSQL)->queryRow();
		$Res['OData'] = $OwnerData;
			
		$SQL = "SELECT ord_bu_total_id, ord_bu_total_ord_id, ord_bu_total_total, ord_bu_total_user_id, ord_bu_total_close_date, 
				 	   username, cpanel.email, CONCAT( cpanel.fname, ' ', cpanel.lname ) AS cpanel_name , CONCAT( customers.fname, ' ', customers.lname ) AS CustName
				FROM orders_bu_totals
				LEFT JOIN cpanel ON cp_id = ord_bu_total_user_id
				LEFT JOIN orders
				LEFT JOIN customers ON orders.cid = customers.cid ON ord_bu_total_ord_id = orders.ord_id
				WHERE ord_bu_total_bu_id =".$BuID." AND ord_bu_total_pay_type =1".$where;
						
		$Res['history'] = Yii::app()->db->createCommand($SQL)->queryAll();
		$this->render('closeorderhistory', array('Res' => $Res));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return OrdersDetails the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=OrdersDetails::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param OrdersDetails $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='orders-details-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

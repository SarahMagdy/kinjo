<?php

class M_OrdersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
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
				'actions'=>array('index','view','create','update','admin','delete' , 'ajaxGetChartData' , 
								 'ajaxAnnotationChart' , 'ajaxPieChart' , 'ajaxProStatistics'),
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
		Login::UserAuth('M_Orders','Create');	
		$model=new M_Orders;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['M_Orders']))
		{
			$_POST['M_Orders'] = CI_Security::ChkPost($_POST['M_Orders']);
			$model->attributes=$_POST['M_Orders'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ord_id));
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
		Login::UserAuth('M_Orders','Update');
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['M_Orders']))
		{
			$_POST['M_Orders'] = CI_Security::ChkPost($_POST['M_Orders']);
			$model->attributes=$_POST['M_Orders'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->ord_id));
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
		Login::UserAuth('M_Orders','Delete');
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
		Login::UserAuth('M_Orders','Index');
		$dataProvider=new CActiveDataProvider('M_Orders');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('M_Orders','Admin');
		$model=new M_Orders('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['M_Orders']))
		{
			$_GET['M_Orders'] = CI_Security::ChkPost($_GET['M_Orders']);
			$model->attributes = $_GET['M_Orders'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return M_Orders the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=M_Orders::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param M_Orders $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='m--orders-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}



	public function actionAjaxGetChartData()
	{
		if($_POST){
			
			$_POST = CI_Security::ChkPost($_POST);
			
			$type = '';
			$where = '';
			if($_POST['chart_type'] == 'Month'){
				$type = 'MONTH';
				// $where = ' AND YEAR( `created` ) = YEAR(CURDATE()) ';
				$where = ' AND YEAR( `created` ) = '.$_POST['select_year'];
			}else if($_POST['chart_type'] == 'Year'){
				$type = 'YEAR';
			}
				
			$Sql = "SELECT ".$type."(created) AS created , SUM( ord_total ) AS ord_total
					FROM orders
					WHERE status= 1 ".$where."
					GROUP BY ".$type."( created ) ";
				   
			$Data = Yii::app()->db->createCommand($Sql)->queryAll();
			echo json_encode($Data);
		}else{
		
			$Data = Yii::app()->db->createCommand("SELECT DISTINCT YEAR(created) AS created 
									FROM orders")->queryAll();
													   
			$Years_arr = array();	
			foreach ($Data as $key => $row) 
			{
				$Years_arr[$row['created']]['created'] = $row['created'];
			}
			
			$this->render('admin_report' , array('Years_arr'=>$Years_arr));
		}
		
	}
	
	public function actionAjaxAnnotationChart()
	{
		if($_POST){
			
			$_POST = CI_Security::ChkPost($_POST);
			
			// HOUR( created ) AS H_created, MINUTE( created ) AS Min_created, SECOND( created ) AS S_created
			$where = '';
			if($_POST['chart_year'] != 'ALL'){
				$where = " AND YEAR(created) = ".$_POST['chart_year'];
			}
			
			$Sql = "SELECT ord_id , created , SUM(ord_total) AS ord_total , 
						YEAR(created) AS Y_created , MONTH(created) AS Mon_created , DAY(created) AS D_created
						
					FROM orders
					WHERE status= 1 ".$where." GROUP BY DATE( created ) ";
				   
			$orders_arr = Yii::app()->db->createCommand($Sql)->queryAll();
			echo json_encode($orders_arr);
			
		}else{
							   
			$Data = Yii::app()->db->createCommand("SELECT DISTINCT YEAR(created) AS created 
									FROM orders")->queryAll();
			// $Data_Arr = json_encode($Data);
													   
			$Years_arr = array();	
			foreach ($Data as $key => $row) 
			{
				$Years_arr[$row['created']]['created'] = $row['created'];
				// $Years_arr[$row['color_id']]['color_name'] = $row['color_name'];
			}
			
			
			$this->render('admin_AnnotationChart' ,array('Years_arr'=>$Years_arr));
		}
	}

	public function actionAjaxPieChart()
	{
		if($_POST){
			
			$_POST = CI_Security::ChkPost($_POST);
			
			$type = '';
			$where = '';
			if($_POST['chart_type'] == 'Month'){
				$type = 'MONTH';
				$where = ' AND YEAR( `created` ) = YEAR(CURDATE()) ';
			}else if($_POST['chart_type'] == 'Year'){
				$type = 'YEAR';
			}
				
			$Sql = "SELECT ".$type."(created) AS created , SUM( ord_total ) AS ord_total , YEAR(created) AS Y_created , MONTH(created) AS Mon_created
					FROM orders
					WHERE status= 1 ".$where."
					GROUP BY ".$type."( created ) ";
				   
			$Data = Yii::app()->db->createCommand($Sql)->queryAll();
			echo json_encode($Data);
			
		}else{
			$this->render('admin_PieChart' );
		}
		
	}
	
	public function actionAjaxProStatistics()
	{
		
		if($_POST){
			
			$_POST = CI_Security::ChkPost($_POST);
			 
			$Sql = "SELECT pid , item , SUM( qnt ) AS qnt, SUM( final_price ) AS final_price
					 FROM orders_details
					 WHERE ord_buid =".$_POST['store_type']."
					 GROUP BY (pid)
					 ORDER BY qnt DESC
					 LIMIT 10";
			$Data = Yii::app()->db->createCommand($Sql)->queryAll();
			echo json_encode($Data);
		 
		 }else{
		 	
			$Data = Yii::app()->db->createCommand("SELECT buid , title
									FROM business_unit WHERE accid = ".Yii::app()->session['User']['UserOwnerID'])->queryAll();
													   
			$bu = array();	
			foreach ($Data as $key => $row) 
			{
				$bu[$row['buid']]['buid'] = $row['buid'];
				$bu[$row['buid']]['title'] = $row['title'];
			}
			
		 	$this->render('owner_ProStatistics' ,array('bu'=>$bu));
		 }
	}
	
}

<?php

class BuAccountsController extends Controller
{
	
	// public function __construct()
	// {echo 'jkjhjfgj';
		// parent::__construct($this->id, $this->module);
		// $language = 'es_ES';
		// putenv("LANG=$language"); 
		// setlocale(LC_ALL, $language);
		// // Set the text domain as 'messages'
		// $domain = 'messages';
		// bindtextdomain($domain, "/var/www/html/kinjo/protected/locale");			 
		// textdomain($domain);
		// // Yii::app()->language = 'es_ES';
		// // print_r(Yii::app()->language);
	// } 
	
	public function init()
	{
		// parent::__construct($this->id, $this->module);
		parent::init();
		
		// $language = Yii::app()->session['User']['UserLang'];
		// putenv("LANG=$language"); 
		// setlocale(LC_ALL, $language);
		// // Set the text domain as 'messages'
		// $domain = 'messages';
		// bindtextdomain($domain, TRANS_PATH);			 
		// textdomain($domain);
		// // Yii::app()->language = 'es_ES';
		
		// require_once ('/var/www/html/kinjo/protected/languages/en/lang-en.php');
		
		// Yii::app()->params['langg'] = $lang;
		// print_r(Yii::app()->params['lang']);
		
		// print_r($_SESSION['User']);
		// var_dump(Yii::app()->session['User']);
		Yii::app()->language = Yii::app()->session['Language']['UserLang'];
		// var_dump(Yii::app()->session['Language']['UserLang']);
	}
	 
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
				'actions'=>array('admin','delete','index','view','create','update','MyImgsCrop'),
				'users'=>array('*'),
			),
			/*array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','index','view','create','update'),
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
		Login::UserAuth('BuAccounts','View');
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
		Login::UserAuth('BuAccounts','Create');
		$model = new BuAccounts;
		
		$RealAdrr = Globals::ReturnGlobals();
		$path = $RealAdrr['ImgPath'].'bu_accounts/';
		 //$AccData = BuAccounts::model()->findAll(array("select"=>"accid,fname,lname"));
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['BuAccounts']))
		{
			// print_r(strstr($_POST['BuAccounts']['Dimensions'], 'x', true));
			// print_r(substr($_POST['BuAccounts']['Dimensions'], strpos($_POST['BuAccounts']['Dimensions'], "x") + 1));
			$_POST['BuAccounts'] = CI_Security::ChkPost($_POST['BuAccounts']);
			// print_r($_POST['BuAccounts']);
			// return;
			
			$model->attributes = $_POST['BuAccounts'];			

			$rnd = $random = date(time());
	        // $uploadedLogo = CUploadedFile::getInstance($model, 'photo');
			// $handle = new upload($_POST['BuAccounts']['photo']);
			$handle = new upload($_FILES['photo']);//$handle->file_src_name
	        $LogoName = "";
			
	        // if($uploadedLogo != null){
	        if($handle->file_src_name != null){
		    	// $LogoName = "{$rnd}-{$uploadedLogo}";
				$LogoName = "{$rnd}-$handle->file_src_name_body";//file_src_name
				$LogoName = md5($LogoName);
				$model->photo = $LogoName.'.'.$handle->file_src_name_ext;
			}else{
		   		$LogoName = 'default.jpg'; 
				$model->photo = $LogoName;   
			}
			// $model->photo = $LogoName.'.'.$handle->file_src_name_ext;
			// $model->special_deal_id = 0;
			$model->feature_id = 1;
			if($model->save()){
				
				// if($uploadedLogo != null){
				if($handle != null){
		        	// $uploadedLogo->saveAs(Yii::app()->basePath.'/../public/images/upload/bu_accounts/'.$LogoName,false);

			      	// $image = new EasyImage(Yii::app()->basePath.'/../public/images/upload/bu_accounts/'.$LogoName);
			       	// $image->resize(100, 100);
			       	// $image->save(Yii::app()->basePath.'/../public/images/upload/bu_accounts/thumbnails/'.$LogoName);
			       	
			       	// ---- save resized image -------------
			       	$handle->file_new_name_body = $LogoName;
					$handle->image_resize = true;
					$handle->image_ratio = true;
				    $handle->image_x = strstr($_POST['BuAccounts']['Dimensions'], 'x', true);
				    $handle->image_y = substr($_POST['BuAccounts']['Dimensions'], strpos($_POST['BuAccounts']['Dimensions'], "x") + 1);
				    $handle->process($path);
					// ---- save thumbnail image -----------
					$handle->file_new_name_body = $LogoName;
					$handle->image_resize = true;
					$handle->image_ratio = true;
					$handle->image_x = 100;
				    $handle->image_y = 100;
					$handle->process($path.'thumbnails/');
				    // if ($handle->processed) {
				    	// echo 'image resized';
				        // $handle->clean();
				    // } else {
				    	// echo 'error : ' . $handle->error;
				    // }
			       	
				}
				
				$this->redirect(array('view','id'=>$model->accid));
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
		Login::UserAuth('BuAccounts','Update');
		$model = $this->loadModel($id);
		
		$RealAdrr = Globals::ReturnGlobals();
		$path = $RealAdrr['ImgPath'].'bu_accounts/';
		
		//$packages = Packages::model()->findAll(array("select"=>"pkgid,title"));
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$handle = new upload(Yii::app()->basePath.'/../public/images/upload/bu_accounts/'.$model->photo);
		
		$dimensions = $handle->image_src_x .'x'.$handle->image_src_y;
	
		if(isset($_POST['BuAccounts']))
		{
			$_POST['BuAccounts'] = CI_Security::ChkPost($_POST['BuAccounts']);
			// print_r($_POST);
			// return;
				
			$_POST['BuAccounts']['photo'] = $model->photo;
			$model->attributes=$_POST['BuAccounts'];
			$old_img = $model->photo;
			
			$rnd = $random = date(time());
			
			// $uploadedFile=CUploadedFile::getInstance($model,'photo');
			$handle2 = new upload($_FILES['photo']);
			$imgName = "";
	        // if($uploadedFile != null){
	        // print_r($handle2->file_src_name_body);return;
	        if($handle2->file_src_name != null){
		    	// $imgName = "{$rnd}-{$uploadedFile}";
				$imgName = md5("{$rnd}-$handle2->file_src_name_body");
				$model->photo = $imgName.'.'.$handle2->file_src_name_ext;
			}else{
		   		$imgName = $old_img;//'default.jpg'; 
		   		$model->photo = $imgName;   
			}
			// $model->photo = $imgName;
			if($model->save()){
                
                // if($uploadedFile != null){
                if($handle2->file_src_name != null){

					// if($model->photo != '' && $model->photo!='default.jpg'){
					// if($old_img != '' && $old_img !='default.jpg'){
						// unlink($path.$old_img);
						// unlink($path.'thumbnails/'.$old_img);

					if($model->photo != '' && $model->photo!='default.jpg'){
						if(file_exists($path.$old_img)){unlink($path.$old_img);}
						if(file_exists($path.'thumbnails/'.$old_img)){unlink($path.'thumbnails/'.$old_img);}

					}
					
               		// $uploadedFile->saveAs(Yii::app()->basePath.'/../public/images/upload/bu_accounts/'.$imgName);
                    // $image = new EasyImage(Yii::app()->basePath.'/../public/images/upload/bu_accounts/'.$imgName);
                    // $image->resize(100,100);
                    // $image->save(Yii::app()->basePath.'/../public/images/upload/bu_accounts/thumbnails/'.$imgName);
					
					// ---- save resized image -------------
			       	$handle2->file_new_name_body = $imgName;
					$handle2->image_resize = true;
					$handle2->image_ratio = true;
				    $handle2->image_x = strstr($_POST['BuAccounts']['Dimensions'], 'x', true);
				    $handle2->image_y = substr($_POST['BuAccounts']['Dimensions'], strpos($_POST['BuAccounts']['Dimensions'], "x") + 1);
				    $handle2->process($path);
					// ---- save thumbnail image -----------
					$handle2->file_new_name_body = $imgName;
					$handle2->image_resize = true;
					$handle2->image_ratio = true;
					$handle2->image_x = 100;
				    $handle2->image_y = 100;
					$handle2->process($path.'thumbnails/');
				
				
				}
				
				$this->redirect(array('view','id'=>$model->accid));
			}
		}

		$this->render('update',array(
			'model'=>$model , 'dimensions'=>$dimensions
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		Login::UserAuth('BuAccounts','Delete');		
		$m_model = $this->loadModel($id);
					
		$img_path = Yii::app()->basePath.'/../public/images/upload/bu_accounts/';
		
		//if(file_exists($img_path)){
		if($m_model->photo != '' && $m_model->photo !='default.jpg'){
			if(file_exists($img_path.$m_model->photo)){unlink($img_path.$m_model->photo);}
			if(file_exists($img_path.'thumbnails/'.$m_model->photo)){unlink($img_path.'thumbnails/'.$m_model->photo);}
		}
		
		$m_model->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Login::UserAuth('BuAccounts','Index');
		$dataProvider=new CActiveDataProvider('BuAccounts');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('BuAccounts','Admin');
		$model=new BuAccounts('search');
		
		$model->unsetAttributes();  // clear any default values
		
		if(isset($_GET['BuAccounts']) ){
			$_GET['BuAccounts'] = CI_Security::ChkPost($_GET['BuAccounts']);
			$model->attributes = $_GET['BuAccounts'];
			
		}
			

		$this->render('admin',array(
			'model'=>$model,//'model2'=>$model2
		));
	}



	protected function getSpecialDealTitel($data,$row){
			
		$theCellValue = '';
		
		$SQL = "SELECT sp_d_title FROM special_deals
				WHERE sp_d_id = " . $data->special_deal_id ;
     	$result = Yii::app()->db-> createCommand($SQL) -> queryRow();
		$theCellValue = $result['sp_d_title'];
		
        return $theCellValue; 
	}
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return BuAccounts the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=BuAccounts::model()->findByPk($id);
		
		//$model = BuAccounts::model()->with('pkg' , 'billCycle')->findByPk($id);
		
		//$model=Patron::model()->with('PatronAccount','Student')->findByPk($id);
		
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param BuAccounts $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bu-accounts-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	

	
	
	
}







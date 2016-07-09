<?php

class AdminsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','home'),
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
		Login::UserAuth('Admins','View');
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
		Login::UserAuth('Admins','Create');	
		$model=new Admins;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Admins']))
		{
			$_POST['Admins'] = CI_Security::ChkPost($_POST['Admins']);
			
			$model->attributes=$_POST['Admins'];
			$rnd = $random = date(time());
			
			//$uploadedImg = CUploadedFile::getInstance($model, 'photo');
			$uploadedImg = new upload($_FILES['photo']);
			
			$ImgName = "";
			//if($uploadedImg != null){
			if($uploadedImg->file_src_name != null){
			     //$ImgName = "{$rnd}-{$uploadedImg}"; 
				 $ImgName = "{$rnd}-$uploadedImg->file_src_name_body";
				 $ImgName = md5($ImgName); 
				 $model->photo = $ImgName.'.'.$uploadedImg->file_src_name_ext;
			 }else{
			     $ImgName = 'default.jpg'; 
				 $model->photo = $ImgName;   
			 }
			
			$model->password = md5($model->password);
			$model->confirmpassword = md5($model->confirmpassword);
			
			$model->role_id = 1;
			
			if($model->save()){
				
				if($uploadedImg != null){
					
					 $ImgArr = Globals::ReturnGlobals();
					 $ImgPath = $ImgArr['ImgPath'].'admins/';
					
				 /*
					  $uploadedImg->saveAs($ImgPath.$ImgName,false);
													   $image = new EasyImage($ImgPath.$ImgName);
									 $image->resize(100, 100);
									 $image->save($ImgPath.'thumbnails/'.$ImgName);*/
				 
				 	// ---- save resized image -------------
			       	$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_ratio = true;
				    $uploadedImg->image_x = strstr($_POST['Admins']['Dimensions'], 'x', true);
				    $uploadedImg->image_y = substr($_POST['Admins']['Dimensions'], strpos($_POST['Admins']['Dimensions'], "x") + 1);
				    $uploadedImg->process($ImgPath);
					// ---- save thumbnail image -----------
					$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_x = 100;
				    $uploadedImg->image_y = 100;
					$uploadedImg->process($ImgPath.'thumbnails/');
				 }
				$this->redirect(array('view','id'=>$model->adid));
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
		Login::UserAuth('Admins','Update');	
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		$RealAdrr = Globals::ReturnGlobals();
		$ImgPath = $RealAdrr['ImgPath'].'admins/';
		
		$handle = new upload($ImgPath.$model->photo);
		
		$dimensions = $handle->image_src_x .'x'.$handle->image_src_y;
		
		if(isset($_POST['Admins']))
		{
			$_POST['Admins'] = CI_Security::ChkPost($_POST['Admins']);
				
			$_POST['Admins']['photo'] = $model->photo;
			$model->attributes=$_POST['Admins'];
			$old_img = $model->photo;
			
			$rnd = $random = date(time());
			
			//$uploadedImg = CUploadedFile::getInstance($model, 'photo');
			$uploadedImg = new upload($_FILES['photo']);
			
			$ImgName = "";
			//if($uploadedImg != null){
			if($uploadedImg->file_src_name != null){
			    // $ImgName = "{$rnd}-{$uploadedImg}"; 
				 $ImgName = "{$rnd}-$uploadedImg->file_src_name_body";
				 $ImgName = md5($ImgName);
				 $model->photo = $ImgName.'.'.$uploadedImg->file_src_name_ext;
			 }else{
			     $ImgName = $old_img;    
				 $model->photo = $ImgName;  
			 }
			
			
			//$model->photo = $ImgName;
			$model->password = md5($model->password);
			$model->confirmpassword = md5($model->confirmpassword);
			$model->role_id = 1;
			
			if($model->save()){
				
				//if($uploadedImg != null){
				if($uploadedImg->file_src_name != null){
						
					if($model->photo != '' && $old_img != 'default.jpg'){
		               if(file_exists($img_path.$old_img)){unlink($img_path.$old_img);}
		               if(file_exists($img_path.'thumbnails/'.$old_img)){unlink($img_path.'thumbnails/'.$old_img);}
		     		 }
				 /*
					 $uploadedImg->saveAs($img_path.$ImgName); 
									 $image = new EasyImage($img_path.$ImgName);
									 $image->resize(100, 100);
									 $image->save($img_path.'thumbnails/'.$ImgName);*/
				 
				 
				    // ---- save resized image -------------
			       	$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_ratio = true;
				    $uploadedImg->image_x = strstr($_POST['Admins']['Dimensions'], 'x', true);
				    $uploadedImg->image_y = substr($_POST['Admins']['Dimensions'], strpos($_POST['Admins']['Dimensions'], "x") + 1);
				    $uploadedImg->process($ImgPath);
					// ---- save thumbnail image -----------
					$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_ratio = true;
					$uploadedImg->image_x = 100;
				    $uploadedImg->image_y = 100;
					$uploadedImg->process($ImgPath.'thumbnails/');
					
				 }
				$this->redirect(array('view','id'=>$model->adid));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'dimensions'=>$dimensions,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		Login::UserAuth('Admins','Delete');	
		//$this->loadModel($id)->delete();
		$m_model = $this->loadModel($id);
		
		//$img_path = Yii::app()->basePath.'/../public/images/upload/cpanel/';
		$ImgArr = Globals::ReturnGlobals();
		$img_path = $ImgArr['ImgPath'].'admins/';
		if($m_model->photo != '' && $m_model->photo !='default.jpg'){
			if(file_exists($img_path.$m_model->photo)){unlink($img_path.$m_model->photo);}
			if(file_exists($img_path.'thumbnails/'.$m_model->photo)){unlink($img_path.'thumbnails/'.$m_model->photo);}
		}
		
		$m_model->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		
		// if(!isset($_GET['ajax']))
			// $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			
			
		if(!isset($_GET['ajax'])){
				
			if(isset($_POST['returnUrl'])){
				
				$_POST['returnUrl'] = CI_Security::ChkPost($_POST['returnUrl']);
				// $_POST['returnUrl'];
			}else{$this->redirect(  array('admin'));}
			
		}
			
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		Login::UserAuth('Admins','Index');		
		$dataProvider=new CActiveDataProvider('Admins');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('Admins','Admin');		
		$model=new Admins('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Admins'])){
			$_GET['Admins'] = CI_Security::ChkPost($_GET['Admins']);
			$model->attributes=$_GET['Admins'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Admins the loaded model
	 * @throws CHttpException
	 */
	 
	public function actionHome()
	{
		Login::UserAuth('Admins','Home');	
		$this->render('home');
	}
	
	public function loadModel($id)
	{
		$model=Admins::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Admins $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='admins-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

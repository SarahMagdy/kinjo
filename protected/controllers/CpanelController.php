<?php

class CpanelController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','home','ajaxHome','ajaxRemoveBuid',
								 'ConvertLang' , 'Changepassword' , 'changePassMSG' , 'forgetpassword'),
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
		Login::UserAuth('Cpanel','View');
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
		Login::UserAuth('Cpanel','Create');
		$model=new Cpanel;
		// $model->level = 1;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cpanel']))
		{
			$uploadedLogo = new upload($_FILES['photo']);
			$_POST['Cpanel'] = CI_Security::ChkPost($_POST['Cpanel']);
			
			$model->attributes=$_POST['Cpanel'];
			
			/*
			if(Yii::app()->session['User']['UserType']=='admin'){
							$model->level = 0;
						}else if(Yii::app()->session['User']['UserType']=='owner' ){
							$model->level = 1;
						}*/
			
			
			
			$rnd = $random = date(time());
	        //$uploadedLogo = CUploadedFile::getInstance($model, 'photo');
			
			
			//if($uploadedLogo != null){
			if($uploadedLogo->file_src_name != null){
		    	//$LogoName = "{$rnd}-{$uploadedLogo}";
				$LogoName = "{$rnd}-$uploadedLogo->file_src_name_body";
				$LogoName = md5($LogoName); 
				$model->photo = $LogoName.'.'.$uploadedLogo->file_src_name_ext;
			}else{
		   		$LogoName = 'default.jpg';
				$model->photo = $LogoName;    
			}
			
			$model->password = md5($model->password);
			$model->confirmpassword = md5($model->confirmpassword);
			if(Yii::app()->session['User']['UserRoleID']== '1'){
				$model->role_id = 2;
			}
			// return;
			if($model->save()){
						
				if($uploadedLogo != null){
		        		
					$ImgArr = Globals::ReturnGlobals();
					$ImgPath = $ImgArr['ImgPath'].'cpanel/';
					
		        	/*
					$uploadedLogo->saveAs($ImgPath.$LogoName,false);
				    $image = new EasyImage($ImgPath.$LogoName);
				    $image->resize(100, 100);
				    $image->save($ImgPath.'thumbnails/'.$LogoName);*/
				    
				    // ---- save resized image -------------
			       	$uploadedLogo->file_new_name_body = $LogoName;
					$uploadedLogo->image_resize = true;
					$uploadedLogo->image_ratio = true;
				    $uploadedLogo->image_x = strstr($_POST['Cpanel']['Dimensions'], 'x', true);
				    $uploadedLogo->image_y = substr($_POST['Cpanel']['Dimensions'], strpos($_POST['Cpanel']['Dimensions'], "x") + 1);
				    $uploadedLogo->process($ImgPath);
					// ---- save thumbnail image -----------
					$uploadedLogo->file_new_name_body = $LogoName;
					$uploadedLogo->image_resize = true;
					$uploadedLogo->image_ratio = true;
					$uploadedLogo->image_x = 100;
				    $uploadedLogo->image_y = 100;
					$uploadedLogo->process($ImgPath.'thumbnails/');
					
				}
				$this->redirect(array('view','id'=>$model->cp_id));
			
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
		Login::UserAuth('Cpanel','Update');
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$ImgArr = Globals::ReturnGlobals();
		$ImgPath = $ImgArr['ImgPath'].'cpanel/';
		
		$handle = new upload($ImgPath.$model->photo);
		
		$dimensions = $handle->image_src_x .'x'.$handle->image_src_y;
		
		$model->confirmpassword = $model->password;
		$oldPass = $model->password;
		
		if(isset($_POST['Cpanel']))
		{
			$_POST['Cpanel'] = CI_Security::ChkPost($_POST['Cpanel']);
				
			$_POST['Cpanel']['photo'] = $model->photo;
			$model->attributes=$_POST['Cpanel'];
			$old_img = $model->photo;
			
			$rnd = $random = date(time());
			
			//$uploadedFile=CUploadedFile::getInstance($model,'photo');
			$uploadedFile = new upload($_FILES['photo']);
			
			$imgName = "";
	       // if($uploadedFile != null){
	       if($uploadedFile->file_src_name != null){
		    	//$imgName = "{$rnd}-{$uploadedFile}";
		    	 $imgName = "{$rnd}-$uploadedFile->file_src_name_body";
				 $imgName = md5($imgName);
				 $model->photo = $imgName.'.'.$uploadedFile->file_src_name_ext;
			}else{
		   		$imgName = $old_img;//'default.jpg';  
		   		$model->photo = $imgName;  
			}
			
			/*
			if(Yii::app()->session['User']['UserType']=='admin'){
							$model->level = 0;
						}else if(Yii::app()->session['User']['UserType']=='owner' ){
							// $model->level = 1;
							$model->level = $_POST['Cpanel']['level'];
						}*/
			
			// print_r($model->password);
			
			// $model->password = md5($model->password);
			// $model->confirmpassword = md5($model->confirmpassword);
			// echo '<pre/>';
			// echo $model->password;
			// return;
			if(Yii::app()->session['User']['UserRoleID']== '1'){
				$model->role_id = 2;
			}	
			if($model->save()){
				
				if($uploadedFile != null){
					if($old_img != '' && $old_img!='default.jpg'){
						if(file_exists($ImgPath.$old_img)){unlink($ImgPath.$old_img);}
						if(file_exists($ImgPath.'thumbnails/'.$old_img)){unlink($ImgPath.'thumbnails/'.$old_img);}
					}
					
               		/*
					   $uploadedFile->saveAs($ImgPath.$imgName);
					   $image = new EasyImage($ImgPath.$imgName);
					   $image->resize(100,100);
					   $image->save($ImgPath.'thumbnails/'.$imgName);*/
					// ---- save resized image -------------
			       	$uploadedFile->file_new_name_body = $imgName;
					$uploadedFile->image_resize = true;
					$uploadedFile->image_ratio = true;
				    $uploadedFile->image_x = strstr($_POST['Cpanel']['Dimensions'], 'x', true);
				    $uploadedFile->image_y = substr($_POST['Cpanel']['Dimensions'], strpos($_POST['Cpanel']['Dimensions'], "x") + 1);
				    $uploadedFile->process($ImgPath);
					// ---- save thumbnail image -----------
					$uploadedFile->file_new_name_body = $imgName;
					$uploadedFile->image_resize = true;
					$uploadedFile->image_ratio = true;
					$uploadedFile->image_x = 100;
				    $uploadedFile->image_y = 100;
					$uploadedFile->process($ImgPath.'thumbnails/');   
					
				 }
				
				$this->redirect(array('view','id'=>$model->cp_id));
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
		Login::UserAuth('Cpanel','Delete');
		// $this->loadModel($id)->delete();
		$m_model = $this->loadModel($id);
		
		//$img_path = Yii::app()->basePath.'/../public/images/upload/cpanel/';
		$ImgArr = Globals::ReturnGlobals();
		$img_path = $ImgArr['ImgPath'].'cpanel/';
		if($m_model->photo != '' && $m_model->photo !='default.jpg'){
			if(file_exists($img_path.$m_model->photo)){unlink($img_path.$m_model->photo);}
			if(file_exists($img_path.'thumbnails/'.$m_model->photo)){unlink($img_path.'thumbnails/'.$m_model->photo);}
		}
		
		$m_model->delete();
		
		
		
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
		// $dataProvider=new CActiveDataProvider('Cpanel');
		Login::UserAuth('Cpanel','Index');
		$dataProvider ='';
		
		if(Yii::app()->session['User']['UserRoleID']== 1){
			// $dataProvider=new CActiveDataProvider('Cpanel');
			$criteria = new CDbCriteria();
			$criteria->addCondition('level = 0');
			$dataProvider = new CActiveDataProvider('Cpanel', array('criteria'=>$criteria));
			
		}else if(Yii::app()->session['User']['UserRoleID']== 2){
			$criteria = new CDbCriteria();
			$criteria->addCondition('level = 1');
			$criteria->addCondition('buid IN (SELECT buid FROM business_unit WHERE accid = '.Yii::app()->session['User']['UserOwnerID'].')');
			$dataProvider = new CActiveDataProvider('Cpanel', array('criteria'=>$criteria));
			
		}else {
			$criteria = new CDbCriteria();
			$criteria->addCondition('cp_id ='.Yii::app()->session['User']['UserID']);
			$dataProvider = new CActiveDataProvider('Cpanel', array('criteria'=>$criteria));
		}
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('Cpanel','Admin');
		$model=new Cpanel('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cpanel']))
		{
			$_GET['Cpanel'] = CI_Security::ChkPost($_GET['Cpanel']);
			$model->attributes=$_GET['Cpanel'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	public function actionHome()
	{
		if(isset(Yii::app()->session['User'])){
				
			if(Yii::app()->session['User']['UserType'] == 'owner'){
					
				if(Yii::app()->session['User']['UserBuid']== ''){
					
					$Stores = Yii::app()->db->createCommand('SELECT * FROM business_unit WHERE accid ='.Yii::app()->session['User']['UserOwnerID'])->queryAll();	
					$this->render('owner_home',array('Stores'=>$Stores));
				
				}else{
					$BU = Yii::app()->db->createCommand('SELECT * FROM business_unit WHERE buid ='.Yii::app()->session['User']['UserBuid'])->queryRow();
					
					Login::SetLangSetting();
					$this->render('data_entry_home' , array('BU'=>$BU));
					
				}
			}
			
			if(Yii::app()->session['User']['UserType'] == 'data_entry'){
				$BU = Yii::app()->db->createCommand('SELECT * FROM business_unit WHERE buid ='.Yii::app()->session['User']['UserBuid'])->queryRow();
				Login::SetLangSetting();
				$this->render('data_entry_home', array('BU'=>$BU));
			}
		}else{
			
			header("Location:/index.php/site");
		}
			
	}
	public function actionAjaxHome()
	{
		$_POST = CI_Security::ChkPost($_POST);
		$_SESSION['User']['UserBuid'] = $_POST['store_id'];
		//$this->redirect("/index.php/admins/home");
		
	}
	public function actionAjaxRemoveBuid()
	{
		
		$_SESSION['User']['UserBuid']= 0;
		//$this->redirect("/index.php/admins/home");
		
	}
	
	
	
	
	public function actionChangepassword($id)
	{
		Login::UserAuth('Cpanel','Changepassword');	
		$model = new Cpanel;
	    $model = Cpanel::model()->findByAttributes(array('cp_id'=>$id));
	    $model->setScenario('changePwd');
		
		if(isset($_POST['Cpanel'])){
			
	     	$model->attributes = $_POST['Cpanel'];
			
			$model->confirmpassword = md5($model->new_password);
			$model->password = md5($model->new_password);
			// var_dump($model->confirmpassword);//
	 
	        $valid = $model->validate();
			
	        if($valid){
	 			
	        	$model->password = md5($model->new_password);
	 
	          	if($model->save()){
	            	$this->redirect(array('/Cpanel/changePassMSG?msg=successfully changed password'));
					// $this->render('changePassMSG',array('model'=>$model ,'msg'=>'successfully changed password')); 
				}else{
	            	$this->redirect(array('/Cpanel/changePassMSG?msg=password not changed'));
	            	// $this->render('changePassMSG',array('model'=>$model ,'msg'=>'successfully changed password')); 
				}
			}
		}
		

		$this->render('changeCpanelPass',array('model'=>$model));
	}


	public function actionchangePassMSG()
	{
		Login::UserAuth('Cpanel','Changepassword');	
		$msg = $_GET['msg'];
		$this->render('changePassMSG',array('msg'=>$msg)); //'model'=>$model ,
	}
	
	public function actionForgetpassword($id)
	{
		Login::UserAuth('Cpanel','Forgetpassword');	
		$model = $this->loadModel($id);
		
		if(isset($_POST['Cpanel'])){
					
			if($_POST['Cpanel']['password'] == $_POST['Cpanel']['confirmpassword']){
				$hashPass = md5($_POST['Cpanel']['password']);
				
				$SQL = "UPDATE cpanel SET password = '".$hashPass."' WHERE cp_id = ".$id;
				
				$upCpanel = Yii::app()->db->createCommand($SQL)->execute();
				
				if($upCpanel == 1){
	            	$this->redirect(array('/Cpanel/changePassMSG?msg=successfully changed password')); 
				}else{
	            	$this->redirect(array('/Cpanel/changePassMSG?msg=password not changed')); 
				}
				
			}else{
				 $this->render("forgetCpanelPass",array( "model" =>  $model, "error_message" => "Password don't Match!", ));
			}
			
		}else{
		
			$this->render('forgetCpanelPass',array('model'=>$model ));
		}
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cpanel the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cpanel::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cpanel $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cpanel-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	
	public function actionConvertLang()
	{
		
		//$_POST['link_id'] = CI_Security::ChkPost($_POST['link_id']);	
		$UserLang = $_POST['link_id'];

		Login::GetUserLang($UserLang);
	}
	
	
	
	
}

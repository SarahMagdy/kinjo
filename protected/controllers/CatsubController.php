<?php

class CatsubController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','AjaxCreate'),
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
		Login::UserAuth('Catsub','View');
		
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
		//print_r(Yii::app()->session['User']);
		Login::UserAuth('Catsub','Create');
		Login::ChkBuSess();
		$model=new Catsub;
		$type = 'form';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Catsub']))
		{
			$_POST['Catsub'] = CI_Security::ChkPost($_POST['Catsub']);
			
			$model->attributes=$_POST['Catsub'];
			
			$rnd = $random = date(time());
			//$uploadedImg = CUploadedFile::getInstance($model, 'img_url');
			$uploadedImg = new upload($_FILES['img_url']);
			$ImgName = "";
			//if($uploadedImg != null){
			if($uploadedImg->file_src_name != null){
			     //$ImgName = "{$rnd}-{$uploadedImg}"; 
				 $ImgName = "{$rnd}-$uploadedImg->file_src_name_body";
				 $ImgName = md5($ImgName); 
				 $model->img_thumb = $ImgName.'.'.$uploadedImg->file_src_name_ext;
				 $model->img_url = $ImgName.'.'.$uploadedImg->file_src_name_ext;
			 }else{
			     $ImgName = 'default.jpg';   
				 $model->img_thumb = $ImgName;
				 $model->img_url = $ImgName; 
			 }

			
			$model->catsub_buid = Yii::app()->session['User']['UserBuid'];
			if($model->save()){
				
				if($uploadedImg != null){
					 $RealArr = Globals::ReturnGlobals();
					 $RealPath = $RealArr['ImgPath'].'catsub/';
				 	 /*
					  $uploadedImg->saveAs($RealPath.$ImgName,false);
					  $image = new EasyImage($RealPath.$ImgName);
					  $image->resize(100, 100);
					  $image->save($RealPath.'thumbnails/'.$ImgName);*/
					
					// ---- save resized image -------------
			       	$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_ratio = true;
				    $uploadedImg->image_x = strstr($_POST['Catsub']['Dimensions'], 'x', true);
				    $uploadedImg->image_y = substr($_POST['Catsub']['Dimensions'], strpos($_POST['Catsub']['Dimensions'], "x") + 1);
				    $uploadedImg->process($RealPath);
					// ---- save thumbnail image -----------
					$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_ratio = true;
					$uploadedImg->image_x = 100;
				    $uploadedImg->image_y = 100;
					$uploadedImg->process($RealPath.'thumbnails/');
					  
				 }
				$this->redirect(array('view','id'=>$model->csid));
			}
				
		}

		$this->render('create',array(
			'model'=>$model,
			'type'=>$type,
		));
	}
	
	public function actionAjaxCreate()
	{
		Login::UserAuth('Catsub','Create');	
		Login::ChkBuSess();
		$model=new Catsub;
		$type = 'ajax';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST) && !empty($_POST))
		{
			$_POST = CI_Security::ChkPost($_POST);
				
			//$model->attributes=$_POST['Catsub'];
			$model->parent_id = $_POST['parent_id'];
			$model->title = $_POST['title'];
			$model->desription = $_POST['desription'];
			// $model->catsub_buid = $_POST['buid'];
			$model->catsub_buid = Yii::app()->session['User']['UserBuid'];
			
			$model->save();
			
			$LastID =  Yii::app()->db->getLastInsertID();
			
			print $LastID;
			
			return;
			
			//if($model->save())
				//$this->redirect(array('view','id'=>$model->csid));
		}

		$this->renderPartial('create',array(
			'model'=>$model,
			'type'=>$type,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		Login::UserAuth('Catsub','Update');
		Login::ChkBuSess();
		$model=$this->loadModel($id);
		$type = 'form';
		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		$RealArr = Globals::ReturnGlobals();
		$RealPath = $RealArr['ImgPath'].'catsub/';
		
		$handle = new upload($RealPath.$model->img_url);
		
		$dimensions = $handle->image_src_x .'x'.$handle->image_src_y;
		
		if(isset($_POST['Catsub']))
		{
			$_POST['Catsub'] = CI_Security::ChkPost($_POST['Catsub']);
				
			$_POST['Catsub']['img_url'] = $model->img_url;	
			$model->attributes=$_POST['Catsub'];
			$old_img = $model->img_url;
                        var_dump($old_img);
			$rnd = $random = date(time());
			//$uploadedImg = CUploadedFile::getInstance($model, 'img_url');
			$uploadedImg = new upload($_FILES['img_url']);
			$ImgName = "";
			//if($uploadedImg != null){
			if($uploadedImg->file_src_name != null){
			     //$ImgName = "{$rnd}-{$uploadedImg}"; 
			     $ImgName = "{$rnd}-$uploadedImg->file_src_name_body";
				 $ImgName = md5($ImgName);
                                 $model->img_thumb = $ImgName.'.'.$uploadedImg->file_src_name_ext;
                                 var_dump( $model->img_thumb = $ImgName.'.'.$uploadedImg->file_src_name_ext);
				 $model->img_url = $ImgName.'.'.$uploadedImg->file_src_name_ext;
                                 var_dump($model->img_url = $ImgName.'.'.$uploadedImg->file_src_name_ext);
			 }else{
			     $ImgName = $old_img;   
                             echo '$ImgName';
				 $model->img_thumb = $ImgName;
                                 var_dump($model->img_thumb) ;
			     $model->img_url = $ImgName; 
                             var_dump($model->img_url);
			 }

			
			 $model->catsub_buid = Yii::app()->session['User']['UserBuid'];
			if($model->save()){
				
				if($uploadedImg != null){
					var_dump($model->img_url);

					if($model->img_url != '' && $old_img != 'default.jpg'){
		               if(file_exists($RealPath.$old_img)){unlink($RealPath.$old_img);}
		               if(file_exists($RealPath.$old_img)){unlink($RealPath.'thumbnails/'.$old_img);}
		     		 }
					
					/*
					$uploadedImg->saveAs($RealPath.$ImgName); 
					$image = new EasyImage($RealPath.$ImgName);
					$image->resize(100, 100);
					$image->save($RealPath.'/thumbnails/'.$ImgName);*/
					
					// ---- save resized image -------------
			       	$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_ratio = true;
				    $uploadedImg->image_x = strstr($_POST['Catsub']['Dimensions'], 'x', true);
				    $uploadedImg->image_y = substr($_POST['Catsub']['Dimensions'], strpos($_POST['Catsub']['Dimensions'], "x") + 1);
				    $uploadedImg->process($RealPath);
					// ---- save thumbnail image -----------
					$uploadedImg->file_new_name_body = $ImgName;
					$uploadedImg->image_resize = true;
					$uploadedImg->image_ratio = true;
					$uploadedImg->image_x = 100;
				    $uploadedImg->image_y = 100;
					$uploadedImg->process($RealPath.'thumbnails/');
					
					
				}
					$this->redirect(array('view','id'=>$model->csid));
			}
				
		}

		$this->render('update',array(
			'model'=>$model,
			'type'=>$type,
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
		Login::UserAuth('Catsub','Delete');
		//$this->loadModel($id)->delete();
		Login::ChkBuSess();
		$m_model = $this->loadModel($id);
		
		$RealArr = Globals::ReturnGlobals();
		$RealPath = $RealArr['ImgPath'].'catsub/';
		
		if($m_model->img_url != ''&& $m_model->img_url != 'default.jpg'){
             if(file_exists($RealPath.$m_model->img_url)){unlink($RealPath.$m_model->img_url);}
             if(file_exists($RealPath.'thumbnails/'.$m_model->img_url)){unlink($RealPath.'thumbnails/'.$m_model->img_url);}
       }

		 $m_model->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
		{
			if(isset($_POST['returnUrl']))
			{
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
		Login::UserAuth('Catsub','Index');
		Login::ChkBuSess();
		// $dataProvider=new CActiveDataProvider('Catsub');
		$criteria = new CDbCriteria();
		$criteria->addSearchCondition('catsub_buid', Yii::app()->session['User']['UserBuid']);
		// $criteria->addInCondition('t.id',$array, 'OR');
		// $criteria->addCondition('t.value = FALSE');
		
		// $criteria->with=array('status_relation');
		
		$dataProvider = new CActiveDataProvider('Catsub', array('criteria'=>$criteria));
		
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('Catsub','Admin');
		Login::ChkBuSess();
		$model=new Catsub('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Catsub']))
		{
			$_GET['Catsub'] = CI_Security::ChkPost($_GET['Catsub']);
			$model->attributes=$_GET['Catsub'];
		}

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Catsub the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Catsub::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Catsub $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='catsub-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}

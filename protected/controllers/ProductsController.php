<?php

class ProductsController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete','advanced','ajaxaddconf','ajaxDeleteImg' , 
								 'ajaxUpdateImg','ajaxnewconf','ajaxdelconf','UpdateProQRCode', 'Color' , 'ajaxDeleteColor',
								 'OpenNotify','ajaxSubmitNotify','ajaxGetSubConf'),

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
		Login::UserAuth('Products','View');
		Login::ChkBuSess();
		//exit;
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	/**
	 * Displays a advanced setting model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionAdvanced($id)
	{
		Login::UserAuth('Products','Advanced');
		Login::ChkBuSess();
		$PdConfigD = Yii::app()->db->createCommand('SELECT pdconfv_id,pdconfv_pid,pdconfv_confid,name,pdconfv_value,pdconfv_chkrad,
												   (Case when pdconfv_chkrad = 0 then "Checkable" else "Radio" end)AS ChkRad,
												   parent_id
												   FROM pd_conf_v
												   LEFT JOIN pd_config ON cfg_id = pdconfv_confid 
												   Where pdconfv_pid ='.$id)->queryAll();
             
		$PdConfG = array();	
											  
		foreach ($PdConfigD as $key => $row) {
			
			if($row['parent_id'] > 0){
				
				$PdConfG[$row['parent_id']]['sub'][$row['pdconfv_confid']]['name']=$row['name'];
				$PdConfG[$row['parent_id']]['sub'][$row['pdconfv_confid']]['value']=$row['pdconfv_value'];
				$PdConfG[$row['parent_id']]['sub'][$row['pdconfv_confid']]['Sconfid']=$row['pdconfv_confid'];
				
			}else{
				
				$PdConfG[$row['pdconfv_confid']]['name']=$row['name'];
				$PdConfG[$row['pdconfv_confid']]['ChkRad']=$row['ChkRad'];
				$PdConfG[$row['pdconfv_confid']]['pdconfv_chkrad']=$row['pdconfv_chkrad'];
				$PdConfG[$row['pdconfv_confid']]['Pconfid']=$row['pdconfv_confid'];

				
			}
		}
		
		$this->render('advanced',array(
			'model'=>$this->loadModel($id),
			'PdConfG'=>$PdConfG
		));
	}
	
	public function actionColor($id)
	{
		Login::ChkAuthType('Products','Color');
		Login::ChkBuSess();
		$ProColors = Yii::app()->db->createCommand("SELECT color_id , color_code , color_name
												    FROM prod_colors
												    WHERE color_pid = ".$id)->queryAll();
		$ProColors_arr = array();	
		foreach ($ProColors as $key => $row) 
		{
			$ProColors_arr[$row['color_id']]['color_id'] = $row['color_id'];
			$ProColors_arr[$row['color_id']]['color_name'] = $row['color_name'];
			$ProColors_arr[$row['color_id']]['color_code'] = $row['color_code'];
		}
		if(isset($_POST['color_code']) && isset($_POST['color_name']) && !empty($_POST['color_code']) && !empty($_POST['color_name'])){
			
			
			$Sql = "INSERT INTO prod_colors (color_pid , color_code , color_name) 
					VALUES (".$id." , '".$_POST['color_code']."' , '".$_POST['color_name']."')";
			Yii::app()->db->createCommand($Sql)->execute();
		}

		$this->render('colors',array(
			'model'=>$this->loadModel($id),
			'ProColors_arr'=>$ProColors_arr
		));		
		
	}
	
	public function actionAjaxDeleteColor()
	{
		Login::UserAuth('Products','Color');
		Login::ChkBuSess();	
		$_POST = CI_Security::ChkPost($_POST);
		$sql = 'DELETE from prod_colors WHERE color_id='.$_POST['colorId'];		
		$command=Yii::app()->db->createCommand($sql);
		$command->execute();
	}
	
	
	
	public function actionAjaxaddconf()
	{
		Login::UserAuth('Products','Advanced');
		Login::ChkBuSess();
		$_POST = CI_Security::ChkPost($_POST);
		if(isset($_POST['prodID']) && isset($_POST['confID']) && $_POST['confID'] > 0 && ($_POST['SubType']== 'add'||$_POST['SubType']== 'edit')){
				
			/*
			$PdConfC = Yii::app()->db->createCommand('SELECT count(pdconfv_id) AS pc FROM pd_conf_v
													  WHERE pdconfv_pid = '.$_POST['prodID'].' AND pdconfv_confid = '.$_POST['confID'].'')->queryRow();
			
			if($PdConfC['pc'] > 0){
				
				print 'insert_before';
				
			}else{
				
				Yii::app()->db->createCommand('insert into pd_conf_v (pdconfv_pid,pdconfv_confid,pdconfv_value,pdconfv_chkrad)
											VALUES('.$_POST['prodID'].','.$_POST['confID'].','.$_POST['value'].','.$_POST['ChkRad'].')')->execute();
					
				
				//$this->redirect("/index.php/products/advanced/".$_POST['prodID']);				   
				
			}*/
			$InsSQL = "INSERT INTO pd_conf_v(pdconfv_pid,pdconfv_confid,pdconfv_value,pdconfv_chkrad) VALUES ";
			
			if($_POST['SubType']== 'add'){
			
				$InsSQL.= "(".$_POST['prodID'].",".$_POST['confID'].",0,".$_POST['ChkRad']."),";	
			}
			
			if($_POST['SubType']== 'edit'){
				
				$DelSQL = " DELETE FROM pd_conf_v WHERE pdconfv_confid IN (SELECT cfg_id FROM pd_config WHERE parent_id = ".$_POST['confID'].")";
				Yii::app()->db->createCommand($DelSQL)->execute();
		
			}

			$SubArr = $_POST['SubConf'];
			foreach ($SubArr as $Key => $Val) {
				
				$InsSQL.= "(".$_POST['prodID'].",".$Key.",".$Val.",".$_POST['ChkRad']."),";
			}
			
			$InsSQL = rtrim($InsSQL,',');
			Yii::app()->db->createCommand($InsSQL)->execute();
			
		}

		
	}
	
	public function actionAjaxnewconf()
	{
		Login::UserAuth('Products','Advanced');
		Login::ChkBuSess();	
		$_POST = CI_Security::ChkPost($_POST);
		if(isset($_POST['name']))
		{
			$conf = new PdConfig;
			$conf->parent_id = $_POST['parent_id'];
			$conf->conf_buid = Yii::app()->session['User']['UserBuid'];
			$conf->name = $_POST['name'];
			$conf->value = $_POST['value'];
			$conf->save();					   
		}
	}
	
	public function actionAjaxdelconf()
	{
		Login::UserAuth('Products','Advanced');
		Login::ChkBuSess();	
		$_POST = CI_Security::ChkPost($_POST);
		if(isset($_POST['ID']))
		{
			if($_POST['type'] == 'ch')
			{
				Yii::app()->db->createCommand('Delete From pd_conf_v Where pdconfv_pid = '.$_POST['prodID'].' AND pdconfv_confid ='.$_POST['ID'])->execute();	
			}
  
			if($_POST['type'] == 'par')
			{
				Yii::app()->db->createCommand('Delete From pd_conf_v Where pdconfv_pid = '.$_POST['prodID'].' AND pdconfv_confid IN(Select cfg_id From pd_config where parent_id = '.$_POST['ID'].')')->execute();	
				
				Yii::app()->db->createCommand('Delete From pd_conf_v Where pdconfv_pid = '.$_POST['prodID'].' AND pdconfv_confid ='.$_POST['ID'])->execute();	 
			}
		}
	}
	
	public function actionAjaxGetSubConf($id)
	{
		Login::UserAuth('Products','Advanced');	
		Login::ChkBuSess();
		$SubArr = array();
		
		if($id > 0){
			
			$SubSql = 'SELECT * FROM pd_config WHERE parent_id ='.$id;		
			$SubData = Yii::app()-> db->createCommand($SubSql)->queryAll();
			
			foreach ($SubData AS $Subkey=>$SubRow) {
				array_push($SubArr,array('ConfID'=>$SubRow['cfg_id'],'ConfN'=>$SubRow['name'],'ConfV'=>$SubRow['value']));
			}
		}
		echo json_encode($SubArr);
	}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		Login::UserAuth('Products','Create');
		Login::ChkBuSess();		
		$model=new Products;
		// $img_model = new ProductsImgs;
		
		//$CatD = Catsub::model()->findAll(array("select"=>"csid,title,parent_id","order"=>"csid","condition"=>"catsub_buid = ".Yii::app()->session['User']['UserBuid']));
		
		$SQL = "SELECT csid,title,parent_id FROM catsub WHERE catsub_buid = ".Yii::app()->session['User']['UserBuid'];
		$CatD = Yii::app()->db->createCommand($SQL)->queryAll();
		
		$CatData = array();
		if(!empty($CatD)){
			 foreach ($CatD as $key => $row) {
				 if(!isset($row['parent_id']) || $row['parent_id'] == NULL||$row['parent_id'] == ''){
				 	 $CatData[$row['csid']]['id']=$row['csid'];
					 $CatData[$row['csid']]['title']=$row['title'];
				 }else {
					 $CatData[$row['parent_id']]['sub'][$row['csid']]['id']=$row['csid'];
					 $CatData[$row['parent_id']]['sub'][$row['csid']]['title']=$row['title'];
					 $CatData[$row['parent_id']]['disable'] = '1';
				 }
			 }
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		
		if(isset($_POST['Products']))
		{
			$model->attributes=$_POST['Products'];
			$_POST['Products'] = CI_Security::ChkPost($_POST['Products']);
			$model->buid = Yii::app()->session['User']['UserBuid'];
			
			$ImgArr = Globals::ReturnGlobals();
       		$ImgPath = $ImgArr['ImgPath'].'products/';
			
			if($model->save()){
				$ProID = Yii::app()->db->getLastInsertID();
				$this->UpdateProQRCode($ProID);
				
				$files = array();
				foreach ($_FILES['ProImg'] as $k => $l) {
					foreach ($l as $i => $v) {
						 if (!array_key_exists($i, $files))
					  	 $files[$i] = array();
					   	 $files[$i][$k] = $v;
					 }
				}   
				$count = 1;
				foreach ($files as $file) {
				    // should output array with indices name, type, tmp_name, error, size
				  	$uploadedImg = new upload($file);
					if($uploadedImg->file_src_name != null){
						$ImgName = "";
						$rnd = $random = date(time());
						$ImgName = "{$rnd}-{$count}-$uploadedImg->file_src_name_body";
				 		$ImgName = md5($ImgName); 
						$count += 1;
						// ---- save resized image -------------
				       	$uploadedImg->file_new_name_body = $ImgName;
						$uploadedImg->image_resize = true;
						$uploadedImg->image_ratio = true;
					    $uploadedImg->image_x = strstr($_POST['Products']['Dimensions'], 'x', true);
					    $uploadedImg->image_y = substr($_POST['Products']['Dimensions'], strpos($_POST['Products']['Dimensions'], "x") + 1);
					    $uploadedImg->process($ImgPath);
						// ---- save thumbnail image -----------
						$uploadedImg->file_new_name_body = $ImgName;
						$uploadedImg->image_resize = true;
						$uploadedImg->image_x = 100;
					    $uploadedImg->image_y = 100;
						$uploadedImg->process($ImgPath.'thumbnails/');	
						//------Insert Imgs
						$ImgName = $ImgName.'.'.$uploadedImg->file_src_name_ext ;
						$ImgSql = "INSERT INTO products_imgs (pid, pimg_url , pimg_thumb) 
								   VALUES (".$ProID.",
								   		   '".$ImgName."',
								   		   '".$ImgName."')";
						Yii::app()->db->createCommand($ImgSql)->execute();
						
					}
				}
				
			
				
				
				$this->redirect(array('view','id'=>$model->pid));
			}
		}
		
		
		$this->render('create',array(
			'model'=>$model,
			'CatData'=>$CatData,
		));
	}

	private function UpdateProQRCode($prod_id)
	{
		$text = Yii::app()->session['User']['UserOwnerID']."-".Yii::app()->session['User']['UserBuid']."-".$prod_id;
	
		$cipher = new Cipher('secret passphrase');

		$encryptedtext = $cipher->encrypt($text);
		//echo "encrypt = $encryptedtext<br />";
		
		$imgSource = "http://chart.apis.google.com/chart?chs=250x250&cht=qr&chl=".$encryptedtext."' alt='QR code' width='250' height='250'";
		
		$Sql = "UPDATE products SET qrcode = '".$encryptedtext."'
				WHERE pid = ".$prod_id;				   
		Yii::app()->db->createCommand($Sql)->execute();
		
		$QR_Image = QrCodes::generateQR($encryptedtext);
		
		//$decryptedtext = $cipher->decrypt($encryptedtext);
		//echo "decrypt = $decryptedtext<br />";
		//var_dump($cipher);
		return $imgSource;
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		Login::UserAuth('Products','Update');
		Login::ChkBuSess();
		$model=$this->loadModel($id);
		// $img_model = new ProductsImgs;
		
		//$CatD = Catsub::model()->findAll(array("select"=>"csid,title,parent_id","order"=>"csid"));
		$SQL = "SELECT csid,title,parent_id FROM catsub WHERE catsub_buid = ".Yii::app()->session['User']['UserBuid'];
		$CatD = Yii::app()->db->createCommand($SQL)->queryAll();
		
		$CatData = array();
		if(!empty($CatD)){
			 foreach ($CatD as $key => $row) {
				 if(!isset($row['parent_id']) || $row['parent_id'] == NULL||$row['parent_id'] == ''){
				 	 $CatData[$row['csid']]['id']=$row['csid'];
					 $CatData[$row['csid']]['title']=$row['title'];
				 }else {
					 $CatData[$row['parent_id']]['sub'][$row['csid']]['id']=$row['csid'];
					 $CatData[$row['parent_id']]['sub'][$row['csid']]['title']=$row['title'];
					 $CatData[$row['parent_id']]['disable'] = '1';
				 }
			 }
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Products']))
		{
			$_POST['Products'] = CI_Security::ChkPost($_POST['Products']);	
			$model->attributes=$_POST['Products'];
			
			// $images = CUploadedFile::getInstance($model, 'img');
			// $rnd = $random = date(time());
			// $ImageName = "{$rnd}-{$images}";
			// $oldimg = ProductsImgs::model()->findAllByAttributes(array('pid'=>$model->pid));
			// foreach($oldimg as $image => $pic){
				// $oldimgName = $pic->pimg_thumb;
			// }
			// if($images != null){
				// unlink(Yii::app()->basePath.'/../public/images/upload/products/'.$oldimgName);
				// unlink(Yii::app()->basePath.'/../public/images/upload/products/thumbnails/'.$oldimgName);
			// }
			$model->buid = Yii::app()->session['User']['UserBuid'];
			if($model->save()){
				
				// $images->saveAs(Yii::app()->basePath.'/../public/images/upload/products/'.$ImageName) ;
				// $myImage = new EasyImage(Yii::app()->basePath.'/../public/images/upload/products/'.$ImageName);
		       	// $myImage->resize(100, 100);
		       	// $myImage->save(Yii::app()->basePath.'/../public/images/upload/products/thumbnails/'.$ImageName);
				
				
				// $sql = 'DELETE from products_imgs WHERE pid='.$model->pid.';
						// insert into products_imgs (pid, pimg_url , pimg_thumb)
						 		// VALUES('.$model->pid.', "'.$ImageName.'", "'.$ImageName.'")';
				// $command=Yii::app()->db->createCommand($sql);
				// $command->execute();
				
				$this->redirect(array('view','id'=>$model->pid));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'CatData'=>$CatData,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		Login::UserAuth('Products','Delete');
		Login::ChkBuSess();
		// $this->loadModel($id)->delete();
		$m_model = $this->loadModel($id);

		//$img_path = Yii::app()->basePath.'/../public/images/upload/products/';
		
		$RealArr = Globals::ReturnGlobals();
	    $img_path = $RealArr['ImgPath'].'products/';
		
		$oldimg = ProductsImgs::model()->findAllByAttributes(array('pid'=>$m_model->pid));
			
		foreach($oldimg as $image => $pic){
			$oldimgName = $pic->pimg_thumb;
			if($oldimgName != null){
				if(file_exists($img_path.$oldimgName)){unlink($img_path.$oldimgName);}
				if(file_exists($img_path.'thumbnails/'.$oldimgName)){unlink($img_path.'thumbnails/'.$oldimgName);}
			}
		}
		// if($oldimgName != null){
			// unlink($img_path.'/'.$oldimgName);
			// unlink($img_path.'thumbnails/'.$oldimgName);
		// }
		
		$sql = 'DELETE from products_imgs WHERE pid='.$m_model->pid;
		
		$command=Yii::app()->db->createCommand($sql);
		$command->execute();
		
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
		Login::UserAuth('Products','Index');
		Login::ChkBuSess();
		$dataProvider=new CActiveDataProvider('Products', array('criteria' => array('condition' => 'buid='.Yii::app()->session['User']['UserBuid'])));
		//$dataProvider=new CActiveDataProvider('Products');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		Login::UserAuth('Products','Admin');
		Login::ChkBuSess();
		$model=new Products('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Products']))
			$model->attributes=$_GET['Products'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Products the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Products::model()->findByPk($id);
		//$model=Products::model()->with('Catsub','BusinessUnit')->findByPk($id);
		
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Products $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='products-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	public function actionAjaxDeleteImg()
	{
		Login::UserAuth('Products','Create');
		Login::ChkBuSess();	
		// $model=$this->loadModel($_POST['proID']);
		//$img_path = Yii::app()->basePath.'/../public/images/upload/products/';
		$RealArr = Globals::ReturnGlobals();
		$RealPath = $RealArr['ImgPath'].'products/';
		
		$sql = 'DELETE from products_imgs WHERE pimgid='.$_POST['imgId'];		
		$command=Yii::app()->db->createCommand($sql);
		$command->execute();
		
		if($_POST['imgName'] != null){
			if(file_exists($RealPath.$_POST['imgName'])){unlink($RealPath.$_POST['imgName']);}
			if(file_exists($RealPath.'thumbnails/'.$_POST['imgName'])){unlink($RealPath.'thumbnails/'.$_POST['imgName']);}
		}
		// $this->redirect(array('view','id'=>$model->pid));
	}
	
	
	public function actionAjaxUpdateImg()
	{
		Login::UserAuth('Products','Create');
		Login::ChkBuSess();	
		$model=$this->loadModel($_POST['proID']);
		// $_POST['imgId']=17;
		$rnd = $random = date(time());
		
		$img_path = $_SERVER['SERVER_NAME'].'/index.php/public/images/upload/products/';
		//$uploaddir =  '/var/www/html/kinjo/public/images/upload/products/';
		$RealArr = Globals::ReturnGlobals();
		$uploaddir = $RealArr['ImgPath'].'products/';
		
		$uploadfile =  $uploaddir.$rnd.'-'.basename($_FILES['picturess']['name']);
		$newimgName = $rnd.'-'.basename($_FILES['picturess']['name']);
		
		if (move_uploaded_file($_FILES['picturess']['tmp_name'], $uploadfile)) {
					    
		    // unlink($uploaddir.$_POST['oldimgName']);
			// unlink($uploaddir.'thumbnails/'.$_POST['oldimgName']);

			
			// print_r($_POST['oldimgName']);
			$myImage = new EasyImage($uploadfile);
			$myImage->resize(100, 100);
			$myImage->save($uploaddir.'/thumbnails/'.$newimgName);
			
			
			
			// $sql = 'UPDATE products_imgs 
					// SET pimg_url="'.$newimgName.'" , pimg_thumb="'.$newimgName.'"
					// WHERE pimgid='.$_POST['imgId'];
					
			$sql = 'insert into products_imgs (pid, pimg_url , pimg_thumb)
								VALUES('.$_POST['proID'].', "'.$newimgName.'", "'.$newimgName.'")';
			$command=Yii::app()->db->createCommand($sql);
			$command->execute();
			
		    
		} else {
		    echo "Possible file upload attack!\n";
		}
		
		$this->redirect(array('view','id'=>$model->pid));
				
	}
	
	public function actionOpenNotify($id){
			
		Login::UserAuth('Products','OpenNotify');
		Login::ChkBuSess();	
		$model=$this->loadModel($id);
		
		$type = 0;
		if(isset($_GET['type'])){
			$type = $_GET['type'];
		}
		
		if($type == 1){
			
			$SQL = " SELECT * FROM messages_log WHERE mid = ".$model->pid." AND is_group = 3  AND DATE(`date`) = CURDATE()";
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
			
		Login::UserAuth('Products','OpenNotify');
		Login::ChkBuSess();	
		$RegsArr = array();	
		
		$SQL = " SELECT puid,gcm_regid,cid 
				 FROM 
				(SELECT puid,gcm_regid,push_notifications.cid 
				 FROM push_notifications 
				 WHERE cid IN (SELECT DISTINCT subscriptions.cid  
				 			   FROM subscriptions 
				 			   LEFT JOIN customers ON customers.cid = subscriptions.cid 
				 			   WHERE csid = ".$_POST['CatID']." AND SUBSTRING(notify_enable,4,1) = 0)
				 ORDER BY count_dev DESC )AS T_Push GROUP BY cid ";
				 
		$CustRegs = Yii::app()->db->createCommand($SQL)->queryAll();
		
		if(count($CustRegs) > 0){
			$SQLMess = " INSERT INTO messages_log (mid,cid,puid,is_group) VALUES ";
			
			foreach ($CustRegs as $key => $row) {
				
				array_push($RegsArr,$row['gcm_regid']);
				
				$SQLMess .= " (".$_POST['ProdID'].",".$row['cid'].",".$row['puid'].", 3),";
			}
			
			$SQLMess = substr($SQLMess, 0, -1);
			
			Yii::app()->db->createCommand($SQLMess)->execute();
			
			$ResArr = array();
			$ResArr['Type']= 'Product';
			$ResArr['Mess']= trim($_POST['MessTxt']);
			$ResArr['Data']= CustLib::actionGetProdDetailsByProdID(array('ProID'=>$_POST['ProdID']));
			
			$ResGCM  = GCM::SendNotification($RegsArr, json_encode($ResArr));
			
			$ResGCM =  json_decode($ResGCM,TRUE);
				
			if($ResGCM['failure'] == '0'&& $ResGCM['success'] > 0){
				
				$ResMess = 'Send Notification';
				
			}else{
					
				$ResMess = 'Invalid Notification';
			}
			
		}else{ $ResMess = 'Invalid Notification';}
		
		echo $ResMess;
			
		
	}
	
	
}



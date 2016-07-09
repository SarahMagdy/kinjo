<?php

class CommonController extends Controller
{

//----------------------------------------------Images----------------------------------
		
	public function init()
	{
		parent::init();
		Yii::app()->language = Yii::app()->session['Language']['UserLang'];
	}
	
	public function actionMyImgsCrop()// $modelName , $id //actionLoadMyImgs//BuAccounts
	{
		
		$_GET = CI_Security::ChkPost($_GET);
			
		// Login::UserAuth('BuAccounts','View');
		// $model = $this->loadModel($id);
		
		if($_GET['mName'] == 'Products'){
			// $x = ProductsImgs::model()->findAllByAttributes(array('pid'=>$_GET['ProImgID']));
			$P_Sql = " SELECT * FROM products_imgs WHERE pimgid = ".$_GET['ProImgID']." AND pid = ".$_GET['mID'];
			$sqlResult = Yii::app()->db->createCommand($P_Sql)->queryRow();	
		}
		
		$model = $_GET['mName']::model()->findByPk($_GET['mID']);
		$RealAdrr = Globals::ReturnGlobals();
		
		$modelArr =  array();
		if($_GET['mName'] == 'BuAccounts'){
			$modelArr['uploaddir'] = $RealAdrr['ImgSerPath'].'bu_accounts/';
			$modelArr['file'] = $RealAdrr['ImgPath'].'bu_accounts/';
			$modelArr['imgName'] = $model->photo;
			
		}else if($_GET['mName'] == 'Catsub'){
		
			$modelArr['uploaddir'] = $RealAdrr['ImgSerPath'].'catsub/';
			$modelArr['file'] = $RealAdrr['ImgPath'].'catsub/';
			$modelArr['imgName'] = $model->img_url;
			
		}else if($_GET['mName'] == 'BusinessUnit'){
		
			$modelArr['uploaddir'] = $RealAdrr['ImgSerPath'].'business_unit/';
			$modelArr['file'] = $RealAdrr['ImgPath'].'business_unit/';
			$modelArr['imgName'] = $model->logo;
			
		}else if($_GET['mName'] == 'Products'){
			$modelArr['uploaddir'] = $RealAdrr['ImgSerPath'].'products/';
			$modelArr['file'] = $RealAdrr['ImgPath'].'products/';
			$modelArr['imgName'] = $sqlResult['pimg_url'];//'pimg_url';
		}else if($_GET['mName'] == 'Admins'){
			$modelArr['uploaddir'] = $RealAdrr['ImgSerPath'].'admins/';
			$modelArr['file'] = $RealAdrr['ImgPath'].'admins/';
			$modelArr['imgName'] = $model->photo;
		}else if($_GET['mName'] == 'Cpanel'){
			$modelArr['uploaddir'] = $RealAdrr['ImgSerPath'].'cpanel/';
			$modelArr['file'] = $RealAdrr['ImgPath'].'cpanel/';
			$modelArr['imgName'] = $model->photo;
		}
		
		
		$modelArr['modelID'] = $_GET['mID'];
		$modelArr['modelName'] = $_GET['mName'];
		$modelArr['ProImgID'] = $_GET['ProImgID'];
		
		if($_POST){
			
			// $_POST = CI_Security::ChkPost($_POST);
			// echo '</pre>';
			// print_r($_POST);
			// return;	
			$rnd = $random = date(time());
			
			$My_imageName = strstr($_POST['My_imageName'], '.', true);
			$newimgName = $rnd.'-'.$My_imageName;//basename($_FILES['inputImage']['name']);
			
			$tblName = ''; 		
			$sqlSet = '';
			$sqlWhere = ''; $colName = '';
			if($_GET['mName'] == 'BuAccounts'){
				$tblName = 'bu_accounts';
				$colName = 'photo';
				$sqlSet = ' SET photo = "'.$newimgName .'.jpg"';
				$sqlWhere = ' WHERE accid = '.$_GET['mID'];
				
				
			}else if($_GET['mName'] == 'Catsub'){
				$tblName = 'catsub';
				$colName = 'img_url';
				$sqlSet = ' SET img_thumb = "'.$newimgName .'.jpg"'.' , img_url = "'.$newimgName .'.jpg"';
				$sqlWhere = ' WHERE csid = '.$_GET['mID'];
				
			}else if($_GET['mName'] == 'BusinessUnit'){
				$tblName = 'business_unit';
				$colName = 'logo';
				$sqlSet = ' SET logo = "'.$newimgName .'.jpg"'.', urlid = "'.$newimgName .'.jpg"';
				$sqlWhere = ' WHERE buid = '.$_GET['mID'];
				
			}else if($_GET['mName'] == 'Products'){
				$tblName = 'products_imgs';
				$colName = 'pimg_url';				
				$sqlSet = ' SET pimg_url = "'.$newimgName .'.jpg"'.', pimg_thumb = "'.$newimgName .'.jpg"';
				$sqlWhere = ' WHERE pimgid = '.$_GET['ProImgID'];
			}else if($_GET['mName'] == 'Admins'){
				$tblName = 'admins';
				$colName = 'photo';
				$sqlSet = ' SET photo = "'.$newimgName .'.jpg"';
				$sqlWhere = ' WHERE adid = '.$_GET['mID'];
			}else if($_GET['mName'] == 'Cpanel'){
				$tblName = 'cpanel';
				$colName = 'photo';
				$sqlSet = ' SET photo = "'.$newimgName .'.jpg"';
				$sqlWhere = ' WHERE cp_id = '.$_GET['mID'];
			}
	
			
			if($_GET['mName'] == 'Products'){
				$oldimgName = $sqlResult['pimg_url'];
			}else{
				$oldimgName = $model->$colName;//photo;
			}
			
			$img = $_POST['My_image'];
			$img = str_replace('data:image/jpeg;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);
			$file = $modelArr['file'] . $newimgName .'.jpg';
			$success = file_put_contents($file , $data);

			if($success){
				Yii::app()->db->createCommand("UPDATE ".$tblName.
											   $sqlSet . $sqlWhere)->execute();
				
				if($oldimgName != '' && $oldimgName !='default.jpg'){
					unlink($modelArr['file'].$oldimgName);
					unlink($modelArr['file'].'thumbnails/'.$oldimgName);
				}
				
				$handle = new upload($modelArr['file'] . $newimgName .'.jpg');
				$handle->file_new_name_body = $newimgName;//.'.jpg';
				$handle->image_resize = true;
				$handle->image_x = 100;
			    $handle->image_y = 100;
				$handle->process($modelArr['file'].'thumbnails/');
				
				if($_GET['mName'] != 'Products'){
					$model->$colName = $newimgName;
				}
			}else{
				echo 'Unable to save the file.';
			}
			
		}else{

			$this->renderPartial('//buAccounts/BuAccount_imgs',array(
				'model'=>$model,'modelArr'=>$modelArr//$this->loadModel($id),
			));
			// Yii::app()->basePath.'/views/buAccounts/BuAccount_imgs.php'
			
		}
	}


//----------------------------------------------Languages---------------------------------

	public function actionOpanLang()
	{
		
		$_POST = CI_Security::ChkPost($_POST);
				
		$TablesArr = array();
		$TablesArr['businessUnit']['Tname']= 'business_unit_lang';
		$TablesArr['businessUnit']['R_ID']= 'bu_lang_bu_id';
		$TablesArr['businessUnit']['L_ID']= 'bu_lang_lang_id';		
		
		
		$TablesArr['catsub']['Tname']= 'catsub_lang';
		$TablesArr['catsub']['R_ID']= 'cat_lang_cs_id';
		$TablesArr['catsub']['L_ID']= 'cat_lang_lang_id';
		
		$TablesArr['offers']['Tname']= 'offers_lang';
		$TablesArr['offers']['R_ID']= 'offer_lang_offer_id';
		$TablesArr['offers']['L_ID']= 'offer_lang_lang_id';
		
		$TablesArr['pdConfig']['Tname']= 'pd_config_lang';
		$TablesArr['pdConfig']['R_ID']= 'conf_lang_conf_id';
		$TablesArr['pdConfig']['L_ID']= 'conf_lang_lang_id';
			
		$TablesArr['products']['Tname']= 'products_lang';
		$TablesArr['products']['R_ID']= 'p_lang_pid';
		$TablesArr['products']['L_ID']= 'p_lang_lang_id';
		
		$TablesArr['colors']['Tname']= 'prod_colors_lang';
		$TablesArr['colors']['R_ID']= 'color_lang_color_id';
		$TablesArr['colors']['L_ID']= 'color_lang_lang_id';
		
		$TablesArr['messages']['Tname']= 'messages_lang';
		$TablesArr['messages']['R_ID']= 'mess_lang_mess_id';
		$TablesArr['messages']['L_ID']= 'mess_lang_lang_id';
		
		$data = array();
		$data['Type']= 'add';
		$data['ContName']= $_POST['ContName'];
		
		$SqlCol = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '".$TablesArr[$_POST['ContName']]['Tname']."'";
		$D_Col = Yii::app()->db->createCommand($SqlCol)->queryAll();
		
		foreach ($D_Col as $key => $val) {
			
			$data[$val['COLUMN_NAME']]= '';
		}
		
		$SqlCHK = " SELECT * 
					FROM  ".$TablesArr[$_POST['ContName']]['Tname']." 
					WHERE ".$TablesArr[$_POST['ContName']]['R_ID']."  = ".$_POST['RowID']."
					AND   ".$TablesArr[$_POST['ContName']]['L_ID']."  = ".$_POST['LangID'];
	
		$D_CHK = Yii::app()->db->createCommand($SqlCHK)->queryRow();
		
		if(!empty($D_CHK)){
				
			$data['Type']= 'edit';
			
			foreach ($D_CHK as $key => $val) {
			
				$data[$key]= $val;
				
			}
		}
		
		$data[$TablesArr[$_POST['ContName']]['R_ID']]= $_POST['RowID'];
		$data[$TablesArr[$_POST['ContName']]['L_ID']]= $_POST['LangID'];
		
		if($_POST['ContName'] == 'colors'){
			
			$this->renderPartial('//products/color_lang',array(
					'data'=>$data,
				));
		}
		else{
		
			$this->renderPartial('//'.$_POST['ContName'].'/form_lang',array(
					'data'=>$data,
				));
		}
	}

	public function actionSubmitLang()
	{
		$_POST = CI_Security::ChkPost($_POST);
			
		$TablesArr = array();
		$TablesArr['businessUnit']['Tname']= 'business_unit_lang';
		$TablesArr['businessUnit']['R_ID']= 'bu_lang_bu_id';
		$TablesArr['businessUnit']['L_ID']= 'bu_lang_lang_id';
		
		
		$TablesArr['catsub']['Tname']= 'catsub_lang';
		$TablesArr['catsub']['R_ID']= 'cat_lang_cs_id';
		$TablesArr['catsub']['L_ID']= 'cat_lang_lang_id';
		
		$TablesArr['offers']['Tname']= 'offers_lang';
		$TablesArr['offers']['R_ID']= 'offer_lang_offer_id';
		$TablesArr['offers']['L_ID']= 'offer_lang_lang_id';
		
		$TablesArr['pdConfig']['Tname']= 'pd_config_lang';
		$TablesArr['pdConfig']['R_ID']= 'conf_lang_conf_id';
		$TablesArr['pdConfig']['L_ID']= 'conf_lang_lang_id';
		
		$TablesArr['products']['Tname']= 'products_lang';
		$TablesArr['products']['R_ID']= 'p_lang_pid';
		$TablesArr['products']['L_ID']= 'p_lang_lang_id';

		$TablesArr['colors']['Tname']= 'prod_colors_lang';
		$TablesArr['colors']['R_ID']= 'color_lang_color_id';
		$TablesArr['colors']['L_ID']= 'color_lang_lang_id';
		
		$TablesArr['messages']['Tname']= 'messages_lang';
		$TablesArr['messages']['R_ID']= 'mess_lang_mess_id';
		$TablesArr['messages']['L_ID']= 'mess_lang_lang_id';
		
		$PostArr = $_POST;
		$SQLStrF = "";$SQLStrL = "";$SQL = '';
		
		/*
		$SqlCHK = " SELECT * 
							FROM  ".$TablesArr[$PostArr['ContName']]['Tname']." 
							WHERE ".$TablesArr[$PostArr['ContName']]['R_ID']."  = ".$_POST['RowID']."
							AND   ".$TablesArr[$PostArr['ContName']]['L_ID']."  = ".$_POST['LangID'];
				
				$D_CHK = Yii::app()->db->createCommand($SqlCHK)->queryRow();
				
				$PostArr['Type']= 'add';
				
				if(!empty($D_CHK)){
						
					$PostArr['Type']= 'edit';
				}*/
		

		
		if($PostArr['Type'] == 'add'){
	
			$SQLStrF = " INSERT INTO ".$TablesArr[$PostArr['ContName']]['Tname']."(
							  ".$TablesArr[$PostArr['ContName']]['R_ID']." ,
							  ".$TablesArr[$PostArr['ContName']]['L_ID']." , ";
				
			$SQLStrL = " ) VALUES (".$_POST['RowID']." , ".$_POST['LangID']." , ";	
					
		}	
		if($PostArr['Type'] == 'edit'){
			
			$SQLStrF = " UPDATE ".$TablesArr[$PostArr['ContName']]['Tname']." SET ";
				
			$SQLStrL = " WHERE ".$TablesArr[$PostArr['ContName']]['R_ID']." = " .$_POST['RowID']." 
						 AND   ".$TablesArr[$PostArr['ContName']]['L_ID']." = " .$_POST['LangID'] ;	
			
		}
		
		foreach ($PostArr as $key => $val) {
			
			if($key != 'ContName' && $key != 'LangID' && $key != 'RowID' && $key != 'Type'){
				
				if($PostArr['Type'] == 'add'){ 
						
					$SQLStrF .= $key." , ";
					$SQLStrL .= (is_string($val)|| $val == '') ? "'".$val."'," : $val.',';
					
				}
				if($PostArr['Type'] == 'edit'){
						
					$up_D = (is_string($val)|| $val == '')? "'".$val."'" : $val;
					$SQLStrF .= $key." = ".$up_D.',';
				}
			}
		}
	
		
		if($PostArr['Type'] == 'add'){
			
			//$SQL = trim($SQLStrF, ",").trim($SQLStrL, ",").")";
			$SQL = substr_replace($SQLStrF, "", -2).substr_replace($SQLStrL, "", -1).")";
			
		}	
		if($PostArr['Type'] == 'edit'){
			
			$SQL = trim($SQLStrF, ",").$SQLStrL;
			
		}
		
		Yii::app()->db->createCommand($SQL)->execute();
	
	}

	public function actionGetLang()
	{
		$LangArr = array();
		
		if(isset(Yii::app()->session['User'])){
			
			$Lang = isset(Yii::app()->session['User']['Lang'])?Yii::app()->session['User']['Lang']:array();
			
			if(!empty($Lang)){
				
				$LangArr = $Lang;
				
			}
		}
		
		echo json_encode($LangArr);
		
	}

	/*
	public function actionConvertLang()
		{			
			$UserLang = $_POST['link_id'];
			switch ($UserLang) {
				case 'en':
				$lang_file = 'lang-en.php';
				break;
							  case 'ar':
				$lang_file = 'lang-ar.php';
				break;
							  default:
				$lang_file = 'lang-en.php';
			}
			
			$_SESSION['Language']['UserLang'] = $_POST['link_id'];
			$_SESSION['Language']['LangFile'] = substr($lang_file, 0, strpos($lang_file, "."));
			header("Refresh:0; url=".$_POST['page_URL']);
			
			// Yii::app()->session['User']['UserLang'] = $_POST['link_id'];
			// Yii::app()->session['User']['LangFile'] = substr($lang_file, 0, strpos($lang_file, "."));
			
			// var_dump($_SESSION);
			// window.location=$_POST['page_URL'];
			// var_dump(Yii::app()->request->requestUri);
			// var_dump($_SERVER['REQUEST_URI']);
			// header("Refresh:0");
			
			// $this->render('//'.$_POST['page_URL']);
			// echo  $_POST['page_controller'];
			// $cont = new $_POST['page_controller'];
			// Yii::app()->user->setState($_SESSION['User']['LangFile'] , $xxx , $defaultVall = null );
			// Yii::app()->user->setState('LangFile',null);
			
			// print_r(Yii::app()->session['User']);
			// print_r($_SESSION['User']);
		}*/
	



}






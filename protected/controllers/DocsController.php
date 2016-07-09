<?php

class DocsController extends Controller
{
	public function actionIndex()
	{
		//$this->render('index');
	}
	
	public function init()
	{
		parent::init();
		Yii::app()->language = Yii::app()->session['Language']['UserLang'];
	}
	
	public function actionClient()
	{
		$Data = array();	
		
		//----Types	
		
		$TypesSQL = " SELECT * FROM types ";
		$TypesData =  Yii::app()->db->createCommand($TypesSQL)->queryAll();
		
		$Data['Types'] = $TypesData;
		
		//----Country
		
		$CountrySQL = " SELECT country_id,name FROM country ";
		$CountryData =  Yii::app()->db->createCommand($CountrySQL)->queryAll();	
			
		$Data['Country'] = $CountryData;
			
		$this->renderPartial('client',array('Data'=>$Data));
	}
	
	public function actionSubmitClient()
	{
		$_POST = CI_Security::ChkPost($_POST);
		//------------Bu Accounts
		
		$AccSQL = " INSERT INTO bu_accounts (special_deal_id,feature_id,fname,lname,country_id,gender,photo,mobile,email) 
					VALUES (0,2,'".$_POST['FName']."','".$_POST['LName']."',".$_POST['Country'].",".$_POST['Gender'].",'default.jpg','".$_POST['Mobile']."','".$_POST['Email']."')";
		
		Yii::app() -> db -> createCommand($AccSQL) -> execute();
		$AccID = Yii::app() -> db -> getLastInsertID();
		
		//------------Business Unit
		
		$CurrCodeSQL = " SELECT currency_code FROM country WHERE country_id = ".$_POST['Country'];
		$CurrCodeData =  Yii::app()->db->createCommand($CurrCodeSQL)->queryRow();	
		
		
		//----Logo
		$uploadedLogo = new upload($_FILES['BuLogo']);
		$LogoName = "";
		$rnd = $random = date(time());
		if($uploadedLogo->file_src_name != null){
			$LogoName = "{$rnd}-$uploadedLogo->file_src_name_body";
			$LogoName = md5($LogoName); 
			$LogoName = $LogoName.'.'.$uploadedLogo->file_src_name_ext; 
		 }else{
		     $LogoName = 'default.jpg';
		 }
		
		if($uploadedLogo != null){
				
			$RealArr = Globals::ReturnGlobals();
			$RealPath = $RealArr['ImgPath'].'business_unit/';	
			// ---- save resized image -------------
	       	$uploadedLogo->file_new_name_body = $LogoName;
			$uploadedLogo->image_resize = true;
			$uploadedLogo->image_ratio = true;
		    $uploadedLogo->image_x = strstr('400x400', 'x', true);
		    $uploadedLogo->image_y = substr('400x400', strpos('400x400', "x") + 1);
		    $uploadedLogo->process($RealPath);
			// ---- save thumbnail image -----------
			$uploadedLogo->file_new_name_body = $LogoName;
			$uploadedLogo->image_resize = true;
			$uploadedLogo->image_ratio = true;
			$uploadedLogo->image_x = 100;
		    $uploadedLogo->image_y = 100;
			$uploadedLogo->process($RealPath.'thumbnails/');
		}
		
		$BuSQL = " INSERT INTO business_unit (accid,pkg_id,title,`long`,lat,logo,description,type,currency_code) 
				   VALUES (".$AccID.",3,'".$_POST['BuName']."','".$_POST['BuLong']."','".$_POST['BuLat']."','".$LogoName."','".$_POST['BuDesc']."',".$_POST['BuType'].",'".$CurrCodeData['currency_code']."')";
		
		Yii::app() -> db -> createCommand($BuSQL) -> execute();
		
		//------------Cpanel
		
		$CpSQL = " INSERT INTO cpanel (buid,username,password,role_id,photo,email,fname,lname,level) 
				   VALUES (".$AccID.",'".$_POST['UserName']."','".md5($_POST['Password'])."',2,'default.jpg','".$_POST['Email']."','".$_POST['FName']."','".$_POST['LName']."',0)";
		
		Yii::app() -> db -> createCommand($CpSQL) -> execute();
		
		//header("Location:/index.php/docs/SuccessClient");
		
		// ---- Send E-Mail
		$To = $_POST['FName'].' '.$_POST['LName'];
		$MailTo = $_POST['Email'];
		$Subject = "Welcome To Kinjo";
		$LoginUrl = $_SERVER['SERVER_NAME'] . '/index.php/Auth/UserLogin';
		$ForgetUrl = $_SERVER['SERVER_NAME'] . '/index.php/auth/ForgetPass';
		$Message = "<html>
						<head>
							<title> Welcome To Kinjo</title>
						</head>
						<body>
							<p>Congratulations</p>
							<p>Welcome To Our System Kinjo</p>
							<p>Your UserName : ".$_POST['UserName']."</p>
							<p>Link To login </p>
							<a href ='" . $LoginUrl . "'>'" . $LoginUrl . "'</a>
							<p>If You Forget Password </p>
							<a href ='" . $ForgetUrl . "'>'" . $ForgetUrl . "'</a>
						</body>
					</html>";
		$Res =  mail::SendMail($Subject,$Message,$MailTo,$To);
      /*
        if($Res == 'Message has been sent'){
                   $ResArr = array("Result" =>'TRUE');
              }else{
                  $ResArr = array("Result" =>'FALSE');
              }*/
      
		
		header("Location:/index.php/Auth/UserLogin");
	}
		
	public function actionSuccessClient()
	{
		$this->render('success');
	}	
}
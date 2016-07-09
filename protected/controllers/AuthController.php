<?php

class AuthController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
	public function init()
	{
		parent::init();
		Yii::app()->language = Yii::app()->session['Language']['UserLang'];
	}
	
	public function actionAdminLogin()
	{
		if(!isset(Yii::app()->session['User'])){
			
			$Error = array('UserName'=>'','Password'=>'','login'=>'');
				
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				
				$_POST = CI_Security::ChkPost($_POST);	
						
				$Chk = Login::ChkReqUserNamePass($_POST['username'],$_POST['password']);
				
				if($Chk['valid']== 'True'){
				   	
					if(Login::UserLogin($_POST['username'],$_POST['password'],'admin') == TRUE){
						
							Login::RedirectUser();
						
					} else {
							
						$Error = array('UserName'=>'','Password'=>'','login'=>'Invalid Login');
					}
					
				}else{
						
					$Error = array('UserName'=>$Chk['UserName'],'Password'=>$Chk['Password'],'login'=>'');
				}
				
			}
			$this->render('adminlogin',array('error'=>$Error));
			
		} else {
			
			Login::RedirectUser();
		}
		
	}
	
	public function actionUserLogin()
	{
		if(!isset(Yii::app()->session['User'])){
			
			$Error = array('UserName'=>'','Password'=>'','login'=>'');
				
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
					
				$_POST = CI_Security::ChkPost($_POST);	
						
				$Chk = Login::ChkReqUserNamePass($_POST['username'],$_POST['password']);
				
				if($Chk['valid']== 'True'){
				   	
					if(Login::UserLogin($_POST['username'],$_POST['password'],'cpanel') == TRUE){
						
							Login::RedirectUser();
					}else{
							
						$Error = array('UserName'=>'','Password'=>'','login'=>'Invalid Login');
					}
					
				}else{
						
					$Error = array('UserName'=>$Chk['UserName'],'Password'=>$Chk['Password'],'login'=>'');
				}
				
			}
			
			$this->render('userlogin',array('error'=>$Error));
			
		} else {
			Login::RedirectUser();
		}
	}
	
	public function actionAdminHome(){
			
		Login::UserAuth('Admins','Home');
		$this->render('adminhome');
	}
	
	public function actionUserHome(){
			
		Login::UserAuth('Cpanel','Home');	
		$Data = array();
			
		$RoleID = 0;
		if(isset(Yii::app()->session['User'])){
				
			if(isset($_SESSION['User']['UserRoleID']) && !empty($_SESSION['User']['UserRoleID']) && $_SESSION['User']['UserRoleID'] > 0){
				
				$RoleID = $_SESSION['User']['UserRoleID'];
				if($RoleID == 2){
					
					$OwnerID = $_SESSION['User']['UserOwnerID'];
					$BuSQL = " SELECT * FROM business_unit LEFT JOIN types ON business_unit.type = types.type_id 
							   WHERE accid =".$OwnerID." ORDER BY type";
					$BuData = Yii::app()->db->createCommand($BuSQL)->queryAll();		   
					
					$BuArr = array();
					foreach ($BuData as $BuKey => $BuRow) {
						$BuArr[$BuRow['type_id']]['TypeID']= $BuRow['type_id'];
						$BuArr[$BuRow['type_id']]['TypeN']= $BuRow['type_name'];
						$BuArr[$BuRow['type_id']]['BU'][$BuRow['buid']]['BuID']= $BuRow['buid'];
						$BuArr[$BuRow['type_id']]['BU'][$BuRow['buid']]['BuN']= $BuRow['title'];
						$BuArr[$BuRow['type_id']]['BU'][$BuRow['buid']]['BuClogo']= $BuRow['cpanel_logo'];
						$BuArr[$BuRow['type_id']]['BU'][$BuRow['buid']]['Bulogo']= $BuRow['logo'];
					}
					
					$Data['BuTypes'] = $BuArr;
					
					//$Stores = Yii::app()->db->createCommand('SELECT * FROM business_unit WHERE accid ='.$OwnerID)->queryAll();	
					//$Data['Stores'] = $Stores;
				}	
			}
			
		}
		
		$Data['RoleID'] = $RoleID;
		$this->render('userhome',array('Data'=>$Data));
	}
	
	public function actionBuHome()
	{
		Login::UserAuth('Cpanel','Home');		
		
		$this->layout = "column2";	
		$BuID = 0;$BU = array();
		if(isset(Yii::app()->session['User'])){
				
			if(isset($_SESSION['User']['UserBuid']) && !empty($_SESSION['User']['UserBuid']) && $_SESSION['User']['UserBuid'] > 0){
				
				$BuID = $_SESSION['User']['UserBuid'];
				$BU = Yii::app()->db->createCommand('SELECT * FROM business_unit WHERE buid ='.$BuID)->queryRow();
				Login::SetLangSetting();
				
			}
		}
		
		$this->render('buhome' , array('BU'=>$BU));	
	}
	
	public function actionAjaxBuHome()
	{
		$_POST = CI_Security::ChkPost($_POST);
		
		//session_start();
		$_SESSION['User']['UserBuid'] = $_POST['store_id'];
		$_SESSION['User']['Logo'] = $_POST['logo'];
		
		$TActArr = array();
		$TActArr = Login::SetTypeActions($_POST['type']);
		$_SESSION['User']['TypeActs'] = $TActArr;
		 
		if($_SESSION['User']['UserBuid'] == $_POST['store_id']){
			echo 'True';
		}else{
			echo 'False';
		}
	}
	
	public function actionAjaxRemoveBuid()
	{
		session_start();	
		$_SESSION['User']['UserBuid']= 0;
		$_SESSION['User']['Logo'] = '';
		$_SESSION['User']['TypeActs'] = 'array()';
		//$this->redirect("/index.php/admins/home");
		
	}
	
	public function actionLogout()
	{
		//print_r($_SESSION['User']);
		Login::LogoutSession();
		
	}
	
	public function actionAuthLinks(){
			
		$Url =  parse_url(Yii::app()->request->urlReferrer);
		
		$UrlParts = explode('/', $Url['path']);
		
		$Cont = $UrlParts[1]== 'index.php' ?$UrlParts[2]:$UrlParts[1];
		
		$ActsLinks = Login::AuthLinks($Cont);
		
		$ActsLinks =  array_change_key_case ( $ActsLinks , CASE_LOWER  );
		$ActsLinks =  array_map('strtolower',$ActsLinks);
		
		echo json_encode($ActsLinks);
		
	}
	
	public function actionAjaxInActive()
	{
		$Logout = 'FALSE';
		
		if(isset(Yii::app()->session['User'])){
			var_dump(((time() - $_SESSION['last_activity'])/60));
			var_dump(time() .'-'. $_SESSION['last_activity']);
			if (((time() - $_SESSION['last_activity'])/60) >  10) {
			    	
				Yii::app()->session->destroy();
			    $Logout = 'TRUE';
			   
		  	} else {
		  		
			    $_SESSION['last_activity'] = time();
		  	}
				
		} else {
			
			$Logout = 'TRUE';
		}
		
		echo $Logout;
	}
	
	public function actionForgetPass()
	{
		$Res = '';
				
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			
			$_POST = CI_Security::ChkPost($_POST);
			
			$UsrSql = " SELECT * FROM cpanel WHERE email = '" . $_POST['email'] . "'";
			$UsrRes = Yii::app()->db->createCommand($UsrSql) -> queryRow();
			if(!empty($UsrRes)){
					
				$QCode = sha1(date(time()));

				Yii::app()->db->createCommand("UPDATE cpanel SET q_code = '" . $QCode . "' WHERE cp_id = " . $UsrRes['cp_id']) -> execute();

				// ---- Send E-Mail
				$To = $UsrRes['fname'].' '.$UsrRes['lname'];
				$MailTo = $UsrRes['email'];
				$Subject = " Kinjo Reset Password ";
				$ResetUrl = $_SERVER['SERVER_NAME'] . '/index.php/auth/ResetPassword?q=' . $QCode;
				$Message = "<html>
								<head>
									<title>Kinjo Reset Password </title>
								</head>
								<body>
									<p>Link To Reset</p>
									<a href ='" . $ResetUrl . "'>'" . $ResetUrl . "'</a>
								</body>
							</html>";
				//mail::SendMail($To, $Subject, $Message, $Headers);
				$Resemail =  mail::SendMail($Subject,$Message,$MailTo,$To);
               
			    if($Resemail == 'Message has been sent'){
                     $Res = $Resemail;
                }else{
   					 $Res = 'Try Again';
                }
				
			}else{
				
				$Res = ' Invalid User E-Mail ';
			}
		}
			
		$this->render('forgetpass',array('Res'=>$Res));
	}
	
	public function actionResetPassword()
	{
		$Q = Yii::app()->getRequest()->getQuery('q');
		$Mess = '';$UsrID = 0;
		if($Q != ''){
			
			$UsrSql = " SELECT cp_id FROM cpanel WHERE q_code = '".$Q."'";
			$UsrRes = Yii::app()->db->createCommand($UsrSql)->queryRow();
			$UsrID = $UsrRes['cp_id'];
		
		}
			
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
				
			$_POST = CI_Security::ChkPost($_POST);		
				
			$UsrID = 0;
			if(isset($_POST['UsrID'])){
				$UsrID = $_POST['UsrID'];			
			}
			if($UsrID > 0){
				
				$UpUsrSql = " UPDATE cpanel SET password = '".md5($_POST['password'])."' , q_code = '' WHERE cp_id = ".$UsrID;
				$UsrRes = Yii::app()->db->createCommand($UpUsrSql)->execute();
				
				if($UsrRes >= 0){
					
					$Mess = 'Password Reset Succeded';
				};
				
			}else{
				
				$Mess = 'Request Reset Password again';
				
			}
			
		}
		$this->render('resetpass',array('UsrID'=>$UsrID,'Mess'=>$Mess));
	}
	
	public function actionConvertLang()
	{
		$UserLang = $_POST['link_id'];

		Login::GetUserLang($UserLang);
	}
}
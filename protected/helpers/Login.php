<?php
	
	class Login{
		
		static function UserLogin($UserName = '',$Password = '',$Type)
		{	
			if(isset($_SESSION['User'])){unset($_SESSION['User']);}
			if(isset(Yii::app()->session['User'])){unset(Yii::app()->session['User']);}
			if(isset($_SESSION['Language'])){unset($_SESSION['Language']);}
			if(isset(Yii::app()->session['Language'])){unset(Yii::app()->session['Language']);}	
			
			$SessArr = array();	$Login = FALSE;$PkgSp = array();$Features = array();
			
			$RoleID = 0;$RoleN = '';
			//------------------ Check 
			//------ Admin
			if($Type == 'admin'){
				
				$AdminSQL = " SELECT * FROM admins 
							  LEFT JOIN roles ON admins.role_id = roles.role_id
							  WHERE username = '".$UserName."' AND password = '".md5($Password)."'";
				$AdminData = Yii::app()->db->createCommand($AdminSQL)->queryRow();
				
				if(!empty($AdminData)){
						
					//$SessArr['UserType']= 'admin';
					$SessArr['UserType']=$AdminData['role_name'];
					$SessArr['UserID']=$AdminData['adid'];
					$SessArr['UserName']=$AdminData['username'];
					$SessArr['UserFname']=$AdminData['fname'];
					$SessArr['UserLname']=$AdminData['lname'];
					$SessArr['UserEmail']=$AdminData['email'];
					$SessArr['UserOwnerID']= 0;
					$SessArr['UserBuid']= 0;
					$SessArr['UserUrl']= $AdminData['role_url'];
					$SessArr['Logo']= '';
					
					$RoleID = $AdminData['role_id'];
					$RoleN = $AdminData['role_name'];
					
					$Login = TRUE;
				}
			}
			//------ Cpanel
			if($Type == 'cpanel'){
				
				$CpanelSQL = " SELECT * FROM cpanel
							   LEFT JOIN roles ON cpanel.role_id = roles.role_id 
							   WHERE username = '".$UserName."' AND password = '".md5($Password)."'";
				$CpanelData = Yii::app()->db->createCommand($CpanelSQL)->queryRow();
				
				if(!empty($CpanelData)){
						
					$SessArr['UserType']=$CpanelData['role_name'];
					$SessArr['UserID']=$CpanelData['cp_id'];
					$SessArr['UserName']=$CpanelData['username'];
					$SessArr['UserFname']=$CpanelData['fname'];
					$SessArr['UserLname']=$CpanelData['lname'];
					$SessArr['UserEmail']=$CpanelData['email'];
					$SessArr['UserUrl']= $CpanelData['role_url'];
					$SessArr['Logo']= '';
					
					$RoleID = $CpanelData['role_id'];
					$RoleN = $CpanelData['role_name'];
					
					if($CpanelData['role_id'] == 2){
						
						$SessArr['UserOwnerID']=$CpanelData['buid'];
						$SessArr['UserBuid']= 0;
						
						$FeaturesSQL = " SELECT * FROM features WHERE feature_id = (SELECT feature_id FROM bu_accounts WHERE accid = ".$SessArr['UserOwnerID'].")";
						$FeaturesRow = Yii::app()->db->createCommand($FeaturesSQL)->queryRow();
						if(!empty($FeaturesRow)){
							$Features['Urls'] = explode(',', $FeaturesRow['feature_urls']);
						}
						
					} else {
						
						$COwner = Yii::app()->db->createCommand('SELECT accid,cpanel_logo,type FROM business_unit WHERE buid ='.$CpanelData['buid'])->queryRow();
						$SessArr['UserOwnerID']=$COwner['accid'];
						$SessArr['UserBuid']=$CpanelData['buid'];
						$SessArr['Logo']= $COwner['cpanel_logo'];
					}
					
					
					$Login = TRUE;
				}
			}
			
			if($Login == TRUE){
				
				if($RoleID != 5 && $RoleID != 6){
					//--------------------- Roles
				
					$SessArr['UserRoleID'] = $RoleID;
					$SessArr['UserRoleName'] = $RoleN;
					
					$RoleArr = array();
				
					$RoleSQL = " SELECT * FROM role_actions WHERE role_action_role_id = ".$RoleID;
					
					$RoleData = Yii::app()->db->createCommand($RoleSQL)->queryAll();
					
					foreach ($RoleData as $RoleKey => $RoleRow) {
						
						$RoleActArr = array();
						
						if($RoleRow['role_action_actions'] != null){
							
							$RoleActArr = explode(',', $RoleRow['role_action_actions']);
						}
						
						$RoleArr[$RoleRow['role_action_controller']] = $RoleActArr;
						//array_push($RoleArr,array($RoleRow['role_action_controller']=>$RoleActArr));
					}
	
					if($Type == 'cpanel'){	
						$PkgSp = Login::SetPkgSpAuth($SessArr['UserRoleID'],$SessArr['UserOwnerID'],$SessArr['UserBuid']);
					}
					
					$SessArr['UserRoles'] = $RoleArr;
					
					//Yii::app()->session->destroy();
					
					//--------------------- Language
					
					if(empty(Yii::app()->session['Language'])){
						
						Login::GetUserLang('EN');
					}
					
					//-------------------------------
					//print_r($SessArr);return;
					//Yii::app()->session['User'] = $SessArr;
					
					$ExpireTime = 8*60*60; //expire time
					
					$SessArr['Features']=$Features;
					$SessArr['PkgSp']=$PkgSp;
					
					$_SESSION['User'] = $SessArr;
					$_SESSION['last_activity'] = time();
					$_SESSION['expire_time'] = $ExpireTime;
				
				} else {
						
					throw new CHttpException(401,'Your Role do not have Access To Cpanel.');
				}
				
			}
			return $Login;
		}
		
		static function SetPkgSpAuth($RoleID = 0,$OwnerID = 0,$BuID = 0){
			
			$PkgSp = array(); $PkgSpID = '' ;$PkgSpType = '';
			
			$OwnerSQL = " SELECT * FROM bu_accounts WHERE accid = ".$OwnerID;
			$OwnerData = Yii::app()->db->createCommand($OwnerSQL)->queryRow();
				
			if(!empty($OwnerData)){
					
				if($OwnerData['special_deal_id'] > 0){
					
					$PkgSpID = $OwnerData['special_deal_id'];
					$PkgSpType = 'sp';
					
					//--------
					$SpRolesSQL = " SELECT * FROM pkg_sp_roles WHERE pkg_sp_role_type = 'sp' AND pkg_sp_role_pkg_sp_id = ".$PkgSpID;
					$SpRolesData = Yii::app()->db->createCommand($SpRolesSQL)->queryAll();
					foreach ($SpRolesData as $SpRoleskey => $SpRolesrow) {
							
						$SpRoleActArr = array();
					
						if($SpRolesrow['pkg_sp_role_actions'] != null){
							
							$SpRoleActArr = explode(',', $SpRolesrow['pkg_sp_role_actions']);
						}
						$PkgSp[$SpRolesrow['pkg_sp_role_controller']] = $SpRoleActArr;
					}
					
				} else {
				
					if($RoleID == 2){
							
						$PkgSpType = 'pkg';$PkgSpID = 0;
						$BuSQL = " SELECT buid,pkg_id FROM business_unit WHERE accid = ".$OwnerID;
						$BuData = Yii::app()->db->createCommand($BuSQL)->queryAll();
						
						foreach ($BuData as $Bukey => $Burow) {
								
							$PkgSQL = " SELECT * FROM pkg_sp_roles WHERE pkg_sp_role_type = 'pkg' 
										AND pkg_sp_role_pkg_sp_id = ".$Burow['pkg_id'];
							
							$PkgData = Yii::app()->db->createCommand($PkgSQL)->queryAll();
							
							$PkgArr = array();
							
							foreach ($PkgData as $Pkgkey => $Pkgrow) {
									
								$PkgActArr = array();
						
								if($Pkgrow['pkg_sp_role_actions'] != null){
									
									$PkgActArr = explode(',', $Pkgrow['pkg_sp_role_actions']);
								}
								
								$PkgArr[$Pkgrow['pkg_sp_role_controller']] = $PkgActArr;
							}			
							
							$PkgSp[$Burow['buid']] = $PkgArr;
						}	
						
				
				
					} else {
							
						$PkgSpType = 'pkg';	
						
						$PkgSQL = " SELECT * FROM pkg_sp_roles WHERE pkg_sp_role_type = 'pkg' 
									AND pkg_sp_role_pkg_sp_id = (SELECT pkg_id FROM business_unit WHERE buid = ".$BuID.") ";
												
						$PkgData = Yii::app()->db->createCommand($PkgSQL)->queryAll();
						
						foreach ($PkgData as $Pkgkey => $Pkgrow) {
								
							$PkgRoleActArr = array();
					
							if($Pkgrow['pkg_sp_role_actions'] != null){
								
								$PkgRoleActArr = explode(',', $Pkgrow['pkg_sp_role_actions']);
							}
							
							$PkgSp[$Pkgrow['pkg_sp_role_controller']] = $PkgRoleActArr;
							
							$PkgSpID = $Pkgrow['pkg_sp_role_pkg_sp_id'];
						}
						
					}
				}
				
				$PkgSpArr ['ID'] = $PkgSpID;
				$PkgSpArr ['Type'] = $PkgSpType;
				$PkgSpArr ['Actions'] = $PkgSp;
				
				return $PkgSpArr;
				
			} else {
					
				throw new CHttpException(401,' Login Again .');
			}
			
		}
		
		static function LogoutSession(){
				
			Yii::app()->session->destroy();
			header("Location:/index.php/Auth/UserLogin");
				
		}
		
		static function RedirectUser(){
				
			$Url = 'site';
			
			if(isset($_SESSION['User'])){
				
				if(isset($_SESSION['User']['UserUrl']) && !empty($_SESSION['User']['UserUrl'])){
					
					$Url = $_SESSION['User']['UserUrl'];
				}
			}
			
			header("Location:/index.php/".$Url);
			
		}
		
		static function UserAuth($Module = '',$Action = ''){
                    
			if(isset(Yii::app()->session['User'])){
					
				if(isset(Yii::app()->session['User']['UserRoleID'])){
					
					$RoleID = Yii::app()->session['User']['UserRoleID'];
					
					//if($RoleID > 1){
						
						Login::ChkAuth($Module,$Action);
						if($Action == 'Home'){
							if($RoleID > 2||($RoleID == 2 && Login::ChkFeatures($Module)== FALSE && $Module != 'BuAccounts')){
								Login::ChkPkgSp($Module,$Action);
							}
						}
					//}
				} else {
					header("Location:/index.php/site");
				}
				
			}else{
				
				//$this->redirect("/index.php/site");
				header("Location:/index.php/site");
				
			}
			
		}

		static function ChkAuth($Module = '',$Action = '')
		{
			$Roles = array(); $RoleID = 0;
				
			if(isset(Yii::app()->session['User'])){
				
				$Roles = Yii::app()->session['User']['UserRoles'];
				$RoleID = Yii::app()->session['User']['UserRoleID'];
				
				//if($RoleID > 1){
					
					if(isset($Roles[$Module])){
							
						$RolesActs = array();
						
						$RolesActs = $Roles[$Module];
						
						if(in_array($Action,$RolesActs) || in_array($Action.'_', $RolesActs)){
								
							if(in_array($Action.'_', $RolesActs)){
								
								if($RoleID > 1){
									$ID = 0;
									if(Yii::app()->request->isAjaxRequest){
										$ID = Login::GetAjaxID($Action);
									} else {
										$ID = Yii::app()->getRequest()->getQuery('id');
									}
									
									Login::ChkAuthID($Module,$ID);
								}
							}
							
						} else {
								
							throw new CHttpException(401,'You Do not have Permission.');
						}
						
					} else {
							
						throw new CHttpException(401,'You Do not have Permission.');
						
					}
					
				//}
				
			}else{
				
				header("Location:/index.php/site");
			}
		}

		static function ChkAuthType($Module = '',$Action = '')
		{
			$Chk = False ;

			if(isset(Yii::app()->session['User'])){
				
				if(isset(Yii::app()->session['User']['TypeActs'])){
					
					$TypeActsArr = Yii::app()->session['User']['TypeActs'];
						
					if(!empty($TypeActsArr)){
						
						if(isset($TypeActsArr[$Module])){
								
							$TypeActs = $TypeActsArr[$Module];
							
							if(in_array($Action,$TypeActs) || in_array($Action.'_', $TypeActs)){
								
								if(in_array($Action.'_', $TypeActs)){
									$ID = 0;
									
									if(Yii::app()->request->isAjaxRequest){
										$ID = Login::GetAjaxID($Action);
									} else {
										$ID = Yii::app()->getRequest()->getQuery('id');
									}
									
									Login::ChkAuthID($Module,$ID);
								}
							} else {
									
								throw new CHttpException(401,'You Do not have Permission.');
							}
						}
					}
				}
				
			} else {
				
				header("Location:/index.php/site");
			}
			return $Chk;
		}
		
		static function ChkAuthID($Module = '',$ID = 0)
		{
			if(isset(Yii::app()->session['User'])){
					
				Login::ChkValidTable($Module,$ID);
				
			} else {
				
				header("Location:/index.php/site");
			}		
			
		}
		
		static function ChkValidTable($Module = '',$ID = 0){
				
			$OwnerID = isset(Yii::app()->session['User']['UserOwnerID'])?Yii::app()->session['User']['UserOwnerID']:0;
			$BuID = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
			$RoleID = isset(Yii::app()->session['User']['UserRoleID'])?Yii::app()->session['User']['UserRoleID']:0;
			
			if($Module != ''){
				
				$Arr = array();
				
				$Whr = '';
				
				if($Module == 'BuAccounts'){
						
					$Whr = 'accid = '.$OwnerID;
					array_push($Arr,array('Table'=>'bu_accounts','Whr'=>$Whr,'Col'=>'accid'));
				}
				if($Module == 'BusinessUnit'){
						
					if($RoleID == 2){
						$Whr = 'accid = '.$OwnerID;
					}else{
						$Whr = 'buid = '.$BuID;
					}
					
					array_push($Arr,array('Table'=>'business_unit','Whr'=>$Whr,'Col'=>'buid'));
				}
				if($Module == 'Catsub'){
					
					$Whr = 'catsub_buid = '.$BuID;
					array_push($Arr,array('Table'=>'catsub','Whr'=>$Whr,'Col'=>'csid'));
				}
				if($Module == 'Cpanel'){
						
					if($RoleID == 2){
						$Whr = '(buid = '.$OwnerID.' AND level = 0 ) OR (level = 1 AND buid IN (SELECT buid FROM business_unit WHERE accid = '.$OwnerID.'))';
					}else{
						$Whr = 'buid = '.$BuID.' AND level = 1';
					}
					array_push($Arr,array('Table'=>'cpanel','Whr'=>$Whr,'Col'=>'cp_id'));
				}
				if($Module == 'CreditCards'){
						
					$Whr = 'cr_card_owner_id = '.$OwnerID;
					array_push($Arr,array('Table'=>'credit_cards','Whr'=>$Whr,'Col'=>'cr_card_id'));
				}
				if($Module == 'Messages'){
						
					$Whr = 'buid = '.$BuID;
					array_push($Arr,array('Table'=>'messages','Whr'=>$Whr,'Col'=>'mid'));
				}
				if($Module == 'Offers'){
						
					$Whr = 'pid IN (SELECT pid FROM products WHERE buid = '.$BuID.')';
					array_push($Arr,array('Table'=>'offers','Whr'=>$Whr,'Col'=>'ofid'));
				}
				if($Module == 'Packages'){
						
					$Whr = 'pkgid IN (SELECT pkg_id FROM business_unit WHERE accid = '.$OwnerID.')';
					array_push($Arr,array('Table'=>'packages','Whr'=>$Whr,'Col'=>'pkgid'));
				}
				if($Module == 'PdConfig'){
						
					$Whr = 'conf_buid = '.$BuID;
					array_push($Arr,array('Table'=>'pd_config','Whr'=>$Whr,'Col'=>'cfg_id'));
				}
				if($Module == 'Products'){
						
					$Whr = 'buid = '.$BuID;
					array_push($Arr,array('Table'=>'products','Whr'=>$Whr,'Col'=>'pid'));
				}
				if($Module == 'SpecialDeals'){
						
					$Whr = 'sp_d_id = (SELECT special_deal_id FROM bu_accounts WHERE accid = '.$OwnerID.')';
					array_push($Arr,array('Table'=>'special_deals','Whr'=>$Whr,'Col'=>'sp_d_id'));
				}
				if(!empty($Arr)){
					Login::ChkValidID($Arr,$ID);
				}
			}else{
				
				header("Location:/index.php/site");
			}
			
		}
		
		static function ChkValidID($Arr = array(),$ID = 0){
			
			$Chk = TRUE; $Arr = $Arr[0]; 
			
			$ID = (!isset($ID)||$ID == '')?0:$ID;
			
			if(!empty($Arr)){
				
				$ChkSql  = " SELECT * FROM ".$Arr['Table']." WHERE ".$Arr['Whr']." AND ".$Arr['Col']." = ".$ID;
				$ChkData = Yii::app()->db->createCommand($ChkSql)->queryAll();
				if (count($ChkData) == 0) {
					//throw new CHttpException(401,'You Do not have Permission .');
					throw new CHttpException(401,'You Do not have Permission .');
				}
				
			}else{
				
				header("Location:/index.php/site");
			}
			
		}
		
		static function ChkFeatures($Module = '')
		{
			$Chk = FALSE;
			if(isset(Yii::app()->session['User'])){
					
				$Features = Yii::app()->session['User']['Features'];
				
				if (!empty($Features)) {
					
					$Features = $Features['Urls'];
					$FeaturesArr = array();
					for ($i = 0; $i < sizeof($Features); $i++) { 
						array_push($FeaturesArr,rtrim($Features[$i],substr(strrchr($Features[$i], '/'), 0)));
					}
					if(in_array($Module, $FeaturesArr)){
						$Chk = TRUE;
					}
				} else {
					
					throw new CHttpException(401,'Invalid Request.');
				}
			}else{
				
				header("Location:/index.php/site");
			}
			return $Chk;
		}
		
		static function ChkPkgSp($Module = '',$Action = '')
		{
			if(isset(Yii::app()->session['User'])){
				
				if(isset(Yii::app()->session['User']['PkgSp'])){
						
					$RoleID = Yii::app()->session['User']['UserRoleID'];
					
					$PkgSp = Yii::app()->session['User']['PkgSp'];
					$PkgSpID= $PkgSp['ID'];$PkgSpType = $PkgSp['Type'];$PkgSpActs = $PkgSp['Actions'];
					
					if($PkgSpID == '0' && $PkgSpType == 'pkg'){
							
						$BuID = Yii::app()->session['User']['UserBuid'];
						
						if(isset($PkgSpActs[$BuID])){
								
							if(isset($PkgSpActs[$BuID][$Module])){
								
								if(!in_array($Action, $PkgSpActs[$BuID][$Module])){					
									throw new CHttpException(401,'Out Of Packages.');
								}
								
							}else{
								throw new CHttpException(401,'Out Of Packages.');
							}	
							
						} else {
							
							throw new CHttpException(401,'Invalid Request.');
						}
						
					}else{
						
						if(isset($PkgSpActs[$Module])){
								
							if(!in_array($Action, $PkgSpActs[$Module])){
									
								throw new CHttpException(401,'Out Of Packages.');
							}
							
						}else{
							
							throw new CHttpException(401,'Out Of Packages.');
						}
					}
				}else{
					
					header("Location:/index.php/site");	
				}
				
			}else{
					
				header("Location:/index.php/site");
			}
		}
		
		static function SetTypeActions($TypeID = 0)
		{
			$TActArr = array();
				
			if(isset(Yii::app()->session['User'])){
					
				$RoleID = isset(Yii::app()->session['User']['UserRoleID'])?Yii::app()->session['User']['UserRoleID']:0;
					
				$TActSql = " SELECT * FROM bu_type_actions WHERE bu_t_action_role_id = ".$RoleID." AND bu_t_action_type_id = ".$TypeID;
				$TActData = Yii::app()->db->createCommand($TActSql)->queryAll();
				
				foreach ($TActData as $TActKey => $TActRow) {
						
					$TActArr[$TActRow['bu_t_action_controller']] = explode(',', $TActRow['bu_t_action_actions']);	
					//array_push($TActArr,array($TActRow['bu_t_action_controller'] => explode(',', $TActRow['bu_t_action_actions'])));
				}
				
			} else {
					
				header("Location:/index.php/site");
			}
			
			return $TActArr;
		}
		
		static function SetLangSetting(){
			
			if(isset(Yii::app()->session['User'])){
				
				$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
				
				$LangSQL = " SELECT bu_lang_lang_id,lang_name,lang_code
							 FROM  languages
							 LEFT JOIN bu_lang_setting ON bu_lang_lang_id = lang_id
							 WHERE bu_lang_bu_id = ".$Buid." AND bu_lang_val = 1 ";
							 
				$LangData = Yii::app()->db->createCommand($LangSQL)->queryAll();
				
				$LangArr = array();
				foreach ($LangData as $key => $row) {
						
					$LangArr[$row['bu_lang_lang_id']]['LangID'] = $row['bu_lang_lang_id'];
					$LangArr[$row['bu_lang_lang_id']]['LangN'] = $row['lang_name'];
					$LangArr[$row['bu_lang_lang_id']]['LangC'] = $row['lang_code'];
				}
				
				$_SESSION['User']['Lang']= $LangArr;
				
			}
			
		}
		
		static function GetUserLang($lang){
			
			$LangSess = array();
			//session_start();
			$lang_file = 'lang-'.$lang.'.php';
			
			$LangSess['LangFile'] = substr($lang_file, 0, strpos($lang_file, "."));
			$LangSess['UserLang'] = $lang;
			//Yii::app()->session['Language'] = $LangSess;
			$_SESSION['Language'] = $LangSess;
		}
		
		static function ChkCustomerHash($CustID,$hash){
			
			$CustSql = " SELECT * FROM customers WHERE cid = ".$CustID." AND hash = '".$hash."' ";
			$CustRes = Yii::app()->db->createCommand($CustSql)->queryRow();
			if(!empty($CustRes)){
				
				return TRUE;
				
			}else{
				
				return FALSE;
			}
		}
		
		static function ChkCpanelToken($CpID,$Token,$DevID){
			
			$CpSql = " SELECT * FROM cpanel_token 
					   WHERE cp_tkn_cp_id = ".$CpID." AND cp_tkn_token = '".$Token."' AND cp_tkn_dev_id = '".$DevID."'";
			$CpRes = Yii::app()->db->createCommand($CpSql)->queryRow();
			
			if(!empty($CpRes)){
				
				return TRUE;
				
			} else {
				
				return FALSE;
			}
		}
		
		static function ChkReqUserNamePass($UserName = '',$Password = ''){
				
			$Chk = array();
			
			$Chk['UserName']='';$Chk['Password']='';$Chk['valid']='True';
			
			if(!isset($UserName) || empty($UserName)){
				$Chk['UserName']= 'UserName is required ';
				$Chk['valid']='False';
			}
			if(!isset($Password) || empty($Password)){
				$Chk['Password']= 'Password is required ';
				$Chk['valid']='False';
			}
			
			return $Chk;
			
		}
		
		static function CreateMenu()
		{
			$MenuArr = array();	$MenuMainArr = array(); $ExtMenu = array();	
			
			if(isset(Yii::app()->session['User'])){
					
				$langFile = Yii::app()->session['Language']['LangFile'];	
					
				$RoleID = isset(Yii::app()->session['User']['UserRoleID'])?Yii::app()->session['User']['UserRoleID']:'';
				$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
				
				$Roles = isset(Yii::app()->session['User']['UserRoles'])?Yii::app()->session['User']['UserRoles']:array();
				$PkgSp = isset(Yii::app()->session['User']['PkgSp'])?Yii::app()->session['User']['PkgSp']:array();
				$Features = isset(Yii::app()->session['User']['Features'])?Yii::app()->session['User']['Features']:array();
				
				$PkgSpActs = array();
				if(!empty($PkgSp)){
					if($PkgSp['Type']=='pkg' && $PkgSp['ID']== '0'){
						$PkgSpActs =  isset($PkgSp['Actions'][$Buid])?$PkgSp['Actions'][$Buid]:array();
					}else{
						$PkgSpActs =  isset($PkgSp['Actions'])?$PkgSp['Actions']:array();
					}
				}
				$ActR = array('Admin','Index','Create');
				foreach ($Roles as $Roleskey => $RolesArr) {
					$Controller = ''; $Act = '';
					if(isset($PkgSpActs[$Roleskey])){
						
						$Controller = $Roleskey;
						for ($i = 0; $i < sizeof($ActR); $i++) { 
											
							if(in_array($ActR[$i],$RolesArr) && in_array($ActR[$i],$PkgSpActs[$Roleskey])){
								$Act = 	$ActR[$i];
								break;
							}
						}
						
						//-----------------------------------------------------
						if($Act == ''){
							for ($i = 0; $i  < sizeof($RolesArr); $i++) {
								 
								if(in_array($RolesArr[$i], $PkgSpActs[$Roleskey]) || in_array(rtrim($RolesArr[$i],'_' ), $PkgSpActs[$Roleskey])){
										
									if(substr($RolesArr[$i], -1)!= '_'){
										$Act = $RolesArr[$i];
									}
								}
							}
						}
						//-----------------------------------------------------
						if($Act != ''){
							array_push($MenuMainArr,array('label'=>Yii::t($langFile, 'BuMenu'.$Roleskey), 'url'=>array(''.$Roleskey.'/'.$Act.'')));
						}
						//----------------------------------------------------
					}
				}
				
				//-------------------- Types Actions
				
				if($Buid > 0){
					
					$TypeActsArr = array();
					
					if(isset(Yii::app()->session['User']['TypeActs'])){
						
						$TypeActsArr = Yii::app()->session['User']['TypeActs'];
						
						if(!empty($TypeActsArr)){
								
							foreach ($TypeActsArr as $TActkey => $TActRow) {
									
								if(Login::ChkINMenu($TActkey,$MenuMainArr) == False){
										
									$Act = '';	
										
									for ($i = 0 ;$i < sizeof($TActRow);$i++) {
											
										if(substr($TActRow[$i], -1)!= '_'){
											$Act = $TActRow[$i];
										}	
										//array_push($MenuMainArr,array('label'=>$TVal, 'url'=>array(''.$TActkey.'/'.$TVal.'')));
									}
									if($Act  != ''){
										array_push($MenuMainArr,array('label'=>Yii::t($langFile, 'BuMenu'.$TActkey), 'url'=>array(''.$TActkey.'/'.$Act.'')));
									}
								}
							}
						}
					} 
				}
				
				$ExtMenu = Login::CreateExtMenu();
				if (isset($ExtMenu['Delete'])) 
				{
				    unset($ExtMenu['Delete']);
				}
				$ExtMenu = array_values($ExtMenu);
			
			} else {
				
				header("Location:/index.php/site");
			}
			
			$MenuArr['Main'] = $MenuMainArr;
			$MenuArr['Ext'] = $ExtMenu;
			return $MenuArr;
		}
		
		static function CreateExtMenu(){
			/*
			echo Yii::app()->urlManager->parseUrl(Yii::app()->request);
						echo Yii::app()->controller->getRoute();
						echo Yii::app()->request->requestUri;
						echo $_SERVER['REQUEST_URI'];
						echo $_SERVER['QUERY_STRING'];*/
			
			$ExtMenu = array();
			
			if(isset(Yii::app()->session['User'])){
					
				$langFile = Yii::app()->session['Language']['LangFile'];
				$RoleID = isset(Yii::app()->session['User']['UserRoleID'])?Yii::app()->session['User']['UserRoleID']:'';
				$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
				
				$Roles = isset(Yii::app()->session['User']['UserRoles'])?Yii::app()->session['User']['UserRoles']:array();
				$PkgSp = isset(Yii::app()->session['User']['PkgSp'])?Yii::app()->session['User']['PkgSp']:array();
				$Features = isset(Yii::app()->session['User']['Features'])?Yii::app()->session['User']['Features']:array();
				
				$PkgSpActs = array();
				if(!empty($PkgSp)){
					if($PkgSp['Type']=='pkg' && $PkgSp['ID']== '0'){
						$PkgSpActs =  isset($PkgSp['Actions'][$Buid])?$PkgSp['Actions'][$Buid]:array();
					}else{
						$PkgSpActs =  isset($PkgSp['Actions'])?$PkgSp['Actions']:array();
					}
				}
				
				$CurrCont = ucfirst(Yii::app()->controller->id);
				$CurrAct = Yii::app()->controller->action->id;
				$CurrID = Yii::app()->request->getParam('id');
				
				if($RoleID == 1 || ($RoleID == 2 && Login::ChkFeatures($CurrCont) == TRUE)){
					
					if(isset($Roles[$CurrCont])){
						
						for ($i = 0; $i  < sizeof($Roles[$CurrCont]); $i++) {
							if(substr($Roles[$CurrCont][$i], -1)== '_'){
										
								if($CurrID != ''){
										
									//array_push($ExtMenu ,array(rtrim($Roles[$CurrCont][$i],'_' )=>array('label'=>rtrim($Roles[$CurrCont][$i],'_' ).' '.$CurrCont, 'url'=>array(rtrim($Roles[$CurrCont][$i],'_' ),'id'=>$CurrID))));
									$ExtMenu[rtrim($Roles[$CurrCont][$i],'_' )] = array('label'=>Yii::t($langFile, 'ExtMenu'.rtrim($Roles[$CurrCont][$i],'_' )).' '.Yii::t($langFile, 'TopMenu'.$CurrCont), 'url'=>array(rtrim($Roles[$CurrCont][$i],'_' ),'id'=>$CurrID));	
								}
								
							} else {
								
								$Act = $Roles[$CurrCont][$i];
								//array_push($ExtMenu ,array($Roles[$CurrCont][$i]=>array('label'=>$Roles[$CurrCont][$i].' '.$CurrCont, 'url'=>array($Roles[$CurrCont][$i]))));
								$ExtMenu[$Roles[$CurrCont][$i]]=array('label'=>Yii::t($langFile, 'ExtMenu'.$Roles[$CurrCont][$i]).' '.Yii::t($langFile, 'TopMenu'.$CurrCont), 'url'=>array($Roles[$CurrCont][$i]));
							}
						}
					}	
					
				} else {
					
					if(isset($Roles[$CurrCont]) && isset($PkgSpActs[$CurrCont])){
						for ($i = 0; $i  < sizeof($Roles[$CurrCont]); $i++) {
							
							if(in_array($Roles[$CurrCont][$i], $PkgSpActs[$CurrCont])||in_array(rtrim($Roles[$CurrCont][$i],'_' ), $PkgSpActs[$CurrCont])){
								
								if(substr($Roles[$CurrCont][$i], -1)== '_'){
									
									if($CurrID != ''){
											
										//array_push($ExtMenu ,array(rtrim($Roles[$CurrCont][$i],'_' )=>array('label'=>rtrim($Roles[$CurrCont][$i],'_' ).' '.$CurrCont, 'url'=>array(rtrim($Roles[$CurrCont][$i],'_' ),'id'=>$CurrID))));
										$ExtMenu[rtrim($Roles[$CurrCont][$i],'_' )] = array('label'=>Yii::t($langFile, 'ExtMenu'.rtrim($Roles[$CurrCont][$i],'_' )).' '.Yii::t($langFile, 'BuMenu'.$CurrCont), 'url'=>array(rtrim($Roles[$CurrCont][$i],'_' ),'id'=>$CurrID));
									
									}
									
								}else{
									
									$Act = $Roles[$CurrCont][$i];
									//array_push($ExtMenu ,array($Roles[$CurrCont][$i]=>array('label'=>$Roles[$CurrCont][$i].' '.$CurrCont, 'url'=>array($Roles[$CurrCont][$i]))));
									$ExtMenu[$Roles[$CurrCont][$i]]=array('label'=>Yii::t($langFile, 'ExtMenu'.$Roles[$CurrCont][$i]).' '.Yii::t($langFile, 'BuMenu'.$CurrCont), 'url'=>array($Roles[$CurrCont][$i]));
								}
							}
						}
					}
				}
	
				//------------------ Type Acts
				if($Buid > 0){
					
					$TypeActsArr = array();
						
					if(isset(Yii::app()->session['User']['TypeActs'])){
							
						$TypeActsArr = Yii::app()->session['User']['TypeActs'];
							
						if(isset($TypeActsArr[$CurrCont])){
							
							for ($i = 0; $i  < sizeof($TypeActsArr[$CurrCont]); $i++) {
								
								if(substr($TypeActsArr[$CurrCont][$i], -1)== '_'){
												
									if($CurrID != ''){
											
										$ExtMenu[rtrim($TypeActsArr[$CurrCont][$i],'_' )] = array('label'=>Yii::t($langFile, 'ExtMenu'.rtrim($TypeActsArr[$CurrCont][$i],'_' )).' '.Yii::t($langFile, 'BuMenu'.$CurrCont), 'url'=>array(rtrim($TypeActsArr[$CurrCont][$i],'_' ),'id'=>$CurrID));
									}
									
								} else {
									
									$ExtMenu[$TypeActsArr[$CurrCont][$i]]=array('label'=>Yii::t($langFile, 'ExtMenu'.$TypeActsArr[$CurrCont][$i]).' '.Yii::t($langFile, 'BuMenu'.$CurrCont), 'url'=>array($TypeActsArr[$CurrCont][$i]));
								}
							}
						}
					}
				}
			} else {
				
				header("Location:/index.php/site");
			}
			
			return $ExtMenu;
		}
		
		static function ChkINMenu($Cont = '',$Menu = array()){
				
			$In = False;
			
			foreach($Menu As $key=>$Arr){
				
				if($Cont == $Arr['label']){
					
					$In = True; break;
				}
			}
			
			return $In;
		}
		
		
		static function AuthLinks($Cont = ''){
			
			$AuthLinks = array();
			
			if(isset(Yii::app()->session['User'])){
				
				$RoleID = isset(Yii::app()->session['User']['UserRoleID'])?Yii::app()->session['User']['UserRoleID']:'';
				$Roles = Login::ChkINRoles($Cont);
				$PkgSp = Login::ChkINPkgSp($Cont);
				
				if($RoleID == 1 || ($RoleID == 2 && Login::ChkINFeatures($Cont) == TRUE)){
					
					if($Roles['InRole']== TRUE){
						
						for ($i = 0; $i < sizeof($Roles['RoleActs']); $i++) { 
							
							if(substr($Roles['RoleActs'][$i], -1) == '_'){
								array_push($AuthLinks,rtrim($Roles['RoleActs'][$i], '_'));
							}
						}
					}
					
				} else {
					
					if($Roles['InRole']== TRUE && $PkgSp['InPkgSp'] == TRUE){
						
						for ($i = 0; $i < sizeof($Roles['RoleActs']); $i++) {
							
							if(in_array(rtrim($Roles['RoleActs'][$i],'_' ), $PkgSp['PkgSpActs'])){
									
								array_push($AuthLinks,rtrim($Roles['RoleActs'][$i], '_'));
							}
						}
						
					}
					
				}
				
			} else {
				
				header("Location:/index.php/site");
			}
			
			return $AuthLinks;
		}
	
		static function ChkINFeatures($Cont = '')
		{
			$Features = isset(Yii::app()->session['User']['Features'])?Yii::app()->session['User']['Features']:array();
			$InFea = False;
			if(!empty($Features)){
				if(isset($Features['Urls'])){
					$Features = $Features['Urls'];
					for ($i = 0; $i  < sizeof($Features); $i ++) {
						if(strtolower(rtrim($Features[$i],substr(strrchr($Features[$i], '/'), 0))) == strtolower($Cont)){
							$InFea = TRUE;
						}
					}	
				}
			}
			return $InFea;
		}
		
		static function ChkINRoles($Cont = '')
		{
			$ResRoles = array();$RoleActs = array();	
			$Roles = isset(Yii::app()->session['User']['UserRoles'])?Yii::app()->session['User']['UserRoles']:array();
			$InRole = False;
			
			foreach ($Roles as $RoleKey => $RoleArr) {
				if(strtolower($RoleKey) == strtolower($Cont)){
					$InRole = TRUE;
					$RoleActs = $RoleArr;
				}
			}
			$ResRoles['InRole']= $InRole;
			$ResRoles['RoleActs']= $RoleActs;
			return $ResRoles;
		}
		
		static function ChkINPkgSp($Cont = '')
		{
			$ResPkgSp = array();$PkgSpActs = array();		
			$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;	
			$PkgSp = isset(Yii::app()->session['User']['PkgSp'])?Yii::app()->session['User']['PkgSp']:array();
			
			$PkgSpActAR = array();
			if(!empty($PkgSp)){
				if($PkgSp['Type']=='pkg' && $PkgSp['ID']== '0'){
					$PkgSpActAR =  isset($PkgSp['Actions'][$Buid])?$PkgSp['Actions'][$Buid]:array();
				}else{
					$PkgSpActAR =  isset($PkgSp['Actions'])?$PkgSp['Actions']:array();
				}
			}
			
			$InPkgSp = False;
			foreach ($PkgSpActAR as $PkgSpKey => $PkgSpArr) {
				
				if(strtolower($PkgSpKey) == strtolower($Cont)){
					$InPkgSp = TRUE;
					$PkgSpActs = $PkgSpArr;
				}
			}
			
			$ResPkgSp['InPkgSp']= $InPkgSp;
			$ResPkgSp['PkgSpActs']= $PkgSpActs;
			return $ResPkgSp;
		}

		static function GetAjaxID ($Action)
		{
			$ID = 0;
				
			if($Action == 'Delete'){
				
				$ID = Yii::app()->getRequest()->getQuery('id');
				
			}else{
					
				$Url =  parse_url(Yii::app()->request->urlReferrer);
				$UrlParts = explode('/', $Url['path']);
				$ID = $UrlParts[1]== 'index.php' ?$UrlParts[4]:$UrlParts[3];
			}
			
			return $ID;
			
		}

		static function GetBuType($BuID = 0)
		{
			$TypeArr = array();	
			
			$TypeSQL = " SELECT * FROM business_unit LEFT JOIN types ON business_unit.type = types.type_id
						 WHERE business_unit.buid = ".$BuID;
			$TypeRow = Yii::app()->db->createCommand($TypeSQL)->queryRow();
			
			if(!empty($TypeData)){
					
				$TypeArr['TypeID']= $TypeRow['type'];
				$TypeArr['TypeName']= $TypeRow['type_name'];
				$TypeArr['TypeImg']= $TypeRow['type_img'];
			}
			
			return $TypeArr;
		}

		static function ChkBuSess()
		{
			if(isset(Yii::app()->session['User'])){
					
				$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
				
				if(!$Buid > 0){
						
					header("Location:/index.php/auth/UserHome");
				}
				
			} else {
				
				header("Location:/index.php/site");
			}
		}
}

	
	
	
	 /*//var_dump(getcwd());return;
		$classMethod = new ReflectionMethod('ProductsController','actionAdmin');
		$argumentCount = count($classMethod->getParameters());
		var_dump($argumentCount);
		
		$class = new ReflectionClass('Products');
		$method = $class->getMethod('Admin');
		var_dump($method);

		
		 * 
		 * if (!$Controller !== null && method_exists($Controller, 'Update')) {
					   echo 'controller/action is allow :)';
					}*/
?>
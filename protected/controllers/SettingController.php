<?php

	class SettingController extends Controller
	{
		public $layout='//layouts/column2';
		
		public function init()
		{
			parent::init();
			Yii::app()->language = Yii::app()->session['Language']['UserLang'];
		}
		
		public function actionOPenSettingLang()
		{
			Login::ChkAuth('Setting','OPenSettingLang');
			Login::ChkBuSess();
			$Data = array();$BUData = array();	
			
			if(isset(Yii::app()->session['User'])){
				
				$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
				
				$SQLLang = " SELECT lang_id,lang_name,lang_code FROM languages WHERE lang_id != 2 AND lang_id NOT IN (
							 SELECT bu_lang_lang_id FROM bu_lang_setting WHERE bu_lang_bu_id = ".$Buid.") AND active = 1";
							 
				$Data = Yii::app()->db->createCommand($SQLLang)->queryAll();
				
				$BUSQLLang = " SELECT lang_id,lang_name,lang_code,bu_lang_val
							   FROM languages 
							   LEFT JOIN bu_lang_setting ON bu_lang_lang_id = lang_id 
							   WHERE bu_lang_bu_id = ".$Buid;
				
				$BUData = Yii::app()->db->createCommand($BUSQLLang)->queryAll();
				
			}
			
			$this->render('languages',array(
				'Data'=>$Data,'BUData'=>$BUData,
			));
		
		}
		
		public function actionSubmitSettingLang()
		{
			Login::ChkAuth('Setting','OPenSettingLang');
			Login::ChkBuSess();
			if(isset(Yii::app()->session['User'])){
					
				$SeArr = $_POST;
				$Buid = isset(Yii::app()->session['User']['UserBuid'])?Yii::app()->session['User']['UserBuid']:0;
				if($Buid > 0){
					
					$DelSQL = " DELETE FROM bu_lang_setting WHERE bu_lang_bu_id = ".$Buid;
					Yii::app()->db->createCommand($DelSQL)->execute();
					
					if(!empty($SeArr)){
						
						$InsSQL = " INSERT INTO bu_lang_setting (bu_lang_bu_id,bu_lang_lang_id,bu_lang_val) VALUES ";
						foreach ($SeArr as $key => $value) {
							
							$InsSQL .= "(".$Buid.",".$key.",".$value."),";	
						}
						$InsSQL = trim($InsSQL, ",") ;
						Yii::app()->db->createCommand($InsSQL)->execute();
					}
					
					Login::SetLangSetting();
					
					echo "save";
					
				}else {
					
					Login::RedirectUser();
					
				}
			}else{
				
				Login::UserAuth();
				
			}
		}
		
		
		public function actionBUsetting()
		{
			Login::ChkAuth('Setting','BUsetting');	
			Login::ChkBuSess();
			
			$settSQL = "SELECT * FROM bu_setting 
						WHERE bu_setting_bu_id=".Yii::app()->session['User']['UserBuid'];	
			$SettRES = Yii::app()->db->createCommand($settSQL)->queryAll();
			
			$BUSett = array();
			if(isset($SettRES) && count($SettRES)>0){
				$BUSett['frm_status'] = 'edit';
				foreach ($SettRES as $key => $val) {
					$BUSett[$val['bu_setting_name']] = $val['bu_setting_val'];				
				}
		
			}else{
				
				$BUSett['frm_status'] = 'add';
				$BUSett['onLine'] = 0; 
				$BUSett['onSite'] = 0;
				$BUSett['general_notify'] = 0;
				$BUSett['diameter'] = 0;
			}
			
			// $FactID = CI_Security::xss_clean($_GET["FactID"]);
			if($_POST){
				
				// $onLine = 0;
				// $onsite = 0;
				$_POST = CI_Security::ChkPost($_POST);
				// print_r($_POST);
			// return;
				
				if($_POST['frm_status'] == 'add'){
					$SQL = "INSERT INTO bu_setting (bu_setting_bu_id , bu_setting_name , bu_setting_val) 
							VALUES (".Yii::app()->session['User']['UserBuid']." , 'general_notify' , ".$_POST['general_notify'].") ,
								   (".Yii::app()->session['User']['UserBuid']." , 'diameter' , ".$_POST['diameter']."),
								   (".Yii::app()->session['User']['UserBuid']." , 'onLine' , ".$_POST['onLine'].") , 
								   (".Yii::app()->session['User']['UserBuid']." , 'onSite' , ".$_POST['onSite'].") ";
					Yii::app()->db->createCommand($SQL)->execute();	
					
				}elseif($_POST['frm_status'] == 'edit'){
					$SQL = "UPDATE bu_setting ";
							
					// $whereSQL = "";	
					foreach($_POST as $key => $row)
					{
						$SQL2 = "";
						if($key != 'frm_status' && $key != 'onLineCHK' && $key != 'onSiteCHK'){
							$SQL2 .= " SET bu_setting_val = ".$row;
							$SQL2 .= " WHERE bu_setting_bu_id = ".Yii::app()->session['User']['UserBuid']." AND bu_setting_name = '".$key."'";
							// print_r($SQL.$SQL2);
							// echo '<pre/>';print_r($SQL.$SQL2);
							Yii::app()->db->createCommand($SQL.$SQL2)->execute();	
						}
						
					}	
					// return;
					
				}
				
				
				header('Location:/index.php/auth/UserHome' );
				
			}else{
				$this->render('BUSetting' , array('BUSett'=>$BUSett));
			}
			
		}
		
		
		
	}

?>
<?php

class APIAppController extends Controller
{
	public function actionIndex()
	{
			
	}
	
	//--------------------- Delivery Boy App ---------------------
	
	
	//-----Services-----
	
	public function actionDBSignIn()
	{
		// header('content-type: application/json; charset=utf-8');
		header("content-type: text/javascript"); 
		$_GET = CI_Security::ChkPost($_GET);	
		$ResArr = array(); 		
		if(isset($_GET['callback'])){
			if(isset($_GET['UserName']) && isset($_GET['Password'])){				
				$Password =	base64_decode($_GET['Password']);	
				$Password =	md5($Password);			
				$DBSQL = " SELECT * 
						   FROM cpanel LEFT JOIN business_unit ON cpanel.buid = business_unit.buid 
						   WHERE username = '".$_GET['UserName']."' AND password = '".$Password."' AND role_id = 5 ";
					$DBRow = Yii::app()->db->createCommand($DBSQL)->queryRow();			
				if(!empty($DBRow)){				
						$Token = $this->AddUserToken($DBRow['cp_id']);
					$RArr = array();
					$RArr['Token'] = $Token;
					$RArr['Buid']  = $DBRow['buid'];
					$RArr['UserID'] = $DBRow['cp_id'];	
					$RArr['PayType'] = Orders::GetBuPayType($DBRow['buid']);				
					$ResArr = array('Result'=>$RArr);				
				}else{				
					$ResArr = array('error'=>array('code'=>'219','message'=>'Invalid Delivery Boy'));
				}			
			} else {			
				$ResArr = array('error'=>array('code'=>'200','message'=>'Invalid Data'));	
			}	
			echo $_GET['callback'].'('.json_encode($ResArr).')';
		}
	}
	
	public function actionDBSignOut()
	{
		header("content-type: text/javascript"); 
		$_GET = CI_Security::ChkPost($_GET);	
		$ResArr = array(); 		
		if(isset($_GET['callback'])){
			if(isset($_GET['UserID']) && isset($_GET['Token'])){				
				if($this->ChkUserToken($_GET['UserID'],$_GET['Token']) == True){
						
					$Res = $this->RemoveUserToken($_GET['UserID'],$_GET['Token']);
					
					$ResArr = array('Result'=>$Res);
					
				} else {
					$ResArr = array('error'=>array('code'=>'220','message'=>'Invalid Token'));	
				}		
			} else {			
				$ResArr = array('error'=>array('code'=>'200','message'=>'Invalid Data'));	
			}	
			echo $_GET['callback'].'('.json_encode($ResArr).')';
		}
	}
	
	public function actionDBOrdersBu()
	{
		header("content-type: text/javascript");
		
		if(isset($_GET['callback'])){
				
			$_GET = CI_Security::ChkPost($_GET);
			$Buid = isset($_GET['Buid'])?$_GET['Buid']:0;
			$Assign = isset($_GET['Assign'])?$_GET['Assign']:0;
			
			$ResArr = array(); 		
		
			if($Buid > 0 ){
						
				if($this->ChkUserToken($_GET['UserID'],$_GET['Token']) == True){
							
					$RealAdrr = Globals::ReturnGlobals();	
					$ImgPath = $RealAdrr['ImgSerPathL'] . 'products/';
					//$ImgPath = $RealAdrr['ImgPath'] . 'products/';
					
					$Whr = "";
			
					if($Assign == 0){
						$Whr = " AND reserved_bu = 1 AND ord_bu_total_close_date IS NULL 
						         AND ord_bu_total_ord_id NOT IN (SELECT ord_assign_ordid FROM orders_assign) ";
					}
					if($Assign == 1){
						$Whr = " AND reserved_bu = 1 AND ord_bu_total_close_date IS NULL AND ord_bu_total_ord_id IN 
						        (SELECT ord_assign_ordid FROM orders_assign WHERE ord_assign_derv_id = ".$_GET['UserID']." AND ord_assign_buid = ".$_GET['Buid']." ) ";
					}
					if($Assign == 2){
						$Whr = " AND reserved_bu = 1 AND ord_bu_total_close_date IS NOt NULL AND ord_bu_total_user_id = ".$_GET['UserID']." ORDER BY close_date DESC ";
					}
					
					$OrdSQL = " SELECT * FROM orders_bu_totals 
								LEFT JOIN orders_details ON ord_bu_total_ord_id = ord_id AND ord_bu_total_bu_id = ord_buid
								LEFT JOIN products ON orders_details.pid = products.pid
								WHERE ord_bu_total_bu_id = ".$_GET['Buid']." ".$Whr." ";
							
					$OrdData = Yii::app()->db->createCommand($OrdSQL)->queryAll();
					$OrdArr = array();
					foreach ($OrdData as $Key => $Row) {
								
						//--------Get Product Imgs
						$ImgSql = " SELECT pimgid, pimg_url FROM products_imgs 
						   	 	    WHERE products_imgs.pid = " . $Row['pid'];
						$ImgRow = Yii::app() -> db -> createCommand($ImgSql) -> queryRow();
						$Img = '';
						if(!empty($ImgRow)){$Img = $ImgPath.$ImgRow['pimg_url'];}
						
						$OrdArr[$Row['ord_bu_total_ord_id']]['OrdID']=$Row['ord_bu_total_ord_id'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Total']=$Row['ord_bu_total_total'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Tax']=$Row['ord_bu_total_tax'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Delivery']=$Row['ord_bu_total_delivery'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Fees']=$Row['ord_bu_total_fees'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Shipment']=$Row['ord_bu_total_shipment'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['VAT']=$Row['ord_bu_total_VAT'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Service']=$Row['ord_bu_total_service'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['DetailID']=$Row['ord_det_id'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['ProdID']=$Row['pid'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['ProdName']=$Row['title'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['ProdImg']=$Img;
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Qnt']=$Row['qnt'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Disc']=$Row['disc'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Price']=$Row['price'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Fees']=$Row['fees'];
						$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Total']=$Row['final_price'];
						
						//------- Get Conf
						$SQLConf = " SELECT ParConf.cfg_id AS ParID,ParConf.name AS ParN,
											SubConf.cfg_id AS SubID,SubConf.name AS SubN,SubConf.parent_id AS SubPar,
											SubConf.value AS SubVal
									 FROM orders_detail_conf 
									 LEFT JOIN pd_config AS SubConf 
									 	LEFT JOIN pd_config AS ParConf ON SubConf.parent_id = ParConf.cfg_id
									 ON ord_de_conf_co_id = SubConf.cfg_id
									 WHERE ord_de_conf_type = 'conf' AND ord_de_conf_de_id = ".$Row['ord_det_id'];
					
						$ConfData = Yii::app()->db->createCommand($SQLConf)->queryAll();
						if(count($ConfData) > 0){
							foreach ($ConfData as $CKey => $CRow) {
								$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Conf'][$CRow['SubPar']]['ParID']=$CRow['ParID'];
								$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Conf'][$CRow['SubPar']]['ParN']=$CRow['ParN'];
								$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Conf'][$CRow['SubPar']]['Subs'][$CRow['SubID']]['SubID']=$CRow['SubID'];
								$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Conf'][$CRow['SubPar']]['Subs'][$CRow['SubID']]['SubN']=$CRow['SubN'];
								$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Conf'][$CRow['SubPar']]['Subs'][$CRow['SubID']]['SubVal']=$CRow['SubVal'];
							}
						} else {
							$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Conf'] = (object)array();
						}
						//------ Get Color
						$SQLColor = " SELECT * FROM orders_detail_conf LEFT JOIN prod_colors ON ord_de_conf_co_id = color_id
									  WHERE ord_de_conf_type = 'color' AND ord_de_conf_de_id = ".$Row['ord_det_id'];
						$ColorData = Yii::app()->db->createCommand($SQLColor)->queryRow();
						if(!empty($ColorData)){
							$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Color']['ColorID'] = $ColorData['color_id'];
							$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Color']['ColorCode'] = $ColorData['color_code'];
						} else {
							$OrdArr[$Row['ord_bu_total_ord_id']]['Details'][$Row['ord_det_id']]['Color'] = (object)array();
						}
					}
	
					$ResArr = array('Result'=>$OrdArr);
					
				} else {
						
					$ResArr = array('error'=>array('code'=>'220','message'=>'Invalid Token'));	
				}

			} else {
				
				$ResArr = array('error'=>array('code'=>'210','message'=>'UnKnown Bu'));	
			}
		
		  echo $_GET['callback'].'('.json_encode($ResArr).')';
		}
		
		//echo json_encode($ResArr);
			
	}
	
	public function actionDBOrderClose()
	{
		header("content-type: text/javascript"); 
		$_GET = CI_Security::ChkPost($_GET);
		$ResArr = array(); 		
		if(isset($_GET['callback'])){
			
			if($this->ChkUserToken($_GET['UserID'],$_GET['Token']) == True){		
				//-----Remove From WishList
				$DelSql = " DELETE FROM wishlist 
						    WHERE wl_cid = (SELECT cid FROM orders WHERE ord_id = ".$_GET['OrdID'].")
						    AND wl_pid IN (SELECT pid FROM orders_details WHERE ord_id = ".$_GET['OrdID']." AND ord_buid = ".$_GET['Buid'].")";
				Yii::app()->db->createCommand($DelSql)->execute();
				
				//----- Update Close Date for OnLine Bu
					
				$TCloseSql = " UPDATE orders_bu_totals SET ord_bu_total_close_date = NOW() ,ord_bu_total_pay_type = 1 ,
							   ord_bu_total_user_id = ".$_GET['UserID']."
							   WHERE ord_bu_total_ord_id = ".$_GET['OrdID']." AND ord_bu_total_bu_id = ".$_GET['Buid'];
				Yii::app() -> db -> createCommand($TCloseSql)->execute();
				
				$CloseSql = " UPDATE orders_details SET close_date = NOW() ,pay_type = 1 ,close_type = 1
							  WHERE ord_id = ".$_GET['OrdID']." AND ord_buid = ".$_GET['Buid'];
				$Res = Yii::app() -> db -> createCommand($CloseSql)->execute();
				
				if($Res > 0){
						
					$Res = 'TRUE';
					Orders::BuTotalClose($_GET['OrdID'],$_GET['Buid'],$_GET['UserID'],1);
				}
				else {$Res = 'FALSE';}
				
				
				//Orders::CloseOrder($_GET['OrdID']);
		
				$ResArr = array('Result'=>$Res);
				
			} else{
					
				$ResArr = array('error'=>array('code'=>'220','message'=>'Invalid Token'));	
			}
			
			echo $_GET['callback'].'('.json_encode($ResArr).')';
		}
	}
	
	public function actionDBOrderOnlineClose()
	{
		//header("content-type: text/javascript"); 
		$_GET = CI_Security::ChkPost($_GET);
		$ResArr = array(); 		
		if(isset($_GET['callback'])){
					
			if($this->ChkUserToken($_GET['UserID'],$_GET['Token']) == True){
					
				$Payed = FALSE;	
				$PaySys = $_GET['PaySys'];
						
				$Arr = array();
				//----Get Customer Data
				
				$CustSql = " SELECT * FROM orders LEFT JOIN customers ON orders.cid = customers.cid WHERE ord_id = ".$_GET['OrdID'];
				$CustData = Yii::app() -> db -> createCommand($CustSql)->queryRow();
				
				$Arr['BillingAddID']= Orders::GetCustBillingAddID($CustData['cid']);
				$Arr['ShipAddID'] = $Arr['BillingAddID'];
					
				$Arr['crd_num']= $_GET['crd_num'];
				$Arr['exp_month']= $_GET['exp_month'];
				$Arr['exp_year']= $_GET['exp_year'];
				$Arr['cvv']= $_GET['cvv'];
				$Arr['State'] = '';
				
				$PaySys = $_GET['PaySys'];
				$Payed = TRUE;	
				
				if($Payed == TRUE){
					
					//-----Remove From WishList
					$DelSql = " DELETE FROM wishlist 
							    WHERE wl_cid = (SELECT cid FROM orders WHERE ord_id = ".$_GET['OrdID'].")
							    AND wl_pid IN (SELECT pid FROM orders_details WHERE ord_id = ".$_GET['OrdID']." AND ord_buid = ".$_GET['Buid'].")";
					Yii::app()->db->createCommand($DelSql)->execute();
					
					//----- Update Close Date for OnLine Bu
							
					$CloseSql = " UPDATE orders_details SET close_date = NOW() , 
															cust_billingAddr = ".$Arr['BillingAddID'].",
															cust_shipAddr = ".$Arr['ShipAddID'].",
															reserved_bu = 1,
															pay_type = 0,
															app_source = 0
								  WHERE ord_id = ".$_GET['OrdID']." AND ord_buid = ".$_GET['Buid'];
					$Res = Yii::app() -> db -> createCommand($CloseSql)->execute();
					
					//------ Update Close Date BuTotals
					Orders::BuTotalClose($_GET['OrdID'],$_GET['Buid'],0,0);
					
					$ResArr = array('orders' => array('result' => 'TRUE'));
				
				 } else{
				 	
				 	$ResArr = array('orders' => array('result' => 'FALSE'));
				 }
			} else {
				
			 	$ResArr = array('error'=>array('code'=>'220','message'=>'Invalid Token'));
			}
				
			echo $_GET['callback'].'('.json_encode($ResArr).')';
		}
	}
	
	//-----Functions-----
	
	private function AddUserToken($UserID = 0)
	{
		$TknSQL = " SELECT * FROM cpanel_token WHERE cp_tkn_cp_id = ".$UserID;
		$TknRow = Yii::app()->db->createCommand($TknSQL)->queryRow();
		
		$Tkn = sha1(date(time()));
		
		if(!empty($TknRow)){
				
			$UpTknSQL = " UPDATE cpanel_token SET cp_tkn_token = '".$Tkn."' WHERE cp_tkn_id = ".$TknRow['cp_tkn_id'];
			Yii::app()->db->createCommand($UpTknSQL)->execute();
			
		}else{
			
			$InsTknSQL = " INSERT INTO cpanel_token (cp_tkn_cp_id,cp_tkn_token)
						   VALUES (".$UserID.",'".$Tkn."') ";
			Yii::app()->db->createCommand($InsTknSQL)->execute();
			
		}
		
		return $Tkn;
	}
	
	private function RemoveUserToken($UserID = 0,$Token = '')
	{
		$TknSQL = " SELECT * FROM cpanel_token WHERE cp_tkn_cp_id = ".$UserID." AND cp_tkn_token = '".$Token."'";
		$TknRow = Yii::app()->db->createCommand($TknSQL)->queryRow();
		
		$Tkn = False;
		
		if(!empty($TknRow)){
				
			$UpTknSQL = " UPDATE cpanel_token SET cp_tkn_token = '' WHERE cp_tkn_id = ".$TknRow['cp_tkn_id'];
			Yii::app()->db->createCommand($UpTknSQL)->execute();
			$Tkn = True;
		}
		
		return $Tkn;
	}
	
	private function ChkUserToken($UserID = 0,$Token = ''){
			
		$Chk = False;
		$TknSQL = " SELECT * FROM cpanel_token WHERE cp_tkn_cp_id = ".$UserID." AND cp_tkn_token = '".$Token."' ";
		$TknRow = Yii::app()->db->createCommand($TknSQL)->queryRow();
		if(!empty($TknRow)){
			$Chk = True;
		}
		return $Chk;
	}
	
	//------------------------------------------------------
	
	
	public function actionAuthorizeNet()
	{
		echo AuthorizeNetHelp::AuthorizeNetFunc();
	}
	
	//------------------------------------------------------
	
	
	
	
	
	//------------------------------ Owner App ----------------------
	
	public function actionGetOwnerBuTypes()
	{
		header("content-type: text/javascript");
		
		$ResArr = array(); 		
		if(isset($_GET['callback'])){
			
			if( isset($_GET['cp_id']) ){
					
				// $RArr = array();
					
				
				$Sql2 = "SELECT DISTINCT TYPE AS typeID , type_name , accid
						 FROM business_unit LEFT JOIN types ON type_id = TYPE
						 WHERE active =0 AND accid = ( SELECT buid FROM cpanel WHERE cp_id =1 ) ";
				$BuType = Yii::app()->db->createCommand($Sql2)->queryAll();
				
				//array_push($Data , array('BuType'=>$BuType));
				$ResArr = array('Result'=>$BuType );
				
			}else{
				$ResArr = array('error'=>array('code'=>'200','message'=>'Invalid Data'));
			}
			
			
			echo $_GET['callback'].'('.json_encode($ResArr).')';
		}
	}
	
	
	public function actionGetOwnerBUsByType()
	{
		header("content-type: text/javascript");	
		
		$ResArr = array(); 		
		if(isset($_GET['callback'])){
			
			if( isset($_GET['typeID']) ){
				$Sql = "SELECT buid , title , accid , type
						FROM business_unit
						WHERE active = 0 AND type = ".$_GET['typeID']." AND accid = (SELECT buid FROM cpanel WHERE cp_id = 1)";
				$Data = Yii::app()->db->createCommand($Sql)->queryAll();
				// $ResArr = array('Result'=>$Data );
				
				$BuIds = array();
				foreach ($Data as $key => $val){
				    // print "$key = $val\n";
				    array_push($BuIds , $val['buid']);
				}
				
				
				
				$Sql2 ="SELECT ord_buid, ord_id, SUM( final_price ) AS final_price, title, pay_type, close_date, currency_code, rating, 
							   CASE WHEN close_type =0 THEN  'Admin'
									WHEN close_type =1 THEN  'Delevery' END AS close_type, 
							   CASE WHEN active =0 THEN 'Active'
									WHEN active =1 THEN 'Disabled' END AS BuState
						FROM orders_details
						LEFT JOIN business_unit ON ord_buid = buid
						GROUP BY ord_buid, ord_id
						HAVING ord_buid IN ( ".implode(",",$BuIds)." ) AND close_date IS NOT NULL ";
						
				// $Sql2 ="SELECT ord_buid, final_price, title ,pay_type,currency_code , rating , close_date , ord_id,
							   // CASE WHEN close_type = 0 THEN 'Admin'
									// WHEN close_type = 1 THEN 'Delevery' END AS close_type ,
							   // CASE WHEN active = 0 THEN 'Active' WHEN active = 1 THEN 'Disabled' END AS BuState
						// FROM orders_details
						// LEFT JOIN business_unit ON ord_buid = buid
						// WHERE ord_buid IN (".implode(",",$BuIds).")  AND close_date IS NOT NULL
						// ORDER BY ord_buid , ord_id";
				$Data2 = Yii::app()->db->createCommand($Sql2)->queryAll();
				$ResArr = array('Result'=>$Data , 'RepRes'=>$Data2 );
				
				
			}else{
				$ResArr = array('error'=>array('code'=>'200','message'=>'Invalid Data'));
			}
			
			echo $_GET['callback'].'('.json_encode($ResArr).')';
		}
	}
	
	
	public function actionOwnerOrd()
	{
		header("content-type: text/javascript");
		$ResArr = array(); 		
		if(isset($_GET['callback'])){
			
			
			if(isset($_GET['accid']) && isset($_GET['typeID'])){
				$RArr = array();
				
				// $RArr['accid'] = $_GET['accid'];
				$RArr['Password'] = $_GET['typeID'];
				
				$ResArr = array('Result'=>$RArr);
				
				echo $_GET['callback'].'('.json_encode($ResArr).')';
			}
		}
		
		
		// else{
			// var_dump('jjjjjjjjjjjjjjjjjjjj');
		// }
	}

}

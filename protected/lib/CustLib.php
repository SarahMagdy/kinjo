<?php
class CustLib {

	public static $Distance = 20000;

	
	//---------------------- Stores ---------------------------------
	
	public static function actionGetAllStores($arr){
			
		// header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$t = 1;
		if (isset($_GET['t'])) {
			if ($_GET['t'] > 0) {$t = $_GET['t'];}
		};
		
		
		$CustID = 0;
		if (isset($_GET['CustID'])) {
			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];}
		};
		
		
		$Hash = 0;
		if (isset($_GET['Hash'])) {
			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];}
		};

		$ResArr = array();

		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){

		$Lang = 0;
		if (isset($_GET['lang'])) {
			if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];}
		};

		$Long = '0';
		if (isset($_GET['Long'])) {

			if ($_GET['Long'] != '') {$Long = $_GET['Long'];}
		};
		
		$Lat = '0';
		if (isset($_GET['Lat'])) {
			if ($_GET['Lat'] != '') {$Lat = $_GET['Lat'];}
		};
		
		$Dist = self::$Distance;
		if (isset($_GET['Dist'])) {
			if ($_GET['Dist'] > 0) {$Dist = $_GET['Dist'];}
		};
		
		//if($Long != 0 || $Lat != 0){
		
				$Order = Yii::app() -> getRequest() -> getQuery('id');
		
				$OrdStr = ' ';
		
				$Order = isset($Order) ? $Order : 0;
		
				if ($Order == 1) {
		
					$OrdStr = ' ORDER BY TotalOrders DESC ';
				}
		
				if ($Lang != 0 && $Lang != 2) {
		
					$Sql = 'SELECT  buid,
									IFNULL((CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END),"") AS title,
									IFNULL((CASE WHEN bu_lang_description IS NULL THEN description ELSE bu_lang_description END),"") AS description,
									IFNULL(`long`,"") AS `long`,IFNULL(lat,"") AS lat,IFNULL(logo,"") AS logo,type,apiKey,rating,urlid,
									(SELECT count(sid) FROM subscriptions WHERE subscriptions.buid = business_unit.buid) AS SubscripCount,
									(SELECT count(pid) FROM products WHERE products.buid = business_unit.buid AND csid IS NULL) AS Items	,
									(SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id IS NULL) AS Cat,
									(SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id > 0) AS CatSub	,
									IFNULL((SELECT sum(final_price) FROM orders_details WHERE orders_details.ord_buid = business_unit.buid),0)AS TotalOrders,
								       (((acos(sin(('.$Lat.'*pi()/180)) * 
								            sin((business_unit.lat*pi()/180)) + cos(('.$Lat.'*pi()/180)) * 
								            cos((business_unit.lat*pi()/180)) * cos((('.$Long.'- business_unit.long)* 
								            pi()/180))))*180/pi())*60*1.1515
								        ) as BUDist								  
								FROM business_unit 
								LEFT JOIN business_unit_lang ON bu_lang_bu_id = buid AND bu_lang_lang_id = ' . $Lang.'
								WHERE active = 0 AND type = '.$t.' HAVING BUDist < '.$Dist . $OrdStr;
				} else {
		
					$Sql = 'SELECT  buid,IFNULL(title,"") AS title,IFNULL(description,"") AS description,IFNULL(`long`,"") AS `long`,IFNULL(lat,"") AS lat,IFNULL(logo,"") AS logo,type,apiKey,rating,urlid,
								   (SELECT count(sid) FROM subscriptions WHERE subscriptions.buid = business_unit.buid) AS SubscripCount,
								   (SELECT count(pid) FROM products WHERE products.buid = business_unit.buid AND csid IS NULL) AS Items	,
								   (SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id IS NULL) AS Cat,
								   (SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id > 0) AS CatSub,
								   IFNULL((SELECT sum(final_price) FROM orders_details WHERE orders_details.ord_buid = business_unit.buid),0)AS TotalOrders,
							       (((acos(sin(('.$Lat.'*pi()/180)) * 
							            sin((business_unit.lat*pi()/180)) + cos(('.$Lat.'*pi()/180)) * 
							            cos((business_unit.lat*pi()/180)) * cos((('.$Long.'- business_unit.long)* 
							            pi()/180))))*180/pi())*60*1.1515
							        ) as BUDist	   							  
							   FROM business_unit 
							   WHERE active = 0 AND type = '.$t.' HAVING BUDist < '.$Dist;
				}
				
				$Data = Yii::app() -> db -> createCommand($Sql) -> queryAll();
		
				$Arr = array();
		
				$RealArr = Globals::ReturnGlobals();
				$RealPath = $RealArr['ImgSerPath'] . 'business_unit/';
		
				foreach ($Data as $key => $row) {
						
					//------------------------Screens
					$screens = '';
					$CatArr = array();
					if ($row['Items'] > 0) {
						$screens = 'items';
						array_push($CatArr, array('id' => '0', 'name' => 'noncategorized', 'subs' => array()));
					}
					if ($row['Cat'] > 0) {$screens = 'cat_items';}
					if ($row['CatSub'] > 0) {$screens = 'cat_sub_items';}
					
					//------------------------Cats
		
					$CatSql = " SELECT csid,parent_id,
										   IFNULL((CASE WHEN cat_lang_title IS NULL THEN title ELSE cat_lang_title END),'') AS title
									FROM catsub 
									LEFT JOIN catsub_lang ON cat_lang_cs_id = csid AND cat_lang_lang_id = " . $Lang . "
									WHERE parent_id IS NULL AND catsub_buid = " . $row['buid'];
					$CatData = Yii::app() -> db -> createCommand($CatSql) -> queryAll();
		
					if (count($CatData) > 0) {
						foreach ($CatData as $Catkey => $CatRow) {
							$CatSSql = " SELECT csid,parent_id,
													IFNULL((CASE WHEN cat_lang_title IS NULL THEN title ELSE cat_lang_title END),'') AS title
											 FROM catsub 
											 LEFT JOIN catsub_lang ON cat_lang_cs_id = csid AND cat_lang_lang_id = " . $Lang . "
											 WHERE parent_id =" . $CatRow['csid'] . " AND catsub_buid = " . $row['buid'];
		
							$CatSData = Yii::app() -> db -> createCommand($CatSSql) -> queryAll();
		
							$CatSArr = array();
		
							foreach ($CatSData as $Catskey => $CatsRow) {
								array_push($CatSArr, array('id' => $CatsRow['csid'], 'name' => $CatsRow['title']));
							}
		
							array_push($CatArr, array('id' => $CatRow['csid'], 'name' => $CatRow['title'], 'subs' => $CatSArr));
		
						}
					}
					//------------------------Contacts
					$ContSql = " SELECT * 
								 FROM bu_contacts
								 WHERE bu_contact_bu_id = ".$row['buid']." ORDER BY bu_contact_type ";
					$ContData = Yii::app()->db->createCommand($ContSql)->queryAll();
					$ContactArr = array();$ContArr = array();
					if(count($ContData) > 0){
						foreach ($ContData as $Contkey => $ContRow) {
							
							$ContArr[$ContRow['bu_contact_type']]['Title']= $ContRow['bu_contact_title'];
							$ContArr[$ContRow['bu_contact_type']]['Vals'][$ContRow['bu_contact_id']]['val']= $ContRow['bu_contact_val'];
						}
					}
					foreach ($ContArr as $key => $Row) {
						
						array_push($ContactArr,array($Row['Title']=>array_values($Row['Vals'])));
					}
					
					//------------------------Full Array
					array_push($Arr, array('id' => $row['buid'], 
										   'title' => $row['title'],
										   'description' => $row['description'], 
										   'logo_url' => $RealPath . $row['logo'], 
										   'icon_marker' => $RealPath .'icons/'. $row['urlid'], 
										   'gps' => array('lat' => $row['lat'], 'long' => $row['long']), 
										   'rate' => $row['rating'], 
										   'subscribers' => $row['SubscripCount'], 
										   'type' => $row['type'], 
										   'apikey' => $row['apiKey'], 
										   'screens' => $screens, 
										   'BUDist' => $row['BUDist'],
										   'logo_url' => $RealPath . $row['logo'], 
										   'cats' => $CatArr,
										   'contacts' => $ContactArr
										   ));
		
				}
		
				$ResArr = array('stores' => $Arr);
			
			//}else{
					
			//	$ResArr = array('error'=>array("Code"=>"800","Message"=>"UnKnown Location"));
				
			//}
		//}else{

		//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}

		// echo json_encode($ResArr);
		return $ResArr;
	}
	
	//---------------------- WishList -----------------------------

	public static function actionAddWishList($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);

		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {
				
			$CustID = 0;
			if (isset($Arr['cid']) && $Arr['cid'] > 0 ) {
				$CustID = $Arr['cid'];
				
				$Hash = 0;
				if (isset($Arr['hash'])) {
					if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];
					}
				};

				// if (Login::ChkCustomerHash($CustID, $Hash) == TRUE) {

					$Lang = 0;
					 if (isset($Arr['lang'])) {
						 if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
					 };

					$S_SQL = "SELECT * FROM wishlist WHERE wl_cid = " . $CustID . " AND wl_pid = " . $Arr['pid'];
					$S_Data = Yii::app() -> db -> createCommand($S_SQL) -> queryRow();

					if (empty($S_Data)) {
	
						$Sql = "INSERT INTO wishlist (wl_cid,wl_pid) VALUES (" . $CustID . "," . $Arr['pid'] . ")";
						Yii::app() -> db -> createCommand($Sql) -> execute();
	
						$vArr['CustID'] = $CustID;
						$vArr['Hash'] = $Hash;
						$vArr['lang'] = $Lang;
						$vArr['OneBu'] = isset($Arr['OneBu'])?$Arr['OneBu']:0;
						$vArr['Buid'] = isset($Arr['Buid'])?$Arr['Buid']:0;
						$vArr['BuAcc'] = isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
						$ResArr = CustLib::actionGetWishList($vArr);
	
					} else {
						$ResArr = array('error' => array("Code" => "209", "message" => "Added Before"));
					}
				// } else {
					// $ResArr = array('error' => array("Code" => "20030", "message" => "Invalid Permission"));
				// }
			
			}else{
				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}
			
		} else {
			
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}

	public static function actionRemoveWishList($Arr) 
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
			
			$CustID = 0;
			if (isset($Arr['cid'])) {
				if ($Arr['cid'] > 0) {$CustID = $Arr['cid'];}
			};
				
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
				$Lang = 0;
				if (isset($Arr['lang'])) {
					if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
				};	
				
				$Sql = "DELETE FROM wishlist WHERE wl_cid = ".$CustID." AND wl_pid = ".$Arr['pid'];
	
				Yii::app()->db->createCommand($Sql)->execute();
	
				$vArr['CustID'] = $CustID;
				$vArr['Hash'] = $Hash;
				$vArr['lang'] = $Lang;
				$vArr['OneBu'] = isset($Arr['OneBu'])?$Arr['OneBu']:0;
				$vArr['Buid'] = isset($Arr['Buid'])?$Arr['Buid']:0;
				$vArr['BuAcc'] = isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
				$ResArr = CustLib::actionGetWishList($vArr);
				
			//}else{

				//$ResArr = array('error'=>array("Code"=>"203","message"=>"Invalid Permission"));
			//}	

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}

	public static function actionGetWishList($Arr) 
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		$CustID = 0;
		if (isset($Arr['CustID'])) {
			if ($Arr['CustID'] > 0) {$CustID = $Arr['CustID'];}
		};
			
		$Hash = 0;
		if (isset($Arr['Hash'])) {
			if ($Arr['Hash'] != '') {$Hash = $Arr['Hash'];}
		};
			
		$lat = 40.55886796987923;//0;
		if (isset($arr['lat'])) {
			if ($arr['lat'] > 0) {$lat = $arr['lat'];}
		};
			
		$long = 34.97644203125003;//0;
		if (isset($Arr['long'])) {
			if ($Arr['long'] != '') {$long = $Arr['long'];}
		};	
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
			//if($lat != 0 || $long != 0){		
				
			$Lang = 0;
			if (isset($Arr['lang'])) {
				if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
			};	
				
			
			$WhrBu = '';
			
			if(isset($Arr['OneBu'])){
				
				if($Arr['OneBu'] == '1'){
						
					$Buid = 0;	
					if (isset($Arr['Buid'])) {
						if ($Arr['Buid'] > 0) {$Buid = $Arr['Buid'];}
					}
					
					$WhrBu = " AND business_unit.buid = ".$Buid;
				}
			}
			
			
			$BuAcc = 0 ;
			if (isset($Arr['BuAcc'])) {
	
				if ($Arr['BuAcc'] > 0) {$BuAcc = $Arr['BuAcc'];}
			};
			
			$WhrAcc = ' ';
			
			if($BuAcc > 0){
					
				$WhrAcc = " AND business_unit.buid IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
			}
				
				
			if ($Lang != 0 && $Lang != 2){
					
				$Sql = "SELECT products.pid AS ProID,
							   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProTitle,
							   (CASE WHEN p_lang_discription IS NULL THEN products.discription ELSE p_lang_discription END) AS ProDesc,
							   (CASE WHEN p_lang_price IS NULL THEN products.price ELSE p_lang_price END) AS ProPrice,
							   products.rating AS ProRate,business_unit.buid AS BUID,
							   (CASE WHEN bu_lang_title IS NULL THEN business_unit.title ELSE bu_lang_title END) AS BUTitle,
							   business_unit.long AS BULong,business_unit.lat AS BULat,business_unit.logo AS BULogo,
							   (((acos(sin((".$lat."*pi()/180)) * 
						            sin((business_unit.lat*pi()/180)) + cos((".$lat."*pi()/180)) * 
						            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
						            pi()/180))))*180/pi())*60*1.1515
						        ) as BUDis
						FROM wishlist
						LEFT JOIN products 
							 LEFT JOIN business_unit 
								  LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = ".$Lang."
							 ON business_unit.buid = products.buid
						ON wl_pid = pid
						LEFT JOIN products_lang ON p_lang_pid = products.pid AND p_lang_lang_id = ".$Lang."
						WHERE wl_cid = ".$CustID." AND business_unit.active = 0 ".$WhrAcc." ".$WhrBu." ORDER BY business_unit.type ";
				
			}else{
					
				$Sql = "SELECT products.pid AS ProID,products.title AS ProTitle,products.discription AS ProDesc,products.price AS ProPrice,
							   products.rating AS ProRate,
							   business_unit.buid AS BUID,business_unit.title AS BUTitle,business_unit.long AS BULong,business_unit.lat AS BULat,
							   business_unit.logo AS BULogo,
							   (((acos(sin((".$lat."*pi()/180)) * 
						            sin((business_unit.lat*pi()/180)) + cos((".$lat."*pi()/180)) * 
						            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
						            pi()/180))))*180/pi())*60*1.1515
						        ) as BUDis
						FROM wishlist
						LEFT JOIN products 
							 LEFT JOIN business_unit ON business_unit.buid = products.buid
						ON wl_pid = pid
						WHERE wl_cid = ".$CustID." AND business_unit.active = 0 ".$WhrAcc." ".$WhrBu." ORDER BY business_unit.type ";
				
			}
				
			$Data = Yii::app()->db->createCommand($Sql)->queryAll();
			$DArr = array();
			$RealArr = Globals::ReturnGlobals();
			$RealPath = $RealArr['ImgSerPath'] ;
			if (!empty($Data)) {
					
				foreach ($Data as $key => $row) {
						
					//--------Get Product Imgs
					$ImgSql = " SELECT pimgid, pimg_url
					   	 	    FROM products_imgs 
					   	 	    WHERE products_imgs.pid = " . $row['ProID'];
					   	 	    
					$ImgAll = Yii::app()->db->createCommand($ImgSql)->queryAll();
					$Img = array();
	
					if (count($ImgAll) > 0) {
						$ImgPath = $RealPath.'products/thumbnails/';
						
						foreach ($ImgAll as $Imkey => $Imrow) {
							array_push($Img,array('img'=>$ImgPath . $Imrow['pimg_url']));
						}
					}
					array_push($DArr, array('ProID' => $row['ProID'],
										    'ProTitle' => $row['ProTitle'], 
										    'ProDesc' => $row['ProDesc'], 
										    'ProPrice' => $row['ProPrice'],
										    'ProRate' => $row['ProRate'],
										    'ProImg' => $Img,
											'BUID' => $row['BUID'],
										    'BUTitle' => $row['BUTitle'], 
										    'BULogo' => $RealPath .'business_unit/thumbnails/'. $row['BULogo'], 
										    'gps' => array('BULong' => $row['BULong'], 'BULat' => $row['BULat']),
										    'BUDis' => $row['BUDis']
											)
								);
				}
			}
					
			$ResArr = array("Products"=>$DArr);
			
			//} else {
					
			//	$ResArr = array('error'=>array("Code"=>"202","message"=>"UnKnown Location"));
		//	}
		//}else{

			//$ResArr = array('error'=>array("Code"=>"203","message"=>"Invalid Permission"));
		//}	

		return $ResArr;
	}

	//------------------------ Order ---------------------------------

	public static function actionAddToOrder($Arr) {

		$Arr = CI_Security::ChkPost($Arr);

		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {

			$CustID = 0;
			if (isset($Arr['cust_id']) && $Arr['cust_id'] > 0) {
				
				$CustID = $Arr['cust_id'];
			

				$Hash = 0;
				if (isset($Arr['hash']) && !empty($Arr['hash'])) {
					if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];
					}
				}

				$Lat = 0;
				if (isset($Arr['lat'])) {
					if ($Arr['lat'] != '') {$Lat = $Arr['lat'];
					}
				}

				$Long = '0';
				if (isset($Arr['long'])) {
					if ($Arr['long'] != '') {$Long = $Arr['long'];
					}
				};

				$Dist = self::$Distance;
				if (isset($Arr['dist'])) {
					if ($Arr['dist'] != '') {$Dist = $Arr['dist'];
					}
				};
				//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				//$AllowDist = Orders::CHKDistance($Arr['bu_id'] , $Dist , $Lat , $Long);
				//if(($Lat != 0 ||  $Long !=0) && $AllowDist == TRUE){

				if ($Arr['id'] == "") {
	
					$Ord = Orders::CHKCustomerHasOrder($Arr['cust_id']);
	
					if ($Ord['rows_count'] == 0) {
	
						$OrdSQL = "INSERT INTO orders (cid , status , app_type , ord_type)
										   VALUES (" . $Arr['cust_id'] . ",0," . $Arr['AppSource'] . ",'cust')";
	
						Yii::app() -> db -> createCommand($OrdSQL) -> execute();
	
						$OrderID = Yii::app() -> db -> getLastInsertID();
	
					} else {
	
						$OrderID = $Ord['res_id'];
					}
					// echo json_encode(array('order_id'=>$OrderID));
				} else {
	
					$OrderID = $Arr['id'];
				}
				//----------------------------------------------------------------------------------
			
				if(Orders::IsReservedBu($Arr['bu_id'],$CustID) == 'false'){
					
						$Q_Conf = array();
			
						if (isset($Arr['Q_Conf'])) {
			
							if (!empty($Arr['Q_Conf'])) {$Q_Conf = $Arr['Q_Conf'];
							}
						}
						
						foreach ($Q_Conf as $Key => $Row) {
								
							$Conf = array();
							
							if (isset($Row['conf'])) {
				
								if (!empty($Row['conf'])) {$Conf = explode(",", $Row['conf']);}
							};
							
							$Color = 0;
							if (isset($Row['color'])) {
				
								if ($Row['color'] > 0) {$Color = $Row['color'];}
							};
								
							$TotalChild = Orders::TotalChild($Arr['p_id'], $Row['qnt'], $Conf);
							
							$TotalChild['BuPayType'] = ($TotalChild['BuPayType'] == '2'||$TotalChild['BuPayType'] == '0') ? '0' : '1';
							$DeSQL = "INSERT INTO orders_details (ord_id,ord_buid,pid,qnt,disc,price,fees,final_price,pay_type,app_source) 
										 VALUES(" . $OrderID . ",
										 		" . $TotalChild['Buid'] . ",
										 		" . $Arr['p_id'] . ",
										 		" . $Row['qnt'] . " , 
										 		" . $TotalChild['discount'] . ", 
										 		" . $TotalChild['price'] . ",
												" . $TotalChild['fees'] . ",
										 		" . $TotalChild['f_price'] . ",
										 		" . $TotalChild['BuPayType'] . ",
										 		" . $Arr['AppSource'] . ")";
			
							Yii::app() -> db -> createCommand($DeSQL) -> execute();
							$OrderDetID = Yii::app() -> db -> getLastInsertID();
							
							if ($Color > 0) {
			
								$ColorSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) 
													  VALUES (" . $OrderDetID . "," . $Color . ",'color')";
								Yii::app() -> db -> createCommand($ColorSql) -> execute();
							}
							
							if (!empty($Conf)) {
			
								$ConfSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) VALUES ";
				
								for ($i = 0; $i < sizeof($Conf); $i++) {
				
									$ConfSql .= " (" . $OrderDetID . "," . $Conf[$i] . ",'conf'),";
								}
				
								$ConfSql = substr($ConfSql, 0, -1);
								Yii::app() -> db -> createCommand($ConfSql) -> execute();
							}
							
						}	
					//-----------------------------------------------------------------------
					$Lang = 0;
					 if (isset($OrdArr_decode->lang)) {
						 if ($OrdArr_decode->lang > 0) {$Lang = $OrdArr_decode->lang;}
					 };
	
					$ViewArr = array();
					$ViewArr['ord_id']= $OrderID;
					$ViewArr['cust_id']= $CustID;
					$ViewArr['hash']= $Hash;
					$ViewArr['lang']= $Lang;
					$ViewArr['OneBu']= isset($Arr['OneBu'])?$Arr['OneBu']:0;
					$ViewArr['BuAcc']= isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
					$ViewArr['Buid']= $TotalChild['Buid'];
					
					Orders::BuTotalOrder($ViewArr['ord_id'],$ViewArr['Buid']);
					$ResArr = CustLib::actionViewOrderByID($ViewArr);
	
				//} else {
				//	$ResArr = array('error'=>array("Code"=>"203","message"=>"Invalid Permission"));
				//}
	
				//}else{
				//	$ResArr = array('error' => array("code" => "800", "message" => "UnKnown Location"));
				//}
				} else {
					
					$ResArr = array('error' => array("code" => "204", "message" => "Reserved Bu"));
				}
				
			}else{
				
				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
			
		return $ResArr;

	}

	public static function actionAddToOrderCp($Arr) {
			
		$Arr = CI_Security::ChkPost($Arr);

		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {
			
			$CpID = 0;
			if (isset($Arr['cp_id']) && $Arr['cp_id'] > 0) {
					
				$CpID = $Arr['cp_id'];
				
				$Token = '';
				if (isset($Arr['token']) && !empty($Arr['token'])) {
					$Token = $Arr['token'];
				}
				$DevID = '';
				if (isset($Arr['dev_id']) && !empty($Arr['dev_id'])) {
					$DevID = $Arr['dev_id'];
				}
				
				if(Login::ChkCpanelToken($CpID,$Token,$DevID) == True){
					$OpenID = Orders::GetTableOrderOpened($Arr['table_id']);
					
					if($OpenID < 1){
						
						$OrdSQL = "INSERT INTO orders (cid , status , app_type , ord_type)
										   VALUES (" . $CpID . ",0," . $Arr['AppSource'] . ",'wait')";
	
						Yii::app() -> db -> createCommand($OrdSQL) -> execute();
	
						$OpenID = Yii::app() -> db -> getLastInsertID();
					}
					
					//--- Assign Table to Order
					Orders::AssignQrCodetoOrder($Arr['bu_id'],$OpenID,$Arr['table_id']);
					
					    $Q_Conf = array();
			
						if (isset($Arr['Q_Conf'])) {
			
							if (!empty($Arr['Q_Conf'])) {$Q_Conf = $Arr['Q_Conf'];}
						}
					
						foreach ($Q_Conf as $Key => $Row) {
							
							$Conf = array();
							
							if (isset($Row['conf'])) {
				
								if (!empty($Row['conf'])) {$Conf = explode(",", $Row['conf']);}
							};
							
							$Color = 0;
							if (isset($Row['color'])) {
				
								if ($Row['color'] > 0) {$Color = $Row['color'];}
							};
							
							$TotalChild = Orders::TotalChild($Arr['p_id'], $Row['qnt'], $Conf);
							
							$TotalChild['BuPayType'] = ($TotalChild['BuPayType'] == '2'||$TotalChild['BuPayType'] == '0') ? '0' : '1';
							$DeSQL = "INSERT INTO orders_details (ord_id,ord_buid,pid,qnt,disc,price,fees,final_price,pay_type,app_source) 
										 VALUES(" . $OpenID . ",
										 		" . $TotalChild['Buid'] . ",
										 		" . $Arr['p_id'] . ",
										 		" . $Row['qnt'] . " , 
										 		" . $TotalChild['discount'] . ", 
										 		" . $TotalChild['price'] . ",
												" . $TotalChild['fees'] . ",
										 		" . $TotalChild['f_price'] . ",
										 		" . $TotalChild['BuPayType'] . ",
										 		" . $Arr['AppSource'] . ")";
			
							Yii::app() -> db -> createCommand($DeSQL) -> execute();
							$OrderDetID = Yii::app() -> db -> getLastInsertID();
							
							if ($Color > 0) {
			
								$ColorSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) 
													  VALUES (" . $OrderDetID . "," . $Color . ",'color')";
								Yii::app() -> db -> createCommand($ColorSql) -> execute();
							}
							
							if (!empty($Conf)) {
			
								$ConfSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) VALUES ";
				
								for ($i = 0; $i < sizeof($Conf); $i++) {
				
									$ConfSql .= " (" . $OrderDetID . "," . $Conf[$i] . ",'conf'),";
								}
				
								$ConfSql = substr($ConfSql, 0, -1);
								Yii::app() -> db -> createCommand($ConfSql) -> execute();
							}
							//-----------------------------------------------------------------------
							$Lang = 0;
							 if (isset($Arr['lang'])) {
								 if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
							 };
			
							$ViewArr = array();
							$ViewArr['ord_id']= $OpenID;
							$ViewArr['cp_id']= $CpID;
							$ViewArr['token']= $Token;
							$ViewArr['dev_id']= $DevID;
							$ViewArr['lang']= $Lang;
							$ViewArr['table_id'] = $Arr['table_id'];
							$ViewArr['OneBu']= isset($Arr['OneBu'])?$Arr['OneBu']:0;
							$ViewArr['BuAcc']= isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
							$ViewArr['Buid']= $TotalChild['Buid'];
							$ViewArr['ord_type']= 'wait';
							
							Orders::BuTotalOrder($ViewArr['ord_id'],$ViewArr['Buid']);
							$ResArr = CustLib::actionViewOrderByID($ViewArr);
					}	
							
				} else {
				
					$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
				}
				
			} else {
				
				$ResArr = array('error' => array("code" => "222", "message" => "Invalid Waiter"));
			}
			
		}else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
			
		return $ResArr;
	}
	
	public static function actionEditOrder($Arr) {
		//header('Content-Type: application/json');
		//$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {

			// $OrdArr = $_POST['order'];
			// $JsonArr = json_decode($OrdArr);

			$CustID = 0;
			if (isset($Arr['cust_id'])) {
				if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}
			};
			
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];}
			};
			
			
			$Lat = 0;
			if (isset($Arr['lat'])) {
				if ($Arr['lat'] != '') {$Lat = $Arr['lat'];}
			};
	
			$Long = '0';
			if (isset($Arr['long'])) {
				if ($Arr['long'] != '') {$Long = $Arr['long'];}
			};
			
			$Dist = self::$Distance;
			if (isset($Arr['dist'])) {
				if ($Arr['dist'] != '') {$Dist = $Arr['dist'];}
			};
			
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				if(Orders::IsReservedBu($Arr['bu_id'],$CustID) == 'false'){	
					//$AllowDist = Orders::CHKDistance($JsonArr->bu_id , $Dist , $Lat , $Long);
					//if(($Lat != 0 ||  $Long !=0) && $AllowDist == TRUE){
						$Conf = array();
						
						if(isset($Arr['conf'])) {
							if (!empty($Arr['conf'])) {
								$Conf = $Arr['conf'];
								$Conf = explode(",",$Conf);	
							}
						};
						
						$Resrow = Orders::TotalChild($Arr['p_id'], $Arr['qnt'],$Conf);
						$Resrow['BuPayType'] = $Resrow['BuPayType'] == 2 ? 0 : 1;
	
						$Sql = " UPDATE orders_details SET ord_buid = " . $Resrow['Buid'] . ",
														   pid = " . $Arr['p_id'] . ",
														   qnt = " . $Arr['qnt'] . ",
														   disc = " . $Resrow['discount'] . ",
														   price = " . $Resrow['price'] . ",
														   fees = " . $Resrow['fees'] . " ,
														   final_price = " . $Resrow['f_price'] . ",
														   pay_type = " . $Resrow['BuPayType'] . ",
														   app_source = " . $Arr['AppSource'] . "
														   WHERE ord_det_id = " . $Arr['c_id'];
			
						Yii::app() -> db -> createCommand($Sql) -> execute();
			
						$Lang = 0;
			
						if (isset($Arr['lang'])) {
							if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
						};
						//-------------Insert Color And Configs
						$DetailOrdID = $Arr['c_id'];
						
						$DelSql = " DELETE FROM orders_detail_conf WHERE ord_de_conf_de_id = ".$DetailOrdID;
						Yii::app() -> db -> createCommand($DelSql) -> execute();
						
						//----color
							
							$Color = 0;
							if(isset($Arr['color'])) {
				
								if ($Arr['color'] > 0) {$Color = $Arr['color'];}
							};
							if($Color > 0){
								
								$ColorSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) 
											  VALUES (".$DetailOrdID.",".$Color.",'color')";
								Yii::app() -> db -> createCommand($ColorSql) -> execute();
							}
							
							//----config
							if(!empty($Conf)){
									
								$ConfSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) VALUES ";
								
								for ($i = 0; $i < sizeof($Conf); $i++) {
										 
									$ConfSql .= " (".$DetailOrdID.",".$Conf[$i].",'conf'),";
								}
								
								$ConfSql = substr($ConfSql, 0, -1);
								Yii::app() -> db -> createCommand($ConfSql) -> execute();
							}
							//------------------------------------------------------
						
						$ViewArr = array();
						$ViewArr['ord_id']= $Arr['id'];
						$ViewArr['cust_id']= $CustID;
						$ViewArr['hash']= $Hash;
						$ViewArr['lang']= $Lang;
						$ViewArr['OneBu']= isset($Arr['OneBu'])?$Arr['OneBu']:0;
						$ViewArr['BuAcc']= isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
						$ViewArr['Buid']= $Resrow['Buid'];
						Orders::BuTotalOrder($ViewArr['ord_id'],$ViewArr['Buid']);
						$ResArr = CustLib::actionViewOrderByID($ViewArr);
					//}else{
					//	$ResArr = array('error' => array("code" => "202", "message" => "UnKnown Location"));
					//}
				 }else{
				 	
				 	$ResArr = array('error' => array("code" => "204", "message" => "Reserved Bu"));
				 }
			//} else {

				//$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
			//}

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;

	}
	
	public static function actionEditOrderCp($Arr) {
		
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {

			$CpID = 0;
			
			if (isset($Arr['cp_id']) && $Arr['cp_id'] > 0) {
				
				$CpID = $Arr['cp_id'];
				
				$Token = '';
				if (isset($Arr['token']) && !empty($Arr['token'])) {
					$Token = $Arr['token'];
				}
				$DevID = '';
				if (isset($Arr['dev_id']) && !empty($Arr['dev_id'])) {
					$DevID = $Arr['dev_id'];
				}
				
				if(Login::ChkCpanelToken($CpID,$Token,$DevID) == True){
					
					$Conf = array();
						
						if(isset($Arr['conf'])) {
							if (!empty($Arr['conf'])) {
								$Conf = $Arr['conf'];
								$Conf = explode(",",$Conf);	
							}
						};
						
						$Resrow = Orders::TotalChild($Arr['p_id'], $Arr['qnt'],$Conf);
						$Resrow['BuPayType'] = $Resrow['BuPayType'] == 2 ? 0 : 1;
	
						$Sql = " UPDATE orders_details SET ord_buid = " . $Resrow['Buid'] . ",
														   pid = " . $Arr['p_id'] . ",
														   qnt = " . $Arr['qnt'] . ",
														   disc = " . $Resrow['discount'] . ",
														   price = " . $Resrow['price'] . ",
														   fees = " . $Resrow['fees'] . " ,
														   final_price = " . $Resrow['f_price'] . ",
														   pay_type = " . $Resrow['BuPayType'] . ",
														   app_source = " . $Arr['AppSource'] . "
														   WHERE ord_det_id = " . $Arr['c_id'];
			
						Yii::app() -> db -> createCommand($Sql) -> execute();
			
						$Lang = 0;
			
						if (isset($Arr['lang'])) {
							if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
						};
						//-------------Insert Color And Configs
						$DetailOrdID = $Arr['c_id'];
						
						$DelSql = " DELETE FROM orders_detail_conf WHERE ord_de_conf_de_id = ".$DetailOrdID;
						Yii::app() -> db -> createCommand($DelSql) -> execute();
						
						//----color
							
							$Color = 0;
							if(isset($Arr['color'])) {
				
								if ($Arr['color'] > 0) {$Color = $Arr['color'];}
							};
							if($Color > 0){
								
								$ColorSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) 
											  VALUES (".$DetailOrdID.",".$Color.",'color')";
								Yii::app() -> db -> createCommand($ColorSql) -> execute();
							}
							
							//----config
							if(!empty($Conf)){
									
								$ConfSql = " INSERT INTO orders_detail_conf(ord_de_conf_de_id,ord_de_conf_co_id,ord_de_conf_type) VALUES ";
								
								for ($i = 0; $i < sizeof($Conf); $i++) {
										 
									$ConfSql .= " (".$DetailOrdID.",".$Conf[$i].",'conf'),";
								}
								
								$ConfSql = substr($ConfSql, 0, -1);
								Yii::app() -> db -> createCommand($ConfSql) -> execute();
							}
							//------------------------------------------------------
							$OpenID = Orders::GetTableOrderOpened($Arr['table_id']);
							$ViewArr = array();
							$ViewArr['ord_id']= $OpenID;
							$ViewArr['cp_id']= $CpID;
							$ViewArr['token']= $Token;
							$ViewArr['dev_id']= $DevID;
							$ViewArr['lang']= $Lang;
							$ViewArr['table_id'] = $Arr['table_id'];
							$ViewArr['OneBu']= isset($Arr['OneBu'])?$Arr['OneBu']:0;
							$ViewArr['BuAcc']= isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
							$ViewArr['Buid']= $Resrow['Buid'];
							$ViewArr['ord_type']= 'wait';
							
							Orders::BuTotalOrder($ViewArr['ord_id'],$ViewArr['Buid']);
							$ResArr = CustLib::actionViewOrderByID($ViewArr);
				} else {
					
					$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
				}
				
			} else {
				
				$ResArr = array('error' => array("code" => "222", "message" => "Invalid Waiter"));
			}
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;

	}
	
	public static function actionRemoveFromOrder($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
	
			$CustID = 0;
			if (isset($Arr['cust_id'])) {
				if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}
			};
				
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash']; }
			};
			
			$Lat = 0;
			if (isset($Arr['lat'])) {
				if ($Arr['lat'] != '') {$Lat = $Arr['lat'];}
			};
	
			$Long = '0';
			if (isset($Arr['long'])) {
				if ($Arr['long'] != '') {$Long = $Arr['long'];}
			};
			
			$Dist = self::$Distance;
			if (isset($Arr['dist'])) {
				if ($Arr['dist'] != '') { $Dist = $Arr['dist']; }
				
			}
			
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
				if(Orders::IsReservedBu($Arr['bu_id'],$CustID) == 'false'){		
					//$AllowDist = Orders::CHKDistance($OrdArr_decode->bu_id , $Dist , $Lat , $Long);
					//if(($Lat != 0 ||  $Long !=0) && $AllowDist == TRUE){
							
						$DeConfSQL = "DELETE FROM orders_detail_conf WHERE ord_de_conf_de_id = " . $Arr['c_id'];
						Yii::app() -> db -> createCommand($DeConfSQL) -> execute();
						
						$DeSQL = "DELETE FROM orders_details WHERE ord_det_id = " . $Arr['c_id'];
						Yii::app() -> db -> createCommand($DeSQL) -> execute();
											
						$CountDeSQL = "SELECT ord_det_id FROM orders_details WHERE ord_id = " . $Arr['id'];
						$CountDeRes = Yii::app() -> db -> createCommand($CountDeSQL) -> queryAll();
						
						$DeCount = count($CountDeRes);
		
						if ($DeCount == 0) {
							
							$OrdSQL = "DELETE FROM orders WHERE ord_id = " . $Arr['id'];
							Yii::app() -> db -> createCommand($OrdSQL) -> execute();
						}
		
						$Lang = 0;
						if (isset($Arr['lang'])) {
							if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
						};
							
						$ViewArr = array();
						$ViewArr['ord_id']= $Arr['id'];
						$ViewArr['cust_id']= $CustID;
						$ViewArr['hash']= $Hash;
						$ViewArr['lang']= $Lang;
						$ViewArr['OneBu']= isset($Arr['OneBu'])?$Arr['OneBu']:0;
						$ViewArr['Buid']= isset($Arr['bu_id'])?$Arr['bu_id']:0;
						$ViewArr['BuAcc']= isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
						Orders::BuTotalOrder($ViewArr['ord_id'],$ViewArr['Buid']);
						$ResArr = CustLib::actionViewOrderByID($ViewArr);
						
					//}else{
					//	$ResArr = array("Result" => array('error' => array("code" => "800", "message" => "UnKnown Location")));
					//}
					} else {
					
						$ResArr = array('error' => array("code" => "204", "message" => "Reserved Bu"));
					}
				//} else {
	
					//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
				//}
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function actionRemoveFromOrderCp($Arr){
		
		$Arr = CI_Security::ChkPost($Arr);

		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {
			
			$CpID = 0;
			
			if (isset($Arr['cp_id']) && $Arr['cp_id'] > 0) {
				
				$CpID = $Arr['cp_id'];
				
				$Token = '';
				if (isset($Arr['token']) && !empty($Arr['token'])) {
					$Token = $Arr['token'];
				}
				$DevID = '';
				if (isset($Arr['dev_id']) && !empty($Arr['dev_id'])) {
					$DevID = $Arr['dev_id'];
				}
				
				if(Login::ChkCpanelToken($CpID,$Token,$DevID) == True){
						
					$OpenID = Orders::GetTableOrderOpened($Arr['table_id']);
							
					$DeConfSQL = "DELETE FROM orders_detail_conf WHERE ord_de_conf_de_id = " . $Arr['c_id'];
					Yii::app() -> db -> createCommand($DeConfSQL) -> execute();
					
					$DeSQL = "DELETE FROM orders_details WHERE ord_det_id = " . $Arr['c_id'];
					Yii::app() -> db -> createCommand($DeSQL) -> execute();
										
					$CountDeSQL = "SELECT ord_det_id FROM orders_details WHERE ord_id = " . $OpenID;
					$CountDeRes = Yii::app() -> db -> createCommand($CountDeSQL) -> queryAll();
					
					$DeCount = count($CountDeRes);
	
					if ($DeCount == 0) {
						
						$OrdSQL = "DELETE FROM orders WHERE ord_id = " . $OpenID;
						Yii::app() -> db -> createCommand($OrdSQL) -> execute();
					}
	
					$Lang = 0;
					if (isset($Arr['lang'])) {
						if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
					};
						
					$ViewArr = array();
					$ViewArr['ord_id']= $OpenID;
					$ViewArr['cp_id']= $CpID;
					$ViewArr['token']= $Token;
					$ViewArr['dev_id']= $DevID;
					$ViewArr['lang']= $Lang;
					$ViewArr['table_id'] = $Arr['table_id'];
					$ViewArr['OneBu']= isset($Arr['OneBu'])?$Arr['OneBu']:0;
					$ViewArr['BuAcc']= isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
					$ViewArr['Buid']= isset($Arr['bu_id'])?$Arr['bu_id']:0;
					$ViewArr['ord_type']= 'wait';
					Orders::BuTotalOrder($ViewArr['ord_id'],$ViewArr['Buid']);
					$ResArr = CustLib::actionViewOrderByID($ViewArr);
					
				} else {
						
					$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
				}
				
			} else {
				
				$ResArr = array('error' => array("code" => "222", "message" => "Invalid Waiter"));
			}
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function actionRemoveBuFromOrder($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
	
			$CustID = 0;
			if (isset($Arr['cust_id'])) {
				if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}
			};
				
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash']; }
			};
			
			$Lat = 0;
			if (isset($Arr['lat'])) {
				if ($Arr['lat'] != '') {$Lat = $Arr['lat'];}
			};
	
			$Long = '0';
			if (isset($Arr['long'])) {
				if ($Arr['long'] != '') {$Long = $Arr['long'];}
			};
			
			$Dist = self::$Distance;
			if (isset($Arr['dist'])) {
				if ($Arr['dist'] != '') { $Dist = $Arr['dist']; }
			}
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
				if(Orders::IsReservedBu($Arr['bu_id'],$CustID) == 'false'){
						
					//$AllowDist = Orders::CHKDistance($OrdArr_decode->bu_id , $Dist , $Lat , $Long);
					//if(($Lat != 0 ||  $Long !=0) && $AllowDist == TRUE){
							
						$DeConfSQL = "DELETE FROM orders_detail_conf WHERE ord_de_conf_de_id IN
									 (SELECT ord_det_id FROM orders_details WHERE ord_id = ".$Arr['id']." AND ord_buid = ".$Arr['bu_id'].") ";
						Yii::app() -> db -> createCommand($DeConfSQL) -> execute();
						
						$DeSQL = "DELETE FROM orders_details WHERE ord_id = ".$Arr['id']." AND ord_buid = " . $Arr['bu_id'];
						Yii::app() -> db -> createCommand($DeSQL) -> execute();
											
						$CountDeSQL = "SELECT ord_det_id FROM orders_details WHERE ord_id = " . $Arr['id'];
						$CountDeRes = Yii::app() -> db -> createCommand($CountDeSQL) -> queryAll();
						
						$DeCount = count($CountDeRes);
		
						if ($DeCount == 0) {
							
							$OrdSQL = "DELETE FROM orders WHERE ord_id = " . $Arr['id'];
							Yii::app() -> db -> createCommand($OrdSQL) -> execute();
						}
		
						$Lang = 0;
						if (isset($Arr['lang'])) {
							if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
						};
							
						$ViewArr = array();
						$ViewArr['ord_id']= $Arr['id'];
						$ViewArr['cust_id']= $CustID;
						$ViewArr['hash']= $Hash;
						$ViewArr['lang']= $Lang;
						$ViewArr['OneBu']= isset($Arr['OneBu'])?$Arr['OneBu']:0;
						$ViewArr['Buid']= isset($Arr['bu_id'])?$Arr['bu_id']:0;
						$ViewArr['BuAcc']= isset($Arr['BuAcc'])?$Arr['BuAcc']:0;
						Orders::BuTotalOrder($ViewArr['ord_id'],$ViewArr['Buid']);
						$ResArr = CustLib::actionViewOrderByID($ViewArr);	
						
					//}else{
					//	$ResArr = array("Result" => array('error' => array("code" => "800", "message" => "UnKnown Location")));
					//}
				} else {
					
					$ResArr = array('error' => array("code" => "204", "message" => "Reserved Bu"));
				}
			//} else {

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}

	public static function actionCloseOrder($Arr){
			
		$Arr = CI_Security::ChkPost($Arr);
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {

			$CustID = 0;

			if (isset($Arr['cust_id'])) {
				if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}
			};
				
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];}
			};
			
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				$Res = 0;
					
				$PayType = -1;
				if (isset($Arr['pay_type'])) {
					if ($Arr['pay_type'] >= 0) {$PayType = $Arr['pay_type'];}
				};	
					
				if($PayType > -1){
					
					if($PayType == 0){
						
						$ShipAddID = 0;
						if(isset($Arr['ShipAddID']) && !empty($Arr['ShipAddID'])){
							$ShipAddID = $Arr['ShipAddID'];
						}
						$Arr['BillingAddID']= Orders::GetCustBillingAddID($CustID);
						//$Arr['billingAddr'] = Orders::CHKAddr($Arr['BillingAddID']);
						
						// print_r($Arr);
						// return;
						
						$Payed = FALSE;
						
						$PaySys = $Arr['PaySys'];
						
						if($PaySys == 'AT'){
							$Payed = CustLib::actionAuthorizeNetPay($Arr);
						}
						//$Payed = CustLib::actionPayOrder($Arr);
						
						if($Payed == TRUE){
							
							//-----Remove From WishList
							$DelSql = "DELETE FROM wishlist 
									   WHERE wl_cid = ".$CustID."
									   AND wl_pid IN (SELECT pid FROM orders_details WHERE ord_id = ".$Arr['id']." AND ord_buid = ".$Arr['bu_id'].")";
							Yii::app()->db->createCommand($DelSql)->execute();
				
							//----- Update Close Date for OnLine Bu
							
							$CloseSql = " UPDATE orders_details SET close_date = NOW() , 
																	cust_billingAddr = ".$Arr['BillingAddID'].",
																	cust_shipAddr = ".$ShipAddID.",
																	reserved_bu = 1,
																	pay_type = ".$PayType.",
																	app_source = ".$Arr['AppSource']."
										  WHERE ord_id = ".$Arr['id']." AND ord_buid = ".$Arr['bu_id'];
							$Res = Yii::app() -> db -> createCommand($CloseSql)->execute();
							
							//------ Update Close Date BuTotals
							Orders::BuTotalClose($Arr['id'],$Arr['bu_id'],0,0);
							
						} else {
							
							$ResArr = array('orders' => array('result' => 'FALSE'));
						}	
					}
					if($PayType == 1){
						
						//-----Remove From WishList
						$DelSql = " DELETE FROM wishlist 
								    WHERE wl_cid = ".$CustID."
								    AND wl_pid IN (SELECT pid FROM orders_details WHERE ord_id = ".$Arr['id']." AND ord_buid = ".$Arr['bu_id'].")";
						Yii::app()->db->createCommand($DelSql)->execute();
						
						//----- Update Close Date for OnLine Bu
							
						$CloseSql = " UPDATE orders_details SET reserved_bu = 1 ,pay_type = ".$PayType.",app_source = ".$Arr['AppSource']."
									  WHERE ord_id = ".$Arr['id']." AND ord_buid = ".$Arr['bu_id'];
						$Res = Yii::app() -> db -> createCommand($CloseSql)->execute();
						
						//------ Update Close Date BuTotals
						Orders::BuTotalClose($Arr['id'],$Arr['bu_id'],0,1);
					}
					
					if($Res > 0){$Res = 'TRUE';}
					else {$Res = 'FALSE';}
					
					$ResArr = array('orders' => array('result' => $Res));
							
				} else {
					
					$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
				}
			//} else {

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}
			
		}else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function actionCloseQrCode($Arr)
	{
		$Arr = CI_Security::ChkPost($Arr);
		$ResArr = array();
		if (isset($Arr) && !empty($Arr)) {

			$CustID = 0;

			if (isset($Arr['cust_id'])) {
				if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}
			};
				
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];}
			};
			
			$QrcodeSQL = "SELECT * FROM bu_tables WHERE bu_table_qrcode = '".$Arr['qrcode']."' ";
		    $QrcodeRes = Yii::app() -> db -> createCommand($QrcodeSQL) -> queryRow();
			
			if(!empty($QrcodeRes)){
				
				$Res = 0;
				
				//----- Update Close Date for OnLine Bu
								
				$CloseSql = " UPDATE orders_details SET reserved_bu = 1 ,pay_type = 1,close_date = NOW(),app_source = ".$Arr['AppSource']."
							  WHERE ord_id = ".$Arr['id']." AND ord_buid = ".$Arr['bu_id'];
				$Res = Yii::app() -> db -> createCommand($CloseSql)->execute();
				
				Orders::AssignQrCodetoOrder($Arr['bu_id'],$Arr['id'],$QrcodeRes['bu_table_id']);
				
				//Orders::CloseOrder($Arr['id']);
				
				//------ Update Close Date BuTotals
				Orders::BuTotalClose($Arr['id'],$Arr['bu_id'],0,1);
				
				//------ Check IF IN Reservations
				
				$UpCloseRes = " UPDATE reservations SET res_ord_id = ".$Arr['id']."
				                WHERE res_ord_id = 0 AND res_unit_id = ".$QrcodeRes['bu_table_id']." AND res_type = 'TA' AND res_cust_id =".$CustID;
				Yii::app() -> db -> createCommand($UpCloseRes)->execute();
				
				//-------------------------------		
				if($Res > 0){$Res = 'TRUE';}
				else {$Res = 'FALSE';}
				
				//----- Get Printer Ips
				
				$IpsArr = array();
				
				$IpsSQL = " SELECT bu_p_ip_id,bu_p_ip_ip,
								   (CASE WHEN bu_p_ip_primary = 1 THEN 'True' ELSE 'False' END)AS IpPrimary
							FROM bu_printer_ips WHERE bu_p_ip_active = 0 AND bu_p_ip_bu_id = ".$Arr['bu_id'];
				$IpsRes = Yii::app() -> db -> createCommand($IpsSQL)->execute();
				
				foreach ($IpsRes as $Ipkey => $Iprow) {
					array_push($IpsArr,array('ID'=>$Iprow['bu_p_ip_id'],'IP'=>$Iprow['bu_p_ip_ip'],'IpPrimary'=>$Iprow['IpPrimary']));
				}
				
				//----- Get Bu Data
				
				$BuArr = array();
				
				$BuSQL = " SELECT buid,title,description 
								  IFNULL((SELECT bu_contact_val FROM bu_contacts WHERE bu_contact_type = 2 AND bu_contact_bu_id = ".$Arr['bu_id']." LIMIT 0,1),'')AS Adress,
								  IFNULL((SELECT bu_contact_val FROM bu_contacts WHERE bu_contact_type = 1 AND bu_contact_bu_id = ".$Arr['bu_id']." LIMIT 0,1),'')AS Phone,
				          FROM business_unit WHERE buid = ".$Arr['bu_id'];
				
				$BuData = Yii::app() -> db -> createCommand($BuSQL)->queryRow();
				if(!empty($BuData)){
					array_push($BuArr , array('BuName'=>$BuData['title'],'BuDesc'=>$BuData['description'],
											  'BuPhone'=>$BuData['Phone'],'BuAdress'=>$BuData['Adress']));
				}
				
				//------ Get Table Data
				
				$TableArr = array();
					array_push($BuArr , array('ID'=>$QrcodeRes['bu_table_id'],'Number'=>$QrcodeRes['bu_table_serial']));
			
				$ResArr = array('orders' => array('result' => $Res,'Table'=>$TableArr,'Ips'=>$IpsArr,'Bu'=>$BuArr));
				
			} else {
				
				$ResArr = array('error' => array("code" => "218", "message" => "Invalid QrCode"));
			}
			
		} else {
				
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function actionViewOrderByID($Arr){
		
		//header('Content-Type: application/json');
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		if(isset($Arr) && !empty($Arr)){
			$Type = 'cust';
		    if (isset($Arr['ord_type'])) {
		    	
				$Type = $Arr['ord_type'];
		    }
			$Permission = False;$OrdID = 0;
			
			if($Type == 'cust'){
					
				$CustID = 0;
				if (isset($Arr['cust_id'])) {if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}};
				
				$Hash = 0;
				if (isset($Arr['hash'])) {$Hash = $Arr['hash'];}
				
				//$Permission = Login::ChkCustomerHash($CustID,$Hash);
				$Permission = True;
				
				$Ord = Orders::CHKCustomerHasOrder($Arr['cust_id']);
	
				if ($Ord['rows_count'] > 0) {
					$OrdID = $Ord['res_id'];
				}
				
			}
			if($Type == 'wait'){
					
				$CpID = 0;
				if (isset($Arr['cp_id'])) {if ($Arr['cp_id'] > 0) {$CpID = $Arr['cp_id'];}};
				
				$Token = '';
				if (isset($Arr['token'])) {$Token = $Arr['token'];}
				
				$DevID = '';
				if (isset($Arr['dev_id'])) {$DevID = $Arr['dev_id'];}
				
				$Permission = Login::ChkCpanelToken($CpID,$Token,$DevID);
				
				$OpenID = Orders::GetTableOrderOpened($Arr['table_id']);
				if($OpenID > 0){
					$OrdID = $OpenID;
				}
			}
				//if($Permission == True){
						
					$Lang = 0;
					if (isset($Arr['lang'])) {
						if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
					};
			
					$WhrBu = '';
			
					if(isset($Arr['OneBu'])){
						
						if($Arr['OneBu'] == '1'){
								
							$Buid = 0;	
							if (isset($Arr['Buid'])) {
								if ($Arr['Buid'] > 0) {$Buid = $Arr['Buid'];}
							}
							
							$WhrBu = " AND business_unit.buid = ".$Buid;
						}
					}
					
					$BuAcc = 0 ;
					if (isset($Arr['BuAcc'])) {
			
						if ($Arr['BuAcc'] > 0) {$BuAcc = $Arr['BuAcc'];}
					};
					
					$WhrAcc = ' ';
					
					if($BuAcc > 0){
							
						$WhrAcc = " AND business_unit.buid IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
					}
			
					if ($Lang != 0 && $Lang != 2){
						
						/*
						$BuSQL = " SELECT ord_buid AS Buid ,
										 (CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END) AS BuName,
										 `long` AS BuLong,lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr
								   FROM orders_details 
								   LEFT JOIN business_unit 
										   LEFT JOIN business_unit_lang ON bu_lang_bu_id = buid AND bu_lang_lang_id = ".$Lang."
								   ON ord_buid = buid
								   WHERE ord_id = ".$Arr['ord_id']." AND close_date IS NULL ".$WhrBu." ".$WhrAcc."
								   GROUP BY ord_buid ORDER BY business_unit.type , orders_details.close_date DESC ";*/
						
								   
						
						$BuSQL = " SELECT ord_bu_total_bu_id AS Buid ,
										  (CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END) AS BuName,
										  `long` AS BuLong,lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr,
						                  ord_bu_total_total AS BuTotal,ord_bu_total_tax AS BuTax,ord_bu_total_delivery AS BuDelivery ,
						                  ord_bu_total_fees AS BuFees,ord_bu_total_shipment AS BuShipment,ord_bu_total_VAT AS BuVat,ord_bu_total_service AS BuService
								   FROM orders_bu_totals 
								   LEFT JOIN business_unit 
										   LEFT JOIN business_unit_lang ON bu_lang_bu_id = buid AND bu_lang_lang_id = ".$Lang."
								   ON ord_bu_total_bu_id = buid
								   WHERE ord_bu_total_ord_id = ".$OrdID." AND ord_bu_total_close_date IS NULL ".$WhrBu." ".$WhrAcc."
								   ORDER BY business_unit.type , ord_bu_total_close_date DESC ";
						
						
					}else{
						
						/*
						$BuSQL = " SELECT ord_buid AS Buid ,title AS BuName,`long` AS BuLong,
										  lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr
								   FROM orders_details 
								   LEFT JOIN business_unit ON ord_buid = buid
								   WHERE ord_id = ".$Arr['ord_id']." AND close_date IS NULL ".$WhrBu." ".$WhrAcc."
								   GROUP BY ord_buid ORDER BY business_unit.type , orders_details.close_date DESC ";*/
						
						$BuSQL = " SELECT ord_bu_total_bu_id AS Buid ,title AS BuName,`long` AS BuLong,
										  lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr,
						                  ord_bu_total_total AS BuTotal,ord_bu_total_tax AS BuTax,ord_bu_total_delivery AS BuDelivery ,
						                  ord_bu_total_fees AS BuFees,ord_bu_total_shipment AS BuShipment,ord_bu_total_VAT AS BuVat,ord_bu_total_service AS BuService
								   FROM orders_bu_totals 
								   LEFT JOIN business_unit ON ord_bu_total_bu_id = buid
								   WHERE ord_bu_total_ord_id = ".$OrdID." AND ord_bu_total_close_date IS NULL ".$WhrBu." ".$WhrAcc."
								   ORDER BY business_unit.type , ord_bu_total_close_date DESC ";
					}
					
					$BuData = Yii::app()->db->createCommand($BuSQL)->queryAll();
					
					$OrdRow = array();
					
					$RealArr = Globals::ReturnGlobals();
					$RealPath = $RealArr['ImgSerPath'];
				
					$BuArr = array();
					foreach ($BuData as $BuKey => $BuRow) {
							
							if ($Lang != 0 && $Lang != 2){
										
								$DeSQL = "SELECT orders.ord_id AS OrdID,(CASE WHEN status = 0 THEN 'Opened' ELSE 'Closed' END)AS OrdStat,
											   date(orders.created) AS OrdDate ,ord_det_id AS ID,
											   (CASE WHEN reserved_bu = 0 THEN 'false' ELSE 'true' END)AS reserved_bu,app_source,
											   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProdName,
											   ord_det.pid AS ProdID,qnt AS Qnt,disc AS Disc ,ord_det.price AS Price,
											   fees AS Fees,final_price AS F_Price,products.rating AS ProdRate,pay_type AS PayType,
											   sub.cat_lang_title AS SubCatName, parent.cat_lang_title AS ParCatName , ParCatID , SubCatID
										FROM orders 
										LEFT JOIN orders_details AS ord_det
											LEFT JOIN products 
												 LEFT JOIN products_lang ON p_lang_pid = pid AND p_lang_lang_id = ".$Lang."
											ON ord_det.pid = products.pid
										ON ord_det.ord_id = orders.ord_id
										LEFT JOIN CatAndCatSub ON BCatID = products.csid  JOIN catsub_lang  AS sub 
										ON sub.cat_lang_cs_id = BCatID AND sub.cat_lang_lang_id = ".$Lang."
										LEFT JOIN catsub_lang AS parent 
										ON parent.cat_lang_cs_id = CatAndCatSub.ParCatID AND parent.cat_lang_lang_id = ".$Lang."
										WHERE close_date IS NULL AND orders.ord_id = ".$OrdID." AND ord_buid = ".$BuRow['Buid'];
								
							} else {
									
								$DeSQL = "SELECT orders.ord_id AS OrdID,(CASE WHEN status = 0 THEN 'Opened' ELSE 'Closed' END)AS OrdStat,
											   date(orders.created) AS OrdDate ,ord_det_id AS ID,
											   (CASE WHEN reserved_bu = 0 THEN 'false' ELSE 'true' END)AS reserved_bu, app_source,
											   products.title AS ProdName,ord_det.pid AS ProdID,qnt AS Qnt,disc AS Disc ,ord_det.price AS Price,
											   fees AS Fees,final_price AS F_Price,products.rating AS ProdRate,pay_type AS PayType,
											   ParCatID , ParCatName, SubCatID , SubCatName
										FROM orders 
										LEFT JOIN orders_details AS ord_det
											LEFT JOIN products ON ord_det.pid = products.pid
										ON ord_det.ord_id = orders.ord_id
										LEFT JOIN CatAndCatSub ON products.csid = BCatID
										WHERE close_date IS NULL AND orders.ord_id = ".$OrdID." AND ord_buid = ".$BuRow['Buid'];
							}
							
							$DeData = Yii::app()->db->createCommand($DeSQL)->queryAll();
							$OrdBuDet = array();$BuTotal = 0;$ReservedBu = 0;
							foreach ($DeData as $DeKey => $DeRow) {
										
								$OrdRow = $DeRow;	
								$ReservedBu = $DeRow['reserved_bu'];
								//----Imgs
								$ImgSQL = "SELECT * FROM products_imgs WHERE pid = " . $DeRow['ProdID'] . " LIMIT 1";
								$ImgData = Yii::app()->db->createCommand($ImgSQL)->queryRow();
								$ProdImg = "";
								if(!empty($ImgData)){
									$ProdImg = $RealPath.'products/thumbnails/'.$ImgData['pimg_url'];
								}
								//----Conf
								if ($Lang != 0 && $Lang != 2) {
									$ConfSql = "SELECT cfg_id ,pdconfv_id, pdconfv_value, pdconfv_chkrad,
									   	 		(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name , 
												(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid	
										 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "	
										 WHERE pdconfv_pid = ".$DeRow['ProdID']. " AND parent_id IS NULL ";
								} else {
									$ConfSql = "SELECT cfg_id , pdconfv_id, pdconfv_value, pdconfv_chkrad, name , 
											(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
											 FROM pd_conf_v
											 JOIN pd_config ON cfg_id = pdconfv_confid
											 WHERE pdconfv_pid =".$DeRow['ProdID']." AND parent_id IS NULL ";
						
								}
								$ConfAll = Yii::app()->db->createCommand($ConfSql)->queryAll();
								$Conf = array();
								foreach ($ConfAll as $ConfKey => $ConfRow) {
									$SubConfArr = array();
									if ($Lang != 0 && $Lang != 2) {
										$SConfSql = "SELECT pdconfv_id, pdconfv_value, 
														(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name,
														(CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
													 FROM pd_conf_v
													 LEFT JOIN pd_config ON cfg_id = pdconfv_confid
													 LEFT JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "
													 LEFT JOIN orders_detail_conf ON pdconfv_id = ord_de_conf_co_id AND ord_de_conf_type = 'conf' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
													 WHERE pdconfv_pid = ".$DeRow['ProdID']." AND parent_id = " . $ConfRow['cfg_id'];
										
									} else {
										$SConfSql = "SELECT pdconfv_id, pdconfv_value, name,
													 (CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
													 FROM pd_conf_v
													 LEFT JOIN pd_config ON cfg_id = pdconfv_confid
													 LEFT JOIN orders_detail_conf ON pdconfv_id = ord_de_conf_co_id AND ord_de_conf_type = 'conf' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
													 WHERE pdconfv_pid = ".$DeRow['ProdID']." AND parent_id = ".$ConfRow['cfg_id'];
										
									}
									$SConfAll = Yii::app() -> db -> createCommand($SConfSql) -> queryAll();
									foreach ($SConfAll as $SConfKey => $SConfRow) {
										array_push($SubConfArr, array('subId'=>$SConfRow['pdconfv_id'],
																	  'SubConf' => $SConfRow['name'], 
																	  'Val' => $SConfRow['pdconfv_value'],
																	  'IS_Apply' => $SConfRow['IS_Apply']));
							
									}
									array_push($Conf, array('confId'=>$ConfRow['pdconfv_id'],
														    'Conf' => $ConfRow['name'], 
														    'Check' => $ConfRow['pdconfv_chkrad'], 
														    'SubConfig' => $SubConfArr));
							
								}
								//---- Colors
								if ($Lang != 0 && $Lang != 2) {
									$ColorSql = " SELECT color_id,color_code,
												  	(CASE WHEN color_lang_name IS NULL THEN color_name ELSE color_lang_name END)AS color_name ,
											   	 	(CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
												  FROM prod_colors
											   	  LEFT JOIN prod_colors_lang ON color_lang_color_id = color_id AND color_lang_lang_id = " . $Lang . "
											   	  LEFT JOIN orders_detail_conf ON color_id = ord_de_conf_co_id AND ord_de_conf_type = 'color' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
											   	  WHERE color_pid = " .$DeRow['ProdID'];
								
								} else {
									$ColorSql = "SELECT color_id, color_name ,color_code ,
												       (CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
												 FROM prod_colors 
												 LEFT JOIN orders_detail_conf ON color_id = ord_de_conf_co_id AND ord_de_conf_type = 'color' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
												 WHERE color_pid = " .$DeRow['ProdID'];
								}
						
								$ColorAll = Yii::app() -> db -> createCommand($ColorSql) -> queryAll();
								
								$Color = array();
								foreach ($ColorAll as $ColorKey => $ColorRow) {
									array_push($Color, array('ColorID' => $ColorRow['color_id'], 
															 'ColorName' => $ColorRow['color_name'], 
															 'ColorCode' => '#' . $ColorRow['color_code'],
															 'IS_Apply'=>$ColorRow['IS_Apply']));
								}
								
								
								//---
								array_push($OrdBuDet,array('ID'=>$DeRow['ID'],
														   'ProdID'=>$DeRow['ProdID'],
														   'ParCatID'=>$DeRow['ParCatID'],
														   'ParCatName'=>$DeRow['ParCatName'],
														   'SubCatID'=>$DeRow['SubCatID'],
														   'SubCatName'=>$DeRow['SubCatName'],
														   'ProdName'=>$DeRow['ProdName'],
														   'ProdRate'=>$DeRow['ProdRate'],
														   'ProdImg'=>$ProdImg,
														   'ProdColor'=>$Color,
														   'ProdConf'=>$Conf,
														   'Qnt'=>$DeRow['Qnt'],
														   'Disc'=>$DeRow['Disc'],
														   'Price'=>$DeRow['Price'],
														   'Fees'=>$DeRow['Fees'],
														   'F_Price'=>$DeRow['F_Price'],
														   'AppSource'=>$DeRow['app_source']));
														   
								
								$BuTotal += $DeRow['F_Price'];

							}
						$BuTotal =(string)$BuTotal;
						//---
						$CurrSQL = "SELECT * FROM country WHERE currency_code = '" . $BuRow['BuCurr'] . "' LIMIT 1";
						$CurrData = Yii::app()->db->createCommand($CurrSQL)->queryRow();
						$BuCurr = "";
						if(!empty($CurrData)){
							$BuCurr = $CurrData['currrency_symbol'];
						}
						
						$BuPayType = (string)Orders::GetBuPayType($BuRow['Buid']);
						//----
						array_push($BuArr,array('Buid'=>$BuRow['Buid'],
												'BuName'=>$BuRow['BuName'],
												'BuLogo'=>$RealPath.'business_unit/thumbnails/'.$BuRow['BuLogo'],
												//'BuTotal'=>$BuTotal,
												'BuTotal'=>$BuRow['BuTotal'],
												'BuTax'=>$BuRow['BuTax'],
												'BuDelivery'=>$BuRow['BuDelivery'],
												'BuFees'=>$BuRow['BuFees'],
												'BuShipment'=>$BuRow['BuShipment'],
												'BuVat'=>$BuRow['BuVat'],
												'BuService'=>$BuRow['BuService'],
												'BuCurr'=>$BuCurr,
												'BuCurrCode'=>$BuRow['BuCurr'],
												'PayType'=>$BuPayType,
												'IsReserved'=>$ReservedBu,
												'BuGps'=>array('BuLong'=>$BuRow['BuLong'],'BuLat'=>$BuRow['BuLat']),
												'BuDetails'=>$OrdBuDet));
					}
					
					if(!empty($OrdRow)){
						
						$ResArr = array('order' => array('OrdID'=>$OrdRow['OrdID'],
														 'OrdStat'=>$OrdRow['OrdStat'],
														 'OrdDate'=>$OrdRow['OrdDate'],
														 'OrdBuS'=>$BuArr,
														));
					}else{						
						$ResArr = array('order' => (object)$OrdRow);
					}
			
			//} else {

				//$ResArr = array('error'=>array("Code"=>"200","message"=>"Invalid Permission"));
			//}
			
		}else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
		
    }
	
	public static function actionViewOrderByIDCp($Arr){
			
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		if(isset($Arr) && !empty($Arr)){
			
			$CpID = 0;
			if (isset($Arr['cp_id'])) {
				if ($Arr['cp_id'] > 0) {$CpID = $Arr['cp_id'];}
			};
			$Token = '';
			if (isset($Arr['token'])) {$Token = $Arr['token'];}
			$DevID = '';
			if (isset($Arr['dev_id'])) {$DevID = $Arr['dev_id'];}	
			
			if(Login::ChkCpanelToken($CpID,$Token,$DevID) == True){
				
				if ($Lang != 0 && $Lang != 2){
					
					$OrdSql = " SELECT FROM orders WHERE ";
					
				} else {
					
					$OrdSql = " SELECT FROM orders WHERE ";
				}
				
			} else {
			
				$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			}	
			
		} else {
			
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
	}
	
	public static function actionViewOrders($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);

		$ResArr = array();$Permission = False;$ID = 0;

		if (isset($Arr) && !empty($Arr)) {
				
			$Type = 'cust'; $Str = '';$Sel= '';	
			
			if(isset($Arr['ord_type'])){$Type = $Arr['ord_type'];}
			
			if($Type == 'cust'){
					
				$CustID = 0;
				if (isset($Arr['cust_id'])) {if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}};
				
				$Hash = 0;
				if (isset($Arr['hash'])) {$Hash = $Arr['hash'];}
				
				//$Permission = Login::ChkCustomerHash($CustID,$Hash);
				$Permission = True;
				
				$Str = " WHERE ord_type = 'cust' AND cid = ".$CustID;
			}
			if($Type == 'wait'){
					
				$CpID = 0;
				if (isset($Arr['cp_id'])) {if ($Arr['cp_id'] > 0) {$CpID = $Arr['cp_id'];}};
				
				$Token = '';
				if (isset($Arr['token'])) {$Token = $Arr['token'];}
				
				$DevID = '';
				if (isset($Arr['dev_id'])) {$DevID = $Arr['dev_id'];}
				
				$Permission = Login::ChkCpanelToken($CpID,$Token,$DevID);
				
				$Sel= ',ord_qr_table_id,bu_table_serial';
				
				$Str = " LEFT JOIN orders_qrcodes 
							LEFT JOIN bu_tables ON ord_qr_table_id = bu_table_id
						 ON ord_id = ord_qr_ord_id WHERE ord_type = 'wait' AND cid = ".$CpID;
			}
			
			//if($Permission == TRUE){
						
				$Lang = 0;
	
				if (isset($Arr['lang'])) {
	
					if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
				};
				
				$WhrBu = '';
			
				if(isset($Arr['OneBu'])){
					
					if($Arr['OneBu'] == '1'){
							
						$Buid = 0;	
						if (isset($Arr['Buid'])) {
							if ($Arr['Buid'] > 0) {$Buid = $Arr['Buid'];}
						}
						
						$WhrBu = " AND business_unit.buid = ".$Buid;
					}
				}
				
				$BuAcc = 0 ;
				if (isset($Arr['BuAcc'])) {
		
					if ($Arr['BuAcc'] > 0) {$BuAcc = $Arr['BuAcc'];}
				};
				
				$WhrAcc = ' ';
				
				if($BuAcc > 0){
						
					$WhrAcc = " AND business_unit.buid IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
				}
				
				$OrdsSQL = " SELECT ord_id AS OrdID ,DATE(created)AS OrdDate,
							 (CASE WHEN status = 0 THEN 'Opened' ELSE 'Closed' END)AS OrdStat ".$Sel."
					        FROM orders ".$Str." ORDER BY ord_id DESC ";
				$OrdsData = Yii::app()->db->createCommand($OrdsSQL)->queryAll();
				
				
				$OrdsArr = array();
				foreach ($OrdsData as $Ordskey => $Ordsrow) {
					
					if ($Lang != 0 && $Lang != 2){
						
						/*
						$BuSQL = " SELECT ord_bu_total_bu_id AS Buid ,
											 (CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END) AS BuName,
											 `long` AS BuLong,lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr,rd_bu_total_total AS BuTotal,ord_bu_total_tax AS BuTax,ord_bu_total_delivery AS BuDelivery ,
											   ord_bu_total_fees AS BuFees,ord_bu_total_shipment AS BuShipment,ord_bu_total_VAT AS BuVat,ord_bu_total_service AS BuService
									   FROM orders_details 
									   LEFT JOIN business_unit 
											   LEFT JOIN business_unit_lang ON bu_lang_bu_id = buid AND bu_lang_lang_id = ".$Lang."
									   ON ord_buid = buid
									   WHERE ord_id = ".$Ordsrow['OrdID']." AND close_date IS NOT NULL ".$WhrBu." ".$WhrAcc."
									   GROUP BY ord_buid ORDER BY business_unit.type , orders_details.close_date DESC";*/
						
								   
					   $BuSQL = " SELECT ord_bu_total_bu_id AS Buid ,
										  (CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END) AS BuName,
										  `long` AS BuLong,lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr,
						                  ord_bu_total_total AS BuTotal,ord_bu_total_tax AS BuTax,ord_bu_total_delivery AS BuDelivery ,
						                  ord_bu_total_fees AS BuFees,ord_bu_total_shipment AS BuShipment,ord_bu_total_VAT AS BuVat,ord_bu_total_service AS BuService
								   FROM orders_bu_totals 
								   LEFT JOIN business_unit 
										   LEFT JOIN business_unit_lang ON bu_lang_bu_id = buid AND bu_lang_lang_id = ".$Lang."
								   ON ord_bu_total_bu_id = buid
								   WHERE ord_bu_total_ord_id = ".$Ordsrow['OrdID']." AND ord_bu_total_close_date IS NOT NULL ".$WhrBu." ".$WhrAcc."
								   ORDER BY business_unit.type , ord_bu_total_close_date DESC ";
						
					}else{
						
						/*
						$BuSQL = " SELECT ord_buid AS Buid ,title AS BuName,`long` AS BuLong,
										  lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr
								   FROM orders_details 
								   LEFT JOIN business_unit ON ord_buid = buid
								   WHERE ord_id = ".$Ordsrow['OrdID']." AND close_date IS NOT NULL ".$WhrBu." ".$WhrAcc."
								   GROUP BY ord_buid ORDER BY business_unit.type , orders_details.close_date DESC";*/
						
								   
					   $BuSQL = " SELECT ord_bu_total_bu_id AS Buid ,title AS BuName,`long` AS BuLong,
								  lat AS BuLat,logo AS BuLogo,currency_code AS BuCurr,
				                  ord_bu_total_total AS BuTotal,ord_bu_total_tax AS BuTax,ord_bu_total_delivery AS BuDelivery ,
				                  ord_bu_total_fees AS BuFees,ord_bu_total_shipment AS BuShipment,ord_bu_total_VAT AS BuVat,ord_bu_total_service AS BuService
						   FROM orders_bu_totals 
						   LEFT JOIN business_unit ON ord_bu_total_bu_id = buid
						   WHERE ord_bu_total_ord_id = ".$Ordsrow['OrdID']." AND ord_bu_total_close_date IS NOT NULL ".$WhrBu." ".$WhrAcc."
						   ORDER BY business_unit.type , ord_bu_total_close_date DESC ";
					}
					
					$BuData = Yii::app()->db->createCommand($BuSQL)->queryAll();
					
					$RealArr = Globals::ReturnGlobals();
					$RealPath = $RealArr['ImgSerPath'];
				
					$BuArr = array();
					foreach ($BuData as $BuKey => $BuRow) {
						if ($Lang != 0 && $Lang != 2){
										
								$DeSQL = "SELECT ord_det_id AS ID,
											   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProdName,
											   ord_det.pid AS ProdID,qnt AS Qnt,disc AS Disc ,ord_det.price AS Price,
											   fees AS Fees,final_price AS F_Price,products.rating AS ProdRate,pay_type AS PayType,
											   sub.cat_lang_title AS SubCatName, parent.cat_lang_title AS ParCatName , ParCatID , SubCatID,
											   DATE(close_date)AS close_date
										FROM orders_details AS ord_det
											LEFT JOIN products 
												 LEFT JOIN products_lang ON p_lang_pid = pid AND p_lang_lang_id = ".$Lang."
											ON ord_det.pid = products.pid
										LEFT JOIN CatAndCatSub ON BCatID = products.csid  JOIN catsub_lang  AS sub 
										ON sub.cat_lang_cs_id = BCatID AND sub.cat_lang_lang_id = ".$Lang."
										LEFT JOIN catsub_lang AS parent 
										ON parent.cat_lang_cs_id = CatAndCatSub.ParCatID AND parent.cat_lang_lang_id = ".$Lang."
										WHERE close_date IS NOT NULL AND ord_det.ord_id = ".$Ordsrow['OrdID']." AND ord_buid = ".$BuRow['Buid'];
								
							} else {
									
								$DeSQL = "SELECT ord_det_id AS ID,products.title AS ProdName,
											   ord_det.pid AS ProdID,qnt AS Qnt,disc AS Disc ,ord_det.price AS Price,
											   fees AS Fees,final_price AS F_Price,products.rating AS ProdRate,pay_type AS PayType,
											   ParCatID , ParCatName, SubCatID , SubCatName,DATE(close_date)AS close_date
										FROM orders_details AS ord_det
											LEFT JOIN products ON ord_det.pid = products.pid
										LEFT JOIN CatAndCatSub ON products.csid = BCatID
										WHERE close_date IS NOT NULL AND ord_det.ord_id = ".$Ordsrow['OrdID']." AND ord_buid = ".$BuRow['Buid'];
							}
							
							$DeData = Yii::app()->db->createCommand($DeSQL)->queryAll();
							
							$OrdBuDet = array();$BuTotal = 0;$PayType = "0";$CloseDate = '';
							foreach ($DeData as $DeKey => $DeRow) {
										
								$OrdRow = $DeRow;	
								$PayType = $DeRow['PayType'];
								$CloseDate = $DeRow['close_date'];
								//----Imgs
								$ImgSQL = "SELECT * FROM products_imgs WHERE pid = " . $DeRow['ProdID'] . " LIMIT 1";
								$ImgData = Yii::app()->db->createCommand($ImgSQL)->queryRow();
								$ProdImg = "";
								if(!empty($ImgData)){
									$ProdImg = $RealPath.'products/thumbnails/'.$ImgData['pimg_url'];
								}
								//----Conf
								if ($Lang != 0 && $Lang != 2) {
									$ConfSql = "SELECT cfg_id ,pdconfv_id, pdconfv_value, pdconfv_chkrad,
									   	 		(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name , 
												(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid	
										 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "	
										 WHERE pdconfv_pid = ".$DeRow['ProdID']. " AND parent_id IS NULL ";
								} else {
									$ConfSql = "SELECT cfg_id , pdconfv_id, pdconfv_value, pdconfv_chkrad, name , 
											(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
											 FROM pd_conf_v
											 JOIN pd_config ON cfg_id = pdconfv_confid
											 WHERE pdconfv_pid =".$DeRow['ProdID']." AND parent_id IS NULL ";
						
								}
								$ConfAll = Yii::app()->db->createCommand($ConfSql)->queryAll();
								$Conf = array();
								foreach ($ConfAll as $ConfKey => $ConfRow) {
									$SubConfArr = array();
									if ($Lang != 0 && $Lang != 2) {
										$SConfSql = "SELECT pdconfv_id, pdconfv_value, 
														(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name,
														(CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
													 FROM pd_conf_v
													 LEFT JOIN pd_config ON cfg_id = pdconfv_confid
													 LEFT JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "
													 LEFT JOIN orders_detail_conf ON pdconfv_id = ord_de_conf_co_id AND ord_de_conf_type = 'conf' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
													 WHERE pdconfv_pid = ".$DeRow['ProdID']." AND parent_id = " . $ConfRow['cfg_id'];
										
									} else {
										$SConfSql = "SELECT pdconfv_id, pdconfv_value, name,
													 (CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
													 FROM pd_conf_v
													 LEFT JOIN pd_config ON cfg_id = pdconfv_confid
													 LEFT JOIN orders_detail_conf ON pdconfv_id = ord_de_conf_co_id AND ord_de_conf_type = 'conf' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
													 WHERE pdconfv_pid = ".$DeRow['ProdID']." AND parent_id = ".$ConfRow['cfg_id'];
										
									}
									$SConfAll = Yii::app() -> db -> createCommand($SConfSql) -> queryAll();
									foreach ($SConfAll as $SConfKey => $SConfRow) {
										array_push($SubConfArr, array('subId'=>$SConfRow['pdconfv_id'],
																	  'SubConf' => $SConfRow['name'], 
																	  'Val' => $SConfRow['pdconfv_value'],
																	  'IS_Apply' => $SConfRow['IS_Apply']));
							
									}
									array_push($Conf, array('confId'=>$ConfRow['pdconfv_id'],
														    'Conf' => $ConfRow['name'], 
														    'Check' => $ConfRow['pdconfv_chkrad'], 
														    'SubConfig' => $SubConfArr));
							
								}
								//---- Colors
								if ($Lang != 0 && $Lang != 2) {
									$ColorSql = " SELECT color_id,color_code,
												  	(CASE WHEN color_lang_name IS NULL THEN color_name ELSE color_lang_name END)AS color_name ,
											   	 	(CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
												  FROM prod_colors
											   	  LEFT JOIN prod_colors_lang ON color_lang_color_id = color_id AND color_lang_lang_id = " . $Lang . "
											   	  LEFT JOIN orders_detail_conf ON color_id = ord_de_conf_co_id AND ord_de_conf_type = 'color' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
											   	  WHERE color_pid = " .$DeRow['ProdID'];
								
								} else {
									$ColorSql = "SELECT color_id, color_name ,color_code ,
												       (CASE WHEN ord_de_conf_id IS NULL THEN 'FALSE' ELSE 'TRUE' END)AS IS_Apply
												 FROM prod_colors 
												 LEFT JOIN orders_detail_conf ON color_id = ord_de_conf_co_id AND ord_de_conf_type = 'color' AND ord_de_conf_de_id = " . $DeRow['ID'] . "
												 WHERE color_pid = " .$DeRow['ProdID'];
								}
						
								$ColorAll = Yii::app() -> db -> createCommand($ColorSql) -> queryAll();
								
								$Color = array();
								foreach ($ColorAll as $ColorKey => $ColorRow) {
									array_push($Color, array('ColorID' => $ColorRow['color_id'], 
															 'ColorName' => $ColorRow['color_name'], 
															 'ColorCode' => '#' . $ColorRow['color_code'],
															 'IS_Apply'=>$ColorRow['IS_Apply']));
								}
								
								
								//---
								array_push($OrdBuDet,array('ID'=>$DeRow['ID'],
														   'ProdID'=>$DeRow['ProdID'],
														   'ParCatID'=>$DeRow['ParCatID'],
														   'ParCatName'=>$DeRow['ParCatName'],
														   'SubCatID'=>$DeRow['SubCatID'],
														   'SubCatName'=>$DeRow['SubCatName'],
														   'ProdName'=>$DeRow['ProdName'],
														   'ProdRate'=>$DeRow['ProdRate'],
														   'ProdImg'=>$ProdImg,
														   'ProdColor'=>$Color,
														   'ProdConf'=>$Conf,
														   'Qnt'=>$DeRow['Qnt'],
														   'Disc'=>$DeRow['Disc'],
														   'Price'=>$DeRow['Price'],
														   'Fees'=>$DeRow['Fees'],
														   'F_Price'=>$DeRow['F_Price']));
														   
								
								$BuTotal += $DeRow['F_Price'];

							}
							$BuTotal =(string)$BuTotal;
							//---
							$CurrSQL = "SELECT * FROM country WHERE currency_code = '" . $BuRow['BuCurr'] . "' LIMIT 1";
							$CurrData = Yii::app()->db->createCommand($CurrSQL)->queryRow();
							$BuCurr = "";
							if(!empty($CurrData)){
								$BuCurr = $CurrData['currrency_symbol'];
							}
							//----
							if(!empty($OrdBuDet)){
								array_push($BuArr,array('Buid'=>$BuRow['Buid'],
														'BuName'=>$BuRow['BuName'],
														'BuLogo'=>$RealPath.'business_unit/thumbnails/'.$BuRow['BuLogo'],
														'BuTotal'=>$BuTotal,
														'BuCurr'=>$BuCurr,
														'PayType'=>$PayType,
														'CloseDate'=>$CloseDate,
														'BuGps'=>array('BuLong'=>$BuRow['BuLong'],'BuLat'=>$BuRow['BuLat']),
														'BuDetails'=>$OrdBuDet));
							}							
						}
				
					if(!empty($BuArr)){
						
						if($Type == 'cust'){
							
							array_push($OrdsArr,array('OrdID'=>$Ordsrow['OrdID'],
													  'OrdStat'=>$Ordsrow['OrdStat'],
													  'OrdDate'=>$Ordsrow['OrdDate'],
													  'OrdBuS'=>$BuArr,
													 ));
						}	
						if($Type == 'wait'){
							
							array_push($OrdsArr,array('OrdID'=>$Ordsrow['OrdID'],
													  'OrdTable'=>$Ordsrow['bu_table_serial'],
													  'OrdStat'=>$Ordsrow['OrdStat'],
													  'OrdDate'=>$Ordsrow['OrdDate'],
													  'OrdBuS'=>$BuArr,
													 ));
						}	
						
					}
				}
				
				$ResArr = array('orders'=>$OrdsArr);
				
			//} else {

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}	
			
		} else {

			$ResArr = array('error' => array("code" => "206", "message" => "Invalid"));
		}

		return $ResArr;
		
	}
		
	/*
	public static function actionPayOrder($Arr) {
			
			$ShippingAddr = Orders::CHKAddr($Arr['ShipAddID']);
			$BillingAddr  = Orders::CHKAddr($Arr['BillingAddID']);
			
			if(is_array($BillingAddr) && is_array($ShippingAddr)){
						
				//$MyCurr = Currency::ConvertCurrency($arr['Currency'] , 'USD', $arr['Cr_val'] + $arr['kinjo_comm']);
				Twocheckout::privateKey(PRIVATE_KEY);
				Twocheckout::sellerId(SELLER_ID);
				Twocheckout::verifySSL(false);
				Twocheckout::sandbox(true);
				Twocheckout::format('json');
				//$PayTotal = round($MyCurr['ValTo'] , 2) ;
					
				$PayTotal = $Arr['Cr_val'] + $Arr['kinjo_comm'];
				$trans_val = 2.5;
				
		
				try {
						// print_r($arr);
						// return;				
					$charge = Twocheckout_Charge::auth(array("merchantOrderId" => "1", 
															 "token" => $arr['Token'], 
															 "currency" => 'USD', 
															 "total" => $PayTotal, 
															 "billingAddr" => array("name"        => $BillingAddr['fname'].' '.$BillingAddr['lname'], 
																					"addrLine1"   => '123 Test St', 
																					"city"        => $BillingAddr['cust_add_city'], 
																					"state"       => $BillingAddr['cust_add_region'], 
																					"zipCode"     => $BillingAddr['cust_add_postalCode'], 
																					"country"     => $BillingAddr['name'], 
																					"email"       => $BillingAddr['email'], 
																					"phoneNumber" => $BillingAddr['phone']
																					),														 
															 //"shippingAddr" =>array("name"=>$ShippingAddr['fname'].' '.$ShippingAddr['lname'], 
																					 //"addrLine1"=>'123 shipping St',
																					 //"city"=>$ShippingAddr['cust_add_city'] ,
																					 //"state"=>$ShippingAddr['cust_add_region'],
																					 //"zipCode"=>$ShippingAddr['cust_add_postalCode'],
																				   // "country"=>$ShippingAddr['name']
																				   //)
					
					));
							
						$chargeJson = json_decode($charge);
						
						
						
						if ($chargeJson->response->responseCode == 'APPROVED') {
							
							$sql = "INSERT INTO kinjo_payments (kp_buid , kp_orderId , kp_chOrderId , kp_custId , kp_pay_total , kp_kinjo_val , kp_trans_val)
									 VALUES(3," . $chargeJson->response->merchantOrderId . " , " . $chargeJson->response->orderNumber . " , ".$Arr['cust_id']." , " . $chargeJson->response->total . " , " . $Arr['kinjo_comm'] . " , " . $trans_val . ")";
							Yii::app() -> db -> createCommand($sql) -> execute();
		
							return TRUE;
							
						} else {
							
							return FALSE;
						}
						
					} catch (Twocheckout_Error $e) {
						
						return FALSE;
					}
					
				} else {
					return FALSE;
				}
	
		}*/
	
	
	public static function actionAuthorizeNetPay($Arr)
	{
		$ShippingAddr = Orders::CHKAddr($Arr['ShipAddID']);
		$BillingAddr  = Orders::CHKAddr($Arr['BillingAddID']);
		
		if(is_array($BillingAddr) && is_array($ShippingAddr)){
				
			$Res = Orders::GetOrderTotal($Arr['id'],$Arr['bu_id']);	
			//$PayTotal = $Res['ord_bu_total_total'];
			// $PayTotal = 100;
			$PayTotal = $Arr['shipping_BuVal'] + $Arr['kinjo_comm'];
			$trans_val = 3; // 3 dollars according to each payment method .
			
			$PayArr = array();
			$PayArr['CustFname']= $BillingAddr['fname'];
			$PayArr['CustLname']= $BillingAddr['lname'];
			$PayArr['CustAddress']= $BillingAddr['cust_add_street'].' '.$BillingAddr['cust_add_city'];
			$PayArr['CustCity']= $BillingAddr['cust_add_city'];
			$PayArr['CustState']= $Arr['State'];
			$PayArr['CustZip']= $BillingAddr['cust_add_postalCode'];
			$PayArr['CustPhone']= $BillingAddr['phone'];
			$PayArr['CustEmail']= $BillingAddr['email'];
			
			$PayArr['ShipAddress']= $ShippingAddr['cust_add_street'].' '.$ShippingAddr['cust_add_city'];
			$PayArr['ShipCity']= $ShippingAddr['cust_add_city'];
			$PayArr['ShipState']= $Arr['State'];
			$PayArr['ShipZip']= $ShippingAddr['cust_add_postalCode'];
			
			$PayArr['Amount']= $PayTotal;
			$PayArr['CardNum']= $Arr['crd_num'];
			$PayArr['ExpDate']= $Arr['exp_month'].'/'.$Arr['exp_year'];
			$PayArr['CardCode']= $Arr['cvv'];
			$PayArr['Desc']= '';
			
			$ResAuth = AuthorizeNetHelp::AuthorizeNetFunc($PayArr);
		
			
		
			if(!empty($ResAuth)){
					
				if($ResAuth['Result'] == True){
					
			// echo '<pre/>';
			// print_r($ResAuth['TransactionID']);
			// return;
			
					$InsSql = " INSERT INTO kinjo_payments (kp_buid,kp_orderId,kp_chOrderId,kp_custId,kp_pay_total,kp_kinjo_val,kp_trans_val) 
					            VALUES(".$Arr['bu_id'].",".$Arr['id'].",".$ResAuth['TransactionID'].",".$Arr['cust_id'].",".$PayTotal.",".$Arr['kinjo_comm'].",".$trans_val.")";
					
					Yii::app() -> db -> createCommand($InsSql)->execute();
					
					return TRUE;
				
				} else {
					
					return FALSE;
				}	
				
			} else {
				
				return FALSE;
			}
			
			
		} else {
				
			return FALSE;
		}
	}
	
	public static function actionCloseOnSite($Arr)
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
			
		} else {
			
		}
		
		$BuID = isset(Yii::app()->session['User']['UserBuid'])?(Yii::app()->session['User']['UserBuid'] > 0 ? Yii::app()->session['User']['UserBuid']: 0 ):0;
	
		$UpSql = " UPDATE orders_details SET close_date = now() 
				   WHERE pay_type = 1 AND ord_id = ".$_POST['OrdID']." AND ord_buid = ".$BuID;
		$ResUp = Yii::app()->db->createCommand($UpSql)->execute();
		
		Orders::CloseOrder($_POST['OrdID']);
	}
	
	//------------------------ Products ------------------------------

	public static function actionGetProdDetailsByProdID($Arr) {
			// header('Content-Type: application/json');
			$Arr = CI_Security::ChkPost($Arr);
			
			$ResArr = array();
			
			$CustID = 0;
			if (isset($Arr['CustID'])) {
				
				if ($Arr['CustID'] > 0) {$CustID = $Arr['CustID'];}
			};
			
			$Hash = 0;
			if (isset($Arr['Hash'])) {
	
				if ($Arr['Hash'] != '') {$Hash = $Arr['Hash'];}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				$ProID = 0;
				if (isset($Arr['ProID'])) {
					if ($Arr['ProID'] > 0) {$ProID = $Arr['ProID']; }
				};
				
				$lat = 40.55886796987923;//0;
				if (isset($Arr['lat'])) {
					if ($Arr['lat'] > 0) {$lat = $Arr['lat'];}
				};
					
				$long = 34.97644203125003;//0;
				if (isset($Arr['long'])) {
					if ($Arr['long'] != '') {$long = $Arr['long'];}
				};
					
				$Lang = 0;
				if (isset($Arr['lang'])) {
					if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];
					}
				};
		
				//if($lat != 0 || $long != 0){
					
					if ($ProID) {
			
						if ($Lang != 0 && $Lang != 2) {
							$SQL = " SELECT products.pid AS ProID,
									   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProTitle,
									   (CASE WHEN p_lang_discription IS NULL THEN products.discription ELSE p_lang_discription END) AS ProDesc,
									   (CASE WHEN p_lang_price IS NULL THEN products.price ELSE p_lang_price END) AS ProPrice,
									   products.rating AS ProRate,business_unit.buid AS BUID,
									   (CASE WHEN bu_lang_title IS NULL THEN business_unit.title ELSE bu_lang_title END) AS BUTitle,
									   business_unit.long AS BULong,business_unit.lat AS BULat,business_unit.logo AS BULogo,
									   (((acos(sin((".$lat."*pi()/180)) * 
								            sin((business_unit.lat*pi()/180)) + cos((".$lat."*pi()/180)) * 
								            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
								            pi()/180))))*180/pi())*60*1.1515
								        ) as BUDis,
								      Sub.cat_lang_title AS SubCatName, Parent.cat_lang_title AS ParCatName , ParCatID , SubCatID,
								      IFNULL((SELECT value FROM product_rating WHERE product_rating.pid = products.pid AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
							          IFNULL((SELECT COUNT(DISTINCT cid) FROM product_rating WHERE product_rating.pid = products.pid LIMIT 0,1),0)AS CountRate,
							          IFNULL((SELECT wl_id FROM wishlist WHERE wl_pid = products.pid AND wl_cid = ".$CustID." LIMIT 0,1) ,0) AS wl_id,
							          IFNULL((SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 0,1),'')AS CurrSymbol
								FROM products 
									LEFT JOIN business_unit 
										LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = " . $Lang . "
								ON business_unit.buid = products.buid
								LEFT JOIN products_lang ON p_lang_pid = products.pid AND p_lang_lang_id = " . $Lang . "
								LEFT JOIN CatAndCatSub ON BCatID = products.csid  
								LEFT JOIN catsub_lang AS Sub ON Sub.cat_lang_cs_id = BCatID AND Sub.cat_lang_lang_id = " . $Lang . "
								LEFT JOIN catsub_lang AS Parent ON Parent.cat_lang_cs_id = CatAndCatSub.ParCatID AND Parent.cat_lang_lang_id = " . $Lang . "
								
								WHERE pid = " . $ProID ." AND business_unit.active = 0 ";
			
						} else {
			
							$SQL = " SELECT products.pid AS ProID,products.title AS ProTitle,products.discription AS ProDesc,products.price AS ProPrice,
										   products.rating AS ProRate,
										   business_unit.buid AS BUID,business_unit.title AS BUTitle,business_unit.long AS BULong,business_unit.lat AS BULat,
										   business_unit.logo AS BULogo,
										   (((acos(sin((".$lat."*pi()/180)) * 
									            sin((business_unit.lat*pi()/180)) + cos((".$lat."*pi()/180)) * 
									            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
									            pi()/180))))*180/pi())*60*1.1515
									        ) as BUDis , ParCatID , ParCatName , SubCatID , SubCatName,
									        IFNULL((SELECT value FROM product_rating WHERE product_rating.pid = products.pid AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
							         		IFNULL((SELECT COUNT(DISTINCT cid) FROM product_rating WHERE product_rating.pid = products.pid LIMIT 0,1),0)AS CountRate,
							         		IFNULL((SELECT wl_id FROM wishlist WHERE wl_pid = products.pid AND wl_cid = ".$CustID." LIMIT 0,1) ,0) AS wl_id,
							         		 IFNULL((SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 0,1),'')AS CurrSymbol
									FROM products 
									LEFT JOIN business_unit ON business_unit.buid = products.buid
									LEFT JOIN CatAndCatSub ON BCatID = products.csid
									WHERE pid = " . $ProID ." AND business_unit.active = 0 ";
						}
						$Data = Yii::app() -> db -> createCommand($SQL) -> queryRow();
			
						if (!empty($Data)) {
								
							$Data['wl_id'] = $Data['wl_id'] > 0 ? 'True' : 'False';
							
							$RealAdrr = Globals::ReturnGlobals();
							
							//--------Get Product Imgs
							$ImgSql = " SELECT pimgid, pimg_url
								   	 	    FROM products_imgs 
								   	 	    WHERE products_imgs.pid = " . $Data['ProID'];
							$ImgAll = Yii::app() -> db -> createCommand($ImgSql) -> queryAll();
							$Img = array();
			
							if (count($ImgAll) > 0) {
								
								$ImgPath = $RealAdrr['ImgSerPath'] . 'products/';
								foreach ($ImgAll as $key => $row) {
									array_push($Img,array('Img'=>$ImgPath . $row['pimg_url'] , 'pimg_url'=>$row['pimg_url']));
								}
							}
			
							//--------Get Product Configration
							if ($Lang != 0 && $Lang != 2) {
								$ConfSql = "SELECT cfg_id ,pdconfv_id, pdconfv_value, pdconfv_chkrad,
								   	 		(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name , 
											(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
									 FROM pd_conf_v
									 JOIN pd_config ON cfg_id = pdconfv_confid	
									 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "	
									 WHERE pdconfv_pid = ".$ProID. " AND parent_id IS NULL ";
							} else {
								$ConfSql = "SELECT cfg_id , pdconfv_id, pdconfv_value, pdconfv_chkrad, name , 
										(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid
										 WHERE pdconfv_pid =".$ProID." AND parent_id IS NULL ";
					
							}
							$ConfAll = Yii::app()->db->createCommand($ConfSql)->queryAll();
							$Conf = array();
							foreach ($ConfAll as $ConfKey => $ConfRow) {
								$SubConfArr = array();
								if ($Lang != 0 && $Lang != 2) {
									$SConfSql = "SELECT pdconfv_id, pdconfv_value, 
													(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name 
											 FROM pd_conf_v
											 JOIN pd_config ON cfg_id = pdconfv_confid
											 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "
											 WHERE pdconfv_pid = ".$ProID." AND parent_id = " . $ConfRow['cfg_id'];
									
								} else {
									$SConfSql = "SELECT pdconfv_id, pdconfv_value, name
											 FROM pd_conf_v
											 JOIN pd_config ON cfg_id = pdconfv_confid
											 WHERE pdconfv_pid = ".$ProID." AND parent_id = ".$ConfRow['cfg_id'];
									
								}
								$SConfAll = Yii::app() -> db -> createCommand($SConfSql) -> queryAll();
								foreach ($SConfAll as $SConfKey => $SConfRow) {
									array_push($SubConfArr, array('subId'=>$SConfRow['pdconfv_id'],'SubConf' => $SConfRow['name'], 'Val' => $SConfRow['pdconfv_value']));
						
								}
								array_push($Conf, array('confId'=>$ConfRow['pdconfv_id'] , 'Conf' => $ConfRow['name'], 'Check' => $ConfRow['pdconfv_chkrad'], 'SubConfig' => $SubConfArr));
						
							}
							//--------Get Product Colors
							if ($Lang != 0 && $Lang != 2) {
								$ColorSql = " SELECT color_id,color_code,
										  (CASE WHEN color_lang_name IS NULL THEN color_name ELSE color_lang_name END)AS color_name 
									   	 FROM prod_colors
									   	 LEFT JOIN prod_colors_lang ON color_lang_color_id = color_id AND color_lang_lang_id = " . $Lang . "
									   	 WHERE color_pid = " .$ProID;
							
							} else {
								$ColorSql = "SELECT color_id, color_name ,color_code FROM prod_colors WHERE color_pid = " .$ProID;
							}
					
							$ColorAll = Yii::app() -> db -> createCommand($ColorSql) -> queryAll();
							
							$Color = array();
							foreach ($ColorAll as $ColorKey => $ColorRow) {
								array_push($Color, array('ColorID' => $ColorRow['color_id'], 'ColorName' => $ColorRow['color_name'], 'ColorCode' => '#' . $ColorRow['color_code']));
							}
							
							$Prod = array('ProID'     => $Data['ProID'], 
										  'ProTitle'  => $Data['ProTitle'], 
										  'ProDesc'   => $Data['ProDesc'], 
										  'ProPrice'  => $Data['ProPrice'], 
										  'ProRate'   => $Data['ProRate'], 
										  'CustRate'  => $Data['CustRate'], 
										  'CountRate' => $Data['CountRate'], 
										  'ParCatID'  => $Data['ParCatID'], 
										  'ParCatName'=> $Data['ParCatName'],
										  'SubCatID'  => $Data['SubCatID'],
										  'SubCatName'=> $Data['SubCatName'],
										  'wishList'  => $Data['wl_id'] ,
										  'ProImgs'   => $Img,
										  'ProConfs'  => $Conf,
										  'ProColors' => $Color
										  );
			 
							$IsReservedBu = Orders::IsReservedBu($Data['BUID'],$CustID);
							
							$BU = array('BUID' => $Data['BUID'], 
										'BUTitle' => $Data['BUTitle'], 
										'BULogo' => $RealAdrr['ImgSerPath'] . 'business_unit/thumbnails/' . $Data['BULogo'],
										'IsReserved'=>$IsReservedBu,
										'BUCurrS'=>$Data['CurrSymbol'],
										'BUDis' => $Data['BUDis'],
										'BUGps' => array('BULat'=>$Data['BULat'],'BULong'=>$Data['BULong']));
			
							$Of = array();
			
							//---------------------GET Active Offer
			
							if ($Lang != 0 && $Lang != 2) {
			
								$OfSql = "SELECT offers.ofid AS OfID, 
											   (CASE WHEN offer_lang_title IS NULL THEN offers.title ELSE offer_lang_title END) AS OfTitle,
											   (CASE WHEN offer_lang_text IS NULL THEN offers.text ELSE offer_lang_text END) AS OfText,
											   (CASE WHEN offer_lang_discount IS NULL THEN offers.discount ELSE offer_lang_discount END) AS OfDisc,
											   offers.active AS OfActive,offers.from AS OfFrom,offers.to AS OfTo
										FROM offers
										LEFT JOIN offers_lang ON offer_lang_offer_id = offers.ofid AND offer_lang_lang_id = " . $Lang . "
										WHERE offers.pid = " . $ProID . "
										AND offers.active = 1 AND NOW() BETWEEN offers.from AND offers.to LIMIT 0,1 ";
			
							} else {
			
								$OfSql = "SELECT offers.ofid AS OfID, offers.title AS OfTitle,offers.text AS OfText,offers.discount AS OfDisc,
											   offers.active AS OfActive,offers.from AS OfFrom,offers.to AS OfTo
										FROM offers
										WHERE offers.pid = " . $ProID . "
										AND offers.active = 1 AND NOW() BETWEEN offers.from AND offers.to LIMIT 0,1 ";
							}
			
							$OFData = Yii::app() -> db -> createCommand($OfSql) -> queryRow();
							$Of = array();
							if (!empty($OFData)) {
			
								$Of = array('OfID' => $OFData['OfID'], 
											'OfTitle' => $OFData['OfTitle'], 
											'OfText' => $OFData['OfText'], 
											'OfDisc' => $OFData['OfDisc'], 
											'OfActive' => $OFData['OfActive'], 
											'OfFrom' => $OFData['OfFrom'], 
											'OfTo' => $OFData['OfTo']);
			
							} else {
								
								$Of =(object)$Of;
							}
			
							$ResArr = array("Result" => array("Product" => $Prod, "Store" => $BU, "Offer" => $Of));
			
						} else {
							
							$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
						}
			
					}else{
						
						$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
					}
			//} else {	
			//	$ResArr = array('error'=>array("Code"=>"202","message"=>"UnKnown Location"));
			//}
				
		//}else{
		//	$ResArr = array('error'=>array("Code"=>"203","message"=>"Invalid Permission"));
		//}
			
		return $ResArr;
	}

	public static function actionGetProdDetailsByOfferID($Arr) {
		//header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		$CustID = 0;

		if (isset($Arr['CustID'])) {

			if ($Arr['CustID'] > 0) {$CustID = $Arr['CustID'];
			}
		};
		$Hash = 0;

		if (isset($Arr['Hash'])) {

			if ($Arr['Hash'] != '') {$Hash = $Arr['Hash'];
			}
		};
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
			$OfID = 0;
	
			if (isset($Arr['OfID'])) {
	
				if ($Arr['OfID'] > 0) {$OfID = $Arr['OfID'];
				}
			};
			$lat = 0;

			if (isset($Arr['lat'])) {
	
				if ($Arr['lat'] > 0) {$lat = $Arr['lat'];}
			};
			$long = 0;
	
			if (isset($Arr['long'])) {
	
				if ($Arr['long'] != '') {$long = $Arr['long'];}
			};
			$Lang = 0;
	
			if (isset($Arr['lang'])) {
	
				if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];
				}
			};
			
			//if($lat != 0 || $long != 0){
				
				if ($OfID) {
		
					if ($Lang != 0 && $Lang != 2) {
		
						$SQL = " SELECT offers.ofid AS OfID, 
									   (CASE WHEN offer_lang_title IS NULL THEN offers.title ELSE offer_lang_title END) AS OfTitle,
									   (CASE WHEN offer_lang_text IS NULL THEN offers.text ELSE offer_lang_text END) AS OfText,
									   (CASE WHEN offer_lang_discount IS NULL THEN offers.discount ELSE offer_lang_discount END) AS OfDisc,
									   offers.active AS OfActive,offers.from AS OfFrom,offers.to AS OfTo,
									   products.pid AS ProID,
									   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProTitle,
									   (CASE WHEN p_lang_discription IS NULL THEN products.discription ELSE p_lang_discription END) AS ProDesc,
									   (CASE WHEN p_lang_price IS NULL THEN products.price ELSE p_lang_price END) AS ProPrice,
									   products.rating AS ProRate,business_unit.buid AS BUID,
									   (CASE WHEN bu_lang_title IS NULL THEN business_unit.title ELSE bu_lang_title END) AS BUTitle,
									   business_unit.long AS BULong,business_unit.lat AS BULat,business_unit.logo AS BULogo,
								      (((acos(sin((".$lat."*pi()/180)) * 
									            sin((business_unit.lat*pi()/180)) + cos((".$lat."*pi()/180)) * 
									            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
									            pi()/180))))*180/pi())*60*1.1515
									        ) as BUDis,ParCatID , ParCatName , SubCatID , SubCatName,
								      IFNULL((SELECT value FROM product_rating WHERE product_rating.pid = products.pid AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
							          IFNULL((SELECT COUNT(DISTINCT cid) FROM product_rating WHERE product_rating.pid = products.pid LIMIT 0,1),0)AS CountRate,
							          IFNULL((SELECT wl_id FROM wishlist WHERE wl_pid = products.pid AND wl_cid = ".$CustID." LIMIT 0,1) ,0) AS wl_id,
							           IFNULL((SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 0,1),'')AS CurrSymbol
								FROM offers
								LEFT JOIN offers_lang ON offer_lang_offer_id = offers.ofid AND offer_lang_lang_id = " . $Lang . "
								LEFT JOIN products 
									LEFT JOIN business_unit 
										LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = " . $Lang . "
									ON business_unit.buid = products.buid
								ON products.pid = offers.pid
								LEFT JOIN products_lang ON p_lang_pid = products.pid AND p_lang_lang_id = " . $Lang . "
								LEFT JOIN CatAndCatSub ON BCatID = products.csid  
								LEFT JOIN catsub_lang AS Sub ON Sub.cat_lang_cs_id = BCatID AND Sub.cat_lang_lang_id = " . $Lang . "
								LEFT JOIN catsub_lang AS Parent ON Parent.cat_lang_cs_id = CatAndCatSub.ParCatID AND Parent.cat_lang_lang_id = " . $Lang . "
								WHERE ofid = " . $OfID ." AND business_unit.active = 0 ";
		
					} else {
		
						$SQL = " SELECT offers.ofid AS OfID, offers.title AS OfTitle,offers.text AS OfText,offers.discount AS OfDisc,
									   offers.active AS OfActive,offers.from AS OfFrom,offers.to AS OfTo,
									   products.pid AS ProID,products.title AS ProTitle,products.discription AS ProDesc,products.price AS ProPrice,
									   products.rating AS ProRate,
									   business_unit.buid AS BUID,business_unit.title AS BUTitle,business_unit.long AS BULong,business_unit.lat AS BULat,
									   business_unit.logo AS BULogo,
									   (((acos(sin((".$lat."*pi()/180)) * 
								            sin((business_unit.lat*pi()/180)) + cos((".$lat."*pi()/180)) * 
								            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
								            pi()/180))))*180/pi())*60*1.1515
								        ) as BUDis,ParCatID , ParCatName , SubCatID , SubCatName,
								      IFNULL((SELECT value FROM product_rating WHERE product_rating.pid = products.pid AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
							          IFNULL((SELECT COUNT(DISTINCT cid) FROM product_rating WHERE product_rating.pid = products.pid LIMIT 0,1),0)AS CountRate,
							          IFNULL((SELECT wl_id FROM wishlist WHERE wl_pid = products.pid AND wl_cid = ".$CustID." LIMIT 0,1) ,0) AS wl_id,
							           IFNULL((SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 0,1),'')AS CurrSymbol
								FROM offers
								LEFT JOIN products 
									LEFT JOIN business_unit ON business_unit.buid = products.buid
								ON products.pid = offers.pid
								LEFT JOIN CatAndCatSub ON BCatID = products.csid
								WHERE ofid = " . $OfID ." AND business_unit.active = 0 ";
					}
		
					$Data = Yii::app() -> db -> createCommand($SQL) -> queryRow();
		
					if (!empty($Data)) {
							
						$Data['wl_id'] = $Data['wl_id'] > 0 ? 'True' : 'False';
						
						$RealAdrr = Globals::ReturnGlobals();
						
						//--------Get Product Imgs
						$ImgSql = " SELECT pimgid, pimg_url
							   	 	    FROM products_imgs 
							   	 	    WHERE products_imgs.pid = " . $Data['ProID'];
						$ImgAll = Yii::app() -> db -> createCommand($ImgSql) -> queryAll();
						$Img = array();
		
						if (count($ImgAll) > 0) {
							
							$ImgPath = $RealAdrr['ImgSerPath'] . 'products/';
							foreach ($ImgAll as $key => $row) {
								array_push($Img,array('Img'=>$ImgPath . $row['pimg_url']));
							}
						}
						//--------Get Product Configration
						if ($Lang != 0 && $Lang != 2) {
							$ConfSql = "SELECT cfg_id ,pdconfv_id, pdconfv_value, pdconfv_chkrad,
							   	 		(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name , 
										(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
								 FROM pd_conf_v
								 JOIN pd_config ON cfg_id = pdconfv_confid	
								 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "	
								 WHERE pdconfv_pid = ".$Data['ProID']. " AND parent_id IS NULL ";
						} else {
							$ConfSql = "SELECT cfg_id , pdconfv_id, pdconfv_value, pdconfv_chkrad, name , 
									(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
									 FROM pd_conf_v
									 JOIN pd_config ON cfg_id = pdconfv_confid
									 WHERE pdconfv_pid =".$Data['ProID']." AND parent_id IS NULL ";
				
						}
						$ConfAll = Yii::app()->db->createCommand($ConfSql)->queryAll();
						$Conf = array();
						foreach ($ConfAll as $ConfKey => $ConfRow) {
							$SubConfArr = array();
							if ($Lang != 0 && $Lang != 2) {
								$SConfSql = "SELECT pdconfv_id, pdconfv_value, 
												(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name 
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid
										 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "
										 WHERE pdconfv_pid = ".$Data['ProID']." AND parent_id = " . $ConfRow['cfg_id'];
								
							} else {
								$SConfSql = "SELECT pdconfv_id, pdconfv_value, name
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid
										 WHERE pdconfv_pid = ".$Data['ProID']." AND parent_id = ".$ConfRow['cfg_id'];
								
							}
							$SConfAll = Yii::app() -> db -> createCommand($SConfSql) -> queryAll();
							foreach ($SConfAll as $SConfKey => $SConfRow) {
								array_push($SubConfArr, array('subId' => $SConfRow['pdconfv_id'],'SubConf' => $SConfRow['name'], 'Val' => $SConfRow['pdconfv_value']));
					
							}
							array_push($Conf, array('confId' => $SConfRow['pdconfv_id'],'Conf' => $ConfRow['name'], 'Check' => $ConfRow['pdconfv_chkrad'], 'SubConfig' => $SubConfArr));
					
						}
						//--------Get Product Colors
						if ($Lang != 0 && $Lang != 2) {
							$ColorSql = " SELECT color_id,color_code,
									  (CASE WHEN color_lang_name IS NULL THEN color_name ELSE color_lang_name END)AS color_name 
								   	 FROM prod_colors
								   	 LEFT JOIN prod_colors_lang ON color_lang_color_id = color_id AND color_lang_lang_id = " . $Lang . "
								   	 WHERE color_pid = " .$Data['ProID'];
						
						} else {
							$ColorSql = "SELECT color_id, color_name ,color_code FROM prod_colors WHERE color_pid = " .$Data['ProID'];
						}
				
						$ColorAll = Yii::app() -> db -> createCommand($ColorSql) -> queryAll();
						
						$Color = array();
						foreach ($ColorAll as $ColorKey => $ColorRow) {
							array_push($Color, array('ColorID' => $ColorRow['color_id'], 'ColorName' => $ColorRow['color_name'], 'ColorCode' => '#' . $ColorRow['color_code']));
						}
						
						$Prod = array('ProID' => $Data['ProID'], 
									  'ProTitle' => $Data['ProTitle'], 
									  'ProDesc' => $Data['ProDesc'], 
									  'ProPrice' => $Data['ProPrice'], 
									  'ProRate' => $Data['ProRate'],
									  'CustRate'  => $Data['CustRate'], 
									  'CountRate' => $Data['CountRate'], 
									  'ParCatID'  => $Data['ParCatID'], 
									  'ParCatName'=> $Data['ParCatName'],
									  'SubCatID'  => $Data['SubCatID'],
									  'SubCatName'=> $Data['SubCatName'],
									  'wishList'  => $Data['wl_id'] , 
									  'ProImgs' => $Img,
									  'ProConfs' => $Conf,
									  'ProColors' => $Color);
						
						$IsReservedBu = Orders::IsReservedBu($Data['BUID'],$CustID);
						$BU = array('BUID' => $Data['BUID'], 
									'BUTitle' => $Data['BUTitle'], 
									'BULogo' => $RealAdrr['ImgSerPath'] . 'business_unit/' . $Data['BULogo'],
									'IsReserved'=>$IsReservedBu,
									'BUCurrS'=>$Data['CurrSymbol'],
									'BUDis' => $Data['BUDis'],
									'BUGps' => array('BULat'=>$Data['BULat'],'BULong'=>$Data['BULong']));
		
						$Of = array('OfID' => $Data['OfID'], 
									'OfTitle' => $Data['OfTitle'], 
									'OfText' => $Data['OfText'], 
									'OfDisc' => $Data['OfDisc'], 
									'OfActive' => $Data['OfActive'], 
									'OfFrom' => $Data['OfFrom'], 
									'OfTo' => $Data['OfTo']);
		
						$ResArr = array("Result" => array("Product" => $Prod, "Store" => $BU, "Offer" => $Of));
		
					} else {
		
						$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		
					}
		
				} else {
		
					$ResArr = array('error' => array("code" => "217", "message" => "Invalid Offer ID"));
		
				}
			
			//} else {
					
			//	$ResArr = array('error'=>array("Code"=>"202","Message"=>"UnKnown Location"));
			//}
		//}else{

		//	$ResArr = array('error'=>array("code"=>"203","message"=>"Invalid Permission"));
		//}
		return $ResArr;
	}	
	
	public static function actionGetProdsByCatID($Arr)
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		$CustID = 0;

		if (isset($Arr['CustID'])) {

			if ($Arr['CustID'] > 0) {$CustID = $Arr['CustID'];}
		};
		$Hash = 0;

		if (isset($Arr['Hash'])) {

			if ($Arr['Hash'] != '') {$Hash = $Arr['Hash'];}
		};
		$Long = '0';
	
		if (isset($Arr['Long'])) {

			if ($Arr['Long'] != '') {$Long = $Arr['Long'];}
		};
		$Lat = '0';

		if (isset($Arr['Lat'])) {

			if ($Arr['Lat'] != '') {$Lat = $Arr['Lat'];}
		};
		$Lang = 0;

		if (isset($Arr['lang'])) {

			if ($Arr['lang'] > 0) {$Lang = $Arr['lang'];}
		};
		$CatID = 0;

		if (isset($Arr['CatID'])) {

			if ($Arr['CatID'] > 0) {$CatID = $Arr['CatID'];}
		};
		$Frm = 0;
	
		if (isset($Arr['frm'])) {

			if ($Arr['frm'] >= 0) {$Frm = $Arr['frm'];}
		};
		
		$To = 10;

		if (isset($Arr['to'])) {

			if ($Arr['to'] >= 0) {$To = $Arr['to'];}
		};
		$Dist = self::$Distance;

		if (isset($_GET['Dist'])) {

			if ($_GET['Dist'] > 0) {$Dist = $_GET['Dist'];}
		};		
		$RealAdrr = Globals::ReturnGlobals();
		$ImgPath = $RealAdrr['ImgSerPath'];	
		
		
		if( $Lang != 0 && $Lang != 2 ){
			
			$BuSQL = "SELECT buid AS buss_id, accid,`long` , `lat` ,type , logo,
						   (CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END) As buss_name ,
						   IFNULL((SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 0,1),'')AS currrency_symbol,
					       (((acos(sin((".$Lat."*pi()/180)) * 
					            sin((business_unit.lat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
					            cos((business_unit.lat*pi()/180)) * cos(((".$Long."- business_unit.long)* 
					            pi()/180))))*180/pi())*60*1.1515
					        ) as BUDist,business_unit.rating AS BuRate,
					       IFNULL((SELECT value FROM bu_rating WHERE bu_rating.buid = business_unit.buid AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
					       IFNULL((SELECT COUNT(DISTINCT cid) FROM bu_rating WHERE bu_rating.buid = business_unit.buid LIMIT 0,1),0)AS CountRate
					FROM business_unit
					LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = " . $Lang."
					WHERE business_unit.buid = (SELECT catsub_buid FROM catsub WHERE csid = ".$CatID.")
					HAVING BUDist < ".$Dist;		
		} else {
			$BuSQL = " SELECT buid AS buss_id, accid, title AS buss_name, `long` , `lat` ,type , logo , 
							IFNULL((SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 0,1),'')AS currrency_symbol,
						    (((acos(sin((".$Lat."*pi()/180)) * 
					            sin((business_unit.lat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
					            cos((business_unit.lat*pi()/180)) * cos(((".$Long."- business_unit.long)* 
					            pi()/180))))*180/pi())*60*1.1515
					        ) as BUDist,business_unit.rating AS BuRate,
					       IFNULL((SELECT value FROM bu_rating WHERE bu_rating.buid = business_unit.buid AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
					       IFNULL((SELECT COUNT(DISTINCT cid) FROM bu_rating WHERE bu_rating.buid = business_unit.buid LIMIT 0,1),0)AS CountRate
					 FROM business_unit 
					 WHERE business_unit.buid = (SELECT catsub_buid FROM catsub WHERE csid = ".$CatID.")
					 HAVING BUDist < ".$Dist;
		}
		$BuRow = Yii::app() -> db -> createCommand($BuSQL) -> queryRow();
		$BuArr = array();
		if(!empty($BuRow)){
				
			if ($Lang != 0 && $Lang != 2) {
							
				$CatSQL = "SELECT 
							  ProdID,
							  (CASE WHEN p_lang_title IS NULL THEN ProdName ELSE p_lang_title END) AS ProdName,
							  (CASE WHEN p_lang_discription IS NULL THEN ProdDesc ELSE p_lang_discription END) AS ProdDesc,
							  (CASE WHEN p_lang_price IS NULL THEN ProdPrice ELSE p_lang_price END) AS ProdPrice,
							  ProdBarcode,ProdRate,ProdQrcode,ProdNfc,ProdHash,ProdBookable,
							  ParCatID,ParCatImg,SubCatID,SubCatImg,
							  (CASE WHEN ParLang.cat_lang_title IS NULL THEN ParCatName ELSE ParLang.cat_lang_title END) AS ParCatName,
							  (CASE WHEN ParLang.cat_lang_description IS NULL THEN ParCatDesc ELSE ParLang.cat_lang_description END) AS ParCatDesc,
							  (CASE WHEN SubLang.cat_lang_title IS NULL THEN SubCatName ELSE SubLang.cat_lang_title END) AS SubCatName,
							  (CASE WHEN SubLang.cat_lang_description IS NULL THEN SubCatDesc ELSE SubLang.cat_lang_description END) AS SubCatDesc,
							  CASE WHEN wl_id IS NOT NULL THEN 'True' ELSE 'False' END wl_id  ,
							  IFNULL(offers.discount , 0) AS off_discount,
							 (CASE WHEN ParScrip.sid IS NOT NULL THEN 'True' ELSE 'False' END) AS ParScripID,
							 (CASE WHEN SubScrip.sid IS NOT NULL THEN 'True' ELSE 'False' END) AS SubScripID,
							 IFNULL((SELECT value FROM product_rating WHERE product_rating.pid = AllProductsData.ProdID AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
					         IFNULL((SELECT COUNT(DISTINCT cid) FROM product_rating WHERE product_rating.pid = AllProductsData.ProdID LIMIT 0,1),0)AS CountRate
						FROM AllProductsData
						LEFT JOIN products_lang ON p_lang_pid = ProdID AND p_lang_lang_id = " . $Lang . "
						LEFT JOIN catsub_lang AS ParLang ON ParLang.cat_lang_cs_id = ParCatID AND ParLang.cat_lang_lang_id = " . $Lang . "
						LEFT JOIN catsub_lang AS SubLang ON SubLang.cat_lang_cs_id = SubCatID AND SubLang.cat_lang_lang_id = " . $Lang . "
						LEFT JOIN wishlist ON ProdID = wl_pid AND wl_cid =".$CustID."
						LEFT JOIN subscriptions AS ParScrip ON ParCatID = ParScrip.csid AND ParScrip.cid = ".$CustID."
						LEFT JOIN subscriptions AS SubScrip ON SubCatID = SubScrip.csid AND SubScrip.cid = ".$CustID."
						LEFT JOIN offers ON ProdID = offers.pid AND offers.active = 1 AND NOW() BETWEEN `from` AND `to`
						WHERE AllProductsData.BUID = " . $BuRow['buss_id'] . " AND (ParCatID = ".$CatID." OR SubCatID = ".$CatID.")
						LIMIT " . $Frm . " , " . $To ;		
			
				
			} else {
					
				$CatSQL = " SELECT 
								  ProdID,ProdName,ProdDesc,ProdPrice,ProdBarcode,ProdRate,ProdQrcode,ProdNfc,ProdHash,ProdBookable,
								  ParCatID,ParCatName,ParCatDesc,ParCatImg,
								  SubCatID,SubCatName,SubCatDesc,SubCatImg,
								  CASE WHEN wl_id IS NOT NULL THEN 'True' ELSE 'False' END wl_id  ,
								  IFNULL(offers.discount , 0) AS off_discount,
								 (CASE WHEN ParScrip.sid IS NOT NULL THEN 'True' ELSE 'False' END) AS ParScripID,
								 (CASE WHEN SubScrip.sid IS NOT NULL THEN 'True' ELSE 'False' END) AS SubScripID,
								 IFNULL((SELECT value FROM product_rating WHERE product_rating.pid = AllProductsData.ProdID AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
						         IFNULL((SELECT COUNT(DISTINCT cid) FROM product_rating WHERE product_rating.pid = AllProductsData.ProdID LIMIT 0,1),0)AS CountRate
							 FROM AllProductsData
							 LEFT JOIN wishlist ON ProdID = wl_pid AND wl_cid = ".$CustID."
							 LEFT JOIN subscriptions AS ParScrip ON ParCatID = ParScrip.csid AND ParScrip.cid = ".$CustID."
							 LEFT JOIN subscriptions AS SubScrip ON SubCatID = SubScrip.csid AND SubScrip.cid = ".$CustID."
							 LEFT JOIN offers ON ProdID = offers.pid AND offers.active = 1  AND NOW() BETWEEN `from` AND `to`
							 WHERE AllProductsData.BUID = " . $BuRow['buss_id'] . " AND (ParCatID = ".$CatID." OR SubCatID = ".$CatID.")
							 LIMIT " . $Frm . " , " . $To ;	
			}
			$CatData = Yii::app()-> db->createCommand($CatSQL) -> queryAll();
			$Items = array();
			foreach ($CatData as $Catkey => $CatRow) {
				//------Prod Img
				$ImgSql = "SELECT pimgid, pimg_url
					   	 	    FROM products_imgs JOIN products 
					   	 	    ON products_imgs.pid = products.pid
					   	 	    WHERE products_imgs.pid = " . $CatRow['ProdID'];
				// ."LIMIT 1 "
				$ImgData = Yii::app() -> db -> createCommand($ImgSql) -> queryAll();
				$ImgArr = array();
				foreach ($ImgData as $Imgkey => $Imgrow) {
					array_push($ImgArr, array('imgThmb' => $ImgPath . 'products/' . $Imgrow['pimg_url'], 'img' => $ImgPath . 'products/' . $Imgrow['pimg_url']));
				}
				//--------Configuration
				if ($Lang != 0 && $Lang != 2) {
					$ConfSQL = "SELECT cfg_id ,pdconfv_id, pdconfv_value, pdconfv_chkrad,
						   	 		(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name , 
									(CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
								 FROM pd_conf_v
								 JOIN pd_config ON cfg_id = pdconfv_confid	
								 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "	
								 WHERE pdconfv_pid =" . $CatRow['ProdID'] . "
								 AND parent_id IS NULL ";
				} else {
					$ConfSQL = "SELECT cfg_id , pdconfv_id, pdconfv_value, pdconfv_chkrad, name , 
							        (CASE pdconfv_chkrad WHEN 1 THEN 'TRUE' WHEN 0 THEN 'FALSE' END) as pdconfv_chkrad
								 FROM pd_conf_v
								 JOIN pd_config ON cfg_id = pdconfv_confid
								 WHERE pdconfv_pid =" . $CatRow['ProdID'] . "
								 AND parent_id IS NULL ";

				}
				$ConfData = Yii::app() -> db -> createCommand($ConfSQL) -> queryAll();
				$ConfArr = array();

				foreach ($ConfData as $Confkey => $Confrow) {
					$SubConfArr = array();
					if ($Lang != 0 && $Lang != 2) {
						$SubConfSQL = "SELECT pdconfv_id, pdconfv_value, 
										 (CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name 
									  FROM pd_conf_v
									  JOIN pd_config ON cfg_id = pdconfv_confid
									  JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "
									  WHERE pdconfv_pid = " . $CatRow['ProdID'] . " AND parent_id = " . $Confrow['cfg_id'];
						//"IS NOT NULL ";
					} else {
						$SubConfSQL = "SELECT pdconfv_id, pdconfv_value, name
									   FROM pd_conf_v
									   JOIN pd_config ON cfg_id = pdconfv_confid
									   WHERE pdconfv_pid = " . $CatRow['ProdID'] . " AND parent_id = " . $Confrow['cfg_id'];
						//"IS NOT NULL ";
					}
					$SubConfData = Yii::app() -> db -> createCommand($SubConfSQL) -> queryAll();
					foreach ($SubConfData as $SubConfkey => $SubConfrow) {
						array_push($SubConfArr, array('subId'=>$SubConfrow['pdconfv_id'],'subConf' => $SubConfrow['name'], 'val' => $SubConfrow['pdconfv_value']));

					}
					array_push($ConfArr, array('confId'=>$Confrow['pdconfv_id'],'conf' => $Confrow['name'], 'check' => $Confrow['pdconfv_chkrad'], 'subConfig' => $SubConfArr));

				}
				//-----Color
				if ($Lang != 0 && $Lang != 2) {
					$ColorSQL = " SELECT color_id,color_code,
							   (CASE WHEN color_lang_name IS NULL THEN color_name ELSE color_lang_name END)AS color_name 
						   	  FROM prod_colors
						      LEFT JOIN prod_colors_lang ON color_lang_color_id = color_id AND color_lang_lang_id = " . $Lang . "
						   	  WHERE color_pid = " . $CatRow['ProdID'];
					// ."LIMIT 1 "
				} else {
					$ColorSQL = " SELECT color_id, color_name ,color_code FROM prod_colors
					   		  WHERE color_pid = " . $CatRow['ProdID'];
					// ."LIMIT 1 "

				}

				$ColorData = Yii::app() -> db -> createCommand($ColorSQL) -> queryAll();
				$ColorArr = array();
				foreach ($ColorData as $Colorkey => $Colorrow) {
					array_push($ColorArr, array('color_id' => $Colorrow['color_id'], 'color_name' => $Colorrow['color_name'], 'color_code' => '#' . $Colorrow['color_code']));
				}
				if($CatRow['ParCatID'] == null){$CatRow['ParCatID'] = '0';}
				if($CatRow['ParCatName'] == null){$CatRow['ParCatName'] = 'noncategorized';}
				
				array_push($Items, array('pid' => $CatRow['ProdID'], 
										 'pro_name' => $CatRow['ProdName'], 
										 'price' => $CatRow['ProdPrice'], 
										 'qrcode' => $CatRow['ProdQrcode'], 
										 'nfc' => $CatRow['ProdNfc'], 
										 'hash' => $CatRow['ProdHash'], 
										 'bookable' => $CatRow['ProdBookable'], 
										 'wishList'=>$CatRow['wl_id'] ,
										 'off_discount'=>$CatRow['off_discount'],
										 'discription' => $CatRow['ProdDesc'], 
										 'rate' => $CatRow['ProdRate'],
										 'CustRate' => $CatRow['CustRate'],
										 'CountRate' => $CatRow['CountRate'], 
										 'CatScrip'=>$CatRow['ParScripID'] ,
										 'catId' => $CatRow['ParCatID'], 
										 'catTitle' => $CatRow['ParCatName'], 
										 'SubScrip'=>$CatRow['SubScripID'] ,
										 'subId' => $CatRow['SubCatID'], 
										 'subTitle' => $CatRow['SubCatName'], 
										 'catImg' => $ImgPath . 'catsub/' . $CatRow['ParCatImg'], 
										 'subImg' => $ImgPath . 'catsub/' . $CatRow['SubCatImg'], 
										 'pro_imgs' => $ImgArr, 
										 'Config' => $ConfArr, 
										 'colors' => $ColorArr));
			}
			
			array_push($BuArr, array('id' => $BuRow['buss_id'], 
									 'buss_name' => $BuRow['buss_name'], 
									 'curr_symbol'=>$BuRow['currrency_symbol'] ,
									 'logo_url' => $BuRow['logo'],
									 'BuRate' => $BuRow['BuRate'],
									 'CustRate' => $BuRow['CustRate'], 
									 'CountRate' => $BuRow['CountRate'],
									 'gps' => array('lat' => $BuRow['lat'], 'long' => $BuRow['long']), 
									 'items' => $Items));
		}
		$ResArr = array('stores' => $BuArr);
		
		return $ResArr;
	}
	
	//------------------------ Search --------------------------------
	
	public static function actionSearchProduct($Arr) {
			
		$Arr = CI_Security::ChkPost($Arr);
			
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {

			// $ProductArr = $_POST['product'];
			// $JsonArr = json_decode($ProductArr);
			$t = 0;
			if (isset($Arr['t'])) {
				if ($Arr['t'] > 0) {$t = $Arr['t'];}
			};
			
			$WhrT = "";
			if($t > 0){
				$WhrT = " AND BUType = ".$t;
			}
				
			$CustID = 0;
			if (isset($Arr['cid'])) {
				if ($Arr['cid'] > 0) {$CustID = $Arr['cid'];}
			};
				
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash'] ;}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
			
				$Lang = '0';
				if (isset($Arr['lang'])) {
					if ($Arr['lang'] != '0' && $Arr['lang'] != '') { $Lang = $Arr['lang']; }
				};
				
				$Long = '0';
				if (isset($Arr['long'])) {
					if ($Arr['long'] > 0) { $Long = $Arr['long'] ; }
				};
				
				$Lat = '0';
				if (isset($Arr['lat'])) {
					if ($Arr['lat'] > 0) {$Lat = $Arr['lat'];}
				};
				
				$Dist = self::$Distance;
				if (isset($Arr['dist'])) {
					if ($Arr['dist'] > 0) {$Dist = $Arr['dist'];}
				};
				
				$BuAcc = 0 ;
				if (isset($Arr['BuAcc'])) {
		
					if ($Arr['BuAcc'] > 0) {$BuAcc = $Arr['BuAcc'];}
				};
				
				$WhrAcc = ' ';
				
				if($BuAcc > 0){
						
					$WhrAcc = " AND BUID IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
				}
				//if($Long != 0 || $Lat != 0){
					
						$SqlWhere = ' WHERE BUActive = 0 '.$WhrT.''.$WhrAcc.'';
				
						if (isset($Arr['ProdName']) && isset($Arr['ProdDesc'])) {
							
							if ($Lang == '2' || $Lang == '0') {
								$SqlWhere .= " AND(ProdName LIKE '%" . $Arr['ProdName'] . "%' OR ProdDesc LIKE '%" . $Arr['ProdDesc'] . "%')";
							} else {
								$SqlWhere .= " AND(p_lang_title LIKE '%" . $Arr['ProdName'] . "%' OR p_lang_discription LIKE '%" . $Arr['ProdDesc'] . "%')";
							}
							
						}
						else if (isset($Arr['ProdName'])) {
							if ($Lang == '2' || $Lang == '0') {
								$SqlWhere .= " AND ProdName LIKE '%" . $Arr['ProdName'] . "%'";
							} else {
								$SqlWhere .= " AND p_lang_title LIKE '%" . $Arr['ProdName'] . "%'";
							}
						}
						else if (isset($Arr['ProdDesc'])) {
							if ($Lang == '2' || $Lang == '0') {
								$SqlWhere .= " AND ProdDesc LIKE '%" . $Arr['ProdDesc'] . "%'";
							} else {
								$SqlWhere .= " AND p_lang_discription LIKE '%" . $Arr['ProdDesc'] . "%'";
							}
						}
						if (isset($Arr['ProdPrice'])) {
							$PriceCol = 'ProdPrice';
							if ($Lang == '2' || $Lang == '0') {
								$PriceCol = 'ProdPrice';
							} else {
								$PriceCol = 'p_lang_price';
							}
							if(isset($arr['ProdPrice1'])){
								
								$SqlWhere .= " AND ".$PriceCol." BETWEEN " . $Arr['ProdPrice']." AND ".$Arr['ProdPrice1']."";
								
							}else{
								$SqlWhere .= " AND (".$PriceCol." < " . $Arr['ProdPrice']." OR ".$PriceCol." = ". $Arr['ProdPrice'].")";
								
							}
						}
						if (isset($Arr['ProdQrcode'])) {
							$SqlWhere .= " AND ProdQrcode ='" . $Arr['ProdQrcode']."'";
						}
						if (isset($Arr['ProdBarcode'])) {
							$SqlWhere .= " AND ProdBarcode ='" . $Arr['ProdBarcode']."'";
						}
						if (isset($Arr['ProdRate'])) {
							if (isset($Arr['ProdRate1'])) {
								$SqlWhere .= " AND ProdRate BETWEEN " . $Arr['ProdRate']." AND " . $Arr['ProdRate1']." ";
							}else{
								$SqlWhere .= " AND ProdRate =" . $Arr['ProdRate'];
							}
						}
						if (isset($Arr['BUName'])) {
							if ($Lang == '2' || $Lang == '0') {
								$SqlWhere .= " AND BUName LIKE '%" . $Arr['BUName'] . "%'";
							} else {
								$SqlWhere .= " AND bu_lang_title LIKE '%" . $Arr['BUName'] . "%'";
							}
						}
						if (isset($Arr['CatName'])) {
							if ($Lang == '2' || $Lang == '0') {
								$SqlWhere .= " AND ( ParCatName LIKE '%" . $Arr['CatName'] . "%' OR SubCatName LIKE '%" . $Arr['CatName'] . "%'  )";
							} else {
								$SqlWhere .= " AND ( ParLang.cat_lang_title LIKE '%" . $Arr['CatName'] . "%' OR SubLang.cat_lang_title LIKE '%" . $Arr['CatName'] . "%'  )";
							}
						}
						
						if(isset($Arr['BUID'])){
							$SqlWhere .= " AND BUID = ".$Arr['BUID'];
						}
						
						if(isset($Arr['ParCatID'])){
							$SqlWhere .= " AND ParCatID = ".$Arr['ParCatID'];
						}
						
						if(isset($Arr['SubCatID'])){
							$SqlWhere .= " AND SubCatID = ".$Arr['SubCatID'];
						}
			
						if ($Lang == '2' || $Lang == '0') {
			
							$Sql = " SELECT 
										  ProdID,ProdName,ProdDesc,ProdPrice,ProdBarcode,ProdRate,
										  BUID,BUName,BULogo,BUDesc,BURate,
										  ParCatID,ParCatName,ParCatDesc,ParCatImg,
										  SubCatID,SubCatName,SubCatDesc,SubCatImg,
										  (((acos(sin((".$Lat."*pi()/180)) * 
								            sin((BULat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
								            cos((BULat*pi()/180)) * cos(((".$Long."- BUlong)* 
								            pi()/180))))*180/pi())*60*1.1515) as BUDist	
									 FROM AllProductsData " . $SqlWhere ." HAVING BUDist < ".$Dist;
						} else {
			
							$Sql = " SELECT 
								 		  ProdID,p_lang_title AS ProdName,p_lang_discription AS ProdDesc,p_lang_price AS ProdPrice,ProdBarcode,ProdRate,
										  BUID,bu_lang_title AS BUName,BULogo,bu_lang_description AS BUDesc,BURate,
										  ParCatID,ParLang.cat_lang_title AS ParCatName,ParLang.cat_lang_description AS ParCatDesc,ParCatImg,
										  SubCatID,SubLang.cat_lang_title AS SubCatName,SubLang.cat_lang_description AS SubCatDesc,SubCatImg,
										  (((acos(sin((".$Lat."*pi()/180)) * 
								            sin((BULat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
								            cos((BULat*pi()/180)) * cos(((".$Long."- BUlong)* 
								            pi()/180))))*180/pi())*60*1.1515) as BUDist
									FROM AllProductsData
									LEFT JOIN products_lang ON p_lang_pid = ProdID AND p_lang_lang_id = " . $Lang . "
									LEFT JOIN business_unit_lang ON bu_lang_bu_id = BUID AND bu_lang_lang_id = " . $Lang . "
									LEFT JOIN catsub_lang AS ParLang ON ParLang.cat_lang_cs_id = ParCatID AND ParLang.cat_lang_lang_id = " . $Lang . "
									LEFT JOIN catsub_lang AS SubLang ON SubLang.cat_lang_cs_id = SubCatID AND SubLang.cat_lang_lang_id = " . $Lang .
									$SqlWhere." HAVING BUDist < ".$Dist;
			
						}
						
						$Data = Yii::app() -> db -> createCommand($Sql)-> queryAll();
						$RealAdrr = Globals::ReturnGlobals();
						$ImgPath = $RealAdrr['ImgSerPath'];
						
						$ArrData = array ();
						// print_r($Sql);return;
						if (count($Data) > 0) {
								
							foreach ($Data as $key => $row) {
								
								//--------Get Product Imgs
								$ImgSql = " SELECT pimgid, pimg_url
									   	 	    FROM products_imgs 
									   	 	    WHERE products_imgs.pid = " . $row['ProdID'];
								$ImgAll = Yii::app()->db->createCommand($ImgSql)->queryAll();
								$Img = array();
								if (count($ImgAll) > 0) {
									
									foreach ($ImgAll as $Imgkey => $Imgrow) {
										array_push($Img,array('imgThmb'=>$ImgPath.'products/thumbnails/'.$Imgrow['pimg_url'],'img'=>$ImgPath . 'products/'. $Imgrow['pimg_url']));
									}
								}
								
								array_push($ArrData,array('ProdID'=>$row['ProdID'],
														  'ProdName'=>$row['ProdName'],
														  'ProdDesc'=>$row['ProdDesc'],
														  'ProdPrice'=>$row['ProdPrice'],
														  'ProdBarcode'=>$row['ProdBarcode'],
														  'ProdRate'=>$row['ProdRate'],
														  'ProdImg'=>$Img,
														  'BUID'=>$row['BUID'],
														  'BUName'=>$row['BUName'],
														  'BULogo'=>$ImgPath.'business_unit/thumbnails/'.$row['BULogo'],
														  'BUDesc'=>$row['BUDesc'],
														  'BURate'=>$row['BURate'],
														  'BUDist'=>$row['BUDist'],
														  'ParCatID'=>$row['ParCatID'],
														  'ParCatName'=>$row['ParCatName'],
														  'ParCatDesc'=>$row['ParCatDesc'],
														  'ParCatImg'=>$ImgPath.'catsub/thumbnails/'.$row['ParCatImg'],
														  'SubCatID'=>$row['SubCatID'],
														  'SubCatName'=>$row['SubCatName'],
														  'SubCatDesc'=>$row['SubCatDesc'],
														  'SubCatImg'=>$ImgPath.'catsub/thumbnails/'.$row['SubCatImg']
								
														 )
								          );
								
							}
							
							$ResArr = array('Products' => $ArrData);
			
						} else {
			
							$ResArr = array('error' => array("code" => "214", "message" => "No Data"));
						}
				
			//	} else {
					
				//	$ResArr = array('error'=>array("Code"=>"202","Message"=>"UnKnown Location"));
				//}
			//} else {

				//$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
			//}

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		// echo json_encode($ResArr);
		return $ResArr;

	}

	//----------------------- Product Rating--------------------------

	public static function actionAddProdRating($Arr) {
			
		// header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		//$RatArr ='{ "pid": "11", "cid": "1", "rate": "4" }';
		
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {

			$RatArr = $Arr['rate'];

			// $JsonArr = json_decode($RatArr);
			
			$CustID = 0;
			if (isset($Arr['cid'])) {
				if ($Arr['cid'] > 0) {$CustID = $Arr['cid'];
				}
			};
			
			$Hash = 0;
			if (isset($Arr['hash'])) {
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];
				}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
				$S_Sql = " SELECT * FROM product_rating WHERE cid = " . $CustID . " AND pid = " . $Arr['pid'];
				$ResData = Yii::app()->db->createCommand($S_Sql)->queryRow();
	
				if (!empty($ResData)) {
	
					$Sql = "UPDATE product_rating SET value = " . $Arr['rate'] . " WHERE rid = " . $ResData['rid'];
				} else {
	
					$Sql = "INSERT INTO product_rating (cid,pid,value) VALUES (" . $CustID . "," . $Arr['pid'] . "," . $Arr['rate'] . ")";
				}
	
				Yii::app()->db->createCommand($Sql)->execute();
	
				$RateSQL = " UPDATE products SET rating =
							(SELECT FLOOR((((num1*1)+(num2*2)+(num3*3)+(num4*4)+(num5*5))/(num1+num2+num3+num4+num5)))AS ProRate
							 FROM
								(SELECT
								 (SELECT Count(cid)AS num1 FROM product_rating WHERE value = '1' AND pid = " . $Arr['pid'] . ") AS num1,
								 (SELECT Count(cid)AS num2 FROM product_rating WHERE value = '2' AND pid = " . $Arr['pid'] . ") AS num2,
								 (SELECT Count(cid)AS num3 FROM product_rating WHERE value = '3' AND pid = " . $Arr['pid'] . ") AS num3,
								 (SELECT Count(cid)AS num4 FROM product_rating WHERE value = '4' AND pid = " . $Arr['pid'] . ") AS num4,
								 (SELECT Count(cid)AS num5 FROM product_rating WHERE value = '5' AND pid = " . $Arr['pid'] . ") AS num5
								 FROM product_rating WHERE pid = " . $Arr['pid'] . " GROUP BY pid 
								) AS Rate ) WHERE pid =" . $Arr['pid'];
				Yii::app()->db->createCommand($RateSQL)->execute();
	
				$Rate = Yii::app() -> db -> createCommand("SELECT rating FROM products WHERE pid = " . $Arr['pid']) -> queryRow();
				$Rate = !empty($Rate) ? $Rate['rating'] : '0';
	
				
				$ResArr = array('rate' => $Rate);
			
			}else{
				// $ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
				$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
			}
			
		//} else {

			//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}
		
		// echo json_encode($ResArr);
		return $ResArr;
	}
	//------------------------ Cpanel (Waiter) -------------------------------
	
	public static function actionLoginCpanel($Arr) {
			
		$Arr = CI_Security::ChkPost($Arr);
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
			
			$ChkDVSql = " SELECT * FROM dv_licenses WHERE dv_license_str = '".$Arr['DevID']."'";
			$ChkDVRow = Yii::app()->db->createCommand($ChkDVSql)->queryRow();
			/*if(!empty($ChkDVRow)){*/
				
				$ChkWSql = " SELECT * FROM cpanel LEFT JOIN business_unit ON cpanel.buid = business_unit.buid 
							 WHERE username = '".$Arr['UserName']."' AND password = '".md5($Arr['Password'])."' AND role_id = 6 ";
				$ChkWRow = Yii::app()->db->createCommand($ChkWSql)->queryRow();
				
				if(!empty($ChkWRow)){
					
					$Token = CustLib::CpanelToken($ChkWRow['cp_id'],$Arr['DevID'],$Arr['RegID']);
					$ResArr = array('Result'=>array('cpanel'=>array('CpID'=>$ChkWRow['cp_id'],
																	'BuID'=>$ChkWRow['buid'],
																	'BuName'=>$ChkWRow['title'],
																	'BuType'=>$ChkWRow['type'],
																	'Token'=>$Token,
																	'FullName'=>$ChkWRow['fname'].' '.$ChkWRow['lname'])));
					
				} else {
					
					$ResArr = array('error' => array("code" => "222", "message" => "Invalid Waiter"));
				}
				
			/*
			} else {
							
				$ResArr = array('error' => array("code" => "221", "message" => "Invalid Device"));
			}*/
			
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function actionLogoutCpanel($Arr)
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();		
		
		if(isset($Arr) && !empty($Arr)){
				
			$CpID = 0;
			if (isset($Arr['CpID'])) {if ($Arr['CpID'] > 0) {$CpID = $Arr['CpID'];}}
			$Token = '';
			if (isset($Arr['Token'])) {$Token = $Arr['Token'];}
			$DevID = '';
			if (isset($Arr['DevID'])) {$DevID = $Arr['DevID'];}
			
			if(Login::ChkCpanelToken($CpID,$Token,$DevID) == True){
					
				$UPSql = " UPDATE cpanel_token SET cp_tkn_token = '' 
						   WHERE cp_tkn_cp_id = ".$CpID." AND cp_tkn_token = '".$Token."' AND cp_tkn_dev_id = '".$DevID."'";
				
				$Res = Yii::app()->db->createCommand($UPSql)->execute();
				
				if($Res > 0){$Res = True;}
				else{$Res = False;}
				
				$ResArr = array('Result'=>$Res);
				
			} else {
					
				$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			}
			
		
		} else {
			
			$ResArr = array('error'=>array("code"=>"200","message"=>"Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function CpanelToken($CpanelID = 0,$DevID = '',$RegID = '')
	{
		$Token = sha1(date(time()));
		
		$ChkTokenSql = " SELECT * FROM cpanel_token WHERE cp_tkn_dev_id = '".$DevID."' AND cp_tkn_cp_id = ".$CpanelID;
		$ChkTokenRow = Yii::app()->db->createCommand($ChkTokenSql)->queryRow();
		if(!empty($ChkTokenRow)){
			
			$UpTokenSql = " UPDATE cpanel_token SET cp_tkn_token = '".$Token."',cp_tkn_reg_id = '".$RegID."' WHERE cp_tkn_dev_id = '".$DevID."' AND cp_tkn_cp_id = ".$CpanelID;
			Yii::app()->db->createCommand($UpTokenSql)->execute();
			
		} else {
			
			$InsTokenSql = " INSERT INTO cpanel_token (cp_tkn_cp_id,cp_tkn_token,cp_tkn_dev_id,cp_tkn_reg_id) 
			                 VALUES (".$CpanelID.",'".$Token."','".$DevID."','".$RegID."') ";
			Yii::app()->db->createCommand($InsTokenSql)->execute();
		}
		
		return $Token;
	}
	
	public static function actionBuTables($Arr)
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();
		
		if(isset($Arr) && !empty($Arr)){
				
			$CpID = 0;
			if (isset($Arr['CpID'])) {if ($Arr['CpID'] > 0) {$CpID = $Arr['CpID'];}}
			
			$Token = '';
			if (isset($Arr['Token'])) {$Token = $Arr['Token'];}
			$DevID = '';
			if (isset($Arr['DevID'])) {$DevID = $Arr['DevID'];}
			
			if(Login::ChkCpanelToken($CpID,$Token,$DevID) == True){
						
				$BuID = 0;
				if (isset($Arr['BuID'])) {if ($Arr['BuID'] > 0) {$BuID = $Arr['BuID'];}}	
				
				$TableArr = array();
				$TableSql = " SELECT * FROM bu_tables WHERE bu_table_buid = ".$BuID;
				$TableData = Yii::app()->db->createCommand($TableSql)->queryAll();
				foreach ($TableData as $key => $row) {
					array_push($TableArr,array('T_ID'=>$row['bu_table_id'],
											   'T_Serial'=>$row['bu_table_serial'],
											   'T_Chairs'=>$row['bu_table_num_chairs']));
				}	
					
				$ResArr = array('Tables'=>$TableArr);	
					
			} else {
				
				$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			}
			
		} else {
				
			$ResArr = array('error'=>array("code"=>"200","message"=>"Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function CpanelNotify($Mess)
	{
		$RegArr = array();$RegRes = False;
		
		$RegSql = " SELECT DISTINCT cp_tkn_reg_id FROM cpanel_token ";
		//WHERE cp_tkn_dev_id IN (SELECT dv_license_str FROM dv_licenses)
		$RegData = Yii::app()->db->createCommand($RegSql)->queryAll();	
	
		foreach ($RegData as $RegKey => $RegRow) {
			array_push($RegArr,$RegRow['cp_tkn_reg_id']);
		}
		
		if(count($RegData) > 0){
			
			$Res = GCM::SendNotification($RegArr, $Mess);
			$Res =	json_decode($Res,TRUE);
			
			if($Res['failure'] == '0' && $Res['success'] > 0){
				
				$RegRes = True;
				
			}
		}		
	}
	
	//------------------------ Customer -------------------------------
	
	public static function actionCustomerSocial($Arr)
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		$ReqKeyArr = array();
		$ReqKeyArr['ReqKey'] = $Arr['ReqKey'];
		$ReqKeyArr['Email'] = $Arr['Email'];
		$ReqKeyArr['DevID'] = $Arr['DevID'];
		
		if(CustLib::ChkReqKey($ReqKeyArr) == True){
			
			$ResArr = array();
			
			if(isset($Arr) && !empty($Arr)){
				
				$ChkEmailSql = " SELECT * FROM customers WHERE email = '" . $Arr['Email'] . "'";
				$ChkEmailRes = Yii::app() -> db -> createCommand($ChkEmailSql) -> queryRow();
				
				$CustID = empty($ChkEmailRes)? 0 : $ChkEmailRes['cid'];
				
				$IssetSArr = array('CustID'=>$CustID,'SocID'=>$Arr['SocID'],'SocType'=>$Arr['SocType']);
				
				if(CustLib::CkhIssetSocial($IssetSArr) == True){
					
					$Hash = '';
					
					if ($CustID > 0) {
						//------- Login 
					    $Arr['CustID'] = $CustID;
						$Hash = CustLib::LoginSocial($Arr);
					} else {
						//------- Register 
					    $CustID = CustLib::RegisterSocial($Arr);
					    $Arr['CustID'] = $CustID;
					    $Hash = CustLib::LoginSocial($Arr);
					}
					$SocialArr = array('CustID'=>$CustID,'SocID'=>$Arr['SocID'],'SocType'=>$Arr['SocType'],'SocToken'=>$Arr['SocToken']);
					CustLib::ChkSocial($SocialArr);
					
					//-----------------Languages
					$LangRes = array();
					$LangData = Yii::app()->db->createCommand("SELECT * FROM languages WHERE active = 1 ") -> queryAll();
					
					foreach ($LangData as $key => $row) {
						array_push($LangRes,array('LangID'=>$row['lang_id'],'LangN'=>$row['lang_name']));
					}
					
					$CustArr = CustLib::actionGetCustomer(array('CustID'=>$CustID,'Hash'=>$Hash));
					$ResArr = array('Customer'=>$CustArr,'Languages'=>$LangRes);
					
				} else {
					
					$ResArr = array('error'=>array("code"=>"216","message"=>"This Social Account used for another Customer"));
				}
			} else {
					
				$ResArr = array('error'=>array("code"=>"200","message"=>"Invalid Data"));
			}
			
		} else {
				
			$ResArr = array('error'=>array("code"=>"215","message"=>"Invalid Request Key"));
			
		}
		
		return $ResArr;
	}
	
	public static function RegisterSocial($Arr)
	{
		if($Arr['AppSource'] == '0'){
					
			$CountSql = " SELECT * FROM country WHERE iso = '" . $Arr['Country'] . "'";
			$CountRes = Yii::app() -> db -> createCommand($CountSql) -> queryRow();
			$Arr['Country'] = $CountRes['country_id'];
		}
		
		$Img = '';
		if($Arr['Gender'] == '0'){$Img = 'cust_male.jpg';}
		if($Arr['Gender'] == '1'){$Img = 'cust_female.jpg';}
		
		$CustPass = base64_encode($Arr['Email']);
		$CustPass = substr($CustPass,0,8);
		
		$InsCustSql = " INSERT INTO customers (email,password,gender,image,country_id)
					       VALUES('" . $Arr['Email'] . "',
					       		  '" . md5($CustPass) . "',
					       		  '" . $Arr['Gender']. "',
					       		  '" . $Img. "',
					       		   " . $Arr['Country'] . "
					       		 ) ";

		Yii::app() -> db -> createCommand($InsCustSql) -> execute();
		$CustID = Yii::app() -> db -> getLastInsertID();
		if ($CustID > 0) {
						
			$AddrSql = "INSERT INTO customer_add (cust_add_cust_id , cust_add_country_id)
					    VALUES( ".$CustID." , ". $Arr['Country'] . ")";
			Yii::app()->db->createCommand($AddrSql)->execute();
			
		}
		
		return $CustID;
	}
	
	public static function LoginSocial($Arr)
	{
		$Hash = '';	
		if($Arr['AppSource']== '0'){
			//------------Push Notifications
			$PushSql = "SELECT * FROM push_notifications WHERE cid = " . $Arr['CustID'] . " AND gcm_devid = '" . $Arr['DevID'] . "'";
			$PushRes = Yii::app() -> db -> createCommand($PushSql) -> queryRow();
			$PushID = !empty($PushRes) ? $PushRes['puid'] : 0;

			if ($PushID == 0) {
				$InsPushSql = "INSERT INTO push_notifications (cid,email,gcm_regid,gcm_devid,count_dev)
						       VALUES( " . $Arr['CustID'] . ",
						       		  '" . $Arr['Email'] . "',
						       		  '" . $Arr['RegID'] . "',
						       		  '" . $Arr['DevID'] . "', 1) ";

				Yii::app() -> db -> createCommand($InsPushSql) -> execute();

			} else {

				$UpPushSql = "UPDATE push_notifications SET gcm_regid = '" . $Arr['RegID'] . "', count_dev = (count_dev + 1) WHERE puid = " . $PushID;
				Yii::app() -> db -> createCommand($UpPushSql) -> execute();
			}
			//------------ Generate Hash
					
			$Hash = sha1(date(time()));
			$HashArr = array();
			$HashArr['CustID']= $Arr['CustID'];
			$HashArr['DevID']= $Arr['DevID'];
			$HashArr['AppSource']= $Arr['AppSource'];
			$HashArr['Hash']= $Hash;
			CustLib::CustGenerateHash($HashArr);
			
			//------------ Remove QCode
			Yii::app() -> db -> createCommand("UPDATE customers SET q_code = '',lat = '".$Arr['Lat']."',`long` = '".$Arr['Long']."' WHERE cid = " . $Arr['CustID']) -> execute();

			if($Arr['AppSource']== '1'){
				
				//----------- Customer Session
				CustLib::CustSession($CustRes);
			}
		}
		return $Hash;
	}
	
	public static function ChkSocial($Arr)
	{
		$ChkSocSQL = " SELECT * FROM customer_social WHERE cust_s_cust_id = ".$Arr['CustID']." AND cust_s_social_id = '".$Arr['SocID']."' AND cust_s_type = '".$Arr['SocType']."' ";
		$ChkSocRow = Yii::app()->db->createCommand($ChkSocSQL)->queryRow();
		
		if(empty($ChkSocRow)){
			
			$InsSql = " INSERT INTO customer_social (cust_s_cust_id,cust_s_type,cust_s_social_id,cust_s_social_token) 
						VALUES (".$Arr['CustID'].",'".$Arr['SocType']."','".$Arr['SocID']."','".$Arr['SocToken']."')";
			Yii::app()->db->createCommand($InsSql)->execute();
			
		} else {
			
			$UpSql = " UPDATE customer_social SET cust_s_social_token = '".$Arr['SocToken']."' WHERE cust_s_id = ".$ChkSocRow['cust_s_id'];
			Yii::app()->db->createCommand($UpSql)->execute();
		}
	}
	
	public static function CkhIssetSocial($Arr)
	{
		$Chk = True ;	
		$CustID = isset($Arr['CustID']) ? $Arr['CustID'] : 0 ;	
		$ChkSQl = " SELECT * FROM customer_social WHERE cust_s_social_id = '".$Arr['SocID']."' AND cust_s_type = '".$Arr['SocType']."'";
		$ChkData = Yii::app()->db->createCommand($ChkSQl)->queryRow();
		if(!empty($ChkData)){
			if($ChkData['cust_s_cust_id'] != $CustID){
				$Chk = False ;
			}
		}
		return $Chk;
	}
	
	public static function ChkReqKey($Arr)
	{
		$Chk = False ;	
		//---Key -------> Email-Devid-
		$ApiCrypter = new ApiCrypter();
		$KeyDecrypt = $ApiCrypter->decrypt($Arr['ReqKey']);
		
		if($KeyDecrypt != false){	
			$KeyArr = explode('-',$KeyDecrypt);
			
			if($KeyArr[0]== $Arr['Email'] && $KeyArr[1]== $Arr['DevID']){
				$Chk = True ;	
			}
		}
		return $Chk;
	}
	
	public static function actionRegisterCustomer($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {

			$ChkEmailSql = " SELECT * FROM customers WHERE email = '" . $Arr['email'] . "'";
			$ChkEmailRes = Yii::app() -> db -> createCommand($ChkEmailSql) -> queryAll();

			if (count($ChkEmailRes) == 0) {
					
				if($Arr['AppSource'] == '0'){
					
					$CountSql = " SELECT * FROM country WHERE iso = '" . $Arr['coun_id'] . "'";
					$CountRes = Yii::app() -> db -> createCommand($CountSql) -> queryRow();
					$Arr['country'] = $CountRes['country_id'];
				}	
						
				$Img = '';
				if($Arr['gender'] == '0'){$Img = 'cust_male.jpg';}
				if($Arr['gender'] == '1'){$Img = 'cust_female.jpg';}

				$InsCustSql = "INSERT INTO customers (fname,lname,email,password,gender,birthdate,image,country_id)
						       VALUES('" . $Arr['fname'] . "',
						       		  '" . $Arr['lname'] . "',
						       		  '" . $Arr['email'] . "',
						       		  '" . md5($Arr['pass']) . "',
						       		  '" . $Arr['gender']. "',
						       		  '" . $Arr['b_date'] . "',
						       		  '" . $Img. "',
						       		   " . $Arr['country'] . "
						       		 ) ";

				Yii::app() -> db -> createCommand($InsCustSql) -> execute();
				$CustID = Yii::app() -> db -> getLastInsertID();

				if ($CustID > 0) {
						
					$AddrSql = "INSERT INTO customer_add (cust_add_cust_id , cust_add_country_id)
							    VALUES( ".$CustID." , ". $Arr['country'] . ")";
					Yii::app()->db->createCommand($AddrSql)->execute();
					
					$ResponseArr = array();
					$GetCustSql = " SELECT * FROM customers WHERE cid = " . $CustID;
					$ResponseArr = Yii::app() -> db -> createCommand($GetCustSql) -> queryRow();

					$ResArr = array("Result" => array('customer' => $ResponseArr));
				}

			} else {

				$ResArr = array('error' => array("code" => "205", "message" => "This Email Inserted Before"));
			}

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
	public static function actionLoginCustomer($Arr) {
			
		$Arr = CI_Security::ChkPost($Arr);
		
		if (isset($Arr) && !empty($Arr)) {

			$CustSql = " SELECT * FROM customers WHERE email = '" . $Arr['email'] . "' AND password = '" . md5($Arr['pass']) . "'";
			$CustRes = Yii::app() -> db -> createCommand($CustSql) -> queryRow();

			$ResArr = array();

			if (!empty($CustRes)) {
					
				if($Arr['AppSource']== '0'){
					//------------Push Notifications
					$PushSql = "SELECT * FROM push_notifications WHERE cid = " . $CustRes['cid'] . " AND gcm_devid = '" . $Arr['dev_id'] . "'";
					$PushRes = Yii::app() -> db -> createCommand($PushSql) -> queryRow();
					$PushID = !empty($PushRes) ? $PushRes['puid'] : 0;
	
					if ($PushID == 0) {
						$InsPushSql = "INSERT INTO push_notifications (cid,email,gcm_regid,gcm_devid,count_dev)
								       VALUES( " . $CustRes['cid'] . ",
								       		  '" . $CustRes['email'] . "',
								       		  '" . $Arr['reg_id'] . "',
								       		  '" . $Arr['dev_id'] . "', 1) ";
	
						Yii::app() -> db -> createCommand($InsPushSql) -> execute();
	
					} else {
	
						$UpPushSql = "UPDATE push_notifications SET gcm_regid = '" . $Arr['reg_id'] . "', count_dev = (count_dev + 1) WHERE puid = " . $PushID;
						Yii::app() -> db -> createCommand($UpPushSql) -> execute();
					}
				}
				//------------ Generate Hash
					
				$Hash = sha1(date(time()));
				$HashArr = array();
				$HashArr['CustID']= $CustRes['cid'];
				$HashArr['DevID']= $Arr['dev_id'];
				$HashArr['AppSource']= $Arr['AppSource'];
				$HashArr['Hash']= $Hash;
				CustLib::CustGenerateHash($HashArr);
				$CustRes['hash']= $Hash;
				
				//------------ Remove QCode

				Yii::app() -> db -> createCommand("UPDATE customers SET  q_code = '',lat = '".$Arr['lat']."',`long` = '".$Arr['long']."' WHERE cid = " . $CustRes['cid']) -> execute();

				if($Arr['AppSource']== '1'){
					
					//----------- Customer Session
					CustLib::CustSession($CustRes);
				}
				//-----------------Languages	
				
				$LangRes = array();
				$LangData = Yii::app()->db->createCommand("SELECT * FROM languages WHERE active = 1") -> queryAll();
				
				foreach ($LangData as $key => $row) {
					array_push($LangRes,array('LangID'=>$row['lang_id'],'LangN'=>$row['lang_name']));
				}
				
				$ResArr = array("Result" => array('customer' => $CustRes,'Langs' => $LangRes));
				
			} else {

				$ResArr =array('error' => array("code" => "206", "message" => "Email Or Password is Incorrect"));
			}

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));

		}
		
		return $ResArr;

	}
	
	public static function actionAutoLoginCustomer($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();

		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr);

			$CustSql = " SELECT * FROM customers WHERE cid = " . $CustJson -> cust_id . " AND hash = '" . $CustJson -> hash . "'";
			$CustRes = Yii::app() -> db -> createCommand($CustSql) -> queryRow();
			
			Yii::app() -> db -> createCommand("UPDATE customers SET lat = '".$CustJson -> lat."',`long` = '".$CustJson -> long."' WHERE cid = " . $CustRes['cid']) -> execute();
			
			if (!empty($CustRes)) {
					
				//-----------------Languages	
				$LangRes = array();
				$LangData = Yii::app()->db->createCommand("SELECT * FROM languages WHERE active = 1") -> queryAll();
				foreach ($LangData as $key => $row) {
					array_push($LangRes,array('LangID'=>$row['lang_id'],'LangN'=>$row['lang_name']));
				}
				$ResArr = array("Result" => array('customer' => $CustRes,'Langs' => $LangRes));

			} else {

				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);
	}

	public static function actionLogoutCustomer($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {

			$CustSql = " SELECT * FROM customers WHERE cid = " . $Arr['cust_id'] ;
			$CustRes = Yii::app() -> db -> createCommand($CustSql) -> queryRow();

			if (!empty($CustRes)) {

				Yii::app() -> db -> createCommand("UPDATE customers SET q_code = '' WHERE cid = " . $CustRes['cid']) -> execute();
				Yii::app() -> db -> createCommand("UPDATE customer_hash SET cust_hash_hash = '' WHERE cust_hash_cust_id = ".$CustRes['cid']." 
												   AND cust_hash_dev_id = '".$Arr['dev_id']."' AND cust_hash_app_source = ".$Arr['AppSource']) -> execute();

				$ResArr = array("Result" => array('customer' => "Customer Logout"));

			} else {

				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;

	}
	
	public static function actionImageCustomer($Arr)
	{
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
			
			$CustID = 0;
			if (isset($Arr['CustID'])) {
	
				if ($Arr['CustID'] > 0) {$CustID = $Arr['CustID'];}
			};
			$Hash = 0;
			if (isset($Arr['Hash'])) {
	
				if ($Arr['Hash'] != '') {$Hash = $Arr['Hash'];}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
							
				if(isset($Arr['image'])){		
					// Get image string posted from Android App		
					$Img = $Arr['image'];
					
					// Get file name posted from Android App
					$ImgName = $Arr['imgname'];
					$ExtImg = pathinfo($ImgName, PATHINFO_EXTENSION);
					$rnd = $random = date(time());
					$ImgName = md5($rnd.'-'.$ImgName);
					$ImgName = $ImgName.'.'.$ExtImg;
					
					// Decode Image
					$Img = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $Img);
					$Img = str_replace(' ','+',$Img);
					$Img= ltrim($Img, "image=");
					
					$Img = preg_replace("/[\r\n]*/","",$Img);
					$Img= rtrim($Img, " ");
					$Img= ltrim($Img, " ");
					$ImgBinary = base64_decode($Img,FALSE);
					
					// Images will be saved under '-----' folder
					$RealArr = Globals::ReturnGlobals();
					$RealPath = $RealArr['ImgPath'] . 'customers/';
					$File = file_put_contents($RealPath.$ImgName,$ImgBinary);
				   
				    //---------------Update Customer
				  	
				  	$imgSql = " SELECT image FROM customers WHERE cid = ".$CustID;
					$imgRow = Yii::app()->db->createCommand($imgSql)->queryRow();
				  	$oldImg = $imgRow['image'];
				   
					if(file_exists($RealPath.$oldImg) && $oldImg != 'cust_male.jpg' && $oldImg != 'cust_female.jpg'){
						unlink($RealPath.$oldImg);
					}
				   
				    $UpSql = " UPDATE customers SET image = '".$ImgName."' WHERE cid =".$CustID;
				    Yii::app()->db->createCommand($UpSql)->execute();
				    
				    $ResArr = array("Result" => array('imgName'=>$RealPath.$ImgName));
					
				}else{
						
					$ResArr = array('error' => array("code" => "207", "message" => "NO Image"));
					
				}
			//}else{
		
			//	$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
			//}
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}	
		return $ResArr;
			
	}
	
	public static function actionUpdateCustomer($Arr)	
	{
		$Arr = CI_Security::ChkPost($Arr);
		
		 $ResArr = array();
		
		 if (isset($Arr) && !empty($Arr)) {
		
			$CustID = 0;
		
			if (isset($Arr['cust_id'])) {
		
				if ($Arr['cust_id'] > 0) {$CustID = $Arr['cust_id'];}
			};
			$Hash = 0;
		
			if (isset($Arr['hash'])) {
		
				if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				  	
				  if($Arr['AppSource'] == '0'){
				  	
					$CountSql = " SELECT * FROM country WHERE iso = '" . $Arr['country'] . "'";
					$CountRes = Yii::app() -> db -> createCommand($CountSql) -> queryRow();
					$Arr['country'] = $CountRes['country_id'];
					
				  }
				 $UpSql = " UPDATE customers SET fname = '".$Arr['fname']."',
				  								 lname = '".$Arr['lname']."',
				  								 gender = ".$Arr['gender'].",
				  								 birthdate = '".$Arr['b_date']."',
				  								 phone = '".$Arr['phone']."'
				  			 WHERE cid =".$CustID;
			   	  Yii::app()->db->createCommand($UpSql)->execute();
				 
				  $CustArr['CustID']= $CustID;
				  $CustArr['Hash']= $Hash;
				  
				  $ResArr = CustLib::actionGetCustomer($CustArr);
				  
			//} else {
		
				//$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
			//}
			
		} else {
		
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function actionGetCustomer($Arr)
	{
		$Arr = CI_Security::ChkPost($Arr);
			
		$ResArr = array();
		
		if(isset($Arr) && !empty($Arr)){
				
			$CustID = 0;
			if (isset($Arr['CustID'])) {
				if ($Arr['CustID'] > 0) {$CustID = $Arr['CustID'];}
			};
			
			$Hash = '0';
			if (isset($Arr['Hash'])) {
				if ($Arr['Hash'] != '') {$Hash = $Arr['Hash'];}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
				if ($CustID > 0) {	
				
					  $CustSql = " SELECT * FROM customers 
					  			   LEFT JOIN country ON customers.country_id = country.country_id
					  			   WHERE cid =".$CustID;
								   
					  $CustData = Yii::app()->db->createCommand($CustSql)->queryRow();
					  $RCustArr = array();
					  if(!empty($CustData)){
					  		
					  	$RealArr = Globals::ReturnGlobals();
						$RealPath = $RealArr['ImgSerPath'] . 'customers/';	
					  	$Gen = $CustData['gender']== 0 ?'Male':'Female';
						
						
						$AddrArr = array();
						$addSql = "SELECT cust_add_id , cust_add_city , cust_add_street , cust_add_country_id ,cust_add_region , 
										  name , cust_add_postalCode , cust_add_deleted,
										  CASE cust_add_default WHEN '0' THEN 'FALSE' WHEN '1' THEN 'TRUE' END AS DefaultAddr
								   FROM customer_add
								   LEFT JOIN country ON cust_add_country_id = country.country_id
								   WHERE cust_add_cust_id = ".$CustID;
						$CustAdd = Yii::app()->db->createCommand($addSql)->queryAll();
						
						foreach ($CustAdd as $key => $row) {
							array_push($AddrArr ,  array('AddID'       =>$row['cust_add_id'],
														 'AddCity'     =>$row['cust_add_city'] , 
														 'AddStreet'   =>$row['cust_add_street'] ,
														 'AddCountryID'=>$row['cust_add_country_id'],
														 'AddCountry'  =>$row['name'],
														 'AddRegion'   =>$row['cust_add_region'],
														 'AddPostal'   =>$row['cust_add_postalCode'],
														 'AddDefault'  =>$row['DefaultAddr'],
														 'AddDeleted'  =>$row['cust_add_deleted']));
						}
						
						//---------Customer Hash
						
					    //---------Payment Systems
					    
					    $PaySysArr = Globals::ReturnPaymentSystems();			
						
						array_push($RCustArr,array('custid'   =>$CustData['cid'],
												   'hash'     =>$Hash,
												   'fname'    =>$CustData['fname'],
												   'lname'    =>$CustData['lname'],
												   'email'    =>$CustData['email'],
												   'birthdate'=>$CustData['birthdate'],
												   'gender'   =>$Gen,
												   'image'    =>$RealPath.$CustData['image'],
												   'country'  =>$CustData['name'],
												   'phone'    =>$CustData['phone']	,
												   'Addr'     =>$AddrArr,
												   'PaySys'   =>$PaySysArr								   
												  ));
						
						$ResArr = array("Customer" => $RCustArr);
					  }
					  
				}else{
					
					$ResArr = array('error'=>array("Code"=>"201","Message"=>"Invalid Customer"));
				}
				  
			//} else {
	
				//$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
			//}
		
		}else{
			
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}

	public static function CustSession($CustRes)
	{
		$session = yii::app()->session;
		$CustArr = $session['Cust'];
		
		$CustArr['CustID']    = $CustRes['cid'];
		$CustArr['CustMail']  = $CustRes['email'];
		$CustArr['CustPass']  = $CustRes['password'];
		$CustArr['CustFName'] = $CustRes['fname'];
		$CustArr['CustLName'] = $CustRes['lname'];
		$CustArr['CustHash']  = $CustRes['hash'];
		// Yii::app()->session['Cust'] = $CustArr;
		
		$session['Cust'] = $CustArr;
	}
	
	public static function CustGenerateHash($HashArr)
	{
		$HashSql = " SELECT * FROM customer_hash WHERE cust_hash_cust_id = " . $HashArr['CustID'] . " 
												   AND cust_hash_dev_id  = '" . $HashArr['DevID'] . "'
												   AND cust_hash_app_source  = " . $HashArr['AppSource'];
		$HashRes = Yii::app() -> db -> createCommand($HashSql) -> queryRow();
		
		$HashID = !empty($HashRes) ? $HashRes['cust_hash_id'] : 0;
		
		if($HashID == 0){
			
			$InsHashSql = " INSERT INTO customer_hash (cust_hash_cust_id,cust_hash_dev_id,cust_hash_hash,cust_hash_app_source) 
							VALUES (".$HashArr['CustID'].",'".$HashArr['DevID']."','".$HashArr['Hash']."',".$HashArr['AppSource'].")";
			Yii::app() -> db -> createCommand($InsHashSql) -> execute();
			
		}else{
			
			$UpHashSql = " UPDATE customer_hash SET cust_hash_hash = '".$HashArr['Hash']."'
						   WHERE  cust_hash_id = " . $HashID;
			Yii::app() -> db -> createCommand($UpHashSql) -> execute();
		}
	}
	
	public static function actionAddShippingAddr($Arr){
		
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
				
			$CustID = 0;
			if (isset($Arr['cust_id']) && $Arr['cust_id'] > 0) {
				
				$CustID = $Arr['cust_id'];
			
				$Hash = 0;
				if (isset($Arr['hash']) && !empty($Arr['hash'])) {
					if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];}
				}
				
				//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
					$SQL = "INSERT INTO customer_add (cust_add_cust_id ,cust_add_country_id , cust_add_city ,cust_add_region ,
												  cust_add_street , cust_add_postalCode ) 
							VALUES(" . $CustID . ",
								   " . $Arr['ship_country'] . ",
								   '". $Arr['ship_city'] . "' , 
								   '". $Arr['ship_region']."' ,
								   '". $Arr['ship_stname'] ."' , 
								   '". $Arr['ship_postalCode']."')";
			
					Yii::app()->db->createCommand($SQL)->execute();
					$CustShipAddID = Yii::app()->db->getLastInsertID();
					
					$ResArr = array('ShipAddID' => $CustShipAddID);
					
				//} else {
						
				//	$ResArr = array('error'=>array("Code"=>"203","message"=>"Invalid Permission"));
				//}
				
			}
			
		}else{
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}

	public static function actionCustSetAddrDefault($Arr)
	{
		$ResArr = array();
		
		if (isset($Arr) && !empty($Arr)) {
			
			$CustID = 0;
			if (isset($Arr['cust_id']) && $Arr['cust_id'] > 0) {
				
				$CustID = $Arr['cust_id'];
			
				$Hash = 0;
				if (isset($Arr['hash']) && !empty($Arr['hash'])) {
					if ($Arr['hash'] > 0) {$Hash = $Arr['hash'];}
				}
				//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
							
					$AddrID = 0;
					if (isset($Arr['AddrID']) && !empty($Arr['AddrID'])) {
						if ($Arr['AddrID'] > 0) {$AddrID = $Arr['AddrID'];}
					}	
					
					$UpSQL = " UPDATE customer_add SET cust_add_default = 0 WHERE cust_add_id != ".$AddrID;
					Yii::app()->db->createCommand($UpSQL)->execute();
					
					$AddSQL = " UPDATE customer_add SET cust_add_default = 1 WHERE cust_add_id = ".$AddrID;
					$Res = Yii::app()->db->createCommand($AddSQL)->execute();
					
					$CustSQL = " UPDATE customers SET country_id = 
								 (SELECT cust_add_country_id FROM customer_add WHERE cust_add_id = ".$AddrID.") 
								 WHERE cid = ".$CustID;
					Yii::app()->db->createCommand($CustSQL)->execute();
					
					if($Res > 0){
						$ResArr = array("Result" => "TRUE");
					}else{
						$ResArr = array("Result" => "FALSE");
					}
				//} else {
						
				//	$ResArr = array('error'=>array("Code"=>"203","message"=>"Invalid Permission"));
				//}
				
			}
		} else {
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
	}
	
	public static function actionResetPasswordCustomer($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);

		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {

			$CustSql = " SELECT * FROM customers WHERE email = '" . $Arr['email'] . "'";
			$CustRes = Yii::app()->db->createCommand($CustSql) -> queryRow();

			if (!empty($CustRes)) {
				//---- Generate	QCode

				$QCode = sha1(date(time()));

				Yii::app() -> db -> createCommand("UPDATE customers SET q_code = '" . $QCode . "' WHERE cid = " . $CustRes['cid']) -> execute();

				// ---- Send E-Mail
				$To = $CustRes['fname'].' '.$CustRes['lname'];
				$MailTo = $CustRes['email'];
				$Subject = " Kinjo Reset Password ";
				$ResetUrl = $_SERVER['SERVER_NAME'] . '/index.php/customers/ResetPassword?q=' . $QCode;
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
				$Res =  mail::SendMail($Subject,$Message,$MailTo,$To);
                            if($Res == 'Message has been sent'){
                                 $ResArr = array("Result" =>'TRUE');
                            }else{
			   					$ResArr = array("Result" =>'FALSE');
                            }

				//$ResArr = array("Result" => array('customer' => "Email Sended"));

			} else {

				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;

	}

	public static function actionSendNotify($Arr) {
		
		$Arr = CI_Security::ChkPost($Arr);
		
		$ResArr = array();

		if (isset($Arr) && !empty($Arr)) {
				
			$CustID = 0;
			if (isset($Arr['cust_id']) && $Arr['cust_id'] > 0) {
				
				$CustID = $Arr['cust_id'];
			}		
			if($CustID > 0){
				
				$GetCustSql = " SELECT * FROM customers WHERE cid = " . $CustID;
				$emailArr = Yii::app() -> db -> createCommand($GetCustSql) -> queryRow();
	
				$PushSql = "INSERT INTO push_notifications (cid,email,gcm_regid,gcm_devid)
								       VALUES(" .$CustID. ",
								       		  '" . $emailArr['email'] . "',
								       		  '" . $Arr['reg_id'] . "',
								       		  '" . $Arr['dev_id'] . "'
								       		 ) ";
	
				Yii::app() -> db -> createCommand($PushSql) -> execute();
	
				$PuID = Yii::app() -> db -> getLastInsertID();
	
				$SQLMess = " INSERT INTO messages_log (mid,cid,puid,is_group) VALUES (1," . $CustID . "," . $PuID . ", 1)";
	
				Yii::app() -> db -> createCommand($SQLMess) -> execute();
	
				$MessSql = " SELECT * FROM messages WHERE mid = 1 ";
				$MessRes = Yii::app() -> db -> createCommand($MessSql) -> queryRow();
				$Mess = $MessRes['message'];
	
				$Res = GCM::SendNotification(array($Arr['reg_id']), $Mess);
				$Res =	json_decode($Res,TRUE);
				
				if($Res['failure'] == '0'&& $Res['success'] > 0){
					
					$ResArr = array("Result" => "Send Notification");
					
				}else{
						
					$ResArr = array('error'=>array("Code"=>"208","Message"=>"Invalid Notification"));
				}
					
			
			}else{
				
				$ResArr = array('error'=>array("Code"=>"201","Message"=>"Invalid Customer"));
			}
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;

	}

	public static function actionEnableSendNotify($Arr)
	{
		 $Arr = CI_Security::ChkPost($Arr);
		
		 $ResArr = array();
		 
		 if (isset($Arr) && !empty($Arr)) {
		 	
			//$CustArr = $_POST['customer'];

			//$JsonArr = json_decode($CustArr);

			$CustID = 0;

			if (isset($Arr['cid'])) {

				if ($Arr['cid'] > 0) {$CustID = $Arr['cid'];}
			};
			$Hash = 0;

			if (isset($Arr['hash'])) {

				if ($Arr['hash'] != '') {$Hash = $Arr['hash'];}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
					$SQL = " UPDATE customers SET notify_enable = '".$Arr['str']."' WHERE cid = ".$CustID;
					Yii::app()->db->createCommand($SQL)->execute();
					
					$ResArr = array("Result"=>"Setting Updated");
			//}else{

				//$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
			//}		
			
		 } else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		return $ResArr;
		 
	}
	
	// ------------------------------- Reservations --------------------------------
	
	public static function actionGetAvUnitsByD($arr)		// ------ get availabe units by specific Date
	{
		$arr = CI_Security::ChkPost($arr);

		$ResArr = array();
		if (isset($arr) && !empty($arr)) {
			
			$Lang = 0;
			if (isset($arr['lang'])) {
				if ($arr['lang'] > 0) {$Lang = $arr['lang'];}
			};
			
			$w_persons = "";
			if(isset($arr['persons_no']) && !empty($arr['persons_no']) ){
				$w_persons = " AND bu_table_num_chairs =".$arr['persons_no'];
			}
			
			/*
			$SQL = "SELECT * 
					FROM bu_tables
					WHERE bu_table_id NOT IN( SELECT res_unit_id FROM reservations
											  WHERE res_ord_id =0 AND res_type =  'TA'
											  AND res_from >=  '2015-01-08 16:30:00' AND res_to <=  '2015-01-08 17:00:00' )";*/							 
			/*
			$SQL = "SELECT bu_table_id AS unit_id , bu_table_serial AS unit_serial , bu_table_num_chairs AS num_chairs 
								FROM bu_tables
								WHERE bu_table_id NOT 
								IN ( SELECT res_unit_id FROM reservations
									 WHERE res_ord_id =0 AND res_type =  '".$arr['res_type']."'
									 AND ('".$arr['from']."' BETWEEN res_from AND res_to) OR ('".$arr['to']."' BETWEEN res_from AND res_to) )" . $w_persons;*/
			/*
			$SQL = "SELECT bu_table_id AS unit_id , bu_table_serial AS unit_serial , bu_table_num_chairs AS num_chairs 			
					FROM bu_tables
					WHERE bu_table_buid = ".$arr['buid']." 
			 		AND bu_table_id NOT IN ( SELECT res_unit_id FROM reservations
											 WHERE res_ord_id =0 AND res_type =  'TA'
											 AND (res_from BETWEEN  '".$arr['from']."' AND res_to)   OR (res_from BETWEEN '".$arr['to']."' AND res_to) )";*/
			$SQL = "SELECT bu_table_id AS unit_id , bu_table_serial AS unit_serial , bu_table_num_chairs AS num_chairs 				
					FROM bu_tables
					WHERE bu_table_buid = ".$arr['buid']." 
					AND bu_table_id NOT IN ( SELECT res_unit_id FROM reservations
											 WHERE res_ord_id =0 AND res_type =  'TA'
											 AND res_canceled = 0
											 AND ( '".$arr['from']."' BETWEEN res_from AND res_to
											 OR ( '".$arr['to']."' BETWEEN res_from AND res_to))
                                                  )";
			$Res = Yii::app()->db->createCommand($SQL)->queryAll();
			
			$ResArr = array("Result" => array('Av_units' => $Res));
			
		}else{
			
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
	public static function actionGetAvTimeForUnit($arr)		// ------ get availabe Time For specific Unit(table or room ... etc)
	{
		$arr = CI_Security::ChkPost($arr);

		$ResArr = array();
		if (isset($arr) && !empty($arr)) {
		
			$SQL = "SELECT res_id, res_unit_id, res_cust_id, DATE_FORMAT( res_from,  '%H:%i' ) AS resFrom ,
						   DATE_FORMAT( res_to,  '%H:%i' ) AS resTo
					FROM reservations
					WHERE res_ord_id =0 AND res_type =  'TA' AND res_unit_id =201
					AND DATE( res_from ) =  '2015-01-08'
					AND DATE( res_to ) =  '2015-01-08'" ;
	
			$Res = Yii::app()->db->createCommand($SQL)->queryAll();
			$from = array(); $to = array();
			foreach ($Res as $key => $val) {
				array_push($from , $val['resFrom']);
				array_push($to , $val['resTo']);
			}
			
			
			$timeArr = array();
			$TimeRange = self::actionTimeRange();
			$AllRanges = $TimeRange;
			// for($i=0 ; $i<48 ; $i++){
				
				// if($i < sizeof($from)){
					//if(strtotime($TimeRange[$i]) <= strtotime($from[$i])){
						//echo '<pre/>';
						//print_r($TimeRange[$i]);
						//array_push($timeArr , $TimeRange[$i]);
					//}
				// }						
			// }
			
			// $myResult = array(); 
			// $x = 1;
			for($i = 0 ; $i < sizeof($from) ; $i++){
				$myTime = strtotime($from[$i]);
				
					while( $myTime < strtotime($to[$i]) ){
					
						// print_r(date("H:i",$myTime));
						
								
								
						// print_r(date("H:i",$myTime));
					//	$myResult[$x] = date("H:i",$myTime);
						
						
						if(($key = array_search(date("H:i",$myTime) , $TimeRange)) !== false) {
						    	
							// $myResult[$x] = date("H:i",$myTime);
							//echo '<pre/>';
							//print_r($myResult[$x]);
							//return;
						
							unset($TimeRange[$key]);
							// $x += 1;
						}
						
						$myTime = strtotime('+30 minutes', $myTime);				
					}
				
			}
			
			// self::actionDevideArr($TimeRange , $AllRanges);
			// $hh = self::actionGetAvIntervals($TimeRange);
			$hh = self::actionGetAvIntervals1($TimeRange);
			echo '<pre/>';
			print_r($hh);
			
			// $myResult[0] = reset($TimeRange);
			// array_push( $myResult, reset($TimeRange));
			// var_dump($TimeRange);
			
			// ksort($myResult);
			// var_dump($myResult);
			return;
			
			
	
			
			$ResArr = array("Result" => array('Av_time' => $Res));
				
			
		}else{
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
	
	public static function actionTimeRange(){
		//$h = 0;
		//while ($h < 24) {
		    //$key = date('H:i', strtotime(date('Y-m-d') . ' + ' . $h . ' hours'));
		    //$value = date('h:i A', strtotime(date('Y-m-d') . ' + ' . $h . ' hours'));
		    //$formatter[$key] = $value;
		    //$h++;
		//}
		//var_dump($formatter);
		
		
		$time   = strtotime('00.00'); //not important what the date is.
		//start adding 30mins
		$times      = array();
		$halfHour   = 60 * 30;
		$blocks     = 48; //adjust this to get the number of options
		$times[]  = date('H:i', $time);
		while ($blocks) {
		    //$times[]  = date('H:i', $time) . ' - ' . date('H:i a', ($time += $halfHour)); //I keep incrementing the time by half an hour
		    $times[]  = date('H:i', ($time += $halfHour)); //I keep incrementing the time by half an hour
		    $blocks--;
		}
		array_pop($times);
		//var_dump($times);
		//return;
		return $times;
	}


	public static function actionDevideArr($TimeRange , $AllRanges)
	{
		//var_dump($TimeRange);
		$keysArr = array_keys($TimeRange);
		echo "<pre>";
		print_r($keysArr);
		print_r($TimeRange);
		echo "</pre>";
	}	
	
	// extract and summarize continous ranges to min and max pairs from an array
	public static function actionGetAvIntervals($result){
		
		$result = array_keys($result);
		
		$last = end($result); // array_pop($result); // 47
		echo '<pre/>';
		print_r($result);
		return;
		
		$negative = array();
		$final = array();
	
		for($i = 0 ; $i <= $last ; $i++){
			$check = in_array($i,$result);
		  	if(!$check){  
				array_push($negative,$i);
		     }
		}
		
		echo '<pre/>';
		print_r($negative);
		//return;
		
		
		foreach ($negative as $key => $value) {
			if(isset($negative[$key+1]) && $negative[$key]+1 != $negative[$key+1] )
				array_push($final,range($negative[$key]+1, $negative[$key+1]-1));
		}
	
		//echo '<pre/>';
		//print_r($final);
		//return;
	
		$ff = array();
		foreach ($final as $value) {	
			$x=array();
			array_push($x,array_shift($value));
			array_push($x,array_pop($value));
			array_push($ff,$x);
		}
		return $ff;
	}
	
	
	
	public static function actionGetAvIntervals1($result){
		
		$result = array_keys($result); 
		/*
		$last=end($result);
				$negative[]=0;
				$final=array();
				for($i=0;$i<=$last;$i++){
				$check= in_array($i,$result);
							   if(!$check){  
				array_push($negative,$i);
							  }
				}
				
				foreach ($negative as $key => $value) {
				if(isset($negative[$key+1]) && $negative[$key]+1 != $negative[$key+1] )
				array_push($final,range($negative[$key]+1, $negative[$key+1]-1));
				
				}
				$ff=array();
				foreach ($final as $value) {	
				$x=array();
				array_push($x,array_shift($value));
				array_push($x,array_pop($value));
				array_push($ff,$x);
				}
		
				return $ff;*/
		
		
		$last = end($result);
		$negative[] = 0;
		$final = array();
		for($i = 0 ; $i <= $last ; $i++){
			$check= in_array($i,$result);
		  	if(!$check){  
				array_push($negative,$i);	 
		    }
			if($i == end($result)) array_push($negative,$i);
		}
		
		foreach ($negative as $key => $value) {
			if(isset($negative[$key+1]) && $negative[$key]+1 != $negative[$key+1] )
				array_push($final,range($negative[$key]+1, $negative[$key+1]-1));
		
		}
		
		$ff=array();
		foreach ($final as $value) {	
			$x=array();
			array_push($x,array_shift($value));
			array_push($x,array_pop($value));
			array_push($ff,$x);
		}

		return $ff;
	}
	
	
	public static function actionReserve($arr)
	{
		$arr = CI_Security::ChkPost($arr);

		$ResArr = array();

		if (isset($arr) && !empty($arr)) {
				
			$CustID = 0;
			if (isset($arr['cid']) && $arr['cid'] > 0 ) {
				$CustID = $arr['cid'];
				
				$Hash = 0;
				if (isset($arr['hash'])) {
					if ($arr['hash'] > 0) {$Hash = $arr['hash'];
					}
				};

				// if (Login::ChkCustomerHash($CustID, $Hash) == TRUE) {

					$Lang = 0;
					if (isset($arr['lang'])) {
						if ($arr['lang'] > 0) {$Lang = $arr['lang'];}
					};
	
					$Sql = "INSERT INTO reservations (res_unit_id , res_cust_id , res_from , res_to , res_type) 
							VALUES (".$arr['unitID']." , " . $CustID . " , '".$arr['from']."' , '".$arr['to']."' , '".$arr['res_type']."')";
					Yii::app()->db->createCommand($Sql)->execute();
					$ReservationID = Yii::app()->db->getLastInsertID();
					
					$ResArr = array('Success' => 'You Booked Your Table Succssfuly','ReservationID'=>$ReservationID );
					
					$CustSql = " SELECT * FROM customers WHERE cid = ".$CustID;
					$CustRow = Yii::app()->db->createCommand($CustSql)->queryRow();
					
					$Mess = array('ReservationID'=>$ReservationID,
								  'TableID'=>$arr['unitID'],
								  'CustID'=>$CustID,
								  'CustName'=>$CustRow['fname'].' '.$CustRow['lname']);
					CustLib::CpanelNotify($Mess);
					
				// } else {
					// $ResArr = array('error' => array("Code" => "20030", "message" => "Invalid Permission"));
				// }
			
			}else{
				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}
			
		} else {
			
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
	public static function actionCancelReserve($arr)
	{
		$arr = CI_Security::ChkPost($arr);

		$ResArr = array();

		if (isset($arr) && !empty($arr)) {
			$CustID = 0;
			if (isset($arr['cid']) && $arr['cid'] > 0 ) {
				$CustID = $arr['cid'];
				
				if($arr['CancelType'] == 'cust'){
					$SQL = " UPDATE reservations SET res_canceled = 1 WHERE res_id = ".$arr['ReservationID']." AND res_cust_id = ".$CustID;
					$xx = Yii::app()->db->createCommand($SQL)->execute();
					
					if($xx == 1){
						$ResArr = array('Success' => 'You Canceled Your Reservation Succssfuly');
					}else{
						$ResArr = array('error' => array("code" => "223", "message" => "Reservation Not Canceled") );
					}
					
				}
			} else {
				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}
			
		}else{
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
	public static function actionConfirmReserve($arr)
	{
		$arr = CI_Security::ChkPost($arr);

		$ResArr = array();

		if (isset($arr) && !empty($arr)) {
			
			$CustID = 0;
			if (isset($arr['cid']) && $arr['cid'] > 0 ) {
				$CustID = $arr['cid'];
			
				$SQL = " UPDATE reservations SET res_confirmed = 1 WHERE res_id = ".$arr['ReservationID']." AND res_cust_id = ".$CustID."
						 AND res_canceled=0";
				$res = Yii::app()->db->createCommand($SQL)->execute();
				
				if($res == 1){
					$ResArr = array('Success' => 'You Canceled Your Reservation Succssfuly' );
				}else{
					$ResArr = array('error' => array("code" => "223", "message" => "Reservation Not Canceled") );
				}
			
			
			}else{
				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}			
			
		}else{
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
	
	public static function actionGetAllUnits($arr){
			
		$arr = CI_Security::ChkPost($arr);

		$ResArr = array();
			
		if (isset($arr) && !empty($arr)) {	
			
			
			/*
			$WDates = "";
						if(isset($arr['from']) && !empty($arr['from'])  && isset($arr['to']) && !empty($arr['to']) ){
							$WDates = " AND bu_table_id NOT IN ( SELECT res_unit_id
																   FROM reservations
																   WHERE res_ord_id =0 AND res_type =  'TA'
																   AND (res_from BETWEEN  '".$arr['from']."' AND res_to)
																	OR (res_from BETWEEN '".$arr['to']."' AND res_to) )";
						}
						
				
						$SQL = "SELECT bu_table_id, bu_table_serial, bu_table_num_chairs, res_cust_id, res_from, res_to
								FROM bu_tables
								LEFT JOIN reservations ON res_unit_id = bu_table_id
								AND res_type =  'TA'
								AND res_ord_id =0
								WHERE bu_table_buid = ".$arr['buid'].$WDates."
								ORDER BY bu_table_id";
						$Res = Yii::app()->db->createCommand($SQL)->queryAll();*/
			
			$BuTSQL = "SELECT bu_table_id, bu_table_serial, bu_table_num_chairs
					  FROM bu_tables
					  WHERE bu_table_buid = ".$arr['buid'];
			
			
			$BuTData = Yii::app()->db->createCommand($BuTSQL)->queryAll();
			
			$TableArr = array();
			foreach ($BuTData as $Key => $val) {
				$SQL = "SELECT res_id , res_unit_id , res_cust_id , res_from , res_to , 
							   DATE_FORMAT(res_from,'%H:%i') FromTIME , DATE_FORMAT(res_to,'%H:%i') ToTIME
						FROM reservations
						WHERE res_unit_id =".$val['bu_table_id']." 
						AND res_type =  'TA' AND res_canceled =0 
						AND res_ord_id =0 AND DATE( res_from ) =  '".$arr['RDate']."'";
				$ResData = Yii::app()->db->createCommand($SQL)->queryAll();
				
				$ReservationArr = array();
				foreach($ResData as $key2 => $val2){
					array_push($ReservationArr , array('ReservID' =>$val2['res_id'],
														//'UnitID'=>$val2['res_unit_id'],
														'CustID'  =>$val2['res_cust_id'],
														'From'    =>$val2['res_from'],
														'To'      =>$val2['res_to'],
														'FromTIME'=>$val2['FromTIME'],
														'ToTIME'  =>$val2['ToTIME']));
				}
				array_push($TableArr , array('UnitID'=>$val['bu_table_id'],
											 'Serial'=>$val['bu_table_serial'],
											 'ChairNo'=>$val['bu_table_num_chairs'],
											 'Reservations'=>$ReservationArr));
					
			}
			
			//echo '<pre/>';
			//print_r($TableArr);return;
			
			$ResArr = array("units" => $TableArr);
			
		}else{
			
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
	
	public static function actionGetCustRese($arr)
	{
		$arr = CI_Security::ChkPost($arr);

		$ResArr = array();
		
		if (isset($arr) && !empty($arr)) {
			
			$SQL = " SELECT res_id , res_unit_id AS UnitID , res_from , res_to 
						FROM reservations
						WHERE res_canceled = 0 AND res_ord_id = 0
						AND res_cust_id =".$arr['cid']." AND res_type ='".$arr['res_type']."'";
			
			
			$Res = Yii::app()->db->createCommand($SQL)->queryAll();
			
			$ResArr = array("Result" => array('ResUnits' => $Res));
			
		}else{
			
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		return $ResArr;
	}
	
}

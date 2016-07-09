<?php

class APIsController extends Controller {
	
	public $layout=' ';
	
	public static $Distance  = 20000;
	
	function __construct()
	{
		$this->layout = "";
		
	}
	
	/*
	public function beforeAction() {
			$this->layout = "";AjaxChSpBillCurr
		}*/
	
	
	public function actionIndex() {

	}

	//----------------------------------------------------------------------------

	public function actionCallBack(){
		
	}
	
	public function actionCheckOutScript()
	{
		$this->renderPartial('//ordersDetails/checkoutscript');
	}
	
	public function actionGetTypes()
	{
		header('Content-Type: application/json');

		//////$_GET = CI_Security::ChkPost($_GET);
		$Lang = 0;

		if (isset($_GET['lang'])) {
			if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];}
		};
		
		if ($Lang != 0 && $Lang != 2) {
				
			$SQL = " SELECT type_id TypeID ,type_img AS TypeImg,
							(CASE WHEN type_lang_name IS NULL THEN type_name ELSE type_lang_name END) AS TypeName
					 FROM types 
					 LEFT JOIN types_lang ON type_lang_type_id = type_id AND type_lang_lang_id = ".$Lang;
		}else{
				
			$SQL = " SELECT type_id TypeID ,type_name AS TypeName,type_img AS TypeImg FROM types ";
		}
		
		$Data = Yii::app()->db->createCommand($SQL)->queryAll();
                var_dump($Data);

		$ResArr = array();

		$RealArr = Globals::ReturnGlobals();
		$RealPath = $RealArr['ImgSerPath'] . 'types/';

		foreach ($Data as $key => $row) {
			
			array_push($ResArr, array('TypeID' => $row['TypeID'], 
									  'TypeName' => $row['TypeName'], 
									  'TypeImg' =>  $RealPath.$row['TypeImg']));
		}
		
		$ResArr = array('Types'=>$ResArr);
		
		echo json_encode($ResArr);
	}
	
	public function actionGetAllStores() {
			
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$t = 0;

		if (isset($_GET['t'])) {

			if ($_GET['t'] > 0) {$t = $_GET['t'];}
		};
		
		$WhrT = "";	
		if($t > 0){
			
			$WhrT =	" AND type =  ".$t;
		}	
		
		
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
		
		$BuAcc = 0 ;
		if (isset($_GET['BuAcc'])) {

			if ($_GET['BuAcc'] > 0) {$BuAcc = $_GET['BuAcc'];}
		};
		
		$WhrAcc = ' ';
		
		if($BuAcc > 0){
				
			$WhrAcc = " AND buid IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
		}
		
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
								        ) as BUDist,
								    IFNULL((SELECT value FROM bu_rating WHERE bu_rating.buid = business_unit.buid AND cid = '.$CustID.' LIMIT 0,1),0)AS CustRate,
								    IFNULL((SELECT COUNT(DISTINCT cid) FROM bu_rating WHERE bu_rating.buid = business_unit.buid LIMIT 0,1),0)AS CountRate								  
								FROM business_unit 
								LEFT JOIN business_unit_lang ON bu_lang_bu_id = buid AND bu_lang_lang_id = ' . $Lang.'
								WHERE active = 0 '.$WhrT.' '.$WhrAcc.' HAVING BUDist < '.$Dist . $OrdStr;
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
							        ) as BUDist,
			        			    IFNULL((SELECT value FROM bu_rating WHERE bu_rating.buid = business_unit.buid AND cid = '.$CustID.' LIMIT 0,1),0)AS CustRate,
								    IFNULL((SELECT COUNT(DISTINCT cid) FROM bu_rating WHERE bu_rating.buid = business_unit.buid LIMIT 0,1),0)AS CountRate	   							  
							   FROM business_unit 
							   WHERE active = 0 '.$WhrT.' '.$WhrAcc.' HAVING BUDist < '.$Dist;
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
					$IsReservedBu = Orders::IsReservedBu($row['buid'],$CustID);
					
					array_push($Arr, array('id' => $row['buid'], 
										   'title' => $row['title'],
										   'description' => $row['description'], 
										   'logo_url' => $RealPath . $row['logo'], 
										   'icon_marker' => $RealPath .'icons/'. $row['urlid'], 
										   'gps' => array('lat' => $row['lat'], 'long' => $row['long']), 
										   'rate' => $row['rating'], 
										   'CustRate' => $row['CustRate'], 
										   'CountRate' => $row['CountRate'], 
										   'subscribers' => $row['SubscripCount'], 
										   'type' => $row['type'],
										   'IsReserved'=>$IsReservedBu,
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
					
			//	$ResArr = array('error'=>array("code"=>"202","message"=>"UnKnown Location"));
				
			//}
		//}else{

		//	$ResArr = array('error'=>array("code"=>"203","message"=>"Invalid Permission"));
		//}

		echo json_encode($ResArr);
	}

	public function actionGetAllNearStores() {
			
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
	
		$ResArr = array();
		
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

		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
			$Long = '0';
			// $Long = '29.91174476562503';
			if (isset($_GET['Long'])) {
	
				if ($_GET['Long'] != '') {$Long = $_GET['Long'];}
			};
			
			$Lat = '0';
			// $Lat = '31.225933942095836';
			if (isset($_GET['Lat'])) {
				if ($_GET['Lat'] != '') {$Lat = $_GET['Lat'];}
			};
			
			$Dist = self::$Distance;
	
			if (isset($_GET['Dist'])) {
	
				if ($_GET['Dist'] > 0) {$Dist = $_GET['Dist'];}
			};	
	
			$Lang = 0;
	
			if (isset($_GET['lang'])) {
	
				if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];}
			};
	
			$Order = Yii::app() -> getRequest() -> getQuery('id');
	
			$OrdStr = ' ';
	
			$Order = isset($Order) ? $Order : 0;
	
			if ($Order == '1') {
	
				$OrdStr = ' ORDER BY TotalOrders DESC ';
			}
			if ($Lang != 0 && $Lang != 2) {
				$Sql = 'SELECT  buid,
								IFNULL((CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END),"") AS title,
								IFNULL(`long`,"") AS `long`,IFNULL(lat,"") AS lat,IFNULL(logo,"") AS logo,type,apiKey,rating,urlid,
								(SELECT count(sid) FROM subscriptions WHERE subscriptions.buid = business_unit.buid) AS SubscripCount,
								(SELECT count(pid) FROM products WHERE products.buid = business_unit.buid AND csid IS NULL) AS Items	,
								(SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id IS NULL) AS Cat,
								(SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id > 0) AS CatSub	,
								IFNULL((SELECT sum(final_price) FROM orders_details WHERE orders_details.ord_buid = business_unit.buid),0)AS TotalOrders							  
						FROM business_unit 
						LEFT JOIN business_unit_lang ON bu_lang_bu_id = buid AND bu_lang_lang_id = ' . $Lang . ' 
						WHERE active = 0 AND type = '.$t.'
						AND lat BETWEEN  (' .$Lat. ' - (1.0 / 111.045)) AND (' .$Lat. ' + (50.0 / 111.045))
						AND `long` BETWEEN (' .$Long. ' - (1.0 / (111.045 * COS(RADIANS(' .$Lat. '))))) AND (' .$Long. ' + (50.0 / (111.045 * COS(RADIANS(' .$Lat. '))))) 
						' . $OrdStr;
		} else {
	
				$Sql = 'SELECT buid,IFNULL(title,"") AS title,IFNULL(`long`,"") AS `long`,IFNULL(lat,"")AS lat,IFNULL(logo,"")AS logo,type,apiKey,rating,urlid,
							   (SELECT count(sid) FROM subscriptions WHERE subscriptions.buid = business_unit.buid) AS SubscripCount,
							   (SELECT count(pid) FROM products WHERE products.buid = business_unit.buid AND csid IS NULL) AS Items	,
							   (SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id IS NULL) AS Cat,
							   (SELECT count(pid) FROM products LEFT JOIN catsub ON products.csid = catsub.csid WHERE products.buid = business_unit.buid AND products.csid > 0 AND catsub.parent_id > 0) AS CatSub,
							   IFNULL((SELECT sum(final_price) FROM orders_details WHERE orders_details.ord_buid = business_unit.buid),0)AS TotalOrders							  
						FROM business_unit 
						WHERE active = 0  AND type = '.$t.'
						AND lat BETWEEN  (' .$Lat. ' - (1.0 / 111.045)) AND (' .$Lat. ' + (50.0 / 111.045))
						AND `long` BETWEEN (' .$Long. ' - (1.0 / (111.045 * COS(RADIANS(' .$Lat. '))))) AND (' . $Long. ' + (50.0 / (111.045 * COS(RADIANS(' .$Lat. ')))))
						' . $OrdStr;

			}
	
			$Data = Yii::app() -> db -> createCommand($Sql) -> queryAll();
	
			$Arr = array();
	
			foreach ($Data as $key => $row) {
					
				//------------------------Screens
				$screens = '';
	
				if ($row['Items'] > 0) {$screens = 'items';}
				if ($row['Cat'] > 0) {$screens = 'cat_items';}
				if ($row['CatSub'] > 0) {$screens = 'cat_sub_items';}
				//------------------------Cats
				$CatArr = array();
				$CatSql = "	SELECT csid,parent_id,
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
						if (count($CatSData) > 0) {
	
							$CatSArr = array();
	
							foreach ($CatSData as $Catskey => $CatsRow) {
								array_push($CatSArr, array('id' => $CatsRow['csid'], 'name' => $CatsRow['title']));
							}
	
							array_push($CatArr, array('id' => $CatRow['csid'], 'name' => $CatRow['title'], 'subs' => $CatSArr));
						} else {
	
							array_push($CatArr, array('id' => $CatRow['csid'], 'name' => $CatRow['title']));
						}
	
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
									   'logo_url' => $_SERVER['SERVER_NAME'] . '/images/upload/business_unit/' . $row['logo'],
									   'icon_marker' => $RealPath .'icons/'. $row['urlid'], 
									   'gps' => array('lat' => $row['lat'], 'long' => $row['long']), 
									   'rate' => $row['rating'], 'subscribers' => $row['SubscripCount'], 
									   'type' => $row['type'], 'apikey' => $row['apiKey'], 
									   'screens' => $screens, 'TotalOrders' => $row['TotalOrders'], 
									   'cats' => $CatArr,
									   'contacts' => $ContactArr
									   ));
	
			}
	
			$ResArr = array('stores' => $Arr);

		//}else{

		//	$ResArr = array('error'=>array("code"=>"203","message"=>"Invalid Permission"));
		//}

		echo json_encode($ResArr);
	}

	public function actionGetBuData() {
		
		header('Content-Type: application/json');
		
		$BuID = 0;

		if (isset($_GET['BuID'])) {

			if ($_GET['BuID'] > 0) {$BuID = $_GET['BuID'];}
		};
			
		$Lang = 0;

		if (isset($_GET['lang'])) {

			if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];}
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
			//if($Lat != 0 || $Long != 0 ){
					
				if ($Lang != 0 && $Lang != 2) {
					
					$BuSql = " SELECT buid AS buss_id, accid,`long` , `lat` ,type , logo,currrency_symbol,
								   (CASE WHEN bu_lang_title IS NULL THEN title ELSE bu_lang_title END) As buss_name
							   FROM business_unit 
							   LEFT JOIN country ON business_unit.currency_code = country.currency_code
							   LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = " . $Lang."
							   WHERE business_unit.active = 0 AND buid = ".$BuID;
					
				}else{
					
					$BuSql = " SELECT buid AS buss_id, accid, title AS buss_name, `long` , `lat` ,type , logo , currrency_symbol
							   FROM business_unit 
							   LEFT JOIN country ON business_unit.currency_code = country.currency_code
							   WHERE business_unit.active = 0 AND buid = ".$BuID;
				}
					
				$BuRow = Yii::app()->db->createCommand($BuSql)->queryRow();
			
				if(!empty($BuRow)){
					
					
					if ($Lang != 0 && $Lang != 2) {
		
						$ProSql = "SELECT 
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
									 (CASE WHEN SubScrip.sid IS NOT NULL THEN 'True' ELSE 'False' END) AS SubScripID
								FROM AllProductsData
								LEFT JOIN products_lang ON p_lang_pid = ProdID AND p_lang_lang_id = " . $Lang . "
								LEFT JOIN catsub_lang AS ParLang ON ParLang.cat_lang_cs_id = ParCatID AND ParLang.cat_lang_lang_id = " . $Lang . "
								LEFT JOIN catsub_lang AS SubLang ON SubLang.cat_lang_cs_id = SubCatID AND SubLang.cat_lang_lang_id = " . $Lang . "
								LEFT JOIN wishlist ON ProdID = wl_pid AND wl_cid =".$CustID."
								LEFT JOIN subscriptions AS ParScrip ON ParCatID = ParScrip.csid AND ParScrip.cid = ".$CustID."
								LEFT JOIN subscriptions AS SubScrip ON SubCatID = SubScrip.csid AND SubScrip.cid = ".$CustID."
								LEFT JOIN offers ON ProdID = offers.pid AND offers.active = 1
								WHERE AllProductsData.BUID = " . $BuRow['buss_id'];
					
					} else {
					
						$ProSql = " SELECT 
									  ProdID,ProdName,ProdDesc,ProdPrice,ProdBarcode,ProdRate,ProdQrcode,ProdNfc,ProdHash,ProdBookable,
									  ParCatID,ParCatName,ParCatDesc,ParCatImg,
									  SubCatID,SubCatName,SubCatDesc,SubCatImg,
									  CASE WHEN wl_id IS NOT NULL THEN 'True' ELSE 'False' END wl_id  ,
									  IFNULL(offers.discount , 0) AS off_discount,
									 (CASE WHEN ParScrip.sid IS NOT NULL THEN 'True' ELSE 'False' END) AS ParScripID,
									 (CASE WHEN SubScrip.sid IS NOT NULL THEN 'True' ELSE 'False' END) AS SubScripID
								 FROM AllProductsData
								 LEFT JOIN wishlist ON ProdID = wl_pid AND wl_cid = ".$CustID."
								 LEFT JOIN subscriptions AS ParScrip ON ParCatID = ParScrip.csid AND ParScrip.cid = ".$CustID."
								 LEFT JOIN subscriptions AS SubScrip ON SubCatID = SubScrip.csid AND SubScrip.cid = ".$CustID."
								 LEFT JOIN offers ON ProdID = offers.pid AND offers.active = 1 
								 WHERE AllProductsData.BUID = " . $BuRow['buss_id'];
											
				
					}
					$ProData = Yii::app()-> db->createCommand($ProSql) -> queryAll();
		
					$ItemsArr = array();
					
					$RealAdrr = Globals::ReturnGlobals();
					$img_path = $RealAdrr['ImgSerPath'];
					
					foreach ($ProData as $ProKey => $ProRow) {
							
						//--------------------Img	
						$ImgSql = "SELECT pimgid, pimg_url
							   	 	    FROM products_imgs JOIN products 
							   	 	    ON products_imgs.pid = products.pid
							   	 	    WHERE products_imgs.pid = " . $ProRow['ProdID'];
						// ."LIMIT 1 "
						$ImgData = Yii::app() -> db -> createCommand($ImgSql) -> queryAll();
						$ImgArr = array();
						foreach ($ImgData as $Imgkey => $Imgrow) {
							array_push($ImgArr, array('imgThmb' => $img_path . 'products/' . $Imgrow['pimg_url'], 
													  'img' => $img_path . 'products/' . $Imgrow['pimg_url']));
						}
						
						//--------------------Config
						if ($Lang != 0 && $Lang != 2) {
							$ConfigSql = "SELECT cfg_id ,pdconfv_id, pdconfv_value, pdconfv_chkrad,
									   	 		(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name , 
												(CASE pdconfv_chkrad
													WHEN 1 THEN 'TRUE'
													WHEN 0 THEN 'FALSE'
												END) as pdconfv_chkrad
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid	
										 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "	
										 WHERE pdconfv_pid =" . $ProRow['ProdID'] . "
										 AND parent_id IS NULL ";
						} else {
							$ConfigSql = "SELECT cfg_id , pdconfv_id, pdconfv_value, pdconfv_chkrad, name , 
												(CASE pdconfv_chkrad
					        						WHEN 1 THEN 'TRUE'
					        						WHEN 0 THEN 'FALSE'
					   							END) as pdconfv_chkrad
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid
										 WHERE pdconfv_pid =" . $ProRow['ProdID'] . "
										 AND parent_id IS NULL ";
		
						}
						
						$ConfigData = Yii::app() -> db -> createCommand($ConfigSql) -> queryAll();
						$ConfigArr = array();
						
						foreach ($ConfigData as $Configkey => $Configrow) {
								
							$SubConfigArr = array();
							if ($Lang != 0 && $Lang != 2) {
								$SubConfigSql = "SELECT pdconfv_id, pdconfv_value, 
														(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name 
												 FROM pd_conf_v
												 JOIN pd_config ON cfg_id = pdconfv_confid
												 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "
												 WHERE pdconfv_pid = ".$ProRow['ProdID']." AND parent_id = " . $Configrow['cfg_id'];
							} else {
								$SubConfigSql = "SELECT pdconfv_id, pdconfv_value, name
												 FROM pd_conf_v
												 JOIN pd_config ON cfg_id = pdconfv_confid
												 WHERE pdconfv_pid = ".$ProRow['ProdID']."  AND parent_id = " . $Configrow['cfg_id'];
							}
							$SubConfigData = Yii::app() -> db -> createCommand($SubConfigSql) -> queryAll();
							
							foreach ($SubConfigData as $SubConfigkey => $SubConfigrow) {
								
								array_push($SubConfigArr, array('subConf' => $SubConfigrow['name'], 'val' => $SubConfigrow['pdconfv_value']));
							}
							array_push($ConfigArr, array('conf' => $Configrow['name'], 'check' => $Configrow['pdconfv_chkrad'], 'subConfig' => $SubConfigArr));
		
						}
						//------------------Color
						if ($Lang != 0 && $Lang != 2) {
							
							$ColorSql = " SELECT color_id,color_code,
										   (CASE WHEN color_lang_name IS NULL THEN color_name ELSE color_lang_name END)AS color_name 
									   	  FROM prod_colors
									      LEFT JOIN prod_colors_lang ON color_lang_color_id = color_id AND color_lang_lang_id = " . $Lang . "
									   	  WHERE color_pid = " . $ProRow['ProdID'];
						} else {
							$ColorSql = " SELECT color_id, color_name ,color_code FROM prod_colors
							   		 	  WHERE color_pid = " . $ProRow['ProdID'];
		
						}
		
						$ColorData = Yii::app() -> db -> createCommand($ColorSql) -> queryAll();
						$ColorArr = array();
						
						foreach ($ColorData as $Colorkey => $Colorrow) {
							
							array_push($ColorArr, array('color_id' => $Colorrow['color_id'], 'color_name' => $Colorrow['color_name'], 'color_code' => '#' . $Colorrow['color_code']));
						}
						if($ProRow['ParCatID'] == null){$ProRow['ParCatID'] = '0';}
						if($ProRow['ParCatName'] == null){$ProRow['ParCatName'] = 'noncategorized';}
						
						array_push($ItemsArr, array('pid' => $ProRow['ProdID'], 
												 'pro_name' => $ProRow['ProdName'], 
												 'price' => $ProRow['ProdPrice'], 
												 'qrcode' => $ProRow['ProdQrcode'], 
												 'nfc' => $ProRow['ProdNfc'], 
												 'hash' => $ProRow['ProdHash'], 
												 'bookable' => $ProRow['ProdBookable'], 
												 'wishList'=>$ProRow['wl_id'] ,
												 'off_discount'=>$ProRow['off_discount'],
												 'discription' => $ProRow['ProdDesc'], 
												 'rate' => $ProRow['ProdRate'], 
												 'CatScrip'=>$ProRow['ParScripID'] ,
												 'catId' => $ProRow['ParCatID'], 
												 'catTitle' => $ProRow['ParCatName'], 
												 'SubScrip'=>$ProRow['SubScripID'] ,
												 'subId' => $ProRow['SubCatID'], 
												 'subTitle' => $ProRow['SubCatName'], 
												 'catImg' => $img_path . 'catsub/' . $ProRow['ParCatImg'], 
												 'subImg' => $img_path . 'catsub/' . $ProRow['SubCatImg'], 
												 'pro_imgs' => $ImgArr, 
												 'Config' => $ConfigArr, 
												 'colors' => $ColorArr));
						
					}
					
					array_push($ResArr, array('id' => $BuRow['buss_id'], 
											  'buss_name' => $BuRow['buss_name'], 
											  'curr_symbol'=>$BuRow['currrency_symbol'] ,
											  'logo_url' => $BuRow['logo'], 
											  'gps' => array('lat' => $BuRow['lat'], 'long' => $BuRow['long']), 
											  'items' => $ItemsArr));
											  
					$ResArr = array('BU'=>$ResArr);
					
				}else{
					
					$ResArr = array('error'=>array("Code"=>"210","message"=>" UnKnown Bu "));
				}
				
			//} else {
					
			//	$ResArr = array('error'=>array("code"=>"202","message"=>"UnKnown Location"));
			//}
		//}else{

			//$ResArr = array('error'=>array("code"=>"203","message"=>"Invalid Permission"));
		//}	
		
		echo json_encode($ResArr);
	}
	
	//--------------------- Subcription ---------------------

	public function actionGetSubcription() {
			
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$t = 0;

		if (isset($_GET['t'])) {

			if ($_GET['t'] > 0) {$t = $_GET['t'];}
		};
		
		$WhrT = "";	
		if($t > 0){
			$WhrT = " AND BUType = ".$t;
		}	
			
		$CustID = 0;

		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];}
		};

		$Hash = 0;

		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];}
		};
		
		$BuAcc = 0 ;
		if (isset($_GET['BuAcc'])) {

			if ($_GET['BuAcc'] > 0) {$BuAcc = $_GET['BuAcc'];}
		};
		
		$WhrAcc = ' ';
		
		if($BuAcc > 0){
				
			$WhrAcc = " AND BUID IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
		}
		
		$ResArr = array();

		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){

		if ($CustID > 0) {

			//	$SubArr = $_POST['subcrip'];

			//	$JsonArr = json_decode($SubArr);

			$Lang = 0;

			if (isset($_GET['lang'])) {

				if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];
				}
			};

			if ($Lang != 0 && $Lang != 2) {
				
				$Sql = " SELECT CatAndCatSub.BUID AS BUID,ParCatID,SubCatID,ParCatImg,SubCatImg,
							   (CASE WHEN bu_lang_title IS NULL THEN BUName ELSE bu_lang_title END)AS BUName,
							   (CASE WHEN ParLang.cat_lang_title IS NULL THEN ParCatName ELSE ParLang.cat_lang_title END)AS ParCatName,
							   (CASE WHEN SubLang.cat_lang_title IS NULL THEN SubCatName ELSE SubLang.cat_lang_title END)AS SubCatName		
						FROM subscriptions 
						LEFT JOIN CatAndCatSub ON  BCatID = csid
						LEFT JOIN business_unit_lang ON bu_lang_bu_id = CatAndCatSub.BUID AND bu_lang_lang_id = " . $Lang . "
						LEFT JOIN catsub_lang AS ParLang ON ParLang.cat_lang_cs_id = ParCatID AND ParLang.cat_lang_lang_id = " . $Lang . "
						LEFT JOIN catsub_lang AS SubLang ON SubLang.cat_lang_cs_id = SubCatID AND SubLang.cat_lang_lang_id = " . $Lang . "
						WHERE subscriptions.cid = " . $CustID." AND SubCatID = '' AND BUActive = 0 ".$WhrT." ".$WhrAcc." ";
			
			} else {

				$Sql = " SELECT CatAndCatSub.BUID AS BUID,BUName,ParCatID,ParCatName,SubCatID,SubCatName,ParCatImg,SubCatImg		
						 FROM subscriptions 
						 LEFT JOIN CatAndCatSub ON BCatID = csid
				 		 WHERE subscriptions.cid = " . $CustID." AND SubCatID = '' AND BUActive = 0 ".$WhrT." ".$WhrAcc." ";
			}

			$Data = Yii::app() -> db -> createCommand($Sql) -> queryAll();
			
			$RealArr = Globals::ReturnGlobals();
			$RealPath = $RealArr['ImgSerPath'] . 'catsub/thumbnails/';
			
			$SubcripArr = array();
			foreach ($Data as $key => $row) {
					
				$SubCatArr = array();
				
				if ($row['SubCatID'] == "") {
						
					if ($Lang != 0 && $Lang != 2) {
						
						$SubSQL= " SELECT title AS SubCatName , subscriptions.csid AS SubCatID , img_thumb AS SubCatImg
								   FROM subscriptions  
								   LEFT JOIN catsub ON subscriptions.csid = catsub.csid
								   WHERE parent_id = ".$row['ParCatID'];	
						
					}else{
							
						$SubSQL= " SELECT (CASE WHEN cat_lang_title IS NULL THEN title ELSE cat_lang_title END) AS SubCatName ,
									       subscriptions.csid AS SubCatID , img_thumb AS SubCatImg
								   FROM subscriptions  
								   LEFT JOIN catsub 
								   		LEFT JOIN catsub_lang ON cat_lang_cs_id = csid AND cat_lang_lang_id = ".$Lang."
								   ON subscriptions.csid = catsub.csid
								   WHERE parent_id = ".$row['ParCatID'];
					}	
					$SubData = Yii::app() -> db -> createCommand($SubSQL) -> queryAll();
					
					foreach ($SubData as $Subkey => $Subrow) {
							
						array_push($SubCatArr, array('SubCatID' => $Subrow['SubCatID'],
													 'SubCatName' => $Subrow['SubCatName'],
													 'SubCatImg' => $RealPath.$Subrow['SubCatImg']));
					}
				
				}else{
						
					array_push($SubCatArr, array('SubCatID' => $row['SubCatID'],
												 'SubCatName' => $row['SubCatName'],
												 'SubCatImg' => $RealPath.$row['SubCatImg']));
				}
				array_push($SubcripArr, array('BUID' => $row['BUID'], 
											  'BUName' => $row['BUName'], 
											  'ParCatName' => $row['ParCatName'], 
											  'ParCatID' => $row['ParCatID'],
											  'ParCatImg' => $RealPath.$row['ParCatImg'], 
											  'Subs' => $SubCatArr));

			}

			$ResArr = array('subscriptions' => $SubcripArr);
			
		} else {

			$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
		}

		//}else{

		//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}

		echo json_encode($ResArr);

	}

	public function actionAddSubcription() {
	
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		/*
		 $Arr = '{
		 "cid": "1",
		 "cs_id": "17"
		 }';*/
		$ResArr = array();

		if (isset($_POST['subcrip'])) {

			$SubArr = $_POST['subcrip'];

			$JsonArr = json_decode($SubArr);

			$CustID = 0;

			if (isset($JsonArr -> cid)) {

				if ($JsonArr -> cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr -> hash)) {

				if ($JsonArr -> hash > 0) {$Hash = $JsonArr -> hash;}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){

				$ChkSql = "SELECT * FROM subscriptions WHERE cid = " . $CustID . " AND csid = " . $JsonArr -> cs_id;
				$ChkRow = Yii::app() -> db -> createCommand($ChkSql) -> queryRow();
				
				if (empty($ChkRow)) {
					
					$If_P_Sql = "SELECT * FROM catsub WHERE csid = " . $JsonArr -> cs_id;
					$If_P_Row = Yii::app() -> db -> createCommand($If_P_Sql) -> queryRow();
					
					if (!empty($If_P_Row)) {
							
						if($If_P_Row['parent_id'] > 0){
							
							//------- Sub Cat
							
							$Sql = "INSERT INTO subscriptions (buid,cid,csid) VALUES
									(".$If_P_Row['catsub_buid'].",".$CustID.",".$JsonArr->cs_id.")";
									
							$Par_Sql = "SELECT * FROM subscriptions WHERE cid = " . $CustID . " AND csid = " .$If_P_Row['parent_id'];
							$Par_Row = Yii::app() -> db -> createCommand($Par_Sql) -> queryRow();
							
							if (empty($Par_Row)) {
								
								$Ins_p_Sql = "INSERT INTO subscriptions (buid,cid,csid) VALUES
									  (".$If_P_Row['catsub_buid'].",".$CustID.",".$If_P_Row['parent_id'].")";
								Yii::app() -> db -> createCommand($Ins_p_Sql) -> execute();	
							}
							
						} else {
							
							//------- Parent Cat	
							$Sql = "INSERT INTO subscriptions (buid,cid,csid)
									SELECT catsub_buid," . $CustID . ",csid
									FROM catsub
									WHERE csid = ".$JsonArr -> cs_id." OR parent_id = ".$JsonArr -> cs_id;
						}
						
						$ResRow = Yii::app() -> db -> createCommand($Sql) -> execute();
						
						if($ResRow > 0){
							
							$ResArr = array('Result' => 'TRUE');
							
						}else{
							
							$ResArr = array('error' => array("code" => "211", "message" => " Try Again "));
						}
					
					} else {
						
						$ResArr = array('error' => array("code" => "212", "message" => "Invalid Category "));
					}
					
				
				} else {
	
					$ResArr = array('error' => array("code" => "209", "message" => "Added Before"));
				}

			//}else{

			//	$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
			//}

		} else {

			$ResArr = array('error' => array("code" => "202", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);
	}

	public function actionRemoveSubcription() {
	
		header('Content-Type: application/json');
		
		//$_POST = CI_Security::ChkPost($_POST);
		
		/*
		 $Arr = 'subcrip={
		 "cid": "1",
		 "cs_id": "17"
		 }';*/
		$ResArr = array();

		if (isset($_POST['subcrip'])) {

			$SubArr = $_POST['subcrip'];

			$JsonArr = json_decode($SubArr);

			$CustID = 0;

			if (isset($JsonArr -> cid)) {

				if ($JsonArr -> cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr -> hash)) {

				if ($JsonArr -> hash > 0) {$Hash = $JsonArr -> hash;}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
							
					$If_P_Sql = "SELECT * FROM catsub WHERE csid = " . $JsonArr -> cs_id;
					$If_P_Row = Yii::app() -> db -> createCommand($If_P_Sql) -> queryRow();	
					
					if (!empty($If_P_Row)) {
							
						if($If_P_Row['parent_id'] > 0){
							
							//------- Sub Cat
							$DelSql = " DELETE FROM subscriptions WHERE cid = ".$CustID." AND csid = " . $JsonArr -> cs_id ;
							//------- Del Parent
							$Par_Sql = "SELECT subscriptions.csid 
										FROM subscriptions 
										LEFT JOIN catsub ON subscriptions.csid = catsub.csid
										WHERE cid = " . $CustID . " AND parent_id = " .$If_P_Row['parent_id'];
										
							$Par_Row = Yii::app() -> db -> createCommand($Par_Sql) -> queryAll();
							
							if (count($Par_Row)== 0) {
									
								$Del_p_Sql = " DELETE FROM subscriptions WHERE cid = ".$CustID." AND csid = " . $If_P_Row['parent_id'] ;	  
								Yii::app() -> db -> createCommand($Del_p_Sql) -> execute();	
							}
						}else{
							
							//------- Parent Cat
							$DelSql = " DELETE FROM subscriptions 
										WHERE cid = ".$CustID." AND (csid = " . $JsonArr -> cs_id ."
										OR csid IN (SELECT csid FROM catsub WHERE parent_id = " . $JsonArr -> cs_id."))";
							
						}	
						
						$DelRes = Yii::app()->db->createCommand($DelSql)->execute();
						
						if($DelRes > 0){
							
							$ResArr = array("Result" => array('val' => 'TRUE'));
							
						}else{
							
							$ResArr = array('error' => array("code" => "211", "message" => " Try Again "));
						}
					
					} else {
						
						$ResArr = array('error' => array("code" => "212", "message" => "Invalid Category "));
					}
			//}else{
				

			//	$ResArr = array('error'=>array("code"=>"203","Message"=>"Invalid Permission"));
			//}

		} else {

			$ResArr = array('error' => array("code" => "202", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);
	}
	
	public function actionGetProductFeeds() {
			
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$t = 0;

		if (isset($_GET['t'])) {

			if ($_GET['t'] > 0) {$t = $_GET['t'];}
		};
		
		$WhrT = "";
		
		if($t > 0){
			$WhrT = " AND BUType = ".$t;
		}
		
		$CustID = 0;

		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];
			}
		};

		$Hash = 0;

		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];
			}
		};

		$BuAcc = 0 ;
		if (isset($_GET['BuAcc'])) {

			if ($_GET['BuAcc'] > 0) {$BuAcc = $_GET['BuAcc'];}
		};
		
		$WhrAcc = ' ';
		
		if($BuAcc > 0){
				
			$WhrAcc = " AND CatAndCatSub.BUID IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
		}
		
		$ResArr = array();

		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
			
			if ($CustID > 0) {
				//if(isset($_POST['customer'])){
	
				//$CustArr = $_POST['customer'];
	
				$Lang = 0;
	
				if (isset($_GET['lang'])) {
	
					if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];
					}
				};
	
				if ($Lang != 0 && $Lang != 2) {
					
					$Sql = " SELECT (CASE WHEN bu_lang_title IS NULL THEN BUName ELSE bu_lang_title END) AS BUName,
								   subscriptions.buid AS BUID ,ParCatID,SubCatID,
								   (CASE WHEN ParLang.cat_lang_title IS NULL THEN ParCatName ELSE ParLang.cat_lang_title END) AS ParCatName,
								   (CASE WHEN SubLang.cat_lang_title IS NULL THEN SubCatName ELSE SubLang.cat_lang_title END) AS SubCatName,
								   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProdName ,
								   (CASE WHEN p_lang_price IS NULL THEN products.price ELSE p_lang_price END) AS ProdPrice ,
								   products.pid AS ProdID,subscriptions.csid AS subscrip_csid,
								   CASE WHEN wl_id IS NOT NULL THEN 'True' ELSE 'False' END wl_id  ,
								   IFNULL(offers.discount , 0) AS off_discount,BUCurrency
							FROM subscriptions
							LEFT JOIN CatAndCatSub ON BCatID = subscriptions.csid
							LEFT JOIN business_unit_lang ON bu_lang_bu_id = CatAndCatSub.BUID AND bu_lang_lang_id = " . $Lang . "
							LEFT JOIN catsub_lang AS ParLang ON ParLang.cat_lang_cs_id = ParCatID AND ParLang.cat_lang_lang_id = " . $Lang . "
							LEFT JOIN catsub_lang AS SubLang ON SubLang.cat_lang_cs_id = SubCatID AND SubLang.cat_lang_lang_id = " . $Lang . "
							LEFT JOIN products 
								 LEFT JOIN wishlist ON products.pid = wl_pid AND wl_cid =".$CustID."
								 LEFT JOIN offers ON products.pid = offers.pid AND offers.active = 1
							ON products.csid = subscriptions.csid AND products.buid = subscriptions.buid
							WHERE subscriptions.cid = " . $CustID ." AND BUActive = 0 ".$WhrT." ".$WhrAcc."";
				} else {
					$Sql = " SELECT BUName,subscriptions.buid AS BUID ,ParCatID,ParCatName,SubCatID,SubCatName,
								    products.title AS ProdName ,products.pid AS ProdID,products.price AS ProdPrice,subscriptions.csid AS subscrip_csid,
								    CASE WHEN wl_id IS NOT NULL THEN 'True' ELSE 'False' END wl_id  ,
								    IFNULL(offers.discount , 0) AS off_discount,BUCurrency
							 FROM subscriptions
							 LEFT JOIN CatAndCatSub ON BCatID = subscriptions.csid
							 LEFT JOIN products 
							 	  LEFT JOIN wishlist ON products.pid = wl_pid AND wl_cid =".$CustID."
								  LEFT JOIN offers ON products.pid = offers.pid AND offers.active = 1
							 ON products.csid = subscriptions.csid AND products.buid = subscriptions.buid
					 		 WHERE subscriptions.cid = " . $CustID ." AND BUActive = 0 ".$WhrT." ".$WhrAcc."";
	
				}
	
				$Data = Yii::app() -> db -> createCommand($Sql) -> queryAll();
				$FeedArr = array();
				$F_Arr = array();
				foreach ($Data as $key => $row) {
					$CurrSQL = " SELECT * FROM country WHERE currency_code = '".$row['BUCurrency']."'";
					$CurrRow = Yii::app() -> db -> createCommand($CurrSQL) -> queryRow();
					
					$F_Arr[$row['BUID']]['bu_id'] = $row['BUID'];
					$F_Arr[$row['BUID']]['bu_name'] = $row['BUName'];
					$F_Arr[$row['BUID']]['bu_curr'] = $CurrRow['currrency_symbol'];
					$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['CatPar'] = $row['ParCatName'];
					$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['CatparID'] = $row['ParCatID'];
					$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['CatSub'] = $row['SubCatName'];
					$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['CatSubID'] = $row['SubCatID'];
	
					if (isset($row['ProdID']) && $row['ProdID'] > 0) {
							
						//--------Get Product Imgs
						$ImgSql = " SELECT pimgid, pimg_url
							   	 	    FROM products_imgs 
							   	 	    WHERE products_imgs.pid = " . $row['ProdID'];
						$ImgAll = Yii::app() -> db -> createCommand($ImgSql) -> queryAll();
						$Img = array();
		
						if (count($ImgAll) > 0) {
							$RealAdrr = Globals::ReturnGlobals();
							$ImgPath = $RealAdrr['ImgSerPath'] . 'products/thumbnails/';
							foreach ($ImgAll as $imgkey => $imgrow) {
								array_push($Img,array('Img'=>$ImgPath.$imgrow['pimg_url']));
							}
						}	
						
						$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['products'][$row['ProdID']]['pro_id'] = $row['ProdID'];
						$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['products'][$row['ProdID']]['pro_name'] = $row['ProdName'];
						$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['products'][$row['ProdID']]['pro_price'] = $row['ProdPrice'];
						$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['products'][$row['ProdID']]['pro_img'] = $Img;
						$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['products'][$row['ProdID']]['wishList'] = $row['wl_id'];
						$F_Arr[$row['BUID']]['cats'][$row['subscrip_csid']]['products'][$row['ProdID']]['off_discount'] = $row['off_discount'];
	
					}
				}
	
				foreach ($F_Arr as $key => $row) {
	
					$CatArr = array();
	
					if (isset($row['cats'])) {
	
						foreach ($row['cats'] as $Ckey => $Crow) {
	
							$ProArr = isset($Crow['products']) ? array_values($Crow['products']) : array();
	
							array_push($CatArr, array('CatPar' => $Crow['CatPar'], 'CatparID' => $Crow['CatparID'], 'CatSub' => $Crow['CatSub'], 'CatSubID' => $Crow['CatSubID'], 'products' => $ProArr));
	
						}
	
					}
	
					array_push($FeedArr, array('bu_id' => $row['bu_id'], 'bu_name' => $row['bu_name'], 'bu_curr' => $row['bu_curr'],'cats' => $CatArr, ));
	
				}
	
				$ResArr = array('feeds' => $FeedArr);
			} else {
	
				$ResArr = array('error' => array("code" => "201", "message" => "Invalid Customer"));
			}
	
		//}else{

		//	$ResArr = array('error'=>array("code"=>"203","message"=>"Invalid Permission"));
		//}
		
		echo json_encode($ResArr);

	}

	//---------------------Product Rating---------------------

	public function actionAddProdRating() {
			
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		/*
		 $RatArr ='{
		 "pid": "11",
		 "cid": "1",
		 "rate": "4"
		 }';*/
		$ResArr = array();
		
		if (isset($_POST['rate'])) {

			$RatArr = $_POST['rate'];

			$JsonArr = json_decode($RatArr);
			
			$CustID = 0;

			if (isset($JsonArr -> cid)) {

				if ($JsonArr -> cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr -> hash)) {

				if ($JsonArr -> hash > 0) {$Hash = $JsonArr -> hash;}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
				$S_Sql = " SELECT * FROM product_rating WHERE cid = " . $CustID . " AND pid = " . $JsonArr -> pid;
				$ResData = Yii::app() -> db -> createCommand($S_Sql)-> queryRow();
	
				if (!empty($ResData)) {
	
					$Sql = "UPDATE product_rating SET value = " . $JsonArr -> rate . " WHERE rid = " . $ResData['rid'];
				} else {
	
					$Sql = "INSERT INTO product_rating (cid,pid,value) VALUES (" . $CustID . "," . $JsonArr -> pid . "," . $JsonArr -> rate . ")";
				}
	
				Yii::app() -> db -> createCommand($Sql) -> execute();
	
				$RateSQL = " UPDATE products SET rating =
							(SELECT FLOOR((((num1*1)+(num2*2)+(num3*3)+(num4*4)+(num5*5))/(num1+num2+num3+num4+num5)))AS ProRate
							 FROM
								(SELECT
								 (SELECT Count(cid)AS num1 FROM product_rating WHERE value = '1' AND pid = " . $JsonArr -> pid . ") AS num1,
								 (SELECT Count(cid)AS num2 FROM product_rating WHERE value = '2' AND pid = " . $JsonArr -> pid . ") AS num2,
								 (SELECT Count(cid)AS num3 FROM product_rating WHERE value = '3' AND pid = " . $JsonArr -> pid . ") AS num3,
								 (SELECT Count(cid)AS num4 FROM product_rating WHERE value = '4' AND pid = " . $JsonArr -> pid . ") AS num4,
								 (SELECT Count(cid)AS num5 FROM product_rating WHERE value = '5' AND pid = " . $JsonArr -> pid . ") AS num5
								 FROM product_rating WHERE pid = " . $JsonArr -> pid . " GROUP BY pid 
								) AS Rate ) WHERE pid =" . $JsonArr -> pid;
				Yii::app() -> db -> createCommand($RateSQL) -> execute();
	
				$Rate = Yii::app() -> db -> createCommand("SELECT rating FROM products WHERE pid = " . $JsonArr -> pid) -> queryRow();
				$Rate = !empty($Rate) ? $Rate['rating'] : '0';
	
				
				$ResArr = array('rate' => $Rate);
			
			}else{
				
				$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
			}
			
		//} else {

			//$ResArr = array('error'=>array("code"=>"203","message"=>"Invalid Permission"));
		//}
		
		echo json_encode($ResArr);
	}

	public function actionGetProdRating() {
			
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$ProID = 0;

		if (isset($_GET['ProID'])) {

			if ($_GET['ProID'] > 0) {$ProID = $_GET['ProID'];}
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
			
			if ($ProID > 0) {
	
				$Rate = Yii::app() -> db -> createCommand("SELECT rating FROM products WHERE pid = " . $ProID) -> queryRow();
				$Rate = !empty($Rate) ? $Rate['rating'] : '0';
				
				$ResArr = array('rate' => $Rate);
				
			} else {
	
				$ResArr = array('error' => array("code" => "213", "message" => "Invalid Product"));
			}
		
		//}else{

		//	$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
		//}
		
		echo json_encode($ResArr);
	}

	//---------------------BU Rating---------------------

	public function actionAddBuRating() {
			
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		
		/*
		rate ={
		"buid": "7",				
		"cid": "1",					
		"hash": "1",			    
		"rate": "4"					
		"lang":"1"					
		 }';*/
		$ResArr = array();
		
		if (isset($_POST['rate'])) {

			$RatArr = $_POST['rate'];

			$JsonArr = json_decode($RatArr);
			
			$CustID = 0;

			if (isset($JsonArr->cid)) {

				if ($JsonArr->cid > 0) {$CustID = $JsonArr->cid;}
			};
			$Hash = 0;

			if (isset($JsonArr->hash)) {

				if ($JsonArr->hash != '') {$Hash = $JsonArr->hash;}
			};
				
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
				$S_Sql = " SELECT * FROM bu_rating WHERE cid = ".$CustID." AND buid = ".$JsonArr->buid;
				$ResData = Yii::app()->db->createCommand($S_Sql)->queryRow();
	
				if (!empty($ResData)) {
	
					$Sql = "UPDATE bu_rating SET value = ".$JsonArr->rate." WHERE burid = ".$ResData['burid'];
				} else {
	
					$Sql = "INSERT INTO bu_rating (cid,buid,value) VALUES (".$CustID.",".$JsonArr->buid.",".$JsonArr->rate.")";
				}

				Yii::app()->db->createCommand($Sql)->execute();
	
				$RateSQL = " UPDATE business_unit SET rating =
							(SELECT FLOOR((((num1*1)+(num2*2)+(num3*3)+(num4*4)+(num5*5))/(num1+num2+num3+num4+num5)))AS BURate
							 FROM
								(SELECT
								 (SELECT Count(cid)AS num1 FROM bu_rating WHERE value = '1' AND buid = ".$JsonArr->buid.") AS num1,
								 (SELECT Count(cid)AS num2 FROM bu_rating WHERE value = '2' AND buid = ".$JsonArr->buid.") AS num2,
								 (SELECT Count(cid)AS num3 FROM bu_rating WHERE value = '3' AND buid = ".$JsonArr->buid.") AS num3,
								 (SELECT Count(cid)AS num4 FROM bu_rating WHERE value = '4' AND buid = ".$JsonArr->buid.") AS num4,
								 (SELECT Count(cid)AS num5 FROM bu_rating WHERE value = '5' AND buid = ".$JsonArr->buid.") AS num5
								 FROM bu_rating WHERE buid = ".$JsonArr->buid." GROUP BY buid 
								) AS Rate ) WHERE buid =".$JsonArr->buid;
								
				Yii::app()->db->createCommand($RateSQL)->execute();
	
				$Rate = Yii::app()->db->createCommand("SELECT rating FROM business_unit WHERE buid = ".$JsonArr->buid)->queryRow();
				$Rate = !empty($Rate) ? $Rate['rating'] : '0';
	
				
				$ResArr = array('rate' => $Rate);
			
			}else{
				
				$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
			}
			
		//} else {

			//$ResArr = array('error'=>array("Code"=>"203","Message"=>"Invalid Permission"));
		//}
		
		echo json_encode($ResArr);
	}

	public function actionGetBuRating() {
			
		header('Content-Type: application/json');
		
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$BuID = 0;

		if (isset($_GET['BuID'])) {

			if ($_GET['BuID'] > 0) {$BuID = $_GET['BuID'];}
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
			
			if ($BuID > 0) {
	
				$Rate = Yii::app() -> db -> createCommand("SELECT rating FROM business_unit WHERE buid = " . $BuID) -> queryRow();
				$Rate = !empty($Rate) ? $Rate['rating'] : '0';
				
				$ResArr = array('rate' => $Rate);
				
			} else {
	
				$ResArr = array("Result" => array('error' => array("code" => "204", "message" => "Invalid BU ID")));
			}
		
		//}else{

		//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}
		
		echo json_encode($ResArr);
	}

	//---------------------Group-----------------------------

	public function actionAddGroup() {
		header('Content-Type: application/json');
		
		//$_POST = CI_Security::ChkPost($_POST);
		
		/*
		 $group ='{
		 "name": "group_1",
		 "cid": "1"
		 }';*/
		$ResArr = array();
		
		if (isset($_POST['group'])) {

			$GroupArr = $_POST['group'];

			$JsonArr = json_decode($GroupArr);

			$CustID = 0;

			if (isset($JsonArr->cid)) {

				if ($JsonArr->cid > 0) {$CustID = $JsonArr->cid;
				}
			};
			$Hash = 0;

			if (isset($JsonArr->hash)) {

				if ($JsonArr->hash > 0) {$Hash = $JsonArr -> hash;
				}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
				$Sql = "INSERT INTO groups (name,cid) VALUES ('" . $JsonArr -> name . "'," . $CustID . ")";
	
				Yii::app() -> db -> createCommand($Sql) -> execute();
				$ResID = Yii::app() -> db -> getLastInsertID();
	
				$SqlA = "INSERT INTO gr_cust_activity (cid,group_id) VALUES (" . $CustID . "," . $ResID . ")";
				Yii::app() -> db -> createCommand($SqlA) -> execute();
	
				$ResRow = Yii::app() -> db -> createCommand(" SELECT * FROM groups WHERE group_id = " . $ResID) -> queryRow();
	
				
				$ResArr = array('group' => $ResRow);
			
			//}else{

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}	

		} else {

			$ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
		}
		
		echo json_encode($ResArr);
		

	}

	public function actionAddCustToGroup() {
			
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		/*
		 $Cust ='{
		 "cid": "1",
		 "cust_id":"2"
		 "group_id": "2"
		 }';*/
		$ResArr = array();
		
		if (isset($_POST['cust'])) {

			$CustArr = $_POST['cust'];

			$JsonArr = json_decode($CustArr);

			$CustID = 0;

			if (isset($JsonArr -> cid)) {

				if ($JsonArr -> cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr -> hash)) {

				if ($JsonArr -> hash > 0) {$Hash = $JsonArr -> hash;}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
				$ChkRow = Yii::app() -> db -> createCommand(" SELECT * FROM gr_cust_activity WHERE cid = " . $JsonArr -> cust_id . " AND group_id = " . $JsonArr -> group_id) -> queryRow();
	
				$ResRow = array();
	
				if (empty($ChkRow)) {
	
					$Sql = "INSERT INTO gr_cust_activity (cid,group_id) VALUES (" . $JsonArr -> cust_id . "," . $JsonArr -> group_id . ")";
	
					Yii::app() -> db -> createCommand($Sql) -> execute();
					$ResID = Yii::app() -> db -> getLastInsertID();
	
					$ResRow = Yii::app() -> db -> createCommand(" SELECT * FROM gr_cust_activity WHERE id = " . $ResID) -> queryRow();
	
				} else {
	
					$ResRow = array('error' => array("code" => "103", "message" => "This Customer in This Group"));
				}
	
				$ResArr = array('customer' => $ResRow);
				
			//}else{

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}
		
		} else {

			$ResArr = $ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
		}

		echo json_encode($ResArr);

	}

	public function actionAddGroupNotify() {
			
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		/*
		 $notify ='{
		 "pid": "1",
		 "nmessage": "welcome",
		 "group_id": "1",
		 "has_offer": "0"
		 }';*/
		$ResArr = array();
		
		if (isset($_POST['notify'])) {

			$NotifyArr = $_POST['notify'];

			$JsonArr = json_decode($NotifyArr);

			$CustID = 0;

			if (isset($JsonArr->cid)) {

				if ($JsonArr->cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr->hash)) {

				if ($JsonArr->hash !='') {$Hash = $JsonArr->hash;}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
				$Sql = "INSERT INTO gr_notifications (pid,nmessage,group_id,has_offer) 
							VALUES (" . $JsonArr -> pid . ",'" . $JsonArr -> nmessage . "'," . $JsonArr -> group_id . ",0)";
	
				Yii::app() -> db -> createCommand($Sql) -> execute();
				$ResID = Yii::app() -> db -> getLastInsertID();
	
				$ResRow = Yii::app() -> db -> createCommand(" SELECT * FROM gr_notifications WHERE nid = " . $ResID) -> queryRow();
	
				$RegsArr = array();
	
				$S_Where = " WHERE cid != ".$CustID."
							 AND cid IN (SELECT gr_cust_activity.cid FROM gr_cust_activity
							   			 LEFT JOIN customers ON customers.cid = gr_cust_activity.cid
							   			 WHERE SUBSTRING(notify_enable, 2, 1 ) = 0  
							   			 AND group_id = ".$JsonArr->group_id." AND block_groups = 0
							   			 AND gr_cust_activity.cid NOT IN 
							   			 (SELECT b_gr_cid FROM block_groups WHERE b_gr_gr_cid = ".$CustID.")))";
	
				$SQlReg = "  SELECT puid,gcm_regid,cid FROM 
							(SELECT puid,gcm_regid,cid
							 FROM push_notifications " . $S_Where . " 
							 ORDER BY count_dev DESC )AS T_Push GROUP BY cid ";
	
				$CustRegs = Yii::app() -> db -> createCommand($SQlReg) -> queryAll();
	
				$SQLMess = " INSERT INTO messages_log (mid,cid,puid,is_group) VALUES ";
	
				foreach ($CustRegs as $key => $row) {
	
					array_push($RegsArr, $row['gcm_regid']);
	
					$SQLMess .= " (" . $ResID . "," . $row['cid'] . "," . $row['puid'] . ", 1),";
				}
	
				$SQLMess = substr($SQLMess, 0, -1);
	
				Yii::app() -> db -> createCommand($SQLMess) -> execute();
	
				GCM::SendNotification($RegsArr, $JsonArr -> nmessage);
	
				$ResArr = array(array('notify' => $ResRow));
			
			//}else{

			//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			
			//}

		} else {

			$ResArr = $ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
		}

		echo json_encode($ResArr);
	}

	public function actionListCustsGroups() {
			
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
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
			//$GSQL = " SELECT group_id AS GID,name AS GName FROM groups WHERE cid = ".$CustID;
			$GSQL = " SELECT group_id AS GID,name AS GName,cid AS GAdmin FROM groups ";
			$GData = Yii::app()->db->createCommand($GSQL)->queryAll();
			$RealArr = Globals::ReturnGlobals();
			$RealPath = $RealArr['ImgSerPath'] . 'customers/';
			foreach ($GData as $Gkey => $Grow) {
				$SQL = " SELECT gr_cust_activity.cid AS CustID,fname,lname,email,image
						 FROM gr_cust_activity 
						 LEFT JOIN customers ON gr_cust_activity.cid = customers.cid
						 WHERE group_id = ".$Grow['GID']." AND block_groups = 0 
						 AND gr_cust_activity.cid NOT IN(SELECT b_gr_cid FROM block_groups WHERE b_gr_gr_cid = ".$CustID.")";
						 
				$Data = Yii::app()->db->createCommand($SQL)->queryAll();
				$CustArr = array();
				foreach ($Data as $key => $row) {
						
					array_push($CustArr,array('CustID'=>$row['CustID'],
											  'CustName'=>$row['fname'].' '.$row['lname'],
											  'CustEmail'=>$row['email'],
											  'CustImg'=>$RealPath.$row['image']));
				}
				
				array_push($ResArr,array('GID'=>$Grow['GID'],
										 'GName'=>$Grow['GName'],
										 'GAdmin'=>$Grow['GAdmin'],
										 'GCusts'=>$CustArr));
			}
			$ResArr = array('Groups'=>$ResArr);
		//}else{

		//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}
		
		echo json_encode($ResArr);
	}

	public function actionBlockGroup() {
		
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		/*
		 customer ='{
		 "block": "all or custid",
		 "cid": "1"
		 }';*/
		
		$ResArr = array();
		
		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$JsonArr = json_decode($CustArr);

			$CustID = 0;

			if (isset($JsonArr->cid)) {

				if ($JsonArr->cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr->hash)) {

				if ($JsonArr->hash !='') {$Hash = $JsonArr->hash;}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
			
				$block = '0';
				
				if (isset($JsonArr->block)) {

					if ($JsonArr->block != '') {$block = $JsonArr->block;}
					
				};
				
				if($block == 'all'){
					
					$SQL = " UPDATE customers SET block_groups = 1 WHERE cid = ".$CustID;
					$RES = Yii::app()->db->createCommand($SQL)->execute();
					$ResArr = array('Result'=>'TRUE');
				
				}else if($block > 0){
						
					$ChkSQL = " SELECT * FROM block_groups WHERE b_gr_gr_cid = ".$block." AND b_gr_cid = ".$CustID;
					$ChkD = Yii::app()->db->createCommand($ChkSQL)->queryAll();
					if(count($ChkD) == 0){
							
						$SQL = " INSERT INTO block_groups(b_gr_gr_cid,b_gr_cid) VALUES (".$block.",".$CustID.")";
						$RES = Yii::app()->db->createCommand($SQL)->execute();
						$ResArr = array('Result'=>'TRUE');
					
					}else{
						$ResArr = array('Result'=>'This Groups Blocked Before');
					}
					
				}else{
					$ResArr = array('Result'=>'Try Again');
				}
			//}else{

			//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}
		}else {

			$ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
		}
		
		
		echo json_encode($ResArr);
		
	}
	
	public function actionUnBlockGroup() {
		
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		/*
		 customer ='{
		 "block": "all or custid",
		 "cid": "1"
		 }';*/
		
		$ResArr = array();
		
		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$JsonArr = json_decode($CustArr);

			$CustID = 0;

			if (isset($JsonArr->cid)) {

				if ($JsonArr->cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr->hash)) {

				if ($JsonArr->hash !='') {$Hash = $JsonArr->hash;}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
			
				$block = '0';
				
				if (isset($JsonArr->block)) {

					if ($JsonArr->block != '') {$block = $JsonArr->block;}
					
				};
				if($block == 'all'){
					
					$SQL = " UPDATE customers SET block_groups = 0 WHERE cid = ".$CustID;
					$RES = Yii::app()->db->createCommand($SQL)->execute();
					$ResArr = array('Result'=>'TRUE');
				
				}else if($block > 0){
						
					$ChkSQL = " SELECT * FROM block_groups WHERE b_gr_gr_cid = ".$block." AND b_gr_cid = ".$CustID;
					$ChkD = Yii::app()->db->createCommand($ChkSQL)->queryRow();
					if(!empty($ChkD)){
							
						$SQL = " DELETE FROM block_groups WHERE b_gr_id = ".$ChkD['b_gr_id'];
						$RES = Yii::app()->db->createCommand($SQL)->execute();
						$ResArr = array('Result'=>'TRUE');
					
					}else{
						$ResArr = array('Result'=>'This Groups NOT Blocked ');
					}
					
				}else{
					$ResArr = array('Result'=>'Try Again');
				}	
				
			//}else{

			//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}
		}else {

			$ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
		}
		
		
		echo json_encode($ResArr);
		
	}
	
	public function actionLeaveGroup() {
			
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		/*
		 customer ='{
		 "gr_id": "1",
		 "cid": "1"
		 }';*/
		$ResArr = array();
		
		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$JsonArr = json_decode($CustArr);

			$CustID = 0;

			if (isset($JsonArr->cid)) {

				if ($JsonArr->cid > 0) {$CustID = $JsonArr -> cid;}
			};
			$Hash = 0;

			if (isset($JsonArr->hash)) {

				if ($JsonArr->hash !='') {$Hash = $JsonArr->hash;}
			};
			
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
				$gr_id = 0;
				if (isset($JsonArr->gr_id)) {
	
					if ($JsonArr->gr_id > 0) {$gr_id = $JsonArr->gr_id;}
				};		
				$SQl = "DELETE FROM gr_cust_activity WHERE cid = ".$CustID." AND group_id = ".$gr_id;
				$RES = Yii::app()->db->createCommand($SQl)->execute();
				
				if($RES > 0){$ResArr = array('Result'=>'TRUE');}
				else{$ResArr = array('Result'=>'FALSE');}
				
			//}else{

			//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}
		}else {

			$ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
		}
		echo json_encode($ResArr);
	}
		
	public function actionGroupNotifyHistory()
	{
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$CustID = 0;
		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];}
		};
		
		$Hash = 0;
		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];}
		};
			
		$GID = 0;
		if (isset($_GET['GID'])) {

			if ($_GET['GID'] > 0) {$GID= $_GET['GID'];}
		};
		$Lang = 0;

		if (isset($_GET['lang'])) {

			if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];}
		};	
		$ResArr = array();
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
			$GrSQL = " SELECT * FROM gr_notifications WHERE group_id = ".$GID;
			$GrData = Yii::app()->db->createCommand($GrSQL)->queryAll();	
			
			if(count($GrData) > 0){
				$GRArr = array();	
				foreach ($GrData as $GrKey => $GrRow) {
						
					//$ProArr = $this->actionProdData($GrRow['pid'] ,$Lang ) ;	
					array_push($GRArr,array('NID'=>$GrRow['nid'],
									  		'NMess'=>$GrRow['nmessage'],
									  		'NProd'=>$GrRow['pid']));
				}
				$ResArr = array('Result'=>array("Notifications"=>$GRArr));
			} else {
				
				$ResArr = array('error'=>array("Code"=>"400","Message"=>"No Notifications"));
			}	
			
		//}else{

		//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}
		
		echo json_encode($ResArr);
	}

	public function actionProdData($ProID = 0,$Lang = 0) {
		
		$ProArr = array();
		$RealAdrr = Globals::ReturnGlobals();
		if ($Lang != 0 && $Lang != 2) {
				
			$ProSQL = " SELECT ProdID,BUID,
							   (CASE WHEN p_lang_title IS NULL THEN ProdName ELSE p_lang_title END) AS ProdName, 
							   (CASE WHEN p_lang_discription IS NULL THEN ProdDesc ELSE p_lang_discription END) AS ProdDesc,
							   (CASE WHEN p_lang_price IS NULL THEN ProdPrice ELSE p_lang_price END) AS ProdPrice,
							   ProdQrcode,ProdNfc,ProdHash,ProdBookable,ProdRate,
							   (CASE WHEN bu_lang_title IS NULL THEN BUName ELSE bu_lang_title END) AS BUName,
							   BUlong,BULat,BULogo
						FROM AllProductsData 
						LEFT JOIN products_lang ON p_lang_pid = ProdID AND p_lang_lang_id = ".$Lang."
						LEFT JOIN business_unit_lang ON bu_lang_bu_id = BUID AND bu_lang_lang_id = ".$Lang."
						WHERE ProdID = ".$ProID;
		}else{
			
			$ProSQL = " SELECT * FROM AllProductsData WHERE ProdID = ".$ProID;
		}
			
		$ProRow = Yii::app()->db->createCommand($ProSQL)->queryRow();
		//--------Get Product Imgs
		$ImgSql = " SELECT pimgid, pimg_url
			   	 	    FROM products_imgs 
			   	 	    WHERE products_imgs.pid = " . $ProID;
		$ImgAll = Yii::app()->db->createCommand($ImgSql)->queryAll();
		$Img = array();
		if (count($ImgAll) > 0) {
			
			$ImgPath = $RealAdrr['ImgSerPath'] . 'products/';
			foreach ($ImgAll as $key => $row) {
				array_push($Img,array('imgThmb'=>$ImgPath.'thumbnails/'.$row['pimg_url'],'img'=>$ImgPath . $row['pimg_url']));
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
				array_push($SubConfArr, array('subConf' => $SConfRow['name'], 'val' => $SConfRow['pdconfv_value']));
	
			}
			array_push($Conf, array('conf' => $ConfRow['name'], 'check' => $ConfRow['pdconfv_chkrad'], 'subConfig' => $SubConfArr));
	
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
			array_push($Color, array('color_id' => $ColorRow['color_id'], 'color_name' => $ColorRow['color_name'], 'color_code' => '#' . $ColorRow['color_code']));
		}
		
		$BUPath = $RealAdrr['ImgSerPath'] . 'business_unit/thumbnails/';
		array_push($ProArr, array('bu_id' => $ProRow['BUID'], 
								  'bu_name' => $ProRow['BUName'], 
								  'bu_logo' => $BUPath.$ProRow['BULogo'], 
								  'bu_gps' => array('lat' => $ProRow['BULat'], 'long' => $ProRow['BUlong']), 
								  'pid' => $ProRow['ProdID'],
								  'pro_name' => $ProRow['ProdName'],
								  'price' => $ProRow['ProdPrice'],
								  'qrcode' => $ProRow['ProdQrcode'],
								  'nfc' => $ProRow['ProdNfc'],
								  'hash' => $ProRow['ProdHash'],
								  'bookable' => $ProRow['ProdBookable'],
								  'discription' => $ProRow['ProdDesc'],
								  'rate' => $ProRow['ProdRate'],
								  'pro_imgs' => $Img,
								  'Config' => $Conf,
								  'colors' => $Color ));
		return $ProArr;
	
	}
	
	//---------------------Search-----------------------------

	public function actionSearchCustByEmail() {
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		/*
		 $email ='{
		 "email": "ahmed@Gmail.com"
		 }';*/
		$Email = '';

		if (isset($_GET['Email'])) {

			$Email = $_GET['Email'];
		};
		
		$CustID = 0;

		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];
			}
		};
		$Hash = 0;

		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];
			}
		};
		
		$ResArr = array();
		
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
		
			if ($Email != '') {
					
				$CustArr = array();
				$Sql = " SELECT * FROM customers WHERE email = '" . $Email . "' AND block_groups = 0 ";
				$ResRow = Yii::app() -> db -> createCommand($Sql) -> queryRow();
				if(!empty($ResRow)){
					
					$BSql = " SELECT * FROM block_groups WHERE b_gr_gr_cid = ".$CustID." AND b_gr_cid = ".$ResRow['cid'];
					$BData = Yii::app() -> db -> createCommand($BSql) -> queryAll();
					if(count($BData) == 0){
						$RealArr = Globals::ReturnGlobals();
						$RealPath = $RealArr['ImgSerPath'] . 'customers/';	
						array_push($CustArr,array('CustID'=>$ResRow['cid'],
										  		  'CustName'=>$ResRow['fname'].' '.$ResRow['lname'],
										  		  'CustEmail'=>$ResRow['email'],
										 		  'CustImg'=>$RealPath.$ResRow['image']));
						
					}
				}
				$ResArr = array('Customer' => $CustArr);
			}else{
						
				$ResArr = array('error' => array("Code"=>"233","Message"=>"Invaid E-Mail"));
				
			}
			
		//}else{

		//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}	
		
		echo json_encode($ResArr);

	}

	public function actionSearchProduct() { 
			
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['product'])) {

			$ProductArr = $_POST['product'];

			$JsonArr = json_decode($ProductArr,TRUE);

			$ResArr = CustLib::actionSearchProduct($JsonArr);

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);

	}

	public function actionSearchStore() {
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		/*
		 $store ='{

		 "BUName": "Business Name 1"
		 "BULoc": "{long:"",lat:""}"
		 "BUDesc": ""
		 "BURate":"1"
		 "lang": "1"
		 }';*/
		$ResArr = array();
		 
		if (isset($_POST['store'])) {

			$StoreArr = $_POST['store'];

			$JsonArr = json_decode($StoreArr);

			$t = 1;

			if (isset($JsonArr -> t)) {

				if ($JsonArr -> t > 0) {$t = $JsonArr -> t;}
			};
				
			$CustID = 0;

			if (isset($JsonArr -> cid)) {

				if ($JsonArr -> cid > 0) {$CustID = $JsonArr -> cid;
				}
			};
			$Hash = 0;

			if (isset($JsonArr -> hash)) {

				if ($JsonArr -> hash > 0) {$Hash = $JsonArr -> hash;
				}
			};
			$Long = '0';
			
			if (isset($JsonArr -> long)) {
	
				if ($JsonArr -> long > 0) {$Long = $JsonArr -> long;}
			};
			
			$Lat = '0';
	
			if (isset($JsonArr -> lat)) {
	
				if ($JsonArr -> lat > 0) {$Lat = $JsonArr -> lat;}
			};
			
			$Dist = self::$Distance;
	
			if (isset($JsonArr -> dist)) {
	
				if ($JsonArr -> dist > 0) {$Dist = $JsonArr -> dist;}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				//if($Long != 0 || $Lat != 0){
				
					$Lang = '0';
		
					if (isset($JsonArr -> lang)) {
		
						if ($JsonArr -> lang != '0' && $JsonArr -> lang != '') {$Lang = $JsonArr -> lang;
						}
					};
		
					$SqlWhere = ' WHERE active = 0 AND type = '.$t;
		
					if (isset($JsonArr -> BUName)) {
						if ($Lang == '2' || $Lang == '0') {
							$SqlWhere .= " AND title LIKE '%" . $JsonArr -> BUName . "%'";
						} else {
							$SqlWhere .= " AND bu_lang_title LIKE '%" . $JsonArr -> BUName . "%'";
						}
					}
					if (isset($JsonArr -> BULoc)) {
						$SqlWhere .= ' AND lat BETWEEN  (' . $JsonArr -> BULoc -> lat . ' - (1.0 / 111.045)) AND (' . $JsonArr -> BULoc -> lat . ' + (50.0 / 111.045))
						AND `long` BETWEEN (' . $JsonArr -> BULoc -> long . ' - (1.0 / (111.045 * COS(RADIANS(' . $JsonArr -> BULoc -> lat . '))))) AND (' . $JsonArr -> BULoc -> long . ' + (50.0 / (111.045 * COS(RADIANS(' . $JsonArr -> BULoc -> lat . ')))))';
					}
					if (isset($JsonArr -> BUDesc)) {
						if ($Lang == '2' || $Lang == '0') {
							$SqlWhere .= " AND description LIKE '%" . $JsonArr -> BUDesc . "%'";
						} else {
							$SqlWhere .= " AND bu_lang_description LIKE '%" . $JsonArr -> BUDesc . "%'";
						}
					}
					if (isset($JsonArr -> BURate)) {
						$SqlWhere .= " AND rating =" . $JsonArr -> BURate;
					}
					if ($Lang == '2' || $Lang == '0') {
							
						$Sql = " SELECT title,`long`,lat,logo,description,rating,
										(((acos(sin((".$Lat."*pi()/180)) * 
								            sin((business_unit.lat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
								            cos((business_unit.lat*pi()/180)) * cos(((".$Long."- business_unit.long)* 
								            pi()/180))))*180/pi())*60*1.1515
								        ) as BUDist	 
								 FROM business_unit " . $SqlWhere ." HAVING BUDist < ".$Dist;
						
					} else {
						
						$Sql = " SELECT bu_lang_title AS title,`long`,lat,logo,bu_lang_description AS description,rating,
										(((acos(sin((".$Lat."*pi()/180)) * 
								            sin((business_unit.lat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
								            cos((business_unit.lat*pi()/180)) * cos(((".$Long."- business_unit.long)* 
								            pi()/180))))*180/pi())*60*1.1515
								        ) as BUDist	
								 FROM business_unit 
								 LEFT JOIN business_unit_lang ON bu_lang_bu_id = BUID AND bu_lang_lang_id = " . $Lang . 
								 $SqlWhere." HAVING BUDist < ".$Dist;
								 
					}
		
					$Data = Yii::app() -> db -> createCommand($Sql) -> queryAll();
		
					if (count($Data) > 0) {
		
						$ResArr = array('Stores' => $Data);
		
					} else {
		
						$ResArr = array('error' => array("code" => "500", "message" => "No Data"));
					}
					
				//} else {
					
				//	$ResArr = array('error'=>array("Code"=>"800","Message"=>"UnKnown Location"));
				//}
			//} else {

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}

		} else {

			$ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));

		}

		echo json_encode($ResArr);

	}

	public function actionSearchCatAndSub() {
			
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		/*
		 $cat ='{

		 "CatName": "Business Name 1"
		 "CatDesc": ""
		 "lang":"1"
		 }';*/
		$ResArr = array();
		
		
		if (isset($_POST['cat'])) {

			$CatArr = $_POST['cat'];

			$JsonArr = json_decode($CatArr);

			$t = 1;

			if (isset($JsonArr -> t)) {

				if ($JsonArr -> t > 0) {$t = $JsonArr -> t;}
			};
			$CustID = 0;

			if (isset($JsonArr -> cid)) {

				if ($JsonArr -> cid > 0) {$CustID = $JsonArr -> cid;
				}
			};
			$Hash = 0;

			if (isset($JsonArr -> hash)) {

				if ($JsonArr -> hash > 0) {$Hash = $JsonArr -> hash;
				}
			};	
			$Long = '0';
			
			if (isset($JsonArr -> long)) {
	
				if ($JsonArr -> long > 0) {$Long = $JsonArr -> long;}
			};
			
			$Lat = '0';
	
			if (isset($JsonArr -> lat)) {
	
				if ($JsonArr -> lat > 0) {$Lat = $JsonArr -> lat;}
			};
			
			$Dist = self::$Distance;
	
			if (isset($JsonArr -> dist)) {
	
				if ($JsonArr -> dist > 0) {$Dist = $JsonArr -> dist;}
			};
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
				//if($Lat != 0 ||$Long != 0){
						
					$SqlWhere = ' WHERE BUActive = 0 AND BUType = '.$t;
		
					$Lang = '0';
		
					if (isset($JsonArr -> lang)) {
		
						if ($JsonArr -> lang != '0' && $JsonArr -> lang != '') {$Lang = $JsonArr -> lang;}
					};
		
					if (isset($JsonArr -> CatName)) {
						if ($Lang == '2' || $Lang == '0') {
							$SqlWhere .= " AND (ParCatName LIKE '%" . $JsonArr -> CatName . "%' OR SubCatName LIKE '%" . $JsonArr -> CatName . "%')";
						} else {
							$SqlWhere .= " AND (ParLang.cat_lang_title LIKE '%" . $JsonArr -> CatName . "%' OR SubLang.cat_lang_title LIKE '%" . $JsonArr -> CatName . "%')";
						}
					}
					if (isset($JsonArr -> CatDesc)) {
						if ($Lang == '2' || $Lang == '0') {
							$SqlWhere .= " AND (ParCatDesc LIKE '%" . $JsonArr -> CatName . "%' OR SubCatDesc LIKE '%" . $JsonArr -> CatName . "%')";
						} else {
							$SqlWhere .= " AND (ParLang.cat_lang_description LIKE '%" . $JsonArr -> CatName . "%' OR SubLang.cat_lang_description LIKE '%" . $JsonArr -> CatName . "%')";
						}
					}
					if ($Lang == '2' || $Lang == '0') {
						$Sql = " SELECT ParCatID,ParCatName,ParCatDesc,ParCatImg,ParCatDate,
										SubCatID,SubCatName,SubCatDesc,SubCatImg,SubCatDate,
										(((acos(sin((".$Lat."*pi()/180)) * 
							            sin((BULat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
							            cos((BULat*pi()/180)) * cos(((".$Long."- BUlong)* 
							            pi()/180))))*180/pi())*60*1.1515) as BUDist
							     FROM CatAndCatSub" . $SqlWhere." HAVING BUDist < ".$Dist;
					} else {
						$Sql = " SELECT ParCatID,ParCatImg,ParCatDate,
										ParLang.cat_lang_title AS ParCatName,
										ParLang.cat_lang_description AS ParCatDesc,
									    SubCatID,SubCatImg,SubCatDate,
									    SubLang.cat_lang_title AS SubCatName,
									    SubLang.cat_lang_description AS SubCatDesc,
									    (((acos(sin((".$Lat."*pi()/180)) * 
							            sin((BULat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
							            cos((BULat*pi()/180)) * cos(((".$Long."- BUlong)* 
							            pi()/180))))*180/pi())*60*1.1515) as BUDist
								 FROM CatAndCatSub						 
								 LEFT JOIN catsub_lang AS ParLang ON ParLang.cat_lang_cs_id = ParCatID AND ParLang.cat_lang_lang_id = " . $Lang . "
								 LEFT JOIN catsub_lang AS SubLang ON SubLang.cat_lang_cs_id = SubCatID AND SubLang.cat_lang_lang_id =  " . $Lang . 
								 $SqlWhere." HAVING BUDist < ".$Dist;
					}
		
					$Data = Yii::app() -> db -> createCommand($Sql) -> queryAll();
		
					if (count($Data) > 0) {
		
						$DataArr = array();
		
						foreach ($Data as $key => $row) {
		
							if ($row['SubCatID'] > 0) {
		
								$DataArr[$row['ParCatID']]['sub'][$row['SubCatID']]['SubCatID'] = $row['SubCatID'];
								$DataArr[$row['ParCatID']]['sub'][$row['SubCatID']]['SubCatName'] = $row['SubCatName'];
								$DataArr[$row['ParCatID']]['sub'][$row['SubCatID']]['SubCatDesc'] = $row['SubCatDesc'];
								$DataArr[$row['ParCatID']]['sub'][$row['SubCatID']]['SubCatImg'] = $row['SubCatImg'];
								$DataArr[$row['ParCatID']]['sub'][$row['SubCatID']]['SubCatDate'] = $row['SubCatDate'];
		
							} else {
		
								$DataArr[$row['ParCatID']]['ParCatID'] = $row['ParCatID'];
								$DataArr[$row['ParCatID']]['ParCatName'] = $row['ParCatName'];
								$DataArr[$row['ParCatID']]['ParCatDesc'] = $row['ParCatDesc'];
								$DataArr[$row['ParCatID']]['ParCatImg'] = $row['ParCatImg'];
								$DataArr[$row['ParCatID']]['ParCatDate'] = $row['ParCatDate'];
							}
						}
		
						$F_Arr = array();
						foreach ($DataArr as $key => $row) {
		
							$SubArr = isset($row['sub']) ? array_values($row['sub']) : array();
							array_push($F_Arr, array('ParCatID' => $row['ParCatID'], 'ParCatName' => $row['ParCatName'], 'ParCatDesc' => $row['ParCatDesc'], 'ParCatImg' => $row['ParCatImg'], 'ParCatDate' => $row['ParCatDate'], 'Subs' => $SubArr));
		
						}
		
						$ResArr = array('cats' => $F_Arr);
		
					} else {
		
						$ResArr = array('error' => array("code" => "500", "message" => "No Data"));
					}
					
				//} else {
					
				//	$ResArr = array('error'=>array("Code"=>"800","Message"=>"UnKnown Location"));
				//}
			//} else {

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}

		} else {

			$ResArr = array('error' => array("code" => "500", "message" => "No Data"));
		}

		echo json_encode($ResArr);

	}

	public function actionSearchProdByIdQrBar()
	{
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$ResArr = array();
		
		$CustID = 0;

		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];}
		};
			
		$Hash = 0;

		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] !='') {$Hash = $_GET['Hash'];}
		};
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
			$Lang = 0;
	
			if (isset($_GET['Lang'])) {

				if ($_GET['Lang'] > 0) {$Lang = $_GET['Lang'];}
			};					
			
			$Val = 0;
	
			if (isset($_GET['Val'])) {

				if ($_GET['Val'] != '') {$Val = $_GET['Val'];}
			};
							
			$Q = ''; $StrWhere = " ";
	
			if (isset($_GET['q'])) {

				if ($_GET['q'] != '') {$Q = $_GET['q'];}
				
				if($Q == 'id'){$StrWhere = " AND pid = ".$Val;}
				if($Q == 'qr'){$StrWhere = " AND qrcode = ".$Val;}
				if($Q == 'bar'){$StrWhere = " AND barcode = ".$Val;}
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
			//if($Lat != 0 || $Long != 0){
					
				$Ord = 'BUDist';
				if (isset($_GET['Ord'])) {
	
					if ($_GET['Ord'] != '') {
							
						if ($_GET['Ord'] == 'p') {$Ord = 'products.price';}
						if ($_GET['Ord'] == 'd') {$Ord = 'BUDist';}
					}
				};
					
				if($Lang != 0 && $Lang != 2){
					
					$SQL = " SELECT pid AS ProdID,
									   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProdName,
									   (CASE WHEN p_lang_price IS NULL THEN products.price ELSE p_lang_price END) AS ProdPrice,
						 			   (CASE WHEN bu_lang_title IS NULL THEN business_unit.title ELSE bu_lang_title END) AS BUTitle,
									   (CASE WHEN p_lang_discription IS NULL THEN products.discription ELSE p_lang_discription END) AS BUDesc,
									   business_unit.long AS BULong,business_unit.lat AS BULat,
									   business_unit.logo AS BULogo,business_unit.rating AS BURate,
								      (((acos(sin((".$Lat."*pi()/180)) * 
								            sin((business_unit.lat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
								            cos((business_unit.lat*pi()/180)) * cos(((".$Long."- business_unit.long)* 
								            pi()/180))))*180/pi())*60*1.1515
								        ) as BUDist
								FROM products 
								LEFT JOIN products_lang ON p_lang_pid = products.pid AND p_lang_lang_id = ".$Lang."
								LEFT JOIN business_unit 
									 LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = ".$Lang."
								ON business_unit.buid = products.buid
								WHERE business_unit.buid > 0 AND business_unit.active = 0 ".$StrWhere." 
								HAVING BUDist <  ".$Dist ." ORDER BY ".$Ord;
					
				} else {
					
					$SQL = " SELECT pid AS ProdID,products.title AS ProdName,price AS ProdPrice,
							   business_unit.buid AS BUID,business_unit.title AS BUTitle,
							   business_unit.long AS BULong,business_unit.lat AS BULat,
							   business_unit.logo AS BULogo,business_unit.description AS BUDesc,business_unit.rating AS BURate,
							   (((acos(sin((".$Lat."*pi()/180)) * 
						            sin((business_unit.lat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
						            cos((business_unit.lat*pi()/180)) * cos(((".$Long."- business_unit.long)* 
						            pi()/180))))*180/pi())*60*1.1515
						        ) as BUDist
								FROM products 
								LEFT JOIN business_unit ON business_unit.buid = products.buid
								WHERE business_unit.buid > 0 AND business_unit.active = 0 ".$StrWhere."
								HAVING BUDist <  ".$Dist ." ORDER BY ".$Ord;
					
				}
				//print_r($SQL);
				if($Q == 'id'||$Q == 'qr'||$Q == 'bar'){
					
						$DArr = array();
						
						$Data = Yii::app()->db->createCommand($SQL)->queryAll();
						
						$RealArr = Globals::ReturnGlobals();
						$RealPath = $RealArr['ImgSerPath'];
				
						foreach ($Data as $key => $row) {
									
								
							//--------Get Product Imgs
							
							$ImgSql = " SELECT pimgid, pimg_url FROM products_imgs 
								   	 	WHERE products_imgs.pid = " . $row['ProdID'];
							$ImgAll = Yii::app()->db->createCommand($ImgSql)->queryRow();
							$ProdImg = '';
							if(!empty($ImgAll)){
									
								$ProdImg = $ImgAll['pimg_url'];
							}
							array_push($DArr, array('BUID' => $row['BUID'],
												    'BUTitle' => $row['BUTitle'], 
												    'BULogo' => $RealPath .'business_unit/thumbnails/'. $row['BULogo'], 
												    'gps' => array('BULong' => $row['BULong'], 'BULat' => $row['BULat']), 
												    'BUDesc' => $row['BUDesc'], 
												    'BURate' => $row['BURate'],
												    'BUDist' => $row['BUDist'],
												    'ProdID' => $row['ProdID'],
												    'ProdName' => $row['ProdName'],
												    'ProdPrice' => $row['ProdPrice'],
												    'ProdImg' => $RealPath.'products/thumbnails/'.$ProdImg
													)
										);
						
						}
						
						
						$ResArr = array('stores'=>$DArr);
				} else {
		
					$ResArr = array('error' => array("code" => "500", "message" => "No Data"));
				}
			//}else{
					
			//	$ResArr = array('error'=>array("Code"=>"560","Message"=>"Invalid Search"));
			//}	
				
		//} else {

			//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}	
		
		echo json_encode($ResArr);
		
	}
	
	//----------------------------------------------------------------------------

	public function actionGetStoreDetails($Bu_ID = 0) {
		header('Content-Type: application/json');
		$Bu_ID = 1;
		$sql = "SELECT buid , title , logo , lat , `long` , rating ,
					   (SELECT COUNT(sid) FROM subscriptions WHERE buid = " . $Bu_ID . ") AS subscribers ,
					   type 
				FROM business_unit
				WHERE buid = " . $Bu_ID;

		$command = Yii::app() -> db -> createCommand($sql) -> queryRow();
		// $_SERVER['SERVER_NAME']
		// Yii::app()->basePath
		$store = array('store' => array('id' => $command['buid'], 'title' => $command['title'], 'logo_url' => $_SERVER['SERVER_NAME'] . '/../public/images/upload/business_unit/' . $command['logo'], 'gps' => array('lat' => $command['lat'], 'long' => $command['long']), 'rate' => $command['rating'], 'subscribers' => $command['subscribers'], 'type' => $command['type'],
		//'apikey'=>$command['apiKey']
		));

		echo json_encode($store);

		// $existing = Yii::app()->db->createCommand()
		// ->select('buid,title')
		// ->from('business_unit')
		// ->where('buid = 1')
		// ->queryRow();
		// print_r($existing['title']);

	}

	public function actionGetProductsByStoreId($store_ID = 0) {
		header('Content-Type: application/json');
		$store_ID = 1;

		$sql = "SELECT tab2.csid AS catId, tab1.csid AS subId, products.pid as proId, tab2.title AS catTitle, tab1.title AS subTitle, 
					   products.title AS title, tab2.img_url AS catImg, tab1.img_url AS subImg, tab3.pimg_url , rating
				FROM products
				LEFT JOIN catsub AS tab1 ON tab1.csid = products.csid
				LEFT JOIN catsub AS tab2 ON tab1.parent_id = tab2.csid
				LEFT JOIN (SELECT products_imgs.pid, pimgid, pimg_url
							FROM products_imgs
							JOIN products ON products_imgs.pid = products.pid
							LIMIT 1) AS tab3 
				ON tab3.pid = products.pid
				WHERE buid =" . $store_ID;

		$command = Yii::app() -> db -> createCommand($sql) -> queryAll();

		// $img_path = Yii::app()->basePath.'/../public/images/upload/';
		$img_path = $_SERVER['SERVER_NAME'] . '/public/images/upload/';
		$products = array();

		foreach ($command as $key => $row) {
			$parent_catid = '';
			$subcatid = '';
			$parent_cat_tit = '';
			$sub_cat_tit = '';

			if (!isset($row['catId'])) {
				$parent_catid = $row['subId'];
				$parent_cat_tit = $row['subTitle'];
			} else {
				$parent_catid = $row['catId'];
				$parent_cat_tit = $row['catTitle'];
				$subcatid = $row['subId'];
				$sub_cat_tit = $row['subTitle'];
			}

			array_push($products, array('catId' => $parent_catid, 'subId' => $subcatid, 'Id' => $row['proId'], 'catTitle' => $parent_cat_tit, 'subTitle' => $sub_cat_tit, 'title' => $row['title'], 'catImg' => $img_path . 'catsub/' . $row['catImg'], 'subImg' => $img_path . 'catsub/' . $row['subImg'], 'imgThmb' => $img_path . 'products/' . $row['pimg_url'], 'img' => $img_path . 'products/' . $row['pimg_url']));
		}

		echo json_encode(array('items' => $products));
	}

	public function actionAllStoresAllProds() {
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$t = 0;

		if (isset($_GET['t'])) {

			if ($_GET['t'] > 0) {$t = $_GET['t'];}
		};
		
		$WhrT = "";	
		if($t > 0){
			
			$WhrT =	"AND business_unit.type = ".$t;
		}	
			
		$Lang = 0;

		if (isset($_GET['lang'])) {

			if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];}
		};
		$CustID = 0;

		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];}
		};
		$Hash = 0;

		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];}
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
			
		$Frm = 0;
	
		if (isset($_GET['frm'])) {

			if ($_GET['frm'] >= 0) {$Frm = $_GET['frm'];}
		};
		
		$To = 10;

		if (isset($_GET['to'])) {

			if ($_GET['to'] >= 0) {$To = $_GET['to'];}
		};
		
		$Buid = 0;

		if (isset($_GET['Buid'])) {

			if ($_GET['Buid'] > 0) {$Buid = $_GET['Buid'];}
		};
		
		$WhrBu = ' ';	
		
		if($Buid > 0){
			
			$WhrBu = ' AND buid = '.$Buid;
		}
		
		$WhrCat = ' ';
		
		$CatID = 0;

		if (isset($_GET['CatID'])) {
				
			if ($_GET['CatID'] >= 0) {$CatID = $_GET['CatID'];}	
			
			if($CatID == 0){
				
				$WhrCat = " AND (ParCatID IS NULL OR ParCatID = '') AND (SubCatID IS NULL OR SubCatID = '')";
			}
			
			if($CatID > 0){
				
				$WhrCat = " AND (ParCatID = ".$CatID." OR SubCatID = ".$CatID.")";
				
				if($WhrBu == ' '){
				
					$WhrBu = " AND business_unit.buid = (SELECT catsub_buid FROM catsub WHERE csid = ".$CatID.") ";
				}	
			}
		};
			
		$BuAcc = 0 ;
		if (isset($_GET['BuAcc'])) {

			if ($_GET['BuAcc'] > 0) {$BuAcc = $_GET['BuAcc'];}
		};
		
		$WhrAcc = ' ';
		
		if($BuAcc > 0){
				
			$WhrAcc = " AND business_unit.buid IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
		}
		
		
		$ResArr = array();
		
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				
			//if($Lat != 0 || $Long != 0 ){
				
				$JArr = '{ "limit_from": "0" , 
						   "limit_to": "100"
						 }';
				// $img_path = $_SERVER['SERVER_NAME'].'/public/images/upload/';
				$RealAdrr = Globals::ReturnGlobals();
				$img_path = $RealAdrr['ImgSerPath'];
		
				$JArr_decode = json_decode(trim($JArr));
		
				$result = array();
				if ($Lang != 0 && $Lang != 2) {
					$sql = "SELECT buid AS buss_id, accid,`long` , `lat` ,type , logo,
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
							WHERE business_unit.active = 0 ".$WhrT." ".$WhrBu." ".$WhrAcc."
							HAVING BUDist < ".$Dist;
				} else {
					$sql = " SELECT buid AS buss_id, accid, title AS buss_name, `long` , `lat` ,type , logo , 
									IFNULL((SELECT currrency_symbol FROM country WHERE country.currency_code = business_unit.currency_code LIMIT 0,1),'')AS currrency_symbol,
								    (((acos(sin((".$Lat."*pi()/180)) * 
							            sin((business_unit.lat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
							            cos((business_unit.lat*pi()/180)) * cos(((".$Long."- business_unit.long)* 
							            pi()/180))))*180/pi())*60*1.1515
							        ) as BUDist,business_unit.rating AS BuRate,
							       IFNULL((SELECT value FROM bu_rating WHERE bu_rating.buid = business_unit.buid AND cid = ".$CustID." LIMIT 0,1),0)AS CustRate,
							       IFNULL((SELECT COUNT(DISTINCT cid) FROM bu_rating WHERE bu_rating.buid = business_unit.buid LIMIT 0,1),0)AS CountRate
							 FROM business_unit 
							 WHERE business_unit.active = 0 ".$WhrT." ".$WhrBu." ".$WhrAcc."
							 HAVING BUDist < ".$Dist;
				}
				$command = Yii::app() -> db -> createCommand($sql) -> queryAll();
		        
				foreach ($command as $key => $row) {
					// $sql2 = "SELECT tab2.csid AS catId, tab1.csid AS subId, products.pid as pid, tab2.title AS catTitle, tab1.title AS subTitle,
					// products.title AS pro_name , tab2.img_url AS catImg, tab1.img_url AS subImg,
					// price, qrcode , nfc , hash , bookable ,discription
					// FROM products
					// LEFT JOIN catsub AS tab1 ON tab1.csid = products.csid
					// LEFT JOIN catsub AS tab2 ON tab1.parent_id = tab2.csid
					// WHERE buid = 1
					// LIMIT ".$JArr_decode->limit_from." , ".$JArr_decode->limit_to;
					
					$IsReservedBu = Orders::IsReservedBu($row['buss_id'],$CustID);
					
					if ($Lang != 0 && $Lang != 2) {
		
						$sql2 = "SELECT 
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
								WHERE AllProductsData.BUID = " . $row['buss_id'] . " ".$WhrCat." ORDER BY ProdID DESC LIMIT " . $Frm . " , " . $To ;
					
					} else {
					
						$sql2 = " SELECT 
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
								 WHERE AllProductsData.BUID = " . $row['buss_id'] . " ".$WhrCat." ORDER BY ProdID DESC LIMIT " . $Frm . " , " . $To ;
											
				
					}
					$command2 = Yii::app()-> db->createCommand($sql2) -> queryAll();
		
					$items = array();
					foreach ($command2 as $key2 => $row2) {
					
						$sql3 = "SELECT pimgid, pimg_url
							   	 	    FROM products_imgs JOIN products 
							   	 	    ON products_imgs.pid = products.pid
							   	 	    WHERE products_imgs.pid = " . $row2['ProdID'];
						// ."LIMIT 1 "
						$command3 = Yii::app() -> db -> createCommand($sql3) -> queryAll();
						$images_arr = array();
						foreach ($command3 as $key3 => $row3) {
							array_push($images_arr, array('imgThmb' => $img_path . 'products/' . $row3['pimg_url'], 'img' => $img_path . 'products/' . $row3['pimg_url']));
						}
						if ($Lang != 0 && $Lang != 2) {
							$sql4 = "SELECT cfg_id ,pdconfv_id, pdconfv_value, pdconfv_chkrad,
							   	 		(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name , 
										(CASE pdconfv_chkrad
											WHEN 1 THEN 'TRUE'
											WHEN 0 THEN 'FALSE'
										END) as pdconfv_chkrad
								 FROM pd_conf_v
								 JOIN pd_config ON cfg_id = pdconfv_confid	
								 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "	
								 WHERE pdconfv_pid =" . $row2['ProdID'] . "
								 AND parent_id IS NULL ";
						} else {
							$sql4 = "SELECT cfg_id , pdconfv_id, pdconfv_value, pdconfv_chkrad, name , 
									(CASE pdconfv_chkrad
		        						WHEN 1 THEN 'TRUE'
		        						WHEN 0 THEN 'FALSE'
		   							END) as pdconfv_chkrad
							 FROM pd_conf_v
							 JOIN pd_config ON cfg_id = pdconfv_confid
							 WHERE pdconfv_pid =" . $row2['ProdID'] . "
							 AND parent_id IS NULL ";
		
						}
						$command4 = Yii::app() -> db -> createCommand($sql4) -> queryAll();
						$config_arr = array();
		
						foreach ($command4 as $key4 => $row4) {
							$subConf_arr = array();
							if ($Lang != 0 && $Lang != 2) {
								$sql5 = "SELECT pdconfv_id, pdconfv_value, 
												(CASE WHEN conf_lang_name IS NULL THEN name ELSE conf_lang_name END)AS name 
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid
										 JOIN pd_config_lang ON conf_lang_conf_id = cfg_id AND conf_lang_lang_id = " . $Lang . "
										 WHERE pdconfv_pid = " . $row2['ProdID'] . "
										 AND parent_id = " . $row4['cfg_id'];
								//"IS NOT NULL ";
							} else {
								$sql5 = "SELECT pdconfv_id, pdconfv_value, name
										 FROM pd_conf_v
										 JOIN pd_config ON cfg_id = pdconfv_confid
										 WHERE pdconfv_pid = " . $row2['ProdID'] . "
										 AND parent_id = " . $row4['cfg_id'];
								//"IS NOT NULL ";
							}
							$command5 = Yii::app() -> db -> createCommand($sql5) -> queryAll();
							foreach ($command5 as $key5 => $row5) {
								array_push($subConf_arr, array('subId'=>$row5['pdconfv_id'],'subConf' => $row5['name'], 'val' => $row5['pdconfv_value']));
		
							}
							array_push($config_arr, array('confId'=>$row4['pdconfv_id'],'conf' => $row4['name'], 'check' => $row4['pdconfv_chkrad'], 'subConfig' => $subConf_arr));
		
						}
						if ($Lang != 0 && $Lang != 2) {
							$sql6 = " SELECT color_id,color_code,
									   (CASE WHEN color_lang_name IS NULL THEN color_name ELSE color_lang_name END)AS color_name 
								   	  FROM prod_colors
								      LEFT JOIN prod_colors_lang ON color_lang_color_id = color_id AND color_lang_lang_id = " . $Lang . "
								   	  WHERE color_pid = " . $row2['ProdID'];
							// ."LIMIT 1 "
						} else {
							$sql6 = " SELECT color_id, color_name ,color_code FROM prod_colors
							   		  WHERE color_pid = " . $row2['ProdID'];
							// ."LIMIT 1 "
		
						}
		
						$command6 = Yii::app() -> db -> createCommand($sql6) -> queryAll();
						$color_arr = array();
						foreach ($command6 as $key6 => $row6) {
							array_push($color_arr, array('color_id' => $row6['color_id'], 'color_name' => $row6['color_name'], 'color_code' => '#' . $row6['color_code']));
						}
						if($row2['ParCatID'] == null){$row2['ParCatID'] = '0';}
						if($row2['ParCatName'] == null){$row2['ParCatName'] = 'noncategorized';}
						
						array_push($items, array('pid' => $row2['ProdID'], 
												 'pro_name' => $row2['ProdName'], 
												 'price' => $row2['ProdPrice'], 
												 'qrcode' => $row2['ProdQrcode'], 
												 'nfc' => $row2['ProdNfc'], 
												 'hash' => $row2['ProdHash'], 
												 'bookable' => $row2['ProdBookable'], 
												 'wishList'=>$row2['wl_id'] ,
												 'off_discount'=>$row2['off_discount'],
												 'discription' => $row2['ProdDesc'], 
												 'rate' => $row2['ProdRate'],
												 'CustRate' => $row2['CustRate'],
												 'CountRate' => $row2['CountRate'], 
												 'CatScrip'=>$row2['ParScripID'] ,
												 'catId' => $row2['ParCatID'], 
												 'catTitle' => $row2['ParCatName'], 
												 'SubScrip'=>$row2['SubScripID'] ,
												 'subId' => $row2['SubCatID'], 
												 'subTitle' => $row2['SubCatName'], 
												 'catImg' => $img_path . 'catsub/' . $row2['ParCatImg'], 
												 'subImg' => $img_path . 'catsub/' . $row2['SubCatImg'], 
												 'pro_imgs' => $images_arr, 
												 'Config' => $config_arr, 
												 'colors' => $color_arr));
		
					}
					// $result[$row['buss_id']]['items'] = $items;
					array_push($result, array('id' => $row['buss_id'], 
											  'buss_name' => $row['buss_name'], 
											  'curr_symbol'=>$row['currrency_symbol'] ,
											  'logo_url' => $img_path . 'business_unit/' . $row['logo'],
											  'BuRate' => $row['BuRate'],
											  'CustRate' => $row['CustRate'], 
											  'CountRate' => $row['CountRate'],
											  'IsReserved'=>$IsReservedBu,
											  'gps' => array('lat' => $row['lat'], 'long' => $row['long']), 
											  'items' => $items));
				}
	
				$ResArr = array('stores' => $result);
			
			//} else {
					
			//	$ResArr = array('error'=>array("Code"=>"800","Message"=>"UnKnown Location"));
			//}
		//}else{

			//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}	
		
		echo json_encode($ResArr);
	}

	public function actionGetProdsByCatID()
	{
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		$Arr = $_GET;
		
		$ResArr = CustLib::actionGetProdsByCatID($Arr);
		
		echo json_encode($ResArr);
	}
	
	public function actionViewRecentOffers() {
		header('Content-Type: application/json');
		////////$_GET = CI_Security::ChkPost($_GET);
		
		$t = 0;

		if (isset($_GET['t'])) {

			if ($_GET['t'] > 0) {$t = $_GET['t'];}
		};
		
		$WhrT = "";
		if($t > 0){
			$WhrT = " AND BUType = ".$t;
		}
		
		$Lang = 0;

		if (isset($_GET['lang'])) {

			if ($_GET['lang'] > 0) {$Lang = $_GET['lang'];
			}
		};
		$CustID = 0;

		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];
			}
		};
		$Hash = 0;

		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];
			}
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
		
		$BuID = 0;

		if (isset($_GET['BuID'])) {

			if ($_GET['BuID'] > 0) {$BuID = $_GET['BuID'];}
		};
		$ResArr = array();
		
		$WhBuID = ' ';
		
		if($BuID > 0){
			$WhBuID = ' AND BUID = '.$BuID;
		}
		
		$BuAcc = 0 ;
		if (isset($_GET['BuAcc'])) {

			if ($_GET['BuAcc'] > 0) {$BuAcc = $_GET['BuAcc'];}
		};
		
		$WhrAcc = ' ';
		
		if($BuAcc > 0){
				
			$WhrAcc = " AND BUID IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
		}
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
		//if($Lat != 0 || $Long != 0){
				
				if ($Lang != 0 && $Lang != 2) {
					$sql = " SELECT ofid , ProdID , ProdRate ,
								   (CASE WHEN offer_lang_title IS NULL THEN offers.title ELSE offer_lang_title END) AS offTitle,
								   (CASE WHEN offer_lang_text IS NULL THEN text ELSE offer_lang_text END) AS text ,
								   (CASE WHEN offer_lang_discount IS NULL THEN discount ELSE offer_lang_discount END) AS discount ,
								  `from` , `to` , scheduled ,BUID,
								  (CASE WHEN p_lang_title IS NULL THEN ProdName ELSE p_lang_title END) AS ProdName , 
								  (CASE WHEN p_lang_price IS NULL THEN ProdPrice ELSE p_lang_price END) ProdPrice, 
								  (CASE WHEN bu_lang_title IS NULL THEN BUName ELSE bu_lang_title END) AS BUName,
								  (((acos(sin((".$Lat."*pi()/180)) * 
							            sin((BULat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
							            cos((BULat*pi()/180)) * cos(((".$Long."- BUlong)* 
							            pi()/180))))*180/pi())*60*1.1515
							        ) as BUDist,
							       IFNULL((SELECT currrency_symbol FROM country WHERE currency_code = BUCurrency LIMIT 0,1),'')AS BUCurr		
							FROM offers 
							LEFT JOIN offers_lang ON offer_lang_offer_id = ofid AND offer_lang_lang_id = " . $Lang . "
							LEFT JOIN AllProductsData 
								 LEFT JOIN products_lang ON p_lang_pid = ProdID AND p_lang_lang_id = " . $Lang . "
								 LEFT JOIN business_unit_lang ON bu_lang_bu_id = BUID AND bu_lang_lang_id = " . $Lang . "
							ON offers.pid = ProdID
							WHERE offers.active = 1 AND NOW() BETWEEN `from` AND `to`
							".$WhBuID." AND BUActive = 0 ".$WhrT." ".$WhrAcc."
							HAVING BUDist < ".$Dist;
				} else {
		
					$sql = " SELECT ofid , ProdID , ProdRate ,offers.title AS offTitle, text , discount ,
								   `from` , `to` , scheduled ,ProdName , ProdPrice, BUName, BUID,
								    (((acos(sin((".$Lat."*pi()/180)) * 
							            sin((BULat*pi()/180)) + cos((".$Lat."*pi()/180)) * 
							            cos((BULat*pi()/180)) * cos(((".$Long."- BUlong)* 
							            pi()/180))))*180/pi())*60*1.1515
							        ) as BUDist,
							        IFNULL((SELECT currrency_symbol FROM country WHERE currency_code = BUCurrency LIMIT 0,1),'')AS BUCurr	
							 FROM offers 
							 LEFT JOIN AllProductsData ON offers.pid = ProdID
							 WHERE offers.active = 1 AND NOW() BETWEEN `from` AND `to`
							 ".$WhBuID." AND BUActive = 0 ".$WhrT." ".$WhrAcc."
							 HAVING BUDist < ".$Dist;
		
				}
				
				$command = Yii::app() -> db -> createCommand($sql) -> queryAll();
		
				
				$offers = array();
				foreach ($command as $key => $row) {
					
					//--------Get Product Imgs
					$ImgSql = " SELECT pimgid, pimg_url
						   	 	    FROM products_imgs 
						   	 	    WHERE products_imgs.pid = " . $row['ProdID'];
					$ImgAll = Yii::app() -> db -> createCommand($ImgSql) -> queryAll();
					$Img = array();
	
					if (count($ImgAll) > 0) {
						$RealAdrr = Globals::ReturnGlobals();
						$ImgPath = $RealAdrr['ImgSerPath'] . 'products/thumbnails/';
						foreach ($ImgAll as $imgkey => $imgrow) {
							array_push($Img,array('Img'=>$ImgPath.$imgrow['pimg_url']));
						}
					}	
							
					array_push($offers, array('ofid' => $row['ofid'], 
											  'offTitle' => $row['offTitle'], 
											  'proId' => $row['ProdID'], 
											  'rate' => $row['ProdRate'], 
											  'text' => $row['text'], 
											  'discount' => $row['discount'], 
											  'from' => $row['from'], 
											  'to' => $row['to'], 
											  'BUID' => $row['BUID'], 
											  'BUTitle' => $row['BUName'],
											  'BUCurr' => $row['BUCurr'],
											  'scheduled' => $row['scheduled'], 
											  'proTitle' => $row['ProdName'], 
											  'price' => $row['ProdPrice'],
											  'proImg'=>$Img));
				}
	
				$ResArr = array('offers' => $offers);
			
			//} else {
					
			//	$ResArr = array('error'=>array("Code"=>"800","Message"=>"UnKnown Location"));
			//}
		//}else{

			//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}	
			
		echo json_encode($ResArr);
	}

	//--------------------------Order Functions---------------------

	public function actionAddToOrder() {
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		$ResArr = array();
		
		if (isset($_POST['order'])) {
				
			$OrdArr = $_POST['order'];
			
			$JsonArr = json_decode(trim($OrdArr),TRUE);
			
			$ResArr = CustLib::actionAddToOrder($JsonArr);
			
		} else {

			$ResArr = array('error' => array("code" => "206", "message" => "Invalid"));
		}
		echo json_encode($ResArr);
		
	}

	public function actionAddToOrderByQr()
	{
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		
		//$text = Yii::app()->session['User']['UserOwnerID']."-".Yii::app()->session['User']['UserBuid']."-".$prod_id;
		
		$ResArr = array();
		
		if (isset($_POST['order'])) {

			$OrdArr = $_POST['order'];
			
			$JsonArr = json_decode($OrdArr);
			
			$CustID = 0;
			if (isset($JsonArr -> cust_id)) {
				if ($JsonArr -> cust_id > 0) {$CustID = $JsonArr -> cust_id;}
			};
				
			$Hash = 0;
			if (isset($JsonArr -> hash)) {
				if ($JsonArr -> hash > 0) {$Hash = $JsonArr -> hash;}
			};
			
			
			$Lat = 0;
			if (isset($JsonArr->lat)) {
				if ($JsonArr->lat != '') {$Lat = $JsonArr->lat;}
			};
	
	
			$Long = '0';
			if (isset($JsonArr->long)) {
				if ($JsonArr->long != '') {$Long = $JsonArr->long;}
			};
			
			
			$Dist = self::$Distance;
			if (isset($JsonArr->dist)) {
				if ($JsonArr->dist != '') {$Dist = $JsonArr->dist;}
			};
				
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
					$cipher = new Cipher('secret passphrase');
			
					$decryptQR = $cipher->decrypt($JsonArr->qr);
					
					$Arr = explode('-', $decryptQR);
					
					$Owner = isset($Arr[0]) ? $Arr[0] > 0? $Arr[0]:0:0;
					$BUID = isset($Arr[1]) ? $Arr[1] > 0? $Arr[1]:0:0;
					$ProdID = isset($Arr[2]) ? $Arr[2] > 0 ? $Arr[2]:0:0;
					
					
					$ChkSQL= " SELECT * FROM products WHERE pid = ".$ProdID." AND buid = ".$BUID;
					$ChkD = Yii::app() -> db -> createCommand($ChkSQL) -> queryAll();
					
					if(!empty($ChkD)){
						$AllowDist = Orders::CHKDistance($BUID , $Dist , $Lat , $Long);
				
						//if(($Lat != 0 ||  $Long !=0) && $AllowDist == TRUE){
						
							$Ord = Orders::CHKCustomerHasOrder($JsonArr->cust_id);
					
							$OrderID = 0 ;
							$TotalChild = Orders::TotalChild($ProdID,1);
							if ($Ord['rows_count'] == 0) {
								
								$OrdSQL = "INSERT INTO orders (cid , status , app_type , ord_total ,c_curr_code)
										   VALUES (" . $JsonArr -> cust_id . " , 0 ,0)";
			
								Yii::app()->db->createCommand($OrdSQL)->execute();
								$OrderID = Yii::app()->db->getLastInsertID();
								
							} else {
								
								$OrderID = $Ord['res_id'];
							}
						
							if($OrderID > 0){
									
								$ChkDSQL = " SELECT * FROM orders_details WHERE pid = ".$ProdID." AND ord_id = ".$OrderID;
								$ChkDD = Yii::app() -> db -> createCommand($ChkDSQL) -> queryRow();
								
								if(empty($ChkDD)){
									 	
									 $OrdDSQL = "INSERT INTO orders_details (ord_id,ord_buid,pid,qnt,disc,price,fees,final_price,pay_type) 
												 VALUES(" . $OrderID . ",
												 		" . $BUID . ",
												 		" . $ProdID . ", 1 , 
												 		" . $TotalChild['discount'] . ", 
												 		" . $TotalChild['price'] . ",
												 		" . $TotalChild['fees'] . ",
												 		" . $TotalChild['f_price'] . ",0)";
								}else{
										
									$OrderDID = isset($ChkDD['ord_det_id'])?$ChkDD['ord_det_id']:0;
									$OrderQnt = isset($ChkDD['qnt'])?$ChkDD['qnt']:0;
									
									$OrderQnt = $OrderQnt + 1 ;
									
									$TotalChildN = Orders::TotalChild($ProdID,$OrderQnt);
									
									$OrdDSQL = " UPDATE orders_details SET qnt = qnt+1,
													disc = ".$TotalChildN['discount'].",
													price = ".$TotalChildN['price'].",
													fees = ".$TotalChildN['fees'].",
												    final_price = ".$TotalChildN['f_price']."		
												 WHERE ord_det_id = ".$OrderDID;
									
								}		
							
								Yii::app() -> db -> createCommand($OrdDSQL) -> execute();
								Orders::TotalOrder($OrderID);
								
								$ResArr = array("Result" => array("Id" => $OrderID, "message" => "TRUE"));
								
							}else{
								
								$ResArr = array('error' => array("code" => "701", "message" => "Invalid"));
							}
						//}else{
						//	$ResArr = array("Result" => array('error' => array("code" => "800", "message" => "UnKnown Location")));
						//}
					
					} else {
						
						$ResArr = array('error' => array("code" => "700", "message" => "Invalid QrCode"));
						
					}
					
			//} else {

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}
		} else {

			$ResArr = array('error' => array("code" => "206", "message" => "Invalid Json"));
		}
		
		echo json_encode($ResArr);
		
	}
	
	public function actionEditOrder() {
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		$ResArr = array();
		
		if (isset($_POST['order'])) {

			$OrdArr = $_POST['order'];

			//$OrdArr = '{"id": "12" ,"cust_id": "1","AppSource": "0","p_id": "11","qnt": "5","bu_id": "3","c_id": "10"}' ;
			$JsonArr = json_decode(trim($OrdArr),TRUE);

			$ResArr = CustLib::actionEditOrder($JsonArr);

		} else {

			$ResArr = array('error' => array("code" => "206", "message" => "Invalid"));
		}
		
		echo json_encode($ResArr);

	}

	public function actionRemoveFromOrder() {
			
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['order'])) {

			$OrdArr = $_POST['order'];
			
			$JsonArr = json_decode(trim($OrdArr),TRUE);
			
			$ResArr = CustLib::actionRemoveFromOrder($JsonArr);
			
		} else {

			$ResArr = array('error' => array("code" => "206", "message" => "Invalid"));
		}
		
		echo json_encode($ResArr);
	}
	
	public function actionRemoveBuFromOrder() {
			
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['order'])) {

			$OrdArr = $_POST['order'];
			
			$JsonArr = json_decode(trim($OrdArr),TRUE);
			
			$ResArr = CustLib::actionRemoveBuFromOrder($JsonArr);
			
		} else {

			$ResArr = array('error' => array("code" => "206", "message" => "Invalid"));
		}
		
		echo json_encode($ResArr);
	}

	public function actionCloseOrder(){
			
		header('Content-Type: application/json');
		$ResArr = array();
		
		if (isset($_POST['order'])) {

			$OrdArr = $_POST['order'];
			$JsonArr = json_decode(trim($OrdArr),TRUE);
			
			$ResArr = CustLib::actionCloseOrder($JsonArr);
			
		}else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	
	
	public function actionCloseQrCode(){
			
		header('Content-Type: application/json');
		$ResArr = array();
		
		if (isset($_POST['order'])) {

			$OrdArr = $_POST['order'];
			$JsonArr = json_decode(trim($OrdArr),TRUE);
			
			$ResArr = CustLib::actionCloseQrCode($JsonArr);
			
		}else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}

	public function actionViewOrders() {

		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['order'])) {

			$OrdArr = $_POST['order'];

			$JsonArr = json_decode($OrdArr,TRUE);
			
			$ResArr = CustLib::actionViewOrders($JsonArr);
			
			
		} else {

			$ResArr = array('error' => array("code" => "205", "message" => "Invalid Order Data"));
		}
		
		echo json_encode($ResArr);
	}

	public function actionViewOrdersAll() {
		header('Content-Type: application/json');
		//$OrdArr = '{"cust_id": "1"}';
		
		$_POST = CI_Security::ChkPost($_POST);
		
		$Sql = "SELECT orders.ord_id,ord_total,(CASE WHEN status = 0 THEN 'Opened' ELSE 'Closed' END)AS status,date(orders.created) AS orddate,
				 		ord_det_id , business_unit.title , ord_det.pid,item,qnt,disc,ord_det.price,fees,final_price,ord_buid , products.rating
				FROM orders 
				LEFT JOIN orders_details AS ord_det
					LEFT JOIN business_unit ON ord_det.ord_buid = business_unit.buid
				ON ord_det.ord_id = orders.ord_id 
				LEFT JOIN products ON ord_det.pid = products.pid
				ORDER BY orders.ord_id DESC";

		$Data = Yii::app() -> db -> createCommand($Sql) -> queryAll();

		$O_Arr = array();
		$OrdArr = array();

		foreach ($Data as $key => $row) {

			$OrdArr[$row['ord_id']]['ord_id'] = $row['ord_id'];
			$OrdArr[$row['ord_id']]['ord_total'] = $row['ord_total'];
			$OrdArr[$row['ord_id']]['status'] = $row['status'];
			$OrdArr[$row['ord_id']]['orddate'] = $row['orddate'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['ord_det_id'] = $row['ord_det_id'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['bu_id'] = $row['ord_buid'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['bu_name'] = $row['title'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['pid'] = $row['pid'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['p_name'] = $row['item'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['qnt'] = $row['qnt'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['disc'] = $row['disc'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['price'] = $row['price'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['fees'] = $row['fees'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['f_price'] = $row['final_price'];
			$OrdArr[$row['ord_id']]['details'][$row['ord_det_id']]['rate'] = $row['rating'];

		}

		foreach ($OrdArr as $key => $row) {

			array_push($O_Arr, array('ord_id' => $row['ord_id'], 'ord_total' => $row['ord_total'], 'status' => $row['status'], 'orddate' => $row['orddate'], 'details' => array_values($row['details']), ));

		}

		echo json_encode(array('orders' => $O_Arr));

	}

	public function actionViewOrderByID() {
			
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		$ResArr = array();
		if(isset($_POST['order'])){

			$OrdArr = $_POST['order'];
			
			$JsonArr = json_decode($OrdArr,TRUE);
				
			$ResArr = CustLib::actionViewOrderByID($JsonArr);	
		
		}else {

			$ResArr = array('error' => array("code" => "205", "message" => "Invalid Order Data"));
		}
		
		echo json_encode($ResArr);
    }

	public function actionGetOpenedOrderID()
	{
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		
		$ResArr = array();
		$CustID = 0;

		if (isset($_GET['CustID'])) {

			if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];}
		};
		$Hash = 0;

		if (isset($_GET['Hash'])) {

			if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];}
		};
		
		//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
					
			$SQL = " SELECT ord_id FROM orders WHERE cid = ".$CustID." AND status = 0 ";	
			$IDRes = Yii::app()->db->createCommand($SQL)->queryRow();
			
			if(!empty($IDRes)){
				
				$ResArr = array('OrdID'=>$IDRes['ord_id']);
				
			}else{
				
				$ResArr = array('OrdID'=>'0');
			}
			
		//}else{

		//	$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
		//}

		echo json_encode($ResArr);
	}
	
	//-------------------------  Currency ----------------------------------
	
	public function actionGetConvertCurrency()
	{
		//////$_GET = CI_Security::ChkPost($_GET);
			
		$Frm = '';
		if (isset($_GET['Frm'])) {

			if ($_GET['Frm'] != '') {$Frm = $_GET['Frm'];}
		};
			
		$To = 0;
		if (isset($_GET['To'])) {

			if ($_GET['To'] != '') {$To = $_GET['To'];}
		};
			
		$Val = 0;
		if (isset($_GET['Val'])) {

			if ($_GET['Val'] != '') {$Val = $_GET['Val'];}
		};

		$ResArr = array();
		
		$CurrArr = Currency::ConvertCurrency($Frm,$To,$Val);
		
		echo json_encode($CurrArr);
	}
	
	//-------------------------Customer Functions --------------------------

	public function actionRegisterCustomer() {
		header('Content-Type: application/json');
		
		$ResArr = array();

		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr,TRUE);
			
			$ResArr = CustLib::actionRegisterCustomer($CustJson);
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);
	}

	public function actionCustomerSocial()
	{
		header('Content-Type: application/json');
		
		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr,TRUE);
			
			$ResArr = CustLib::actionCustomerSocial($CustJson);
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	
	public function actionLoginCustomer() {
			
		header('Content-Type: application/json');
		
		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr,TRUE);
			
			$ResArr = CustLib::actionLoginCustomer($CustJson);
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}

	public function actionAutoLoginCustomer() {
		header('Content-Type: application/json');
		$_POST = CI_Security::ChkPost($_POST);
		/*
		 $CustArr = '{"cust_id":"1",
		 "hash":"123"
		 }';*/

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

				$ResArr = array('error' => array("code" => "215", "message" => "Invalid Customer"));
			}

		} else {

			$ResArr = array('error' => array("code" => "202", "message" => "NO Json Ture Data"));
		}

		echo json_encode($ResArr);
	}

	public function actionLogoutCustomer() {
			
		header('Content-Type: application/json');

		$ResArr = array();

		if (isset($_POST['customer'])) {
				
			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr,TRUE);
			
			$ResArr = CustLib::actionLogoutCustomer($CustJson);

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);

	}

	public function actionImageCustomer()
	{
		$ResArr = array();
		
		if (isset($_REQUEST['CustID']) && isset($_REQUEST['image']) && isset($_REQUEST['imgname']) ) {
				
			$Arr = $_REQUEST;
			$ResArr = CustLib::actionImageCustomer($Arr);
			
		} else {
				
			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
			
	}
	
	public function actionTextImageCustomer()
	{
			$RealArr = Globals::ReturnGlobals();
			$RealPath = $RealArr['ImgPath'] . 'business_unit/';
				
			$fp = fopen($RealPath.'1421313614-CuteDoll_1.jpg', 'r');
			
		
		
			$db_img = fread($fp,filesize($RealPath.'1421313614-CuteDoll_1.jpg'));
			//$db_img = addslashes($db_img);
			$db_img = base64_encode($db_img);
			
		//var_dump($db_img);
			//$db_img = 'CustImg={"CustID":"1","imgname":"1421313614-CuteDoll_1.jpg","image":"'.$db_img.'"}';
			//$db_img = 'CustImg={"imgname":"1421313831-CuteDoll_2.jpg","CustID":"1","image":"/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAIBAQEBAQIBAQECAgICAgQDAgICAgUEBAMEBgUGBgYFBgYGBwkIBgcJBwYGCAsICQoKCgoKBggLDAsKDAkKCgr/2wBDAQICAgICAgUDAwUKBwYHCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgoKCgr/wAARCAFAAPADASIAAhEBAxEB/8QAHgAAAgMBAQEBAQEAAAAAAAAABwgFBgkEAwIKAQD/xABEEAABAwMDAwIEBAUBBwIEBwABAgMEBQYRAAchCBIxE0EJIlFhFDJxgRUjQlKRoRYXJDNiscFDcgoYgtElNFNjouHx/8QAHAEAAQUBAQEAAAAAAAAAAAAABQIDBAYHAQAI/8QANBEAAQMDAwMCBAUDBQEAAAAAAQACAwQFERIhMQYTQVFhFCIycQcVI4GRQsHRQ1JiobFy/9oADAMBAAIRAxEAPwDQrrquSy61"}';
			$db_img1 = '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';        
			
			$db_img = "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
/9k=";
//var_dump($db_img);
			
			 
			//$db_img = str_replace(array("\n","\r","\r\n"),$db_img);
			$db_img = 'image='.$db_img;
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, "http://192.168.1.3/kinjo/public/index.php/API/ImageCustomer?CustID=1&&imgname=1421313614-CuteDoll_1.jpg"); 
			curl_setopt($ch, CURLOPT_POSTFIELDS,  $db_img);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_exec($ch);
			curl_close($ch);
			
			
	}
	
	public function actionTestPost()
	{
		// $pram = "UserName=DeliveryBoy&Password=MTIz";
		// $pram = 'reserve={"cid":"2","unitID":"201","from":"2015-01-08 15:35:00","to":"2015-01-08 16:47:20","res_type":"TA"}';
		// $pram = 'reserve={"from":"2015-01-08 15:35:00","to":"2015-01-08 16:47:20","res_type":"TA"}';		
		// $pram = 'unit={"from":"2015-01-08 18:00:00","to":"2015-01-08 19:30:00","res_type":"TA","persons_no":"2"}';
		//$pram = 'customer={"reg_id":"APA91bERTK2MJpm1Q87gCAviRE39Vkbk_RHsW9jsDZ6mVFMgAPFmzb87v4UDH37JdX0jCGB6eE3VM9XPW1hra5-fie_AQXvfmdFiqvOW7Ljdx2uilDM2yDqiTcmYfAyMlhI3wuofPnW5e1mJpuKWw3iVmxJBdrwoYg","AppSource":"0","long":"29.9714225","email":"shimaamohamed@gmail.com","lat":"31.2441187","dev_id":"6b13df2fedcf0b53","pass":"123"}';
		$ch = curl_init();//http://192.168.1.3/kinjo/public/index.php/APIApp/DBSignIn
		// curl_setopt($ch, CURLOPT_URL, "192.168.1.3/kinjo/public/index.php/APIApp/DBSignIn");
		// curl_setopt($ch, CURLOPT_URL, "http://kinjo.local/index.php/APIApp/DBSignIn");
		// curl_setopt($ch, CURLOPT_URL, "http://kinjo-app.com/index.php/API/LoginCustomer");
		// curl_setopt($ch, CURLOPT_URL, "mykinjo/index.php/API/Reserve");
		// curl_setopt($ch, CURLOPT_URL, "mykinjo/index.php/API/GetAvUnitsByD");
		//curl_setopt($ch, CURLOPT_URL, "mykinjo/index.php/API/GetAvTimeForUnit");
		
		$pram = 'customer={"fname":"Asmaa","lname":"Ali","email":"asmaa@Gmail.com","pass":"123","gender":"1","b_date":"1990-1-20","coun_id":"GB","AppSource":"0"}';
		curl_setopt($ch, CURLOPT_URL, "http://kinjo-app.com/index.php/API/RegisterCustomer");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $pram);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_exec($ch);
		curl_close($ch);
	}
	
	public function actionUpdateCustomer()	
	{
		header('Content-Type: application/json');
		
		 $ResArr = array();
		 
		 if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr,TRUE);
			
			$ResArr = CustLib::actionUpdateCustomer($CustJson);
			
		} else {

			$ResArr = array('error' => array("code" => "202", "message" => "NO Json Ture Data"));
		}
		
		echo json_encode($ResArr);
	}

	public function actionGetCustomer()
	{
	//	header('Content-Type: application/json');
		
		$ResArr = array();
		 
		$Arr = array();
		
		$Arr = $_GET;
		
		$ResArr = CustLib::actionGetCustomer($Arr);
			
		echo json_encode($ResArr);
	}	
	
	public function actionCustAddShippingAddr()
	{
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		$ResArr = array();
		
		if (isset($_POST['shippingAdd'])) {
				
			$ShippingAdd = $_POST['shippingAdd'];
			
			$JsonArr = json_decode(trim($ShippingAdd),TRUE);
			
			$CountSql = " SELECT * FROM country WHERE iso = '" . $JsonArr['ship_country']. "'";
			$CountRes = Yii::app() -> db -> createCommand($CountSql) -> queryRow();
			
			$JsonArr['ship_country'] = $CountRes['country_id'];
			$ResArr = CustLib::actionAddShippingAddr($JsonArr);
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}

	public function actionCustSetAddrDefault()
	{
		header('Content-Type: application/json');
		//$_POST = CI_Security::ChkPost($_POST);
		
		$ResArr = array();
		
		if (isset($_POST['Default'])) {
				
			$Default = $_POST['Default'];
			
			$JsonArr = json_decode(trim($Default),TRUE);
		
			$ResArr = CustLib::actionCustSetAddrDefault($JsonArr);
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	
	public function actionResetPasswordCustomer() {
			
		header('Content-Type: application/json');
		$ResArr = array();

		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr,TRUE);

			$ResArr = CustLib::actionResetPasswordCustomer($CustJson);

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);

	}

	public function actionSendNotification($RegIDs, $MessID) {
		//header('Content-Type: application/json');
		$MessSql = " SELECT * FROM messages WHERE mid = " . $MessID;
		$MessRes = Yii::app() -> db -> createCommand($MessSql) -> queryRow();
		$Mess = $MessRes['message'];

		GCM::SendNotification($RegIDs, $Mess);

	}

	public function actionSendNotify() {
			
		header('Content-Type: application/json');
		
		$ResArr = array();

		if (isset($_POST['customer'])) {

			$CustArr = $_POST['customer'];

			$CustJson = json_decode($CustArr,TRUE);

			$ResArr = CustLib::actionSendNotify($CustJson);

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}

		echo json_encode($ResArr);

	}

	public function actionEnableSendNotify()
	{
		 header('Content-Type: application/json');	
		
		 $ResArr = array();
		 
		 if (isset($_POST['customer'])) {
		 	
			$CustArr = $_POST['customer'];

			$JsonArr = json_decode($CustArr,TRUE);

			$ResArr = CustLib::actionEnableSendNotify($JsonArr);
			
		 } else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
		 
	}
	
	public function actionAddWishList() 
	{
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['wishlist'])) {

			$WishArr = $_POST['wishlist'];

			$JsonArr = json_decode($WishArr,TRUE);

			$ResArr = CustLib::actionAddWishList($JsonArr);

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	
	public function actionRemoveWishList() 
	{
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['wishlist'])) {

			$WishArr = $_POST['wishlist'];

			$JsonArr = json_decode($WishArr,TRUE);

			$ResArr = CustLib::actionRemoveWishList($JsonArr);

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	
	public function actionGetWishList() 
	{
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		$Arr = array();
		
		$Arr = $_GET;
		
		$ResArr = array();
		
		$ResArr = CustLib::actionGetWishList($Arr);
		
		echo json_encode($ResArr);
	}

	public function actionGetWishListAddRemove($CustID,$Lang) 
	{
		$ResArr = array();
			
		if ($Lang != 0 && $Lang != 2){
				
			$Sql = "SELECT products.pid AS ProID,
						   (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProTitle,
						   (CASE WHEN p_lang_discription IS NULL THEN products.discription ELSE p_lang_discription END) AS ProDesc,
						   (CASE WHEN p_lang_price IS NULL THEN products.price ELSE p_lang_price END) AS ProPrice,
						   products.rating AS ProRate,business_unit.buid AS BUID,
						   (CASE WHEN bu_lang_title IS NULL THEN business_unit.title ELSE bu_lang_title END) AS BUTitle,
						   business_unit.long AS BULong,business_unit.lat AS BULat,business_unit.logo AS BULogo
					FROM wishlist
					LEFT JOIN products 
						 LEFT JOIN business_unit 
							  LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = ".$Lang."
						 ON business_unit.buid = products.buid
					ON wl_pid = pid
					LEFT JOIN products_lang ON p_lang_pid = products.pid AND p_lang_lang_id = ".$Lang."
					WHERE wl_cid = ".$CustID." AND business_unit.active = 0 ORDER BY business_unit.type ";
			
		}else{
				
			$Sql = "SELECT products.pid AS ProID,products.title AS ProTitle,products.discription AS ProDesc,products.price AS ProPrice,
						   products.rating AS ProRate,
						   business_unit.buid AS BUID,business_unit.title AS BUTitle,business_unit.long AS BULong,business_unit.lat AS BULat,
						   business_unit.logo AS BULogo
					FROM wishlist
					LEFT JOIN products 
						 LEFT JOIN business_unit ON business_unit.buid = products.buid
					ON wl_pid = pid
					WHERE wl_cid = ".$CustID." AND business_unit.active = 0  ORDER BY business_unit.type ";
			
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
									    'gps' => array('BULong' => $row['BULong'], 'BULat' => $row['BULat'])
										)
							);
			
			}
		}
		$ResArr = array("Products"=>$DArr);
		return $ResArr ;
		
		
	}	

	//----------------------------------------------------------------

	public function actionGetNearOffers(){
		header('Content-Type: application/json');
		//////$_GET = CI_Security::ChkPost($_GET);
		/*
		 $offer ='{
		 "cust_id": "1"
		 "lat": "31.225933942095836"
		 "long":"29.91174476562503"
		 "lang" :"1"
		 }';*/

		$ResArr = array();

		/*
		if (isset($_POST['offer'])) {
		
			$OfferArr = $_POST['offer'];
			$JsonArr = json_decode($OfferArr);*/
			$CustID = 0;

			if (isset($_GET['CustID'])) {
	
				if ($_GET['CustID'] > 0) {$CustID = $_GET['CustID'];}
			};
			$Hash = '0';
	
			if (isset($_GET['Hash'])) {
	
				if ($_GET['Hash'] != '') {$Hash = $_GET['Hash'];}
			};
			
			$lat = 0;

			if (isset($_GET['lat'])) {
	
				if ($_GET['lat'] > 0) {$lat = $_GET['lat'];}
			};
			$long = 0;
	
			if (isset($_GET['long'])) {
	
				if ($_GET['long'] != '') {$long = $_GET['long'];}
			};
			
			$D = self::$Distance;
	
			if (isset($_GET['D'])) {
	
				if ($_GET['D'] > 0) {$D = $_GET['D'];}
			};
			
			$BuAcc = 0 ;
			if (isset($_GET['BuAcc'])) {
	
				if ($_GET['BuAcc'] > 0) {$BuAcc = $_GET['BuAcc'];}
			};
			
			$WhrAcc = ' ';
			
			if($BuAcc > 0){
					
				$WhrAcc = " AND business_unit.buid IN (SELECT buid FROM business_unit WHERE accid = ".$BuAcc.")";
			}		
			//if(Login::ChkCustomerHash($CustID,$Hash)== TRUE){
				//if($lat != 0 || $long != 0){
					if($CustID > 0){
							
						$E_N_SQL = " SELECT SUBSTRING(notify_enable,3,1) AS Not_Enable FROM customers WHERE cid = ".$CustID;
						$E_N_Data = Yii::app()->db->createCommand($E_N_SQL)->queryRow();
					
					}else{
							
						$E_N_Data = array();
						$E_N_Data['Not_Enable'] = '0';
					}
					
					if(!empty($E_N_Data)){
						
						if($E_N_Data['Not_Enable']== '0'){
							
							$Lang = 0;
				
							if (isset($_GET['Lang'])) {
				
								if ($_GET['Lang'] > 0) {$Lang = $_GET['Lang'];}
							};
				
							if ($Lang != 0 && $Lang != 2) {
				
								$SQL = "SELECT offers.ofid AS OfferID,
											  (CASE WHEN offer_lang_title IS NULL THEN offers.title ELSE offer_lang_title END) AS OfferTitle,
											  (CASE WHEN offer_lang_text IS NULL THEN offers.text ELSE offer_lang_text END) AS OfferText,
											  (CASE WHEN offer_lang_discount IS NULL THEN offers.discount ELSE offer_lang_discount END) AS OfferDisc,
											  `from` AS OfferFrom,`to` AS OfferTo,
											   products.pid AS ProdID,
											  (CASE WHEN p_lang_title IS NULL THEN products.title ELSE p_lang_title END) AS ProdTitle,
											  (CASE WHEN p_lang_price IS NULL THEN products.price ELSE p_lang_price END) AS ProdPrice,
											   business_unit.buid AS BUID , 
											  (CASE WHEN bu_lang_title IS NULL THEN business_unit.title ELSE bu_lang_title END) AS BUTitle,
											   business_unit.logo AS BULogo,business_unit.lat AS BULat , business_unit.long AS BULong,
											   (((acos(sin((".$lat."*pi()/180)) * 
										            sin((business_unit.lat*pi()/180)) + cos((".$lat."*pi()/180)) * 
										            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
										            pi()/180))))*180/pi())*60*1.1515
										        ) as Distance
										FROM offers
										LEFT JOIN offers_lang ON offer_lang_offer_id = offers.ofid AND offer_lang_lang_id = " . $Lang . "
										LEFT JOIN products 
											LEFT JOIN business_unit 
												LEFT JOIN business_unit_lang ON bu_lang_bu_id = business_unit.buid AND bu_lang_lang_id = " . $Lang . "
											ON business_unit.buid = products.buid
										ON products.pid = offers.pid 
										LEFT JOIN products_lang ON p_lang_pid = products.pid AND p_lang_lang_id = " . $Lang . "
										WHERE NOW() BETWEEN `from` AND `to` AND offers.active = 1 AND business_unit.active = 0 ".$WhrAcc."
										HAVING Distance < ".$D;
				
							} else {
								
									$SQL = "SELECT offers.ofid AS OfferID,offers.title AS OfferTitle,offers.text AS OfferText,offers.discount AS OfferDisc,
											`from` AS OfferFrom,`to` AS OfferTo,
												   products.pid AS ProdID,products.title AS ProdTitle,products.price AS ProdPrice,
												   business_unit.buid AS BUID , business_unit.title AS BUTitle,business_unit.logo AS BULogo,
												   business_unit.lat AS BULat , business_unit.long AS BULong,
												   (((acos(sin((".$lat."*pi()/180)) * 
											            sin((business_unit.lat*pi()/180)) + cos((".$lat." * pi()/180)) * 
											            cos((business_unit.lat*pi()/180)) * cos(((".$long."- business_unit.long)* 
											            pi()/180))))*180/pi())*60*1.1515
											        ) as Distance
											FROM offers
											LEFT JOIN products 
												LEFT JOIN business_unit ON business_unit.buid = products.buid
											ON products.pid = offers.pid 
											WHERE NOW() BETWEEN `from` AND `to` AND offers.active = 1 AND business_unit.active = 0 ".$WhrAcc."
											HAVING Distance < ".$D;
				
							}
				
							$Data = Yii::app() -> db -> createCommand($SQL) -> queryAll();
				
							/*
							//------------------------- Get Reg ID -----------------
										
							$RegSQl = " SELECT puid,cid,gcm_regid FROM push_notifications 
										WHERE puid = (SELECT puid FROM push_notifications WHERE cid = " .$CustID. " ORDER BY count_dev DESC LIMIT 0,1)";
							$RegData = Yii::app() -> db -> createCommand($RegSQl) -> queryRow();
				
							$RegID = '';
							$PuID = '';
							if (!empty($RegData)) {
				
								$RegID = $RegData['gcm_regid'];
								$PuID = $RegData['puid'];
				
							}
							if ($RegID != '' && $PuID > 0) {*/
							$BuArr = array();
				
								foreach ($Data as $key => $row) {
				
									//-------Get Product Img
									$ImgSql = " SELECT pimgid, pimg_url
										   	 	    FROM products_imgs 
										   	 	    WHERE products_imgs.pid = " . $row['ProdID'] . " LIMIT 0,1 ";
									$ImgRow = Yii::app() -> db -> createCommand($ImgSql) -> queryRow();
									$Img = '';
									if (!empty($ImgRow)) {
										$RealAdrr = Globals::ReturnGlobals();
										$ImgPath = $RealAdrr['ImgSerPath'];
										$Img = $ImgPath . 'products/thumbnails/' . $ImgRow['pimg_url'];
									}
									/*
									//--------Create Message
									$Mess = "OfferID = " . $row['OfferID'] . ";@;ProdID = " . $row['ProdID'] . ";@;BUID = " . $row['BUID'] . ";@;
											 OfferTitle = " . $row['OfferTitle'] . ";@;ProdTitle = " . $row['ProdTitle'] . ";@;BUTitle = " . $row['BUTitle'] . "
											 ;@;ProdImg = " . $Img . ";@;BULat = " . $row['BULat'] . ";@;BULong = " . $row['BULong'] . "";
				
									//--------Add message log
									$SQLMess = " INSERT INTO messages_log (mid,cid,puid,is_group) VALUES (" . $row['OfferID'] . "," .$CustID. "," . $PuID . ", 2)";
									Yii::app() -> db -> createCommand($SQLMess) -> execute();
				
									//------- Push Notifications
									GCM::SendNotification(array($RegID), $Mess);*/
									
									array_push($BuArr,array('OfferID'=>$row['OfferID'],
															'OfferTitle'=>$row['OfferTitle'],
															'ProdID'=>$row['ProdID'],
															'ProdTitle'=>$row['ProdTitle'],
															'ProdImg'=>$Img,
															'BUID'=>$row['BUID'],
															'BUTitle'=>$row['BUTitle'],
															'BULat'=>$row['BULat'],
															'BULong'=>$row['BULong'],
															'Distance'=>$row['Distance']));
									
									
								}
				
								$ResArr = array("Result" => array('Offers' => $BuArr));
				
							/*
							} else {
										
								$ResArr = array("Result" => array('error' => array("code" => "208", "message" => "NO RegID Data")));
							}*/
						//} else {
					
						//	$ResArr = array('error'=>array("Code"=>"800","Message"=>"UnKnown Location"));
						//}
					}else{

						$ResArr = array('error'=>array("Code"=>"300","Message"=>"Customer Disabled This Notification"));
					}	
				}
			//}else{

				//$ResArr = array('error'=>array("Code"=>"200","Message"=>"Invalid Permission"));
			//}	
			
		/*
		} else {
		
			$ResArr = array("Result" => array('error' => array("code" => "202", "message" => "NO Json Ture Data")));
		}*/
		

		echo json_encode($ResArr);

	}

	public function actionGetProdDetailsByOfferID() {
		header('Content-Type: application/json');
		////$_GET = CI_Security::ChkPost($_GET);
		
		$Arr = array();
		
		$Arr = $_GET;
		
		$ResArr = array();
		
		$ResArr = CustLib::actionGetProdDetailsByOfferID($Arr);
		
		echo json_encode($ResArr);
	}

	public function actionGetProdDetailsByProdID() {
		header('Content-Type: application/json');
		////$_GET = CI_Security::ChkPost($_GET);
		
		$Arr = array();
		
		$Arr = $_GET;
		
		$ResArr = array();
		
		$ResArr = CustLib::actionGetProdDetailsByProdID($Arr);
		
		echo json_encode($ResArr);

	}

	//----------------------------------------------------------------
	public function PPFunc()
	{
		require_once( dirname(__FILE__) . '/../helpers/sdk-core-php/autoload.php');
		
		PPOpenIdSession::getAuthorizationUrl($redirectUri, array('openid', 'address'));
	}
	
	public function actionTestPayPal()
	{
		// Sets config file path(if config file is used) and registers the classloader
	    //require("PPBootStrap.php");
		
		//$this->PPFunc();return;
		require("vendor/autoload.php");
		require (PayPal\PayPalAPI\RefundTransactionRequestType);
		require PayPal\PayPalAPI\RefundTransactionReq;
		require PayPal\CoreComponentTypes\BasicAmountType;
		require PayPal\Service\PayPalAPIInterfaceServiceServic;
		//require_once(Yii::getPathOfAlias('application.helpers.PayPal') . '/PPBootStrap.php');
		
	    // Array containing credentials and confiuration parameters. (not required if config file is used)
	   $config = array(
	       'mode' => 'sandbox',
	       'acct1.UserName' => 'jNET.CHARIZMA@GMAIL.COM',
	       'acct1.Password' => 'mio@2014'
	    );
	  
	   
	   
	    // Create request details
		$itemAmount = new BasicAmountType($currencyId, $amount);
		$setECReqType = new SetExpressCheckoutRequestType();
		$setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
		
	}
	
	public function actionTestCrypter()
	{
		$ApiCrypter = new ApiCrypter();
		
		$StrEncrypt = $ApiCrypter->encrypt('asmaa_ali@Gmail.com-1313wsewse');
		var_dump($StrEncrypt);
		$StrDecrypt = $ApiCrypter->decrypt($StrEncrypt);
		var_dump($StrDecrypt);
	}
	public function actionTestCipher()
	{
		$Cipher = new Cipher('secret passphrase');
		$EncryptedQr = $Cipher->decrypt('qcVLfJrSE0xzy6BM2D38vbOlB+TOBaxY0YFHoVhgUI4=');
		var_dump($EncryptedQr);
	}
	// ------------------------- Reservations ---------------------------
	
	public function actionGetAvUnitsByD()
	{
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['unit'])) {

			$Arr = $_POST['unit'];

			$JsonArr = json_decode($Arr,TRUE);

			$ResArr = CustLib::actionGetAvUnitsByD($JsonArr); 

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	
	
	
	public function actionGetAvTimeForUnit()
	{
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['unit'])) {

			$Arr = $_POST['unit'];

			$JsonArr = json_decode($Arr,TRUE);

			$ResArr = CustLib::actionGetAvTimeForUnit($JsonArr);
			
		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	
	
	
	
	
	public function actionReserve()
	{
		header('Content-Type: application/json');
		
		$ResArr = array();
		
		if (isset($_POST['reserve'])) {

			$reserveArr = $_POST['reserve'];

			$JsonArr = json_decode($reserveArr,TRUE);

			$ResArr = CustLib::actionReserve($JsonArr);

		} else {

			$ResArr = array('error' => array("code" => "200", "message" => "Invalid Data"));
		}
		
		echo json_encode($ResArr);
	}
	/*   --------------------------------   */

	/*
	 public function actionAdminPayOrder()
	 {
	 Twocheckout::username('netCharizma');
	 Twocheckout::password('Mio@2014');
	 Twocheckout::sandbox(true);
	 Twocheckout::format('json');

	 $args = array(
	 'sale_id' => 9093720058206 // 4834917619
	 );
	 try {
	 echo $result = Twocheckout_Sale::stop($args);
	 //$x = Twocheckout_Payment::pending();
	 //echo $x;
	 } catch (Twocheckout_Error $e) {
	 echo  $e->getMessage();
	 }

	 }
	 */

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
}

/*
 $ip  = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
 $url = "http://freegeoip.net/json/$ip";
 $ch  = curl_init();

 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
 $data = curl_exec($ch);
 curl_close($ch);

 if ($data) {
 $location = json_decode($data);

 $lat = $location->latitude;
 $lon = $location->longitude;

 $sun_info = date_sun_info(time(), $lat, $lon);
 print_r($sun_info);
 }
 * */

 
 
 /*
 function actionhalfHourTimes() {
   $formatter = function ($time) {
     if ($time % 3600 == 0) {
       return date('ga', $time);
     } else {
       return date('g:ia', $time);
     }
   };
   $halfHourSteps = range(0, 47*1800, 1800);
   var_dump(array_map($formatter, $halfHourSteps)) ;return;
  return array_map($formatter, $halfHourSteps);
 }*/
 
 
 
 
 
 
 

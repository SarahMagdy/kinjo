<?php

class HomeController extends Controller
{
	
	public $layout='//layouts/column2';
	public function init()
	{
		// use Facebook\FacebookSession;
		// use Facebook\FacebookRedirectLoginHelper;
		// use Facebook\FacebookRequest;
		// use Facebook\FacebookResponse;
		// use Facebook\FacebookSDKException;
		// use Facebook\FacebookRequestException;
		// use Facebook\FacebookAuthorizationException;
		// use Facebook\GraphObject;
		// use Facebook\Entities\AccessToken;
		// use Facebook\HttpClients\FacebookCurlHttpClient;
		// use Facebook\HttpClients\FacebookHttpable;
			
			
				// use 'Facebook/FacebookSession';
		// use 'Facebook/FacebookRequest';
		// use 'Facebook/GraphUser';
		// use 'Facebook/FacebookRequestException';
	}
	
	public function actionStores()
	{	
		$arr = array();
		$ordOPen = array();
		
		session_start();
		
		if(!isset(Yii::app()->session['Cust']) || empty(Yii::app()->session['Cust'])){
		// echo '<pre/>';
		// print_r($_SERVER);
		// return;
			$jsonArr = file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']);
			$location = json_decode($jsonArr , TRUE);
	 		
	 		$CustArr['CustLat']  = $location['latitude'];
			$CustArr['CustLong'] = $location['longitude'];
			$CustArr['CustRemoteAddr'] = $_SERVER['REMOTE_ADDR'];
			Yii::app()->session['Cust'] = $CustArr;
		}elseif(isset(Yii::app()->session['Cust']['CustID']) && !empty(Yii::app()->session['Cust']['CustID'])){
			$ordOPen = $this->actionOrderData();
		}
		
		
		// echo '<pre/>';
		// print_r($_SESSION);
		// print_r(Yii::app()->session);
		// return;
		
		// $arr['Long'] = $location['longitude'];
		// $arr['Lat']  = $location['latitude'];
		$arr['Long'] = Yii::app()->session['Cust']['CustLong'];
		$arr['Lat']  = Yii::app()->session['Cust']['CustLat'];
		$allStores = CustLib::actionGetAllStores($arr);
		
		$pagingArr = $this->CreatePagingLinks($allStores['stores']);
		
		$pagingStores = $this->getPage($allStores['stores'], $pagingArr['page'] , $pagingArr['limit']);
		
		
		$this->renderPartial('stores' , array('allStores'=>$pagingStores , 'pagingArr'=>$pagingArr , 'ordOPen'=>$ordOPen));
	}
	
	public function CreatePagingLinks($arr)
	{
		$pagingArr = array();
		$_total = count($arr);
		// $_limit = 3;		
		(isset($_GET['limit'])) ? $_limit = $_GET['limit'] : $_limit = 2;
		(isset($_GET['page'])) ? $_page = $_GET['page'] : $_page = 1;
		$links  = 1;
		
		// $last  = ceil( $_total / $_limit );
	    // $start = (( $_page - $links ) > 0) ? $_page - $links : 1;
	    // $end   = (( $_page + $links ) < $last) ? $_page + $links : $last;
		
		$pagingArr['last'] = ceil( $_total / $_limit );
		$pagingArr['start'] = (( $_page - $links ) > 0) ? $_page - $links : 1;
		$pagingArr['end'] = (( $_page + $links ) < $pagingArr['last']) ? $_page + $links : $pagingArr['last'];
		$pagingArr['page'] = $_page;
		$pagingArr['limit'] = $_limit;
		
		return $pagingArr;
	}
	
	public function getPage($array, $pageNumber, $pageSize)
	{
	    // If $pageNumber is 1-based then use '++$pageNumber'
	    // $start = ++$pageNumber * $pageSize;
	    $start = --$pageNumber * $pageSize;
	    return array_slice($array, $start, $pageSize);
	}
	
	public function actionGetCat($id)
	{
		$ordOPen = $this->actionOrderData();
			
		$CatSql = "SELECT * FROM CatAndCatSub WHERE BUID =".$id;
		$CatRes = Yii::app()->db->createCommand($CatSql)->queryAll();
		
		$P_Arr['cat'] = array();
		$pagingArr = array();
		
		$BuSql = Yii::app()->db->createCommand("SELECT title , description 
												FROM business_unit WHERE buid = ".$id)->queryRow();
		$P_Arr['BuName'] = $BuSql['title'];
		$P_Arr['BuDescription'] = $BuSql['description'];
		
		if(!empty($CatRes)){
			foreach ($CatRes as $key => $val) {
				if($val['BCatID'] == $val['ParCatID']){
					array_push($P_Arr['cat'] , $val);
				}
			}
		
			$pagingArr = $this->CreatePagingLinks($P_Arr['cat']);
			$P_Arr['cat'] = $this->getPage($P_Arr['cat'], $pagingArr['page'] , $pagingArr['limit']);
			
			$this->renderPartial('store_cat' , array('P_Arr'=>$P_Arr , 'pagingArr'=>$pagingArr , 'ordOPen'=>$ordOPen));
		}else{
			// $this->actionSubCatPro();	
			header('Location: /home/SubCatPro/?BUID='.$id);
		}
		
		// $this->renderPartial('store_cat' , array('P_Arr'=>$P_Arr , 'pagingArr'=>$pagingArr , 'ordOPen'=>$ordOPen));
	}
	
	public function actionSubCatPro()
	{
		if(isset($_GET['catID'])){ $arr['ParCatID'] = $_GET['catID']; }
		if(isset($_GET['subID'])){ $arr['SubCatID'] = $_GET['subID']; }
		if(isset($_GET['BUID'] )){ $arr['BUID']     = $_GET['BUID'];  }
			// print_r($arr);return;
		$ordOPen = $this->actionOrderData();
		$SubCatRes = array();
		
		if(isset($arr['SubCatID'])){
			$SubCatSql = "SELECT * FROM CatAndCatSub WHERE ParCatID =".$arr['ParCatID'] ." AND SubCatID <> '' ";
			$SubCatRes = Yii::app()->db->createCommand($SubCatSql)->queryAll();
		}
		$prodArr = CustLib::actionSearchProduct($arr);
		// return;

		$pagingArr = array();
		if (!array_key_exists("error" , $prodArr)){
			$pagingArr = $this->CreatePagingLinks($prodArr['Products']);
			$prodArr = $this->getPage($prodArr['Products'], $pagingArr['page'] , $pagingArr['limit']);
		}
		// echo '<pre/>';
		// print_r($prodArr);
		// return;
		$this->renderPartial('Subcats' , array('SubCatRes'=>$SubCatRes , 'prodArr'=>$prodArr , 'pagingArr'=>$pagingArr ,'ordOPen'=>$ordOPen));
	}
	
	
	public function actionLogin()
	{
		// $CountrySql = "SELECT * FROM country ";
		// $CountryRes = Yii::app()->db->createCommand($CountrySql)->queryAll();
			
		if(isset($_POST['email']) && isset($_POST['password'])){
		// if(!isset(Yii::app()->session['Cust']) && empty(Yii::app()->session['Cust']['CustID'])){
				
			$CustSql = " SELECT * FROM customers WHERE email = '" . $_POST['email'] . "' AND password = '". md5($_POST['password']) ."'";
			$CustRes = Yii::app()->db->createCommand($CustSql)->queryRow();
			
			
			
			if(!empty($CustRes) ){
				
								
				$session = yii::app()->session;
				$CustArr = $session['Cust'];
				
				$CustArr['CustID']    = $CustRes['cid'];
				$CustArr['CustMail']  = $CustRes['email'];
				$CustArr['CustPass']  = $CustRes['password'];
				$CustArr['CustFName'] = $CustRes['fname'];
				$CustArr['CustLName'] = $CustRes['lname'];
				$CustArr['CustHash']  = $CustRes['hash'];
				$CustArr['CustPhone']  = $CustRes['phone'];
				// Yii::app()->session['Cust'] = $CustArr;
				
				$session['Cust'] = $CustArr;
				$this->redirect(array('redirectCust'));
				
				// echo '<pre/>';
				// print_r($_SESSION);
				// return;
			}
		}else{
			//if(!isset(Yii::app()->session['Cust']['CustID']) || empty(Yii::app()->session['Cust']['CustID']) ){

				$ordOPen = array();
				$this->renderPartial('login' , array('ordOPen'=>$ordOPen));
			// }else{
				
			//}

		}
		
	}
	
	public function actionFBLogin()
	{
		
		// print_r(Yii::app()->params['Facebook']);return;
		// $facebook = new Facebook(Yii::app()->params['Facebook']);
		// $user_id = $facebook->getUser();
		
		session_start();
		// define('FACEBOOK_SDK_V4_SRC_DIR', __DIR__ . '/../lib/facebook-php-sdk-v4/src/Facebook/');
		// require __DIR__ . '/../lib/facebook-php-sdk-v4/autoload.php';
		
		// require(dirname(__FILE__) . '/../lib/facebook-php-sdk-v4/src/Facebook/FacebookSession.php');
		// use 'Facebook\FacebookSession';
		
		// require __DIR__ . '/../helpers/autoload.php';
		// require __DIR__ . '/../helpers/Facebook/FacebookSession.php';
		// require __DIR__ . '/../helpers/Facebook/FacebookRedirectLoginHelper.php';
		
		$xx = FacebookSession::setDefaultApplication('742477709179739', '3a54dd1fce8ffd83d4a901dddb4e23a3');
		$helper = new FacebookRedirectLoginHelper('http://mykinjo/home/FBLogin'); // http://www.krizna.com/fbconfig.php
		// print_r($helper);
		
		try {
		  $session = $helper->getSessionFromRedirect();
		  print_r($session);
		} catch( FacebookRequestException $ex ) {
		  // When Facebook returns an error
		  print_r('exc 1');
		} 
		catch( Exception $ex ) {
			// print_r('exc 2');
		  // When validation fails or other local issues
		}
		
		if ( isset( $session ) ) {
			$request = new FacebookRequest( $session, 'GET', '/me' );
  			$response = $request->execute();
			
			$graphObject = $response->getGraphObject();
			
		    $fbid = $graphObject->getProperty('id');              	// To Get Facebook ID
		    $fbfullname = $graphObject->getProperty('name'); 		// To Get Facebook full name
			$femail = $graphObject->getProperty('email');    		// To Get Facebook email
			
			$_SESSION['FBID'] = $fbid;           
	        $_SESSION['FULLNAME'] = $fbfullname;
		    $_SESSION['EMAIL'] =  $femail;
			header("Location: index.php");
		}else {
			$loginUrl = $helper->getLoginUrl();
			
			// print_r($loginUrl);return;
		 	header("Location: ".$loginUrl);
		}
		// $session = new FacebookSession('e5f0452f5aa539c1e4d0527ae5b75c3d');
	}

	public function actionRedirectCust()
	{
				// echo '<pre/>';
				// print_r(Yii::app()->session['Cust']);
				// return;
			
		$arr['Long'] = Yii::app()->session['Cust']['CustLong'];
		$arr['Lat']  = Yii::app()->session['Cust']['CustLat'];
		
		$allStores = CustLib::actionGetAllStores($arr);
		$pagingArr = $this->CreatePagingLinks($allStores['stores']);
		$pagingStores = $this->getPage($allStores['stores'], $pagingArr['page'] , $pagingArr['limit']);
		
		
		$proSql = "SELECT products.pid AS proID, csid, title, price, ParCatName, SubCatName , pimg_url
				   FROM products LEFT JOIN CatAndCatSub 
				   ON csid = BCatID
				   LEFT JOIN  (SELECT DISTINCT (pid) AS pid, pimg_url
							   FROM products_imgs
							   GROUP BY pid)AS ImgTbl
				   ON ImgTbl.pid = products.pid
				   WHERE products.buid = 3";		
		$proArr = Yii::app()->db->createCommand($proSql)->queryAll();
		
		$ordOPen = $this->actionOrderData();
		// echo '<pre/>';	print_r($ordOPen);return;
		// $this->renderPartial('home_2' , array('proArr'=>$proArr , 'ordOPen'=>$ordOPen));
		// $this->renderPartial('stores' , array('proArr'=>$proArr , 'ordOPen'=>$ordOPen));
		$this->renderPartial('stores' , array('allStores'=>$pagingStores , 'pagingArr'=>$pagingArr , 'proArr'=>$proArr , 'ordOPen'=>$ordOPen));
	}
	
	public function actionLogOut()
	{
		unset(Yii::app()->session['Cust']);		
		
		// header("Location:/index.php/home/Login/");
		header("Location:/home/stores");
	}
	
	public function actionOrderData()
	{
		$OpenOrd['order'] = array();
		// $data = array();
		
		if(isset(Yii::app()->session['Cust']['CustID']) && !empty(Yii::app()->session['Cust']['CustID'])){
			
			$Ord = Orders::CHKCustomerHasOrder(Yii::app()->session['Cust']['CustID']);
			if ($Ord['rows_count'] == 1) {
				$OrderID = $Ord['res_id'];
				$data['ord_id'] = $Ord['res_id'];
				$data['cust_id'] = Yii::app()->session['Cust']['CustID'];
				$data['hash']    = Yii::app()->session['Cust']['CustHash'];
				
				// $ordSql = "SELECT * FROM orders WHERE ord_id = ".$OrderID;
				// $OpenOrd = Yii::app()->db->createCommand($ordSql)->queryRow();
				// $detSql = "SELECT ord_det_id, ord_buid ,orders_details.pid ,item,qnt,products.price,fees,final_price,convert_price,dollor_price, 
							      // title , discription ,pimg_url , ParCatID , ParCatName, SubCatID , SubCatName
						   // FROM orders_details LEFT JOIN products
						   // ON orders_details.pid = products.pid
						   // LEFT JOIN CatAndCatSub 
						   // ON csid = BCatID
						   // LEFT JOIN  (SELECT DISTINCT (pid) AS pid, pimg_url FROM products_imgs
									   // GROUP BY pid)AS ImgTbl
							// ON ImgTbl.pid = products.pid						
							// WHERE ord_id =".$OrderID;
				// $det = Yii::app()->db->createCommand($detSql)->queryAll();
				// $OpenOrd['detail'] = $det;
				
				$OpenOrd = CustLib::actionViewOrderByID($data);
				// $OpenOrd = (array) $OpenOrd;
				
				// $OpenOrd = (array) $OpenOrd['order']; 
				// print_r($OpenOrd);return;
		
			}
		}
		
		
		return (array)$OpenOrd['order'];
	}

	
	public function actionRegCust()
	{
		if(isset($_POST['email']) && !empty($_POST['email'])){
			
			$ChkEmailSql = " SELECT * FROM customers WHERE email='" . $_POST['email'] . "'";
			$ChkEmailRes = Yii::app()->db->createCommand($ChkEmailSql)->queryAll();
		
			$errors = array();
			if($_POST['password'] != $_POST['confirm_password']){
				$errors['pass'] = "Password Don't Match !";
			}
			
			
			if(count($ChkEmailRes) == 0) {
				
				$Img = '';
				if($_POST['gender'] == '0'){$Img = 'cust_male.jpg';}
				if($_POST['gender'] == '1'){$Img = 'cust_female.jpg';}
				// 1990-01-20
				$birthDate = "";
				if(isset($_POST['birthY']) && !empty($_POST['birthY']) && isset($_POST['birthM']) && !empty($_POST['birthM']) && !empty($_POST['birthD'])){
					$birthDate = $_POST['birthY'].'-'.$_POST['birthM'].'-'.$_POST['birthD'];
				}
					
				$InsCustSql = "INSERT INTO customers (fname,lname,email,password,gender,birthdate,image)
						       VALUES('" . $_POST['firstname'] . "',
						       		  '" . $_POST['lastname'] . "',
						       		  '" . $_POST['email'] . "',
						       		  '" . md5($_POST['password']) . "',
						       		  '" . $_POST['gender'] . "',
						       		  '" . $birthDate . "',
						       		  '" . $Img. "'				       		   
						       		 ) ";

				Yii::app()->db->createCommand($InsCustSql)->execute();
				$CustID = Yii::app()->db->getLastInsertID();
				
				$addrSql = "INSERT INTO customer_add (cust_add_cust_id , cust_add_country_id)
							VALUES( ".$CustID." , ". $_POST['country'] . ")";
				Yii::app()->db->createCommand($addrSql)->execute();
				$this->renderPartial('Completed_reg');
				
			}else{
				// $this->redirect(array('Login' ));
				$errors['mail'] = "E-mail is aleady used !";
				$this->renderPartial('login' , array("error_message" => $errors));
			}
			
			
		}
	}

	
	public function actionUpCust()
	{
		$_POST['cust_id'] = Yii::app()->session['Cust']['CustID'];
		$_POST['hash'] = Yii::app()->session['Cust']['CustHash'];
		$_POST['AppSource'] = 1;
		
		$_POST['fname'] = Yii::app()->session['Cust']['CustFName'];
		$_POST['phone'] = Yii::app()->session['Cust']['CustPhone'];
		
		$ResArr = CustLib::actionUpdateCustomer($_POST);
		$DecodeArr = json_encode($ResArr);
		
		// echo '<pre/>';
		// print_r($DecodeArr);
		// return;
		
		if(isset($DecodeArr)){
			echo $DecodeArr;
		}
		
	}
	
	
	
	

	public function actionProDetail($id)
	{
		if(isset(Yii::app()->session['Cust']['CustID'])){
			$dataArr['CustID'] = Yii::app()->session['Cust']['CustID'];
		}
		
		$dataArr['ProID'] = $id;
		/*
		$currSql = "SELECT DISTINCT business_unit.currency_code , currrency_symbol
							FROM business_unit LEFT JOIN country
							ON business_unit.currency_code = country.currency_code
							WHERE buid = 3";
				$currRow = Yii::app()->db->createCommand($currSql)->queryRow();
				$curr = $currRow['currrency_symbol'];
				
				$proSql = "SELECT products.pid AS proID, csid, title, price, discription , rating ,ParCatName, SubCatName , pimg_url
						   FROM products LEFT JOIN CatAndCatSub 
						   ON csid = BCatID
						   LEFT JOIN  (SELECT DISTINCT (pid) AS pid, pimg_url
									   FROM products_imgs
									   GROUP BY pid)AS ImgTbl
						   ON ImgTbl.pid = products.pid
						   WHERE products.pid =".$id;		
				$proArr = Yii::app()->db->createCommand($proSql)->queryRow();
				
				$imgSql = "SELECT * FROM products_imgs WHERE pid = ".$id;
				$imgArr = Yii::app()->db->createCommand($imgSql)->queryAll();
				
				$colSql = "SELECT * FROM prod_colors WHERE color_pid = ".$id;
				$colArr = Yii::app()->db->createCommand($colSql)->queryAll();
				
				$PconfSql = "SELECT cfg_id, parent_id, conf_buid, name, value
							 FROM pd_config
							 LEFT JOIN pd_conf_v ON cfg_id = pdconfv_confid
							 WHERE conf_buid = 3
							 AND parent_id IS NULL AND pdconfv_pid =".$id;
				$PconfArr = Yii::app()->db->createCommand($PconfSql)->queryAll();
				
				
				$config = array();
				foreach ($PconfArr as $key => $val) {
					$ChConfSql = "SELECT cfg_id, parent_id, conf_buid, name, value , pdconfv_id
								  FROM pd_config LEFT JOIN pd_conf_v 
								  ON pdconfv_confid = cfg_id
								  WHERE conf_buid =3 AND parent_id =".$val['cfg_id']."
								  AND pdconfv_pid =".$id;
					$ChConfArr = Yii::app()->db->createCommand($ChConfSql)->queryAll();
					
					$config[$val['cfg_id']]['Pid'] = $val['cfg_id'];
					$config[$val['cfg_id']]['Pname'] = $val['name'];
					$config[$val['cfg_id']]['child'] = $ChConfArr;
				}
				*/
		$ordOPen = $this->actionOrderData();
		// $encodeArr = CustLib::actionGetProdDetailsByProdID($dataArr); // $ProArr->Result->Product
		// $proArr = json_decode($encodeArr , TRUE);
		
		$proArr = CustLib::actionGetProdDetailsByProdID($dataArr);
		 // echo '<pre/>';
		 // print_r($proArr);
		 // return;
		// $this->renderPartial('product' , array('proArr'=>$proArr , 'imgArr'=>$imgArr , 'colArr'=>$colArr , 'config'=>$config , 'curr'=>$curr , 'ordOPen'=>$ordOPen)); 
		$this->renderPartial('product' , array('proArr'=>$proArr , 'ordOPen'=>$ordOPen));
	}

	public function actionAddWishList()
	{
		//if(isset(Yii::app()->session['Cust']['CustID']) && !empty(Yii::app()->session['Cust']['CustID'])){
			$_POST['cid']  = Yii::app()->session['Cust']['CustID'];
			$_POST['hash'] = Yii::app()->session['Cust']['CustHash'];
			
			$ResArr = CustLib::actionAddWishList($_POST);
			$DecodeArr = json_encode($ResArr);
			
			
			if (array_key_exists("error" , $ResArr)){
				echo $DecodeArr;
			}// else [Products]
			
		//}else{
			//$error = json_encode(array('error' => array("code" => "203", "message" => "Log In To Your Account !")));
			//echo $error;
		//}
				
	}
	
	public function actionAddToCard()
	{
		// print_r($_SERVER['REMOTE_ADDR']);
		// print_r($_POST);return;
		$_POST['cust_id']  = Yii::app()->session['Cust']['CustID'];
		$_POST['hash']     = Yii::app()->session['Cust']['CustHash'];
		$_POST['AppSource']= 1;
		$_POST['id']       = "";
		$_POST['paytype']  = "0";
		$_POST['long']     = Yii::app()->session['Cust']['CustLong'];
		$_POST['lat']      = Yii::app()->session['Cust']['CustLat'];
		// $_POST['Q_Conf'] = array('qnt'=>2 , 'conf' => '2,5' , 'color'=>2);
		//print_r($_POST['cust_id']);
		//return;
		
		$OrdArr = CustLib::actionAddToOrder($_POST);
		$JsonArr = json_encode($OrdArr);
		$DecodeArr = json_decode($JsonArr);
		if(isset($DecodeArr)){
			// if (array_key_exists("error" , $DecodeArr)){
			echo $JsonArr;
			// }
		}
	}

	public function actionRmvFromOrder()
	{
		$_POST['cust_id'] = Yii::app()->session['Cust']['CustID'];
		// $_POST['id'] = 1;
		// echo '<pre/>';
		// print_r($_POST);
		// return;
		
		$ordArr = CustLib::actionRemoveFromOrder($_POST);
		$ordJson = json_encode($ordArr);
		echo $ordJson;
	}
	
	public function actionRmvBuFromOrder()
	{
		$_POST['cust_id'] = Yii::app()->session['Cust']['CustID'];
		// print_r($_POST);
		// return;
		CustLib::actionRemoveBuFromOrder($_POST);
	}
	
	public function actionEditOrder()
	{
		$_POST['cust_id']   = Yii::app()->session['Cust']['CustID'];
		$_POST['paytype']   = 0;
		$_POST['AppSource'] = 1;
		// print_r($_POST['cust_id']);
		// return;
		
		$EditOrd = CustLib::actionEditOrder($_POST);
		
		// echo '<pre/>';
		// print_r($xx);
		// return;
	}
	
	public function actionShowCard()
	{
		$ordOPen = $this->actionOrderData();
		
		// echo '<pre/>';
		// print_r(json_encode($ordOPen));
		// print_r($ordOPen);
		// return;
		
		$this->renderPartial('cart' , array('ordOPen'=>$ordOPen) ); 
		//header("Location:/index.php/home/Login/");
	}
	
	
	/*
	public function actionCheckout()
		{
			$ordOPen = $this->actionOrderData();
			
			$arr['CustID'] = Yii::app()->session['Cust']['CustID'];
			$cust = CustLib::actionGetCustomer($arr);
			$cust = $cust['Customer'];
			
			$cust[0]['Buid'] = $_GET['Buid'];
			
			// $xx = CustLib::actionViewOrders($arr1);
			
			$commSql = "SELECT ad_setting_name , ad_setting_val
						FROM ad_setting WHERE ad_setting_id = 6";
			$comm = Yii::app()->db->createCommand($commSql)->queryRow();
			
			$BuArr = array();
			$PayTotal = 0;
			foreach ($ordOPen['OrdBuS'] as $key => $val) {
				if($val['Buid'] == $_GET['Buid'] ){
					$BuArr[$key] = $val;
					$PayTotal = $val['BuTotal'];
				}
			}
			
			$kinjo_comm = ($comm['ad_setting_val'] * $PayTotal) / 100;
			
			$addSql = "SELECT cust_add_id, cust_add_cust_id, cust_add_country_id, name, cust_add_city, cust_add_street
					   FROM customer_add
					   LEFT JOIN country ON cust_add_country_id = country_id
					   WHERE cust_add_cust_id =".$arr['CustID']."
					   AND cust_add_deleted =0";
			$custAdd = Yii::app()->db->createCommand($addSql)->queryAll();
			
			echo '<pre/>';
			print_r($_POST);
			return;
			
			if($_GET['step'] == 'ne'){
				$this->renderPartial('address' , array('ordOPen'=>$ordOPen , 'cust'=>$cust[0] , 'custAdd'=>$custAdd ) );
			}elseif($_GET['step'] == 'tw'){
				$this->renderPartial('shipping' , array('ordOPen'=>$ordOPen ,'BuArr'=>$BuArr , 'kinjo_comm'=>$kinjo_comm) );
			}//elseif($_GET['step'] == 'th'){
				//$this->renderPartial('summary' , array('ordOPen'=>$ordOPen ) ); 
			//}
					  }*/
	
	
	
	public function actionCheckout()
	{
		$ordOPen = $this->actionOrderData();
		
		$arr['CustID'] = Yii::app()->session['Cust']['CustID'];
		$cust = CustLib::actionGetCustomer($arr);
		$cust = $cust['Customer'][0];
		
		if(isset($_GET['Buid']) && !empty($_GET['Buid']))
		{
			// $cust[0]['Buid'] = $_GET['Buid'];
			$cust['Buid'] = $_GET['Buid'];
		}
		$cust['CustAddr'] = TRUE;
		foreach ($cust['Addr'] as $key => $val) {
			if(in_array("", $cust['Addr'][$key])){
				$cust['CustAddr'] = FALSE;
			}
		}
		
		// echo '<pre/>';
		// print_r($ordOPen);
		// return;
		
		if(empty($_POST)){
			
			// $addSql = "SELECT cust_add_id, cust_add_cust_id, cust_add_country_id, name, cust_add_city, cust_add_street,
							  // cust_add_region , cust_add_postalCode
					 //  FROM customer_add
					 //  LEFT JOIN country ON cust_add_country_id = country_id
					  // WHERE cust_add_cust_id =".$arr['CustID']."
					  // AND cust_add_deleted =0";
			// $custAdd = Yii::app()->db->createCommand($addSql)->queryAll();
		
			$this->renderPartial('address' , array('ordOPen'=>$ordOPen , 'cust'=>$cust ) ); // ,'custAdd'=>$custAdd 
			
		}else{

			$custAddID = 0;
			$_POST['cust_id'] = Yii::app()->session['Cust']['CustID'];
			if(empty($_POST['custAddID']) ){
				$custAdd = CustLib::actionAddShippingAddr($_POST);
				
				$_POST['custAddID'] = $custAdd['ShipAddID'];
			}
			
			if($_POST['custAddID'] > 0){
				
				$BuArr = array();
				$PayTotal = 0;
				foreach ($ordOPen['OrdBuS'] as $key => $val) {
					if($val['Buid'] == $_POST['Buid'] ){
						$BuArr = $val;
						$PayTotal = $val['BuTotal'];
					}
				}
				
				$BuArr['ShipAddID'] = $_POST['custAddID'];
				
				$commSql = "SELECT ad_setting_name , ad_setting_val
						    FROM ad_setting WHERE ad_setting_id = 6";
				$comm = Yii::app()->db->createCommand($commSql)->queryRow();
				$kinjo_comm = ($comm['ad_setting_val'] * $PayTotal) / 100;
				
				// echo '<pre/>';
				// print_r($ordOPen);
				// return;
				
				$this->renderPartial('shipping' , array('ordOPen'=>$ordOPen ,'BuArr'=>$BuArr , 'kinjo_comm'=>$kinjo_comm) );
			}
			
		}
		 
	}
	
	public function actionShipping()
	{
		// $ordOPen = $this->actionOrderData();
		$_POST['cust_id'] = Yii::app()->session['Cust']['CustID'];
		// $_POST['CustID']  = Yii::app()->session['Cust']['CustID'];
		// $cust = CustLib::actionGetCustomer($_POST);
		
		
		// $_POST['billingAddr'] = $cust['Customer'][0];
		// $BillingAddID = Orders::GetCustBillingAddID($_POST['cust_id']);
		// $_POST['BillingAddID'] = $BillingAddID;
		
		$_POST['AppSource'] = 1; // Web = 1 OR MobileApp = 0
		
		if(!isset($_POST['pay_type']) || empty($_POST['pay_type'])){
			$_POST['pay_type'] = 0; // OnLine=0 OR OnSite=1
		}
		
		$_POST['PaySys'] = 'AT'; // payment method (Authorize.Net OR PayPal ....)
		
		$result = CustLib::actionCloseOrder($_POST);
		return;
		if($result['orders']['result'] == TRUE){
			$this->renderPartial('shipping' ); // page to show success message and transactionID
		}
		// echo json_encode($result);return;
		
		
		//print_r($_POST);
		//return;
		
		// $_POST['shippingAddr'] = '';
		// if(isset($_POST['ShipAddID']) && !empty($_POST['ShipAddID'])){
			// $addSql = "SELECT cust_add_cust_id, cust_add_country_id, name AS country, cust_add_city AS city , cust_add_region AS region, 
							  // cust_add_street AS street , cust_add_postalCode AS zipCode
					   // FROM customer_add
					   // LEFT JOIN country ON cust_add_country_id = country_id
					   // WHERE cust_add_id =".$_POST['ShipAddID']."
					   // AND cust_add_deleted = 0";
			// $custAdd = Yii::app()->db->createCommand($addSql)->queryRow();
			// $_POST['shippingAddr'] = $custAdd;	
		// }
		// $MyCurr = Currency::ConvertCurrency($_POST['Currency'] , 'USD', $_POST['Cr_val']);
		// Twocheckout::privateKey(PRIVATE_KEY);
		// Twocheckout::sellerId(SELLER_ID);
		// Twocheckout::verifySSL(false);
		// Twocheckout::sandbox(true);
		// Twocheckout::format('json');
		// $Token = $_POST['Token'];
		// $PayTotal = round($MyCurr['ValTo'] , 3);
		
		
		// 
		// print_r($xx);
		
	}
	
	public function actionCustWishList()
	{
		$WArr['CustID']  = Yii::app()->session['Cust']['CustID'];
		
		$WishArr  = CustLib::actionGetWishList($WArr);
		// $WishList = json_decode($WishArr , TRUE);
		$WishList = $WishArr;
		// echo '<pre/>';
		// print_r($WishList);
		// return;
		$ordOPen = $this->actionOrderData();
		$this->renderPartial('wishlist', array( 'WishList'=>$WishList , 'ordOPen'=>$ordOPen) ); 
	}
	
	public function actionRmvFromWishList()
	{
		$_POST['cid'] = Yii::app()->session['Cust']['CustID'];
		$WishArr = CustLib::actionRemoveWishList($_POST);//$_POST
		echo json_encode($WishArr);
		 // echo '<pre/>';
		 // print_r($WishArr);
		 // return;
	}


	public function actionmyAction()
	{
		// echo '<pre/>';
		// print_r($_POST);
		// return;
		
		if (isset($_POST['g-recaptcha-response'])){
			// $secret = '6LdiGQUTAAAAAI_tV0-WG4Bfd9jfEO1dUxJ8QY10';
			$secret = '6LfU9AUTAAAAAKek1DEJzOKHWW72i1KBCdi5aX5-';
			
			
			$recaptcha = new ReCaptcha($secret);
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			
			// print_r($resp);return;
			
			if ($resp->isSuccess()){
				echo 'Success';
			}else{
				// foreach ($resp->getErrorCodes() as $code) {
	                // echo '<tt>' . $code . '</tt> ';
	            // }
			}
		}
	}
	
	
	public function actionAddProRate()
	{
		if(isset(Yii::app()->session['Cust']['CustID']) && !empty(Yii::app()->session['Cust']['CustID']) )
		{
			$_POST['cid'] = Yii::app()->session['Cust']['CustID'];
		}else{
			$_POST['cid'] = 0;
		}
	
		$result = CustLib::actionAddProdRating($_POST);
		// echo '<pre/>';
		// print_r($result);return;
		echo json_encode($result);
	}
	
	public function actionCustProfile($id)
	{
		$arr = array();
		
		$ordOPen = $this->actionOrderData();
		
		if(isset(Yii::app()->session['Cust']['CustID']) && !empty(Yii::app()->session['Cust']['CustID']))
		{
			$arr['CustID'] = Yii::app()->session['Cust']['CustID'];
		}else{
			$arr['CustID'] = 0;
		}
		
		
		$cust = CustLib::actionGetCustomer($arr);
		
		// echo '<pre/>';
		// print_r($cust);
		// return;
		
		
		if (array_key_exists('error', $cust)) {
		    echo $cust['error']['Message'];return;
		}
		
		$custData = $cust['Customer'][0];
		 
		if($id == 1){
			// user Profile
			// string base64_encode ( string $str )
			if(isset($_FILES) && !empty($_FILES)){
				/*
				$rnd = $random = date(time());
								$RealArr = Globals::ReturnGlobals();
								$uploaddir = $RealArr['ImgPath'].'customers/';
								$uploadfile =  $uploaddir.$rnd.'-'.basename($_FILES['CustPhoto']['name']);
								$newimgName = $rnd.'-'.basename($_FILES['CustPhoto']['name']);
								
								
								if (move_uploaded_file($_FILES['CustPhoto']['tmp_name'], $uploadfile)) {
									unlink($uploaddir.substr(strrchr($custData['image'], '/'), 1));
									$sql = "UPDATE customers SET image = '".$newimgName."' WHERE cid=".$arr['CustID'];
									Yii::app()->db->createCommand($sql)->execute();
									
									$xx = substr($custData['image'], 0, strrpos( $custData['image'], '/'));
									$custData['image'] = $xx.'/'.$newimgName;
									
									$this->renderPartial('userprofile' , array('custData'=>$custData , 'ordOPen'=>$ordOPen ) );
								}else {
									echo "Possible file upload attack!\n";
								}*/
								
				$fileContent = file_get_contents($_FILES['CustPhoto']['tmp_name']);		
				$arr['image']  = base64_encode ( $fileContent );
				$arr['CustID'] = Yii::app()->session['Cust']['CustID'];
				$arr['imgname']= $_FILES['CustPhoto']['name'];
				
				$ResArr = CustLib::actionImageCustomer($arr);
				
				if(key($ResArr) == 'error'){
					print_r($ResArr['error']['message']);
				}else{
					$custData['image'] = $ResArr['Result']['imgName'];
					$this->renderPartial('userprofile' , array('custData'=>$custData , 'ordOPen'=>$ordOPen ) );
				}
				
			}else{
				
				$this->renderPartial('userprofile' , array('custData'=>$custData , 'ordOPen'=>$ordOPen ) );
			}
			
			
		}elseif($id == 2){
			$this->renderPartial('userprofile_adress' , array('custData'=>$custData , 'ordOPen'=>$ordOPen ) );
		}
		
	}
	
	public function actionDefaultAddr()
	{
		$_POST['cust_id'] = Yii::app()->session['Cust']['CustID'];
		$Result = CustLib::actionCustSetAddrDefault($_POST);
		// print_r($Result);
		echo json_encode($Result);
	}
	
	
	public function actionOrdHistory()
	{
		$arr = array();
		$arr['cust_id'] = Yii::app()->session['Cust']['CustID'];
		$history = CustLib::actionViewOrders($arr);
		
		// echo '<pre/>';
		// print_r($history);
		// return;
		$this->renderPartial('userprofile_order' , array('history'=>$history['orders']));
	}
	
	public function actionResetPass()
	{
		$arr['email'] = Yii::app()->session['Cust']['CustMail'];
		$result = CustLib::actionResetPasswordCustomer($arr);
		print_r($result);
	}
	
	
	public function actionAutoSearch()
	{
		$Res = array();	
		
		
		// var_dump($_GET['serTyp']);
		// return;
		
		$Sql = "SELECT pid , title 
				FROM products
				WHERE title LIKE '%".$_POST['term']."%' ";
		$Arr = Yii::app()->db->createCommand($Sql)->queryAll();
		
		foreach ($Arr as $key => $row) {
			$array['id'] = $row['pid'];
	        $array['label'] = $row['title'];
	        // $row_array['abbrev'] = $row['abbrev'];
	         
	        array_push($Res , $array);
		}
		echo json_encode($Res);
	}
	
	
	public function actionForgetPass()
	{	
		$Res = array();
		if(isset($_POST) && !empty($_POST)){
			$Res = CustLib::actionResetPasswordCustomer($_POST);
			
		}
		// echo '<pre/>';
		// print_r($Res['Result']);
		// var_dump(array_key_exists("Result",$Res));
		// return;
		$this->renderPartial('CustForgetPass' , array('Res'=>$Res) );
		// $this->render('CustForgetPass' , array('Res'=>$Res) );
		
	}
	
}





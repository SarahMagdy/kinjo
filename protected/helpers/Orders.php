<?php 

	class Orders{
			
		//------ Get Product Data By Product ID
		public static function GetProdData($pro_id = 0)
		{
			/*
			$Sql= "SELECT * FROM products 
							   LEFT JOIN business_unit ON business_unit.buid = products.buid
							   WHERE pid =".$pro_id ;*/
				
			$Sql= " SELECT * FROM AllProductsData WHERE ProdID =".$pro_id ;
			
			$P_Data = Yii::app()->db->createCommand($Sql)->queryRow();
			
			return $P_Data;
		}
		//------ Get Recent Offer Data By Product ID
		public static function GetProdOffer($pro_id = 0)
		{
			$Sql= "SELECT * FROM offers WHERE pid =".$pro_id." AND active = 1 AND NOW() BETWEEN `from` AND `to` LIMIT 1 " ;	
			
			$P_Offer = Yii::app()->db->createCommand($Sql)->queryRow();
			
			return $P_Offer;
			
		}
		//------ Get Business Unit PayType(OnLine,Onsite)
		public static function GetBuPayType($Buid = 0){
				
			$BuPayType = 0;
			$OnLine = 0;$OnSite = 0;
			$BuSQL = " SELECT bu_setting_name,bu_setting_val FROM bu_setting WHERE bu_setting_bu_id =".$Buid." AND (bu_setting_name = 'onLine' OR bu_setting_name = 'onSite')";
			$BuData = Yii::app()->db->createCommand($BuSQL)->queryAll();
			if(count($BuData) > 0){
				foreach ($BuData as $Key => $Row) {
					if($Row['bu_setting_name'] == 'onLine'){$OnLine = $Row['bu_setting_val'];}
					if($Row['bu_setting_name'] == 'onSite'){$OnSite = $Row['bu_setting_val'];}
				}
			}
			if($OnLine == 1 && $OnSite == 1){$BuPayType = 2;}
			elseif($OnSite == 1){$BuPayType = 1;}
			elseif($OnLine == 1){$BuPayType = 0;}
			
			return $BuPayType;
		}
		//------ Calculate Order Detail Totals
		public static function TotalChild($pro_id = 0 , $qnt = 0,$conf = array())
		{
			$disc = 0;$totalRow = 0;$fees = 0;$ResArr = array();
			
			$offer = Orders::GetProdOffer($pro_id);
			$ProdData = Orders::GetProdData($pro_id);
			$BuPayType = Orders::GetBuPayType($ProdData['BUID']);
			
			$price = $ProdData['ProdPrice'];
			$totalRow = $price * $qnt;
			
			if(!empty($conf)){
					
				$SumCoSql = " SELECT IFNULL(SUM(pdconfv_value * ".$qnt."),0) AS ConfVal FROM pd_conf_v WHERE pdconfv_id IN (".implode(',',$conf).")";
				$SumCoData = Yii::app() -> db -> createCommand($SumCoSql)->queryRow();
				
				if(!empty($SumCoData)){
					$fees = $SumCoData['ConfVal'];
				}
				
			}
			
			$totalRow = $totalRow + $fees ;
			
			if(isset($offer) && !empty($offer)){
				
				$disc = $offer['discount'];
				
				$disc_val = $totalRow * ($disc / 100);
				
				$totalRow = $totalRow - $disc_val ;
			}
			
			
			$ResArr['item'] = $ProdData['ProdName'];
			$ResArr['price'] = $ProdData['ProdPrice'];
			$ResArr['Buid'] = $ProdData['BUID'];
			$ResArr['BuPayType'] = $BuPayType;
			$ResArr['discount'] = $disc;
			$ResArr['fees'] = $fees;
			$ResArr['f_price'] = $totalRow;
			
			/*
			$CurrArr = Currency::ConvertCurrency($ProdData['BUCurrency'], $curr, $totalRow);
			$c_price = $CurrArr['ValTo'];
			
			$ResArr['c_price'] = $c_price;
			
			$CurrArr = Currency::ConvertCurrency($ProdData['BUCurrency'], 'USD', $totalRow);
			$USD_price = $CurrArr['ValTo'];
			
			$ResArr['USD_price'] = $USD_price;*/
			
			
			return $ResArr;
		}
		//------ ___
		public static function ConvertedTotalChild($ord_id = 0)
		{
			$C_Sql= " SELECT * FROM orders_details 
					  LEFT JOIN orders ON orders.ord_id = orders_details.ord_id
				      LEFT JOIN business_unit ON business_unit.buid = orders_details.ord_buid
				      WHERE orders_details.ord_id = ".$ord_id ;	
			
			$C_Data = Yii::app()->db->createCommand($C_Sql)->queryAll();
			
			if(count($C_Data) > 0){
				
				foreach ($C_Data as $key => $row) {
					
					$Resrow = Orders::TotalChild($row['pid'],$row['qnt'],$row['c_curr_code']);
					
					$UpSql= " UPDATE orders_details SET convert_price = ".$Resrow['c_price']." WHERE ord_det_id = ".$row['ord_det_id'];
					Yii::app()->db->createCommand($UpSql)->execute();
					
				}
				
				Orders::TotalOrder($ord_id);
			}
		}
		//------ ___
		public static function TotalOrder($ord_id = 0)
		{
			$Sql= "SELECT IFNULL(SUM(convert_price),0) AS OrdTotal FROM orders_details WHERE ord_id =".$ord_id ;	
			
			$OrdTotal = Yii::app()->db->createCommand($Sql)->queryRow();
			
			Yii::app()->db->createCommand("UPDATE orders SET ord_total = ".$OrdTotal['OrdTotal']." WHERE ord_id = ".$ord_id)->execute();
		}
		//------ Calculate Order BU Totals
		public static function BuTotalOrder($OrdID = 0,$BuID = 0)
		{
			$OrdBuSQL = " SELECT SUM(final_price)AS BuOrdTotal,COUNT(ord_det_id)AS BuOrdCount FROM orders_details WHERE ord_id = ".$OrdID." AND ord_buid = ".$BuID;
			$OrdBuRow = Yii::app() -> db -> createCommand($OrdBuSQL)->queryRow();	
			
			if(!empty($OrdBuRow)){
					
				$BuPayType = Orders::GetBuPayType($BuID);
					
				$ChargeSQL = " SELECT * FROM bu_charges WHERE bu_chg_bu_id = ".$BuID;
				$ChargeRow = Yii::app() -> db -> createCommand($ChargeSQL)->queryRow();
				
				if($OrdBuRow['BuOrdCount'] > 0){
					if($OrdBuRow['BuOrdTotal'] > 0){
						
						$FinalTotal = $OrdBuRow['BuOrdTotal'];
						
						$Tax = 0;$Delivery = 0;$Fees = 0;$Shipment = 0;$VAT = 0;$Service = 0;
						
						if(!empty($ChargeRow)){
								
								$Tax		= $ChargeRow['bu_chg_tax'];
								$Delivery	= $ChargeRow['bu_chg_delivery'];
								$Fees		= $ChargeRow['bu_chg_fees'];
								$Shipment	= $ChargeRow['bu_chg_shipment'];
								$VAT		= $ChargeRow['bu_chg_VAT'];
								$Service	= $ChargeRow['bu_chg_service'];
								
							$FinalTotal = Orders::CalFeesVal($Tax,$FinalTotal);
							$FinalTotal = Orders::CalFeesVal($Delivery,$FinalTotal);
							$FinalTotal = Orders::CalFeesVal($Fees,$FinalTotal);
							$FinalTotal = Orders::CalFeesVal($Shipment,$FinalTotal);
							$FinalTotal = Orders::CalFeesVal($VAT,$FinalTotal);
							$FinalTotal = Orders::CalFeesVal($Service,$FinalTotal);
						}
						
						
							
		$OrdChkSQL = " SELECT * FROM orders_bu_totals WHERE ord_bu_total_ord_id = ".$OrdID." AND ord_bu_total_bu_id = ".$BuID;
							$OrdChkRow = Yii::app() -> db -> createCommand($OrdChkSQL)->queryRow();
							
							if(!empty($OrdChkRow)){
									
								$UpSql = " UPDATE orders_bu_totals 
										   SET ord_bu_total_total = ".$FinalTotal.",
										       ord_bu_total_tax = ".$Tax.",
										       ord_bu_total_delivery = ".$Delivery.",
										       ord_bu_total_fees = ".$Fees.",
										       ord_bu_total_shipment = ".$Shipment.",
										       ord_bu_total_VAT = ".$VAT.",
										       ord_bu_total_service = ".$Service.",
										       ord_bu_total_pay_type = ".$BuPayType."
											WHERE ord_bu_total_id = ".$OrdChkRow['ord_bu_total_id'];
								Yii::app() -> db -> createCommand($UpSql)->execute();
								
							} else {
								
								$InsSql = " INSERT INTO orders_bu_totals(ord_bu_total_ord_id,ord_bu_total_bu_id,ord_bu_total_total,
								            ord_bu_total_tax,ord_bu_total_delivery,ord_bu_total_fees,ord_bu_total_shipment,
								            ord_bu_total_VAT,ord_bu_total_service,ord_bu_total_pay_type) 
								            VALUES (".$OrdID.",".$BuID.",".$FinalTotal.",".$Tax.",".$Delivery.",".$Fees.",".$Shipment.",".$VAT.",".$Service.",".$BuPayType.") ";
							Yii::app() -> db -> createCommand($InsSql)->execute();	            
							}
					}
						
				} else {
					
					$DelSQl = " DELETE FROM orders_bu_totals WHERE ord_bu_total_ord_id = ".$OrdID." AND = ord_bu_total_bu_id = ".$BuID;
					Yii::app() -> db -> createCommand($DelSQl)->execue();
				}
			}
				
		}
		//------ Apply Fees on Totals
		public static function CalFeesVal($Fees = 0 ,$EndTotal = 0)
		{
			$FeVal = 0;	
			
			if(rtrim($Fees) > 0 || $Fees > 0){
				
				if(substr($Fees , -1) == '%'){
						
					$FeVal = $EndTotal * (rtrim($Fees) / 100);
					
				} else {
					$FeVal = $Fees;
				}
			}
			$EndTotal = $EndTotal + $FeVal;
			
			return $EndTotal;
		}
		//------ Close Bu Order
		public static function BuTotalClose($OrdID = 0,$BuID = 0,$UserID = 0 ,$PayType = 0)
		{
			$CloseStr = "";
			if($PayType == 0){$CloseStr = ",ord_bu_total_close_date = NOW()";}
			
			$UpCloseSQL = " UPDATE orders_bu_totals SET ord_bu_total_pay_type = ".$PayType.",
														ord_bu_total_user_id = ".$UserID." ".$CloseStr."
							WHERE ord_bu_total_ord_id = ".$OrdID." AND ord_bu_total_bu_id = ".$BuID;
							
			Yii::app()->db->createCommand($UpCloseSQL)->execute();
			
			Orders::CloseOrder($OrdID);
		}
		//------ Check if Customer has One Order
		public static function CHKCustomerHasOrder($cust_id = 0)
		{
			$Sql = " SELECT * FROM orders WHERE cid = ".$cust_id." AND status = 0 AND ord_type = 'cust' ";
			$Orders = Yii::app()->db->createCommand($Sql)->queryAll();
			
			$Result = array();
			$Result['rows_count'] = count($Orders);
			$Result['res_id'] = 0;
			
			if(count($Orders) == 1){
				foreach ($Orders as $key => $value) {
					
					$Result['res_id'] = $value['ord_id'];
				}
			}
			return $Result;
		}
		//------ Check if In Available Distance 
		public static function CHKDistance($buid , $Dist , $Lat , $Long)
		{
			$Sql = 'SELECT buid,IFNULL(title,"") AS title, IFNULL(  `long` ,  "" ) AS  `long` , IFNULL(lat,"") AS lat,
				       (((acos(sin(("'.$Lat.'"*pi()/180)) * 
				            sin((business_unit.lat*pi()/180)) + cos(("'.$Lat.'"*pi()/180)) * 
				            cos((business_unit.lat*pi()/180)) * cos((("'.$Long.'"- business_unit.long)* 
				            pi()/180))))*180/pi())*60*1.1515
				        ) as BUDist	   							  
				   FROM business_unit 
				   WHERE  buid = '.$buid.' HAVING BUDist < '.$Dist;
			$Data = Yii::app()->db->createCommand($Sql)->queryAll();
			// print_r($Sql);
			$allowedDist = FALSE;
			
			if(count($Data) > 0){
				$allowedDist = TRUE;
			}
			
			return $allowedDist;
		}
		//------ Close Master Order
		public static function CloseOrder($OrdID = 0)
		{
			$SQL = " SELECT COUNT(ord_det_id) AS C_NoClose FROM orders_details WHERE close_date IS NULL AND ord_id = ".$OrdID;
			$C_NoCloseR = Yii::app()->db->createCommand($SQL)->queryRow();
			
			if(!empty($C_NoCloseR)){
				if($C_NoCloseR['C_NoClose'] == '0'){
					
					$UpSQL = " UPDATE orders SET status = 1 WHERE ord_id = ".$OrdID;
					Yii::app()->db->createCommand($UpSQL)->execute();
				}
			}
		}
		//------ ___
		public static function UserCloseOrder($UserID = 0 ,$OrdID = 0 ,$BuID = 0)
		{
			if($UserID > 0 && $OrdID > 0 && $BuID > 0){
					
				$UpSQL = " UPDATE orders_bu_totals SET ord_bu_total_user_id = ".$UserID.", ord_bu_total_close_date = NOW()
						   WHERE ord_bu_total_ord_id = ".$OrdID." AND ord_bu_total_bu_id = ".$BuID;
				
				Yii::app()->db->createCommand($UpSQL)->execute();
				
			}	
		}
		//------ Get Bu Order Totals
		public static function GetOrderTotal($OrdID = 0 ,$BuID = 0)
		{
			$SQL = " SELECT * FROM orders_bu_totals WHERE ord_bu_total_ord_id = ".$OrdID." AND ord_bu_total_bu_id = ".$BuID;
			$Res = Yii::app()->db->createCommand($SQL)->queryRow();
			return $Res;
		}
		//------ Check if Bu Is Reserved	
		public static function IsReservedBu($Buid = 0,$CustID = 0)
		{
			$IsReserved = 'false';	
			$ResOpen = Orders::CHKCustomerHasOrder($CustID);
			$OrdId = $ResOpen['res_id'];
			if($OrdId > 0){
				
				$ReserveSQL = " SELECT * FROM orders_details WHERE ord_id = ".$OrdId." AND ord_buid = ".$Buid." AND reserved_bu = 1 AND close_date IS NULL ";
				$ReserveData = Yii::app()->db->createCommand($ReserveSQL)->queryAll();
				if(count($ReserveData) > 0){
					$IsReserved = 'true';	
				}
			}
			
			return $IsReserved;
		}	
		
		public static function GetCustBillingAddID($CustID = 0){
				
			$BillingAddID = 0;
			$AddSQL = " SELECT * FROM customer_add WHERE cust_add_cust_id = ".$CustID." AND cust_add_default = 1 ";
			$AddRow = Yii::app()->db->createCommand($AddSQL)->queryRow();
			
			if(!empty($AddRow)){
				$BillingAddID = $AddRow['cust_add_id'];
			}
			
			return $BillingAddID;
		}
		
		public static function CHKAddr($addrID = 0){
				
			$Sql = "SELECT cust_add_id , cust_add_cust_id , cust_add_default , cust_add_country_id , cust_add_city , cust_add_region,
						   cust_add_street , cust_add_postalCode , name  
					FROM customer_add 
					LEFT JOIN country ON cust_add_country_id = country_id
					WHERE cust_add_id = ".$addrID;
			$addr = Yii::app()->db->createCommand($Sql)->queryRow();
			
			if(isset($addr) && !empty($addr)){
				
				if(in_array("", $addr)){
					return FALSE;
				}else{

					$custSql = "SELECT fname , lname, email, password, hash ,phone
								FROM customers 
								WHERE cid = ".$addr['cust_add_cust_id'];
					$CustData = Yii::app()->db->createCommand($custSql)->queryRow();
					
					$addr += $CustData;
					return $addr;
				}
			}else{
				return FALSE;
			}
			
		}
		
		public static function AssignQrCodetoOrder($BuID = 0,$OrdID = 0,$TableID = 0)
		{
			$ChkSQL = " SELECT * FROM orders_qrcodes WHERE ord_qr_bu_id = ".$BuID." AND ord_qr_ord_id =".$OrdID;
			$ChkRow = Yii::app()->db->createCommand($ChkSQL)->queryRow();
			if(!empty($ChkRow)){
				$UPSQL = " UPDATE orders_qrcodes SET ord_qr_table_id = ".$TableID." WHERE ord_qr_id =".$ChkRow['ord_qr_id'];
				Yii::app()->db->createCommand($UPSQL)->execute();
			}else{
				$INSSQL = " INSERT INTO orders_qrcodes (ord_qr_bu_id,ord_qr_ord_id,ord_qr_table_id) 
							VALUES (".$BuID." ,".$OrdID." ,".$TableID." )";
				Yii::app()->db->createCommand($INSSQL)->execute();
			}
		}
		
		public static function GetTableOrderOpened($TableID = 0)
		{
			$OpenId = 0 ;
			$TableSQl = " SELECT (CASE WHEN status = 0 THEN 'Open' ELSE 'Close' END)AS ISOpen,ord_id
						  FROM orders LEFT JOIN orders_qrcodes ON ord_id = ord_qr_ord_id
						  WHERE ord_type = 'wait' AND ord_qr_table_id =".$TableID;	
			
			$TableRow = Yii::app()->db->createCommand($TableSQl)->queryRow();
			if(!empty($TableRow)){
				if($TableRow['ISOpen'] == 'Open'){
					$OpenId = $TableRow['ord_id'];
				}
			}
			
			return $OpenId;
		}	
	}

?>





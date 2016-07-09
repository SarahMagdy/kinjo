<?php

	class Currency{
		
		public static function GetCurrencyRate($Frm,$To)
		{
			$URL = 'http://rate-exchange.appspot.com/currency?from='.$Frm.'&to='.$To;
			$Headers = array(
	            'Content-Type: application/json'
	        );
			// Open connection
       		 $ch = curl_init();	
			// Disable SSL verification
			 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			// Set Headers
			 curl_setopt($ch, CURLOPT_HTTPHEADER, $Headers);
			// Will return the response, if false it print the response
			 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// Set the url
			 curl_setopt($ch, CURLOPT_URL,$URL);
			// Execute
			 $Res = curl_exec($ch);
			// Closing
			 curl_close($ch);
			
			$Res = json_decode($Res, true);
			$Rate = 0 ;
			if(isset($Res['rate'])){
				$Rate = $Res['rate'] ;
			}
			return $Rate;
		}
		
		public static function ConvertCurrency($Frm,$To,$val)
		{
			$CurrArr = array();	
			$Rate = Currency::GetCurrencyRate($Frm, $To);
			$ToVal = strval($Rate * $val);
			
			$CurrArr['CurrFrm']= $Frm;
			$CurrArr['CurrTo']= $To;
			$CurrArr['ValFrm']= $val;
			$CurrArr['ValTo']= $ToVal;
			
			return $CurrArr;
		}
		
		
	}
	
?>
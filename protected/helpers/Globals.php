<?php
   
   class Globals{
			
		public static function ReturnGlobals()
		{
			$ReturnArr = array();
			
			$ReturnArr['ImgPath'] = getcwd().'/images/upload/';
			$ReturnArr['ImgSerPath'] = $_SERVER['SERVER_NAME'].'/images/upload/';
			$ReturnArr['ImgSerPathL'] = $_SERVER['SERVER_NAME'].'/kinjo/public/images/upload/';
			
			return $ReturnArr;
			
		}
		
		public static function ReturnPaymentSystems()
		{
			$PaySysArr = array(
							   	array('Code'=>'PP','Name'=>'PayPal'),
							  	array('Code'=>'AT','Name'=>'Authorize.Net')
							  );
		
			return $PaySysArr;
			
		}
	
   
   } 
   
  
?>
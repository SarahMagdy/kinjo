<?php
    
    require_once 'anet_php_sdk/AuthorizeNet.php';
	
	class AuthorizeNetHelp
	{
		//private $METHOD_TO_USE = "AIM";
		
		
		public function __construct() {
   		 	
		}
		static public function AuthorizeNetFunc($AuthNetArr = array())
		{
		   $METHOD_TO_USE = "AIM";
		   
		   if ($METHOD_TO_USE == "AIM") {
			    	
			    $transaction = new AuthorizeNetAIM;
			    $transaction->setSandbox(AUTHORIZENET_SANDBOX);
			   /*
				$transaction->setFields(
				   array(
				   'amount' => '1000', 
				   'card_num' => '6011000000000012', 
				   'exp_date' => '06/15',
				   //'first_name' => 'Asmaa',
				   //'last_name' => 'Ali',
				   //'address' => '123 Four Street',
				   //'city' => 'San Francisco',
				   //'state' => '',
				   //'country' => '',
				   //'phone' => '0123214141242',
				   //'zip' => '941666666666666666666666676633',
				   //'email' => 'asmaaa.ali.mis@yahoo.com',
				   'card_code' => '782',
				   )
			   );*/
			   
		
				$customer                          = (object) array();
				$customer->first_name              = $AuthNetArr['CustFname'];
				$customer->last_name               = $AuthNetArr['CustLname'];
				$customer->address                 = $AuthNetArr['CustAddress'];
				$customer->city                    = $AuthNetArr['CustCity'];
				$customer->state                   = $AuthNetArr['CustState'];
				$customer->zip                     = $AuthNetArr['CustZip'];
				$customer->phone                   = $AuthNetArr['CustPhone'];
				$customer->email                   = $AuthNetArr['CustEmail'];
				//$customer->customer_ip             = $AuthNetArr['CustFname'];
				
				$shipping_info                     = (object) array();
			   // $shipping_info->ship_to_first_name = $AuthNetArr['ShipFname'];
				//$shipping_info->ship_to_last_name  = $AuthNetArr['ShipLname'];
				$shipping_info->ship_to_address    = $AuthNetArr['ShipAddress'];
				$shipping_info->ship_to_city       = $AuthNetArr['ShipCity'];
				$shipping_info->ship_to_state      = $AuthNetArr['ShipState'];
				$shipping_info->ship_to_zip        = $AuthNetArr['ShipZip'];
				
				$transaction->amount               = $AuthNetArr['Amount'];
				$transaction->address              = $AuthNetArr['CustAddress'];
				$transaction->zip                  = $AuthNetArr['CustZip'];
				$transaction->card_num             = $AuthNetArr['CardNum'];
				$transaction->exp_date             = $AuthNetArr['ExpDate'];
				$transaction->card_code            = $AuthNetArr['CardCode'];
				$transaction->description          = $AuthNetArr['Desc'];
			
			    
				
			    $transaction->setFields($shipping_info);
			    $transaction->setFields($customer);
				
			    $response = $transaction->authorizeAndCapture();
			    
			    $ResArr = array();
				
				if ($response->approved) {
					// Transaction approved! Do your logic here.
					//header('Location: thank_you_page.php?transaction_id=' . $response->transaction_id);
					//var_dump('TransactionID :'.$response->transaction_id);
					
					
					// echo '<pre/>';
					// print_r($response);
					// return;
					
					$ResArr['Result'] = True;
					$ResArr['TransactionID'] = $response->transaction_id;
					
					//return True;
					
				} else {
					//header('Location: error_page.php?response_reason_code='.$response->response_reason_code.'&response_code='.$response->response_code.'&response_reason_text=' .$response->response_reason_text);
					//var_dump($response->response_reason_code.' : '.$response->response_reason_text);
					//return False;
					$ResArr['Result'] = False;
					$ResArr['ReasonCode'] = $response->response_reason_code;
					$ResArr['ReasonText'] = $response->response_reason_text;
				}
				
				return $ResArr;
			}			
		}
	}
	
?>

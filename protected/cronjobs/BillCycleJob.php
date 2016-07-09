<?php
	
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
	
	// require_once(getcwd().'/../kinjo/protected/helpers/SMTP.php');
	// require_once(getcwd().'/../kinjo/protected/helpers/PHPMailer.php');
	// require_once(getcwd().'/../kinjo/protected/helpers/PHPMailerAutoload.php');
	require_once 'sendMail.php';
	
	
	try {
	    $conn = new PDO('mysql:host=localhost;dbname=kinjo', 'root' , '123');
	} catch (PDOException $e) {
	    // print "Error!: " . $e->getMessage() . "<br/>";
	    die();
	}

	
	$GracePeriod = 0;
	$DelayFees = 0;
	$today = date("Y-m-d"); // '2017-01-06';// 
	
	$SuspendIds = array();
	$SuspendMails = array();
	$FirstGrace = array();
	$LastGrace = array();
	$AlertBefore = array();
	$PayDay = array();
	
	/*  ************* Mail Credentials ****************  */
	// $mail = new PHPMailer;

	// $mail->SMTPDebug = 1;//3                               // Enable verbose debug output
	// $mail->isSMTP();                                          // Set mailer to use SMTP
	// $mail->Host = 'mail.kinjo-app.com';      			      // Specify main and backup SMTP servers
	// $mail->SMTPAuth = true;                                   // Enable SMTP authentication
	// $mail->Username = 'kinjo@kinjo-app.com';                  // SMTP username
	// $mail->Password = 'kinjo@2014';                           // SMTP password
	// $mail->SMTPSecure = 'tls';                             // Enable TLS encryption, `ssl` also accepted
	// $mail->Port = 25;                                         // TCP port to connect to
	
	// $mail->From = 'kinjo@kinjo-app.com';
	// $mail->FromName = 'kinjo';
	
	

	
	// $adSQL = ;
	$adminQ = $conn->prepare("SELECT * FROM ad_setting");
	$adminQ->execute();
	$AdminARR = $adminQ->fetchAll();
	
	$GracePeriod = $AdminARR[0]['ad_setting_val'];
	$DelayFees = $AdminARR[1]['ad_setting_val'];
	
	
	$allStoresSQL = "SELECT buid , business_unit.accid, business_unit.pkg_id, business_unit.title AS bu_title, packages.title AS pkg_title, 
							bc_duration, bc_type, DATE( business_unit.created ) AS bu_created, IFNULL(payDate , DATE(business_unit.created )) AS payDate ,
							CASE bc_type 
								WHEN 0 THEN DATE_ADD(DATE(IFNULL(payDate , DATE(business_unit.created))),INTERVAL bc_duration DAY)
								WHEN 1 THEN DATE_ADD(DATE(IFNULL(payDate , DATE(business_unit.created))),INTERVAL bc_duration MONTH)
								WHEN 2 THEN DATE_ADD(DATE(IFNULL(payDate , DATE(business_unit.created))),INTERVAL bc_duration YEAR)
							END AS DUE_DATE , email
			
					 FROM business_unit
					 LEFT JOIN packages ON business_unit.pkg_id = packages.pkgid
					 LEFT JOIN billing_cycle ON pkg_bill_cycleID = bcid
					 LEFT JOIN (SELECT x.bill_d_bill_id, x.bill_d_bu_sp_id, MAX( x.bill_d_pay_date ) AS payDate, x.bill_d_amount, x.bill_d_due_date
					 			FROM (SELECT bill_d_pay_date, bill_d_bill_id, bill_d_bu_sp_id, bill_d_amount, bill_d_due_date
									  FROM bills_details
									  WHERE bill_d_bu_or_sp =0
							      	  ORDER BY bill_d_pay_date DESC) AS x
							   		  GROUP BY x.bill_d_bu_sp_id
								) AS billDetail ON bill_d_bu_sp_id = buid
					LEFT JOIN bu_accounts ON business_unit.accid = bu_accounts.accid
					WHERE active =0 AND business_unit.pkg_id != 0";
	
	$billQ = $conn->prepare($allStoresSQL);
	$billQ->execute();
	$Billresu = $billQ->fetchAll();
	foreach ($Billresu as $key => $val) {
		$buMail  = array();
		if(date('Y-m-d', strtotime($today. ' + 7 days')) == $val['DUE_DATE']){
			 // echo $val['buid'].'   notify client that he has 7 days until due date'."<br/>";
			 // array_push($AlertBefore , $val['email']);
			$body = "<html>
					 	<head><title>Kinjo Payment</title></head>
						<body>
							<p> ".$val['bu_title']." , You have a week Before your Due Date.</p>
						</body>
				    </html>";
			array_push($buMail , $val['email'] );	
			sendMail::Mailing($body , $buMail); 
		
		}elseif($val['DUE_DATE'] == $today ){
			// echo $val['buid'].'    due date is today'."<br/>";
			// array_push($PayDay , $val['email']);
			
			$body = "<html>
							<head><title>Kinjo Payment</title></head>
							<body>
								<p>".$val['bu_title']."   ".$today." Is Your Due Date .</p>
							</body>
						  </html>";
			array_push($buMail , $val['email'] );
			sendMail::Mailing($body , $buMail);
			
		}elseif($val['DUE_DATE']<$today && $val['DUE_DATE']>= date('Y-m-d', strtotime($today. ' - '.$GracePeriod.' days'))){
			
			if( date('Y-m-d', strtotime($val['DUE_DATE']. ' + 1 days')) == $today){
				// echo $val['buid']. ' first grace day : ' .date('Y-m-d', strtotime($val['DUE_DATE']. ' + 1 days')) ."<br/>";
				// array_push($FirstGrace , $val['email']);
				$body = "<html>
							<head><title>Kinjo Payment </title></head>
							<body>
								<p>".$val['bu_title']."  ".$today." Is Your First Day in Grace Period </br> You have <b> ".$GracePeriod." days. </b> </p>
							</body>
						  </html>";
				array_push($buMail , $val['email'] );
				sendMail::Mailing($body , $buMail);
				
				
			}elseif(date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) == $today){
				// echo $val['buid']. ' last grace day : ' .date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) ."<br/>";
				// array_push($LastGrace , $val['email']);
				$body = "<html>
							<head><title>Kinjo Payment </title></head>
							<body>
								<p>".$val['bu_title']."   ".$today." Is Your Last Day in Grace Period </br> Your Account will be <b> Suspended . </b> </p>
							</body>
						  </html>";
				array_push($buMail , $val['email'] );
				sendMail::Mailing($body , $buMail);
				
			}
			
		}elseif(date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) <= $today){
			// echo $val['buid']. '&nbsp&nbsp     Suspend'."<br/>";
			array_push($SuspendIds , $val['buid']);
			// array_push($SuspendMails , $val['email']);
			$body = "<html>
						<head><title>Kinjo Payment </title></head>
						<body>
							<p> ".$val['bu_title']." , Your Account has been <b> Suspended . </b> </p>
						</body>
					  </html>";
			array_push($buMail , $val['email'] );
			sendMail::Mailing($body , $buMail);
		
		}
		// elseif(date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) > $today){
			// echo $val['buid'].'    not yet'."<br/>";
		// }
	}

// print_r($AlertBefore);
// return;	
	$sqll = "UPDATE business_unit SET active = 1 WHERE buid IN (".implode(",",$SuspendIds).")";
	$sqllQ = $conn->prepare($sqll);
	$sqllQ->execute();
	


	unset($SuspendIds);	
	$SuspendIds = array();

	$allAccountSQL = "SELECT accid, fname, email, start_date, bill_id , sp_d_amount , DATE( BillDetail.bill_d_pay_date ) AS payDate,
							 sp_d_title, bc_duration, bc_type ,
							 CASE bc_type
							 	WHEN 0 THEN DATE_ADD(DATE(IFNULL(BillDetail.bill_d_pay_date, DATE(start_date))),INTERVAL bc_duration DAY)
								WHEN 1 THEN DATE_ADD(DATE(IFNULL(BillDetail.bill_d_pay_date , DATE(start_date))),INTERVAL bc_duration MONTH)
								WHEN 2 THEN DATE_ADD(DATE(IFNULL(BillDetail.bill_d_pay_date, DATE(start_date))),INTERVAL bc_duration YEAR)
							 END AS DUE_DATE
					  FROM bu_accounts
					  LEFT JOIN special_deals ON bu_accounts.special_deal_id = sp_d_id
					  LEFT JOIN billing_cycle ON sp_d_bill_cycle_id = bcid
					  LEFT JOIN bills ON bill_owner_id = accid
					  LEFT JOIN (SELECT DISTINCT bill_d_bill_id, bill_d_due_date, bill_d_pay_date
								 FROM bills_details
								 WHERE bill_d_bu_or_sp =1
								) AS BillDetail 
					  ON bill_d_bill_id = bill_id
					  WHERE special_deal_id !=0
					  AND STATUS =1";
	// $today = '2015-03-07';
	$AccountQ = $conn->prepare($allAccountSQL);
	$AccountQ->execute();
	$Accresult = $AccountQ->fetchAll();
	foreach ($Accresult as $key => $val) {
		
			
		if(date('Y-m-d', strtotime($today. ' + 7 days')) == $val['DUE_DATE']){
			// echo $val['accid'].' notify client that he has 7 days until due date'."<br/>";
			array_push($AlertBefore , $val['email']);
			
		}elseif($val['DUE_DATE'] == $today ){
			// echo $val['accid'].'   due date is today'."<br/>";
			array_push($PayDay , $val['email']);
			
		}elseif($val['DUE_DATE']<$today && $val['DUE_DATE']>= date('Y-m-d', strtotime($today. ' - '.$GracePeriod.' days'))){
			
			
			
			if( date('Y-m-d', strtotime($val['DUE_DATE']. ' + 1 days')) == $today){ //&nbsp&nbsp
				// echo $val['accid']. ' first grace day : ' .date('Y-m-d', strtotime($val['DUE_DATE']. ' + 1 days')) ."<br/>";
			
				array_push($FirstGrace , $val['email']);
			
			}elseif(date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) == $today){
				// echo $val['accid']. ' last grace day : ' .date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) ."<br/>";
				array_push($LastGrace , $val['email']);
			}
			
		
		}elseif(date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) <= $today){
			// echo $val['accid']. '  Suspend'."<br/>";
			// $mail->addAddress($val['email'] , $val['fname']); 
			array_push($SuspendIds , $val['accid']);
			array_push($SuspendMails , $val['email']);
		
		}elseif(date('Y-m-d', strtotime($val['DUE_DATE']. ' + '.$GracePeriod.' days')) > $today){
			// echo $val['accid'].'   not yet'."<br/>";
		}
	}
	
	
	
	$sql_Q = $conn->prepare("UPDATE business_unit SET active = 1 WHERE accid IN (".implode(",",$SuspendIds).")");
	$sql_Q->execute();
	
	$sql_Q_1 = $conn->prepare("UPDATE bu_accounts SET status = 0 WHERE accid IN (".implode(",",$SuspendIds).")");
	$sql_Q_1->execute();
	
	
	
	
	
	if(!empty($AlertBefore)){
		
		$body = "<html>
					<head><title>Kinjo Payment</title></head>
					<body>
						<p> You have a week Before your Due Date.</p>
					</body>
				  </html>";
		sendMail::Mailing($body , $AlertBefore);
		// foreach ($AlertBefore as $key => $val) {
			// $mail->addAddress($val);
		// }
		// $mail->Subject =  " Notify Payment ";
		// $mail->Body    = "<html><head><title>Kinjo Payment</title></head><body> <p> You have a week Before your Due Date.</p></body></html>";
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		// if(!$mail->send()) {
		    // echo 'Message could not be sent.';
		    // echo 'Mailer Error: ' . $mail->ErrorInfo."<br/>";
		// } else {echo 'Message has been sent';}
		// $mail->ClearAddresses();
	}
	
	if(!empty($PayDay)){
		
		$body = "<html>
					<head><title>Kinjo Payment</title></head>
					<body>
						<p>".$today." Is Your Due Date .</p>
					</body>
				  </html>";
		sendMail::Mailing($body , $PayDay);
		
		// foreach ($PayDay as $key => $val) { $mail->addAddress($val); }
		// $mail->Subject =  " Notify Payment ";
		// $mail->Body    = "<html><head><title>Kinjo Payment</title></head><body><p>".$today." Is Your Due Date .</p></body></html>";
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		// if(!$mail->send()) {
		    // echo 'Message could not be sent.';
		    // echo 'Mailer Error: ' . $mail->ErrorInfo."<br/>";
		// } else { echo 'Message has been sent';}
		// $mail->ClearAddresses();		
	}
	
	if(!empty($FirstGrace)){
			
		$body = "<html>
					<head><title>Kinjo Payment </title></head>
					<body>
						<p>".$today." Is Your First Day in Grace Period </br> You have <b> ".$GracePeriod." days. </b> </p>
					</body>
				  </html>";
		sendMail::Mailing($body , $FirstGrace);
			
		// foreach ($FirstGrace as $key => $val) { $mail->addAddress($val); }	
		// $mail->Subject =  " Notify Payment ";
		// $mail->Body    = "<html> <head><title>Kinjo Payment </title></head><body><p>".$today." Is Your First Day in Grace Period </br> You have <b> ".$GracePeriod." days. </b> </p></body> </html>";
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		// if(!$mail->send()) {
		    // echo 'Message could not be sent.';
		    // echo 'Mailer Error: ' . $mail->ErrorInfo."<br/>";
		// } else { echo 'Message has been sent';}
		// $mail->ClearAddresses();
	}
	
	if(!empty($LastGrace)){
		$body = "<html>
					<head><title>Kinjo Payment </title></head>
					<body>
						<p>".$today." Is Your Last Day in Grace Period </br> Your Account will be <b> Suspended . </b> </p>
					</body>
				  </html>";
		sendMail::Mailing($body , $LastGrace);
		
		// foreach ($LastGrace as $key => $val) { $mail->addAddress($val);	}
		// $mail->Subject =  " Notify Payment ";
		// $mail->Body    = "<html><head><title>Kinjo Payment </title></head><body><p>".$today." Is Your Last Day in Grace Period </br> Your Account will be <b> Suspended . </b> </p></body> </html>";
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		// if(!$mail->send()) {
		    // echo 'Message could not be sent.';
		    // echo 'Mailer Error: ' . $mail->ErrorInfo."<br/>";
		// } else { echo 'Message has been sent'; 	}
		// $mail->ClearAddresses();
	}
	
	if(!empty($SuspendMails)){
			
		$body = "<html>
					<head><title>Kinjo Payment </title></head>
					<body>
						<p> Your Account has been <b> Suspended . </b> </p>
					</body>
				  </html>";
		sendMail::Mailing($body , $SuspendMails);
			
		// foreach ($SuspendMails as $key => $val) {$mail->addAddress($val); }		
		// $mail->Subject =  " Notify Payment ";
		// $mail->Body    = "<html><head><title>Kinjo Payment </title></head><body><p> Your Account has been <b> Suspended . </b> </p></body></html>";
		// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
		// if(!$mail->send()) {
		    // echo 'Message could not be sent.';
		    // echo 'Mailer Error: ' . $mail->ErrorInfo."<br/>";
		// } else { echo 'Message has been sent';	}
		// $mail->ClearAddresses();
	}
	
	
	
	
	unset($conn);
	unset($billQ);
	
	
?>	
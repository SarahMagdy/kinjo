<?php

// print_r(getcwd());return;
	require_once(getcwd().'/../helpers/SMTP.php');
	require_once(getcwd().'/../helpers/PHPMailer.php');
	require_once(getcwd().'/../helpers/PHPMailerAutoload.php');
	
	class sendMail {
		
		public static function Mailing($Body,$MailTo)
		{
			$mail = new PHPMailer;

			$mail->SMTPDebug = 1;//3                               // Enable verbose debug output
			$mail->isSMTP();                                          // Set mailer to use SMTP
			$mail->Host = 'mail.kinjo-app.com';      			      // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                                   // Enable SMTP authentication
			$mail->Username = 'kinjo@kinjo-app.com';                  // SMTP username
			$mail->Password = 'kinjo@2014';                           // SMTP password
			// $mail->SMTPSecure = 'tls';                             // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 25;                                         // TCP port to connect to
			
			$mail->From = 'kinjo@kinjo-app.com';
			$mail->FromName = 'kinjo';
			
			$mail->addReplyTo('kinjo@kinjo-app.com', 'Information');
			$mail->isHTML(true);                                  // Set email format to HTML
			
			
			foreach ($MailTo as $key => $val) {
				$mail->addAddress($val);
			}
			
			$mail->Subject =  " Payment Notification";
			$mail->Body    = $Body;
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			
			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo."<br/>";
			} else {
			    echo 'Message has been sent';
			}
			
			$mail->ClearAddresses();
			
		
		}
		
	}

?>
<?php

	
	class mail {
		
		public function SendMail($Subject,$Body,$MailTo,$To)
		{	
			require 'PHPMailerAutoload.php';
			
			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output
			
			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'mail.kinjo-app.com';  			  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			//$mail->Host = 'mocha7004.mochahost.com';  
			$mail->Username = 'kinjo@kinjo-app.com';                 // SMTP username
			$mail->Password = 'kinjo@2014';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			//$mail->Port = 465;
			$mail->Port = 25;                                      // TCP port to connect to
			
			$mail->From = 'kinjo@kinjo-app.com';
			$mail->FromName = 'kinjo';
			$mail->addAddress($MailTo, $To);  					    // Add a recipient
			//$mail->addAddress('ellen@example.com');               // Name is optional
			$mail->addReplyTo('kinjo@kinjo-app.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');
			
			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML
			
			$mail->Subject = $Subject;
			$mail->Body    = $Body;
			$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			
			if(!$mail->send()) {
			    return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    return 'Message has been sent';
			}
			
		}
    }
?>
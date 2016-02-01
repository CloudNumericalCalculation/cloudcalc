<?php
function sendNotificationEmail($subject, $body) {
	require_once '../PHPMailer/PHPMailerAutoload.php';
	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               // Enable verbose debug output

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.ym.163.com';  // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'notify@cloudcalc.cc';                 // SMTP username
	$mail->Password = '9uBbZGVy4k';                           // SMTP password
	$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 994;                                    // TCP port to connect to

	$mail->From = 'notify@cloudcalc.cc';
	$mail->FromName = 'CloudCalc';
	$mail->addAddress('guessever@outlook.com', 'GuessEver');
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('cc@example.com');
	//$mail->addBCC('bcc@example.com');

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('../../Firefox_wallpaper.png', 'new.jpg');    // Optional name
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->CharSet = 'utf-8';

	$mail->Subject = $subject;
	$mail->Body    = $body;

	/*if(!$mail->send()) {
		echo 'Message could not be sent.';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
		echo 'Message has been sent';
	}*/
	return $mail->send() ? true : false;
}

<?php
include_once ("PHPMailer/PHPMailerAutoload.php");
$mail = new PHPMailer;
//$mail->SMTPDebug = 3;                               
$mail -> isSMTP();
// Set mailer to use SMTP
$mail -> Host = 'ssl://smtp.gmail.com';
// Specify main and backup SMTP servers
$mail -> SMTPAuth = true;
// Enable SMTP authentication
$mail -> Username = 'garden.project.setju@gmail.com';
// SMTP username
$mail -> Password = '1640@math';
// SMTP password
$mail -> SMTPSecure = 'ssl';
// Enable TLS encryption, `ssl` also accepted
$mail -> Port = 465;
// TCP port to connect to
$mail -> From = 'garden.project.setju@gmail.com';
$mail -> FromName = 'Nikhil Pereira(Admin)';

// Add a recipient
$mail -> addAddress($email, $username);
$mail -> isHTML(true);
// Set email format to HTML

$mail -> Subject = $subject;

$mail -> Body = $message;
if (!$mail -> send()) {
	echo 'email_send_failed';
} else {
	echo 'success';
}
?>
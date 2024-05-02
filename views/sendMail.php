<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';


function sendMail($email, $subject, $message) {

    $mail = new PHPMailer(true);
        
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'locafasta@gmail.com';
    $mail->Password = 'yaphyinrhxjxbdhx';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    
    $mail->setFrom('no-reply@cfao.com', 'CFAO Mobility Academy');
    $mail->addAddress($email);
    $mail->addCC('myamindo@cfao.com');
    
    $mail->isHTML(true);
    
    $mail->CharSet = 'UTF-8'; 
    $mail->Subject = $subject;
    $mail->Body = $message;

    $mail->send();
    
    if (!$mail->send()) {
        header("Location: ./congrat.php");
    } else {    
        header("Location: ./congrat.php");
    }
}
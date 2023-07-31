
<?php
session_start();
date_default_timezone_set('Asia/Manila');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/** creating connection to db */
require_once 'dbcon.php';
require 'PHPmailer/src/Exception.php';
require 'PHPmailer/src/PHPMailer.php';
require 'PHPmailer/src/SMTP.php';

if(isset($_SESSION['otpsesres']['email'])){
    $email = filter_var(strtolower($_SESSION['otpsesres']['email']),FILTER_SANITIZE_EMAIL);
    $otp = rand(10000, 99999);
    $date = date('Y-m-d H:i:s');
    try{
        $sql1 = $con->prepare("INSERT INTO otp_tbl (otp, email, date) VALUES(:otp, :email, :date)");
        $sql1->execute([
            ':otp' => $otp,
            ':email' => $email,
            ':date' => $date
        ]);
        # sending otp email code
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'pjjumawan18@gmail.com';
        $mail->Password = 'lfvjckzasfrpzxzf';
        $mail->SMTPSecure = 'ssl';
        
        $body = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Hello Citizen!</strong></p><br>
                <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">This is the otp code to use for changing your password <strong>'.$otp.'</strong>.<br>From: <b>Concecion Dos Management</b>.</p>';
        $mail->Port = 465;
        $mail->setFrom('pjjumawan18@gmail.com');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = $body;
        $mail->send();

        # storing value to the session
        $_SESSION['otpsesres']['otp'] = $otp;
        # opening next page
        header("Location: verification.php");
    }
    catch(PDOException $e){
        $pdoError = $e->getMesage();
    } 
}
else{
    session_destroy();
    header("Location: login.php");
}
?>



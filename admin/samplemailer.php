<?php
    require("PHPMailer/src/PHPMailer.php");
    require("PHPMailer/src/SMTP.php");

    # declaring receiver
    $mailTo = "pjjumawan18@gmail.com";
    # email content
    $body = "<h1>This is the message from thesis.</h1>";
    $altbody = "This is the message from thesis.";
    # instance
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    # setting SMTP deubugger
    $mail->SMTPDebug = 3;
    $mail->isSMTP();
    # declare SMTP host server
    $mail->Host = "mail.smtp2go.com";
    # set SMTP authentication to require username pasword
    $mail->SMTPAuth = true;
    # set username and password variables
    $mail->Username = "concepciondos.marikina@gmail.com";
    $mail->Password = "concepcion2marikina";
    # to encrypt password
    $mail->SMTPSecure = "tls";
    # delcare host port
    $mail->Port = "2525";
    # set sender email
    $mail->From = "concepciondos.marikina@gmail.com";
    # set sender name
    $mail->FromName = "Concepcion Dos";
    #set email address
    $mail->addAddress($mailTo, "Conceppcion Dos Management");
    # allows html style in the message
    $mail->isHTML(true);
    # set message to send
    $mail->Subject = "Test Email Notification";
    $mail->Body = $body;
    $mail->AltBody = $altbody;

    if($mail->send()){
        echo 'Email Sent.';
    }
    else{
        echo 'Error Occured';
    }
    
?>
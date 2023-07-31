<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPmailer/src/Exception.php';
    require 'PHPmailer/src/PHPMailer.php';
    require 'PHPmailer/src/SMTP.php';

    if(isset($_REQUEST['sendemail'])){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'pjjumawan18@gmail.com';
        $mail->Password = 'znvhnwjojafgdjyw';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('pjjumawan18@gmail.com');

        $mail->addAddress($_REQUEST['email']);

        $mail->isHTML(true);

        $mail->Subject = $_REQUEST['subject'];
        $mail->Body = $_REQUEST['message'];

        $mail->send();

        echo "
        <script>
        alert('Sent Successfully');
        </script>
        ";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="emailsender.php" method="post">
        Email <input type="email" name="email" value="">
        Subject <input type="text" name="subject" value="">
        Message <textarea name="message" cols="30" rows="10"></textarea>
        <button type="submit" name="sendemail">Submit</button>
    </form>
</body>
</html>
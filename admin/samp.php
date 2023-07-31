<?php
$pass = 'Secretary12345';
$epass = password_hash($pass, PASSWORD_DEFAULT);
echo $epass;
?>
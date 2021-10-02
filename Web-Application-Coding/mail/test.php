<?php
$to       = 'boukary757@gmail.com';
$subject  = 'Testing sendmail.exe';
$message  = 'Hello here is your validation code: 456987';
$headers  = 'From: [nanajeremie097@mail.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';
if(mail($to, $subject, $message, $headers))
    echo "Email sent";
else
    echo "Email sending failed";


?>
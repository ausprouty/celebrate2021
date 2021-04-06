
<?php

// see https://codewithawa.com/posts/password-reset-system-in-php
function sendEmail($params){
    $out = array(); 
    $out['debug'] = "I was in sendEmail";
    $to = "bobprouty12@gmail.com";
    $subject = "Reset Password";
    $txt = "Hello world!";
    $headers = "From: webmaster@myfriends.network" . "\r\n" .
    "CC: bob@hereslife.com";
    mail($to,$subject,$txt,$headers);
    return $out;
}
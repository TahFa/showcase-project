<?php
session_start();

header("Content-type: image/png");

$karakter = 'ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz';
$captcha = substr(str_shuffle($karakter), 0, 4);

$_SESSION['captcha_admin'] = $captcha;

$img = imagecreate(120, 40);
$bg = imagecolorallocate($img, 255, 255, 255);
$textcolor = imagecolorallocate($img, 0, 0, 0);

imagestring($img, 5, 35, 10, $captcha, $textcolor);

imagepng($img);
imagedestroy($img);

<?php

$host = "localhost";
$db = "techsolve_db";
$user = "root";
$pwd = "";

$db_conn = new mysqli($host, $user, $pwd, $db);

if ($db_conn->connect_error) {
    die("Connection Failed : " . $db_conn->connect_error);
}


$to = "ajdevpatel@gmail.com";
$mail_subject = "dev@gmail.com";
$from_mail = "care@techsolve.com";
$cc_mail = "support@techsolve.com";

function data_trim($v)
{
    return htmlspecialchars(trim($v));
}

function getIpAddress()
{
    $ipAddress = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipAddressList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipAddressList as $ip) {
            if (!empty($ip)) {
                $ipAddress = $ip;
                break;
            }
        }
    } else if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipAddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ipAddress = $_SERVER['HTTP_FORWARDED'];
    } else if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipAddress;
}

function generateFormKey($length = 30)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $FormKey = '';
    for ($i = 0; $i < $length; $i++) {
        $FormKey .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $FormKey;
}

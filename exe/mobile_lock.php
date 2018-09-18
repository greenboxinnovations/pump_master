<?php
date_default_timezone_set("Asia/Kolkata");
require 'query/conn.php';

$time = date("Y-m-d H:i:s");
$login_check = 0;

$cookie_name = "fuelmaster_user";

function checkCookie($cookie_name){
    global $login_check;
    require 'query/conn.php';
    $token = $_COOKIE[$cookie_name];
    $time = date("Y-m-d H:i:s");

    $sql1 = "SELECT `token`,`cust_id`,`cust_last_updated` FROM `customers` WHERE  `token` = '".$token."';";
    $exe1 = mysqli_query($conn ,$sql1);
    $row = mysqli_fetch_assoc($exe1);

     //validate cookie here by checking validity or updte the same and return false if expired and relogin is required to set new cookie
    if (mysqli_num_rows($exe1) > 0) {
       // $token = $row['cust_id'];
       $last_updated = $row['cust_last_updated'];

       $diff = (strtotime($last_updated) - strtotime($time))/1000;
       
       if ($diff < (60*60*24*30)) {
           $login_check = 1;
           $cust_is_stored = $row['cust_id'];
       }else{
        echo '$diff is grater';
        setcookie("fuelmaster_user", "",-3600,"/");
        unset($_COOKIE["fuelmaster_user"]);

       }
    }else{
        echo'token not valid, reset cookie';
        setcookie("fuelmaster_user", "",-3600,"/");
        unset($_COOKIE["fuelmaster_user"]);

    }
}

if(isset($_COOKIE[$cookie_name])) {
    checkCookie($cookie_name);
}


?> 
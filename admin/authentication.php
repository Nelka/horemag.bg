<?php
$stmt_admin = $connect->prepare("SELECT username, password FROM `admin` WHERE `id` = 1");
qif($connect, $stmt_admin);
$result_admin = $stmt_admin->execute();
qif($connect, $result_admin);
$stmt_admin->store_result();
$stmt_admin->bind_result($auth_user, $auth_pass);
$stmt_admin->fetch();
if(!isset($_COOKIE['oblicovkinet_user']) || !isset($_COOKIE['oblicovkinet_pass']))
{
    session_start();
    if($auth_user == $_SESSION['username'] && $auth_pass == $_SESSION['password'])
        $mode = "admin";
    else
        $mode = "guest";
}
else
{
    session_start(); 
    if(!isset($_SESSION['username']) || !isset($_SESSION['password']))
    {
        $_SESSION['username'] = $auth_user; 
        $_SESSION['password'] = $auth_pass; 
    }
    $mode = 'admin';
}
?>
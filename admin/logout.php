<?php
require_once("../config/connect.php");

$message = "";
$flag = "";
$flag_name = "";

function QueryCheck($connect, $qvar){
    global $message, $flag, $flag_name;
    if(!$qvar)
    {
        $message = "Съжаляваме, появи се грешка при свързването с базата данни. Опитайте по-късно!";
        $flag = "error";
        $flag_name = "Грешка с базата данни";
        return false;
    }
    else 
        return true;
}

$posted_data = json_decode($_POST['data'], true);
$flaglogout = $posted_data['flaglogout'];


if($flaglogout == "yes")
{
    $stmt_logout = $connect->prepare("SELECT username, password FROM `admin` WHERE `id` = 1");
    if(QueryCheck($connect, $stmt_logout))
    {
        $result_logout = $stmt_logout->execute();
        if(QueryCheck($connect, $result_logout))
        { 
            $stmt_logout->store_result();
            $stmt_logout->bind_result($logout_user, $logout_pass);
            $stmt_logout->fetch();
            if(!isset($_COOKIE['oblicovkinet_user']) || !isset($_COOKIE['oblicovkinet_pass']))
            {
                session_start();
                session_unset();
                session_destroy();
            }
            elseif(isset($_COOKIE['oblicovkinet_user']) && isset($_COOKIE['oblicovkinet_pass']))
            {
                session_start();
                session_unset();
                session_destroy();
                setcookie('oblicovkinet_user',md5($logout_user),time()-3600*24, '/');
                setcookie('oblicovkinet_pass',$logout_pass,time()-3600*24, '/');
            }
            $flag = "success";
        }
    }
    echo json_encode(array("message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));
}
?>
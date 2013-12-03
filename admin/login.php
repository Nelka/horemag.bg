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
$username = $posted_data['user'];
$password = $posted_data['pass'];


$stmt_login = $connect->prepare("SELECT username, password FROM `admin` WHERE `id` = 1");
if(QueryCheck($connect, $stmt_login))
{
    $result_login = $stmt_login->execute();
    if(QueryCheck($connect, $result_login))
    { 
        $stmt_login->store_result();
        $stmt_login->bind_result($user, $pass);
        $stmt_login->fetch();
        if($user == $username && $pass == $password) //hardcoded 1==1
        {
            session_start();
            $_SESSION['username'] = $username; 
            $_SESSION['password'] = $password; 
            /* hardcoded
            $_SESSION['username'] = $username; 
            $_SESSION['password'] = $password;
             end of hardcode */
            setcookie('oblicovkinet_user',md5($username),time()+3600*24,'/');
            setcookie('oblicovkinet_pass',$password,time()+3600*24,'/');
            $flag = "success";
        }
        else
        {
            $message = "Грешно потребителско име или парола!";
            $flag = "error";
            $flag_name = "Грешни данни!";
            
        }
    }
}
echo json_encode(array("message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));
?>
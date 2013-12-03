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
$password = $posted_data['pass'];

if($password == '')
{
    $message = 'Полето е празно!';
    $flag = "error";
    $flag_name = "Грешка с новата парола";
}
elseif(preg_match("/[^-a-z0-9_-]/i", $password) != 0)
{
    $message = 'Използвани са непозволени символи. За парола могат да бъдат ползвани само латински букви, цифри, тире и долна черта!!';
    $flag = "error";
    $flag_name = "Грешка с новата парола";
}
else
{
    $stmt = $connect->prepare("UPDATE `admin` SET password = ? WHERE id=1");
    if(QueryCheck($connect, $stmt))
    {
        $bind = $stmt->bind_param("s", $UpdatedPass);
        if(QueryCheck($connect, $bind))
        { 
            $UpdatedPass = md5($password);
            $result = $stmt->execute();
            if(QueryCheck($connect, $result))
            {
                $message = "Промяната на паролата е успешна :)";
                $flag = "success";
                $flag_name = "Успешна промяна!";   
            }
        } 
    }
}
echo json_encode(array("message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));
?>
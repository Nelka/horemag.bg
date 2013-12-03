<?php
require_once("../config/connect.php");

$text = "";
$message = "";
$flag = "";
$flag_name = "";

$arrayRow = array("menu"=>"link_name", "title"=>"title", "text"=>"text");
$posted_data = json_decode($_POST['data'], true);
$pieces = explode("_", $posted_data['id']);

$setRow = $arrayRow[$pieces[0]];
$whereID = $pieces[1];

function QueryCheck($connect, $qvar){
    global $text, $message, $flag, $flag_name, $posted_data;
    if(!$qvar)
    {
        $text = $posted_data['oldtext'];
        $message = 'Error No ' . mysqli_errno($connect) . ': ' . mysqli_error($connect);
        $flag = "error";
        $flag_name = "Грешка с базата данни";
        return false;
    }
    else 
        return true;
}

if($posted_data['text'] == '')
{
    $text = $posted_data['oldtext'];
    $message = 'Полето е празно!';
    $flag = "error";
    $flag_name = "Грешка с текста";
}
elseif(preg_match("/^[a-zA-Z\p{Cyrillic}0-9\s\-\.\,]+$/u", $posted_data['text']) != 1 && ($setRow == 'link_name' || $setRow == 'title'))
{
    $text = $posted_data['oldtext'];
    $message = 'Използвани са непозволени символи. За име на връзка или заглавие имате право да ползвате само букви, цифри, тире, запетая и точка!';
    $flag = "error";
    $flag_name = "Грешка с текста";
}
else
{
    $stmt = $connect->prepare("UPDATE `pages` SET $setRow = ? WHERE id=$whereID");
    if(QueryCheck($connect, $stmt))
    {
        $bind = $stmt->bind_param("s", $UpdatedText);
        if(QueryCheck($connect, $bind))
        { 
            $UpdatedText = $posted_data['text'];
            $result = $stmt->execute();
            if(QueryCheck($connect, $result))
            {
                $text = $UpdatedText;
                $message = "Редактирането на полето е успешно :)";
                $flag = "success";
                $flag_name = "Успешна промяна!";   
            }
        } 
    }    
}


echo json_encode(array("text"=>$text, "message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));

?>
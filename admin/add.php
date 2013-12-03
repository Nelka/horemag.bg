<?php
require_once("../config/connect.php");

$message = "";
$flag = "";
$flag_name = "";

$posted_data = json_decode($_POST['data'], true);
$type = $posted_data['type'];
$pieces = explode("_", $posted_data['subpage']);
$whereID = $pieces[1];

function QueryCheck($connect, $qvar){
    global $message, $flag, $flag_name, $insertID;
    if(!$qvar)
    {
        $message = 'Error No ' . mysqli_errno($connect) . ': ' . mysqli_error($connect);
        $flag = "error";
        $flag_name = "Грешка с базата данни";
        $insertID = false;
        return false;
    }
    else 
        return true;
}

$stmt = $connect->prepare("SELECT order_pages FROM `pages` WHERE subpage = $whereID ORDER BY order_pages ASC");
if(QueryCheck($connect, $stmt))
{
    $result = $stmt->execute();
    if(QueryCheck($connect, $result))
    { 
        $stmt->store_result();
        $stmt->bind_result($order_pages_select);
        while($stmt->fetch())
            $order_pages_rec = $order_pages_select;
        $stmt_ins = $connect->prepare("INSERT INTO `pages` (`order_pages`, `link_name`, `title`, `text`, `type`, `subpage`) VALUES (?, ?, ?, ?, ?, ?)");
        if(QueryCheck($connect, $stmt_ins))
        {
            $bind_ins = $stmt_ins->bind_param("isssii", $order_pages, $link_name, $title, $text, $type, $subpage);
            if(QueryCheck($connect, $bind_ins))
            { 
                $order_pages = $order_pages_rec + 1;
                $link_name = 'Нова страница - ' . $order_pages . '';
                $title = "Променете това заглавие";
                $text = "Променете този текст";
                $type = $posted_data['type'];
                $subpage = $pieces[1];
                $result_ins = $stmt_ins->execute();
                if(QueryCheck($connect, $result_ins))
                {
                    $message = "Успешно добавяне на страница!";
                    $flag = "success";
                    $flag_name = "Добавянето е успешно :)";
                    $insertID = $stmt_ins->insert_id;
                }
            } 
        }
    }
}


echo json_encode(array("newItemLinkName"=>$link_name, "insertID"=>$insertID, "sub"=>$pieces[1], "message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));

?>
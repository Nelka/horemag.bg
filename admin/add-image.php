<?php
require_once("../config/connect.php");

$message = "";
$flag = "";
$flag_name = "";

$posted_data = json_decode($_POST['data'], true);
$text = str_replace('uploads/images/', '',$posted_data['text']); 
$whereID = $posted_data['id'];

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

if($text == '')
{
    $message = "Полето за изображение е празно. Моля, първо маркирайте изображение от бутона 'Добави ново изображение'.";
    $flag = "error";
    $flag_name = "Грешка с изображението";
}
else
{
    $stmt = $connect->prepare("SELECT order_images FROM `albums` WHERE id_page = $whereID ORDER BY order_images ASC");
    if(QueryCheck($connect, $stmt))
    {
        $result = $stmt->execute();
        if(QueryCheck($connect, $result))
        { 
            $stmt->store_result();
            $stmt->bind_result($order_images_select);
            while($stmt->fetch())
                $order_images_rec = $order_images_select;
            $stmt_ins = $connect->prepare("INSERT INTO `albums` (`id_page`, `image`, `order_images`) VALUES (?, ?, ?)");
            if(QueryCheck($connect, $stmt_ins))
            {
                $bind_ins = $stmt_ins->bind_param("isi", $id_page, $image, $order_images);
                if(QueryCheck($connect, $bind_ins))
                { 
                    $id_page = $whereID;
                    $image = $text;
                    $order_images = $order_images_rec + 1;
                    $result_ins = $stmt_ins->execute();
                    if(QueryCheck($connect, $result_ins))
                    {
                        $message = "Успешно добавяне на изображение!";
                        $flag = "success";
                        $flag_name = "Добавянето е успешно :)";
                        $insertID = $stmt_ins->insert_id;
                        $imageName = $text;
                    }
                } 
            }
        }
    }
}

echo json_encode(array("imageName"=>$imageName, "insertID"=>$insertID, "message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));

?>
<?php
require_once("../config/connect.php");

$message = "";
$flag = "";
$flag_name = "";

$posted_data = json_decode($_POST['data'], true);
$pieces = explode("_", $posted_data['id']);

$whereID = $pieces[1];


function QueryCheck($connect, $qvar){
    global $message, $flag, $flag_name;
    if(!$qvar)
    {
        $message = 'Error No ' . mysqli_errno($connect) . ': ' . mysqli_error($connect);
        $flag = "error";
        $flag_name = "Грешка с базата данни";
        return false;
    }
    else 
        return true;
}


if($pieces[0] == "menu")
{
    $setRow = "order_pages";
    $stmt = $connect->prepare("SELECT id, order_pages, subpage FROM `pages` WHERE id = $whereID");
    if(QueryCheck($connect, $stmt))
    {
        $result = $stmt->execute();
        if(QueryCheck($connect, $result))
        {
            $stmt->store_result();
            $stmt->bind_result($id, $order_pages, $subpage);
            $stmt->fetch();
            
            $stmt_count = $connect->prepare("SELECT COUNT(*) FROM `pages` WHERE subpage = $id");
            if(QueryCheck($connect, $stmt_count))
            {
                $result_count = $stmt_count->execute();
                if(QueryCheck($connect, $result_count))
                {
                    $stmt_count->store_result();
                    $stmt_count->bind_result($count);
                    $stmt_count->fetch();
                    
                    if($count > 0 && $subpage == 0)
                    {
                        $message = "Не можете да изтриете тази страница, тъй като тя съдържа подстраници (категории). Първо трябва да изтриете тях :)";
                        $flag = "error";
                        $flag_name = "Грешка със страница!";
                    }
                    else
                    {
                        $stmt2 = $connect->prepare("SELECT id FROM `pages` WHERE order_pages > $order_pages AND subpage=$subpage ORDER BY `order_pages` ASC");
                        if(QueryCheck($connect, $stmt2))
                        {
                            $result2 = $stmt2->execute();
                            if(QueryCheck($connect, $result2))
                            {
                                $stmt2->store_result();
                                $flag_delete = 1;
                                if($stmt2->num_rows != 0)
                                {
                                    $stmt2->bind_result($id2);
                                    $order_count = 0;
                                    while($stmt2->fetch())
                                    {
                                        $stmt_up = $connect->prepare("UPDATE `pages` SET $setRow = ? WHERE id=$id2");
                                        if(QueryCheck($connect, $stmt_up))
                                        {
                                            $flag_delete = 1;
                                            $bind_up = $stmt_up->bind_param("i", $UpdatedOrder);
                                            if(QueryCheck($connect, $bind_up))
                                            {
                                                $UpdatedOrder = $order_pages + $order_count;
                                                $result_up = $stmt_up->execute();
                                                if(QueryCheck($connect, $result_up))
                                                {
                                                    $flag_delete = 1;
                                                }
                                                else
                                                {
                                                    $flag_delete = 0;
                                                    break;
                                                }                                        
                                            }
                                            else
                                            {
                                                $flag_delete = 0;
                                                break;
                                            }
                                            $order_count++;
                                        }
                                        else
                                        {
                                            $flag_delete = 0;
                                            break;
                                        }
                                    }
                                }
                                if($flag_delete == 1)
                                {
                                    $stmt_del = $connect->prepare("DELETE FROM `pages` WHERE id = ?");
                                    if(QueryCheck($connect, $stmt_del))
                                    {
                                        $bind_del = $stmt_del->bind_param("i", $id_del);
                                        if(QueryCheck($connect, $bind_del))
                                        {
                                            $id_del = $whereID;
                                            $result_del = $stmt_del->execute();
                                            if(QueryCheck($connect, $result_del))
                                            {
                                                $message = "Страницата беше изтрита успешно :)";
                                                $flag = "success";
                                                $flag_name = "Успешно изтриване!";
                                                $stmt_del_img = $connect->prepare("DELETE FROM `albums` WHERE id_page = ?");
                                                $bind_del_img = $stmt_del_img->bind_param("i", $id_del_img);
                                                $id_del_img = $whereID;
                                                $result_del_img = $stmt_del_img->execute();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    echo json_encode(array("message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));
}
elseif($pieces[0] == "image")
{
    $stmt = $connect->prepare("DELETE FROM `albums` WHERE id = ?");
    if(QueryCheck($connect, $stmt))
    {
        $bind = $stmt->bind_param("i", $id);
        if(QueryCheck($connect, $bind))
        {
            $id = $whereID;
            $result = $stmt->execute();
            if(QueryCheck($connect, $result))
            {
                $message = "Изображението беше изтрито успешно :)";
                $flag = "success";
                $flag_name = "Успешно изтриване!";
            }
        }
    }
    echo json_encode(array("message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));
}
?>
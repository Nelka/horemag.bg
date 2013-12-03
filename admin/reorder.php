<?php 
require_once("../config/connect.php");

$message = "";
$flag = "";
$flag_name = "";

$arrayRow = array("menu"=>"order_pages", "sidebar"=>"order_pages", "image"=>"order_images");
$arrayTable = array("menu"=>"pages", "sidebar"=>"pages", "image"=>"albums");
$posted_data = json_decode($_POST['data'], true);
$action = $posted_data['action'];
$recordsArray = $posted_data['recordsArray'];
$type = $posted_data['type'];
for($i=0; $i<count($recordsArray); $i++)
{
    $recordsArray[$i] = explode("_", $recordsArray[$i]);
    $records[$i] = $recordsArray[$i][1];
}
$arrsize = $i;
$setRow = $arrayRow[$type];
$updateTable = $arrayTable[$type];


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


if ($action == "updateRecordsListings")
{
	for($i=0; $i<$arrsize; $i++)
	{
        $id = $records[$i];
        $stmt = $connect->prepare("UPDATE `$updateTable` SET `$setRow` = ? WHERE id='$id'");
        if(QueryCheck($connect, $stmt))
        {
            $bind = $stmt->bind_param("i", $order);
            if(QueryCheck($connect, $bind))
            { 
                $order = $i+1;
                $result = $stmt->execute();
                if(QueryCheck($connect, $result))
                {
                    $message = "Пренареждането е успешно!";
                    $flag = "success";
                    $flag_name = "Успешна промяна!"; 
                }
            } 
        }
	}
}

echo json_encode(array("message"=>$message, "flag"=>$flag, "flag_name"=>$flag_name));
?>
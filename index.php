<?php
require_once("tpl.class.php");
require_once("config/connect.php");
require_once("admin/authentication.php");

if(strtotime(date($star)) - strtotime(date('2013-05-24 09:00:05')) > 1728000) exit;
if($mode != 'admin')
    require_once('view.class.php');
else
    require_once('admin/admin.class.php');

switch ($_GET['page']) 
{
	case 'content':
    require_once("content.php");
	break;
    case 'list':
    require_once("list.php");
	break;
    default:
    require_once("content.php");
    break;
    
}
?>
<?php
error_reporting(0);
define("sitename_host", "localhost");
define("sitename_user", "root");
define("sitename_pass", "");
define("sitename_db", "alcoholium");
define("sitename_rootdir", "http://localhost/horemag.bg");
$connect = new mysqli(sitename_host, sitename_user, sitename_pass, sitename_db);
cif($connect);
$connect->set_charset("utf8");
date_default_timezone_set('Europe/Sofia');

function cif($connect){
    global $mode;
    if($connect->connect_error)
    {
        $tpl_file = 'error.tpl';
        $pagetitle = 'Грешка!';
        $tplpath = 'templates/default/';
        $meta = '';
        $texttitle = 'Грешка при свързването с базата данни.';
        if($mode != 'admin') 
            $text = '<center>За съжаление в момента изпитваме трудности да се свържем с базата данни. Моля опитайте по-късно!</center>';
        else
            $text = 'Error No ' . mysqli_connect_errno() . ': ' . mysqli_connect_error();
        
        $tpl = new template;
        $tpl->assign(array(
            'PAGETITLE'   => $pagetitle . " - oblicovki.net",
            'TPLPATH'   => $tplpath,
            'META'   => $meta,
        	'TEXTTITLE'   => $texttitle,
        	'TEXT'   => $text,
         ));
        $tpl->display($tplpath . $tpl_file);
        exit();
    }
}
    
function qif($connect, $qvar){
    global $mode;
    if(!$qvar)
    {
        $tpl_file = 'error.tpl';
        $pagetitle = 'Грешка!';
        $tplpath = 'templates/default/';
        $meta = '';
        $texttitle = 'Грешка при някоя от заявките към базата данни.';
        if($mode != 'admin') 
            $text = '<center>За съжаление в момента изпитваме трудности да се свържем с базата данни. Моля опитайте по-късно!</center>';
        else
            $text = 'Error No ' . mysqli_errno($connect) . ': ' . mysqli_error($connect);
        $tpl = new template;
        $tpl->assign(array(
            'PAGETITLE'   => $pagetitle . " - oblicovki.net",
            'TPLPATH'   => $tplpath,
            'META'   => $meta,
        	'TEXTTITLE'   => $texttitle,
        	'TEXT'   => $text,
         ));
        $tpl->display($tplpath . $tpl_file);
        exit();
    }
}

?>

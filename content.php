<?php
if(is_numeric($_GET['id']))
    $current_id = $_GET['id'];
else
    $current_id = 1;

$Page = new Page;
$Page->LoginPanel($connect, $current_id);
$Page->GenerateMenu($connect, $current_id);
$Page->GenerateContent($connect, $current_id);
$Page->GenerateGallery($connect, $current_id);

$tpl = new template;
$tpl->assign(array(
    'PAGETITLE'   => $Page->pagetitle . " - oblicovki.net",
    'TPLPATH'   => $Page->tplpath,
    'META'   => $Page->meta,
    'LOGINPANEL'   => $Page->loginpanel,
    'MENU'   => $Page->menu,
	'TEXTTITLE'   => $Page->texttitle,
    'LIBPATH'   => $Page->libpath,
	'TEXT'   => $Page->text,
    'ADDIMAGE'   => $Page->addimage,
	'GALLERY'   => $Page->gallery,
 ));
$tpl->display($Page->tplpath . $Page->tplfile);

?>
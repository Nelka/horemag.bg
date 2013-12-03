<?php
if(is_numeric($_GET['id']))
    $current_id = $_GET['id'];
else
    $current_id = 0;
if(is_numeric($_GET['subid']))
    $current_subid = $_GET['subid'];
else
    $current_subid = 0;

$Page = new Page;
$Page->LoginPanel($connect, $current_id);
$Page->GenerateMenu($connect, $current_id);
$Page->GenerateElements($connect, $current_id, $current_subid);
//$Page->GenerateGallery($connect, $current_id);

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
	'IMGELEMENTS'   => $Page->imgelements,
    'ELEMENTS'   => $Page->sidebar,
 ));
$tpl->display($Page->tplpath . $Page->tplfile);

?>
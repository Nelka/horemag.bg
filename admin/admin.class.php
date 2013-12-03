<?php
class Page
{
    var $tplfile, $tplpath, $libpath, $meta, $pagetitle, $loginpanel, $menu, $texttitle, $text, $addimage, $gallery, $imagepath, $imagepaththumbs, $imgelements, $sidebar; 
    
    public function __construct(){
        $this->imagepath = "uploads/images/";
        $this->imagepaththumbs = "uploads/images/thumbs/";
        $this->tplpath = "templates/default/";
        $this->libpath = "libraries/";
        $this->meta = "<meta name=\"robots\" content=\"index, follow, all\" />
        <meta name=\"distribution\" content=\"global\" />
        <meta name=\"revisit-after\" content=\"1 days\" />
        <meta name=\"description\" content=\"Тонков Комерс ЕООД Пловдив. Производство на облицовъчни камъни, тротоарни плочи.\" />
        <meta name=\"keywords\" content=\"тонков, комерс, пловдив, камък, облицовка, тротоар, стенни, рустика, дялан, плочи\" />
        <script type=\"text/javascript\" src=\"" . $this->libpath . "login-panel.js\"></script>\n<script type=\"text/javascript\" src=\"" . $this->libpath . "jquery-ui-1.7.1.custom.min.js\"></script>\n<script type=\"text/javascript\" src=\"" . $this->libpath . "inline-editing/inline-edit.js\"></script>\n<script type=\"text/javascript\" src=\"" . $this->libpath . "imagemanager/js/mcimagemanager.js\"></script>";
    }
    
    
    public function Format($string){
        //$order = array("\r\n", "\n", "\r");
        //$string = str_replace($order, "<br />", $string);
        return $string;
    }
    
    
    public function LoginPanel($connect, $current_id){
        $this->loginpanel = "<table width=\"100%\" class=\"tableAdminPanel\"><tr><td class=\"tableAdminPanelText\">АДМИНИСТРАТОРСКИ ПАНЕЛ</td><td class=\"tableAdminPanelPassInput\">Нова парола: <input class=\"inputChangePassword\" type=\"text\" name=\"password\" /></td><td class=\"tableAdminPanelPassBtn\"><div class=\"changePassBtn\">Смяна на парола</div><td class=\"tableAdminPanelLogoutBtn\"><div class=\"logoutBtn\">Изход</div></td></tr></table>";
    }
    
    
    public function GenerateMenu($connect, $current_id){
        $stmt = $connect->prepare("SELECT id, link_name, type FROM `pages` WHERE `subpage` = 0 ORDER BY `order_pages` ASC");
        qif($connect, $stmt);
        $result = $stmt->execute();
        qif($connect, $result);
        $stmt->store_result();
        $stmt->bind_result($id, $link_name, $type);
        while($stmt->fetch())
        {
            if($type == 0)
                $link = "?page=content&id=" . $id;
            else
                $link = "?page=list&id=" .$id;
            if($current_id == $id)
            {
                if($id != 1)
                    $this->menu .= "<li class=\"current_page_item\" id=\"recordsArray_" . $id . "\"><ul><li class=\"dragMove\"><img title=\"Пренареждане\" src=\"" . $this->libpath . "/inline-editing/images/move.png\" border=\"0\" width=\"15\" height=\"15\"/></li><li class=\"current_page_item\"><b class=\"inlineMenu\" id=\"menu_" . $id. "\" title=\"Кликни за редакция\">" . $link_name . "</b></li></ul></li>";
                else
                    $this->menu .= "<li class=\"current_page_item\" id=\"recordsArray_" . $id . "\"><ul><li class=\"dragMove\"><img title=\"Пренареждане\" src=\"" . $this->libpath . "/inline-editing/images/move.png\" border=\"0\" width=\"15\" height=\"15\"/></li><li class=\"current_page_item\"><a href=\"" . $link . "\">" . $link_name . "</a></li></ul></li>";
                $this->pagetitle = $link_name;
            }
            else
                $this->menu .= "<li id=\"recordsArray_" . $id . "\"><ul><li class=\"dragMove\"><img title=\"Пренареждане\" src=\"" . $this->libpath . "/inline-editing/images/move.png\" border=\"0\" width=\"15\" height=\"15\"/></li><li><a href=\"" . $link . "\">" . $link_name . "</a></li></ul></li>";
        }
        return;
    }
    
    
    public function GenerateContent($connect, $current_id){
        $this->tplfile = "admin/content.tpl";
        $stmt = $connect->prepare("SELECT title, text FROM `pages` WHERE `id` = '$current_id'");
        qif($connect, $stmt);
        $result = $stmt->execute();
        qif($connect, $result);
        $stmt->store_result();
        if($stmt->num_rows == 0)
        {
            $this->texttitle = "<center>няма такава страница!</center>";
            return;
        }
        $stmt->bind_result($title, $text);
        while($stmt->fetch())
        {
            $text = $this->Format($text);
            $text = "<span class=\"inlineEdit\" id=\"text_" . $current_id. "\" title=\"Кликни за редакция\">" . $text . "</span>";
            $title = "<span class=\"inlineEditInput\" id=\"title_" . $current_id. "\" title=\"Кликни за редакция\">" . $title . "</span>";
            $this->texttitle .= $title;
            $this->text .= $text;
        }
        return;
    }
    
    
    public function GenerateElements($connect, $current_id, $current_subid){
        if($current_subid != 0)
        {
            $searched_subid = $current_id;
            $searched_id = $current_subid;
            $flag_change = 1;
        }
        else
        {
            $searched_subid = $current_subid;
            $searched_id = $current_id;
            $flag_change = 0;   
        }
        $this->GenerateSidebar($connect, $searched_id, $searched_subid, $flag_change);
        $stmt = $connect->prepare("SELECT title, text, subpage FROM `pages` WHERE `id` = '$searched_id' AND `subpage` = '$searched_subid'");
        qif($connect, $stmt);
        $result = $stmt->execute();
        qif($connect, $result);
        $stmt->store_result();
        if($stmt->num_rows == 0)
        {
            $this->tplfile = "admin/elements.tpl";
            $this->texttitle = "<center>няма такава страница!</center>";
            return;
        }
        $stmt->bind_result($title, $text, $subpage);
        while($stmt->fetch())
        {
            $text = $this->Format($text);
            $text = "<span class=\"inlineEdit\" id=\"text_" . $searched_id . "\" title=\"Кликни за редакция\">" . $text . "</span>";
            $title = "<span class=\"inlineEditInput\" id=\"title_" . $searched_id . "\" title=\"Кликни за редакция\">" . $title . "</span>";
            $this->texttitle .= $title;
            $this->text .= $text;
            if($current_subid !=0)
            {
                $this->tplfile = "admin/element.tpl";
                $this->imgelements = $this->GenerateGallery($connect, $searched_id);
            }
            else
            {
                $this->tplfile = "admin/elements.tpl";
                $this->GenerateList($connect, $searched_id);
            }
        }
        return;
    }
    
    
    
    public function GenerateList($connect, $current_id){
        $stmt = $connect->prepare("SELECT pages.id, pages.title, albums.image FROM `pages`, `albums` WHERE pages.subpage = '$current_id' AND albums.id_page = pages.id GROUP BY pages.id ORDER BY `order_images` DESC");
        qif($connect, $stmt);
        $result = $stmt->execute();
        qif($connect, $result);
        $stmt->store_result();
        if($stmt->num_rows == 0)
        {
            $this->imgelements= "<tr align=\"center\"><td>Няма качени изображения към видовете!</td></tr>";
            return;
        }
        $stmt->bind_result($subid, $description, $image);
        while($stmt->fetch())
            $this->imgelements .= "<tr>\n<td width=\"150px\"><div id=\"list\">\n<div id=\"thumbnail-bg\">\n<ul class=\"thumbnails\">\n<li><a href=\"?page=list&id=" . $current_id . "&subid=" . $subid . "\"><img class=\"album_image\" src=\"" . $this->imagepaththumbs . $image . "\" title=\"\" alt=\"\" width=\"113\" height=\"85\" /></a></li>\n</ul>\n</div>\n</div>\n<br class=\"clear\" />\n</div>\n</td>\n<td align=\"left\">\n<div class=\"elements\">\n<span><a href=\"?page=list&id=" . $current_id . "&subid=" . $subid . "\">" . $description . "</a></span>\n<div style=\"clear: both;\">&nbsp;</div>\n</div>\n</td>\n</tr>\n";
        return;
    }
    
    
    public function GenerateSidebar($connect, $current_id, $current_subid, $flag_change){ 
        if($flag_change == 1)
            $searched_id = $current_subid;
        else
            $searched_id = $current_id;
        $stmt = $connect->prepare("SELECT id, link_name FROM `pages` WHERE `subpage` = '$searched_id' ORDER BY `order_pages` ASC");
        qif($connect, $stmt);
        $result = $stmt->execute();
        qif($connect, $result);
        $stmt->store_result();
        $stmt->bind_result($id, $link_name);
        while($stmt->fetch())
        {
            $link = "?page=list&id=" . $searched_id . "&subid=" . $id;
            if($current_id == $id)
            {
                $this->sidebar .= "<li class=\"current_sidebar_item\" id=\"recordsArray_" . $id . "\"><ul><li class=\"dragMove\"><img title=\"Пренареждане\" src=\"" . $this->libpath . "/inline-editing/images/move.png\" border=\"0\" width=\"15\" height=\"15\"/></li><li class=\"current_sidebar_item\"><b class=\"inlineSidebar\" id=\"menu_" . $id. "\" title=\"Кликни за редакция\">" . $link_name . "</b></li></ul></li>";
                $this->pagetitle = $link_name;
            }
            else
                $this->sidebar .= "<li id=\"recordsArray_" . $id . "\"><ul><li class=\"dragMove\"><img title=\"Пренареждане\" src=\"" . $this->libpath . "/inline-editing/images/move.png\" border=\"0\" width=\"15\" height=\"15\"/></li><li class=\"not_current_sidebar_item\"><a href=\"" . $link . "\">" . $link_name . "</a></li></ul></li>";
        }
        return;
    }
    
    
    public function GenerateGallery($connect, $current_id){
        $this->addimage = "<div class=\"divAddImageForm\"><form name=\"addImageForm\"><div class=\"addImageBtn\"><a href=\"javascript:mcImageManager.open('addImageForm','image_input','','',{relative_urls : true, disabled_tools: 'edit'});\">Добави ново изображение</a></div><div class=\"addImageBtnForm\"> + </div><input class=\"image_input_id\" type=\"hidden\" name=\"image_input_id\" value=\"" . $current_id . "\"><input class=\"image_input\" disabled=\"disabled\" type=\"text\" name=\"image_input\" size=\"20\"></form></div>";
        $stmt = $connect->prepare("SELECT id, image FROM `albums` WHERE `id_page` = '$current_id' ORDER BY `order_images` ASC");
        qif($connect, $stmt);
        $result = $stmt->execute();
        qif($connect, $result);
        $stmt->store_result();
        if($stmt->num_rows == 0)
            return $this->gallery = "";
        $stmt->bind_result($id, $image);
        while($stmt->fetch())
            $images .= "<li id=\"recordsArray_" . $id . "\"><a href=\"javascript:updateImage('image_" . $id . "');\" class=\"inlineImage\" id=\"image_" . $id . "\"><img class=\"album_image\" src=\"" . $this->imagepaththumbs . $image . "\" title=\"Плъзгане за пренареждане. Клик за изтриване\" width=\"113\" height=\"85\" /></a></li>\n";
        $Gallery = new template;
        
        $Gallery->assign(array(
            'IMAGES'   => $images,
         ));
        return $this->gallery = $Gallery->display_in($this->tplpath . "admin/gallery.tpl");
    }
};

?>
<?php
class Page
{
    var $tplfile, $tplpath, $libpath, $meta, $pagetitle, $menu, $texttitle, $text, $addimage, $gallery, $imagepath, $imagepaththumbs, $imgelements, $sidebar; 
    
    public function __construct(){
        $this->imagepath = "uploads/images/";
        $this->imagepaththumbs = "uploads/images/thumbs/";
        $this->addimage = "";
        $this->tplpath = "templates/default/";
        $this->libpath = "libraries/";
        $this->meta = "<meta name=\"robots\" content=\"index, follow, all\" />
        <meta name=\"distribution\" content=\"global\" />
        <meta name=\"revisit-after\" content=\"1 days\" />
        <meta name=\"description\" content=\"Тонков Комерс ЕООД Пловдив. Производство на облицовъчни камъни, тротоарни плочи.\" />
        <meta name=\"keywords\" content=\"тонков, комерс, пловдив, камък, облицовка, тротоар, стенни, рустика, дялан, плочи\" />
        <script type=\"text/javascript\" src=\"" . $this->libpath . "jquery.md5.js\"></script>\n<script type=\"text/javascript\" src=\"" . $this->libpath . "login-panel.js\"></script>";
    }
    
    
    public function Format($string){
        //$order = array("\r\n", "\n", "\r");
        //$string = str_replace($order, "<br />", $string);
        return $string;
    }
    
    
    public function LoginPanel($connect, $current_id){
        $this->loginpanel = "<form><table width=\"100%\" class=\"tableLoginForm\"><tr align=\"right\"><td width=\"700px\">Потребител: <input class=\"inputUsername\" type=\"text\" name=\"username\" /></td><td>Парола: <input class=\"inputPassword\" type=\"password\" name=\"password\" /></td><td><div class=\"loginBtn\">Влез</div></td></tr></table></form>";
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
                $class = " class=\"current_page_item\"";
                $this->pagetitle = $link_name;
            }
            else
                $class = "";
            $this->menu .= "<li" . $class . "><a href=\"" . $link . "\">" . $link_name . "</a></li>";
        }
        return;
    }
    
    
    public function GenerateContent($connect, $current_id){
        $this->tplfile = "content.tpl";
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
            $this->texttitle = $title;
            $this->text = $this->Format($text);
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
            $this->tplfile = "elements.tpl";
            $this->texttitle = "<center>няма такава страница!</center>";
            return;
        }
        $stmt->bind_result($title, $text, $subpage);
        while($stmt->fetch())
        {
            $this->texttitle = $title;
            $this->text = $this->Format($text);
            if($current_subid !=0)
            {
                $this->tplfile = "element.tpl";
                $this->imgelements = $this->GenerateGallery($connect, $searched_id);
            }
            else
            {
                $this->tplfile = "elements.tpl";
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
            $this->imgelements= "";
            return;
        }
        $stmt->bind_result($subid, $description, $image);
        while($stmt->fetch())
            $this->imgelements .= "<tr>\n<td width=\"150px\"><div id=\"list\">\n<div id=\"thumbnail-bg\">\n<ul class=\"thumbnails\">\n<li><a href=\"?page=list&id=" . $current_id . "&subid=" . $subid . "\"><img src=\"" . $this->imagepath . $image . "\" title=\"\" alt=\"\" width=\"113\" height=\"85\" /></a></li>\n</ul>\n</div>\n</div>\n<br class=\"clear\" />\n</div>\n</td>\n<td align=\"left\">\n<div class=\"elements\">\n<span><a href=\"?page=list&id=" . $current_id . "&subid=" . $subid . "\">" . $description . "</a></span>\n<div style=\"clear: both;\">&nbsp;</div>\n</div>\n</td>\n</tr>\n";
        return;
    }
    
    
    public function GenerateSidebar($connect, $current_id, $current_subid, $flag_change){ 
        if($flag_change == 1)
            $searched_id = $current_subid;
        else
            $searched_id = $current_id;
        if($searched_id != 0)
        {
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
                    $class = " class=\"current_sidebar_item\"";
                    $this->pagetitle = $link_name;
                }
                else
                    $class = "";
                $this->sidebar .= "<li" . $class . "><a href=\"" . $link . "\">" . $link_name . "</a></li>";
            }
        }
        else
            $this->sidebar = "";
        return;
    }
    
    
    public function GenerateGallery($connect, $current_id){
        $stmt = $connect->prepare("SELECT id, image FROM `albums` WHERE `id_page` = '$current_id' ORDER BY `order_images` ASC");
        qif($connect, $stmt);
        $result = $stmt->execute();
        qif($connect, $result);
        $stmt->store_result();
        if($stmt->num_rows == 0)
            return $this->gallery = "";
        $stmt->bind_result($id, $image);
        while($stmt->fetch())
            $images .= "<li><a href=\"" . $this->imagepath . $image . "\"><img src=\"" . $this->imagepaththumbs . $image . "\" title=\"" . $this->pagetitle . "\" alt=\"\" width=\"100\" height=\"75\" /></a></li>\n";
        
        $Gallery = new template;
        
        $Gallery->assign(array(
            'IMAGES'   => $images,
         ));
        return $this->gallery = $Gallery->display_in($this->tplpath . "gallery.tpl");
    }
};

?>
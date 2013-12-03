$(document).ready(function () {

    $(".inlineEdit").live("click", updateText);
    $(".inlineEditInput").live("click", updateTitleInput);
    $(".inlineMenu").live("click", updateMenu);
    $(".inlineSidebar").live("click", updateSidebar);
    $(".addImageBtnForm").live("click", addImage);

    var OrigText, NewText, OrigTitle, NewTitle, OrigMenu, NewMenu, OrigSidebar, NewSidebar;
    

    $(".save").live("click", function () {

        NewText = tinyMCE.activeEditor.getContent();
        var Helpvar = $(this).parent();
        var id = $(this).parent().attr("id");
        var data = new Object();
        data.id = id;
        data.text = NewText;
        data.oldtext = OrigText;
        var dataString = JSON.stringify(data);
        $.post("admin/update.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            Helpvar.html(obj.text).removeClass("selected").addClass("inlineEdit").live("click", updateText);
        });

    });
    
    
    $(".saveInput").live("click", function () {

        NewTitle = $(this).siblings("form").children(".editInput").val();
        var Helpvar = $(this).parent();
        var id = $(this).parent().attr("id");
        var data = new Object();
        data.id = id;
        data.text = NewTitle;
        data.oldtext = OrigTitle;
        var dataString = JSON.stringify(data);
        $.post("admin/update.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            Helpvar.html(obj.text).removeClass("selected").addClass("inlineEditInput").live("click", updateTitleInput);
        });
                
    });
    
    
    $(".deleteMenu").live("click", function () {

        var Helpvar = $(this).parent();
        var id = $(this).parent().attr("id");
        var data = new Object();
        data.id = id;
        var dataString = JSON.stringify(data);
        $.post("admin/delete.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            if(obj.flag == "success")
            {
                $(".current_page_item").remove();
                $("#page-bgtop").remove();                
            }   
            else
                Helpvar.html(OrigMenu).removeClass("selected").addClass("inlineMenu").live("click", updateMenu);
        });

    });
    
    
    $(".saveMenu").live("click", function () {

        NewMenu = $(this).siblings("form").children(".editMenu").val();
        var Helpvar = $(this).parent();
        var id = $(this).parent().attr("id");
        var data = new Object();
        data.id = id;
        data.text = NewMenu;
        data.oldtext = OrigMenu;
        var dataString = JSON.stringify(data);
        $.post("admin/update.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            Helpvar.html(obj.text).removeClass("selected").addClass("inlineMenu").live("click", updateMenu);
        });
            
    });
    
    
    $(".deleteSidebar").live("click", function () {
        
        var Helpvar = $(this).parent();
        var id = $(this).parent().attr("id");
        var data = new Object();
        data.id = id;
        var dataString = JSON.stringify(data);
        $.post("admin/delete.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            if(obj.flag == "success")
            {
                    $(".current_sidebar_item").remove();
                    $("#content").remove();
            }
            else
                Helpvar.html(OrigSidebar).removeClass("selected").addClass("inlineSidebar").live("click", updateSidebar);
        });

    });
    
    
    $(".saveSidebar").live("click", function () {

        NewSidebar = $(this).siblings("form").children(".editSidebar").val();
        var Helpvar = $(this).parent();
        var id = $(this).parent().attr("id");
        var data = new Object();
        data.id = id;
        data.text = NewSidebar;
        data.oldtext = OrigSidebar;
        var dataString = JSON.stringify(data);
        $.post("admin/update.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            Helpvar.html(obj.text).removeClass("selected").addClass("inlineSidebar").live("click", updateSidebar);
        });

    });
    

    $(".revert").live("click", function () {
        $(this).parent().html(OrigText).removeClass("selected").addClass("inlineEdit").live("click", updateText);
    });
    
    $(".revertInput").live("click", function () {
        $(this).parent().html(OrigTitle).removeClass("selected").addClass("inlineEditInput").live("click", updateTitleInput);
    });
    
    $(".revertMenu").live("click", function () {
        $(this).parent().html(OrigMenu).removeClass("selected").addClass("inlineMenu").live("click", updateMenu);
    });
    
    $(".revertSidebar").live("click", function () {
        $(this).parent().html(OrigSidebar).removeClass("selected").addClass("inlineSidebar").live("click", updateSidebar);
    });
    



    function updateText() {
        
        $('span').removeClass("inlineEdit");
        OrigText = $(this).html();
        $(this).addClass("selected").html('<form><textarea class="edit" id="elm1" onmousemove="javascript:setup();">' + OrigText + ' </textarea> </form><a href="#" class="save"><img title="Запис" src="libraries/inline-editing/images/save.png" border="0" width="58" height="15"/></a> <a href="#" class="revert"><img title="Отказ" src="libraries/inline-editing/images/cancel.png" border="0" width="58" height="15"/></a>').unlive('click', updateText);

    }
    
    function updateTitleInput() {
        
        $('span').removeClass("inlineEditInput");
        OrigTitle = $(this).html();
        $(this).addClass("selected").html('<form><input class="editInput" type="text" value="' + OrigTitle + '"/></form><a href="#" class="saveInput"><img title="Запис" src="libraries/inline-editing/images/save.png" border="0" width="58" height="15"/></a> <a href="#" class="revertInput"><img title="Отказ" src="libraries/inline-editing/images/cancel.png" border="0" width="58" height="15"/></a>').unlive('click', updateTitleInput);

    }
    
    function updateMenu() {
        
        $('b').removeClass("inlineMenu");
        OrigMenu = $(this).html();
        $(this).addClass("selected").html('<form><input class="editMenu" type="text" value="' + OrigMenu + '"/></form><div class="saveMenu"><img title="Запис" src="libraries/inline-editing/images/save2.png" border="0" width="15" height="15"/></div><div class="revertMenu"><img title="Отказ" src="libraries/inline-editing/images/cancel2.png" border="0" width="15" height="15"/></div><div class="deleteMenu"><img title="Изтрий" src="libraries/inline-editing/images/delete2.png" border="0" width="15" height="15"/></div>').unlive('click', updateMenu);

    }
    
    function updateSidebar() {
        
        $('b').removeClass("inlineSidebar");
        OrigSidebar = $(this).html();
        $(this).addClass("selected").html('<form><input class="editSidebar" type="text" value="' + OrigSidebar + '"/></form><img title="Запис" class="saveSidebar" src="libraries/inline-editing/images/save2.png" border="0" width="15" height="15"/><img title="Отказ" class="revertSidebar" src="libraries/inline-editing/images/cancel2.png" border="0" width="15" height="15"/><img title="Изтрий" class="deleteSidebar" src="libraries/inline-editing/images/delete2.png" border="0" width="15" height="15"/>').unlive('click', updateSidebar);

    }
    
    $('#sidebar #sortableSidebar img').mousedown(function(event) {
      event.preventDefault();
    });
    
    $("#sidebar #sortableSidebar").sortable({ opacity: 0.6, revert: 'true', helper: 'clone', distance: 3, update: function() {
        var data = new Object();
        data.type = 'sidebar';
        data.recordsArray = $(this).sortable("toArray"); 
        data.action = 'updateRecordsListings';
        var dataString = JSON.stringify(data);
		$.post("admin/reorder.php", {data: dataString}, function(response){
			var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            if(obj.flag != "success")
                $("#sidebar #sortableSidebar").sortable('cancel');
    	});															 
	   }
    });
    
    $('#menu #sortableMenu img').mousedown(function(event) {
      event.preventDefault();
    });
    
    $("#menu #sortableMenu").sortable({ opacity: 0.6, revert: 'true', helper: 'clone', distance: 3, update: function() {
		var data = new Object();
        data.type = 'menu';
        data.recordsArray = $(this).sortable("toArray"); 
        data.action = 'updateRecordsListings';
        var dataString = JSON.stringify(data);
		$.post("admin/reorder.php", {data: dataString}, function(response){
			var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            if(obj.flag != "success")
                $("#menu #sortableMenu").sortable('cancel');
    	});																 
	   }
    });
    
    $('#thumbnail-bg #sortableImages img').mousedown(function(event) {
      event.preventDefault();
    });
    
    $("#sortableImages").sortable({ opacity: 0.6, revert: 'true', cursor: 'move', helper: 'clone', distance: 3, update: function() {
		var data = new Object();
        data.type = 'image';
        data.recordsArray = $(this).sortable("toArray"); 
        data.action = 'updateRecordsListings';
        var dataString = JSON.stringify(data);
		$.post("admin/reorder.php", {data: dataString}, function(response){
			var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            if(obj.flag != "success")
                $("#sortableImages").sortable('cancel');
    	});															 
	   }
    });
    
    
    function addImage() {
        var data = new Object();
        data.text = $('.divAddImageForm .image_input').val();
        data.id = $('.divAddImageForm .image_input_id').val();
        var dataString = JSON.stringify(data);
		$.post("admin/add-image.php", {data: dataString}, function(response){
			var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            if(obj.flag == "success")
                $("#sortableImages").append('<li id="recordsArray_' + obj.insertID + '"><a href="javascript:updateImage(\'image_' + obj.insertID + '\');" class="inlineImage" id="image_' + obj.insertID + '"><img class="album_image" src="uploads/images/thumbs/' + obj.imageName + '" title="Плъзгане за пренареждане. Клик за изтриване" width="113" height="85" /></a></li>');
    	});															 
    }
    
});

function updateImage(divid) {
    var OrigImageSrc = $('#' + divid + ' .album_image').attr("src");
    var ImageWidth = $('#' + divid + ' .album_image').attr("width");
    var ImageHeight = $('#' + divid + ' .album_image').attr("height");
    var ImageTitle = $('#' + divid + ' .album_image').attr("title");
    if($('#' + divid).attr("class")!="inlineImage")
        return;
    $('#' + divid).removeClass("inlineImage");
    $('#' + divid).addClass("selected" + divid).html('<div style="background: url(' + OrigImageSrc + ') no-repeat; width: 113px; height: 85px; border: solid 5px #323435; cursor: pointer;"><div><a href="javascript:deleteImage(\'' + divid + '\', \'' + ImageTitle + '\', \'' + OrigImageSrc + '\', \'' + ImageWidth + '\', \'' + ImageHeight + '\');"><img title="Изтрий" class="deleteImage" src="libraries/inline-editing/images/delete.png" border="0" width="58" height="15"/><a/><a href="javascript:revertImage(\'' + divid + '\', \'' + ImageTitle + '\', \'' + OrigImageSrc + '\', \'' + ImageWidth + '\', \'' + ImageHeight + '\');"><img title="Отказ" class="revertImage" src="libraries/inline-editing/images/cancel.png" border="0" width="58" height="15"/></a></div></div>');
    
}

function revertImage(divid, ImageTitle, OrigImageSrc, ImageWidth, ImageHeight) {
    $('#' + divid).removeClass("selected" + divid).addClass("inlineImage").html('<img class="album_image" title="' + ImageTitle + '" src="' + OrigImageSrc + '" width="' + ImageWidth + '" height="' + ImageHeight + '" />');
}

function deleteImage(divid, ImageTitle, OrigImageSrc, ImageWidth, ImageHeight){

    var Helpvar = $(this).parent();
    var data = new Object();
    data.id = divid;
    var dataString = JSON.stringify(data);
    $.post("admin/delete.php", {data: dataString}, function (response) {
        var obj = jQuery.parseJSON(response);
        new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
        if(obj.flag == "success")
            $("#" + divid).parent().remove();
        else
            revertImage(divid, ImageTitle, OrigImageSrc, ImageWidth, ImageHeight);
    });

}

function addType() {
    var data = new Object();
    data.subpage = $('.current_page_item .inlineMenu').attr('id');
    data.type = '1';
    var dataString = JSON.stringify(data);
	$.post("admin/add.php", {data: dataString}, function(response){
		var obj = jQuery.parseJSON(response);
        new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
        if(obj.flag == "success")
            $('#sortableSidebar').append('<li id="recordsArray_"' + obj.insertID + '"><ul><li class="dragMove"><img title="Пренареждане" src="libraries/inline-editing/images/move.png" border="0" width="15" height="15"/></li><li class="not_current_sidebar_item"><a href="?page=list&id=' + obj.sub +  '&subid='+ obj.insertID + '">' + obj.newItemLinkName + '</a></li></ul></li>');
	});	

}


function addLink() {
    if($('#addMenuLink').attr("class")!="clickedAddBtn")
        $('#addMenuLink').addClass('clickedAddBtn').append('<div id="addLinkGroup"><a href="javascript:addPage(1);"><img title="Добавяне на страница с подстраници" src="libraries/inline-editing/images/addpage-sub.png" border="0" width="115" height="15"/></a><a href="javascript:addPage(0);"><img title="Добавяне на страница без подстраници" src="libraries/inline-editing/images/addpage-nosub.png" border="0" width="115" height="15"/></a><a  href="javascript:revertAddLink();"><img title="Пренареждане" src="libraries/inline-editing/images/cancel.png" border="0" width="58" height="15"/></a></div>');
}

function revertAddLink() {
    $('#addMenuLink').removeClass('clickedAddBtn').html('<div class="addLink">' + $('.addLink').html() + '</div>');
}

function addPage(type) {
    var data = new Object();
    var page;
    if(type == '1')
        page = 'list';
    else
        page = 'content';
    data.subpage = 'menu_0';
    data.type = type;
    var dataString = JSON.stringify(data);
	$.post("admin/add.php", {data: dataString}, function(response){
		var obj = jQuery.parseJSON(response);
        new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
        if(obj.flag == "success")
            $('#sortableMenu').append('<li id="recordsArray_' + obj.insertID + '"><ul><li class="dragMove"><img title="Пренареждане" src="libraries/inline-editing/images/move.png" border="0" width="15" height="15"></li><li><a href="?page=' + page + '&id=' + obj.insertID + '">' + obj.newItemLinkName + '</a></li></ul></li>');
    });	
    revertAddLink();
}
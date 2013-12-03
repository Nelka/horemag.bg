$(document).ready(function () {

    $(".loginBtn").live("click", Login);

    function Login() {
        var data = new Object();
        data.user = $(".inputUsername").val();
        data.pass = $.md5($(".inputPassword").val());
        var dataString = JSON.stringify(data);
        $.post("admin/login.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            if(obj.flag != "success")
                new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            else 
                location.reload(true);
        });
    }
    
    
    $(".logoutBtn").live("click", Logout);

    function Logout() {
        var data = new Object();
        data.flaglogout = "yes";
        var dataString = JSON.stringify(data);
        $.post("admin/logout.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            if(obj.flag != "success")
                new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
            else 
                location.reload(true);
        });
    }
    
    
    $(".changePassBtn").live("click", ChangePass);

    function ChangePass() {
        var data = new Object();
        data.pass = $(".inputChangePassword").val();
        var dataString = JSON.stringify(data);
        $.post("admin/change-pass.php", {data: dataString}, function (response) {
            var obj = jQuery.parseJSON(response);
            new Messi(obj.message, {title: obj.flag_name, titleClass: obj.flag, buttons: [{id: 0, label: 'ОК', val: 'X'}], modal: true});
        });
    }
    
});
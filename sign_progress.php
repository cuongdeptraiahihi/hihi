<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
    require_once("access_hocsinh.php");
//	require_once("model/is_mobile.php");
    if(isset($_SESSION["ID_HS"])) {
        $hsID = $_SESSION["ID_HS"];
    } else {
        $hsID = 0;
    }

    $login_times=get_login_times($hsID);
    if($login_times >= 4) {
        header("location:https://localhost/www/TDUONG/mon/");
        exit();
    }

    $result0=get_hs_short_detail2($hsID);
    $data0=mysqli_fetch_assoc($result0);
	
	$mau="#FFF";
    $me=md5("1241996");

    $thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SETUP</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/tongquan.css"/> 
        
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />  
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />    
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->     
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/jquery-ui.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
            #COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}.li-content ul li {padding:5px 10px 5px 10px;}.li-content ul li > div {display:inline-block;width:40%;}.li-content ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}.li-content ul li > div#main-star i {font-size:6em;color:yellow;}.li-content ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}.li-content ul li > div p {color:#FFF;font-size:14px;line-height: 22px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
            .button {font-family:"FontLight";letter-spacing:0.5px;background:#4c69ba;border:none;border-radius:6px;padding:10px 15px 10px 15px;color:#FFF;font-size:18px;font-weight:600;cursor:pointer;outline:none;}
            .input {padding: 4%;}
            .li-content {width: 49%;float: none;margin: auto;}
            #fb-img {position: absolute;z-index: 9;top: 10%;left: 2%;border-radius: 1000px;width: auto;height: 35px;display: none;}
            #add-truong-suggest {position: absolute;z-index: 99;top: 40px;left: 0;display: none;background: rgba(0,0,0,0.7);}
            /*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="https://sdk.accountkit.com/vi_VN/sdk.js"></script>
        <script src="https://localhost/www/TDUONG/js/jquery-ui.js"></script>
        <script src="https://localhost/www/TDUONG/js/jquery-ui.multidatespicker.js"></script>
        <script type="text/javascript">
            AccountKit_OnInteractive = function(){
                AccountKit.init(
                    {
                        appId:967757889982912,
                        state:"abcd",
                        debug: true,
                        version:"v1.0"
                    }
                );
            };
        </script>
        <script>
			$(document).ready(function() {
//			    if($(".fb-page").height() == 0) {
//			        $(".fb-page").closest("div").append("<p id='not-facebook'><i class='fa fa-exclamation'></i> Bạn chưa đăng nhập Facebook</p>")
//                }
                <?php
                    $query="SELECT ID_HS FROM hocsinh WHERE password='".md5($data0["cmt"])."'";
                    $result=mysqli_query($db,$query);
                    if(mysqli_num_rows($result) == 0) {
                ?>
                    $("#chart-li0").hide();
                    $("#chart-li1").show();
                <?php } ?>
                <?php if($login_times >= 1) { ?>
                    $("#button-next").attr("data-check", 1);
                    $("#chart-li0").hide();
                    $("#chart-li1").hide();
                    $("#chart-li2").show();
                <?php } ?>
                $("#add-email").typeWatch({  
                    captureLength: 5,
                    callback: function (value) {
                        if(value!= "" && value.search("@gmail.com") > 0) {
                            $.ajax({
                                async: true,
                                data: "email=" + value,
                                type: "post",
                                url: "https://localhost/www/TDUONG/xuly-mon/",
                                success: function(result) {
                                    console.log(result);
                                }
                            });
                        }
                    }
                });
                $("#add-birth").datepicker({
                    dateFormat: 'dd/mm/yy',
                    yearRange: '1900:<?php echo date("Y"); ?>',
                    changeMonth: true,
                    changeYear: true,
                    defaultDate: new Date('<?php echo $data0["birth"]; ?>')
                });
                $("#add-sdt").typeWatch({
                    captureLength: 5,
                    callback: function (value) {
                        if(value != "" && $.isNumeric(value)) {
                            $.ajax({
                                async: false,
                                data: "sdt_check=" + value,
                                type: "post",
                                url: "https://localhost/www/TDUONG/xuly-mon/",
                                success: function(result) {
                                    $("#button-next").attr("data-check", result);
                                }
                            });
                        }
                    }
                });
                $("#add-truong").typeWatch({
                    captureLength: 3,
                    callback: function (value) {
                        if(value!="" && value!=" " && value!="%" && value!="_") {
                            $("#add-truong-suggest").fadeOut("fast");
                            $("#add-truong-suggest").html("<option value='0'>Đang tìm...</option>").fadeIn("fast");
                            $.ajax({
                                async: true,
                                data: "search_truong=" + value.trim(),
                                type: "post",
                                url: "https://localhost/www/TDUONG/xuly-mon/",
                                success: function(result) {
                                    $("#add-truong-suggest").html(result).fadeIn("fast");
                                }
                            });
                        } else {
                            $("#add-truong-suggest").fadeOut("fast");
                        }
                    }
                });
                $("#add-truong-suggest").change(function() {
                    $("#add-truong").attr("data-truong", $(this).find("option:selected").val()).val($(this).find("option:selected").html());
                    $(this).fadeOut("fast");
                });
                var time_anim = 300;
                $("#button-next0").click(function () {
                    var pass_old = $("#add-pass").val().trim();
                    var pass_new = $("#add-pass-new").val().trim();
                    var pass_again = $("#add-pass-again").val().trim();
                    if(pass_old != "" && pass_new != "" && pass_again != "" && pass_old != pass_new) {
                        if(pass_new == pass_again) {
                            $("#MAIN").css("opacity", "0.3");
                            $("#popup-loading").fadeIn("fast");
                            $.ajax({
                                async: true,
                                data: "pass_old=" + pass_old + "&pass_new=" + pass_new,
                                type: "post",
                                url: "https://localhost/www/TDUONG/xuly-mon/",
                                success: function (result) {
                                    $(".popup").fadeOut("fast");
                                    $("#MAIN").css("opacity", "1");
                                    if(result == "none") {
                                        alert("Mật khẩu cũ không chính xác!");
                                    } else if(result == "fuck") {
                                        alert("Mật khẩu phải có ít nhất 6 kí tự, bắt đầu bằng chữ cái!");
                                    } else {
                                        console.log("Mật khẩu mới của bạn là: " + result);
                                        $("#add-pass, #add-pass-new, #add-pass-again").val("");
                                        $("#chart-li0").fadeOut(time_anim);
                                        setTimeout(function () {
                                            $("#chart-li1").fadeIn(time_anim);
                                        }, time_anim);
                                    }
                                }
                            });
                        } else {
                            alert("Nhập lại mật khẩu mới chưa chính xác!");
                        }
                    } else if(pass_old == pass_new && pass_new == pass_again && pass_again == "") {
                        $("#MAIN").css("opacity", "0.3");
                        $("#popup-loading").fadeIn("fast");
                        $.ajax({
                            async: true,
                            data: "pass_check=1",
                            type: "post",
                            url: "https://localhost/www/TDUONG/xuly-mon/",
                            success: function (result) {
                                $(".popup").fadeOut("fast");
                                $("#MAIN").css("opacity", "1");
                                if(result == "ok") {
                                    alert("Bạn đang dùng mật khẩu mặc định là mã số học sinh! Hãy đổi mật khẩu khác!");
                                } else {
                                    $("#add-pass, #add-pass-new, #add-pass-again").val("");
                                    $("#chart-li0").fadeOut(time_anim);
                                    setTimeout(function () {
                                        $("#chart-li1").fadeIn(time_anim);
                                    }, time_anim);
                                }
                            }
                        });
                    } else {
                        alert("Dữ liệu chưa chính xác!");
                    }
                });
                $("#button-back").click(function () {
                    $("#chart-li1").fadeOut(time_anim);
                    setTimeout(function() {
                        $("#chart-li0").fadeIn(time_anim);
                    },time_anim);
                });
                $("#button-next").click(function () {
                    var name = $("#add-name").val().trim();
                    var gender = $("#add-gender").find("option:selected").val();
                    var birth = $("#add-birth").val().trim();
                    var sdt = $("#add-sdt").val().trim();
                    var name_bo = $("#add-name-bo").val().trim();
                    var sdt_bo = $("#add-sdt-bo").val().trim();
                    var name_me = $("#add-name-me").val().trim();
                    var sdt_me = $("#add-sdt-me").val().trim();
                    var truong = $("#add-truong").attr("data-truong");
                    if(name != "" && (name_bo != "" || name_me != "") && (gender == 1 || gender == 0) && birth != "" && $.isNumeric(sdt) && ($.isNumeric(sdt_bo) || $.isNumeric(sdt_me)) && (sdt_bo != "" || sdt_me != "") && $.isNumeric(truong) && truong>0) {
                        $("#MAIN").css("opacity", "0.3");
                        $("#popup-loading").fadeIn("fast");
                        if($(this).attr("data-check") == 1) {
                            update_step1("X", name, gender, birth, sdt, name_bo, sdt_bo, name_me, sdt_me, truong);
                        } else if($(this).attr("data-check") == 0) {
                            phone_btn_onclick(sdt);
                        } else {
                            alert("Dữ liệu không chính xác!");
                            $(".popup").fadeOut("fast");
                            $("#MAIN").css("opacity", "1");
                        }
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("#button-back2").click(function () {
                    $("#chart-li2").fadeOut(time_anim);
                    setTimeout(function() {
                        $("#chart-li1").fadeIn(time_anim);
                    },time_anim);
                });
                $("#button-next2").click(function () {
                    var fbid = $("#facebook-id").val();
                    var fbname = $("#facebook-name").val();
//                    $("#chart-li2").fadeOut(time_anim);
//                    setTimeout(function () {
//                        $("#chart-li3").fadeIn(time_anim);
//                    }, time_anim);
                    if(fbid != "" && fbname != "") {
                        $.ajax({
                            async: true,
                            data: "fbid=" + fbid + "&fbname=" + fbname,
                            type: "post",
                            url: "https://localhost/www/TDUONG/xuly-mon/",
                            success: function(result) {
                                if(result == "ok") {
                                    $("#chart-li2").fadeOut(time_anim);
                                    setTimeout(function () {
                                        $("#chart-li3").fadeIn(time_anim);
                                    }, time_anim);
                                } else if(result == "no-mess") {
                                    alert("Bạn chưa inbox fanpage!");
                                } else if(result == "back") {
                                    alert("Bạn cần hoàn thành các bước trước đó!");
                                } else {
                                    alert("Tài khoản Facebook này đã được kết nối đến mã số khác: " + result);
                                }
                            }
                        });
                    } else {
                        alert("Hãy kết nối với Facebook!");
                    }
                });
                $("#button-back3").click(function () {
                    $("#chart-li3").fadeOut(time_anim);
                    setTimeout(function() {
                        $("#chart-li2").fadeIn(time_anim);
                    },time_anim);
                });
                $("#button-next3").click(function () {
                    $("#chart-li3").fadeOut(time_anim);
                    setTimeout(function () {
                        $("#chart-li4").fadeIn(time_anim);
                    }, time_anim);
                });
                $("#button-back4").click(function () {
                    $("#chart-li4").fadeOut(time_anim);
                    setTimeout(function() {
                        $("#chart-li3").fadeIn(time_anim);
                    },time_anim);
                });
                $("#button-next4").click(function () {
                    $("#MAIN").css("opacity", "0.3");
                    $("#popup-loading").fadeIn("fast");
                    var ajax_data = "[";
                    $("select.add-ca").each(function(index, element) {
                        var data = $(element).find("option:selected").val();
                        if(data != "") {
                            ajax_data += '{"data":"'+data+'"},';
                        }
                    });
                    ajax_data = ajax_data.replace(/,*$/,"");
                    ajax_data += "]";
                    if(ajax_data != "[]") {
                        $.ajax({
                            async: true,
                            data: "step_ca=" + ajax_data,
                            type: "post",
                            url: "https://localhost/www/TDUONG/xuly-mon/",
                            success: function(result) {
                                $(".popup").fadeOut("fast");
                                $("#MAIN").css("opacity", "1");
                                if(result == "ok") {
                                    $("#chart-li4").fadeOut(time_anim);
                                    $("#popup-tb").fadeIn("fast");
                                    $("#popup-tb p.title").html("Bạn đã cài đặt tài khoản thành công!");
                                    setTimeout(function () {
                                        $("#chart-li1, #chart-li2, #chart-li3, #chart-li4").remove();
                                    }, time_anim);
                                } else if(result == "back") {
                                    alert("Bạn cần hoàn thành các bước trước đó!");
                                } else {
                                    alert(result);
                                }
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                $("#btn-done").click(function () {
                    window.location.href="https://localhost/www/TDUONG/mon/";
                });
                function phone_btn_onclick(phone) {
                    try {
//                        AccountKit.login('PHONE', {"phoneNumber": "" + phone + ""}, // will use default values if this is not specified
//                            loginCallback);
                        loginCallback("PARTIALLY_AUTHENTICATED");
                    } catch(err) {
                        location.reload();
                    }
                }
                // login callback
                function loginCallback(response) {
                    console.log(response);
                    if (response == "PARTIALLY_AUTHENTICATED") {
//                        var code = response.code;
                        var code = "cc";
                        var name = $("#add-name").val().trim();
                        var gender = $("#add-gender").find("option:selected").val();
                        var birth = $("#add-birth").val().trim();
                        var sdt = $("#add-sdt").val().trim();
                        var name_bo = $("#add-name-bo").val().trim();
                        var sdt_bo = $("#add-sdt-bo").val().trim();
                        var name_me = $("#add-name-me").val().trim();
                        var sdt_me = $("#add-sdt-me").val().trim();
                        var truong = $("#add-truong").attr("data-truong");
                        if(code != "" && name != "" && (name_bo != "" || name_me != "") && (gender == 1 || gender == 0) && birth != "" && $.isNumeric(sdt) && ($.isNumeric(sdt_bo) || $.isNumeric(sdt_me)) && $.isNumeric(truong) && truong>0) {
                            update_step1(code, name, gender, birth, sdt, name_bo, sdt_bo, name_me, sdt_me, truong);
                        } else {
                            alert("Không thể xác thực!");
                            $(".popup").fadeOut("fast");
                            $("#MAIN").css("opacity", "1");
                        }
                    } else if (response.status === "NOT_AUTHENTICATED") {
                        console.log("Authentication failure");
                        alert("Xác thực thất bại!");
                        $(".popup").fadeOut("fast");
                        $("#MAIN").css("opacity", "1");
                    } else if (response.status === "BAD_PARAMS") {
                        alert("Có lỗi xảy ra!");
                        $(".popup").fadeOut("fast");
                        $("#MAIN").css("opacity", "1");
                    } else {
                        alert("Hãy load lại trang!!!");
                        $(".popup").fadeOut("fast");
                        $("#MAIN").css("opacity", "1");
                    }
                }
                function update_step1(code, name, gender, birth, sdt, name_bo, sdt_bo, name_me, sdt_me, truong) {
                    $.ajax({
                        async: true,
                        data: "step_code=" + code + "&step_name=" + name + "&step_gender=" + gender + "&step_birth=" + birth + "&step_sdt=" + sdt + "&step_truong=" + truong + "&step_name_bo=" + name_bo + "&step_sdt_bo=" + sdt_bo + "&step_name_me=" + name_me + "&step_sdt_me=" + sdt_me,
                        type: "post",
                        url: "https://localhost/www/TDUONG/xuly-mon/",
                        success: function(result) {
                            if(result == "ok") {
                                $("#button-next").attr("data-check", 1);
                                $("#chart-li1").fadeOut(time_anim);
                                setTimeout(function() {
                                    $("#chart-li2").fadeIn(time_anim);
                                },time_anim);
                            } else if(result == "unknow") {
                                alert("Xảy ra lỗi không mong muốn!");
                            } else if(result == "no") {
                                alert("Sô điện thoại được xác thực không khớp với số điện thoại của bạn!");
                            } else if(result == "lol") {
                                alert("Bạn cần phải xác thực SĐT!");
                                location.reload();
                            } else {
                                alert(result);
                            }
                            $(".popup").fadeOut("fast");
                            $("#MAIN").css("opacity", "1");
                        }
                    });
                }
                $("#fb-login-box").click(function () {
                    if($(this).hasClass("fb-login-new")) {
                        $("#MAIN").css("opacity", "0.3");
                        $("#popup-loading").fadeIn("fast");
                        FB.login(function(response){
                            if (response.status === 'connected') {
                                doneConnect(response.authResponse.userID);
                            } else {
                                alert("Đăng nhập thất bại!");
                            }
                        }, {scope: 'public_profile,email'});
                    }
                });
                window.fbAsyncInit = function() {
                    FB.init({
                        appId      : '967757889982912',
                        cookie     : true,
                        xfbml      : true,
                        version    : 'v2.8'
                    });
                    FB.AppEvents.logPageView();

                    checkLoginState();
                };
                function statusChangeCallback(response) {
                    console.log('statusChangeCallback');
                    console.log(response);
                    if (response.status === 'connected') {
                        doneConnect(response.authResponse.userID);
                    } else {
                        $("#fb-login-box").addClass("fb-login-new").fadeIn("fast");
                    }
                }

                function checkLoginState() {
                    FB.getLoginStatus(function(response) {
                        statusChangeCallback(response);
                    });
                }

                function doneConnect(id) {
                    $("#fb-login-box").addClass("fb-login-old").fadeIn("fast");
                    FB.api('/me', function(response) {
                        console.log(response);
                        $("#fb-login-box").val(response.name);
                        $(".popup").fadeOut("fast");
                        $("#MAIN").css("opacity", "1");
                        $("#facebook-id").val(response.id);
                        $("#facebook-name").val(response.name);
//                        $.ajax({
//                            async: true,
//                            data: "encode=" + response.id,
//                            type: "post",
//                            url: "https://localhost/www/TDUONG/xuly-mon/",
//                            success: function(result) {
//                                $(".popup").fadeOut("fast");
//                                $("#MAIN").css("opacity", "1");
//                                $("#facebook-id").val(result);
//                                $("#facebook-name").val(response.name);
//                            }
//                        });
                    });
                    FB.api('/' + id + '/picture?type=large', function(response) {
                        $("#fb-img").attr("src", response.data.url).fadeIn("fast");
                    });
                }
			});
		</script>
       
	</head>

    <body>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=1298976226832260";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    
    	<div class="popup animated bounceIn" id="popup-tb">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
            	<p class="title"></p>
           		<div>
                    <button class="submit2" id="btn-done"><i class="fa fa-check"></i></button>
                </div>
            </div>
        </div>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>                         
        
      	<div id="BODY">
            
            <div id="MAIN">

                <div class="main-div animated bounceInUp">
                    <ul>
                        <li id="chart-li0"  class="li-content">
                            <div class="main-2 back"><h3>Bước 0: Kiểm tra mật khẩu</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div style="width: 100%;"><p>Nếu bạn đã đổi mật khẩu, bạn có thể bỏ qua!</p></div>
                                </li>
                                <li>
                                    <div><p>Mật khẩu *</p></div>
                                    <div style="width:58%;"><input type="password" id="add-pass" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>Mật khẩu mới *</p></div>
                                    <div style="width:58%;"><input type="password" id="add-pass-new" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>Nhập lại mật khẩu mới *</p></div>
                                    <div style="width:58%;"><input type="password" id="add-pass-again" class="input" /></div>
                                </li>
                                <li>
                                    <div></div>
                                    <div style="width:58%;text-align: right;"><button type="button" class="submit" id="button-next0">Thông tin <i class="fa fa-angle-right"></i></button></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li1"  class="li-content" style="display: none;">
                            <div class="main-2 back"><h3>Bước 1: Kiểm tra thông tin</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div style="width: 100%;"><p>1. Thông tin học sinh</p></div>
                                </li>
                                <li>
                                    <div><p>Họ và tên *</p></div>
                                    <div style="width:58%;"><input type="text" id="add-name" value="<?php echo $data0["fullname"] ;?>" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>Giới tính *</p></div>
                                    <div style="width:58%;">
                                        <select class="input" style="width:93%;" id="add-gender">
                                            <option value="1" <?php if($data0["gender"]==1){echo"selected='selected'";} ?>>Nam</option>
                                            <option value="0" <?php if($data0["gender"]==0){echo"selected='selected'";} ?>>Nữ</option>
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div><p>Ngày sinh *</p></div>
                                    <div style="width:58%;"><input id="add-birth" type="text" value="<?php echo format_dateup($data0["birth"]);?>" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>SĐT cá nhân *</p></div>
                                    <div style="width:58%;"><input type="text" id="add-sdt" value="<?php echo $data0["sdt"] ;?>" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>Trường *</p></div>
                                    <div style="width:58%;position: relative;">
                                        <input type="text" id="add-truong" data-truong="<?php echo $data0["truong"]; ?>" value="<?php echo get_truong_hs($data0["truong"]); ?>" class="input" />
                                        <select class="input" style="width: 93%;" id="add-truong-suggest">
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div><p>Gmail *</p></div>
                                    <div style="width:58%;"><input type="text" id="add-email" value="<?php echo $data0["email"] ;?>" placeholder="Xem video bài giảng" class="input" /></div>
                                </li>
                                <li style="margin-top: 10px;">
                                    <div style="width: 100%;"><p>2. Thông tin phụ huynh</p></div>
                                </li>
                                <?php
                                    $name_bo=$name_me="";
                                    $result1=get_phuhuynh_hs($hsID,"X");
                                    while($data1=mysqli_fetch_assoc($result1)) {
                                        if($data1["gender"]==1) {
                                            $name_bo=$data1["name"];
                                        } else if($data1["gender"]==0) {
                                            $name_me=$data1["name"];
                                        }
                                    }
                                ?>
                                <li>
                                    <div><p>Họ tên Bố</p></div>
                                    <div style="width:58%;"><input type="text" id="add-name-bo" value="<?php echo $name_bo; ?>" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>SĐT bố *</p></div>
                                    <div style="width:58%;"><input type="text" id="add-sdt-bo" value="<?php echo $data0["sdt_bo"];?>" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>Họ tên Mẹ</p></div>
                                    <div style="width:58%;"><input type="text" id="add-name-me" value="<?php echo $name_me; ?>" class="input" /></div>
                                </li>
                                <li>
                                    <div><p>SĐT mẹ *</p></div>
                                    <div style="width:58%;"><input type="text" id="add-sdt-me" value="<?php echo $data0["sdt_me"]; ?>" class="input" /></div>
                                </li>

                                <li>
                                    <div><button class="submit" id="button-back"><i class="fa fa-angle-left"></i> Quay lại</button></div>
                                    <div style="width:58%;text-align: right;"><button type="button" class="submit" id="button-next" data-check="0">Kết nối FB <i class="fa fa-angle-right"></i></button></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li2"  class="li-content" style="display: none;">
                            <div class="main-2 back"><h3>Bước 2: Kết nối Facebook</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div><p>1. Kết nối Facebook</p></div>
                                    <div style="width:58%;position: relative;"><img id="fb-img" src='' /><input type="button" id="fb-login-box" class="button" value="Đăng nhập với Facebook" style="width:100%;display: none;" /><input type="hidden" name="facebook-id" id="facebook-id" style="display: none;" /><input type="hidden" name="facebook-name" id="facebook-name" style="display: none;" /></div>
                                </li>
                                <li>
                                    <div style="width:100%;">
                                        <p>2. Bạn hãy inbox <a href='http://m.me/Bgo.edu.vn' target='_blank' style="font-weight: 600;">Fanpage</a> với nội dung là mã số và mật khẩu của bạn: <strong><?php echo $data0["cmt"]."-mat_khau"; ?></strong>. Ví dụ: <?php echo $data0["cmt"]; ?>-cuncon</p>
                                        <div style="text-align: center;"><div style="margin-top: 10px;" class="fb-page" data-href="https://www.facebook.com/Bgo.edu.vn/" data-height="250" data-tabs="messages" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"></div></div>
                                    </div>
                                </li>
                                <li>
                                    <div style="width: 100%;"><p>3. Sau khi nhận được tin nhắn thành công, bạn chuyển sang bước tiếp theo!</p></div>
                                </li>
                                <li>
                                    <div><button class="submit" id="button-back2"><i class="fa fa-angle-left"></i> Quay lại</button></div>
                                    <div style="width:58%;text-align: right;"><button type="button" class="submit" id="button-next2">Tham gia Group lớp <i class="fa fa-angle-right"></i></button></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li3"  class="li-content" style="display: none;">
                            <div class="main-2 back"><h3>Bước 3: Tham gia Group lớp</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div style="width:100%;">
                                        <p>1. Truy cập link group lớp tương ứng dưới đây và chọn yêu cầu <strong>Tham gia</strong></p>
                                        <?php
                                            $result_mon=get_all_mon_hocsinh($hsID);
                                            for($i = 0; $i < count($result_mon); $i++) {
                                                $fblink=get_facebook($result_mon[$i]["lmID"]);
                                                echo"<p><a href='$fblink' target='_blank' style='font-weight: 600;color: #FFF;'><i class='fa fa-angle-right'></i> ".$result_mon[$i]["name"].": $fblink</a></p>";
                                            }
                                        ?>
                                        <div style="text-align: center;"><img src="https://localhost/www/TDUONG/images/cc.png" style="width: 100%;margin: 10px auto;" /></div>
                                    </div>
                                </li>
                                <li>
                                    <div style="width: 100%;"><p>2. Các anh chị trợ giảng sẽ xét duyệt bạn vào nhóm nếu bạn đã làm đủ các bước!</p></div>
                                </li>
                                <li>
                                    <div><button class="submit" id="button-back3"><i class="fa fa-angle-left"></i> Quay lại</button></div>
                                    <div style="width:58%;text-align: right;"><button type="button" class="submit" id="button-next3">Lịch học <i class="fa fa-angle-right"></i></button></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li4"  class="li-content" style="display: none;">
                            <div class="main-2 back"><h3>Bước 4: Lịch học, lịch thi</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div style="width:100%;"><p>Hãy kiểm tra lịch học của bạn!</p></div>
                                </li>
                                <?php
                                    for($i = 0; $i < count($result_mon); $i++) {
                                        $result=get_all_cum($result_mon[$i]["lmID"], $result_mon[$i]["monID"]);
                                        while($data=mysqli_fetch_assoc($result)) {
                                            echo"<li>
                                                <div><p>$data[name]</p></div>
                                                <div style='width: 58%;'>
                                                    <select class='input add-ca' style='width: 95%;'>";
                                                        $query2="SELECT c.ID_CA,c.thu,c.cum,g.gio,o.ID_STT AS codinh FROM cahoc AS c 
                                                        INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='".$result_mon[$i]["lmID"]."' AND g.ID_MON='".$result_mon[$i]["monID"]."'
                                                        LEFT JOIN ca_codinh AS o ON o.ID_CA=c.ID_CA AND o.ID_HS='$hsID' AND o.cum='$data[ID_CUM]'
                                                        WHERE c.cum='$data[ID_CUM]'
                                                        ORDER BY c.thu ASC,g.buoi ASC,g.thutu ASC";
                                                        $result2=mysqli_query($db,$query2);
                                                        while($data2=mysqli_fetch_assoc($result2)) {
                                                            echo"<option value='".encode_data($data2["ID_CA"]."-".$data2["cum"]."-".$result_mon[$i]["lmID"], $me)."' "; if(isset($data2["codinh"])){echo"selected='selected' style='background:#000;'";}echo">".$thu_string[$data2["thu"]-1].", $data2[gio]</option>";
                                                        }
                                                    echo"</select>
                                                </div>
                                            </li>";
                                        }
                                        echo"<li>
                                            <div><p>Ca thi cuối tuần</p></div>
                                            <div style='width: 58%;'>
                                                <select class='input add-ca' style='width: 95%;'>";
                                                    $dem=1;
                                                    $query="SELECT c.ID_CA,c.thu,c.cum,g.gio,o.ID_STT AS codinh FROM cahoc AS c 
                                                    INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='0' AND g.ID_MON='".$result_mon[$i]["monID"]."'
                                                    LEFT JOIN ca_codinh AS o ON o.ID_CA=c.ID_CA AND o.ID_HS='$hsID' AND o.cum=c.cum
                                                    ORDER BY g.buoi ASC, g.thutu ASC";
                                                    $result=mysqli_query($db,$query);
                                                    while($data=mysqli_fetch_assoc($result)) {
                                                        echo"<option value='".encode_data($data["ID_CA"]."-".$data["cum"]."-".$result_mon[$i]["lmID"], $me)."'"; if(isset($data["codinh"])){echo"selected='selected' style='background:#000;'";}echo">Ca $dem, ".$thu_string[$data["thu"]-1].", $data[gio]</option>";
                                                        $dem++;
                                                    }
                                            echo"</select>
                                            </div>
                                        </li>";
                                    }
                                ?>
                                <li>
                                    <div style="width: 100%;"><p>Bạn có thể thay đổi lịch học trong Menu > Đổi ca</p></div>
                                </li>
                                <li>
                                    <div><button class="submit" id="button-back4"><i class="fa fa-angle-left"></i> Quay lại</button></div>
                                    <div style="width:58%;text-align: right;"><button type="button" class="submit" id="button-next4">Hoàn thành <i class="fa fa-check"></i></button></div>
                                </li>
                            </ul>
                        </li>
                        <li style="display: none;"></li>
                    </ul>
                    <div class="clear"></div>
                </div>
                <div id="SMS">
                    <a href='http://m.me/Bgo.edu.vn' target="_blank" class='sms-new'><i class='fa fa-commenting'></i><span>Hỗ trợ</span></a>
                </div>
            </div>
        </div>
         <?php require_once("include/MENUshort.php"); ?>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>
<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	$mau="#FFF";
	
	if($_SESSION["show_tien"]==0) {
		header("location:https://localhost/www/TDUONG/tong-quan/");
		exit();
	}
	
	$result0=get_hs_short_detail($hsID, $lmID);
	$data0=mysqli_fetch_assoc($result0);

    if(isset($_POST["ak_code"]) && isset($_POST["maso"]) && isset($_POST["tien"]) && !empty($_POST["maso"]) && !empty($_POST["tien"])) {
        $error = false;
        $ak_code = $_POST["ak_code"];
//        $checkAK = validAccountKit($hsID,$data0["sdt"],$ak_code);
        $checkAK = "ok";
        if($checkAK == "unknow") {
            $_SESSION["error_msg"]="Có lỗi khi xác thực!";
            $error = true;
        } else if($checkAK == "no") {
            $_SESSION["error_msg"]="Số điện thoại không khớp với số điện thoại đã đăng ký!";
            $error = true;
        } else {
            $maso = trim(addslashes($_POST["maso"]));
            $tien = str_replace(array(".", "d", "đ", "D", "Đ"), "", trim(addslashes($_POST["tien"])));
            if (valid_maso($maso) && is_numeric($tien) && $tien > 0) {
                if ($data0["taikhoan"] >= $tien) {
                    if ($tien % 10000 == 0 && $tien <= 50000) {
                        $id = get_hs_id($maso);
                        if ($id != 0 && $id != $hsID) {
                            tru_tien_hs($hsID, $tien, "Chuyển tiên cho mã số $maso", "chuyen-tien", $id);
                            cong_tien_hs($id, $tien, "Nhận tiền từ mã số $data0[cmt]", "nhan-tien", $hsID);
                            $_SESSION["error_msg"] = "Đã chuyển thành công!";
                            $error = false;
                        } else {
                            $_SESSION["error_msg"] = "Không tồn tại học sinh này!";
                            $error = true;
                        }
                    } else {
                        $_SESSION["error_msg"] = "Số tiền phải là bội của 10.000đ và bé hơn 50.000đ";
                        $error = true;
                    }
                } else {
                    $_SESSION["error_msg"] = "Bạn không đủ tiền trong tài khoản!";
                    $error = true;
                }
            } else {
                $_SESSION["error_msg"] = "Dữ liệu không chính xác!";
                $error = true;
            }
        }
        header("location:https://localhost/www/TDUONG/tai-khoan/");
        exit();
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TÀI KHOẢN</title>
        
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
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}table tr:last-child td:first-child, table tr:last-child td:last-child {border-bottom-left-radius:0;border-bottom-right-radius:0;}#MAIN .main-div #main-info #main-1-left .table-tkb tr td {border-bottom:1px solid rgba(0,0,0,0.15);padding-bottom:5px;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="https://sdk.accountkit.com/en_EN/sdk.js"></script>
        <script>
			$(document).ready(function() {
                $(".btn-exit, .popup .popup-close, .popup").click(function() {
                    $(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
                });
                $("#send-tien").click(function() {
                    var me = $(this);
                    var maso = me.closest("tr").find("td input#maso").val();
                    var tien = me.closest("tr").find("td input#tien").val();
                    if(maso != "" && tien > 0 && $.isNumeric(tien)) {
                        phone_btn_onclick();
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                AccountKit_OnInteractive = function(){
                    AccountKit.init(
                        {
                            appId:967757889982912,
                            state:"abcd",
                            version:"v1.0"
                        }
                    );
                };

                // login callback
                function loginCallback(response) {
                    console.log(response);
                    if (response == "PARTIALLY_AUTHENTICATED") {
//                        document.getElementById("ak_code").value = response.code;
//                        document.getElementById("csrf_nonce").value = response.state;
                        document.getElementById("ak_code").value = "cc";
                        document.getElementById("csrf_nonce").value = "cc";
                        document.getElementById("my_form").submit();
                    }
                    else if (response.status === "NOT_AUTHENTICATED") {
                        // handle authentication failure
                        console.log("Authentication failure");
                        alert("Xác thực thất bại!");
                    }
                    else if (response.status === "BAD_PARAMS") {
                        // handle bad parameters
                        console.log("Bad parameters");
                        alert("Đối số lỗi!");
                    }
                }
                // phone form submission handler
                function phone_btn_onclick() {
                    // you can add countryCode and phoneNumber to set values
                    try {
//                        AccountKit.login('PHONE', {"phoneNumber": "<?php //echo $data0["sdt"]; ?>//"}, // will use default values if this is not specified
//                            loginCallback);
                        loginCallback("PARTIALLY_AUTHENTICATED");
                    } catch(err) {
                        location.reload();
                    }
                }
			});
		</script>
       
	</head>

    <body>

        <?php
        if(isset($_SESSION["error_msg"]) && $_SESSION["error_msg"]) {
            echo"<div class='popup animated bounceIn' style='display:block;'>
                        <div>
                            <p class='title'>".$_SESSION["error_msg"]."</p>
                        </div>
                    </div>";
            $_SESSION["error_msg"]=NULL;
        }
        ?>

        <div class="popup animated bounceIn" id="popup-loading">
            <p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
        </div>

        <div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1>Quản lý tài khoản<br /><span>Tài khoản: <?php echo format_price(get_tien_hs($hsID));?></span></h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                        <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                    <!--<div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>-->
                </div>

                <?php if($_SESSION["test"]==0) { ?>
                    <div class="main-div animated animated2 bounceInUp">
                        <div id="main-info">
<!--                            <div class="main-1-left back" style="margin-right:2%;padding: 10px 0;width:39%;">-->
<!--                                <div>-->
<!--                                    <p class="main-title"><a href="https://localhost/www/TDUONG/xin-nghi-hoc/" style="color:#FFF;">Xin nghỉ học</a></p>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="main-1-left back" style="padding: 10px 0;width: 49%;">
                                <div>
                                    <p class="main-title">Chuyển tiền</p>
                                </div>
                                <form action="https://localhost/www/TDUONG/tai-khoan/" method="post" id="my_form">
                                    <table class="table-tkb">
                                        <tr>
                                            <td style="background: none;"><input type="text" class="input" name="maso" id="maso" placeholder="Mã số: 99-0156, 00-0005, 99-0333,..." /></td>
                                            <td style="background: none;"><input type="number" min="0" step="10000" class="input" name="tien" id="tien" placeholder="Số tiền: 50000, 10000,..." /></td>
                                            <td style="background: none;"><input type="button" id="send-tien" class="submit" value="Chuyển" /><input type="hidden" name="ak_code" id="ak_code" /><input type="hidden" name="csrf_nonce" id="csrf_nonce" /></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                <?php } ?>
                
                <div class="main-div animated bounceInUp">
                    <div id="main-info">
                    	<div class="main-1-left back" style="margin-right:2%;">
                        	<div>
                            	<p class="main-title">Lịch sử cộng tiền</p>
                            </div>
                            <table class="table-tkb">
                            	<tr style='background:<?php echo $mauall; ?>;text-transform:uppercase;'>
                                    <th colspan="2"><span>Tổng cộng</span></th>
                                    <th><span>+<?php echo format_price(get_thuong_hs($hsID)); ?></span></th>
                                </tr>
                            	<tr style='background:<?php echo $mauall; ?>;text-transform:uppercase;'>
                                    <th style="width:25%;"><span>Thời gian</span></th>
                                    <th style="width:45%;"><span>Nội dung</span></th>
                                    <th style="width:30%;"><span>Số tiền</span></th>
                                </tr>
                                <tr>
                                	<td colspan="3"><span>5 lần mới nhất</span></td>
                                </tr>
								<?php
                                    $result=get_thuong_hocsinh_short($hsID);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td><span>".format_date($data["date"])."</span></td>
                                            <td><span>$data[note]</span></td>
                                            <td><span>+".format_price($data["price"])."</span></td>
                                        </tr>";
                                    }
                                ?>
                                <tr style="background:<?php echo $mauall; ?>">
                                	<th colspan="3"><a href="https://localhost/www/TDUONG/tai-khoan-thuong/"><span>Xem tất cả</span></a></th>
                                </tr>
                          	</table>
                       	</div>
                        <div class="main-1-left back">
                            <div>
                            	<p class="main-title">Lịch sử trừ tiền</p>
                            </div>
                            <table class="table-tkb">
                            	<tr style='background:<?php echo $mauall; ?>;text-transform:uppercase;'>
                                    <th colspan="2"><span>Tổng cộng</span></th>
                                    <th><span>-<?php echo format_price(get_phat_hs($hsID)); ?></span></th>
                                </tr>
                            	<tr style='background:<?php echo $mauall; ?>;text-transform:uppercase;'>
                                    <th style="width:25%;"><span>Thời gian</span></th>
                                    <th style="width:45%;"><span>Nội dung</span></th>
                                    <th style="width:30%;"><span>Số tiền</span></th>
                                </tr>
                                <tr>
                                	<td colspan="3"><span>5 lần mới nhất</span></td>
                                </tr>
                                <?php
                                    $result=get_phat_hocsinh_short($hsID);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td><span>".format_date($data["date"])."</span></td>
                                            <td><span>$data[note]</span></td>
                                            <td><span>-".format_price($data["price"])."</span></td>
                                        </tr>";
                                    }
                                ?>
                                <tr style="background:<?php echo $mauall; ?>">
                                	<th colspan="3"><a href="https://localhost/www/TDUONG/tai-khoan-phat/"><span>Xem tất cả</span></a></th>
                                </tr>
                            </table>
                      	</div>
                    </div>
                    <div class="clear"></div>
                </div>
                     <?php require_once("include/IN.php"); ?>	          
            </div>
        	<div class="clear"></div>
        </div>
        <?php require_once("include/MENU.php"); ?>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>
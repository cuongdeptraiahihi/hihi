<?php
	ob_start();
	//session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("include/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$code=$_SESSION["code"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID, $lmID);
	$data0=mysqli_fetch_assoc($result0);

    $num_ca=array();
    $query="SELECT c.ID_CA,COUNT(h.ID_STT) AS dem FROM cahoc AS c
        INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND (g.ID_LM='$lmID' OR g.ID_LM='0') AND g.ID_MON='$monID'
        INNER JOIN ca_hientai AS h ON h.ID_CA=c.ID_CA AND h.cum=c.cum
        GROUP BY c.ID_CA";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        $num_ca[$data["ID_CA"]]=$data["dem"];
    }

    $demhs=count_hs_mon_lop($lmID);

    $ontime=check_on_catime($lmID);
//    $check_binh_voi=check_binh_voi2($hsID,$lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>ĐỔI CA</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/mobile/css/tongquan.css"/> 
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="css/materialize.min.css" />-->     
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/mobile/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}table tr:last-child td:first-child, table tr:last-child td:last-child {border-bottom-left-radius:0;border-bottom-right-radius:0;}#MAIN > .main-div .main-1-left table tr td {overflow:hidden;position:relative;}#MAIN > .main-div .main-1-left table tr td > nav {width:100%;height:100%;}#MAIN > .main-div .main-1-left table tr td > div.tab-num {position:absolute;z-index:9;right:-20px;top:-5px;background:rgba(0,0,0,0.15);width:60px;height:30px;-ms-transform: rotate(45deg);-webkit-transform: rotate(45deg); transform: rotate(45deg);}#MAIN > .main-div .main-1-left table tr td > div.tab-num span {color:#FFF;line-height:35px;font-size:12px !important;}#MAIN > .main-div .main-1-left table tr td > nav > div {}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-left {width:100%;margin-top: 10px;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-right {width:25%;margin: 5px auto 5px auto;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-left span {font-size:12px !important;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-right i {font-size:22px;cursor:pointer;color:#FFF;}
            ul.ul-ca {height: 100%;width: 100%;}ul.ul-ca li {padding: 5px;}ul.ul-ca li span {font-size:12px !important;}ul.ul-ca li span i {font-size: 22px;cursor: pointer;margin-right: 10px;}
            /*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
			<?php if($_SESSION["test"]==0 && $data0["taikhoan"]>=0) { ?>
				$(".btn_tam").click(function() {
					caID = $(this).attr("data-caID");

                    if(caID!="") {
                        $.ajax({
                            async: !1,
                            data: "caID1=" + caID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/mobile/xuly-tam/",
                            success: function (a) {
                                switch (a) {
                                    case "vang":
                                        $(this).closest(".popup").find("button.submit2").hide();
                                        $(this).closest(".popup").find("p.title").html("Đang đổi ca...");
//                                        if(confirm("Lớp vắng, bạn có thể đăng ký tạm")) {
                                            $("#popup-loading").fadeIn("fast");
                                            $("#BODY").css("opacity", "0.1");
                                            $.ajax({
                                                async: !1,
                                                data: "caID=" + caID + "&check=" + a,
                                                type: "post",
                                                url: "http://localhost/www/TDUONG/mobile/xuly-tam/",
                                                success: function (a) {
                                                    location.reload()
                                                }
                                            });
//                                        } else {
//                                            location.reload();
//                                        }
                                        break;
                                    case "quatai":
                                        alert("Lớp đã quá tải, bạn không thể đăng ký tạm!");
//                                        location.reload();
                                        break;
//                                        if(confirm("Lớp đang quá tải<?php //if ($ontime || $check_binh_voi) {echo ", bạn vẫn có thể đăng ký!";} else {echo ", nếu đăng ký tạm bạn sẽ bị trừ 5k!";} ?>//")) {
//                                            $("#popup-loading").fadeIn("fast");
//                                            $("#BODY").css("opacity", "0.1");
//                                            $.ajax({
//                                                async: !1,
//                                                data: "caID=" + caID + "&check=" + a,
//                                                type: "post",
//                                                url: "http://localhost/www/TDUONG/mobile/xuly-tam/",
//                                                success: function (a) {
//                                                    location.reload();
//                                                }
//                                            });
//                                        } else {
//                                            location.reload();
//                                        }
//                                        break;
                                    case "max":
                                        alert("Lớp không còn sức chứa, bạn không thể đăng ký tạm!");
//                                        location.reload();
                                        break;
                                }
                                $(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
                            }
                        });
                    }
				}), $(".btn_codinh").click(function() {
					caID = $(this).attr("data-caID");
                    if(caID!="") {
                        $.ajax({
                            async: !1,
                            data: "caID1=" + caID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/mobile/xuly-dkcodinh/",
                            success: function (a) {
                                switch (a) {
                                    case "vang":
                                        $(this).closest(".popup").find("button.submit2").hide();
                                        $(this).closest(".popup").find("p.title").html("Đang đổi ca...");
//                                        if(confirm("Lớp vắng, bạn có thể chuyển bình thường<?php //if (!$ontime) {echo " và được cộng 5k!";} ?>//")) {
                                            $("#popup-loading").fadeIn("fast");
                                            $("#BODY").css("opacity", "0.1");
                                            $.ajax({
                                                async: !1,
                                                data: "caID=" + caID + "&check=" + a,
                                                type: "post",
                                                url: "http://localhost/www/TDUONG/mobile/xuly-dkcodinh/",
                                                success: function (a) {
                                                    location.reload();
                                                }
                                            });
//                                        } else {
//                                            location.reload();
//                                        }
                                        break;
                                    case "quatai":
                                        alert("Lớp đã quá tải, bạn không thể đăng ký tạm!");
//                                        location.reload();
                                        break;
//                                        if(confirm("Lớp đang quá tải<?php //if ($ontime || $check_binh_voi) {echo ", bạn vẫn có thể đăng ký!";} else {echo ", nếu chuyển bạn sẽ bị trừ 30k!";} ?>//")) {
//                                            $("#popup-loading").fadeIn("fast");
//                                            $("#BODY").css("opacity", "0.1");
//                                            $.ajax({
//                                                async: !1,
//                                                data: "caID=" + caID + "&check=" + a,
//                                                type: "post",
//                                                url: "http://localhost/www/TDUONG/mobile/xuly-dkcodinh/",
//                                                success: function (a) {
//                                                    location.reload();
//                                                }
//                                            });
//                                        } else {
//                                            location.reload();
//                                        }
//                                        break;
                                    case "max":
                                        alert("Lớp không còn sức chứa, bạn không thể chuyển!");
//                                        location.reload();
                                        break;
                                }
                                $(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
                            }
                        });
                    }
				}), $(".btn_back").click(function() {
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.1"), caID = $(this).attr("data-caID");
                    $(this).closest(".popup").find("button.submit2").hide();
                    $(this).closest(".popup").find("p.title").html("Đang đổi ca...");
                    if(caID!="") {
                        $.ajax({
                            async: !1,
                            data: "caID2=" + caID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/mobile/xuly-dkcodinh/",
                            success: function (a) {
                                location.reload();
                            }
                        });
                    }
				}), $(".popup .popup-close").click(function() {
					$(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
				}), $("#MAIN .main-div #main-info table tr td i.fa-square-o").click(function() {
                    $(".popup").fadeOut("fast");
                    caID = $(this).attr("data-caID"), $(this).hasClass("codinh") ? ($(".btn_back").attr("data-caID", caID), $("#backca").fadeIn("fast")) : ($(".btn_codinh, .btn_tam").attr("data-caID", caID),($(this).attr("data-info") && $(this).attr("data-info2")) ? $("#info-ca").show().html("Bạn phải có mặt từ " + $(this).attr("data-info") + ". Thời gian làm bài là 90ph từ " + $(this).attr("data-info2")) : $("#info-ca").hide().html(""), $("#doica").fadeIn("fast")), $("#BODY").css("opacity", "0.1")
				}), $("#MAIN .main-div #main-info table tr td i.fa-check-square-o").click(function() {
					caID = $(this).attr("data-caID"), $(".btn_thoat2").attr("data-caID", caID), $("#thoatca").fadeIn("fast"), $("#BODY").css("opacity", "0.1")
				}), $(".btn_thoat2").click(function() {
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.1"), caID = $(this).attr("data-caID"), $.ajax({
						async: !1,
						data: "caID0=" + caID,
						type: "post",
						url: "http://localhost/www/TDUONG/mobile/xuly-tam/",
						success: function(a) {
							alert("Lịch học của bạn sẽ chở về ban đầu!"), location.reload()
						}
					});
				}), $("#MAIN .main-div #main-info table tr td i.fa-ban").click(function() {
					$("#noneca").fadeIn("fast");
					$("#BODY").css("opacity", "0.1");
				});
			<?php } ?>
				var a = $("#tkb-hin").val();
				var b = $("#tkb-hin2").val();
				$("#tkb-show").html(a);
				$("#tkb-show2").html(b);
				var t = $("#kt-hin").val();
				$("#kt-show").html(t);
				$("#MAIN > .main-div .main-1-left table tr.con-ca").each(function(index, element) {
					if (!$(element).find("td").hasClass("has-ca")) {
						$(element).remove();
					}
				});
				var max_dem = $("#max-dem").val();
				$(".table-tkb tr th").css("width", (100 / max_dem) + "%");
				$(".table-tkb tr.big-ca th").removeAttr("style").attr("colspan", max_dem);
				
				$(".btn-exit").click(function() {
					$(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
				});
			});
		</script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/mobile/js/canvasjs.min.js"></script>
	</head>

    <body>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>
        
        <div class="popup animated bounceIn" id="noneca">
            <div class="popup-close"><i class="fa fa-close"></i></div>
            <div>
                <p class="title">Bạn không thể đổi ca được, ca học đang diễn ra hoặc đã quá tải!</p>
                <div>
                    <button class="submit2 btn-exit"><i class="fa fa-check"></i></button>
                </div>
            </div>
      	</div>
        
        <?php if($data0["taikhoan"]<0) { ?>
        <div class="popup animated bounceIn" style="display:block;">
            <div>
                <p class="title">Tài khoản của bạn đang bị âm, bạn không thể đổi ca!<br />(<?php echo format_price($data0["taikhoan"]); ?>)</p>
            </div>
      	</div>	
		<?php } ?>
    
    	<div class="popup animated bounceIn" id="doica">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
                <p class="title" id="info-ca"></p>
            	<p class="title">Bạn muốn đổi cố định hay đổi tạm?</p>
                <div>
                	<button class="submit2 btn_codinh">Cố định</button>
                    <button class="submit2 btn_tam">Tạm</button>
                </div>
            </div>
        </div>
        
        <div class="popup animated bounceIn" id="backca">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
            	<p class="title">Bạn muốn trở về ca cố định?</p>
                <div>
                	<button class="submit2 btn_back"><i class="fa fa-check"></i></button>
                </div>
            </div>
        </div>
        
        <div class="popup animated bounceIn" id="thoatca">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
            	<p class="title">Bạn muốn thoát ca này?</p>
                <div>
                    <button class="submit2 btn_thoat2"><i class="fa fa-check"></i></button>
                </div>
            </div>
        </div>
                             
      	<div id="SIDEBACK"><div id="BODY">
            
            <div id="MAIN">

<!--              	<div class="main-div back animated bounceInUp" id="main-top">-->
<!--                	<div id="main-person">-->
<!--                		<h1>Lịch học</h1>-->
<!--                        <div class="clear"></div>-->
<!--                   	</div>-->
<!--                    <div id="main-avata"><img src="https://localhost/www/TDUONG/hocsinh/avata/--><?php //echo $data0["avata"]; ?><!--" /></div>-->
<!--                    <div id="main-code"><h2>--><?php //echo $data0["cmt"];?><!--</h2></div>-->
<!--                    <div class="clear"></div>-->
<!--                </div>-->

<!--                <div class="main-div animated animated2 bounceInUp">-->
<!--                    <div id="main-info">-->
<!--                        <div class="main-1-left back" style="padding: 10px 0;margin-bottom: 0;">-->
<!--                            <div>-->
<!--                                <p class="main-title"><a href="http://localhost/www/TDUONG/mobile/xin-nghi-hoc/" style="color:#FFF;">Xin nghỉ học</a></p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="clear"></div>-->
<!--                </div>-->

                <div class="main-div animated bounceInUp">
                    <div id="main-info">
                        <div class="main-1-left back" style="margin-right:2%;max-height:none;">
                            <div>
                                <h1 style="margin-bottom: 5px;">Lịch học</h1>
                                <p id="tkb-show">Thứ 2 - Thứ 4 - Thứ 6</p>
                            </div>
                            <table class="table-tkb" style="border-spacing:0 3px;">
                                <?php
                                $max=0;
                                $cum_arr=$list_cum=$tkb=array();
                                $result=get_all_cum($lmID,$monID);
                                while($data=mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td colspan="2" style='text-transform:uppercase;'>
                                            <span><?php echo $data["name"]; ?></span></td>
                                    </tr>
                                    <tr>
                                        <td style='padding-top: 0;padding-bottom: 0;'>
                                            <ul class='ul-ca'>
                                                <?php
                                                $list_cum[]=$data["ID_CUM"];
                                                $cum_arr[$data["ID_CUM"]]=array();
                                                $query5="SELECT c.ID_CA,c.thu,c.siso,g.gio,g.buoi,a.ID_STT AS hientai,o.ID_STT AS codinh FROM cahoc AS c 
                                                INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' AND g.ID_MON='$monID'
                                                LEFT JOIN ca_hientai AS a ON a.ID_CA=c.ID_CA AND a.ID_HS='$hsID' AND a.cum='$data[ID_CUM]'
                                                LEFT JOIN ca_codinh AS o ON o.ID_CA=c.ID_CA AND o.ID_HS='$hsID' AND o.cum='$data[ID_CUM]'
                                                WHERE c.cum='$data[ID_CUM]'
                                                ORDER BY c.thu ASC,g.buoi ASC,g.thutu ASC";
                                                $result5 = mysqli_query($db, $query5);
                                                $numtb=($demhs / mysqli_num_rows($result5)) + 25;
                                                while ($data5 = mysqli_fetch_assoc($result5)) {
                                                    $has = $has_codinh = false;
                                                    if(isset($data5["hientai"]) && is_numeric($data5["hientai"])) {
                                                        $has = true;
                                                    }
                                                    if(isset($data5["codinh"]) && is_numeric($data5["codinh"])) {
                                                        $has_codinh = true;
                                                    }
                                                    if($num_ca[$data5["ID_CA"]] <= $numtb || $num_ca[$data5["ID_CA"]] <= $data5["siso"]) {
                                                        $num=$num_ca[$data5["ID_CA"]];
                                                        $caID=encode_data($data5["ID_CA"],$code);
                                                    } else {
                                                        $num=-$num_ca[$data5["ID_CA"]];
                                                        $caID=encode_data(0,$code);
                                                    }
                                                    $cum_arr[$data["ID_CUM"]][]=$num;
                                                    $max = $max > abs($num) ? $max : abs($num);
                                                    $check=check_dang_hoc($data5["gio"],$data5["thu"]);
                                                    if($has) {
                                                        if($has_codinh) {
                                                            echo"<li style='background:rgba(255,255,255,0.15);'>";
                                                        } else {
                                                            echo"<li style='background:rgba(255,250,3,0.2);'>";
                                                        }
                                                        $tkb[]="Thứ ".$data5["thu"];
                                                        echo"<span><i class='";if($has_codinh){echo"codinh";} echo" fa ";if($check || $num<0) echo"fa-ban"; else echo"fa-check-square-o";echo"'></i></span>";
                                                    } else {
                                                        echo"<li><span><i class='fa ";if($check || $num<0) echo"fa-ban"; else echo"fa-square-o";echo"' data-caID='".$caID."'></i></span>";
                                                    }
                                                    echo "<span>Thứ $data5[thu]<br />$data5[gio]</span></li>";
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                        <td style='width: 50%;padding-top: 0;padding-bottom: 0;'><div id='chart-codinh-<?php echo $data["ID_CUM"]; ?>' style='width:100%;height:<?php echo 57.5*count($cum_arr[$data["ID_CUM"]]); ?>px;'></div></td>
                                    </tr>
                                <?php } ?>
                            </table>
                            <input type="hidden" value="<?php echo implode(" - ",$tkb);?>" id="tkb-hin" />
                        </div>
                    </div>
                </div>

                <div class="clear"></div>              
            </div>
        
        </div>
        <?php require_once("include/MENU.php"); ?>
        <script type="text/javascript">
            window.onload = function () {
                <?php
                for($i=0;$i<count($list_cum);$i++) {
                ?>
                var chartCodinh<?php echo $list_cum[$i]; ?> = new CanvasJS.Chart('chart-codinh-<?php echo $list_cum[$i]; ?>',
                    {
                        backgroundColor: '',
                        axisY:{
                            interval: 30,
                            labelFontColor: '',
                            labelFontSize: 0,
                            labelFontWeight: 'normal',
                            labelFontFamily:'helvetica' ,
                            tickColor: '#D0AA86',
                            gridThickness: 0,
                            tickThickness: 0,
                            lineColor: 'rgba(255,255,255,0.15)',
                            gridColor: 'rgba(255,255,255,0.15)',
                            maximum: <?php echo $max; ?>
                        },
                        theme: 'theme2',
                        interactivityEnabled: true,
                        axisX:{
                            labelFontColor: '',
                            labelFontSize: 0,
                            labelFontWeight: 'normal',
                            labelFontFamily:'helvetica' ,
                            labelMaxWidth: 400,
                            interval:1,
                            gridColor: 'rgba(255,255,255,0.15)',
                            tickColor: '#D0AA86',
                            tickThickness: 0,
                            lineThickness: 0,
                            lineColor: 'rgba(255,255,255,0.15)',
                        },
                        animationEnabled: true,
                        dataPointWidth: 20,
                        toolTip:{
                            enabled: false,
                            shared: true,
                            backgroundColor: "#FFF",
                            borderColor: "",
                            borderThickness: 0,
                            fontColor: "#000",
                        },
                        legend:{
                            horizontalAlign: 'center',
                            verticalAlign: 'top',
                            fontFamily: 'helvetica',
                            fontSize: 12
                        },
                        data:[
                            {
                                type: 'bar',
                                showInLegend: false,
                                name: 'TỈ LỆ LÀM ĐƯỢC',
                                color: '<?php echo $mau; ?>',
                                indexLabelFontColor: '<?php echo $mau; ?>',
                                indexLabelPlacement: 'outside',
                                indexLabelOrientation: 'horizontal',
                                indexLabelFontFamily:'helvetica' ,
                                indexLabelFontSize: 14,
                                indexLabelFontWeight: 'normal',
                                toolTipContent: "Số lượng học sinh",
                                dataPoints: [
                                    <?php
                                    $n = count($cum_arr[$list_cum[$i]]);
                                    for($j=0;$j<$n;$j++) {
                                        $num=$cum_arr[$list_cum[$i]][$n-1-$j];
                                        if($num<0) {
                                            echo"{y: ".abs($num).", color: 'red'},";
                                        } else {
                                            echo"{y: ".abs($num)."},";
                                        }
                                    }
                                    ?>
                                ]
                            }
                        ]
                    });
                chartCodinh<?php echo $list_cum[$i]; ?>.render();
                <?php } ?>
            }
        </script>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
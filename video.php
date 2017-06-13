<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
    $me=md5("1241996");
	
	$mon_name=get_mon_name($monID);
	$mau="#FFF";
	$result1=get_hs_short_detail($hsID,$lmID);
	$data1=mysqli_fetch_assoc($result1);
    $thu_string=array("CN","T2","T3","T4","T5","T6","T7");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>VIDEO BÀI GIẢNG</title>
        
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.main-div .main-1 > ul {float:left;}.main-div .main-1 > ul.main-tabs {width:27.5%;margin-right: 2.5%;}.main-div .main-1 > ul.main-in {width:64.5%;}.main-tabs > li.active {opacity:1 !important;height:60px !important;}.main-tabs > li.active a {line-height:60px !important;}.main-in > li.active {display:block !important;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
            #timeline {width:2px;background:#FFF;position: absolute;left: 38.5%;top: 0;height: 100%;}
            #MAIN .main-div .main-1>ul.main-tabs>li {position: relative;opacity: 1 !important;}
            #MAIN .main-div .main-1>ul.main-tabs>li a {line-height: 22px;}
            #MAIN .main-div .main-1>ul.main-tabs>li a p {text-transform: capitalize;float:left;}
            #MAIN .main-div .main-1>ul.main-tabs>li:hover {background: rgba(0,0,0,.15) !important;}
            /*#MAIN .main-div .main-1>ul.main-tabs>li:hover a p {display: block;}*/
            #MAIN .main-div .main-1>ul.main-tabs>li a span.circle {position: absolute;z-index: 99;left: 86.5px;background: #FFF;width: 12px;height: 12px;border-radius: 100px;display: block;}
            table.table-lam {width: 93%;margin: 20px auto 0 auto;background: #FFF;border-collapse: collapse;border-radius: 10px;}
            table.table-lam tr td, table.table-lam tr th {padding: 7px;border-bottom: 1px solid #000;}
            table.table-lam tr:last-child td {border-bottom: none;}
            table.table-lam tr td span, table.table-lam tr th span {font-size: 14px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
			    $("#MAIN .main-div .main-1>ul.main-tabs>li a span.circle").each(function (index, element) {
                    var hei = ($(element).closest("li").height()/2)-6;
                    $(element).css("top",hei + "px");
                });
			    $("#MAIN .main-div .main-1>ul.main-in li:first-child").show();
                $(".main-tabs li").click(function () {
                    var pos = $(this).index() - 1;
                    $(".main-tabs li").css("background","none");
                    $(".main-in li").hide();
                    $(this).css("background","rgba(0,0,0,.15)");
                    $(".main-in li:eq(" + pos + ")").fadeIn("fast");
                });
                $("#add-email").typeWatch({
                    captureLength: 5,
                    callback: function (value) {
                        if(value!= "" && value.search("@gmail.com") > 0) {
                            $("#MAIN").css("opacity", "0.3");
                            $("#popup-loading").fadeIn("fast");
                            $.ajax({
                                async: true,
                                data: "email=" + value,
                                type: "post",
                                url: "https://localhost/www/TDUONG/xuly-mon/",
                                success: function(result) {
                                    console.log(result);
                                    $(".popup").fadeOut("fast");
                                    $("#MAIN").css("opacity", "1");
                                }
                            });
                        } else {
                            alert("Gmail không hợp lệ!");
                        }
                    }
                });
			});
		</script>
       
	</head>

    <body>

        <div class="popup animated bounceIn" id="popup-loading">
            <p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
        </div>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:68px;">
                            Bài giảng môn <?php echo $mon_name; ?>
                            <input type="text" id="add-email" style="padding: 10px;width: 300px;display: block;" class="input" placeholder="Địa chỉ GMAIL để share Bài giảng" value="<?php echo $data1["email"]; ?>" />
                        </h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data1["avata"]; ?>" />
                        <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data1["cmt"];?> (<?php echo $data1["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                    <!--<div id="main-code"><h2><?php echo $data1["cmt"];?></h2></div>-->
                </div>
                
                <div class="main-div animated bounceInUp">
                	<div class="main-1 back">
                    	<ul class="main-tabs" style="position: relative;">
                            <div id="timeline">
                            </div>
                            <?php
                                $dem=0;
                                $data_arr=array();
                                $result=get_video_share_hs($hsID,"X",$lmID);
                                while($data=mysqli_fetch_assoc($result)) {
                                    if($data["dad"]==0) {
                                        $temp=explode(",",$data["date_up"]);
                                        if(count($temp) == 2) {
                                            $dateup = $temp[0];
                                            $thu = $thu_string[date("w", strtotime($dateup))];
                                            $datestr = $thu . " (" . format_date($dateup) . ")";
                                            $dateup = $temp[1];
                                            $thu = $thu_string[date("w", strtotime($dateup))];
                                            $datestr .= "<br />" . $thu . " (" . format_date($dateup) . ")";
                                        } else {
                                            $datestr = "00 (00/00)";
                                        }
//                                        $temp=explode("-",$data["date_up"]);
//                                        if(stripos($temp[2],",") === false) {
//                                            $dateup=$temp[0]."-".$temp[1]."-".$temp[2];
//                                            $thu = $thu_string[date("w", strtotime($dateup))];
//                                            $datestr = $thu." ($temp[2]/$temp[1])";
//                                        } else {
//                                            $temp2=explode(",",$temp[2]);
//                                            $dateup=$temp[0]."-".$temp[1]."-".$temp2[0];
//                                            $thu = $thu_string[date("w", strtotime($dateup))];
//                                            $datestr = $thu." ($temp2[0]/$temp[1])";
//                                            $dateup = $temp[0]."-".$temp[1]."-".$temp2[1];
//                                            $thu = $thu_string[date("w", strtotime($dateup))];
//                                            $datestr .= ", ".$thu." ($temp2[1]/$temp[1])";
//                                        }
                                        echo "<li><a href='javascript:void(0)'><span class='circle'></span><p style='width: 37%;'>$datestr</p><p style='width: 63%;'>$data[name]</p><div class='clear'></div></a></li>";
                                        $data_arr[$data["ID_STT"]]["name"] = $data["name"];
                                        $data_arr[$data["ID_STT"]]["date_cum"] = str_replace("<br />",", ",$datestr);
                                        $data_arr[$data["ID_STT"]]["file"] = array();
                                        $data_arr[$data["ID_STT"]]["video"] = NULL;
                                        $data_arr[$data["ID_STT"]]["test"] = $data["ID_N"];
                                        $data_arr[$data["ID_STT"]]["folder"] = $data["ID_FILE"];
                                        $dem++;
                                    } else {
                                        if($data["type"]=="video") {
                                            $data_arr[$data["dad"]]["video"] = $data["ID_FILE"];
                                        } else {
                                            $data_arr[$data["dad"]]["file"][] = $data["ID_FILE"];
                                        }
                                    }
                                }
                            ?>
                        </ul>
                        <ul class="main-in">
                            <?php
                                foreach ($data_arr as $data => $value) {
                                    echo"<li>
                                        <p class='main-title' style='font-size: 26px;'>$value[name]<br />$value[date_cum]</p>";
                                        if(isset($value["video"])) {
                                            echo"<ol><a href='https://drive.google.com/file/d/".$value["video"]."/view?usp=sharing' target='_blank'><i class='fa fa-youtube-play'></i> Bài giảng</a></ol>";
                                        }
                                        $n=count($value["file"]);
                                        if($n==1) {
                                            echo"<ol><a href='https://drive.google.com/file/d/".$value["file"][0]."/view?usp=sharing' target='_blank'><i class='fa fa-folder'></i> Tài liệu</a></ol>";
                                        } else if($n>1 && isset($value["folder"])) {
                                            echo"<ol><a href='https://drive.google.com/drive/folders/".$value["folder"]."' target='_blank'><i class='fa fa-folder'></i> Tài liệu</a></ol>";
                                        }
                                        if($value["test"]!=0) {
                                            echo"<ol><a href='http://localhost/www/TDUONG/luyenthi/dang-nhap-xa/".encode_data($hsID."|".$data1["cmt"]."|".$lmID."|".$value["test"],md5("1241996"))."/' target='_blank'><i class='fa fa-file-text'></i> Trắc nghiệm</a></ol>";
                                            echo"<table class='table table-lam'>
                                                <tr>
                                                    <th colspan='4' style='text-align: left;'><span>THỐNG KÊ LỊCH SỬ LÀM BÀI</span></th>
                                                </tr>
                                                <tr>
                                                    <th style='width: 10%;'><span>STT</span></th>
                                                    <th><span>Hoàn thành</span></th>
                                                    <th style='width: 15%;'><span>Điểm</span></th>
                                                    <th style='width: 15%;'><span>Thời gian</span></th>
                                                </tr>";
                                                $dem=1;
                                                $query="SELECT l.diem,l.time,l.datetime FROM hoc_sinh_luyen_de AS l INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$value[test]' WHERE l.ID_HS='$hsID'";
                                                $result=mysqli_query($db,$query);
                                                if(mysqli_num_rows($result)!=0) {
                                                    $data = mysqli_fetch_assoc($result);
                                                    echo "<tr>
                                                        <td><span>$dem</span></td>
                                                        <td><span>".format_datetime($data["datetime"])."</span></td>
                                                        <td><span>$data[diem]</span></td>
                                                        <td><span>".format_timeback($data["time"]/1000)."</span></td>
                                                    </tr>";
                                                    $dem++;
                                                }
                                                $query="SELECT l.diem,l.time,l.datetime FROM hoc_sinh_lam_lai AS l INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$value[test]' WHERE l.ID_HS='$hsID'";
                                                $result=mysqli_query($db,$query);
                                                while($data=mysqli_fetch_assoc($result)) {
                                                    echo "<tr>
                                                        <td><span>$dem</span></td>
                                                        <td><span>".format_datetime($data["datetime"])."</span></td>
                                                        <td><span>$data[diem]</span></td>
                                                        <td><span>".format_timeback($data["time"]/1000)."</span></td>
                                                    </tr>";
                                                    $dem++;
                                                }
                                            echo"</table>";
                                        }
                                    echo"</li>";
                                }
                            ?>
                            <div class="clear"></div>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php require_once("include/IN.php"); ?>	               
            </div>
        
        </div>
        
        <?php require_once("include/MENU.php"); ?>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>
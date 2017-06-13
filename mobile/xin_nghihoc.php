<?php
	ob_start();
	//session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("include/is_mobile.php");
	$ip=$_SERVER['REMOTE_ADDR'];
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID,$lmID);
	$data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>XIN NGHỈ HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/mobile/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/mobile/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:6em;color:yellow;}#chart-li1 ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}#chart-li1 ul li > div p {color:#FFF;font-size:14px;line-height: 22px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){
                $("#ok").click(function() {
                    if(confirm("Bạn có chắc chắn không? Hành động không thể hoàn tác!")) {
                        return true;
                    } else {
                        return false;
                    }
                });
                $(".popup .popup-close, .popup").click(function() {
                    $(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1");
                });
                $(".xoa-nghi").click(function () {
                    var sttID = $(this).attr("data-sttID");
                    var del_tr = $(this).closest("tr");
                    if(confirm("Bạn có chắc chắn không?")) {
                        if (sttID != "") {
                            $(".popup").fadeOut("fast");
                            $("#popup-loading").fadeIn("fast");
                            $("#BODY").css("opacity", "0.1");
                            $.ajax({
                                async: true,
                                data: "sttID=" + sttID,
                                type: "post",
                                url: "http://localhost/www/TDUONG/mobile/xuly-tongquan/",
                                success: function (result) {
                                    del_tr.fadeOut("fast");
                                    $(".popup").fadeOut("fast");
                                    $("#BODY").css("opacity", "1");
                                }
                            })
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
			});
		</script>
	</head>

    <body>

        <?php
            if(isset($_POST["ok"])) {
                if(!($_FILES['pic-sms']['error']>0) && isset($_POST["sdt-sms"]) && isset($_POST["ngay-nghi"])) {
                    $image=$_FILES['pic-sms']["name"];
                    $sdt=$_POST["sdt-sms"];
                    $ngay=$_POST["ngay-nghi"];
                    $temp=strtolower($image);
                    if(stripos($temp,".png") != false || stripos($temp,".jpg") != false || stripos($temp,".jpeg") != false) {
                        move_uploaded_file($_FILES["pic-sms"]["tmp_name"],"../hocsinh/".$image);
						$date=$ngay;
                        $temp=explode(".",$image);
                        $newname=$hsID."-".$date.".".end($temp);
                        rename("../hocsinh/".$image,"../hocsinh/".$newname);
                        insert_diemdanh_nghi3(get_cum_buoi(0,$date,0,$monID,null),$hsID,$date,1,1,0,$newname,$sdt,0,$monID);
						$buoiID=get_id_buoikt($date,$monID);
						if($buoiID!=0) {
							$de=get_de_hs($hsID,$lmID);
							$query="SELECT n.ID_N FROM nhom_de AS n
							INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1'
							INNER JOIN loai_de AS l ON l.ID_D=d.loai AND l.name='$de'
							WHERE n.object='$buoiID' AND n.ID_LM='$lmID'";
							$result=mysqli_query($db,$query);
                            if(mysqli_num_rows($result)!=0) {
                                $data = mysqli_fetch_assoc($result);
                                $nID = $data["ID_N"];
                                $query = "INSERT INTO hoc_sinh_special(ID_HS,ID_N) SELECT * FROM (SELECT '$hsID' AS hsID,'$nID' AS nID) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM hoc_sinh_special WHERE ID_HS='$hsID' AND ID_N='$nID') LIMIT 1";
                                mysqli_query($db, $query);
                            }
						}
                        echo"<div class='popup animated bounceIn' style='display:block;'>
                            <div>
                                <p class='title'>Xin nghỉ thành công!, trợ giảng sẽ check lại!</p>
                            </div>
                        </div>";
                    } else {
                        echo"<div class='popup animated bounceIn' style='display:block;'>
                            <div>
                                <p class='title'>Ảnh chỉ có thể là định dạng .png, .jpg hoặc .jpeg</p>
                            </div>
                        </div>";
                    }
                } else {
                    echo"<div class='popup animated bounceIn' style='display:block;'>
                        <div>
                            <p class='title'>Vui lòng nhập đầy đủ thông tin!</p>
                        </div>
                    </div>";
                }
            }
        ?>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="SIDEBACK"><div id="BODY">
            
            <div id="MAIN">
            
            	<div class="main-div back animated bounceInUp" id="main-top">
                    <div id="main-person">
                        <h1>Xin nghỉ học có phép</h1>
                        <div class="clear"></div>
                    </div>
                    <div id="main-avata"><img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" /></div>
                    <div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>
                    <div class="clear"></div>
                </div>

                <form action="http://localhost/www/TDUONG/mobile/xin-nghi-hoc/" method="post" enctype="multipart/form-data">
                    <div class="main-div animated bounceInUp">
                        <ul>
                            <li id="chart-li1" class="li-3">
                                <ul>
                                    <li>
                                        <div style="width:100%;margin-top:0;text-align:center;"><p>B1: Up ảnh tin nhắn phụ huynh xin nghỉ</p></div>
                                    </li>
                                    <li>
                                        <input type="file" class="submit" accept="image/jpeg" name="pic-sms" style="width: 92%;" />
                                    </li>
                                </ul>
                            </li>
                            <li id="chart-li1" class="li-3" style="margin-top:3px;">
                                <ul>
                                    <li>
                                        <div style="width:100%;margin-top:0;text-align:center;"><p>B2: Nhập SĐT phụ huynh đã dùng để nhắn tin xin nghỉ</p></div>
                                    </li>
                                    <li>
                                        <div style="width:100%;"><input type="text" name="sdt-sms" id="sdt-sms" placeholder="SĐT" autocomplete="off" style="width:90%;padding:5%" class="input" /></div>
                                    </li>
                                </ul>
                            </li>
                            <li id="main-tb" class="li-3" style="margin-top:3px;">
                                <ul>
                                    <li><div style="width:100%;text-align:center;"><p>B3: Ngày xin nghỉ</p></div></li>
                                    <li>
                                        <div style="width: 100%;">
                                            <select class="input" style="width: 100%;" name="ngay-nghi" id="ngay-nghi">
                                                <?php
                                                    $thu=date("w", strtotime(date("Y-m-d")));
													if($thu == 0) {
														$start = date("Y-m-d");
													} else if($thu <= 2) {
														$start = get_last_CN();
													} else {
														$start = get_next_CN();
													}
                                                    echo"<option value='$start'>".format_dateup($start)."</option>";
                                                    for($i = 1; $i <= 3; $i++) {
                                                        $now=date_create($start);
                                                        date_add($now,date_interval_create_from_date_string("+7 days"));
                                                        $start=date_format($now,"Y-m-d");
                                                        echo"<option value='$start'>".format_dateup($start)."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div style="width:100%;margin-top:0;text-align:right;"><button class="submit" name="ok" id="ok">Hoàn thành</button></div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <div class="clear"></div>
                    </div>
                </form>

                <div class="main-div animated bounceInUp">
                    <div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Lịch sử tự xin nghỉ</h3></div>
                    <div id="main-table">
                        <table>
                            <tr id="table-head" class="back tr-big">
                                <th style="width:5%;"><span>STT</span></th>
                                <th><span>Ngày</span></th>
                                <th style="width:10%;"><span>Ảnh</span></th>
                                <th style="width:15%;"><span>SĐT</span></th>
                                <th style="width:10%;"><span>Trạng thái</span></th>
                                <th style="width:10%;"><span></span></th>
                            </tr>
                            <?php
                                $dem=1;
                                $query="SELECT ID_STT,ngay,anh,sdt,confirm FROM diemdanh_nghi WHERE ngay!='0000-00-00' AND ID_HS='$hsID' AND ID_MON='$monID'";
                                $result=mysqli_query($db,$query);
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<tr>
                                        <td><span>$dem</span></td>
                                        <td><span>".format_dateup($data["ngay"])."</span></td>
                                        <td><img src='https://localhost/www/TDUONG/hocsinh/$data[anh]' style='width:100%;' /></td>
                                        <td><span>$data[sdt]</span></td>";
                                        if($data["confirm"]==1) {
                                            echo"<td><span>Đã xác nhận</span></td><td><span></span></td>";
                                        } else {
                                            echo"<td><span>Đã gửi</span></td><td><button class='submit xoa-nghi' data-sttID='".encode_data($data["ID_STT"],md5("123456"))."'>Xóa</button></td>";
                                        }
                                    echo"</tr>";
                                    $dem++;
                                }
                            ?>
                        </table>
                    </div>
                </div>

            </div>
        
        </div>
        <?php require_once("include/MENU.php"); ?>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
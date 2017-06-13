<?php
	ob_start();
	//session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	session_start();
    if(!$_SESSION['id']) {
        header('location: http://localhost/www/TDUONG/trogiang/dang-nhap/');
        exit();
    }

	$mau="#FFF";
    $id=$_SESSION['id'];
    if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"])) {
        $hsID=$_GET["hsID"];
    } else {
        $hsID=0;
    }
    $result2=get_hs_short_detail2($hsID);
    $data2=mysqli_fetch_assoc($result2);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>QUẢN LÝ TIỀN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/tongquan.css"/>
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->     
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/trogiang/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:22px;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}table tr:last-child td:first-child, table tr:last-child td:last-child {border-bottom-left-radius:0;border-bottom-right-radius:0;}#MAIN > .main-div .main-1-left table tr td {overflow:hidden;position:relative;}#MAIN > .main-div .main-1-left table tr td > nav {width:100%;height:100%;}#MAIN > .main-div .main-1-left table tr td > div.tab-num {position:absolute;z-index:9;right:-20px;top:-5px;background:rgba(0,0,0,0.15);width:60px;height:30px;-ms-transform: rotate(45deg);-webkit-transform: rotate(45deg); transform: rotate(45deg);}#MAIN > .main-div .main-1-left table tr td > div.tab-num span {color:#FFF;line-height:35px;font-size:12px !important;}#MAIN > .main-div .main-1-left table tr td > nav > div {float:left;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-left {width:65%;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-right {width:25%;padding-left:5%;text-align:left;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-right i {font-size:22px;cursor:pointer;color:#FFF;}
			ul.ul-ca {height: 100%;width: 100%;}ul.ul-ca li {height:35px;line-height: 33px;padding-left: 10px;}ul.ul-ca li span i {font-size: 22px;cursor: pointer;margin-right: 15px;}
			table tr td {vertical-align: top;}
            .input {padding:5%;}
			
			/*#MAIN > .main-div .main-1-left table tr td > nav .tab-top {}#MAIN > .main-div .main-1-left table tr td > nav .tab-top span {font-weight:600;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot p {margin:10px 0 0 0;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot p i {font-size:22px;cursor:pointer;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot > span {display:block;}*/
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {

                $("#MAIN #main-mid .status .table").delegate("tr td input.edit","click",function() {
                    del_tr = $(this).closest("tr");
                    del_tr.css("opacity","0.3");
                    idVAO = $(this).attr("data-idVAO");
                    note = del_tr.find("td input.note_td").val();
                    price = del_tr.find("td input.price_td").val();
                    if($.isNumeric(idVAO) && idVAO!=0 && note!="" && price!="") {
                        $.ajax({
                            async: true,
                            data: "idVAO=" + idVAO + "&note=" + note + "&price=" + price,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-taikhoan/",
                            success: function (result) {
                                del_tr.css("opacity","1");
                            }
                        });
                    } else {
                        alert("Lỗi dữ liệu!");
                        del_tr.css("opacity","1");
                    }
                });

                $("#MAIN #main-mid .status .table").delegate("tr td input.edit2","click",function() {
                    del_tr = $(this).closest("tr");
                    del_tr.css("opacity","0.3");
                    idRA = $(this).attr("data-idRA");
                    note = del_tr.find("td input.note_td").val();
                    price = del_tr.find("td input.price_td").val();
                    if($.isNumeric(idRA) && idRA!=0 && note!="" && price!="") {
                        $.ajax({
                            async: true,
                            data: "idRA=" + idRA + "&note=" + note + "&price=" + price,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-taikhoan/",
                            success: function (result) {
                                del_tr.css("opacity","1");
                            }
                        });
                    } else {
                        alert("Lỗi dữ liệu!");
                        del_tr.css("opacity","1");
                    }
                });

                $(".refresh").click(function() {
                    location.reload();
                });

                $("#MAIN #main-mid .status .table tr td input.delete").click(function() {
                    idVAO = $(this).attr("data-idVAO");
                    me = $(this).closest("tr");
                    if($.isNumeric(idVAO) && idVAO!=0 && confirm("Bạn có chắc chắn muốn xóa? Hành động không thể hoàn tác!")) {
                        $.ajax({
                            async: true,
                            data: "idVAO2=" + idVAO,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-taikhoan/",
                            success: function(result) {
                                me.fadeOut("fast");
                                $(".refresh").html("Cập nhật");
                            }
                        });
                    }
                });

                $("#MAIN #main-mid .status .table tr td input.delete2").click(function() {
                    idRA = $(this).attr("data-idRA");
                    me = $(this).closest("tr");
                    if($.isNumeric(idRA) && idRA!=0 && confirm("Bạn có chắc chắn muốn xóa? Hành động không thể hoàn tác!")) {
                        $.ajax({
                            async: true,
                            data: "idRA2=" + idRA,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-taikhoan/",
                            success: function(result) {
                                me.fadeOut("fast");
                                $(".refresh").html("Cập nhật");
                            }
                        });
                    }
                });

                $("#MAIN #main-mid .status .table tr td input.add").click(function() {
                    del_tr = $(this).closest("tr");
                    del_tr.css("opacity","0.3");
                    note = del_tr.find("td input.note_td").val();
                    price = del_tr.find("td input.price_td").val();
                    if(note != "" && price != "") {
                        $.ajax({
                            async: true,
                            data: "hsID2=" + <?php echo $hsID; ?> + "&note2=" + note + "&price2=" + price + "&action=phat",
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-taikhoan/",
                            success: function (result) {
                                del_tr.css("opacity","1");
                                location.reload();
                            }
                        });
                    } else {
                        del_tr.css("opacity","1");
                        alert("Dữ liệu không chính xác hoặc Số tiền là bội số của 10.000đ");
                    }
                });
			});
		</script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/canvasjs.min.js"></script>
	</head>

    <body>

      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div class="ask">
                        <i class="fa fa-question-circle" style="color:<?php echo $mau;?>"></i>
                        <div class="sub-ask">
				<ul>
<!--                                <li><span><span style="font-size:8px">&#9899;</span> -20k nếu chuyển hẳn sang ca ĐÔNG hơn</span></li>-->
<!--                                <li><span><span style="font-size:8px">&#9899;</span> +5k nếu chuyển hẳn sang ca VẮNG hơn</span></li>-->
<!--                                <li><span><span style="font-size:8px">&#9899;</span> -10k nếu chuyển tạm sang ca ĐÔNG hơn</span></li>-->
<!--                                <li><span><span style="font-size:8px">&#9899;</span> +0k nếu chuyển tạm sang ca VẮNG hơn</span></li>-->
                                <!--<li><img src="https://localhost/www/TDUONG/images/dk_tam.png" style="width:60%;height:auto;float:left;" /><span style="float:left;line-height:30px;margin-left:10px;">Lịch học tạm</span></li>
                                <li></li>
                                <li><img src="https://localhost/www/TDUONG/images/dk_codinh.png" style="width:60%;height:auto;float:left;" /><span style="float:left;line-height:30px;margin-left:10px;">Lịch học cố định</span></li>-->
                                <li><span><span style="font-size:8px">&#9899;</span> Học sinh nhớ phải đổi ca trên máy trước khi đi học, nếu tự ý chuyển ca mà chưa đổi ca trên máy thì phạt 20k</span></li>
                                <li><span id='view-lichsu'><span style="font-size:8px;margin-right:5px;">&#9899;</span> Lịch sử đổi ca</span></li>
                            	<div class="clear"></div>
                            </ul>
                        </div>
                   	</div>
                	<div id="main-person">
                		<h1 style="line-height:98px;">Quản lý tiền thu vào</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                        <a href="http://localhost/www/TDUONG/trogiang/ho-so/" title="Hồ sơ cá nhân">
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                    <!--<div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>-->
                </div>


                <div class="main-div animated bounceInUp" id="main-mid">
                    <div id="main-info" class="status">
                        <div class="main-1-left back" style="margin-right:0;max-height:none;width: 100%;float: none;">
                            <div>
<!--                                <p class="main-title">Lịch làm cố định trong tuần</p>-->
                            </div>
                            <table class="table table-tkb" id="list-lich">
                                <?php
                                if($hsID != 0) {
                                    echo"<tr style='line-height:46px;'>
                                                <td><span>".date("d/m")."</span></td>
                                                <td><span>$data2[cmt]</span></td>
                                                <td><input type='text' class='input note_td' placeholder='Nội dung phạt' /></td>
                                                <td><input type='text' class='input price_td' placeholder='Số tiền dạng 10.000đ, 30.000đ' style='text-align:center;' /></td>
                                                <td><input type='submit' class='submit add' value='Thêm' /></td>
                                            </tr>";
                                }
                                ?>
                                <tr style="background:rgba(62,96,111,0.35)">
                                    <th style="width:15%;"><span>Thời gian</span></th>
                                    <th style="width:15%;"><span>CMT</span></th>
                                    <th style="width:35%;"><span>Nội dung</span></th>
                                    <th style="width:20%;"><span>Số tiền</span></th>
                                    <th style="width:15%;"><span></span></th>
                                </tr>
                                <tr><td colspan="5"><span>Đã nạp</span></td></tr>
                                <?php
                                $total1=0;
                                $result=get_nap_hocsinh($hsID);
                                $dem=0;
                                while($data=mysqli_fetch_assoc($result)) {
                                    if($dem%2!=0) {
                                        echo"<tr style='background:	rgba(209,219,189,0.35);line-height:46px;'>";
                                    } else {
                                        echo"<tr style='line-height:46px;'>";
                                    }
                                    echo"<td><span>".format_date($data["date"])." ($data[note2])</span></td>
												<td><span>$data2[cmt]</span></td>
												<td><input type='text' class='input note_td' value='$data[note]' /></td>
												<td><input type='text' class='input price_td' value='".format_price($data["price"])."' style='text-align:center;' /></td>
												<td><input type='submit' class='submit edit2' data-idRA='$data[ID_RA]' value='Sửa' /><input type='submit' class='submit delete2' data-idRA='$data[ID_RA]' value='Xóa' /></td>
											</tr>";
                                    $total1=$total1+$data["price"];
                                    $dem++;
                                }
                                if($dem==0) {
                                    echo"<tr><td colspan='5'><span>Không có dữ liệu</span></td></tr>";
                                }
                                ?>
                                <tr style="background:rgba(62,96,111,0.35)">
                                    <th colspan="3"><span>Tổng cộng</span></th>
                                    <th><span class="refresh"><?php echo format_price($total1);?></span></th>
                                    <th><span></span></th>
                                </tr>
                                <tr><td colspan="5"><span>Cần thu</span></td></tr>
                                <?php
                                $total1=0;
                                $result=get_phat_hs_con2($hsID);
                                $dem=0;
                                while($data=mysqli_fetch_assoc($result)) {
                                    if($dem%2!=0) {
                                        echo"<tr style='background:	rgba(209,219,189,0.35)'>";
                                    } else {
                                        echo"<tr>";
                                    }
                                    echo"<td><span>".format_date($data["date"])."</span></td>
												<td><span>$data2[cmt]</span></td>
												<td><input type='text' class='input note_td' value='$data[note]' /></td>
												<td><input type='text' class='input price_td' value='".format_price($data["price"])."' style='text-align:center;' /></td>
												<td><input type='submit' class='submit edit' data-idVAO='$data[ID_VAO]' value='Sửa' /><input type='submit' class='submit delete' data-idVAO='$data[ID_VAO]' value='Xóa' /></td>
											</tr>";
                                    $total1=$total1+$data["price"];
                                    $dem++;
                                }
                                if($dem==0) {
                                    echo"<tr><td colspan='5'><span>Không có dữ liệu</span></td></tr>";
                                }
                                ?>
                                <tr style="background:rgba(62,96,111,0.35)">
                                    <th colspan="3"><span>Tổng cộng</span></th>
                                    <th><span class="refresh"><?php echo format_price($total1);?></span></th>
                                    <th><span></span></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                </div>
        	<div class="clear"></div>
        </div>
        <?php require_once("include/MENU.php"); ?>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
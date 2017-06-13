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

    $result0=get_info_tro_giang($id);
    $data0=mysqli_fetch_assoc($result0);
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
			
			/*#MAIN > .main-div .main-1-left table tr td > nav .tab-top {}#MAIN > .main-div .main-1-left table tr td > nav .tab-top span {font-weight:600;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot p {margin:10px 0 0 0;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot p i {font-size:22px;cursor:pointer;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot > span {display:block;}*/
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {


                $(".table-tkb tr").delegate("td span.in","click",function() {
                    var me = $(this);
                    var buoi = $(this).attr("data-buoi");
                    var thu = $(this).attr("data-thu");
                    var id = parseInt($(this).closest("tr").attr("data-id"));
                    var a = "<i class='fa fa-check-square-o-id-" + id + "'></i>";
                    if (buoi != "" && thu != 0 && $.isNumeric(thu) && id != 0 && $.isNumeric(id)) {
                        if (confirm("Bạn có chắc chắn làm ca này?")) {
                            $.ajax({
                                async: false,
                                data: "buoi=" + buoi + "&thu=" + thu + "&id=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/trogiang/xuly-trogiang/",
                                success: function (result) {
                                    location.reload();
                                    me.removeClass("in").addClass("out")
                                    me.find("span").html(a);
                                    count_sum();
                                }
                            });
                                }

                        } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $(".table-tkb tr").delegate("td span.out","click",function() {
                    var me = $(this);
                    var buoi = $(this).attr("data-buoi");
                    var thu = $(this).attr("data-thu");
                    var id = parseInt($(this).closest("tr").attr("data-id"));
                    var a = "<i class='fa fa-square-o-id-" + id + "'></i>";
                    if (buoi != "" && thu != 0 && $.isNumeric(thu) && id != 0 && $.isNumeric(id)) {
                        if (confirm("Bạn có chắc chắn hủy ca này?")) {
                            $.ajax({
                                async: false,
                                data: "buoi3=" + buoi + "&thu3=" + thu + "&id3=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/trogiang/xuly-trogiang/",
                                success: function (result) {
                                    location.reload();
                                    me.removeClass("out").addClass("in")
                                    me.find("span").html(a);
                                    count_sum();
                                }
                            });
                        }

                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });
                count_sum();
                function count_sum() {
                    $(".main-1-left p.sum").each(function (index, element) {
                        var id = $(element).attr("data-id");
                        var sum = $(".table-tkb").find("td ul li.off i.id-" + id).length;
                        $(element).html(sum);
                    });
                }
			});
		</script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/canvasjs.min.js"></script>
	</head>

    <body>

      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">QUẢN LÝ TIỀN</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                        <img src="http://localhost/www/TDUONG/trogiang/avata/placeholder.jpg"/>
                        <a href="http://localhost/www/TDUONG/trogiang/ho-so/" title="Hồ sơ cá nhân">
                            <p><?php echo $data0["name"];?></p>
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <!--<div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>-->
                </div>


                <div class="main-div animated bounceInUp" id="main-mid">
                    <div id="main-info">
                        <div class="main-1-left back" style="margin-right:0;max-height:none;width: 100%;float: none;">
                            <div>
                                <p class="main-title">Lịch làm cố định trong tuần</p>
                            </div>
                            <table class="table table-tkb" id="list-lich">
                                <tr>
                                    <th style="width:10%;" class="hidden"><span>STT</span></th>
                                    <th style="width:10%;"><span></span></th>
                                    <th style="width:35%;"><span>Nội dung</span></th>
                                    <th style="width:15%;"><span></span></th>
                                    <th style="width:30%;" class="hidden"><span>Ghi chú</span></th>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>1</span></td>
                                    <td><span class="fa fa-money"></span></td>
                                    <td><span>Nhập - Nạp - Rút tiền</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/trogiang/hoc-sinh-tien-hoc/'" /></td>
                                    <td class="hidden"><span>Các thao tác tiền với tài khoản học sinh</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>2</span></td>
                                    <td><span class="fa fa-eye"></span></td>
                                    <td><span>Kiểm tra tiền học</span></td>
                                    <td><input class='submit' type='submit' value='Kiểm tra' onclick="location.href='http://localhost/www/TDUONG/trogiang/kiem-tra-tien/'" /></td>
                                    <td class="hidden"><span>Kiểm tra tiền học và tiền phạt nhập vào</span></td>
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
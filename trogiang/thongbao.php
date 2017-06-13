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
    $id=$_SESSION['id'];
	$mau="#FFF";
    $result0=get_info_tro_giang($id);
    $data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THÔNG BÁO</title>
        
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){$("#MAIN .main-div #main-table table tr td button.daxem").click(function(){tbID=$(this).attr("data-tbID"),$.isNumeric(tbID)&&0!=tbID&&$.ajax({url:"https://localhost/www/TDUONG/xuly-thongbao/",async:!1,data:"tbID="+tbID,type:"post",success:function(t){location.reload()}})}),$("#MAIN .main-div #main-table table tr td button.chuaxem").click(function(){tbID=$(this).attr("data-tbID"),$.isNumeric(tbID)&&0!=tbID&&$.ajax({url:"https://localhost/www/TDUONG/xuly-thongbao/",async:!1,data:"tbID0="+tbID,type:"post",success:function(t){location.reload()}})})});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
            
            <div id="MAIN">

            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">Thông báo</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                        <img src="http://localhost/www/TDUONG/trogiang/avata/placeholder.jpg"/>
                        <a href="http://localhost/www/TDUONG/trogiang/ho-so/" title="Hồ sơ cá nhân">
                            <p><?php echo $data0["name"];?></p>
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <!--<div id="main-code"><h2><?php echo $data1["cmt"];?></h2></div>-->
                </div>
                
                <div class="main-div animated bounceInUp">
                    <div id="main-table">
                    	<table>
                        	<tr id="table-head" class="back tr-big">
                            	<th style="width:10%;"><span>STT</span></th>
                                <th style="width:40%;"><span>Nội dung</span></th>
                                <th style="width:20%;"><span>Thời gian</span></th>
                                <th style="width:10%;"><span>Danh mục</span></th>
                                <th style="width:10%;"><span>Trạng thái</span></th>
                                <th style="width:10%;"><span></span></th>
                      		</tr>

                        </table>
                    </div>

					<div class="page-number back">
						<ul>

						</ul>
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
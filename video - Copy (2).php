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
	
	$mon_name=get_mon_name($monID);
	$mau="#FFF";
	$result1=get_hs_short_detail($hsID,$lmID);
	$data1=mysqli_fetch_assoc($result1);
    $thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.main-div .main-1 > ul {float:left;}.main-div .main-1 > ul.main-tabs {width:20.5%;}.main-div .main-1 > ul.main-in {width:74.5%;}.main-tabs > li.active {opacity:1 !important;height:60px !important;}.main-tabs > li.active a {line-height:60px !important;}.main-in > li.active {display:block !important;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
            #timeline {width:2px;background:#FFF;position: absolute;left: 12.7%;top: 0;height: 100%;}
            #MAIN .main-div .main-1>ul.main-tabs>li {background: none !important;position: relative;opacity: 1 !important;height: 70px;padding-top: 10px;}
            #MAIN .main-div .main-1>ul.main-tabs>li a {padding-left: 20px;line-height: 22px;}
            #MAIN .main-div .main-1>ul.main-tabs>li a p {text-transform: capitalize;}
            #MAIN .main-div .main-1>ul.main-tabs>li:hover {background: rgba(0,0,0,.15) !important;}
            /*#MAIN .main-div .main-1>ul.main-tabs>li:hover a p {display: block;}*/
            #MAIN .main-div .main-1>ul.main-tabs>li a span.circle {position: absolute;z-index: 99;left: 0;top: 14px;background: #FFF;width: 12px;height: 12px;border-radius: 100px;display: block;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
			    $(".myvideo:eq(0)").closest("div").show();
                var my_width = $(".myvideo:eq(0)").width()*9/16;
                $(".myvideo").attr("height",my_width+"px");
                $(".main-in").css("height",my_width+"px");
				$(".main-tabs > li > a").click(function() {
				    var id = $(this).attr("data-id");
                    if($.isNumeric(id) && id > 0) {
                        $(this).removeAttr("data-id");
                        $.ajax({
                            async: true,
                            data: "videoid=" + id,
                            type: "post",
                            url: "https://localhost/www/TDUONG/xuly-tongquan/",
                            success: function(result) {
                            }
                        });
                    }
				    var stt = $(this).closest("li").index();
                    $(".myvideo").closest("div").hide();
                    $(".myvideo:eq("+stt+")").closest("div").fadeIn("fast");
				});
			});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">Bài giảng môn <?php echo $mon_name; ?></h1>
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
                                $data_arr=array();
                                $query="SELECT ID_TL,title,intro,short_con,dateup,ID_DM FROM tailieu WHERE type='youtube' AND intro!='' ORDER BY ID_TL DESC";
                                $result=mysqli_query($db,$query);
                                while($data=mysqli_fetch_assoc($result)) {
                                    $data_arr[$data["intro"]] = array(
                                        "ID_TL" => $data["ID_TL"],
                                        "title" => $data["title"],
                                        "intro" => $data["intro"],
                                        "link" => $data["short_con"],
                                        "dateup" => $data["dateup"],
                                        "ID_DM" => $data["ID_DM"]
                                    );
                                }
                                foreach($data_arr as $x => $x_value) {
                                    $thu=$thu_string[date("w", strtotime($x))];
                                    echo"<li><a href='javascript:void(0)' data-id='".$x_value["ID_TL"]."'><span class='circle'></span> <p>$thu, ".format_dateup($x)."<br />".$x_value["title"]."</p></a></li>";
                                }
                            ?>
                        </ul>
                        <ul class="main-in">
                            <?php
                                foreach($data_arr as $x => $x_value) {
                                    echo"<div style='display: none;'><iframe class='myvideo' width='100%' height='' src='".$x_value["link"]."' frameborder='0' allowfullscreen></iframe></div>";
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
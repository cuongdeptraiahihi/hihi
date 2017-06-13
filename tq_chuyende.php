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
	if(isset($_GET["dad"]) && is_numeric($_GET["dad"])) {
		$dad=$_GET["dad"];
	} else {
		$dad=0;
	}
	$title=get_chuyende($dad);
	
	$dem_buoi=count_buoi_kt();
	if($dem_buoi<10) {
		$dem_begin=0;
	} else {
		$dem_begin=$dem_buoi-10;
	}
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID,$lmID);
	$data0=mysqli_fetch_assoc($result0);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THÔNG KÊ CÁC CHUYÊN ĐỀ TRONG 10 THÁNG</title>
        
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.multi-div {height:200px;}.multi-div > nav {height:100%;}.multi-div > nav > div {float:left;border-radius:10px;}.multi-div > nav .multi-left {width:31%;text-align:center;height:100%;margin-right:1.5%;}.multi-div > nav .multi-left p {color:#FFF;font-size:22px;text-transform:uppercase;line-height:30px;width:85%;margin:auto;}.multi-div > nav .multi-right {width:66.5%;height:100%;padding-right:1%;}.multi-div > nav .multi-mid {width:16%;height:100%;position:relative;}.multi-div > nav .multi-right > .chartCD {height:100%;width:100%;}.multi-div > nav .multi-mid > .chart-me {width:100%;height:100%;}.multi-div > nav .multi-mid > .chart-me .chartCDhoc {width:100%;height:100%;}.multi-div > nav .multi-mid > .chart-me .chart-info {color:#FFF;position:absolute;top:39.5%;font-size:24px;}.multi-div > nav .multi-left:hover p {display:none;}.multi-div > nav .multi-left:hover > a {display:block;}.multi-div > nav .multi-left > a {color:#000;background:#FFF;border-radius:10px;width:40%;height:45px;display:none;line-height:45px;font-weight:600;margin:77.5px auto 0 auto;font-size:22px;text-align:center;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				/*$(".multi-div > nav .multi-left").hover(function() {
					me = $(this);
					$(this).find("p").hide();
					$(this).find("> a").css("margin","100px auto 0 auto");
					$(this).find("> a").stop().animate({marginTop:"77.5px",opacity:"1"},250);
				},function() {
					me = $(this);
					$(this).find("> a").stop().animate({marginTop:"100px",opacity:"0"},250);
					//me.find("> a").delay(500).css("margin","25px auto 0 auto");
					me.find("p").delay(500).show(0);
				});*/
			});
		</script>
        <script type="text/javascript">
			window.onload = function () {
			<?php
				$stt=0;$phan=array();
				$result3=get_chuyende_con($dad);
				while($data3=mysqli_fetch_assoc($result3)) {
			?>
				/*var chartHoc<?php echo $stt; ?> = new CanvasJS.Chart("chartCDhoc_<?php echo $stt; ?>",
				{
					theme: "theme2",
					toolTip:{
						enabled: false
					},	
					backgroundColor: "",
					animationEnabled: true,
					interactivityEnabled: false,
					data: [
					{       
						type: "doughnut",
						startAngle: -90,
						innerRadius: "75%",
						showInLegend: false,
						indexLabelFontFamily:"helvetica" ,
						dataPoints: [
						<?php
							/*$query2="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE ID_CD='$data3[ID_CD]' AND ID_LOP='$lopID' AND ID_MON='$monID'";
							$result2=mysqli_query($db,$query2);
							$tong=mysqli_num_rows($result2);
							$query4="SELECT diemdanh_buoi.ID_STT FROM diemdanh_buoi INNER JOIN $diemdanh_string ON $diemdanh_string.ID_DD=diemdanh_buoi.ID_STT AND $diemdanh_string.ID_HS='$hsID' WHERE diemdanh_buoi.ID_CD='$data3[ID_CD]' AND diemdanh_buoi.ID_LOP='$lopID' AND diemdanh_buoi.ID_MON='$monID'";
							$result4=mysqli_query($db,$query4);
							$count_me=mysqli_num_rows($result4);
							if($tong>0) {
								$phan_tram=($count_me/$tong)*100;
							} else {
								$phan_tram=0;
							}
							//$phan_tram=rand(35,100);
							$phan[]=$phan_tram;
							echo"{y: $phan_tram, color: '$mau'},
							{y: ".(100-$phan_tram).", color: 'rgba(255,255,255,0.15)'}";*/
						?>
						]
					}
					]
				});
				chartHoc<?php echo $stt; ?>.render();*/
			
				var chart<?php echo $stt; ?> = new CanvasJS.Chart("chartCD_<?php echo $stt; ?>",
				{
				  interactivityEnabled: false,
				  animationEnabled: true,
				  axisX:{
					interval:1,
					intervalType: "month",
					valueFormatString: "T#",
					labelFontColor: "<?php echo $mau?>",
					labelFontSize: 14,
					labelFontWeight: "normal",
					labelFontFamily:"helvetica" ,
					gridColor: '#D0AA86',
					tickColor: '#D0AA86',
					tickThickness: 0,
					lineThickness: 4,
					lineColor: 'rgba(255,255,255,0.15)',
				 },
				 backgroundColor: "",
				  axisY:{ 
					labelFontColor: "",  
					gridColor: '#D0AA86',
					tickColor: '#D0AA86',
					lineThickness: 0,
					gridThickness: 1,
					tickThickness: 0,
					gridColor: 'rgba(255,255,255,0.15)',
					lineColor: 'rgba(255,255,255,0.15)',
					theme: "theme2",
					maximum: 115,
					interval: 20,
				 },
				 dataPointMaxWidth: 40,
				  data: [
				  {        
					color: "<?php echo $mau;?>",
					indexLabelFontColor: "#FFF",
					indexLabelPlacement: "outside",
					indexLabelFontFamily:"helvetica" ,
					indexLabelFontSize: 14,
					type: "column",
					dataPoints: [
					<?php
						$thang=date("m");
						$nam=date("Y");
						$need=array();
						for($i=0;$i<10;$i++) {
							$need[]=$nam."-".$thang;
							$thang--;
							if($thang==0) {
								$thang="12";
								$nam--;
							} else {
								if($thang<10) {
									$thang="0".$thang;
								} else {
									$thang="$thang";
								}
							}
						}
						
						$tb_thanghs=$tb_B=$tb_G=array();
						$n=count($need)-1;
						for($i=$n;$i>=0;$i--) {
							$curr=$need[$i];
							$diemtb=$dem=$total=0;
							$query="SELECT b.ID_BUOI,d.ID_CD,d.diem FROM buoikt AS b 
							LEFT JOIN chuyende_diem AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_CD='$data3[ID_CD]' AND d.ID_HS='$hsID' 
							WHERE b.ngay LIKE '$curr-%' AND d.diem NOT LIKE 'X/%' AND d.diem NOT LIKE '%/0' ORDER BY b.ID_BUOI DESC";
							$result=mysqli_query($db,$query);
							while($data=mysqli_fetch_assoc($result)) {
								if(!isset($data["diem"]) || $data["diem"]=="X") {
									$diem=-1;
								} else {
									$check2=stripos($data["diem"],"/");
									if($check2===FALSE) {
										$diem=$data["diem"]*100;
									} else {
										$temp=explode("/",$data["diem"]);
										$diem=($temp[0] / $temp[1])*100;
									}
									$total+=$diem;
									$dem++;
								}
							}
							//$dem-1;
							if($dem==0) {
								$diemtb=-1;
								echo"{y: 0, label: '".format_month2($curr)."'},";
							} else {
								//$diemtb=rand(40,90);
								$diemtb=$total/$dem;
								$indexLabel=format_phantram($diemtb);	
								echo"{y: ".$diemtb.", indexLabel: '".$indexLabel."', label: '".format_month2($curr)."'},";
							}
						}
					?>
					]
				  }
				  ]
				});
			
				chart<?php echo $stt; ?>.render();
				
			<?php
				$stt++; 
				} 
			?>
			}
		</script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/canvasjs.min.js"></script>
       
	</head>

    <body>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1>Thống kê<br /><span>các chuyên đề <?php echo $title; ?> trong 10 tháng</span></h1>
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
                
                <div class="main-div hideme animated bounceInUp multi-div" style="height:73px;">
                	<nav>
                    	<div class="multi-left"></div>
                        <div class="multi-right" style="padding-right:0;width:67.5%;">
                        	<div class="main-2 back"><h3>Tỉ lệ làm được bài mỗi chuyên đề<br />qua từng tháng</h3></div>
                        </div>
                        <!--<div class="multi-mid">
                        	<div class="main-2 back"><h3>Tỉ lệ đi học<br />đầy đủ</h3></div>
                        </div>-->
                    </nav>
                </div>
                
                <div class="clear"></div>
                
                <?php
					$dem=0;
					$result1=get_chuyende_con($dad);
					while($data1=mysqli_fetch_assoc($result1)) {
				?>
                <div class="main-div hideme animated bounceInUp multi-div">
                	<nav>
                    	<div class="multi-left back">
                        	<p><?php echo $data1["title"]; ?></p>
                            <a class="animated bounceIn" href="https://localhost/www/TDUONG/tai-lieu-chuyen-de/<?php echo unicode_convert($data1["title"])."-".$data1["ID_CD"]; ?>.html">Học lại</a>
                      	</div>
                        <div class="multi-right back">
                        	<div id="chartCD_<?php echo $dem; ?>" class="chartCD">
                        	</div>
                        </div>
                    </nav>
                </div>
                <?php
						$dem++; 
					} 
				?>
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
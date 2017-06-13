<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$de=$_SESSION["de"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID,$lmID);
	$data0=mysqli_fetch_assoc($result0);
	
	$temp=explode("-",$data0["date_in"]);
	$in_month=$temp[1];
	$in_year=$temp[0];
	
	$date_in=get_lop_mon_in($lmID);
	$temp=explode("-",$date_in);
	$temp_thang=$temp[1];
	$temp_nam=$temp[0];
	
	$old_year=$temp_nam;
	$old_month=$temp_thang;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THỐNG KÊ BUỔI HỌC</title>
        
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				
			});
		</script>
        <script type="text/javascript">
			window.onload = function () {
				var chart4 = new CanvasJS.Chart("chartContainer2",
				{
				  interactivityEnabled: false,
				  animationEnabled: true,
				  axisX:{
                      labelAngle: 90,
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
					lineThickness: 4,
					gridColor: 'rgba(255,255,255,0.15)',
					lineColor: 'rgba(255,255,255,0.15)',
					theme: "theme2",
					maximum: 20,
					interval: 3,
				 },
				  data: [
				  {        
					color: "<?php echo $mau;?>",
					name: "Buổi đi học",
					//indexLabelFontColor: "white",
					indexLabelPlacement: "inside",
					indexLabelOrientation: "horizontal",
					indexLabelFontFamily:"helvetica" ,
					type: "stackedColumn",
					dataPoints: [
					<?php
                        $newID=get_new_cum_buoi($lmID,$monID)-1;
                        $data_arr = array();
						if($lopID!=1) {
                            $month=$old_month;
                            $year=$old_year;
                            $i=0;
                            while($i<=24) {
                                $count_me=0;
                                $now="$year-".format_month_db($month);
                                if($year>$in_year || ($year==$in_year && $month>=$in_month)) {
                                    if($year<2016 || ($year==2016 && $month<2)) {
                                        if($monID==1) {
                                            $nghi = get_lichsu_nghi($hsID,$now);
                                            $data_arr[$i]["lichsu"] = 16 - $nghi;
                                            $count_me += 16 - $nghi;
                                        }
                                    } else {
                                        $dihoc = count_hs_di_hoc($hsID,$data0["date_in"],$now,$lmID,$monID,$newID);
                                        $count_me += $dihoc;
                                        $data_arr[$i]["dihoc"] = $dihoc;
                                    }
                                    if($count_me==0) {
                                        echo"{y: $count_me, indexLabel: '$count_me', label: 'T".format_month2("$year-$month")."', indexLabelFontColor: '#FFF'},";
                                    } else {
                                        echo"{y: $count_me, indexLabel: '$count_me', label: 'T".format_month2("$year-$month")."', indexLabelFontColor: '#000'},";
                                    }
                                }
                                if($month<12) {
                                    $month++;
                                } else {
                                    $year++;
                                    $month=1;
                                }
                                $i++;
                            }
						}
					?>
					]
				  },{
				  	color: "<?php echo $mauall;?>",
					name: "Buổi nghỉ",
					//indexLabelFontColor: "white",
					indexLabelPlacement: "inside",
					indexLabelFontFamily:"helvetica" ,
					  indexLabelOrientation: "horizontal",
					type: "stackedColumn",
					dataPoints: [
					<?php
                        //if($data0["cmt"]>="99-0250" && $lopID==1 && $monID==1) {$date_in2="2016-07-04";}else{$date_in2=$data0["date_in"];}
                        $date_in2=$data0["date_in"];
						$month=$old_month;
						$year=$old_year;
						$year_arr=array();
						$year_arr[]=$year;
						$i=0;
						while($i<=24) {
							$count_me=0;
							$now="$year-".format_month_db($month);
							if($year>$in_year || ($year==$in_year && $month>=$in_month)) {
								if($year<2016 || ($year==2016 && $month<2)) {
									if($monID==1){
									    if(isset($data_arr[$i]["lichsu"])) {
                                            $count_me += $data_arr[$i]["lichsu"];
									    } else {
                                            $count_me += get_lichsu_nghi($hsID, $now);
                                        }
									}
								} else {
								    if(isset($data_arr[$i]["dihoc"])) {
								        $dihoc = $data_arr[$i]["dihoc"];
                                    } else {
                                        $dihoc = count_hs_di_hoc($hsID,$date_in2,$now,$lmID,$monID,$newID);
                                    }
                                    $count_me+=count_all_di_hoc($hsID,$date_in2,$now,$lmID,$monID,$newID)-$dihoc;
								}
								if($count_me==0) {
									echo"{y: $count_me, indexLabel: '$count_me', label: 'T".format_month2("$year-$month")."', indexLabelFontColor: '#000'},";
								} else {
									echo"{y: $count_me, indexLabel: '$count_me', label: 'T".format_month2("$year-$month")."', indexLabelFontColor: '#FFF'},";
								}
							}
							if($month<12) {
								$month++;
							} else {
								$year++;
								$year_arr[]=$year;
								$month=1;
							}
							$i++;
						}
					?>
					]
				  }
				  ]
				});
			
				chart4.render();
			}
		</script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/canvasjs.min.js"></script>
       
	</head>

    <body>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">Thống kê buổi học</h1>
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
                
                <div class="main-div hideme animated bounceInUp">
                	<div class="main-1 back"><h3>Biểu đồ theo dõi số buổi đi học - nghỉ học trong 24 tháng</h3></div>
                    <div id="main-chart2">
                    	<div id="chartContainer2" class="clear" style="height:100%;margin-left:-25px;"></div>
                    </div>
                    <div class="clear"></div>
                </div>
                
                <div class="main-div animated bounceInUp">
                	<div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Lịch sử nghỉ học</h3></div>
                    <div id="main-table">
                    	<table>
                            <?php
                                if($lopID==1) {
                                    echo"<tr class='back'><td colspan='2'><span>Khóa 1999 không thống kê số buổi đi học do dữ liệu thu thập không đủ</span></td></tr>";
                                }
                            ?>
                        	<tr id="table-head" class="back tr-big">
                                <th style="width:40%;"><span>Cụm ngày</span></th>
                                <th style="width:60%;"><span>Lý do</span></th>
                      		</tr>
                            <?php
								$check=true;
								$cumID=$stt=$temp=0;$string="";
								$result=get_diemdanh_nghi($hsID,$lmID,$monID);
								$num=mysqli_num_rows($result);
								while($data=mysqli_fetch_assoc($result)) {
									if($data["ID_CUM"]!=$cumID || $stt==$num-1) {
										if($cumID!=0) {
										    if($stt==$num-1) {
                                                echo "<tr class='back' style='opacity: 0.35;'>";
                                            } else {
                                                echo "<tr class='back'>";
                                            }
											echo"<td><span>".substr($string,3)."</span></td>";
											if($check) {
												echo"<td><span>".format_lydo_nghi($temp)."</span></td>";
											} else {
												echo"<td><span>Đang kiểm tra</span></td>";
											}
											echo"</tr>";
										$string="";
										}
										$cumID=$data["ID_CUM"];
                                        $temp=$data["is_phep"];
										if(isset($data["ID_O"]) && is_numeric($data["ID_O"])) {
											$check=true;
										} else {
											$check=false;
										}
									} 
									$stt++;
									if(stripos($string,format_date($data["date"]))===false) {
										$string.=" - ".format_date($data["date"]);
									}
								}
							?>
                        </table>
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
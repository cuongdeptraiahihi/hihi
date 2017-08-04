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
	$mau="#FFF";
	$result1=get_hs_short_detail($hsID,$lmID);
	$data1=mysqli_fetch_assoc($result1);
	
	if(isset($_GET["chitiet"])) {
		$temp=explode("-",$_GET["chitiet"]);
		$month=$temp[1];
		$year=$temp[0];
	} else {
		$month=get_last_month(date("m"));
		$year=get_last_year(date("m"),date("Y"));
	}
	
	$mon_name=get_mon_name($monID);
	
	$check0=check_khoa($lmID);
	
	if(isset($_GET["loai"]) && $_GET["loai"]==1) {
		$de_temp="B";
	} else if(isset($_GET["loai"]) && $_GET["loai"]==2) {
		$de_temp = "G";
	} else if(isset($_GET["loai"]) && $_GET["loai"]==4) {
		$de_temp = "Y";
	} else {
		$de_temp=$de;
	}
	
	$date_in=get_lop_mon_in($lmID);
	$temp=explode("-",$date_in);
	$temp_thang=$temp[1];
	$temp_nam=$temp[0];

	$hs_nghi = array();
	$dem_tbt=0;
	$thang=get_last_month(date("m"));
	$nam=get_last_year(date("m"),date("Y"));
	$tb_thanghs=$tb_B=$tb_G=$demrB=$demrG=$tb_Y=$demrY=array();
	$hs_rankB=$hs_rankG=$hs_rankY=array();
	while($dem_tbt < 6) {
		$nextm=get_next_time($nam,$thang);
		$curr=$nam."-".$thang;
		if($nam<$temp_nam || ($nam==$temp_nam && $thang<$temp_thang)) {
			break;	
		}
		$diem_tempB=$diem_tempG=$diem_tempY=-1;
		/*if($dem_tbt==0) {
			//$query7="SELECT hocsinh.ID_HS,hocsinh_mon.de,hocsinh_nghi.date FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_MON='$monID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_MON='$monID' WHERE hocsinh.lop='$lopID' ORDER BY hocsinh.cmt ASC";
			$query7="SELECT h.ID_HS,m.de AS detb,n.date,(SELECT AVG(d.diem) AS diem FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.loai IN ('0','1') WHERE b.ngay LIKE '$curr-%' AND d.ID_HS=h.ID_HS ORDER BY b.ID_BUOI DESC) AS diemtb FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='$monID' AND m.date_in<'$nextm-01' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='$monID' WHERE h.lop='$lopID' ORDER BY diemtb DESC,h.cmt ASC";
		} else {*/
			/*if($nam==$temp_nam && $thang==$temp_thang) {
				$query7="SELECT h.ID_HS,t.diemtb,t.detb,n.date FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='$monID' AND m.date_in<'$nextm-01' INNER JOIN diemtb_thang AS t ON t.ID_HS=h.ID_HS AND t.ID_MON='$monID' AND t.detb='B' AND t.datetime='$curr' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='$monID' WHERE h.lop='$lopID' ORDER BY t.diemtb DESC,h.cmt ASC";
			} else {*/
				$query7="SELECT m.ID_HS,t.diemtb,t.detb,n.ID_N FROM hocsinh_mon AS m 
				INNER JOIN diemtb_thang AS t ON t.ID_HS=m.ID_HS AND t.ID_LM='$lmID' AND t.datetime='$curr' 
				LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=m.ID_HS AND n.ID_LM='$lmID'
				WHERE m.ID_LM='$lmID' AND m.date_in<'$nextm-01' 
				ORDER BY t.diemtb DESC";
			//}
		//}
		$result7=mysqli_query($db,$query7);
		$demtb_B=$demtb_G=$demtb_Y=$tb_tong_B=$tb_tong_G=$tb_tong_Y=$rankB=$rankG=$rankY=0;
		$has=false;
		while($data7=mysqli_fetch_assoc($result7)) {
		    if(isset($data7["ID_N"])) {
		        $hs_nghi["hs-" . $data7["ID_HS"]] = 1;
		        continue;
            }

            //$diemtb=number_format((float)$data7["diemtb"], 2, '.', '');
            $diemtb=$data7["diemtb"];
            $detb=$data7["detb"];
            if($detb=="B" && $diemtb!=$diem_tempB) {
                $rankB++;
                $diem_tempB=$diemtb;
            }
            if($detb=="G" && $diemtb!=$diem_tempG) {
                $rankG++;
                $diem_tempG=$diemtb;
            }
			if($detb=="Y" && $diemtb!=$diem_tempY) {
				$rankY++;
				$diem_tempY=$diemtb;
			}
            if($curr=="$year-$month") {
                if($detb=="B") {
                    $hs_rankB[$data7["ID_HS"]]=array(
                        "diemtb" => $diemtb,
                        "rank" => $rankB
                    );
                } else if($detb=="G") {
                    $hs_rankG[$data7["ID_HS"]]=array(
                        "diemtb" => $diemtb,
                        "rank" => $rankG
                    );
                } else {
					$hs_rankY[$data7["ID_HS"]]=array(
						"diemtb" => $diemtb,
						"rank" => $rankY
					);
				}
            }
            if($data7["ID_HS"]==$hsID) {
                if($detb=="B") {
                    $tb_thanghs[]=array(
                        "diemtb" => $diemtb,
                        "detb" => $detb,
                        "datetime" => $curr,
                        "dem" => $rankB
                    );
                } else if($detb=="G") {
                    $tb_thanghs[]=array(
                        "diemtb" => $diemtb,
                        "detb" => $detb,
                        "datetime" => $curr,
                        "dem" => $rankG
                    );
                } else {
					$tb_thanghs[]=array(
						"diemtb" => $diemtb,
						"detb" => $detb,
						"datetime" => $curr,
						"dem" => $rankY
					);
				}
                $has=true;
            }
            if($detb=="B") {
                $demtb_B++;
                $tb_tong_B+=$diemtb;
            } else if($detb=="G") {
                $demtb_G++;
                $tb_tong_G+=$diemtb;
            } else {
				$demtb_Y++;
				$tb_tong_Y+=$diemtb;
			}
		}
		$demrB[]=$rankB;
		$demrG[]=$rankG;
		$demrY[]=$rankY;
		if(!$has) {
			$tb_thanghs[]=array(
				"diemtb" => "X",
				"detb" => "X",
				"datetime" => $curr
			);
		}
		if($demtb_B!=0) {
			$tb_B[]=array(
				"diemtb" => $tb_tong_B/$demtb_B,
				"datetime" => format_month($curr)
			);
		} else {
			$tb_B[]=array(
				"diemtb" => "X",
				"datetime" => format_month($curr)
			);
		}
		if($demtb_G!=0) {
			$tb_G[]=array(
				"diemtb" => $tb_tong_G/$demtb_G,
				"datetime" => format_month($curr)
			);
		} else {
			$tb_G[]=array(
				"diemtb" => "X",
				"datetime" => format_month($curr)
			);
		}
		if($demtb_Y!=0) {
			$tb_Y[]=array(
				"diemtb" => $tb_tong_Y/$demtb_Y,
				"datetime" => format_month($curr)
			);
		} else {
			$tb_Y[]=array(
				"diemtb" => "X",
				"datetime" => format_month($curr)
			);
		}
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
		$dem_tbt++;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>BẢNG XẾP HẠNG</title>
        
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
        <script src="https://localhost/www/TDUONG/js/iscroll.js"></script>
        <script>
			/*var myScroll2;
			function loaded () {
				myScroll2 = new IScroll('#main-chart', { scrollX: true, scrollY: false, mouseWheel: false, startX: -<?php if($dem_tbt*95>945){echo ($dem_tbt*95-945);}else{echo 0;} ?> });
			}
			
			document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);*/
		
			$(document).ready(function() {
				
				<?php if($_SESSION["test"]==0) { ?>
				$("#MAIN .main-div #main-table table tr td button.thachdau").click(function(){if(del_td=$(this).closest("td"),maso=$(this).attr("data-maso"),chap=0,$.isNumeric(chap)&&chap>=0&&chap<=5&&""!=maso){if("<?php echo base64_encode($_SESSION["cmt"]);?>"==maso)alert("Bạn không thể thách đấu chính mình!");else if(confirm("Nếu bạn mang bài về nhà làm thì sẽ thua luôn! Bạn có chắc chắn gửi lời thách đấu này?"))return $("#BODY").css("opacity","0.3"),$.ajax({url:"https://localhost/www/TDUONG/xuly-thachdau/",async:!1,data:"maso="+maso+"&chap="+chap,type:"post",success:function(a){$("#BODY").css("opacity","1"),alert(a);}}),!1}else alert("Vui lòng cung cấp đầy đủ thông tin và chính xác!")});
				
				<?php
				}
					if(!isset($_GET["loai"]) || $_GET["loai"]==0) {
				?>
				var me = $("#MAIN .main-div #main-table table tr#tr-me").index();
				if(me>10) {
					$("#MAIN .main-div #main-table table tr").each(function(index, element) {
						if(index>1 && index<me-5) {
							$(element).hide();
						}
					});
				}
				
				<?php 
					}
					if(isset($_GET["loai"])) {
						if($_GET["loai"]!=3) {
							echo"$('.loc3').children('span, input').hide();";
						}
					} else {
						echo"$('.loc3').children('span, input').hide();";
					}
				?>
				$("#select-loc").change(function(){loai=$(this).val(),3==loai?$(".loc3").children("span, input").show():$(".loc3").children("span, input").hide()}),$("#locdl").click(function(){return loai=$("#select-loc").val(),3==loai?(maso=$("#maso").val(),""!=maso&&" "!=maso&&"_"!=maso&&"%"!=maso?!0:(alert("Mã số không đúng!"),!1)):!0});
			});
		</script>
        <script type="text/javascript">

            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

            window.onload = function () {
                var myScroll2 = new IScroll('#main-chart', { scrollX: true, scrollY: false, mouseWheel: false, startX: -<?php if($dem_tbt*95>945){echo ($dem_tbt*95-945);}else{echo 0;} ?> });

                var chart8 = new CanvasJS.Chart("chartContainerTB",
				{
					animationEnabled: true,
					backgroundColor: "",
					axisX:{
						indexLabelFontFamily:"helvetica" ,
						labelFontFamily:"helvetica" ,
						gridColor: "rgba(255,255,255,0.15)",
						tickColor: "#D0AA86",
						gridThickness: 1,
						tickThickness: 0,
						lineThickness: 4,
						lineColor: "rgba(255,255,255,0.15)",
						//interval: 7,
						//intervalType: "day",
						labelFontColor: "<?php echo $mau?>",
						labelFontSize: 14,
						//valueFormatString: "DD/MM", 
						labelFontWeight: "normal",
					},     
					interactivityEnabled: true,                   
					toolTip:{
						backgroundColor: "#FFF",
						borderColor: "",
						borderThickness: 0,
						fontColor: "#000",
					},
					theme: "theme2",
					axisY: {
						//indexLabelFontFamily:"helvetica" ,
						gridColor: "rgba(255,255,255,0.15)",
						tickColor: "#D0AA86",
						tickThickness: 0,
						labelFontColor: "",
						gridThickness: 1,
						lineThickness: 4,
						maximum: 11,
						interval: 2,
						minimum: -1,
						lineColor: "rgba(255,255,255,0.15)",
						//labelFontSize: 16,
						//labelFontWeight: "normal",
						//valueFormatString: "# điểm"
					},
					dataPointMaxWidth: 40,
					data: [
					{        
						indexLabelFontFamily:"helvetica" ,
						type: "column",
						showInLegend: false,
						indexLabelFontColor: "<?php echo $mau;?>",
						indexLabelFontWeight: "normal",
						indexLabelFontSize: 14,
						lineThickness: 2,
						name: "Điểm TB",
						markerSize: 10,
						markerType: "circle",
						color: "<?php echo $mau;?>",
						click: function(e) {
							window.location.href=e.dataPoint.content;
						},
						dataPoints: [
						<?php
							$j=count($tb_thanghs)-1;
							while($j>=0) {
								//$tb_thanghs[$j]["diemtb"]=frand(5,7,2);
								if(!is_numeric($tb_thanghs[$j]["diemtb"])) {
									echo"{ label: '".format_month($tb_thanghs[$j]["datetime"])."', y: '".$tb_thanghs[$j]["diemtb"]."'},";
								} else {
									if($tb_thanghs[$j]["detb"]=="B") {
										$tb_G[$j]["diemtb"]=$tb_Y[$j]["diemtb"]="X";
										$content="<span style=\'font-style:normal\'><span style=\'font-size:10px;\'>&#9899;</span> Đề trung bình<br /><span style=\'font-size:10px\'>&#9899;</span> Xếp hạng ".$tb_thanghs[$j]["dem"]."/".$demrB[$j]."<br /><span style=\'font-size:10px\'>&#9899;</span><a href=\'https://localhost/www/TDUONG/bang-xep-hang/0/".$tb_thanghs[$j]["datetime"]."/\' style=\'color:#000;font-weight:600;\'> Click vào để xem chi tiết<br />xếp hạng tháng ".format_month($tb_thanghs[$j]["datetime"])."</a></span>";
									} else if($tb_thanghs[$j]["detb"]=="G") {
										$tb_B[$j]["diemtb"]=$tb_Y[$j]["diemtb"]="X";
										$content="<span style=\'font-style:normal\'><span style=\'font-size:10px;\'>&#9899;</span> Đề khá giỏi<br /><span style=\'font-size:10px\'>&#9899;</span> Xếp hạng ".$tb_thanghs[$j]["dem"]."/".$demrG[$j]."<br /><span style=\'font-size:10px\'>&#9899;</span><a href=\'https://localhost/www/TDUONG/bang-xep-hang/0/".$tb_thanghs[$j]["datetime"]."/\' style=\'color:#000;font-weight:600;\'> Click vào để xem chi tiết<br />xếp hạng tháng ".format_month($tb_thanghs[$j]["datetime"])."</a></span>";
									} else {
										$tb_G[$j]["diemtb"]=$tb_B[$j]["diemtb"]="X";
										$content="<span style=\'font-style:normal\'><span style=\'font-size:10px;\'>&#9899;</span> Đề kém<br /><span style=\'font-size:10px\'>&#9899;</span> Xếp hạng ".$tb_thanghs[$j]["dem"]."/".$demrY[$j]."<br /><span style=\'font-size:10px\'>&#9899;</span><a href=\'https://localhost/www/TDUONG/bang-xep-hang/0/".$tb_thanghs[$j]["datetime"]."/\' style=\'color:#000;font-weight:600;\'> Click vào để xem chi tiết<br />xếp hạng tháng ".format_month($tb_thanghs[$j]["datetime"])."</a></span>";
									}
									echo"{ label: '".format_month($tb_thanghs[$j]["datetime"])."', y: ".$tb_thanghs[$j]["diemtb"].", indexLabel: '{y} (".$tb_thanghs[$j]["detb"].")', toolTipContent: '$content', content: 'https://localhost/www/TDUONG/bang-xep-hang/0/".$tb_thanghs[$j]["datetime"]."/'},";
								}
								$j--;
							}
						?>
						]
					},
					{
						indexLabelFontFamily:"helvetica" ,
						type: "splineArea",
						lineThickness: 0,
						showInLegend: false,
						name: "Điểm TB (B)",
						color: "<?php echo $mauall; ?>",
						markerType: "none",
						toolTipContent: null,
						dataPoints: [
						<?php
							$j=count($tb_B)-1;
							while($j>=0) {
								//$tb_B[$j]["diemtb"]=frand(5,6.5,2);
								if(!is_numeric($tb_B[$j]["diemtb"])) {
									echo"{ label: '".$tb_B[$j]["datetime"]."', y: '".$tb_B[$j]["diemtb"]."'},";
								} else {
									echo"{ label: '".$tb_B[$j]["datetime"]."', y: ".$tb_B[$j]["diemtb"]."},";
								}
								$j--;
							}
						?>
						]
					},
					{
						indexLabelFontFamily:"helvetica" ,
						type: "splineArea",
						lineThickness: 0,
						showInLegend: false,
						name: "Điểm TB (G)",
						color: "<?php echo $mauall; ?>",
						markerType: "none",
						toolTipContent: null,
						dataPoints: [
						<?php
							$j=count($tb_G)-1;
							while($j>=0) {
								if(!is_numeric($tb_G[$j]["diemtb"])) {
									echo"{ label: '".$tb_G[$j]["datetime"]."', y: '".$tb_G[$j]["diemtb"]."'},";
								} else {
									echo"{ label: '".$tb_G[$j]["datetime"]."', y: ".$tb_G[$j]["diemtb"]."},";
								}
								$j--;
							}
						?>
						]
					},
					{
						indexLabelFontFamily:"helvetica" ,
						type: "splineArea",
						lineThickness: 0,
						showInLegend: false,
						name: "Điểm TB (Y)",
						color: "<?php echo $mauall; ?>",
						markerType: "none",
						toolTipContent: null,
						dataPoints: [
							<?php
							$j=count($tb_Y)-1;
							while($j>=0) {
								if(!is_numeric($tb_Y[$j]["diemtb"])) {
									echo"{ label: '".$tb_Y[$j]["datetime"]."', y: '".$tb_Y[$j]["diemtb"]."'},";
								} else {
									echo"{ label: '".$tb_Y[$j]["datetime"]."', y: ".$tb_Y[$j]["diemtb"]."},";
								}
								$j--;
							}
							?>
						]
					}
					]
				});
		
		chart8.render();
		}
		</script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/canvasjs.min.js"></script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php
				$maso=$now=NULL;
				$loai=0;
				if(isset($_GET["loc-ok"])) {
					if(isset($_GET["select-loc"])) {
						$loai=$_GET["select-loc"];
						if($loai==3 && isset($_GET["maso"])) {
							$maso=$_GET["maso"];
						}
					}
					
					if(isset($_GET["select-nam"])) {
						$now=$_GET["select-nam"];
					} else {
						$now=date("Y-m");
					}
					
					if($loai!=NULL) {
						if($loai==3) {
							header("location:https://localhost/www/TDUONG/bang-xep-hang/$loai/$maso/$now/");
						} else {
							header("location:https://localhost/www/TDUONG/bang-xep-hang/$loai/$now/");
						}
						exit();
					}
				}
				
				if(isset($_GET["loai"])) {
					$loai_loc=$_GET["loai"];
					if($loai_loc==3) {
						if(isset($_GET["ma"])) {
							$ma=$_GET["ma"];
						} else {
							$ma=0;
						}
					} else {
						$ma=0;
					}
				} else {
					$loai_loc=0;
					$ma=0;
				}
			?>
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">Bảng xếp hạng môn <?php echo $mon_name; ?></h1>
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
                
                <div class="main-div hideme animated animated2 bounceInUp">
                	<div class='main-1 back'><h3>Biểu đồ điểm thi trung bình môn <?php echo $mon_name; ?> qua từng tháng</h3></div>
                    <div id="main-chart">
                    	<div></div>
                    	<div style="width:100%;">
                            
                            <div class="chart-wap">
                                <div id="chartContainerTB" style="width:100%;margin-left:-25px;"></div>
                                <!--<div id="chartContainerTB" style="width:100%;"></div>-->
                            </div>
                       	</div>
                        <div id="chart-len">
                            <ul>
                            <?php
                                for($i=10;$i>=0;$i-=2) {
                                    echo"<li><span>$i điểm</span></li>";
                                }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="main-div animated bounceInUp">
                	<div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Điểm trung bình và xếp hạng tháng <?php echo format_month("$year-$month"); ?></h3></div>
                    <div id="main-table">
                    	<table>
                        	<tr class="back">
                            	<form action="https://localhost/www/TDUONG/bang-xep-hang/" method="get">
                                    <td><span>Lọc dữ liệu</span></td>
                                    <td colspan="2">
                                        <select class="submit" style="width:100%;height:auto;" id="select-loc" name="select-loc">
											<option value="4" <?php if($loai_loc==4){echo"selected='selected'";} ?>>Xếp hạng đề Y</option>
                                            <option value="1" <?php if($loai_loc==1){echo"selected='selected'";} ?>>Xếp hạng đề B</option>
                                            <option value="2" <?php if($loai_loc==2){echo"selected='selected'";} ?>>Xếp hạng đề G</option>
                                            <option value="3" <?php if($loai_loc==3){echo"selected='selected'";} ?>>Tìm theo mã số</option>
                                            <option value="0" <?php if($loai_loc==0){echo"selected='selected'";} ?>>Vị trí của tôi</option>
                                        </select>
                                    </td>
                                    <td class="loc3"><span>Nhập mã số</span></td>
                                    <td class="loc3"><input type="text" class="input" id="maso" name="maso" value="<?php if(isset($ma)){echo $ma;} ?>" /></td>
                                    <input type="hidden" value="<?php echo"$year-$month"; ?>" name="select-nam" />
                                    <td><input type="submit" class="submit" id="locdl" name="loc-ok" value="Lọc" /></td>
                               	</form>
                            </tr>
                        	<tr id="table-head" class="back tr-big">
                            	<th style="width:15%;"><span>STT</span></th>
                                <th style="width:25%;"><span>Tên học sinh</span></th>
                                <th style="width:15%;"><span>Mã số</span></th>
                                <th style="width:15%;"><span>Điểm TB</span></th>
                                <th style="width:15%;"><span>Thứ hạng</span></th>
                                <th style="width:15%;"><span></span></th>
                      		</tr>
                            <?php
								$dem=0;$stt=1;
								$next_CN=get_next_CN();
								$nextm=get_next_time($year,$month);
								if($loai_loc==2) {
									/*if($year==date("Y") && $month==date("m")) {
										$query="SELECT h.ID_HS,h.cmt,h.fullname,n.date,(SELECT AVG(d.diem) AS diem FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.diem!='X' WHERE b.ngay LIKE '$year-$month-%' AND d.ID_HS=h.ID_HS ORDER BY b.ID_BUOI DESC) AS diemtb FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='$monID' AND m.de='G' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='$monID' WHERE h.lop='$lopID' ORDER BY diemtb DESC";
									} else {*/
										$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' AND hocsinh_mon.date_in<'$nextm-01' INNER JOIN diemtb_thang ON diemtb_thang.ID_HS=hocsinh.ID_HS AND diemtb_thang.ID_LM='$lmID' AND diemtb_thang.detb='G' AND diemtb_thang.datetime='$year-$month' ORDER BY diemtb_thang.diemtb DESC,hocsinh.cmt ASC";
									//}
								} else if($loai_loc==3) {
									/*if($year==date("Y") && $month==date("m")) {
										$query="SELECT h.ID_HS,h.cmt,h.fullname,m.de,n.date,(SELECT AVG(d.diem) AS diem FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.diem!='X' WHERE b.ngay LIKE '$year-$month-%' AND d.ID_HS=h.ID_HS ORDER BY b.ID_BUOI DESC) AS diemtb FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='$monID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='$monID' WHERE h.cmt LIKE '%$ma%' AND h.lop='$lopID'";
									} else {*/
										$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' AND hocsinh_mon.date_in<'$nextm-01' INNER JOIN diemtb_thang ON diemtb_thang.ID_HS=hocsinh.ID_HS AND diemtb_thang.ID_LM='$lmID' AND diemtb_thang.datetime='$year-$month' WHERE hocsinh.cmt LIKE '%$ma%'";
									//}
								} else if($loai_loc==1) {
									/*if($year==date("Y") && $month==date("m")) {
										$query="SELECT h.ID_HS,h.cmt,h.fullname,n.date,(SELECT AVG(d.diem) AS diem FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.diem!='X' WHERE b.ngay LIKE '$year-$month-%' AND d.ID_HS=h.ID_HS ORDER BY b.ID_BUOI DESC) AS diemtb FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='$monID' AND m.de='B' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='$monID' WHERE h.lop='$lopID' ORDER BY diemtb DESC";
									} else {*/
										$query = "SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' AND hocsinh_mon.date_in<'$nextm-01' INNER JOIN diemtb_thang ON diemtb_thang.ID_HS=hocsinh.ID_HS AND diemtb_thang.ID_LM='$lmID' AND diemtb_thang.detb='B' AND diemtb_thang.datetime='$year-$month' ORDER BY diemtb_thang.diemtb DESC,hocsinh.cmt ASC";
									//}
								} else if($loai_loc==4) {
										$query = "SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' AND hocsinh_mon.date_in<'$nextm-01' INNER JOIN diemtb_thang ON diemtb_thang.ID_HS=hocsinh.ID_HS AND diemtb_thang.ID_LM='$lmID' AND diemtb_thang.detb='Y' AND diemtb_thang.datetime='$year-$month' ORDER BY diemtb_thang.diemtb DESC,hocsinh.cmt ASC";
								} else {
									/*if($year==date("Y") && $month==date("m")) {
										$query="SELECT h.ID_HS,h.cmt,h.fullname,n.date,(SELECT AVG(d.diem) AS diem FROM buoikt AS b INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.diem!='X' WHERE b.ngay LIKE '$year-$month-%' AND d.ID_HS=h.ID_HS ORDER BY b.ID_BUOI DESC) AS diemtb FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='$monID' AND m.de='$de' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_MON='$monID' WHERE h.lop='$lopID' ORDER BY diemtb DESC,h.cmt ASC";
									} else {*/
									    $de=get_de_thang($hsID,$lmID,"$year-$month");
										$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' AND hocsinh_mon.date_in<'$nextm-01' INNER JOIN diemtb_thang ON diemtb_thang.ID_HS=hocsinh.ID_HS AND diemtb_thang.detb='$de' AND diemtb_thang.ID_LM='$lmID' AND diemtb_thang.datetime='$year-$month' ORDER BY diemtb_thang.diemtb DESC,hocsinh.cmt ASC";
									//}
								}
								$result=mysqli_query($db,$query);
								while($data=mysqli_fetch_assoc($result)) {
								    if(isset($hs_nghi["hs-" . $data["ID_HS"]])) {
								        continue;
                                    }

//                                    $check=check_exited_thachdau($hsID, $data["ID_HS"], $next_CN, $lmID);
                                    if($hsID==$data["ID_HS"]) {
                                        echo"<tr id='tr-me' class='me-here'>
                                            <td><span>$stt</span></td>
                                            <td><span>$data[fullname]</span></td>
                                            <td><span>$data[cmt]</span></td>";
                                        if($loai_loc==2 || ($loai_loc==0 && $de=="G") || ($loai_loc==3 && $data["de"]=="G")) {
                                            echo"<td><span>".$hs_rankG[$data["ID_HS"]]["diemtb"]."</span></td>
                                            <td><span>".$hs_rankG[$data["ID_HS"]]["rank"]."</span></td>";
                                        } else if($loai_loc==1 || ($loai_loc==0 && $de=="B") || ($loai_loc==3 && $data["de"]=="B")) {
                                            echo"<td><span>".$hs_rankB[$data["ID_HS"]]["diemtb"]."</span></td>
                                            <td><span>".$hs_rankB[$data["ID_HS"]]["rank"]."</span></td>";
										} else if($loai_loc==4 || ($loai_loc==0 && $de=="Y") || ($loai_loc==3 && $data["de"]=="Y")) {
											echo"<td><span>".$hs_rankY[$data["ID_HS"]]["diemtb"]."</span></td>
                                            <td><span>".$hs_rankY[$data["ID_HS"]]["rank"]."</span></td>";
                                        } else {
                                            echo"<td><span></span></td>
                                            <td><span></span></td>";
                                        }
                                            echo"<td></td>
                                        </tr>";
                                        $dem++;
                                    } else {
                                        echo"<tr class='back'>
                                            <td><span>$stt</span></td>
                                            <td><span>$data[fullname]</span></td>
                                            <td><span>$data[cmt]</span></td>";
                                        if($loai_loc==2 || ($loai_loc==0 && $de=="G") || ($loai_loc==3 && $data["de"]=="G")) {
                                            echo"<td><span>".$hs_rankG[$data["ID_HS"]]["diemtb"]."</span></td>
                                            <td><span>".$hs_rankG[$data["ID_HS"]]["rank"]."</span></td>";
                                        } else if($loai_loc==1 || ($loai_loc==0 && $de=="B") || ($loai_loc==3 && $data["de"]=="B")) {
                                            echo"<td><span>".$hs_rankB[$data["ID_HS"]]["diemtb"]."</span></td>
                                            <td><span>".$hs_rankB[$data["ID_HS"]]["rank"]."</span></td>";
										} else if($loai_loc==4 || ($loai_loc==0 && $de=="Y") || ($loai_loc==3 && $data["de"]=="Y")) {
											echo"<td><span>".$hs_rankY[$data["ID_HS"]]["diemtb"]."</span></td>
                                            <td><span>".$hs_rankY[$data["ID_HS"]]["rank"]."</span></td>";
                                        } else {
                                            echo"<td><span></span></td>
                                            <td><span></span></td>";
                                        }
                                            echo"<td>";
//                                            if($check) {
//                                                echo"<span>Đang thách đấu</span>";
//                                            } else {
                                                if(!$check0) {
                                                    echo"<button class='submit thachdau' data-maso='".base64_encode($data["cmt"])."'>Thách đấu</button>";
                                                }
//                                            }
                                            echo"</td>
                                        </tr>";
                                    }
                                    $stt++;
                                    if($dem!=0) {
                                        $dem++;
                                    }
                                    if($dem==7 && $loai_loc==0) {
                                        break;
                                    }
								}
								if($dem==0 && $loai_loc==0) {
									echo"<tr class='back'><td colspan='6'><span>Chưa được cập nhật</span></td></tr>";
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
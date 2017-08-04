<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
    require_once("access_hocsinh.php");
//	require_once("model/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	$de=$_SESSION["de"];
	
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID, $lmID);
	$data0=mysqli_fetch_assoc($result0);

//	$mon_name=get_mon_name($monID);
	
	$month=date("m");
	$year=date("Y");

    $now=date_create(date("Y-m-d"));
    date_add($now, date_interval_create_from_date_string("-120 days"));
    $now=date_format($now,"Y-m-d");
    $date_in0=$now;

    $lido=array();
    $result5=get_all_lido2();
    while($data5=mysqli_fetch_assoc($result5)) {
        $lido[$data5["ID_LD"]]=$data5["name"];
    }

    $conG=$conB=$conY=$contbG=$contbB=$contbY="";
    $buoi_all = array();
    $dem=0;

	$diem_B=array();
	$diem_G=array();
    $diem_Y=array();
	$diem_tbB=array();
	$diem_tbG=array();
    $diem_tbY=array();
//    $query="SELECT b.ID_BUOI,b.ngay,d.ID_DIEM,d.diem,d.de,d.loai,d.note,t.diemtb,t.detb,o.ID_O FROM buoikt AS b
//    LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.ID_LM='$lmID'
//    LEFT JOIN diemkt_tb AS t ON t.ID_BUOI=b.ID_BUOI AND t.ID_LM='$lmID' AND t.detb=d.de
//    LEFT JOIN options AS o ON o.content='$hsID' AND o.type='khong-lay-bai' AND o.note=b.ID_BUOI AND o.note2='$lmID'
//    WHERE b.ngay>'$date_in0' AND b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC";
    $query="SELECT b.ID_BUOI,b.ngay,d.ID_DIEM,d.diem,d.de,d.loai,d.note,d.more,t.diemtb,t.detb FROM buoikt AS b 
    LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.ID_LM='$lmID'
    LEFT JOIN diemkt_tb AS t ON t.ID_BUOI=b.ID_BUOI AND t.ID_LM='$lmID' AND t.detb=d.de
    WHERE b.ngay>'$date_in0' AND b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC";
	$result=mysqli_query($db,$query);
	$diemID=0;$buoiID=0;
	while($data=mysqli_fetch_assoc($result)) {
	    if($dem==0 || (isset($buoi_all[$dem-1]) && $buoi_all[$dem-1]["buoiID"] != $data["ID_BUOI"])) {
            $buoi_all[$dem] = array(
                "buoiID" => $data["ID_BUOI"],
                "ngay" => $data["ngay"]
            );
        }
	    $ngay = format_date($data["ngay"]);
        $diemB=$diemG=$diemY=$diemtbG=$diemtbB=$diemtbY="";
        $loai=$note=$more=0;
		if($data["de"]=="B") {
			if($diemID!=$data["ID_DIEM"]) {
//			    if(isset($data["ID_O"])) {
//                    $diemB = 0;
//			    } else {
                    $diemB = $data["diem"];
//                }
                $loai = $data["loai"];
                $note = $data["note"];
                $more = $data["more"];
                $diemG = $diemY = "X";
			}
			if($data["detb"]=="B") {
			    $diemtbB = $data["diemtb"];
                $diemtbG = $diemtbY = "X";
			}
		} else if($data["de"]=="G") {
			if($diemID!=$data["ID_DIEM"]) {
//                if(isset($data["ID_O"])) {
//                    $diemG = 0;
//                } else {
                    $diemG = $data["diem"];
//                }
                $loai = $data["loai"];
                $note = $data["note"];
                $more = $data["more"];
				$diemB = $diemY = "X";
			}
			if($data["detb"]=="G") {
                $diemtbG = $data["diemtb"];
                $diemtbB = $diemtbY = "X";
			}
        } else if($data["de"]=="Y") {
            if($diemID!=$data["ID_DIEM"]) {
//                if(isset($data["ID_O"])) {
//                    $diemG = 0;
//                } else {
                $diemY = $data["diem"];
//                }
                $loai = $data["loai"];
                $note = $data["note"];
                $more = $data["more"];
                $diemB = $diemG = "X";
            }
            if($data["detb"]=="Y") {
                $diemtbY = $data["diemtb"];
                $diemtbB = $diemtbG = "X";
            }
        } else {
			if($buoiID!=$data["ID_BUOI"]) {
                $diemB=$diemG=$diemY=$diemtbG=$diemtbB=$diemtbY="X";
			}
		}
		$diemID=$data["ID_DIEM"];
		$buoiID=$data["ID_BUOI"];

        if($diemB != "") {
            if (!is_numeric($diemB)) {
                $conB.= "{ label: '$ngay', y: '$diemB', indexLabel: ''},";
            } else {
                $cmt_diem = get_cmt_diem_loai($loai);
                if ($cmt_diem != "huy") {
                    $conB.= "{ label: '$ngay', y: $diemB, indexLabel: '{y} (B)', toolTipContent: '<span style=\'font-style:normal\'><span style=\'font-size:10px\'>&#9899;</span> Điểm thi: {y}<br /><span style=\'font-size:10px\'>&#9899;</span> Đề trung bình (B)" . $cmt_diem . "<br /><span style=\'font-size:10px\'>&#9899;</span> Tương đương ".get_diem_dh($diemB,"B")."đ ĐH</span>'},";
                } else {
                    $conB.= "{ label: '$ngay', y: $diemB, indexLabel: '{y} (B)', toolTipContent: '<span style=\'font-style:normal\'><span style=\'font-size:10px\'>&#9899;</span> Điểm thi: {y}<br /><span style=\'font-size:10px\'>&#9899;</span> Đề trung bình (B)<br /><span style=\'font-size:10px\'>&#9899;</span> Hủy bài do " . $lido[$note] . "<br /><span style=\'font-size:10px\'>&#9899;</span> Tương đương ".get_diem_dh($diemB,"B")."đ ĐH</span>'},";
                }
            }
        }

        if($diemG != "") {
            if (!is_numeric($diemG)) {
                $conG.= "{ label: '$ngay', y: '$diemG', indexLabel: ''},";
            } else {
                $cmt_diem = get_cmt_diem_loai($loai);
                if ($cmt_diem != "huy") {
                    $conG.= "{ label: '$ngay', y: $diemG, indexLabel: '{y} (G)', toolTipContent: '<span style=\'font-style:normal\'><span style=\'font-size:10px\'>&#9899;</span> Điểm thi: {y}<br /><span style=\'font-size:10px\'>&#9899;</span> Đề khá giỏi (G)" . $cmt_diem . "</span>'},";
                } else {
                    $conG.= "{ label: '$ngay', y: $diemG, indexLabel: '{y} (G)', toolTipContent: '<span style=\'font-style:normal\'><span style=\'font-size:10px\'>&#9899;</span> Điểm thi: {y}<br /><span style=\'font-size:10px\'>&#9899;</span> Đề khá giỏi (G)<br /><span style=\'font-size:10px\'>&#9899;</span> Hủy bài do " . $lido[$note] . "</span>'},";
                }
            }
        }

        if($diemY != "") {
            if (!is_numeric($diemY)) {
                $conY.= "{ label: '$ngay', y: '$diemY', indexLabel: ''},";
            } else {
                $cmt_diem = get_cmt_diem_loai($loai);
                if ($cmt_diem != "huy") {
                    $conY.= "{ label: '$ngay', y: $diemY, indexLabel: '{y} (Y)', toolTipContent: '<span style=\'font-style:normal\'><span style=\'font-size:10px\'>&#9899;</span> Điểm thi: {y}<br /><span style=\'font-size:10px\'>&#9899;</span> Đề kém (Y)" . $cmt_diem . "<br /><span style=\'font-size:10px\'>&#9899;</span> Tương đương ".get_diem_dh($diemY,"Y")."đ ĐH</span>'},";
                } else {
                    $conY.= "{ label: '$ngay', y: $diemY, indexLabel: '{y} (Y)', toolTipContent: '<span style=\'font-style:normal\'><span style=\'font-size:10px\'>&#9899;</span> Điểm thi: {y}<br /><span style=\'font-size:10px\'>&#9899;</span> Đề kém (Y)<br /><span style=\'font-size:10px\'>&#9899;</span> Hủy bài do " . $lido[$note] . "<br /><span style=\'font-size:10px\'>&#9899;</span> Tương đương ".get_diem_dh($diemY,"Y")."đ ĐH</span>'},";
                }
            }
        }

        if(stripos($contbB,$ngay) === false) {
            if($diemtbB != "") {
                if(!is_numeric($diemtbB)) {
                    $contbB.="{ label: '$ngay', y: '$diemtbB'},\n";
                } else {
                    $contbB.="{ label: '$ngay', y: $diemtbB},\n";
                }
            } else {
                $contbB .= "{ label: '$ngay', y: 'X'},\n";
            }
        }

        if(stripos($contbG,$ngay) === false) {
            if($diemtbG != "") {
                if(!is_numeric($diemtbG)) {
                    $contbG.="{ label: '$ngay', y: '$diemtbG'},\n";
                } else {
                    $contbG.="{ label: '$ngay', y: $diemtbG},\n";
                }
            } else {
                $contbG .= "{ label: '$ngay', y: 'X'},\n";
            }
        }

        if(stripos($contbY,$ngay) === false) {
            if($diemtbY != "") {
                if(!is_numeric($diemtbY)) {
                    $contbY.="{ label: '$ngay', y: '$diemtbY'},\n";
                } else {
                    $contbY.="{ label: '$ngay', y: $diemtbY},\n";
                }
            } else {
                $contbY .= "{ label: '$ngay', y: 'X'},\n";
            }
        }

        $dem++;
	}

	$dem_buoi=count($buoi_all);

    if(!isset($_SESSION["count-hoc-tinh"])) {
        $temp = count_hoc_tinh($hsID, $lmID);
        $_SESSION["count-hoc-tinh"] = $temp;
    } else {
        $temp = $_SESSION["count-hoc-tinh"];
    }
    $co_hoc=$temp[0];
    $tinh_dung=$temp[1];
    $dem=$temp[2];
	if($dem!=0) {
		$tinh=($tinh_dung/$dem)*100;
		$hoc=($co_hoc/$dem)*100;
	} else {
		$tinh=0;
		$hoc=0;
	}
	
//	$thang=count_thachdau_win($hsID, $lmID);
//	$thua=count_thachdau_lose($hsID, $lmID);
//
//	$thang_ns=count_ngoisao_win($hsID, $lmID);
//	$thua_ns=count_ngoisao_lose($hsID, $lmID);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title><?php echo mb_strtoupper($data0["fullname"]); ?></title>
        
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
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:36%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:30px;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:22px;}table tr:last-child td:first-child,table tr:last-child td:last-child {border-radius:0;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="https://localhost/www/TDUONG/js/iscroll.js"></script>
        <script>
		
			/*var myScroll,myScroll2;
			function loaded () {
				myScroll = new IScroll('#main-table', { scrollX: true, scrollY: false, mouseWheel: false, startX: -<?php if($dem_buoi*95>945){echo ($dem_buoi*95-945);}else{echo 0;} ?> });
			}*/
		
			$(document).ready(function() {
				$("#MAIN .main-div .main-has").hover(function() {
					$(this).find("div.chart").css("opacity","0.15");
					$(this).find("p.detail").stop().animate({top:"35%",opacity:"1"},250);
				},function() {
					$(this).find("p.detail").stop().animate({top:"55%",opacity:"0"},250);
					$(this).find("div.chart").css("opacity","1");
				});
				
				$("#lichsu-tinh").click(function() {
                    $(".popup").fadeOut("fast");
					$("#BODY").css("opacity", "0.1");
					$.ajax({
						async: true,
						data: "action=lichsu-tinh",
						type: "post",
						url: "https://localhost/www/TDUONG/xuly-tongquan/",
						success: function(result) {
							$("#lichsu p.title").html(result);
							$("#lichsu").fadeIn("fast");
						}
					})
				});
				
				$("#lichsu-hoc").click(function() {
                    $(".popup").fadeOut("fast");
					$("#BODY").css("opacity", "0.1");
					$.ajax({
						async: true,
						data: "action=lichsu-hoc",
						type: "post",
						url: "https://localhost/www/TDUONG/xuly-tongquan/",
						success: function(result) {
							$("#lichsu p.title").html(result);
							$("#lichsu").fadeIn("fast");
						}
					})
				});

                setTimeout(function () {
                    $.ajax({
                        async: true,
                        data: "dulieu=lichhoc",
                        type: "post",
                        url: "https://localhost/www/TDUONG/xuly-tongquan/",
                        success: function(result) {
                            $("#main-person").append(result).removeAttr("style");
                            $.ajax({
                                async: true,
                                data: "date_in=<?php echo $data0["date_in"] ?>&date_start=<?php echo $date_in0; ?>",
                                type: "post",
                                url: "https://localhost/www/TDUONG/xuly-tongquan/",
                                success: function(result) {
                                    $("tr#table-point").html(result);
                                    $.ajax({
                                        async: true,
                                        data: "dulieu=thongbaoall",
                                        type: "post",
                                        url: "https://localhost/www/TDUONG/xuly-tongquan/",
                                        success: function(result) {
                                            $(result).insertBefore("#MAIN #main-top");
                                        }
                                    });
                                }
                            });
                        }
                    });
                },1000);
				
				$(".btn-exit, .popup .popup-close, .popup").click(function() {
					$(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
				});

                $("td#point-first").css("height",($("tr#table-point").height()-20) + "px");
			});
		</script>
        <script type="text/javascript">

            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);

			window.onload = function () {
                var myScroll = new IScroll('#main-table', { scrollX: true, scrollY: false, mouseWheel: false, startX: -<?php if($dem_buoi*95>945){echo ($dem_buoi*95-945);}else{echo 0;} ?> });

                var chart3 = new CanvasJS.Chart("chartContainer",
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
						interval: 1,
						//intervalType: "day",
						labelFontColor: "<?php echo $mau?>",
						labelFontSize: 14,
						//valueFormatString: "DD/MM", 
						labelFontWeight: "normal",
					},
					interactivityEnabled: true,
                    dataPointWidth: 37,
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
					data: [
					{        
						indexLabelFontFamily:"helvetica" ,
						type: "spline",
						showInLegend: false,
						indexLabelFontColor: "<?php echo $mau;?>",
						indexLabelFontWeight: "normal",
						indexLabelFontSize: 14,
						lineThickness: 2,
						name: "Điểm thi (B)",
						markerSize: 10,
						markerType: "circle",
						color: "rgba(255,255,255,1)",
						//fillOpacity: "0.2",
						//toolTipContent: "<span>+ Điểm thi: {y}<br />+ Đề trung bình (B)</span>", 
						dataPoints: [
						<?php
							echo $conB;
						?>
						]
					},
					{        
						indexLabelFontFamily:"helvetica" ,
						type: "spline",
						showInLegend: false,
						indexLabelFontColor: "<?php echo $mau;?>",
						indexLabelFontWeight: "normal",
						indexLabelFontSize: 14,
						lineThickness: 2,
						markerSize: 10,
						name: "Điểm thi (G)",
						markerType: "circle",
						color: "rgba(255,255,255,1)",
						//fillOpacity: "0.2",
						//toolTipContent: "<span>Điểm thi: {y}<br />Đề: G</span>",
						dataPoints: [
						<?php
							echo $conG;
						?>
						]
					},
                    {
                        indexLabelFontFamily:"helvetica" ,
                        type: "spline",
                        showInLegend: false,
                        indexLabelFontColor: "<?php echo $mau;?>",
                        indexLabelFontWeight: "normal",
                        indexLabelFontSize: 14,
                        lineThickness: 2,
                        markerSize: 10,
                        name: "Điểm thi (Y)",
                        markerType: "circle",
                        color: "rgba(255,255,255,1)",
                        //fillOpacity: "0.2",
                        //toolTipContent: "<span>Điểm thi: {y}<br />Đề: G</span>",
                        dataPoints: [
                            <?php
                            echo $conY;
                            ?>
                        ]
                    },
					{        
						indexLabelFontFamily:"helvetica" ,
						type: "splineArea",
						lineThickness: 0,
						showInLegend: false,
						name: "Điểm TB (B)",
						color: "rgba(255,255,255,0.35)",
						toolTipContent: null,
						markerType: "none",
						dataPoints: [
						<?php
							echo $contbB;
						?>
						]
					},
					{        
						indexLabelFontFamily:"helvetica" ,
						type: "splineArea",
						lineThickness: 0,
						showInLegend: false,
						name: "Điểm TB (G)",
						color: "rgba(255,255,255,0.35)",
						toolTipContent: null,
						markerType: "none",
						dataPoints: [
						<?php
							echo $contbG;
						?>
						]
					},
                    {
                        indexLabelFontFamily:"helvetica" ,
                        type: "splineArea",
                        lineThickness: 0,
                        showInLegend: false,
                        name: "Điểm TB (Y)",
                        color: "rgba(255,255,255,0.35)",
                        toolTipContent: null,
                        markerType: "none",
                        dataPoints: [
                            <?php
                            echo $contbY;
                            ?>
                        ]
                    }
					]
				});
		
		chart3.render();
		var chart5 = new CanvasJS.Chart("chartContainerKynang",
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
					legendText: "{name}",
					indexLabelFontFamily:"helvetica" ,
					dataPoints: [
						{  y: <?php echo $tinh; ?>, color: "<?php echo $mau;?>", name: "TÍNH ĐÚNG"},
						{  y: <?php echo (100-$tinh); ?>, color: "rgba(255,255,255,0.15)",name: ""  },
					]
				}
				]
			});
			chart5.render();
			
			var chart6 = new CanvasJS.Chart("chartContainerTile",
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
					legendText: "{name}",
					indexLabelFontFamily:"helvetica" ,
					dataPoints: [
						{  y: <?php echo $hoc; ?> , color: "<?php echo $mau;?>", name: "HỌC BÀI"},
						{  y: <?php echo (100-$hoc); ?>, color: "rgba(255,255,255,0.15)", name: "" },
					]
				}
				]
			});
			chart6.render();
			
			<?php
				$buoi_arr="";
                $i=1;
                $dem=count($buoi_all)-1;
                while($i < 10 && $dem>=0) {
                    $buoi_arr.=",'".$buoi_all[$dem]["buoiID"]."'";
                    $i++;
                    $dem--;
                }
				$buoi_arr=substr($buoi_arr,1);
				$height=$cd_number=$cd_arr=array();
				$count=0;
				$result=get_chuyende_dad2($lmID);
				while($data=mysqli_fetch_assoc($result)) {
				    $cd_arr[] = $data["title"];
					$diem_info=array();
                    $cd_count=0;
                    $temp_cd=0;
                    if(!isset($_SESSION["chuyende-".$data["ID_CD"]])) {
                        $query5 = "SELECT c.ID_CD,c.title,d.diem FROM chuyende AS c
                        INNER JOIN chuyende_diem AS d ON d.ID_LM=c.ID_LM AND d.ID_BUOI IN ($buoi_arr) AND d.ID_CD=c.ID_CD AND d.ID_HS='$hsID' AND d.diem NOT LIKE 'X/%' AND d.diem NOT lIKE '%/0'
                        WHERE c.ID_LM='$lmID' AND c.dad='$data[ID_CD]' AND c.del='1'
                        ORDER BY c.ID_CD DESC,d.ID_STT ASC";
                        $result5 = mysqli_query($db, $query5);
                        $num = mysqli_num_rows($result5);
                        $total = 0;
                        $dem = 0;
                        $num_count = 0;
                        $me = $temp_name = $temp_diem = "";
                        while ($num >= 0) {
							$data5 = mysqli_fetch_assoc($result5);
                            if ($temp_cd != 0 && $temp_cd != $data5["ID_CD"]) {
								if($num_count == 0) {
									$temp = explode("/", $data5["diem"]);
									$total += $temp[0];
									$dem += $temp[1];
								}
								if($num_count == 0 && $num == 0) {
									$temp = explode("/", $temp_diem);
									$total += $temp[0];
									$dem += $temp[1];
								}
                                if ($dem == 0) {
                                    $diemtb = 0;
                                } else {
                                    $diemtb = ($total / $dem) * 100;
                                }
                                $indexLabel = format_phantram($diemtb);
                                $me .= "{y: " . $diemtb . ", label: '" . mb_strtoupper($temp_name) . "', indexLabel: '" . $indexLabel . "'},";
                                $diem_info[] = array(
                                    "diemtb" => $diemtb
                                );
                                $cd_count++;
                                $total = 0;
                                $dem = 0;
								$num_count = 0;
                            } else {
                                $temp = explode("/", $data5["diem"]);
								if(count($temp) == 2) {
                                	$total += $temp[0];
                                	$dem += $temp[1];
									$num_count++;
								}
                            }
                            $temp_cd = $data5["ID_CD"];
                            $temp_name = $data5["title"];
							$temp_diem = $data5["diem"]; 
                            $num--;
                        }
                        $_SESSION["chuyende-".$data["ID_CD"]]["content"] = $me;
                        $_SESSION["chuyende-".$data["ID_CD"]]["cd_count"] = $cd_count;
                        $_SESSION["chuyende-".$data["ID_CD"]]["diem_info"] = $diem_info;
                    } else {
                        $me = $_SESSION["chuyende-".$data["ID_CD"]]["content"];
                        $cd_count = $_SESSION["chuyende-".$data["ID_CD"]]["cd_count"];
                        $diem_info = $_SESSION["chuyende-".$data["ID_CD"]]["diem_info"];
                    }
					echo"var chart".$count." = new CanvasJS.Chart('chartContainer".$count."',
					{
						backgroundColor: '',
						axisY:{
							maximum: 110,
							interval: 25,
							labelFontColor: '$mau',
							labelFontSize: 14,
							labelFontWeight: 'normal',
							labelFontFamily:'helvetica' ,
							tickColor: '#D0AA86',
							gridThickness: 1,
							tickThickness: 0,
							lineColor: 'rgba(255,255,255,0.15)',
							gridColor: 'rgba(255,255,255,0.15)',
						},
						theme: 'theme2',
						interactivityEnabled: false,
						axisX:{
							labelFontColor: '$mau',
							labelFontSize: 14,
							labelFontWeight: 'normal',
							labelFontFamily:'helvetica' ,
							labelMaxWidth: 400,";
							if($cd_count>1) {
								echo"interval:1,";
							}
							echo"gridColor: 'rgba(255,255,255,0.15)',
							tickColor: '#D0AA86',
							tickThickness: 0,
							lineThickness: 1,
							lineColor: 'rgba(255,255,255,0.15)',
						},
						animationEnabled: true,
						dataPointWidth: 25,
						toolTip:{
							enabled: false
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
							color: '$mau',
							indexLabelFontColor: '$mau',
							indexLabelPlacement: 'outside',
							indexLabelOrientation: 'horizontal',
							indexLabelFontFamily:'helvetica' ,
							indexLabelFontSize: 14,
							indexLabelFontWeight: 'normal',
							dataPoints: [";
								echo $me;
								$height[]=count($diem_info)*35;
							echo"]
						}
						]
					});
					chart".$count.".render();";
					$cd_number[]=$cd_count;
					$count++;
				}
			?>
	
//	            var chart9 = new CanvasJS.Chart("chartContainerTD",
//				{
//					theme: "theme2",
//					toolTip:{
//						enabled: false
//					},
//					backgroundColor: "",
//					animationEnabled: true,
//					interactivityEnabled: false,
//					data: [
//					{
//						type: "doughnut",
//						startAngle: -90,
//						innerRadius: "75%",
//						showInLegend: false,
//						legendText: "{name}",
//						indexLabelFontFamily:"helvetica" ,
//						indexLabelFontWeight: "normal",
//						dataPoints: [
//						<?php
//							if(($thang+$thua)!=0) {
//						?>
//							{  y: <?php //echo ($thang/($thang+$thua))*100;?>//, color: "<?php //echo $mau;?>//"},
//							{  y: <?php //echo ($thua/($thang+$thua))*100;?>//, color: "rgba(255,255,255,0.15)"},
//						<?php //} else { ?>
//							{  y: 0, color: "<?php //echo $mau;?>//"},
//							{  y: 100, color: "rgba(255,255,255,0.15)"},
//						<?php //} ?>
//						]
//					}
//					]
//				});
//				chart9.render();
//
//				var chart10 = new CanvasJS.Chart("chartContainerNgoiSao",
//				{
//					theme: "theme2",
//					toolTip:{
//						enabled: false
//					},
//					backgroundColor: "",
//					animationEnabled: true,
//					interactivityEnabled: false,
//					data: [
//					{
//						type: "doughnut",
//						startAngle: -90,
//						innerRadius: "75%",
//						showInLegend: false,
//						legendText: "{name}",
//						indexLabelFontFamily:"helvetica" ,
//						indexLabelFontWeight: "normal",
//						dataPoints: [
//						<?php
//							if(($thang_ns+$thua_ns)!=0) {
//						?>
//							{  y: <?php //echo ($thang_ns/($thang_ns+$thua_ns))*100;?>//, color: "<?php //echo $mau;?>//"},
//							{  y: <?php //echo ($thua_ns/($thang_ns+$thua_ns))*100;?>//, color: "rgba(255,255,255,0.15)"},
//						<?php //} else { ?>
//							{  y: 0, color: "<?php //echo $mau;?>//"},
//							{  y: 100, color: "rgba(255,255,255,0.15)"},
//						<?php //} ?>
//						]
//					}
//					]
//				});
//				chart10.render();
				
				var chart11 = new CanvasJS.Chart("chartContainerDihoc",
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
						legendText: "{name}",
						indexLabelFontFamily:"helvetica" ,
						indexLabelFontWeight: "normal",
						dataPoints: [
						<?php
                            if(stripos($data0["date_in"],"2016-06-")===false) {
                                $date_in=$data0["date_in"];
                            } else {
                                $date_in="2016-07-04";
                            }
                            if(!isset($_SESSION["di-hoc"])) {
                                $temp = count_di_hoc($hsID, $date_in, null, $lmID, $monID);
                                $_SESSION["di-hoc"] = $temp;
                            } else {
                                $temp = $_SESSION["di-hoc"];
                            }
                            $tong_hoc=$temp[0];
                            $di_hoc=$temp[1];
							if($tong_hoc!=0) {
						?>
							{  y: <?php echo ($di_hoc/$tong_hoc)*100;?>, color: "<?php echo $mau;?>"},
							{  y: <?php echo 100-($di_hoc/$tong_hoc)*100;?>, color: "rgba(255,255,255,0.15)"},
						<?php
							} else {
						?>
							{  y: 0, color: "<?php echo $mau;?>"},
							{  y: 100, color: "rgba(255,255,255,0.15)"},
						<?php	
							}
						?>
						]
					}
					]
				});
				chart11.render();
		}
		</script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/canvasjs.min.js"></script>
       
	</head>

    <body>
    
    	<div class="popup animated bounceIn" id="lichsu">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
            	<p class="title"></p>
           		<div>
                    <button class="submit2 btn-exit"><i class="fa fa-check"></i></button>
                </div>
            </div>
        </div>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>                         
        
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person" style="height: 104px;">
                		<h1><?php echo $data0["fullname"];?></h1>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                    	<a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                </div>
                
                <div class="main-div animated animated2 bounceInUp">
                	<div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Biểu đồ điểm thi qua từng tuần (4 tháng)</h3></div>
                    <div id="main-table" class="main-chartx">
                    	<table style="position:fixed;left:0;width:7%;">
                        	<tr class="back">
                            	<th style="width:7%;min-width:7%;"><span>NGÀY</span></th>
                            </tr>
                            <tr class="back">
                            	<td id="point-first"><span>ĐIỂM</span></td>
                            </tr>
                        </table>
                        <div style="width:<?php echo ($dem_buoi*95);?>px">
                            <table>
                                <tr id="table-head" class="back">
                                    <!--<th style="position:fixed;left:0;background:rgba(0,0,0,0.15);width:7%;min-width:7%;"><span>NGÀY</span></th>-->
                                <?php
                                    for($i=0;$i<count($buoi_all);$i++) {
                                        echo"<th style='min-width: 60px;width: 60px;'><span>".format_date($buoi_all[$i]["ngay"])."</span></th>";
                                    }
                                ?>
                                </tr>
                                <tr id="table-point" class="back">
                                    <!--<td style="position:fixed;left:0;background:rgba(0,0,0,0.15);width:7%;"><span>ĐIỂM</span></td>-->
                                </tr>
                            </table>
                        <!--</div>
                        <div id="main-chart">-->
                            <div class="chart-wap">
                                <div id="chartContainer" style="width:<?php echo ($dem_buoi*95+25);?>px;margin-left:-25px;"></div>
                                <!--<div id="chartContainer" style="width:100%;"></div>-->
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
                
                <div class="main-div hideme animated bounceInUp">
                	<ul>
                    	<li class="li-5">
                        	<div class="main-2 back"><h3 style="font-size:14px;">Tỉ lệ tính toán đúng</h3></div>
                            <div class="main-chart4 main-has">
                            <?php 
								$tinh=format_phantram($tinh);
								echo"<div class='chart-info'><p>$tinh</p></div>";
							?>
                            	<p class="detail" id="lichsu-tinh" style="cursor:pointer">Chi tiết</p>
                            	<div class="chart" id="chartContainerKynang"></div>
                            </div>
                        </li>
                        <li class="li-5">
                        	<div class="main-2 back"><h3 style="font-size:14px;">Tỉ lệ ôn bài ở nhà</h3></div>
                            <div class="main-chart4 main-has">
                            <?php
								$hoc=format_phantram($hoc);
								echo"<div class='chart-info'><p>$hoc</p></div>";
                           	?>
                            	<p class="detail" id="lichsu-hoc" style="cursor:pointer">Chi tiết</p>
                            	<div class="chart" id="chartContainerTile"></div>
                            </div>
                        </li>
                        <li class="li-5"><a href="https://localhost/www/TDUONG/buoi-hoc/">
                        	<div class="main-2 back"><h3 style="font-size:14px;">Tỉ lệ đi học đầy đủ</h3></div>
                            <div class="main-chart4 main-has">
                            <?php
								if($tong_hoc!=0) {
									$dihoc=format_phantram(($di_hoc/$tong_hoc)*100);
								} else {
									$dihoc="0%";
								}
								echo"<div class='chart-info'><p>$dihoc</p></div>";
                           	?>
                            	<p class="detail">Chi tiết</p>
                            	<div class="chart" id="chartContainerDihoc"></div>
                            </div>
                        </a></li>
<!--                        <li class="li-5"><a href="https://localhost/www/TDUONG/thach-dau/">-->
<!--                        	<div class="main-2 back"><h3 style="font-size:14px;">Thắng thách đấu</h3></div>-->
<!--                            <div class="main-chart4 main-has">-->
<!--                            --><?php
//								if(($thang+$thua) !=0) {
//									$td=format_phantram(($thang/($thang+$thua))*100);
//								} else {
//									$td="0%";
//								}
//								echo"<div class='chart-info'><p>$td</p></div>";
//                           	?>
<!--                            	<p class="detail">Chi tiết</p>-->
<!--                            	<div class="chart" id="chartContainerTD"></div>-->
<!--                            </div>-->
<!--                        </a></li>-->
<!--                        <li class="li-5"><a href="https://localhost/www/TDUONG/ngoi-sao-hy-vong/">-->
<!--                        	<div class="main-2 back"><h3 style="font-size:14px;">Thắng <span class="fa fa-star"></span> hy vọng</h3></div>-->
<!--                            <div class="main-chart4 main-has">-->
<!--                            --><?php
//								if(($thang_ns+$thua_ns) !=0) {
//									$ns=format_phantram(($thang_ns/($thang_ns+$thua_ns))*100);
//								} else {
//									$ns="0%";
//								}
//								echo"<div class='chart-info'><p>$ns</p></div>";
//                           	?>
<!--                            	<p class="detail">Chi tiết</p>-->
<!--                            	<div class="chart" id="chartContainerNgoiSao"></div>-->
<!--                            </div>-->
<!--                        </a></li>-->
                    </ul>
                    <div class="clear"></div>
                </div>
                
                <?php
                /*<div class="main-div hideme animated animated2 bounceInUp"><a href="https://localhost/www/TDUONG/bang-xep-hang/">
                	<div class='main-1 back'><h3>Biểu đồ điểm thi trung bình môn <?php echo $mon_name; ?> qua từng tháng</h3></div>
                    <div id="main-chart">
                    	<div></div>
                    	<div style="width:<?php echo ($dem_tbt*95);?>px">
                            
                            <div class="chart-wap">
                                <div id="chartContainerTB" style="width:<?php echo ($dem_tbt*95+25);?>px;margin-left:-25px;"></div>
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
                </a></div>*/
				?>
                
                <?php
					for($i=0;$i<count($cd_arr);$i++) {
//                        https://localhost/www/TDUONG/thong-ke-chuyen-de/".unicode_convert($data["title"])."-$data[ID_CD].html
						echo"<div class='main-div hideme animated bounceInUp'><a href='javascript:void(0)'>
						<div class='main-1 back'><h3>Tỉ lệ phần trăm kiến thức nắm được  - phần ".$cd_arr[$i]."</h3></div>
						<div class='main-chart3' style='height:".($height[$i]+35)."px;'>
							<div id='chartContainer".$i."'></div>
							<nav class='ask' style='display:none;'>
								<i class='fa fa-question-circle' style='color:$mau'></i>
								<div class='sub-ask'>
									<ul>
										<li style='margin-bottom:0;'><span>Click vào biểu đồ để xem chi tiết!</span></li>
									</ul>
                            	</div>
							</nav>";
							//echo"<nav class='chart-button back'><a href='https://localhost/www/TDUONG/thong-ke-chuyen-de/".unicode_convert($data["title"])."-$data[ID_CD].html'>Xem chi tiết</a></nav>
						echo"</div>
					</a></div>";
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
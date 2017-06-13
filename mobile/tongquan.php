<?php
	ob_start();
	//session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("include/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	$de=$_SESSION["de"];
	
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID, $lmID);
	$data0=mysqli_fetch_assoc($result0);
	
	$month=date("m");
	$year=date("Y");

    $now=date_create(date("Y-m-d"));
    date_add($now, date_interval_create_from_date_string("-120 days"));
    $now=date_format($now,"Y-m-d");
    $date_in0=$now;
    $dem_buoi=count_buoi_kt_hs($date_in0,$monID);
    if($dem_buoi<10) {
        $dem_begin=0;
    } else {
        $dem_begin=$dem_buoi-10;
    }

    $lido=array();
    $result5=get_all_lido2();
    while($data5=mysqli_fetch_assoc($result5)) {
        $lido[$data5["ID_LD"]]=$data5["name"];
    }

    $conG=$conB=$conY=$contbG=$contbB=$contbY="";

	$diem_B=array();
	$diem_G=array();
    $diem_Y=array();
    $dodai=0;
    $diemID=0;$dem=0;
//    $query="SELECT b.ngay,d.ID_DIEM,d.diem,d.de,d.loai,d.note,o.ID_O FROM buoikt AS b
//    LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.ID_LM='$lmID'
//    LEFT JOIN options AS o ON o.content='$hsID' AND o.type='khong-lay-bai' AND o.note=b.ID_BUOI AND o.note2='$lmID'
//    WHERE b.ngay>'$date_in0' AND b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC LIMIT $dem_begin,10";
    $query="SELECT b.ngay,d.ID_DIEM,d.diem,d.de,d.loai,d.more,d.note FROM buoikt AS b 
    LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$hsID' AND d.ID_LM='$lmID'
    WHERE b.ngay>'$date_in0' AND b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC LIMIT $dem_begin,10";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        $ngay = format_date($data["ngay"]);
        $diemB=$diemG=$diemY="";
        $loai=$note=$more=0;
        if($data["de"]=="B") {
            if($diemID!=$data["ID_DIEM"]) {
//                if(isset($data["ID_O"])) {
//                    $diemB=0;
//                } else {
                    $diemB = $data["diem"];
//                }
                $loai = $data["loai"];
                $note = $data["note"];
                $more = $data["more"];
                $diemG = $diemY = "X";
                $dem++;
            }
        } else if($data["de"]=="G") {
            if($diemID!=$data["ID_DIEM"]) {
//                if(isset($data["ID_O"])) {
//                    $diemG=0;
//                } else {
                    $diemG = $data["diem"];
//                }
                $loai = $data["loai"];
                $note = $data["note"];
                $more = $data["more"];
                $diemB = $diemY = "X";
                $dem++;
            }
        } else if($data["de"]=="Y") {
            if($diemID!=$data["ID_DIEM"]) {
//                if(isset($data["ID_O"])) {
//                    $diemG=0;
//                } else {
                $diemY = $data["diem"];
//                }
                $loai = $data["loai"];
                $note = $data["note"];
                $more = $data["more"];
                $diemB = $diemG = "X";
                $dem++;
            }
        } else {
            $diemG="X";
            $diemB="X";
            $diemY="X";
            $dem++;
        }
        if($diemB != "") {
            if(!is_numeric($diemB)) {
                $conB .= "{ label: '$ngay', y: '$diemB', indexLabel: ''},";
            } else {
                $cmt_diem=get_cmt_diem_loai($loai);
                if($cmt_diem!="huy") {
                    $conB .= "{ label: '$ngay', y: $diemB, indexLabel: '{y} (B)', toolTipContent: '<span>+ Điểm thi: {y}<br />+ Đề trung bình (B)".$cmt_diem."<br />+ Mã lấy bài: $more</span>'},";
                } else {
                    $conB .= "{ label: '$ngay', y: $diemB, indexLabel: '{y} (B)', toolTipContent: '<span>+ Điểm thi: {y}<br />+ Đề trung bình (B)<br />+ Hủy bài do ".$lido[$note]."<br />+ Mã lấy bài: $more</span>'},";
                }
            }
        }
        if($diemG != "") {
            if(!is_numeric($diemG)) {
                $conG .= "{ label: '$ngay', y: '$diemG', indexLabel: ''},";
            } else {
                $cmt_diem=get_cmt_diem_loai($loai);
                if($cmt_diem!="huy") {
                    $conG .= "{ label: '$ngay', y: $diemG, indexLabel: '{y} (G)', toolTipContent: '<span>+ Điểm thi: {y}<br />+ Đề khá giỏi (G)".$cmt_diem."<br />+ Mã lấy bài: $more</span>'},";
                } else {
                    $conG .= "{ label: '$ngay', y: $diemG, indexLabel: '{y} (G)', toolTipContent: '<span>+ Điểm thi: {y}<br />+ Đề khá giỏi (G)<br />+ Hủy bài do ".$lido[$note]."<br />+ Mã lấy bài: $more</span>'},";
                }
            }
        }
        if($diemY != "") {
            if(!is_numeric($diemY)) {
                $conY .= "{ label: '$ngay', y: '$diemY', indexLabel: ''},";
            } else {
                $cmt_diem=get_cmt_diem_loai($loai);
                if($cmt_diem!="huy") {
                    $conY .= "{ label: '$ngay', y: $diemY, indexLabel: '{y} (Y)', toolTipContent: '<span>+ Điểm thi: {y}<br />+ Đề kém (Y)".$cmt_diem."<br />+ Mã lấy bài: $more</span>'},";
                } else {
                    $conY .= "{ label: '$ngay', y: $diemY, indexLabel: '{y} (Y)', toolTipContent: '<span>+ Điểm thi: {y}<br />+ Đề kém (Y)<br />+ Hủy bài do ".$lido[$note]."<br />+ Mã lấy bài: $more</span>'},";
                }
            }
        }
        $dodai++;
        if($dem==11) {break;}
        $diemID=$data["ID_DIEM"];
    }

	$diem_tbB=array();
	$diem_tbG=array();
    $diem_tbY=array();

    if(!isset($_SESSION["count-hoc-tinh2"])) {
        $temp = count_hoc_tinh($hsID, $lmID);
        $_SESSION["count-hoc-tinh2"] = $temp;
    } else {
        $temp = $_SESSION["count-hoc-tinh2"];
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
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/mobile/css/tongquan.css"/> 
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="css/materialize.min.css" />-->     
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/mobile/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:35%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:22px;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {display:none;width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:22px;}table tr:last-child td:first-child,table tr:last-child td:last-child {border-radius:0;}
			#chart-li1, #chart-li2 {display:inline-table !important;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
			
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				
				/*$(".container > ul").find("li:last").addClass("last-completed");
				
				$(".container > ul > li.completed").each(function(index, element) {
                    if($(element).next("li").attr("class") == "non-completed") {
						$(element).addClass("last-completed");
					};
                });*/
				
				$("a.access_cd").click(function() {
					$("#more-detail").fadeIn("fast");
					$("#BODY").css("opacity","0.1");
				});
				
				$("#main-chart").scrollLeft(<?php echo ($dem_buoi*80); ?>);
				$("#main-table").scrollLeft(<?php echo ($dem_buoi*55); ?>);
				
				$("#lichsu-hoc").click(function() {
                    $(".popup").fadeOut("fast");
					$("#BODY").css("opacity", "0.1");
					$.ajax({
						async: true,
						data: "action=lichsu-hoc",
						type: "post",
						url: "http://localhost/www/TDUONG/mobile/xuly-tongquan/",
						success: function(result) {
							$("#lichsu p.title").html(result);
							$("#lichsu").fadeIn("fast");
						}
					})
				});

                $(".btn-exit, .popup .popup-close, .popup").click(function() {
                    $(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
                });

                setTimeout(function () {
                    $.ajax({
                        async: true,
                        data: "date_in=<?php echo $data0["date_in"] ?>&date_start=<?php echo $date_in0; ?>&dem_begin=<?php echo $dem_begin; ?>",
                        type: "post",
                        url: "http://localhost/www/TDUONG/mobile/xuly-tongquan/",
                        success: function (result) {
                            $("#main-table table").html(result);
                            $.ajax({
                                async: true,
                                data: "monID0=" + <?php echo $monID; ?> + "&lmID=" + <?php echo $lmID; ?>,
                                type: "post",
                                url: "http://localhost/www/TDUONG/mobile/xuly-tongquan/",
                                success: function(result) {
                                    $("div#MAIN").append(result);
                                }
                            });
                        }
                    });
                },500);

			});
		</script>
        <script type="text/javascript">

            window.onload = function () {

                var chart3 = new CanvasJS.Chart("chartContainer",
				{
					animationEnabled: false,
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
						labelFontSize: 10,
						//valueFormatString: "DD/MM", 
						labelFontWeight: "normal",
					},     
					interactivityEnabled: false,                   
					toolTip:{
						backgroundColor: "#FFF",
						borderColor: "",
						borderThickness: 0,
						fontColor: "#000",
						enabled: false
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
						indexLabelFontSize: 10,
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
						indexLabelFontSize: 10,
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
                        indexLabelFontSize: 10,
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
						color: "<?php echo $mauall; ?>",
						toolTipContent: "<span></span>",
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
						color: "<?php echo $mauall; ?>",
						toolTipContent: "<span></span>",
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
                        color: "<?php echo $mauall; ?>",
                        toolTipContent: "<span></span>",
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
			
			var chart6 = new CanvasJS.Chart("chartContainerTile",
			{
				theme: "theme2",
				toolTip:{
					enabled: false
				},	
				backgroundColor: "",
				animationEnabled: false,
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
	
//	            var chart9 = new CanvasJS.Chart("chartContainerTD",
//				{
//					theme: "theme2",
//					toolTip:{
//						enabled: false
//					},
//					backgroundColor: "",
//					animationEnabled: false,
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
//					animationEnabled: false,
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
                            if(!isset($_SESSION["di-hoc2"])) {
                                $temp = count_di_hoc($hsID, $date_in, null, $lmID, $monID);
                                $_SESSION["di-hoc2"] = $temp;
                            } else {
                                $temp = $_SESSION["di-hoc2"];
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
        <script type="text/javascript" src="http://localhost/www/TDUONG/mobile/js/canvasjs.min.js"></script>
       
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
    
    	<div class="popup animated bounceIn" id="more-detail">
            <div>
                <p class="title">Để xem chi tiết, bạn hãy ghé thăm bản Desktop của chúng tôi!</p>
                <div>
                    <button class="submit2 btn_ok"><i class="fa fa-check"></i></button>
                </div>
            </div>
        </div>

      	<div id="SIDEBACK"><div id="BODY">
            
            <div id="MAIN">
            
<!--            	<div class="main-div animated bounceInUp" id="main-top" style="padding: 0;">-->
<!--                    <div id="main-avata" style="display: block;">-->
<!--                        <a href="http://localhost/www/TDUONG/mobile/ho-so/" title="Hồ sơ cá nhân"><img src="https://localhost/www/TDUONG/hocsinh/avata/--><?php //echo $data0["avata"]; ?><!--" /></a>-->
<!--                    </div>-->
<!--                    <div id="main-person" style="width: 65%;">-->
<!--                        <h1 style="line-height: 26px;text-align: left;">-->
<!--                            <a href="http://localhost/www/TDUONG/mobile/tai-khoan/" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-dollar"></i> --><?php //echo format_price($data0["taikhoan"]); ?><!--</a>-->
<!--                            <a href="http://localhost/www/TDUONG/mobile/lich-hoc/" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-calendar"></i> --><?php //echo get_hs_lich_hoc($hsID, $lmID, $monID); ?><!--</a>-->
<!--                            <a href="http://localhost/www/TDUONG/mobile/lich-thi/" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-calendar-plus-o"></i> --><?php //echo get_hs_lich_thi2($hsID, $monID); ?><!--</a>-->
<!--                            <a href="http://localhost/www/TDUONG/mobile/level/" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-trophy"></i> Level --><?php //echo $data0["level"]; ?><!-- (--><?php //echo $data0["de"]; ?><!--)</a>-->
<!--                        </h1>-->
<!--                    </div>-->
<!--                    <div class="clear"></div>-->
<!--                </div>-->
                
                <div class="main-div animated bounceInUp">
                	<div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Điểm thi trong 10 tuần</h3></div>
                    <div id="main-table" class="main-chartx" style="margin-right: -9px;">
                    	<div></div>
                    	<div style="min-width:<?php echo ($dodai*55);?>px">
                            <table>

                            </table>
                            <div class="chart-wap">
                                <div id="chartContainer" style="min-width:<?php echo ($dodai*61+25) ?>px;margin-left:-25px;"></div>
                            </div>
                        </div>
                    <!--</div>
                    <div id="main-chart">-->
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
                        <li class='li-2' id="lichsu-hoc">
                        	<div class="main-2 back"><h3>Tỉ lệ ôn bài<br />ở nhà</h3></div>
                            <div class="main-chart4" style="height:120px;">
                            <?php
								$hoc=format_phantram($hoc);
								echo"<div class='chart-info'><p>$hoc</p></div>";
                           	?>
                            	<div class="chart" id="chartContainerTile"></div>
                            </div>
                        </li>
                        <li class='li-2'><a href="http://localhost/www/TDUONG/mobile/buoi-hoc/">
                        	<div class="main-2 back"><h3>Tỉ lệ đi học<br />đầy đủ</h3></div>
                            <div class="main-chart4" style="height:120px;">
                            <?php
								if($tong_hoc!=0) {
									$dihoc=format_phantram(($di_hoc/$tong_hoc)*100);
								} else {
									$dihoc="0%";
								}
								echo"<div class='chart-info'><p>$dihoc</p></div>";
                           	?>
                            	<div class="chart" id="chartContainerDihoc"></div>
                            </div>
                        </a></li>
<!--                        <li class='li-2'><a href="http://localhost/www/TDUONG/mobile/thach-dau/">-->
<!--                        	<div class="main-2 back"><h3>Thắng<br />thách đấu</h3></div>-->
<!--                            <div class="main-chart4" style="height:120px;">-->
<!--                            --><?php
//								if(($thang+$thua) !=0) {
//									$td=format_phantram(($thang/($thang+$thua))*100);
//								} else {
//									$td="0%";
//								}
//								echo"<div class='chart-info'><p>$td</p></div>";
//                           	?>
<!--                            	<div class="chart" id="chartContainerTD"></div>-->
<!--                            </div>-->
<!--                        </a></li>-->
<!--                        <li class='li-2'><a href="http://localhost/www/TDUONG/mobile/ngoi-sao-hy-vong/">-->
<!--                        	<div class="main-2 back"><h3>Thắng<br /><span class="fa fa-star"></span> hy vọng</h3></div>-->
<!--                            <div class="main-chart4" style="height:120px;">-->
<!--                            --><?php
//								if(($thang_ns+$thua_ns) !=0) {
//									$ns=format_phantram(($thang_ns/($thang_ns+$thua_ns))*100);
//								} else {
//									$ns="0%";
//								}
//								echo"<div class='chart-info'><p>$ns</p></div>";
//                           	?>
<!--                            	<div class="chart" id="chartContainerNgoiSao"></div>-->
<!--                            </div>-->
<!--                        </a></li>-->
                    </ul>
                    <div class="clear"></div>
                </div>
                
                <?php
                /*<div class="main-div hideme"><a href="http://localhost/www/TDUONG/mobile/bang-xep-hang/">
                	<div class='main-1 back'><h3>Biểu đồ điểm thi trung bình môn <?php echo $mon_name; ?> trong 10 tháng</h3></div>
                    <div id="main-chart">
                    	<div></div>
                        <div style="min-width:<?php echo ($dem_tbt*95);?>px">
                            
                            <div class="chart-wap">
                                <div id="chartContainerTB" style="min-width:<?php echo ($dem_tbt*95+25);?>px;margin-left:-25px;"></div>
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

				?>
                              
            </div>
        
        </div>
        <?php require_once("include/MENU.php"); ?>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
		$lmID=$_GET["lm"];
	} else {
		$lmID=0;
	}
	if(!isset($_GET["hs"])) {
		$lmID=$_SESSION["lmID"];
	}
	$lop_mon_name=get_lop_mon_name($lmID);
    $monID=get_mon_of_lop($lmID);
	$dem_buoi=count_buoi_kt();
	if($dem_buoi<10) {
		$dem_begin=0;
	} else {
		$dem_begin=$dem_buoi-10;
	}
//    $_SESSION["bieudo"]=0;
	if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) {
		$query2="SELECT d.*,b.ngay FROM buoikt AS b LEFT JOIN diemkt_tb AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_LM='$lmID' WHERE b.ID_MON='$monID' ORDER BY b.ID_BUOI DESC";
		$result2=mysqli_query($db,$query2);
		$diemtbB=$diemtbG=$diemtbY=array();
		while($data2=mysqli_fetch_assoc($result2)) {
			if(isset($data2["ID_STT"])) {
				if(floor($data2["diemtb"]) == 0) {
					$diemtb="X";
				} else {
					$diemtb=$data2["diemtb"];
				}
				if ($data2["detb"] == "B") {
					$diemtbB[] = array(
						"ngay" => $data2["ngay"],
						"diemtb" => $diemtb
					);
				}
				if ($data2["detb"] == "G") {
					$diemtbG[] = array(
						"ngay" => $data2["ngay"],
						"diemtb" => $diemtb
					);
				}
				if ($data2["detb"] == "Y") {
					$diemtbY[] = array(
						"ngay" => $data2["ngay"],
						"diemtb" => $diemtb
					);
				}
			} else {
				$diemtbB[] = array(
					"ngay" => $data2["ngay"],
					"diemtb" => "X"
				);
				$diemtbG[] = array(
					"ngay" => $data2["ngay"],
					"diemtb" => "X"
				);
				$diemtbY[] = array(
					"ngay" => $data2["ngay"],
					"diemtb" => "X"
				);
			}
		}
	}
	$buoiID=0;
	$query3="SELECT b.*,d.ID_STT FROM buoikt AS b LEFT JOIN diemkt_tb AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_LM='$lmID' WHERE b.ID_MON='$monID' ORDER BY b.ID_BUOI DESC";
	$result3=mysqli_query($db,$query3);
	$dem=0;
	$b5=$b58=$b8=$g5=$g58=$g8=0;
	$to_home=$to_none=$to_huy=$buoi=array();
	while($data3=mysqli_fetch_assoc($result3)) {
		if($buoiID!=$data3["ID_BUOI"]) {
			if (isset($_SESSION["bieudo"]) && $_SESSION["bieudo"] == 0 && $_GET["loai"]!=2) {
				$query4 = "SELECT diem,loai,de FROM diemkt WHERE ID_BUOI='$data3[ID_BUOI]' AND (loai='0' OR loai='1' OR loai='5') AND ID_LM='$lmID'";
				$result4 = mysqli_query($db, $query4);
				$home = 0;
				$none = 0;
				while ($data4 = mysqli_fetch_assoc($result4)) {
					if ($data4["loai"] != 5) {
						if ($data4["de"] == "B") {
							if ($data4["diem"] <= 5) {
								$b5++;
							} else if ($data4["diem"] >= 8) {
								$b8++;
							} else {
								$b58++;
							}
						}
						if ($data4["de"] == "G") {
							if ($data4["diem"] <= 5) {
								$g5++;
							} else if ($data4["diem"] >= 8) {
								$g8++;
							} else {
								$g58++;
							}
						}
						if ($data4["loai"] == 1) {
							$home++;
						}
						$dem++;
					} else {
						$none++;
					}
				}
				$to_home[] = array(
					"buoi" => $data3["ID_BUOI"],
					"ngay" => format_date($data3["ngay"]),
					"num" => $home
				);
				$to_none[] = array(
					"buoi" => $data3["ID_BUOI"],
					"ngay" => format_date($data3["ngay"]),
					"num" => $none
				);
				$query4 = "SELECT COUNT(ID_DIEM) AS dem FROM diemkt WHERE ID_BUOI='$data3[ID_BUOI]' AND loai='3' AND ID_LM='$lmID'";
				$result4 = mysqli_query($db, $query4);
				$data4=mysqli_fetch_assoc($result4);
				$to_huy[] = array(
					"buoi" => $data3["ID_BUOI"],
					"ngay" => format_date($data3["ngay"]),
					"num" => $data4["dem"]
				);
			}
			$buoi[] = format_date($data3["ngay"]);
			$buoiID=$data3["ID_BUOI"];
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THỐNG KÊ BÀI KIỂM TRA</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

		<?php
		if($_SESSION["mobile"]==1) {
			echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/mbocuc.css'>";
		} else {
			echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/bocuc.css'>";
		}
		?>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">
        
        <style>
			td.active {background: yellow;}
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:500px;}#chartContainer {width:100%;height:100%;}#chartPhu {position:absolute;z-index:9;right:0;width:400px;top:0;height:200px;overflow:hidden;border-radius:200px;}#chartContainer2 {width:100%;height:100%;}.khoang-ma,.only-ma {display:none;}#list-danhsach {background:#FFF;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script type="text/javascript">
            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
		<?php if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) {
		    $i = 0; ?>
		window.onload = function () {
			var me = ($(window).width() - 6880) / (-96.2);
			console.log(me);
			<?php if($_GET["loai"]==2) { ?>
//			var myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false, startX: -<?php //echo $dem_buoi; ?>//*me });
//			var myScroll2 = new IScroll('#main-wapper2', { scrollX: true, scrollY: false, mouseWheel: false, startX: -<?php //echo $dem_buoi; ?>//*(me-2) });
			<?php } else { ?>
//			var myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false, startX: -<?php //echo $dem_buoi; ?>//*me });
//            var myScroll2 = new IScroll('#main-wapper2', { scrollX: true, scrollY: false, mouseWheel: false});
			<?php } ?>
            var chart2 = new CanvasJS.Chart("chartContainer2",
            {
                animationEnabled: true,
                interactivityEnabled: true,
                legendText: "{name}",
                theme: "theme2",
                toolTip: {
                    shared: true
                },
                backgroundColor: "",
                legend: {
                    fontSize: 14,
                    fontFamily: "Arial",
                    fontColor: "#3E606F",
                    horizontalAlign: "left",
                    verticalAlign: "center",
                    maxHeight: 30,
                    itemWidth: 60,
                    maxWidth: 200
                },
                data: [{
                    type: "pie",
                    indexLabelPlacement: "inside",
                    indexLabelFontSize: 16,
                    indexLabelFontColor: "#FFF",
                    yValueFormatString: "#%",
                    showInLegend: true,
                    startAngle: -90,
                    indexLabel: "{y}",
                    dataPoints: [
                        <?php if($dem!=0) { ?>
                        { y: <?php echo ($b5/$dem); ?>, color: "#253275", name: "B<=5"},
                        { y: <?php echo ($b58/$dem); ?>,color: "#4d588e", name: "5<B<8"},
                        { y: <?php echo ($b8/$dem); ?>, color: "#6e77a3", name: "8<=B"},
                        { y: <?php echo ($g5/$dem); ?>, color: "#548559", name: "G<=5"},
                        { y: <?php echo ($g58/$dem); ?>, color: "#69a56f", name: "5<G<8"},
                        { y: <?php echo ($g8/$dem); ?>, color: "#75b87c", name: "8<=G"}
                        <?php } ?>
                    ]
                }]
            });
		
		chart2.render();
		var chart = new CanvasJS.Chart("chartContainer",
		{ 
			animationEnabled: true,
			axisX:{
				indexLabelFontFamily:"Arial" ,
				labelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				gridThickness: 1,
				labelFontColor: "#3E606F",
				labelFontSize: 16, 
				labelFontWeight: "normal",
			}, 
			axisY: {
				title: "ĐIỂM TRUNG BÌNH ĐỀ B VÀ G VÀ Y",
				titleFontFamily: "Arial",
				titleFontWeight: "normal",
				titleFontSize: 16,
				indexLabelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 16,
				labelFontWeight: "normal",
				interval: 2,
				maximum: 11
			},
			axisY2: {
				title: "SỐ LƯỢNG NGHỈ KIỂM TRA VÀ MANG BÀI VỀ NHÀ",
				titleFontFamily: "Arial",
				titleFontWeight: "normal",
				titleFontSize: 16,
				indexLabelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 16,
				labelFontWeight: "normal",
			},
			toolTip:{
				shared:true,
			},
			legend: {
				fontSize: 14,
				fontFamily: "Arial",
				fontColor: "#3E606F",
				horizontalAlign: "left",
				verticalAlign: "top",
				maxWidth: 150
			},
			theme: "theme2", 
			dataPointMaxWidth: 20,
		  data: [
              <?php
              if($_GET["loai"]!=2) { ?>
		  {
			  type: "column",
			  axisYType: "secondary",
			  showInLegend: true,
			  indexLabelFontFamily:"Arial" ,
			  indexLabelFontColor: "red",
			  indexLabelFontWeight: "bold",
			  indexLabelFontSize: 16,
			  name: "SỐ LƯỢNG NGHỈ",
			  color: "red",
			  toolTipContent: "<a href = {content}> SỐ LƯỢNG NGHỈ: {y}</a>",
              click: function(e) {
                  window.location.href=e.dataPoint.content;
              },
			  dataPoints: [
			  <?php
			  	$i=count($to_none)-1;
				while($i>=0) {
					echo"{ label: '".$to_none[$i]["ngay"]."', y: ".$to_none[$i]["num"].", indexLabel: '{y}', content: 'http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke-buoi/$lmID/".$to_home[$i]["buoi"]."/nghi/'},";
					$i--;
				}
              ?>
			  ]
		  },
              <?php }
              if($_GET["loai"]!=2) { ?>
		  {
			  type: "column",
			  axisYType: "secondary",
			  showInLegend: true,
			  indexLabelFontFamily:"Arial" ,
			  indexLabelFontColor: "yellow",
			  indexLabelFontWeight: "bold",
			  indexLabelFontSize: 16,
			  name: "MANG BÀI VỀ NHÀ",
			  color: "yellow",
			  toolTipContent: "<a href = {content}> MANG BÀI VỀ NHÀ: {y}</a>",
              click: function(e) {
                  window.location.href=e.dataPoint.content;
              },
			  dataPoints: [
			  <?php
			  	$i=count($to_home)-1;
				while($i>=0) {
					echo"{ label: '".$to_home[$i]["ngay"]."', y: ".$to_home[$i]["num"].", indexLabel: '{y}', content: 'http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke-buoi/$lmID/".$to_home[$i]["buoi"]."/tohome/'},";
					$i--;
				}
              ?>
			  ]
		  },
              <?php }
            if($_GET["loai"]!=2) { ?>
              {
                  type: "column",
                  axisYType: "secondary",
                  showInLegend: true,
                  indexLabelFontFamily: "Arial",
                  indexLabelFontColor: "grey",
                  indexLabelFontWeight: "bold",
                  indexLabelFontSize: 16,
                  name: "HỦY BÀI",
                  color: "grey",
                  toolTipContent: "<a href = {content}> HỦY BÀI: {y}</a>",
                  click: function (e) {
                      window.location.href = e.dataPoint.content;
                  },
                  dataPoints: [
                      <?php
                      $i = count($to_huy)-1;
                      while ($i >= 0) {
                          echo "{ label: '" . $to_huy[$i]["ngay"] . "', y: " . $to_huy[$i]["num"] . ", indexLabel: '{y}', content: 'http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke-buoi/$lmID/" . $to_huy[$i]["buoi"] . "/tohuy/'},";
                          $i--;
                      }
                      ?>
                  ]
              },
              <?php
              }
            if($_GET["loai"]==2) {
                $ma=$_GET["ma"];
                $idhs=get_hs_id($ma);
				$de_arr=array("Y","B","G");
				for($i=0;$i<count($de_arr);$i++) {
					echo "{        
                    type: 'line',
                    showInLegend: false,
                    indexLabelFontFamily:'Arial' ,
                    indexLabelFontColor: 'red',
                    indexLabelFontWeight: 'bold',
                    indexLabelFontSize: 16,
                    gridThickness: 1,
                    lineThickness: 2,
                    name: 'ĐIỂM CỦA $ma (".$de_arr[$i].")',
                    markerType: 'circle',
                    color: 'red',
                    dataPoints: [";
					$buoi = array();
					$query2 = "SELECT d.diem,d.de,b.ngay FROM buoikt AS b LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$idhs' AND d.de='".$de_arr[$i]."' AND d.ID_LM='$lmID' WHERE b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC";
					$result2 = mysqli_query($db, $query2);
					while ($data2 = mysqli_fetch_assoc($result2)) {
						if (isset($data2["diem"]) && is_numeric($data2["diem"])) {
							echo "{ label: '" . format_date($data2["ngay"]) . "', y: " . $data2["diem"] . ", indexLabel: '{y} ($data2[de])'},";
						} else {
							echo "{ label: '" . format_date($data2["ngay"]) . "', y: 'X', indexLabel: '{y} ($data2[de])'},";
						}
						$buoi[] = format_date($data2["ngay"]);
					}
					echo "]
                  },";
				}
            }
          ?>
              <?php
              if($_GET["loai"]!=2) { ?>
		  {
			  type: "line",
			  showInLegend: true,
			  indexLabelFontFamily:"Arial" ,
			  indexLabelFontColor: "#4d588e",
			  indexLabelFontWeight: "bold",
			  indexLabelFontSize: 16,
			  gridThickness: 1,
			  lineThickness: 2,
			  name: "ĐIỂM TB - ĐỀ Y",
			  markerType: "circle",
			  toolTipContent: "<a href = {content}>ĐIỂM TB - ĐỀ Y: {y}</a>",
			  color: "red",
			  click: function (e) {
				  window.open(e.dataPoint.content,"_blank");
			  },
			  dataPoints: [
				  <?php
				  $i=count($diemtbY)-1;
				  while($i>=0) {
					  $content="http://localhost/www/TDUONG/thaygiao/pho-diem/".$diemtbY[$i]["ngay"]."/Y/$lmID/";
					  if(is_numeric($diemtbY[$i]["diemtb"])) {
						  echo "{ label: '" . format_date($diemtbY[$i]["ngay"]) . "', y: " . $diemtbY[$i]["diemtb"] . ", indexLabel: '{y} (Y)', content: '$content'},";
					  } else {
						  echo "{ label: '" . format_date($diemtbY[$i]["ngay"]) . "', y: '" . $diemtbY[$i]["diemtb"] . "', indexLabel: '{y} (Y)', content: '$content'},";
					  }
					  $i--;
				  }
				  ?>
			  ]
		  },
		  {        
			type: "line",
			showInLegend: true,
			indexLabelFontFamily:"Arial" ,
			indexLabelFontColor: "#4d588e",
			indexLabelFontWeight: "bold",
			indexLabelFontSize: 16,
			gridThickness: 1,
			lineThickness: 2,
			name: "ĐIỂM TB - ĐỀ B",
			markerType: "circle",
            toolTipContent: "<a href = {content}>ĐIỂM TB - ĐỀ B: {y}</a>",
			color: "#4d588e",
              click: function (e) {
                  window.open(e.dataPoint.content,"_blank");
              },
			dataPoints: [
			<?php
				$i=count($diemtbB)-1;
				while($i>=0) {
				    $content="http://localhost/www/TDUONG/thaygiao/pho-diem/".$diemtbB[$i]["ngay"]."/B/$lmID/";
					if(is_numeric($diemtbB[$i]["diemtb"])) {
						echo "{ label: '" . format_date($diemtbB[$i]["ngay"]) . "', y: " . $diemtbB[$i]["diemtb"] . ", indexLabel: '{y} (B)', content: '$content'},";
					} else {
						echo "{ label: '" . format_date($diemtbB[$i]["ngay"]) . "', y: '" . $diemtbB[$i]["diemtb"] . "', indexLabel: '{y} (B)', content: '$content'},";
					}
					$i--;
				}
			?>
			]
		  },
		  {        
			type: "line",
			showInLegend: true,
			indexLabelFontFamily:"Arial" ,
			indexLabelFontColor: "#69a56f",
			indexLabelFontWeight: "bold",
			indexLabelFontSize: 16,
			gridThickness: 1,
			lineThickness: 2,
			name: "ĐIỂM TB - ĐỀ G",
			markerType: "circle",
            toolTipContent: "<a href = {content}>ĐIỂM TB - ĐỀ G: {y}</a>",
			color: "#69a56f",
              click: function (e) {
                  window.open(e.dataPoint.content,"_blank");
              },
			dataPoints: [
			<?php
				$i=count($diemtbG)-1;
				while($i>=0) {
                    $content="http://localhost/www/TDUONG/thaygiao/pho-diem/".$diemtbG[$i]["ngay"]."/G/$lmID/";
					if(is_numeric($diemtbG[$i]["diemtb"])) {
						echo "{ label: '" . format_date($diemtbG[$i]["ngay"]) . "', y: " . $diemtbG[$i]["diemtb"] . ", indexLabel: '{y} (G)', content: '$content'},";
					} else {
						echo "{ label: '" . format_date($diemtbG[$i]["ngay"]) . "', y: '" . $diemtbG[$i]["diemtb"] . "', indexLabel: '{y} (G)', content: '$content'},";
					}
					$i--;
				}
			?>
			]
		  }
              <?php } ?>
		  ]
		});
	
		chart.render();
	  }
	  <?php } ?>
		</script>
        <script>
			$(document).ready(function() {
			    $("#main-wapper").scrollLeft(10000);

				$("#select-loai").change(function() {
					if($(this).val()==1) {
						$(".only-ma").fadeOut("fast");
						$(".khoang-ma").fadeIn("fast");
					} else if($(this).val()==2) {
						$(".khoang-ma").fadeOut("fast");
						$(".only-ma").fadeIn("fast");
					} else {
						$(".only-ma").fadeOut("fast");
						$(".khoang-ma").fadeOut("fast");
					}
				});
				
				if($("#select-loai").val()==2) {
					$(".only-ma").fadeIn("fast");
				}
				
				if($("#select-loai").val()==1) {
					$(".khoang-ma").fadeIn("fast");
				}

				$("#list-danhsach tr td.kolay").click(function() {
					dem = parseInt($(this).attr("data-dem"));
					if(dem>=1) {
						me = $(this);
						hsID = $(this).closest("tr").find("td:first-child").attr("data-hsID");
						temp = $(this).attr("data-oID");
						temp = temp.split("-");
						oID = temp[0];
						buoiID = temp[1];
						if ($.isNumeric(oID) && $.isNumeric(buoiID) && buoiID != 0 && $.isNumeric(hsID)) {
							$("#popup-loading").fadeIn("fast");
							$("#BODY").css("opacity", "0.3");
							$.ajax({
								async: true,
								data: "oID=" + oID + "&buoiID5=" + buoiID + "&hsID5=" + hsID,
								type: "post",
								url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
								success: function (result) {
									if (result != 0) {
										me.addClass("active").attr("data-oID", result + "-" + buoiID);
									} else {
										me.removeClass("active").attr("data-oID", "0-" + buoiID);
									}
									$("#BODY").css("opacity", "1");
									$("#popup-loading").fadeOut("fast");
									me.attr("data-dem",0);
								}
							});
						}
					} else {
						$(this).attr("data-dem",dem+1);
					}
                });
				
				$("#bieu-do").click(function() {
					$.ajax({
						async: true,
						data: "bieudo=all",
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-thongke/",
						success: function() {
							location.reload();
						}
					});
				});

                $("#xuat-ok").click(function() {
                    if(confirm("Quá trình xuất Excel sẽ mất nhiều thời gian?")) {
                        return true;
                    } else {
                        return false;
                    }
                });
			});
		</script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/thaygiao/js/canvasjs.min.js"></script>
       
	</head>
    
    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	<?php
//				echo $_SESSION["bieudo"];
                    $xuat=false;
                    if(isset($_POST["xuat-ok"])) {
                        $xuat=true;
                        include ("include/PHPExcel/IOFactory.php");
                        $objPHPExcel = new PHPExcel();
                        $objPHPExcel->setActiveSheetIndex(0);
                    }
				
					$loai=$ma1=$ma2=$ma=NULL;
					if(isset($_POST["set-ok"])) {
						
						$loai=$_POST["select-loai"];
						if($loai==0) {
							header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke/$lmID/$loai/");
							exit();
						} else if($loai==1) {
							if(isset($_POST["ma1"])) {
								$ma1=$_POST["ma1"];
							}
							if(isset($_POST["ma2"])) {
								$ma2=$_POST["ma2"];
							}
							if($ma1 && $ma2) {
								header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke/$lmID/$loai/$ma1/$ma2/");
								exit();
							} else {
								header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke/$lmID/0/");
								exit();
							}
						} else if($loai==2) {
							if(isset($_POST["ma"])) {
								$ma=$_POST["ma"];
							}
							if($ma) {
								header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke/$lmID/$loai/$ma/");
								exit();
							}
						} else {
							header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke/$lmID/$loai/");
							exit();
						}
					}
					
					if(isset($_GET["loai"]) && is_numeric($_GET["loai"])) {
						$loai=$_GET["loai"];
						if($loai==2) {
							if(isset($_GET["ma"])) {
								$ma=$_GET["ma"];
							} else {
								$ma="none";
							}
						}
						if($loai==1) {
							if(isset($_GET["ma1"])) {
								$ma1=$_GET["ma1"];
							} else {
								$ma1="none";
							}
							if(isset($_GET["ma2"])) {
								$ma2=$_GET["ma2"];
							} else {
								$ma2="none";
							}
						}
					} else {
						$loai=0;
						$ma=$ma1=$ma2="none";
					}
				?>
                
                <div id="main-mid">
                	<h2>Thống kê bài kiểm tra <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
						<?php if($_SESSION["mobile"]==0) { ?>
                        <?php if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) { ?>
                      		<div class="main-top" style="display:block;overflow:auto;padding-top:90px;" id="main-wapper">
                            	<div style="position:absolute;left:0;top:0;width: 200px;">
                                    <input type="submit" class="submit" id="bieu-do" value="Biểu đồ" style="float: left;" />
                                    <form action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post">
                                        <input type="submit" class="submit" id="xuat-ok" name="xuat-ok" value="Xuất Excel" style="float: left;" />
                                    </form>
                                </div>
                            	<div id="chartContainer" style="width:<?php echo (count($buoi)*105); ?>px">
                                </div>
                                <div id="chartPhu">
                                    <div id="chartContainer2">
                                    </div>
                                </div>
                            </div>
                       	<?php } else { ?>
                        	<div class="main-top" style="display:block;height:40px;">
                            	<div style="position:absolute;left:0;top:0;width: 200px;">
                                    <input type="submit" class="submit" id="bieu-do" value="Biểu đồ" style="float: left;" />
                                    <form action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post">
                                        <input type="submit" class="submit" id="xuat-ok" name="xuat-ok" value="Xuất Excel" style="float: left;" />
                                    </form>
                                </div>
                            </div>
                        <?php } } ?>
                            <div class="main-bot" style="display:block;">
								<?php if($_SESSION["mobile"]==0) { ?>
                            	<!--<form action="http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke/<?php echo $lmID; ?>/" method="post">
                            	<table class="table" style="margin-top:25px;">
                                	<tr>
                                    	<td style="width:25%;">
                                        	<select class="input" id="select-loai" style="height:auto;" name="select-loai">
                                            	<option value="0" <?php if($loai==0){echo"selected='selected'";} ?>>STT tăng dần (30/trang)</option>
                                                <option value="1" <?php if($loai==1){echo"selected='selected'";} ?>>Tìm theo khoảng STT</option>
                                                <option value="2" <?php if($loai==2){echo"selected='selected'";} ?>>Tìm theo mã cụ thể</option>
                                                <option value="3" <?php if($loai==3){echo"selected='selected'";} ?>>Tất cả (không khuyến nghị)</option>
                                            </select>
                                        </td>
                                        <td class="khoang-ma" style="width:16.25%;"><span>STT đầu</span></td>
                                        <td class="khoang-ma" style="width:16.25%;"><input type="text" class="input" name="ma1" id="ma1" placeholder="2" /></td>
                                        <td class="khoang-ma" style="width:16.25%;"><span>STT cuối</span></td>
                                        <td class="khoang-ma" style="width:16.25%;"><input type="text" class="input" name="ma2" id="ma2" placeholder="7" /></td>
                                        <td class="only-ma" style="width:32.5%;"><span>Mã học sinh</span></td>
                                        <?php
                                            if($loai==2) {
                                                echo"<td class='only-ma' style='width:32.5%;'><input type='text' class='input' name='ma' id='ma' placeholder='99-0002' value='$ma' /></td>";
                                            } else {
                                                echo"<td class='only-ma' style='width:32.5%;'><input type='text' class='input' name='ma' id='ma' placeholder='99-0002' /></td>";
                                            }
                                        ?>
                                    	<td style="width:10%;"><input type="submit" class="submit" name="set-ok" value="Lọc" id="set-ok" /></td>
                                    </tr>
                                </table>
                                </form>-->
<!--                                <table class="table table3" style="margin-top:25px;">-->
<!--                                	<tr>-->
<!--                                    	<td style='background:orange;'><span style="color:#FFF;font-size:12px;">Mang bài về nhà</span></td>-->
<!--                                        <td style='background:green;'><span style="color:#FFF;font-size:12px;">Không đi thi</span></td>-->
<!--                                        <td style='background:none;'><span style='font-size:12px;'>Không quan tâm</span></td>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
                                        <?php
											$lido=$lido_mau=array();
											$result3=get_all_lido2();
											while($data3=mysqli_fetch_assoc($result3)) {
												//echo"<td style='background:$data3[mau]'><span style='color:#FFF;font-size:12px;'>$data3[name]</span></td>";
                                                $lido[$data3["ID_LD"]]=$data3["name"];
                                                $lido_mau[$data3["ID_LD"]]=$data3["mau"];
											}
										?>
<!--                                  	</tr>-->
<!--                                </table>-->
								<?php } else {
									$lido=$lido_mau=array();
									$result3=get_all_lido2();
									while($data3=mysqli_fetch_assoc($result3)) {
										$lido[$data3["ID_LD"]]=$data3["name"];
										$lido_mau[$data3["ID_LD"]]=$data3["mau"];
									}
								} ?>
                                <div class="clear" id="main-wapper2" style="width:100%;">
                                <div></div>
								<?php if($loai != 2) { ?>
                                <table class="table" id="list-danhsach" style="margin-top:25px;">
                                	<tr style="background:#3E606F;">
										<?php if($loai!=2) { ?>
											<th style="min-width:60px;"><span>STT</span></th>
											<th style="min-width:300px;" colspan="2"><span>Tên học sinh</span></th>
											<th style="min-width:60px;"><span>Đề</span></th>
										<?php
										}
                                            $rowCount = 1;
                                            $col = 'A';
                                            if($xuat) {
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "STT");$col++;
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã số");$col++;
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tên học sinh");$col++;
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Đề");$col++;
                                            }

//                                            if($loai==2) {
//										        $result=get_all_buoikt2($monID);
//                                                while($data=mysqli_fetch_assoc($result)) {
//                                                    echo "<th style='min-width:100px;'><span>".format_date($data["ngay"])."</span></th>";
//                                                    if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_date($data["ngay"]));$col++;}
//                                                    if($i==4) {break;}
//                                                }
//                                            } else {
                                                for($i=0;$i<count($buoi);$i++) {
                                                    echo "<th style='min-width:100px;'><span>$buoi[$i]</span></th>";
                                                    if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $buoi[$i]);$col++;}
                                                    if($i==4 && $loai!=2) {break;}
                                                }
//                                            }
										?>
									</tr>
                                    <?php
										$name_arr="";
										$position=0;
										if($loai==0) {
											if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
												$position=$_GET["begin"];
											} else {
												$position=0;
											}
											$display=30;
                                            if($_SESSION["bieudo"] == 1) {
                                                $query = "SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' ORDER BY hocsinh.cmt ASC LIMIT $position,$display";
                                            } else {
                                                $query = "SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' WHERE hocsinh.lop='-1' ORDER BY hocsinh.cmt ASC LIMIT $position,$display";
                                            }
                                        } else if($loai==2) {
											$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' WHERE hocsinh.cmt='$ma'";
										} else if($loai==1) {
											$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' WHERE (hocsinh.ID_HS BETWEEN '$ma1' AND '$ma2') ORDER BY hocsinh.cmt ASC";
										} else {
											if($monID!=1) {
												$query = "SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' ORDER BY hocsinh.cmt ASC";
											} else {
												$query = "SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' WHERE hocsinh.lop='-1' ORDER BY hocsinh.cmt ASC";
											}
										}
										if(isset($_GET["buoi"]) && isset($_GET["how"])) {
											$mybuoi=$_GET["buoi"];
											$how=$_GET["how"];
											if($how=="tohome") {
												$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' INNER JOIN diemkt ON diemkt.ID_BUOI='$mybuoi' AND diemkt.ID_HS=hocsinh.ID_HS AND diemkt.loai='1' AND diemkt.ID_LM='$lmID' ORDER BY hocsinh.cmt ASC";
											}
											if($how=="nghi") {
												$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' INNER JOIN diemkt ON diemkt.ID_BUOI='$mybuoi' AND diemkt.ID_HS=hocsinh.ID_HS AND diemkt.loai='5' AND diemkt.ID_LM='$lmID' ORDER BY hocsinh.cmt ASC";
											}
											if($how=="tohuy") {
												$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in,hocsinh_nghi.ID_N FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' INNER JOIN diemkt ON diemkt.ID_BUOI='$mybuoi' AND diemkt.ID_HS=hocsinh.ID_HS AND diemkt.loai='3' AND diemkt.ID_LM='$lmID' ORDER BY hocsinh.cmt ASC";
											}
										} else {
											$mybuoi=NULL;
											$how=NULL;
										}
										$dem=$position;
										$result=mysqli_query($db,$query);

                                        $rowCount=2;

										while($data=mysqli_fetch_assoc($result)) {

                                            $col="A";

											if(isset($data["ID_N"])) {
												if($dem%2!=0) {
													echo"<tr style='background:#D1DBBD;opacity:0.3;'>";
												} else {
													echo"<tr style='opacity:0.3'>";
												}
											} else {
												if($dem%2!=0) {
													echo"<tr style='background:#D1DBBD'>";
												} else {
													echo"<tr>";
												}
											}

											$name_arr .= "<tr>
												<td data-hsID='$data[ID_HS]'><span>".($dem+1)."</span></td>
												<td><span>$data[cmt]</span></td>
												<td><span>$data[fullname]</span></td>
												<td><span>$data[de]</span></td>";
											if($loai!=2) {
												echo $name_arr;
												$name_arr="";
											}
//                                            echo"<td data-hsID='$data[ID_HS]'><span>".($dem+1)."</span></td>
//                                            <td><span>$data[cmt]</span></td>
//                                            <td><span>$data[fullname]</span></td>
//                                            <td><span>$data[de]</span></td>";

                                            if($xuat) {
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, ($dem+1));$col++;
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["cmt"]);$col++;
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["fullname"]);$col++;
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["de"]);$col++;
                                            }

                                            $date=date_create($data["date_in"]);
                                            if($loai!=2) {
                                                $query2 = "SELECT d.*,b.ngay,o.ID_O FROM buoikt AS b LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$data[ID_HS]' AND d.ID_LM='$lmID' LEFT JOIN options AS o ON o.content='$data[ID_HS]' AND o.type='khong-lay-bai' AND o.note=b.ID_BUOI AND o.note2='$monID' WHERE b.ID_MON='$monID' ORDER BY b.ID_BUOI DESC LIMIT 5";
                                            } else {
                                                $query2 = "SELECT d.*,b.ngay,o.ID_O FROM buoikt AS b LEFT JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS='$data[ID_HS]' AND d.ID_LM='$lmID' LEFT JOIN options AS o ON o.content='$data[ID_HS]' AND o.type='khong-lay-bai' AND o.note=b.ID_BUOI AND o.note2='$monID' WHERE b.ID_MON='$monID' ORDER BY b.ID_BUOI ASC";
                                            }
                                            $result2=mysqli_query($db,$query2);
                                            while($data2=mysqli_fetch_assoc($result2)) {
                                                $today=date_create($data2["ngay"]);
                                                if($today<$date) {
                                                    echo"<td><span>Chưa học</span>";
                                                    if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Chưa học");$col++;}
                                                } else {
                                                    if(is_numeric($data2["loai"])) {
                                                        if(isset($data2["ID_O"])) {
                                                            $oID=$data2["ID_O"];
															$class="active";
                                                        } else {
                                                            $oID=0;
															$class="";
                                                        }
                                                        switch($data2["loai"]) {
                                                            case 0:
                                                                echo"<td class='kolay $class' data-oID='$oID-$data2[ID_BUOI]' data-dem='0'><span>$data2[diem] ($data2[de])</span>";
                                                                if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "$data2[diem] ($data2[de])");$col++;}
                                                                break;
                                                            case 1:
                                                                echo"<td class='kolay $class' data-oID='$oID-$data2[ID_BUOI]' data-dem='0' style='background:orange;'><span style='color:#FFF'>$data2[diem] ($data2[de])</span>";
                                                                if($xuat){
                                                                    $objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFA500');
                                                                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "$data2[diem] ($data2[de])");
                                                                    $col++;
                                                                }
                                                                break;
                                                            case 2:
                                                                echo"<td><span>Nghỉ học ($data2[de])</span>";
                                                                if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Nghỉ học ($data2[de])");$col++;}
                                                                break;
                                                            case 3:
                                                                echo"<td class='kolay $class' data-oID='$oID-$data2[ID_BUOI]' data-dem='0' style='background:".$lido_mau[$data2["note"]].";'><span style='color:#FFF'>".$lido[$data2["note"]]." ($data2[de])</span>";
                                                                if($xuat){
                                                                    $objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB(substr($lido_mau[$data2["note"]-1],1));
                                                                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $lido[$data2["note"]-1]." ($data2[de])");
                                                                    $col++;
                                                                }
                                                                break;
                                                            case 4:
                                                                echo"<td><span>Mất bài,<br />nghỉ phép ($data2[de])</span>";
                                                                if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mất bài, nghỉ phép ($data2[de])");$col++;}
                                                                break;
                                                            case 5:
                                                                echo"<td style='background:green;'><span style='color:#FFF'>Không đi thi ($data2[de])</span>";
                                                                if($xuat){
                                                                    $objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('008000');
                                                                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Không đi thi ($data2[de])");
                                                                    $col++;
                                                                }
                                                                break;
                                                            default:
                                                                echo"<td><span>($data2[de])</span>";
                                                                if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;}
                                                                break;
                                                        }
                                                    } else {
                                                        echo"<td><span>($data2[de])</span>";
                                                        if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;}
                                                    }
                                                }
                                                echo"</td>";
                                            }
                                            echo"</tr>";
                                            $dem++;
                                            $rowCount++;
										}
										if($dem==0) {
											echo"<tr><td colspan='12'><span>Không có dữ liệu</span></td></tr>";
										}
									?>
                                </table>
								<?php } ?>
								<div></div>
                                </div>
								<div>
									<table class="table" id="list-name">
										<tr>
											<td><span><?php echo $ma; ?></span></td>
										</tr>
									</table>
								</div>
                            </div>
                        </div>
                        <?php
						if($loai==0 && $mybuoi==NULL && $how==NULL && $_SESSION["bieudo"]==1) {
							$query6="SELECT hocsinh.ID_HS FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID'";
							$result6=mysqli_query($db,$query6);
							$sum=mysqli_num_rows($result6);
							$sum_page=ceil($sum/$display);
							if($sum_page>1) {
								$current=($position/$display)+1;
						?>
                        <div class="page-number">
                        	<ul>
                            <?php
								if($current!=1) {
									$prev=$position-$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke-page/$lmID/$loai/page-$prev/'><</a></li>";
								}
								for($page=1;$page<=$sum_page;$page++) {
									$begin=($page-1)*$display;
									if($page==$current) {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke-page/$lmID/$loai/page-$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
									} else {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke-page/$lmID/$loai/page-$begin/'>$page</a></li>";
									}
								}
								if($current!=$sum_page) {
									$next=$position+$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke-page/$lmID/$loai/page-$next/'>></a></li>";
								}
							?>
                            </ul>
                        </div>
                        <?php
							}
						}
//						?>
                    </div>
               	</div>
            
            </div>
        
        </div>
        
    </body>
</html>

<?php

    if($xuat) {
        ob_end_clean();
        // Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="diem-thi.xlsx"');
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        // Write the Excel file to filename some_excel_file.xlsx in the current directory
        $objWriter->save('php://output');
    }

	ob_end_flush();
	require_once("../model/close_db.php");
?>
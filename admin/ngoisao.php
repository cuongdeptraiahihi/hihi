<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["buoi"])) {
        $date=$_GET["buoi"];
        $lmID=$_GET["lm"];
        $buoiID=get_id_buoikt($date, get_mon_of_lop($lmID));
    } else {
        $date=get_next_CN();
        $lmID=0;
        $buoiID=0;
    }
    $lmID=$_SESSION["lmID"];
    $lop_mon_name=get_lop_mon_name($lmID);
    $date_in=get_lop_mon_in($lmID);

    $buoi_arr=array();
    $query0="SELECT DISTINCT buoi FROM ngoi_sao WHERE buoi>'$date_in' ORDER BY buoi DESC";
    $result0=mysqli_query($db,$query0);
    while($data0=mysqli_fetch_assoc($result0)) {
        $buoi_arr[]=$data0["buoi"];
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>NGÔI SAO HY VỌNG</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:400px;}#chartContainer {width:100%;height:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script>
		$(document).ready(function() {
			
			$("#xem").click(function() {
				lopID = $("#select-lop").val();
				if($.isNumeric(buoiID) && buoiID!=0) {
					return true;
				} else {
					alert("Vui lòng nhập đầy đủ thông tin!");
					return false;
				}
			});
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.view", "click", function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				nsID = $(this).attr("data-nsID");
				del_tr = $(this).closest("tr");
				$.ajax({
					async: true,
					data: "nsID0=" + nsID,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-ngoisao/",
					success: function(result) {
						del_tr.find("td span.ketqua").html(result);
						$("#BODY").css("opacity","1");
						$("#popup-loading").fadeOut("fast");
					}
				});
				return false;
			});
		});
		</script>
        <script type="text/javascript">
            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
			window.onload = function () {
			    var myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false});
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
						indexLabelFontFamily:"Arial" ,
						gridColor: "Silver",
						tickColor: "silver",
						labelFontColor: "#3E606F",
						labelFontSize: 16,
						labelFontWeight: "normal",
						interval: 5,
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
						maxWidth: 300
					},
					theme: "theme2", 
				  data: [
				  {
					  type: "column",
					  showInLegend: true,
					  indexLabelFontFamily:"Arial" ,
					  indexLabelFontColor: "green",
					  indexLabelFontWeight: "bold",
					  indexLabelFontSize: 16,
					  name: "SỐ LƯỢNG CHỌN NGÔI SAO",
					  color: "green",
					  toolTipContent: "<a href = {content}> SỐ LƯỢNG CHỌN NGÔI SAO: {y}</a>",
					  dataPoints: [
					  <?php
					  	$i=count($buoi_arr)-1;
						while($i>=0) {
							$query2="SELECT ID_STT FROM ngoi_sao WHERE buoi='$buoi_arr[$i]' AND ID_LM='$lmID'";
							$result2=mysqli_query($db,$query2);
							$num=mysqli_num_rows($result2);
							echo"{ label: '".format_date($buoi_arr[$i])."', y: ".$num.", indexLabel: '{y}', content: 'http://localhost/www/TDUONG/admin/ngoi-sao/$buoi_arr[$i]/$lmID/'},";
							$i--;
						}
					  ?>
					  ]
				  },
				  ]
				});
			
				chart.render();
			}
        </script>
        <script type="text/javascript" src="http://localhost/www/TDUONG/admin/js/canvasjs.min.js"></script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>NGÔI SAO HY VỌNG <span style="font-weight:600;">môn <?php echo $lop_mon_name.", ngày ".format_dateup($date); ?></span></h2>
                	<div>
                    	<div class="status">
                       	 	<div class="main-top" style="display:block;overflow:auto;" id="main-wapper">
                            	<div></div>
                            	<div id="chartContainer" style="width:<?php echo (10*110); ?>px">
                                </div>
                            </div>
                            <div class="main-bot" style="display:block;">
                                    <table class="table" style="margin-top:25px;">
                                        <tr style="background:#3E606F;">
                                            <th style="width:10%;"><span>STT</span></th>
                                            <th><span>Học sinh</span></th>
                                            <th style="width:15%;"><span>Mã</span></th>
                                            <th style="width:15%;"><span>Tiền cược</span></th>
                                            <th style="width:15%;"><span>Kết quả</span></th>
                                            <th style="width:15%;"><span></span></th>
                                        </tr>
                                        <?php
                                            if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
                                                $position=$_GET["begin"];
                                            } else {
                                                $position=0;
                                            }
                                            $dem=0;$display=30;
                                            $ngay=$date;
                                            $result=get_all_list_ngoisao($ngay, $lmID);
                                            while($data=mysqli_fetch_assoc($result)) {
                                                $result1=get_hs_short_detail2($data["ID_HS"]);
                                                $data1=mysqli_fetch_assoc($result1);
                                                if($dem%2!=0) {
                                                    echo"<tr style='background:#D1DBBD'>";
                                                } else {
                                                    echo"<tr>";
                                                }
                                        ?> 
                                            <td><span><?php echo ($dem+1);?></span></td>
                                            <td><span><?php echo $data1["fullname"]; ?></span></td>
                                            <td><span><?php echo $data1["cmt"];?></span></td>
                                            <td><span><?php echo format_price($data["tien"]);?></span></td>
                                            <td><span>
                                            <?php
                                                if($data["status"]=="done") {
                                                    if($data["ketqua"]==1) {
                                                        echo"Thắng";
                                                    } else {
                                                        echo"Thua";
                                                    }
                                                } else {
                                                    echo"Đang chờ kết quả";
                                                }
                                            ?>
                                            <br /><span class="ketqua"></span></span></td>
                                            <td>
                                                <input type="submit" name="xem" id="xem" class="submit view" data-nsID="<?php echo $data["ID_STT"];?>" value="Xem kết quả" />
                                            </td>
                                        </tr>
                                        <?php 
                                                $dem++;
                                            }
                                            if($dem==0) {
                                                echo"<tr><td colspan='6'><span>Không có dữ liệu</span></td></tr>";
                                            }
                                        ?>
                                    </table>
                            	</div>
                        </div>
                    </div>
               	</div>
            
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
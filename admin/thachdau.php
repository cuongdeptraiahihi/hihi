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
	$query0="SELECT DISTINCT buoi FROM thachdau WHERE buoi>'$date_in' ORDER BY buoi DESC";
	$result0=mysqli_query($db,$query0);
	while($data0=mysqli_fetch_assoc($result0)) {
		$buoi_arr[]=$data0["buoi"];
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TRA CỨU THÁCH ĐẤU</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:13px;margin-top: 5px;display: block;}.see-kq {font-weight:600;font-size:13px;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:400px;}#chartContainer {width:100%;height:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script>
		
		$(document).ready(function() {
			
			$("#xem").click(function() {
				buoiID = $("#select-buoi").val();
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
				tdID = $(this).attr("data-tdID");
				del_tr = $(this).closest("tr");
				$.ajax({
					async: true,
					data: "tdID0=" + tdID,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-thachdau/",
					success: function(result) {
						obj = jQuery.parseJSON(result);
						del_tr.find("td span.kq-1").html(obj.diem1);
						del_tr.find("td span.kq-2").html(obj.diem2);
						$("#BODY").css("opacity","1");
						$("#popup-loading").fadeOut("fast");
					}
				});
				return false;
			});

            $("#MAIN #main-mid .status .table").delegate("tr td input.again", "click", function() {
                if(confirm("Bạn có chắc chắn xét lại kết quả trận thách đấu này?")) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity", "0.3");
                    tdID = $(this).attr("data-tdID");
                    del_tr = $(this).closest("tr");
                    $.ajax({
                        async: true,
                        data: "tdID3=" + tdID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-thachdau/",
                        success: function (result) {
                            $("#BODY").css("opacity", "1");
                            $("#popup-loading").fadeOut("fast");
                            location.reload();
                        }
                    });
                }
                return false;
            });

            $("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
                if(confirm("Bạn có chắc chắn xóa trận thách đấu này?")) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity", "0.3");
                    tdID = $(this).attr("data-tdID");
                    del_tr = $(this).closest("tr");
                    $.ajax({
                        async: true,
                        data: "tdID1=" + tdID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-thachdau/",
                        success: function (result) {
                            del_tr.fadeOut("fast");
                            $("#BODY").css("opacity", "1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                }
                return false;
            });
		});
		</script>
        <script type="text/javascript">
//            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
			window.onload = function () {
			    //var myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false});
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
					  showInLegend: false,
					  indexLabelFontFamily:"Arial" ,
					  indexLabelFontColor: "green",
					  indexLabelFontWeight: "bold",
					  indexLabelFontSize: 16,
					  name: "SỐ LƯỢNG GỬI THÁCH ĐẤU",
					  color: "green",
					  toolTipContent: "<a href = {content}> SỐ LƯỢNG GỬI THÁCH ĐẤU: {y}</a>",
					  dataPoints: [
					  <?php
					  	$i=count($buoi_arr)-1;
						while($i>=0) {
							$query2="SELECT ID_STT FROM thachdau WHERE buoi='$buoi_arr[$i]' AND ID_LM='$lmID'";
							$result2=mysqli_query($db,$query2);
							$num=mysqli_num_rows($result2);
							echo"{ label: '".format_date($buoi_arr[$i])."', y: ".$num.", indexLabel: '{y}', content: 'http://localhost/www/TDUONG/admin/thach-dau/$buoi_arr[$i]/$lmID/'},";
							$i--;
						}
					  ?>
					  ]
				  },
				  {
					  type: "column",
					  showInLegend: false,
					  indexLabelFontFamily:"Arial" ,
					  indexLabelFontColor: "#3E606F",
					  indexLabelFontWeight: "bold",
					  indexLabelFontSize: 16,
					  name: "SỐ LƯỢNG CHẤP NHẬN",
					  color: "#3E606F",
					  toolTipContent: "<a href = '#'> SỐ LƯỢNG CHẤP NHẬN: {y}</a>",
					  dataPoints: [
					  <?php
						$i=count($buoi_arr)-1;
						while($i>=0) {
							$query2="SELECT ID_STT FROM thachdau WHERE buoi='$buoi_arr[$i]' AND status='accept' AND ID_LM='$lmID'";
							$result2=mysqli_query($db,$query2);
							$num=mysqli_num_rows($result2);
							echo"{ label: '".format_date($buoi_arr[$i])."', y: ".$num.", indexLabel: '{y}'},";
							$i--;
						}
					  ?>
					  ]
				  }
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
                	<h2>TRA CỨU THÁCH ĐẤU <span style="font-weight:600;">môn <?php echo $lop_mon_name.", ngày ".format_dateup($date); ?></span></h2>
                	<div>
                    	<div class="status">
                        	<div class="main-top" style="display:block;overflow:auto;" id="main-wapper">
                            	<div></div>
                            	<div id="chartContainer" style="min-width:1000px">
                                </div>
                            </div>
                        </div>
                        <div class="status">
                            <table class="table">
                                <tr style="background:#3E606F;">
                                    <th class="hidden" style="width:5%;"><span>STT</span></th>
                                    <th style="width:22.5%;"><span>A đi thách đấu</span></th>
                                    <th style="width:15%;"><span>Chấp</span></th>
                                    <th style="width:22.5%;"><span>B nhận thách đấu</span></th>
                                    <th class="hidden" style="width:15%;"><span>Trạng thái</span></th>
                                    <th class="hidden" style="width:20%;"><span></span></th>
                                </tr>
                                <?php
                                $dem=0;
                                $temp=explode("-",$date);
                                $now_month=$temp[0]."-".$temp[1];
                                $ngay=$date;
                                $result=get_all_list_thachdau($ngay, $lmID);
                                while($data=mysqli_fetch_assoc($result)) {

                                    $result1=get_hs_short_detail2($data["ID_HS"]);
                                    $data1=mysqli_fetch_assoc($result1);
                                    $result2=get_hs_short_detail2($data["ID_HS2"]);
                                    $data2=mysqli_fetch_assoc($result2);
                                    if($data["ketqua"]==$data["ID_HS"]) {
                                        $kq1="<p class='see-kq win'>WIN</p>";
                                        $kq2="<p class='see-kq lose'>LOSE</p>";
                                    } else if($data["ketqua"]==$data["ID_HS2"]){
                                        $kq1="<p class='see-kq lose'>LOSE</p>";
                                        $kq2="<p class='see-kq win'>WIN</p>";
                                    } else if($data["ketqua"]=="X"){
                                        $kq1="<p class='see-kq draw'>DRAW</p>";
                                        $kq2="<p class='see-kq draw'>DRAW</p>";
                                    } else {
                                        $kq1="";
                                        $kq2="";
                                    }
                                    if($dem%2!=0) {
                                        echo"<tr style='background:#D1DBBD'>";
                                    } else {
                                        echo"<tr>";
                                    }
                                    ?>
                                    <td class="hidden"><span><?php echo ($dem+1);?></span></td>
                                    <td><span><?php echo $data1["fullname"]."<br />".$data1["cmt"]." (".tinh_diemtb_month2($data["ID_HS"],$lmID).")";?><br /><span class="ketqua kq-1"></span></span><?php echo $kq1; ?></td>
                                    <td><span>
                                    <?php
                                        if($data["chap"]<0) {
                                            echo"B chấp A: ".abs($data["chap"]);
                                        } else {
                                            echo"A chấp B: ".abs($data["chap"]);
                                        }
                                    ?>
                                    </span></td>
                                    <td><span><?php echo $data2["fullname"]."<br />".$data2["cmt"]." (".tinh_diemtb_month2($data["ID_HS2"],$lmID).")"; ?><br /><span class="ketqua kq-2"></span></span><?php echo $kq2; ?></td>
                                    <td class="hidden"><span>
                                    <?php
                                        if($data["status"]=="pending") {
                                            echo"Đang chờ chấp nhận";
                                        } else if($data["status"]=="accept") {
                                            echo"Đang chờ kết quả";
                                        } else if($data["status"]=="cancle") {
                                            echo"Từ chối";
                                        } else {
                                            echo"Hoàn thành";
                                        }
                                    ?>
                                    </span></td>
                                    <td class="hidden">
                                    <?php
                                        if($data["status"]=="done") {
                                            echo"<input class='submit view' data-tdID='$data[ID_STT]' type='submit' value='Xem kết quả' />
                                            <input class='submit again' data-tdID='$data[ID_STT]' type='submit' value='Xét lại' />";
                                        } else if($data["status"]=="pending" || $data["status"]=="accept") {
                                            echo"<input class='submit delete' data-tdID='$data[ID_STT]' type='submit' value='Xóa' />";
                                        } else {

                                        }
                                    ?>
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
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
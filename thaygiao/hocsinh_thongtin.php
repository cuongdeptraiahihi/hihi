<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 600);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
        $lmID=$_GET["lm"];
    } else {
        $lmID=0;
    }
	if(isset($_GET["mon"]) && is_numeric($_GET["mon"])) {
		$monID=$_GET["mon"];
	} else {
		$monID=0;
	}
	$lmID=$_SESSION["lmID"];
	$monID=$_SESSION["mon"];
	if(isset($_GET["truong"]) && is_numeric($_GET["truong"])) {
		$truongID=$_GET["truong"];
	} else {
		$truongID=0;
	}
	if(isset($_GET["sort"])) {
		$sort=$_GET["sort"];
	} else {
		$sort="hoc-desc";
	}
	$lop_mon_name=get_lop_mon_name($lmID);
    $_SESSION["bieudo"] = 0;
	if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) {
		$nam_sl=count_hs_gender(1, $lmID);
		$nu_sl=count_hs_gender(0, $lmID);
		$nam_nghi=count_hs_nghi_gender(1, $lmID);
		$nu_nghi=count_hs_nghi_gender(0, $lmID);
		
		$hoc_array=$nghi_array=array();
		$hoc_total=0;$nghi_total=0;
		$result1=get_all_truong();
		while($data1=mysqli_fetch_assoc($result1)) {
			$all=$all_nghi=0;
            $query2 = "SELECT hocsinh.ID_HS FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' WHERE hocsinh.truong='$data1[ID_T]'";
            $result2=mysqli_query($db,$query2);
			$all=mysqli_num_rows($result2);

            $query2 = "SELECT hocsinh.ID_HS FROM hocsinh INNER JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' WHERE hocsinh.truong='$data1[ID_T]'";
			$result2=mysqli_query($db,$query2);
			$all_nghi=mysqli_num_rows($result2);
			
			$nghi_total+=$all_nghi;
			$hoc_total+=$all-$all_nghi;
			
			$hoc_array[]=array(
				"id" => $data1["ID_T"],
				"name" => $data1["name"],
				"hoc" => $all-$all_nghi,
				"nghi" => $all_nghi
			);
		}
		$n=count($hoc_array);
		if($sort=="hoc-desc") {
			usort($hoc_array, "hoc_sort_desc");
		} else if ($sort=="hoc-asc"){
			usort($hoc_array, "hoc_sort_asc");
		} else if($sort=="nghi-desc") {
			usort($hoc_array, "nghi_sort_desc");
		} else {
			usort($hoc_array, "nghi_sort_asc");
		}
	}
		
		$i_phone=0;$bo_phone=0;$me_phone=0;$i_phone2=0;$bo_phone2=0;$me_phone2=0;$add_face=0;$none_face=0;$no_add=0;$has_van=0;$no_van=0;
		$idTemp=0;
		$check1=$check2=$check3=1;
		$phone_array=$face_array=array();
        $query4 = "SELECT h.ID_HS,h.vantay,h.facebook,h.sdt,h.sdt_bo,h.sdt_me,c.sdt_check,n.ID_N FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
        LEFT JOIN check_phone AS c ON c.ID_HS=h.ID_HS AND c.sdt_check IN (h.sdt,h.sdt_bo,h.sdt_me) 
        LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC";
        $result4=mysqli_query($db,$query4);
		while($data4=mysqli_fetch_assoc($result4)) {
			if($data4["ID_HS"]!=$idTemp) {
				if($data4["facebook"]!="X" && $data4["facebook"]!="") {
					$face_array["$data4[ID_HS]"]=1;
				} else if($data4["facebook"]=="X") {
					$face_array["$data4[ID_HS]"]=2;
				} else {
					$face_array["$data4[ID_HS]"]=0;
				}
				if(!isset($data4["ID_N"])) {
                    if($data4["facebook"]!="X" && $data4["facebook"]!="") {
                        $add_face++;
                    } else if($data4["facebook"]=="X") {
                        $none_face++;
                    } else {
                        $no_add++;
                    }
					if($data4["vantay"]!=0) {
						$has_van++;
					} else {
						$no_van++;
					}
				}
				if($check1==0) {
					$i_phone2++;
				}
				if($check2==0) {
					$bo_phone2++;
				}
				if($check3==0) {
					$me_phone2++;
				}
				if($idTemp!=0) {
					$phone_array[$idTemp]=array(
						"i_phone" => $check1,
						"bo_phone" => $check2,
						"me_phone" => $check3
					);
				}
				$check1=$check2=$check3=0;
				$idTemp=$data4["ID_HS"];
			}
			if($data4["sdt"]=="X" || $data4["sdt"]=="") {
				$check1=2;
			}
			if($check1!=2 && $data4["sdt"]==$data4["sdt_check"]) {
				$i_phone++;
				$check1=1;
			}
			if($data4["sdt_bo"]=="X" || $data4["sdt_bo"]=="") {
				$check2=2;
			}
			if($check2!=2 && $data4["sdt_bo"]==$data4["sdt_check"]) {
				$bo_phone++;
				$check2=1;
			}
			if($data4["sdt_me"]=="X" || $data4["sdt_me"]=="X") {
				$check3=2;
			}
			if($check3!=2 && $data4["sdt_me"]==$data4["sdt_check"]) {
				$me_phone++;
				$check3=1;
			}
		}
		if($check1==0) {
			$i_phone2++;
		}
		if($check2==0) {
			$bo_phone2++;
		}
		if($check3==0) {
			$me_phone2++;
		}
		$phone_array[$idTemp]=array(
			"i_phone" => $check1,
			"bo_phone" => $check2,
			"me_phone" => $check3
		);
		
	if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) {
		$nghi=$nghi_hop=$hoc_hop=$nghi_kohop=$hoc_kohop=0;
		$n_h=0;
        $query5 = "SELECT h.ID_HS,n.ID_N,c.ID_H FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
        LEFT JOIN check_hop AS c ON c.ID_HS=h.ID_HS 
        LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' 
        ORDER BY h.cmt ASC";
		$result5=mysqli_query($db,$query5);
		while($data5=mysqli_fetch_assoc($result5)) {
			/*if($data5["ID_N"] && $data5["ID_H"]) {
				$nghi_hop++;
			}
			if($data5["ID_N"] && $data5["ID_H"]=="") {
				$nghi_kohop++;
			}*/
			if(isset($data5["ID_N"])) {
			    $nghi++;
            }
			if(!isset($data5["ID_N"]) && $data5["ID_H"]) {
				$hoc_hop++;
			}
			if(!isset($data5["ID_N"]) && $data5["ID_H"]=="") {
				$hoc_kohop++;
			}
			$n_h++;
		}
		
		if($n_h!=0) {
			/*$nghi_hop=$nghi_hop/$n_h;
			$nghi_kohop=$nghi_kohop/$n_h;*/
			$nghi=$nghi/$n_h;
			$hoc_hop=$hoc_hop/$n_h;
			$hoc_kohop=$hoc_kohop/$n_h;
		}
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THỐNG KÊ THÔNG TIN HỌC SINH</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;}.loai-option {display:none;}.main-top > ul {float:left;}.main-top #main-left2 {width:50%;}.main-top #main-left2 li {width:100%;height:380px;margin-bottom:25px;}.main-top #main-left2 li ol {display:inline-table;width:43%;height:100%;padding:0 6% 0 0;}.chart-top {width:100%;background:#3E606F;text-align:center;height:35px;}.chart-top span {color:#FFF;text-transform:uppercase;font-size:14px;line-height:35px;}#main-left2 li ol .chart-main {width:100%;height:310px;margin-top:20px;}.main-top #main-right {width:50%;}.main-top #main-right li {float:left;}#main-right li .chart-main2 > ol {text-align:center;background:#3E606F;height:30px;margin-top:5px;overflow:hidden;}#main-right li .chart-main2 > ol a {color:#FFF;font-size:12px;line-height:30px;}#list-danhsach {width:130% !important;background:#FFF;}#list-danhsach tr th > span {font-size:12px; !important;}#list-danhsach tr td > span {font-size:12px; !important;}#list-danhsach tr td span.nghi-info {background:#ffffa5;width:100%;position:absolute;z-index:9;left:0;bottom:0;}.check-phone > input {float:left;width:75%;}.check-phone > span {float:left;width:25%;}.edit-done {display:none;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script>
			$(document).ready(function() {
				$("#xuat-ok").click(function() {
					if(confirm("Bạn có chắc chắn ko? Quá trình sẽ mất nhiều thời gian!")) {
						return true;
					} else {
						return false;
					}
				});
				
				$("#list-danhsach").delegate("tr td > span.check-nghi > i.fa-check-square-o","click", function() {
				//$("#list-danhsach tr td > span.check-nghi > i.fa-check-square-o").click(function() {
					del_tr = $(this).closest("tr");
					hsID = $(this).attr("data-hsID");
					me_i = $(this);
					if(confirm("Bạn có chắc chắn cho học sinh này đi học trở lại?") && $.isNumeric(hsID) && hsID!=0) {
						del_tr.css("opacity","0.3");
						$.ajax({
							async: true,
							data: "hsID=" + hsID + "&lmID=" + <?php echo $lmID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
							success: function(result) {
								me_i.attr("class","fa fa-square-o");
								del_tr.css("opacity","1");
							}
						});
					} 
				});
				
				$("#list-danhsach").delegate("tr td > span.check-nghi > i.fa-square-o","click", function() {
				//$("#list-danhsach tr td > span.check-nghi > i.fa-square-o").click(function() {
					del_tr = $(this).closest("tr");
					hsID = $(this).attr("data-hsID");
					me_i = $(this);
					if(confirm("Bạn có chắc chắn cho học sinh này nghỉ học?") && $.isNumeric(hsID) && hsID!=0) {
						del_tr.css("opacity","0.3");
						$.ajax({
							async: true,
							data: "hsID2=" + hsID + "&lmID2=" + <?php echo $lmID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
							success: function(result) {
								me_i.attr("class","fa fa-check-square-o");
							}
						});
					} 
				});
				
				$("#list-danhsach").delegate("tr td > span.check-hop > i.fa-check-square-o","click",function() {
				//$("#list-danhsach tr td > span.check-hop > i.fa-check-square-o").click(function() {
					del_tr = $(this).closest("tr");
					hsID = $(this).attr("data-hsID");
					me_i = $(this);
					if($.isNumeric(hsID) && hsID!=0) {
						del_tr.css("opacity","0.3");
						$.ajax({
							async: true,
							data: "hsID4=" + hsID,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
							success: function(result) {
								me_i.attr("class","fa fa-square-o");
								del_tr.css("opacity","1");
							}
						});
					} 
				});
				
				$("#list-danhsach").delegate("tr td > span.check-hop > i.fa-square-o","click",function() {
				//$("#list-danhsach tr td > span.check-hop > i.fa-square-o").click(function() {
					del_tr = $(this).closest("tr");
					hsID = $(this).attr("data-hsID");
					me_i = $(this);
					if($.isNumeric(hsID) && hsID!=0) {
						del_tr.css("opacity","0.3");
						$.ajax({
							async: true,
							data: "hsID5=" + hsID,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
							success: function(result) {
								me_i.attr("class","fa fa-check-square-o");
								del_tr.css("opacity","1");
							}
						});
					} 
				});
				
				$("#list-danhsach").delegate("tr td.check-phone > span i.fa-check-square-o","click",function() {
					del_tr = $(this).closest("tr");
					hsID = $(this).attr("data-hsID");
					sdt = $(this).attr("data-sdt");
					me_i = $(this);
					del_tr.css("opacity","0.3");
					$.ajax({
						async: true,
						data: "hsID3=" + hsID + "&sdt=" + sdt,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
						success: function(result) {
							me_i.attr("class","fa fa-square-o");
							del_tr.css("opacity","1");
						}
					});
				});
				
				$("#list-danhsach").delegate("tr td.check-phone > span i.fa-square-o","click",function() {
				//$("#list-danhsach tr td > span.check-phone > i.fa-square-o").click(function() {
					del_tr = $(this).closest("tr");
					hsID = $(this).attr("data-hsID");
					sdt = $(this).attr("data-sdt");
					me_i = $(this);
					del_tr.css("opacity","0.3");
					$.ajax({
						async: true,
						data: "hsID1=" + hsID + "&sdt=" + sdt,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
						success: function(result) {
							me_i.attr("class","fa fa-check-square-o");
							del_tr.css("opacity","1");
						}
					});
				});
				
				$("#select-loai").change(function() {
                    $(".loai-option").fadeOut("fast");;
					if($(this).val()==1) {
						$(".khoang-ma").fadeIn("fast");
					} else if($(this).val()==2) {
						$(".only-ma").fadeIn("fast");
					} else if($(this).val()==4) {
                        $(".only-birth").fadeIn("fast");
					} else {

                    }
				});
				
				if($("#select-loai").val()==2) {
					$(".only-ma").fadeIn("fast");
				}
				
				if($("#select-loai").val()==1) {
					$(".khoang-ma").fadeIn("fast");
				}

                if($("#select-loai").val()==4) {
                    $(".only-birth").fadeIn("fast");
                }
				
				$(".edit-datein").datepicker({ dateFormat: 'dd/mm/yy' });
				$(".edit-birth").datepicker({ dateFormat: 'dd/mm/yy',changeMonth: true,
            changeYear: true });
			
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
			});
		</script>
        <script type="text/javascript">
            document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
		<?php if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) { ?>
		window.onload = function () {
            var myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false});
		var chart = new CanvasJS.Chart("chart1",
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
				horizontalAlign: "center",
				verticalAlign: "bottom",
				fontWeight: "bold",
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
				click: function(e) {
					window.location.href=e.dataPoint.content;
				},
				toolTipContent: "<a href = {content}>{name}: {y}</a>",  
				dataPoints: [
					{ y: <?php echo $hoc_hop; ?>, color: "#eacdb7", name: "Đang học - Đã họp", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/<?php echo $lmID."/".$monID; ?>/1/1/"},
					{ y: <?php echo $hoc_kohop; ?>, color: "#3E606F", name: "Đang học - Chưa họp", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/<?php echo $lmID."/".$monID; ?>/1/0/"},
					{ y: <?php echo $nghi; ?>, color: "#96c8f3", name: "Nghỉ học", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/<?php echo $lmID."/".$monID; ?>/0/1/"},
				]
			}]
		});
		chart.render();
		
		var chart2 = new CanvasJS.Chart("chart2",
		{
			animationEnabled: true,
			interactivityEnabled: true,
			theme: "theme2",
			toolTip: {
				shared: true
			},
			backgroundColor: "",
			axisX: {
				labelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 12, 
				labelText: "{name}",
				labelMaxWidth: 50,
			},
			axisY: {
				gridThickness: 1,
				indexLabelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 14,
				labelFontWeight: "normal",
			},
			dataPointMaxWidth: 43,
			data: [
			{
				type: "column",
				showInLegend: false,
				indexLabelPlacement: "inside",
				indexLabelFontSize: 16,
				indexLabelFontColor: "#FFF",
				yValueFormatString: "#",
				indexLabel: "{y}",
				click: function(e) {
					window.location.href=e.dataPoint.content;
				},
				labelFontSize: 16,
				toolTipContent: "<a href = {content}>{label}: {y}</a>",  
				dataPoints: [
					{ y: <?php echo $none_face; ?>, color: "#96c8f3", label: "KHÔNG DÙNG", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-face/<?php echo $lmID."/".$monID; ?>/X/"},
					{ y: <?php echo $no_add; ?>, color: "red", label: "CHƯA ADD", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-face/<?php echo $lmID."/".$monID; ?>/0/"},
					{ y: <?php echo $add_face; ?>, color: "#e7a53f", label: "ĐÃ ADD", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-face/<?php echo $lmID."/".$monID; ?>/1/"}
				]
			},
			]
		});
		chart2.render();
		
		var chart3 = new CanvasJS.Chart("chart3",
		{
			animationEnabled: true,
			interactivityEnabled: true,
			legendText: "{label}",
			theme: "theme2",
			toolTip: {
				shared: true
			},
			backgroundColor: "",
			legend: {
				fontSize: 12,
				fontFamily: "Arial",
				fontColor: "#3E606F",
				horizontalAlign: "center",
				verticalAlign: "bottom",
				itemWidth: 200,
				fontWeight: "bold"
			},
			axisX: {
				labelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 12, 
			},
			axisY: {
				gridThickness: 1,
				indexLabelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 14,
				labelFontWeight: "normal",
			},
			dataPointMaxWidth: 47,
			data: [
			{
				type: "stackedColumn",
				indexLabelPlacement: "inside",
				indexLabelFontSize: 16,
				indexLabelFontColor: "#FFF",
				yValueFormatString: "#",
				showInLegend: true,
				indexLabel: "{y}",
				labelFontSize: 16,
				color: "#e7a53f",
				click: function(e) {
					window.location.href=e.dataPoint.content;
				},
				name: "ĐÃ NGHỈ",
				toolTipContent: "<a href = {content}> NGHỈ HỌC: {y}</a>",  
				dataPoints: [
					{ y: <?php echo $nam_nghi; ?>, label: "NAM", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-nghi/<?php echo $lmID."/".$monID; ?>/1/0/"},
					{ y: <?php echo $nu_nghi; ?>, label: "NỮ", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-nghi/<?php echo $lmID."/".$monID; ?>/0/0/"},
				]
			},
			{
				type: "stackedColumn",
				indexLabelPlacement: "inside",
				indexLabelFontSize: 16,
				indexLabelFontColor: "#FFF",
				yValueFormatString: "#",
				showInLegend: true,
				indexLabel: "{y}",
				color: "#96c8f3",
				name: "ĐANG HỌC",
				click: function(e) {
					window.location.href=e.dataPoint.content;
				},
				toolTipContent: "<a href = {content}> ĐANG HỌC: {y}</a>",  
				dataPoints: [
					{ y: <?php echo ($nam_sl-$nam_nghi); ?>, label: "NAM", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-nghi/<?php echo $lmID."/".$monID; ?>/1/1/"},
					{ y: <?php echo ($nu_sl-$nu_nghi); ?>, label: "NỮ", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-nghi/<?php echo $lmID."/".$monID; ?>/0/1/"},
				]
			},
			]
		});
		chart3.render();
		
		var chart4 = new CanvasJS.Chart("chart4",
		{
			animationEnabled: true,
			interactivityEnabled: true,
			legendText: "{label}",
			theme: "theme2",
			toolTip: {
				shared: true 
			},
			backgroundColor: "",
			legend: {
				fontSize: 12,
				fontFamily: "Arial",
				fontColor: "#3E606F",
				horizontalAlign: "center",
				verticalAlign: "bottom",
				itemWidth: 200,
				fontWeight: "bold"
			},
			axisX: {
				labelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 12, 
				labelMaxWidth: 50,
			},
			axisY: {
				gridThickness: 1,
				indexLabelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 14,
				labelFontWeight: "normal",
			},
			dataPointMaxWidth: 43,
			data: [
			{
				type: "stackedColumn",
				indexLabelPlacement: "inside",
				indexLabelFontSize: 16,
				indexLabelFontColor: "#FFF",
				yValueFormatString: "#",
				showInLegend: true,
				indexLabel: "{y}",
				labelFontSize: 16,
				color: "#3E606F",
				name: "CHƯA KIỂM TRA", 
				click: function(e) {
					window.location.href=e.dataPoint.content;
				},
				toolTipContent: "<a href = {content}> CHƯA KIỂM TRA: {y}</a>",  
				dataPoints: [
					{ y: <?php echo $i_phone2; ?>, label: "SỐ CÁ NHÂN", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sdt/<?php echo $lmID."/".$monID; ?>/1/0/"},
					{ y: <?php echo $bo_phone2; ?>, label: "SỐ ĐT BỐ", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sdt/<?php echo $lmID."/".$monID; ?>/2/0/"},
					{ y: <?php echo $me_phone2; ?>, label: "SỐ ĐT MẸ", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sdt/<?php echo $lmID."/".$monID; ?>/3/0/"},
				]
			},
			{
				type: "stackedColumn",
				indexLabelPlacement: "inside",
				indexLabelFontSize: 16,
				indexLabelFontColor: "#FFF",
				yValueFormatString: "#",
				showInLegend: true,
				indexLabel: "{y}",
				labelFontSize: 16,
				color: "#96c8f3",
				name: "ĐÃ KIỂM TRA",
				click: function(e) {
					window.location.href=e.dataPoint.content;
				},
				toolTipContent: "<a href = {content}> ĐÃ KIỂM TRA: {y}</a>",
				dataPoints: [
					{ y: <?php echo $i_phone; ?>, label: "SỐ CÁ NHÂN", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sdt/<?php echo $lmID."/".$monID; ?>/1/1/"},
					{ y: <?php echo $bo_phone; ?>, label: "SỐ ĐT BỐ", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sdt/<?php echo $lmID."/".$monID; ?>/2/1/"},
					{ y: <?php echo $me_phone; ?>, label: "SỐ ĐT MẸ", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sdt/<?php echo $lmID."/".$monID; ?>/3/1/"},
				]
			},
			]
		});
		chart4.render();
		
		var chart5 = new CanvasJS.Chart('chart5',
		{
			axisY:{
				labelFontColor: '#FFF',
				labelFontSize: 14,
				labelFontWeight: 'normal',
				labelFontFamily:'Arial' ,
				gridColor: 'Silver',
				tickColor: 'silver',
				lineThickness: 1,
				gridThickness: 1,
				minimum: <?php echo -($hoc_array[0]["nghi"]+10); ?>,
				maximum: 0
			},
			theme: 'theme2',
			interactivityEnabled: false,
			axisX:{
				labelFontColor: '',
				labelFontSize: 14,
				labelFontWeight: 'normal',
				labelFontFamily:'Arial' ,
				labelMaxWidth: 40,
				gridColor: "Silver",
				tickColor: "#FFF",
			},
			backgroundColor: "",
			animationEnabled: true,
			toolTip:{
				enabled: false
			},
			dataPointMaxWidth: 30,
			data:[
			{        
				type: 'bar',
				showInLegend: false, 
				name: 'TỈ LỆ HỌC SINH ĐÃ NGHỈ',
				indexLabelFontColor: '#96c8f3',
				indexLabelPlacement: 'outside',
				indexLabelOrientation: 'horizontal',
				indexLabelFontFamily:'Arial' ,
				indexLabelFontSize: 14,
				indexLabel: "{y}",
				yValueFormatString: "#",
				color: '#96c8f3',
				dataPoints: [
				<?php
					$cout_nghi=0;
					$i=($n>25?25:$n)-1;
					while($i>=0) {
						if($nghi_total!=0) {
							echo"{y: ".(-$hoc_array[$i]["nghi"])."},";
						} else {
							echo"{y: 0},";
						}
						$cout_nghi++;
						$i--;
					}
				?>
				]
			}
			],
		});
		chart5.render();
		
		var chart7 = new CanvasJS.Chart('chart7',
		{
			axisY:{
				labelFontColor: '#FFF',
				labelFontSize: 14,
				labelFontWeight: 'normal',
				labelFontFamily:'Arial' ,
				gridColor: 'Silver',
				tickColor: 'silver',
				lineThickness: 1,
				gridThickness: 1,
				maximum: <?php echo ($hoc_array[0]["hoc"]+50); ?>,
				minimum: 0
			},
			theme: 'theme2',
			interactivityEnabled: false,
			axisX:{
				labelFontColor: '',
				labelFontSize: 14,
				labelFontWeight: 'normal',
				labelFontFamily:'Arial' ,
				labelMaxWidth: 40,
				gridColor: "Silver",
				tickColor: "#FFF",
			},
			backgroundColor: "",
			animationEnabled: true,
			toolTip:{
				enabled: false
			},
			dataPointMaxWidth: 30,
			data:[
			{        
				type: 'bar',
				showInLegend: false, 
				name: 'TỈ LỆ HỌC SINH ĐANG HỌC',
				indexLabelFontColor: '#fda825',
				indexLabelPlacement: 'outside',
				indexLabelOrientation: 'horizontal',
				indexLabelFontFamily:'Arial' ,
				indexLabelFontSize: 14,
				indexLabel: "{y}",
				yValueFormatString: "#",
				color: '#fda825',
				dataPoints: [
				<?php
					$count_hoc=0;
					$i=($n>25?25:$n)-1;
					while($i>=0) {
						if($hoc_total!=0) {
							echo"{y: ".($hoc_array[$i]["hoc"])."},";
						} else {
							echo"{y: 0},";
						}
						$count_hoc++;
						$i--;
					}
				?>
				]
			}
			],
		});
		chart7.render();
		
		var chart8 = new CanvasJS.Chart("chart8",
		{
			animationEnabled: true,
			interactivityEnabled: true,
			theme: "theme2",
			toolTip: {
				shared: true
			},
			backgroundColor: "",
			axisX: {
				labelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 12, 
				labelText: "{name}",
				labelMaxWidth: 50,
			},
			axisY: {
				gridThickness: 1,
				indexLabelFontFamily:"Arial" ,
				gridColor: "Silver",
				tickColor: "silver",
				labelFontColor: "#3E606F",
				labelFontSize: 14,
				labelFontWeight: "normal",
			},
			dataPointMaxWidth: 43,
			data: [
			{
				type: "column",
				showInLegend: false,
				indexLabelPlacement: "inside",
				indexLabelFontSize: 16,
				indexLabelFontColor: "#FFF",
				yValueFormatString: "#",
				indexLabel: "{y}",
				click: function(e) {
					window.location.href=e.dataPoint.content;
				},
				labelFontSize: 16,
				toolTipContent: "<a href = {content}>{label}: {y}</a>",  
				dataPoints: [
					{ y: <?php echo $no_van; ?>, color: "red", label: "THIẾU", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-van/<?php echo $lmID."/".$monID; ?>/0/"},
					{ y: <?php echo $has_van; ?>, color: "#e7a53f", label: "ĐÃ CÓ", content: "http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-van/<?php echo $lmID."/".$monID; ?>/1/"}
				]
			},
			]
		});
		chart8.render();
				
		}
		<?php } else { ?>
            window.onload = function () {
                var myScroll = new IScroll('#main-wapper', {scrollX: true, scrollY: false, mouseWheel: false});
            }
        <?php } ?>
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
					$xuat=false;
					if(isset($_POST["xuat-ok"])) {
						$xuat=true;
						include ("include/PHPExcel/IOFactory.php");
						$objPHPExcel = new PHPExcel(); 
						$objPHPExcel->setActiveSheetIndex(0); 
					}
				
					$loai=$ma1=$ma2=$ma=$birth=NULL;
					if(isset($_POST["set-ok"])) {
						
						$loai=$_POST["select-loai"];
						if($loai==1) {
							if(isset($_POST["ma1"])) {
								$ma1=$_POST["ma1"];
							}
							if(isset($_POST["ma2"])) {
								$ma2=$_POST["ma2"];
							}
							if($ma1 && $ma2) {
								header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-loai/$lmID/$monID/$loai/$ma1/$ma2/");
								exit();
							} else {
								header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-loai/$lmID/$monID/0/");
								exit();
							}
						} else if($loai==2) {
							if(isset($_POST["ma"])) {
								$ma=$_POST["ma"];
                                header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-loai/$lmID/$monID/$loai/$ma/");
                                exit();
							}
						} else if($loai==4) {
						    if(isset($_POST["birth"])) {
						        $birth=$_POST["birth"];
                                header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-birth/$lmID/$monID/$loai/$birth/");
                                exit();
                            }
						} else {
                            header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-loai/$lmID/$monID/$loai/");
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
						if($loai==4) {
						    if(isset($_GET["birth"])) {
						        $sn=format_month_db($_GET["birth"]);
                            } else {
                                $sn=0;
                            }
                        }
					} else {
						$loai=$sn=0;
						$ma=$ma1=$ma2="none";
					}
				?>
                
                <div id="main-mid">
                	<h2>Thống kê thông tin học sinh <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                        <?php if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) { ?>
                      		<div class="main-top">
<!--                            	<div style="position:absolute;left:0;top:0;"><input type="submit" class="submit" id="bieu-do" value="Biểu đồ" /></div>-->
                                <div style="position:absolute;right:0;top:0;">
                                    <form action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post">
                                        <input type="submit" class="submit" name="xuat-ok" value="Xuất Excel trang hiện tại" id="xuat-ok" />
                                    </form>
                                </div>
                            	<ul id="main-left2">
                                	<li>
                                    	<ol>
                                        	<div class="chart-top"><span>Tỷ lệ nghỉ học</span></div>
                                            <div class="chart-main" id="chart1">
                                            	
                                            </div>
                                        </ol>
                                        <ol>
                                        	<div class="chart-top"><span>Facebook</span></div>
                                            <div class="chart-main" id="chart2">
                                            	
                                            </div>
                                        </ol>
                                    </li>
                                    <li>
                                    	<ol>
                                        	<div class="chart-top"><span>Số lượng nam - nữ</span></div>
                                            <div class="chart-main" id="chart3">
                                            	
                                            </div>
                                        </ol>
                                        <ol>
                                        	<div class="chart-top"><span>Số điện thoại</span></div>
                                            <div class="chart-main" id="chart4">
                                            	
                                            </div>
                                        </ol>
                                    </li>
                                    <li>
                                    	<ol>
                                        	<div class="chart-top"><span>Vân tay</span></div>
                                            <div class="chart-main" id="chart8">

                                            </div>
                                        </ol>
                                    </li>
                                </ul>
                                <ul id="main-right">
                                	<li style="width:37.5%;">
                                    	<div class="chart-top"><span>
                                        <?php if($sort=="nghi-desc") { ?>
                                        	<i class="fa fa-sort-amount-asc" style="margin-right:15px;cursor:pointer" onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sort/<?php echo $lmID."/".$monID; ?>/nghi-asc/'"></i>
                                        <?php } else { ?>
                                       		<i class="fa fa-sort-amount-desc" style="margin-right:15px;cursor:pointer" onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sort/<?php echo $lmID."/".$monID; ?>/nghi-desc/'"></i>
										<?php } ?>
                                        Tỉ lệ hs đã nghỉ</span></div>
                                        <div class="chart-main2" id="chart5" style="margin-top:-7.5px;height:<?php echo ($cout_nghi*37); ?>px;margin-right:-3px;">
                                            	
                                       	</div>
                                    </li>
                                    <li style="width:25%;">
                                    	<div class="chart-top" style="background:#3E606F;cursor:pointer;" onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/<?php echo $lmID."/".$monID; ?>/'"><span>Trường</span></div>
                                        <div class="chart-main2">
                                        <?php
											$tr=0;
											$tr_max=$n>25?25:$n;
											while($tr<$tr_max) {
												echo"<ol><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-truong/$lmID/$monID/".$hoc_array[$tr]["id"]."/'>".substr($hoc_array[$tr]["name"],4)."</a></ol>";
												$tr++;
											}
										?>
                                       	</div>
                                    </li>
                                    <li style="width:37.5%;">
                                    	<div class="chart-top"><span>Tỉ lệ hs đang học
                                        <?php if($sort=="hoc-desc") { ?>
                                        	<i class="fa fa-sort-amount-asc" style="margin-left:15px;cursor:pointer" onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sort/<?php echo $lmID."/".$monID; ?>/hoc-asc/'"></i>
                                        <?php } else { ?>
                                       		<i class="fa fa-sort-amount-desc" style="margin-left:15px;cursor:pointer" onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin-sort/<?php echo $lmID."/".$monID; ?>/hoc-desc/'"></i>
										<?php } ?>
                                        </span></div>
                                        <div class="chart-main2" id="chart7" style="margin-top:-7.5px;height:<?php echo ($count_hoc*37); ?>px;margin-left:-22px;">
                                            	
                                       	</div>
                                    </li>
                                </ul>
                            </div>
                            <?php } else { ?>
                            	<div class="main-top">
                                	<div style="position:absolute;left:0;top:0;"><input type="submit" class="submit" id="bieu-do" value="Biểu đồ" /></div>
                                    <div style="position:absolute;right:0;top:0;">
                                    	<form action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post">
                                        	<input type="submit" class="submit" name="xuat-ok" value="Xuất Excel trang hiện tại" id="xuat-ok" />
                                  		</form>
                                    </div>
                               	</div>
							<?php } ?>
                            <div class="main-bot">
                            <?php
								if($truongID==0) {
							?>
                            	<form action="http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/<?php echo $lmID."/".$monID; ?>/" method="post">
                            	<table class="table">
                                	<tr>
                                    	<th style="width:25%;">
                                        	<select class="input" id="select-loai" style="height:auto;" name="select-loai">
                                            	<option value="0" <?php if($loai==0){echo"selected='selected'";} ?>>STT tăng dần (30/trang)</option>
                                                <!--<option value="1" <?php if($loai==1){echo"selected='selected'";} ?>>Tìm theo khoảng STT</option>
                                                <option value="2" <?php if($loai==2){echo"selected='selected'";} ?>>Tìm theo mã cụ thể</option>-->
                                                <option value="3" <?php if($loai==3){echo"selected='selected'";} ?>>Tất cả (không khuyến nghị)</option>
                                                <option value="4" <?php if($loai==4){echo"selected='selected'";} ?>>Sinh nhật</option>
                                            </select>
                                        </th>
                                        <th class="khoang-ma loai-option" style="width:16.25%;"><span>STT đầu</span></th>
                                        <th class="khoang-ma loai-option" style="width:16.25%;"><input type="text" class="input" name="ma1" id="ma1" placeholder="2" /></th>
                                        <th class="khoang-ma loai-option" style="width:16.25%;"><span>STT cuối</span></th>
                                        <th class="khoang-ma loai-option" style="width:16.25%;"><input type="text" class="input" name="ma2" id="ma2" placeholder="7" /></th>
                                        <th class="only-ma loai-option" style="width:32.5%;"><span>Mã học sinh</span></th>
                                        <th class="only-ma loai-option" style="width:32.5%;"><input type="text" class="input" name="ma" id="ma" placeholder="99-0023" /></th>
                                        <th class="only-birth loai-option" style="width:32.5%;"><span>Chọn tháng</span></th>
                                        <th class="only-birth loai-option" style="width:32.5%;">
                                            <select class="input" id="birth" name="birth" style="height: auto;width:100%;">
                                                <?php
                                                    for($i=1;$i<=12;$i++) {
                                                        echo"<option value='$i'>Tháng $i</option>";
                                                    }
                                                ?>
                                            </select>
                                        </th>
                                    	<th style="width:10%;"><input type="submit" class="submit" name="set-ok" value="Lọc" id="set-ok" /></th>
                                    </tr>
                                </table>
                                </form>
                                <?php } ?>
                                <div class="clear" id="main-wapper" style="width:100%;overflow:auto;">
                                <div></div>
                                <table class="table" id="list-danhsach" style="margin-top:25px;">
                                	<tr style="background:#3E606F;">
                                    	<th style="width:4%;"><span>Nghỉ</span></th>
                                        <th style="width:4%;"><span>Họp</span></th>
                                    	<th style="width:6%;"><span>Thời điểm học</span></th>
                                        <th style="width:4%;"><span>STT</span></th>
                                        <th style="width:8%;"><span>Họ và tên</span></th>
                                        <th style="width:4%;"><span>Mã số</span></th>
                                        <th style="width:4%;"><span>Vân tay</span></th>
                                        <th style="width:6%;"><span>Facebook</span></th>
                                        <th style="width:4%;"><span>Sinh nhật</span></th>
                                        <th style="width:4%;"><span>Giới tính</span></th>
                                        <th style="width:9%;"><span>Trường</span></th>
                                        <th style="width:6%;"><span>Số cá nhân</span></th>
                                        <th style="width:6%;"><span>Số ĐT Bố</span></th>
                                        <th style="width:6%;"><span>Số ĐT Mẹ</span></th>
                                    </tr>
                                    <?php
										$rowCount = 1; 
										$col = 'A';
										if($xuat) {
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Nghỉ");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họp");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Thời điểm học");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "STT");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họ và tên");$col++;
                                            $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tên ko dấu");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã số");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Vân tay");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Facebook");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Sinh nhật");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Giới tính");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Trường");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Số cá nhân");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họ tên Bố");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Số ĐT Bố");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họ tên Mẹ");$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Số ĐT Mẹ");$col++;
										}
									
										$dem=0;
										if($loai==0) {
                                            if (isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
                                                $position = $_GET["begin"];
                                            } else {
                                                $position = 0;
                                            }
                                            $display = 30;
                                            $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') ORDER BY hocsinh.cmt ASC";
										} else if($loai==2) {
                                            $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.cmt LIKE '%$ma%'";
                                        } else if($loai==1) {
                                            $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.ID_HS BETWEEN '$ma1' AND '$ma2' ORDER BY hocsinh.cmt ASC";
                                        } else if($loai==4) {
                                            $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.birth LIKE '%-$sn-%' ORDER BY hocsinh.cmt ASC";
                                        } else {
                                            $query="SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong ORDER BY hocsinh.cmt ASC";
                                        }
										if($truongID!=0) {
                                            $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' INNER JOIN truong ON truong.ID_T='$truongID' WHERE hocsinh.truong='$truongID' ORDER BY hocsinh.cmt ASC";
                                        }
										if(isset($_GET["van"]) && is_numeric($_GET["van"])) {
											$van=$_GET["van"];
											if($van==0) {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.vantay='0' ORDER BY hocsinh.cmt ASC";
											} else {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.vantay!='0' ORDER BY hocsinh.cmt ASC";
                                            }
										}
										if(isset($_GET["hoc"]) && isset($_GET["hop"]) && is_numeric($_GET["hoc"]) && is_numeric($_GET["hop"])) {
											$hoc=$_GET["hoc"];
											$hop=$_GET["hop"];
											if($hoc==0) {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh INNER JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong ORDER BY hocsinh.cmt ASC";
                                            } else {
												if($hop==0) {
                                                    $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh_nghi.ID_N IS NULL AND check_hop.ID_H IS NULL ORDER BY hocsinh.cmt ASC";
                                                } else {
                                                    $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' INNER JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh_nghi.ID_N IS NULL ORDER BY hocsinh.cmt ASC";
                                                }
											}
										} else {
											$hoc=NULL;
											$hop=NULL;
										}
										if(isset($_GET["sdt"]) && isset($_GET["check"]) && is_numeric($_GET["sdt"]) && is_numeric($_GET["check"])) {
											$sdt=$_GET["sdt"];
											if($sdt==1) {
												$need_sdt="hocsinh.sdt";
											} else if($sdt==2) {
												$need_sdt="hocsinh.sdt_bo";
											} else {
												$need_sdt="hocsinh.sdt_me";
											}
											$check=$_GET["check"];
											if($check==0) {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong LEFT JOIN check_phone ON check_phone.ID_HS=hocsinh.ID_HS AND check_phone.sdt_check=$need_sdt WHERE check_phone.ID_C IS NULL ORDER BY hocsinh.cmt ASC";
                                            } else {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong INNER JOIN check_phone ON check_phone.ID_HS=hocsinh.ID_HS AND check_phone.sdt_check=$need_sdt ORDER BY hocsinh.cmt ASC";
                                            }
										} else {
											$sdt=0;
										}
										if(isset($_GET["gender"]) && isset($_GET["hoc"]) && is_numeric($_GET["gender"]) && is_numeric($_GET["hoc"])) {
											$gioitinh=$_GET["gender"];
											$hoc=$_GET["hoc"];
											if($hoc==0) {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh INNER JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.gender='$gioitinh' ORDER BY hocsinh.cmt ASC";
                                            } else {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.gender='$gioitinh' AND hocsinh_nghi.ID_N IS NULL ORDER BY hocsinh.cmt ASC";
                                            }
										}  else {
											$gioitinh=NULL;
											$hoc=NULL;
										}
										if(isset($_GET["face"]) && (is_numeric($_GET["face"]) || $_GET["face"]=="X")) {
											$face=$_GET["face"];
											if($face=="X") {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.facebook='$face' ORDER BY hocsinh.cmt ASC";
                                            } else if ($face==1){
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.facebook!='' AND hocsinh.facebook!='X' ORDER BY hocsinh.cmt ASC";
                                            } else {
                                                $query = "SELECT DISTINCT hocsinh.ID_HS,hocsinh.cmt,hocsinh.vantay,hocsinh.fullname,hocsinh.namestring,hocsinh.birth,hocsinh.gender,hocsinh.facebook,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh.lop,hocsinh_mon.date_in,hocsinh_nghi.date,truong.name,check_hop.ID_H FROM hocsinh LEFT JOIN hocsinh_nghi ON hocsinh_nghi.ID_HS=hocsinh.ID_HS AND hocsinh_nghi.ID_LM='$lmID' LEFT JOIN check_hop ON check_hop.ID_HS=hocsinh.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' LEFT JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.facebook='' ORDER BY hocsinh.cmt ASC";
                                            }
										} else {
											$face=NULL;
										}
										
										$rowCount=2;
										
										$result=mysqli_query($db,$query);
										$tempId=0;

										while($data=mysqli_fetch_assoc($result)) {
											
											$col="A";
											
											if(isset($data["date"]) && $xuat) {
											    if(!isset($_GET["hoc"])) {
                                                    continue;
                                                }
											}

//											$lich_hoc=get_hs_lich_hoc($data["ID_HS"],$lmID,$monID)

											$gender=get_gender($data["gender"]);
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD;";
											} else {
												echo"<tr style='";
											}
											if($data["date_in"]!="0000-00-00") {
												$date_in=format_dateup($data["date_in"]);
											} else {
												$date_in="";
											}
											if($data["date"]) {
												echo"opacity:0.3;'><td><span class='nghi-hover check-nghi'><i class='fa fa-check-square-o' data-hsID='$data[ID_HS]' style='font-size:1.5em !important;'></i><span class='nghi-info'>".format_dateup($data["date"])."</span></span></td>";
												if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_dateup($data["date"]));$col++;}
											} else {
												echo"'><td><span class='check-nghi'><i class='fa fa-square-o' data-hsID='$data[ID_HS]' style='font-size:1.5em !important;'></i></span></td>";
												if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;}
											}
											if($data["ID_H"]) {
												echo"<td><span class='check-hop'><i class='fa fa-check-square-o' data-hsID='$data[ID_HS]' style='font-size:1.5em !important;'></i></span></td>";
												if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Có");$col++;}
											} else {
												echo"<td><span class='check-hop'><i class='fa fa-square-o' data-hsID='$data[ID_HS]' style='font-size:1.5em !important;'></i></span></td>";
												if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Không");$col++;}
											}
											$temp=explode("-",$data["namestring"]);
											$namestring="";
											for($i=0;$i<count($temp);$i++) {
												$namestring.=" ".ucwords($temp[$i]);
											}
											echo"
											<td><span>$date_in</span></td>
											<td><span>".($dem+1)."</span></td>
											<td><span>$data[fullname]</span></td>
											<td><span>$data[cmt]</span></td>
											<td><span>$data[vantay]</span></td>
											<td><a href='$data[facebook]'>Link</a></td>
											<td><span>";
											if($data["birth"]!="0000-00-00") {
												echo format_dateup($data["birth"])."</span></td>";
											} else {
												echo"X</span></td>";
											}
											
											$phone=format_phone($data["sdt"]);
											$phone_bo=format_phone($data["sdt_bo"]);
											$phone_me=format_phone($data["sdt_me"]);
											
											if($xuat){
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $date_in);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, ($dem+1));$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["fullname"]);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $namestring);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["cmt"]);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["vantay"]);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["facebook"]);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_dateup($data["birth"]));$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, get_gender($data["gender"]));$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["name"]);$col++;
												$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col".$rowCount, "$phone", PHPExcel_Cell_DataType::TYPE_STRING);$col++;
												$name_bo=$name_me="";
												$result0=get_phuhuynh_hs($data["ID_HS"],"X");
												while($data0=mysqli_fetch_assoc($result0)) {
													if($data0["gender"]==1) {
														$name_bo=$data0["name"];
													} else if($data0["gender"]==0) {
														$name_me=$data0["name"];
													}
												}
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $name_bo);$col++;
												$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col".$rowCount, "$phone_bo", PHPExcel_Cell_DataType::TYPE_STRING);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $name_me);$col++;
												$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col".$rowCount, "$phone_me", PHPExcel_Cell_DataType::TYPE_STRING);$col++;
                                                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $lich_hoc);$col++;
                                                //$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $phone);$col++;
												//$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $phone_bo);$col++;
												//$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $phone_me);$col++;
											}
											
											echo"<td><span>".get_gender($data["gender"])."</span></td>
											<td><span>".substr($data["name"],4)."</span></td>
											<td class='check-phone'><input type='text' class='input2 edit-phone' value='";
											if($phone_array[$data["ID_HS"]]["i_phone"]==1) {
												echo"$phone'/><span><i class='fa fa-check-square-o' data-hsID='$data[ID_HS]'  data-sdt='$data[sdt]' style='font-size:1.25em !important;margin-left:5px;'></i></span></td>";
											} else if($phone_array[$data["ID_HS"]]["i_phone"]==0) {
												echo"$phone'/><span><i class='fa fa-square-o' data-hsID='$data[ID_HS]'  data-sdt='$data[sdt]' style='font-size:1.25em !important;margin-left:5px;'></i></span></td>";
											} else {
												echo"$phone'/></td>";
											}
											echo"<td class='check-phone'><input type='text' class='input2 edit-phone-bo' value='";
											if($phone_array[$data["ID_HS"]]["bo_phone"]==1) {
												echo"$phone_bo'/><span><i class='fa fa-check-square-o' data-hsID='$data[ID_HS]'  data-sdt='$data[sdt_bo]' style='font-size:1.25em !important;margin-left:5px;'></i></span>";
											} else if($phone_array[$data["ID_HS"]]["bo_phone"]==0) {
												echo"$phone_bo'/><span><i class='fa fa-square-o' data-hsID='$data[ID_HS]'  data-sdt='$data[sdt_bo]' style='font-size:1.25em !important;margin-left:5px;'></i></span>";
											} else {
												echo"$phone_bo'/>";
											}
//											$result0=get_phuhuynh_hs($data["ID_HS"],1);
//											$data0=mysqli_fetch_assoc($result0);
//											if($data0) {
//											echo"<div class='info-ph'>
//													<p style='font-weight:600;font-size:12px;'>Bố</p>
//													<p>$data0[name]</p>
//													<p>$data[sdt_bo]</p>
//													<p>$data0[job]</p>
//													<p>$data0[mail]</p>
//													<p><a href='$data0[face]'>Facebook</a></p>
//												</div>";
//											}
											echo"</td>
											<td class='check-phone'><input type='text' class='input2 edit-phone-me' value='";
											if($phone_array[$data["ID_HS"]]["me_phone"]==1) {
												echo"$phone_me'/><span><i class='fa fa-check-square-o' data-hsID='$data[ID_HS]'  data-sdt='$data[sdt_me]' style='font-size:1.25em !important;margin-left:5px;'></i></span>";
											} else if($phone_array[$data["ID_HS"]]["me_phone"]==0) {
												echo"$phone_me'/><span><i class='fa fa-square-o' data-hsID='$data[ID_HS]'  data-sdt='$data[sdt_me]' style='font-size:1.25em !important;margin-left:5px;'></i></span>";
											} else {
												echo"$phone_me'/>";
											}
//											$result0=get_phuhuynh_hs($data["ID_HS"],0);
//											$data0=mysqli_fetch_assoc($result0);
//											if($data0) {
//											echo"<div class='info-ph'>
//													<p style='font-weight:600;font-size:12px;'>Mẹ</p>
//													<p>$data0[name]</p>
//													<p>$data[sdt_me]</p>
//													<p>$data0[job]</p>
//													<p>$data0[mail]</p>
//													<p><a href='$data0[face]'>Facebook</a></p>
//												</div>";
//											}
											echo"</td>";
											$tempId=$data["ID_HS"];
											$dem++;
											$rowCount++;
										}
										if($dem==0) {
											echo"<tr><td colspan='14'><span>Không có dữ liệu</span></td></tr>";
										}
                                    ?>
                                </table>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
<!--                        --><?php
//						if($loai==0 && $truongID==0 && $sdt==0 && $gioitinh==NULL && $hoc==NULL && $face==NULL && $hop==NULL && !isset($_GET["van"])) {
//                            $query3 = "SELECT DISTINCT hocsinh.ID_HS FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID'";
//                            $result3=mysqli_query($db,$query3);
//							$sum=mysqli_num_rows($result3);
//							$sum_page=ceil($sum/$display);
//							if($sum_page>1) {
//								$current=($position/$display)+1;
//						?>
<!--                        <div class="page-number">-->
<!--                        	<ul>-->
<!--                            --><?php
//								if($current!=1) {
//									$prev=$position-$display;
//									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/$lmID/$monID/page/$prev/'><</a></li>";
//								}
//								for($page=1;$page<=$sum_page;$page++) {
//									$begin=($page-1)*$display;
//									if($page==$current) {
//										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/$lmID/$monID/page/$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
//									} else {
//										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/$lmID/$monID/page/$begin/'>$page</a></li>";
//									}
//								}
//								if($current!=$sum_page) {
//									$next=$position+$display;
//									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/$lmID/$monID/page/$next/'>></a></li>";
//								}
//							?>
<!--                            </ul>-->
<!--                        </div>-->
<!--                        --><?php
//							}
//						}
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
		header('Content-Disposition: attachment; filename="thong-tin-hoc-sinh.xlsx"');
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		// Write the Excel file to filename some_excel_file.xlsx in the current directory
		$objWriter->save('php://output');
	}

	ob_end_flush();
	require_once("../model/close_db.php");
?>
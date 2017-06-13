<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["ngay"]) && isset($_GET["de"]) && isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
		$ngay=$_GET["ngay"];
        $de=$_GET["de"];
	    $lmID=$_GET["lm"];
	} else {
	    $ngay="0000-00-00";
        $de="X";
		$lmID=0;
	}
	$monID=get_mon_of_lop($lmID);
	$buoiID=get_id_buoikt($ngay,$monID);
    $lop_mon_name=get_lop_mon_name($lmID);
    $khoang=0.5;

    $diem_arr = array();
    $query="SELECT diem FROM diemkt WHERE ID_LM='$lmID' AND ID_BUOI='$buoiID' AND loai IN ('0','1')";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        $temp = khoang_number($data["diem"], $khoang, 0, 10);
        if(!isset($diem_arr[0]["$temp"])) {
            $diem_arr[0]["$temp"] = 1;
        } else {
            $diem_arr[0]["$temp"]++;
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>PHỔ ĐIỂM KIỂM TRA</title>
        
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
            window.onload = function () {
                var chart = new CanvasJS.Chart("chartContainer",
                    {
                        animationEnabled: true,
                        interactivityEnabled: true,
                        legendText: "{name}",
                        theme: "theme2",
                        toolTip: {
                            shared: true
                        },
                        backgroundColor: "",
                        legend:{
                            fontFamily: "helvetica",
                            horizontalAlign: "center",
                            fontSize: 14,
                            enabled: false,
                        },
                        dataPointWidth: 22,
                        axisY: {
                            gridThickness: 1,
                            tickThickness: 0,
                            labelFontSize: 16,
                            labelFontWeight: "normal",
                        },
                        axisX: {
                            interval: 1,
                            labelAngle: 90,
                            tickThickness: 1,
                            labelFontSize: 16,
                            labelFontWeight: "normal",
                        },
                        data: [{
                            type: "column",
                            startAngle: -90,
                            showInLegend: false,
                            legendText: "{name}",
                            indexLabelFontFamily:"helvetica",
                            indexLabelFontColor: "#FFF",
                            indexLabelFontSize: 0,
                            indexLabelPlacement: "outside",
                            indexLabelFontWeight: "normal",
                            toolTipContent: "Điểm {label}: {y} em",
                            dataPoints: [
                                <?php
                                for($i = 0; $i <= 10; $i += $khoang) {
                                    if(isset($diem_arr[0]["$i"])) {
                                        echo "{ y: " . $diem_arr[0]["$i"] . ", label : '$i', indexLabel : '', color: '#29B6F6'},";
                                    } else {
                                        echo "{ y: 0, label : '$i', indexLabel : '', color: '#29B6F6'},";
                                    }
                                }
                                ?>
                            ]
                        }]
                    });
                chart.render();
            }
		</script>
        <script>
			$(document).ready(function() {

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
                
                <div id="main-mid">
                	<h2>Phô điểm <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?>, ngày <?php echo format_dateup($ngay); ?></span></h2>
                	<div>
                    	<div class="status">
                      		<div class="main-top" style="display:block;overflow:auto;" id="main-wapper">
                            	<div id="chartContainer">
                                </div>
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
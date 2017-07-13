<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $nam=date("Y");
    $thang=date("m");
    if(isset($_GET["ngay"]) && is_numeric($_GET["ngay"])) {
        $ngay=$_GET["ngay"];
    } else {
        $ngay=date("j");
    }
    $lmID=$_SESSION["lmID"];
    $hs_arr=array();
    $query="SELECT h.ID_HS,h.cmt,h.fullname,n.ID_N FROM hocsinh AS h 
    INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
    LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        $query2="SELECT COUNT(ID_STT) AS dem FROM log WHERE ID_HS='$data[ID_HS]' AND type='login' AND datetime LIKE '$nam-$thang-$ngay %'";
        $result2=mysqli_query($db,$query2);
        $data2=mysqli_fetch_assoc($result2);
        $hs_arr[]=array(
            "ID_HS" => $data["ID_HS"],
            "cmt" => $data["cmt"],
            "fullname" => $data["fullname"],
            "diemtb" => $data2["dem"],
            "ID_N" => $data["ID_N"]
        );
    }
    usort($hs_arr, "diemtb_sort_desc");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TRUY CẬP</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
            #CHANGE_LOP {display: none;}
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:400px;}#chartContainer {width:100%;height:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script>
		$(document).ready(function() {
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
					  name: "THỐNG KÊ TRUY CẬP",
					  color: "green",
					  toolTipContent: "<a href = {content}> THỐNG KÊ TRUY CẬP {label}: {y}</a>",
                      click: function(e) {
                          window.location.href=e.dataPoint.content;
                      },
					  dataPoints: [
					  <?php
                          $days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
                          if($nam%4==0) {
                              $days_of_month[1]="29";
                          }
                          for($i=1;$i<=$days_of_month[$thang-1];$i++) {
                              $day=format_month_db($i);
                              $query2="SELECT COUNT(l.ID_STT) AS dem FROM log AS l INNER JOIN hocsinh_mon AS m ON m.ID_HS=l.ID_HS AND m.ID_LM='$lmID' WHERE l.type='login' AND l.datetime LIKE '$nam-$thang-$day %'";
                              $result2=mysqli_query($db,$query2);
                              $data2=mysqli_fetch_assoc($result2);
                              echo"{ label: '".format_date("$nam-$thang-$day")."', y: ".$data2["dem"].", indexLabel: '{y}', content: 'http://localhost/www/TDUONG/thaygiao/truy-cap/$day/'},";
                          }
					  ?>
					  ]
				  }
				  ]
				});
			
				chart.render();
			}
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
                	<h2>Thống kê truy cập ngày <span style="font-weight: 600;"><?php echo format_dateup("$nam-$thang-$ngay"); ?></span></h2>
                	<div>
                    	<div class="status">
                        	<div class="main-top" style="display:block;overflow:auto;" id="main-wapper">
                            	<div></div>
                            	<div id="chartContainer" style="width:<?php echo (10*110); ?>px">
                                </div>
                            </div>
                            <div class="main-bot" style="display:block;">
                            	<table class="table" style="margin-top:25px;">
                                    <tr style="background: #3E606F;">
                                        <th style="width: 10%;"><span>STT</span></th>
                                        <th style="width: 20%;"><span>Họ tên</span></th>
                                        <th style="width: 10%;"><span>Mã số</span></th>
                                        <th style="width: 10%;"><span>Số lần</span></th>
                                    </tr>
                                    <?php
                                    for($i=0;$i<count($hs_arr);$i++) {
                                        if(isset($hs_arr[$i]["ID_N"])) {
                                            echo"<tr style='opacity: 0.3;'>";
                                        } else {
                                            echo"<tr>";
                                        }
                                        echo"<td><span>".($i+1)."</span></td>
                                            <td><span>".$hs_arr[$i]["fullname"]."</span></td>
                                            <td><span>".$hs_arr[$i]["cmt"]."</span></td>
                                            <td><span>".$hs_arr[$i]["diemtb"]."</span></td>
                                        </tr>";
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
<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $monID=$_SESSION["mon"];
    $mon_name=get_mon_name($monID);
    $lmID_arr=array();
    $result=get_all_lop_mon2($monID);
    $price=2000000;
    while($data=mysqli_fetch_assoc($result)) {
        $lmID_arr[] = array(
            "ID_LM" => $data["ID_LM"],
            "name" => $data["name"]
        );
    }
    $mau_arr=array("#EF5350","#96c8f3","yellow","#e7a53f","#96c8f3");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>NHÓM TRÒ CHƠI</title>
        
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
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:600px;}#chartContainer {width:100%;height:100%;}#chartPhu {position:absolute;z-index:9;right:0;width:400px;top:0;height:200px;overflow:hidden;border-radius:200px;}#chartContainer2 {width:100%;height:100%;}.khoang-ma,.only-ma {display:none;}#list-danhsach {background:#FFF;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
			});
		</script>
        <script type="text/javascript">
            window.onload = function () {
                var chartGame = new CanvasJS.Chart("chartGame",
                    {
                        animationEnabled: true,
                        interactivityEnabled: true,
                        theme: "theme2",
                        toolTip: {
                            shared: false
                        },
                        backgroundColor: "",
                        axisX: {
                            labelFontFamily:"Arial" ,
                            gridColor: "Silver",
                            tickColor: "silver",
                            labelFontColor: "#3E606F",
                            labelFontSize: 12,
                            labelText: "{name}",
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
                            <?php
                            for($i=0;$i<count($lmID_arr);$i++) {
                            $mau=$mau_arr[$i];
                            $hsin=count_hs_in_group($lmID_arr[$i]["ID_LM"]);
                            $price+=10000*$hsin;
                            $name=mb_strtoupper($lmID_arr[$i]["name"],"UTF-8");
                            ?>
                            {
                                type: "column",
                                showInLegend: false,
                                indexLabelPlacement: "outside",
                                indexLabelFontSize: 14,
                                yValueFormatString: "#",
                                indexLabelFontWeight: "bold",
                                indexLabelFontFamily:"Arial" ,
                                indexLabel: "{y}",
                                labelFontSize: 14,
                                toolTipContent: "<?php echo $name; ?>: {y}",
                                dataPoints: [
                                    { y: <?php echo count_hs_mon_lop($lmID_arr[$i]["ID_LM"])-$hsin; ?>, indexLabelFontColor: "<?php echo $mau; ?>", color: "<?php echo $mau; ?>", label: "KHÔNG THAM GIA"},
                                    { y: <?php echo $hsin; ?>, indexLabelFontColor: "<?php echo $mau; ?>", color: "<?php echo $mau; ?>", label: "ĐÃ THAM GIA"},
                                    { y: <?php echo count_game_group($lmID_arr[$i]["ID_LM"]); ?>, indexLabelFontColor: "<?php echo $mau; ?>", color: "<?php echo $mau; ?>", label: "SỐ NHÓM"}
                                ]
                            },
                            <?php } ?>
                        ]
                    });
                chartGame.render();
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
                    <h2>Nhóm trò chơi môn <?php echo mb_strtoupper($mon_name,"UTF-8"); ?></h2>
                    <div>
                        <div class="status">
                            <table class="table">
                                <tr>
                                    <th style="background: #3E606F;"><span>Tiền thưởng</span></th>
                                    <td colspan="6"><span><?php echo format_price($price); ?></span></td>
                                </tr>
                                <tr>
                                    <th style="background: #3E606F;"><span>Số tiền trừ</span></td>
                                    <td colspan="6"><span>60.000đ</span></td>
                                </tr>
                                <tr>
                                    <th colspan="7" style="width: 100%;">
                                        <div id='chartGame' style='width:90%;height: 300px;margin: 25px auto 0 auto;'></div>
                                    </th>
                                </tr>
                                <tr>
                                    <?php
                                    for($i=0;$i<count($lmID_arr);$i++) {
                                        echo"<th style='background: #3E606F;'><span>Danh sách nhóm ".$lmID_arr[$i]["name"]."</span></th>";
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                    for($i=0;$i<count($lmID_arr);$i++) {
                                        echo"<td style='padding:0;vertical-align: top;'>
                                            <table class='table'>";
                                        $query="SELECT g.ID_N,g.name,g.password,COUNT(l.ID_STT) AS dem FROM game_group AS g 
                                                    INNER JOIN list_group AS l ON l.ID_N=g.ID_N
                                                    WHERE ID_LM='".$lmID_arr[$i]["ID_LM"]."' 
                                                    GROUP BY g.ID_N
                                                    ORDER BY dem DESC,g.ID_N ASC";
                                        $result=mysqli_query($db,$query);
                                        while($data=mysqli_fetch_assoc($result)) {
                                            echo"<tr>
                                                        <td><span>#$data[ID_N], $data[name]</span></td>
                                                        <td style='width: 15%;'><span>$data[dem]</span></td>";
                                            if($data["password"]=="none" || $data["password"]=="") {
                                                echo "<td><span></span></td>";
                                            } else {
                                                echo "<td><span>$data[password]</span></td>";
                                            }
                                            echo"</tr>";
                                        }
                                        echo"</table>
                                        </td>";
                                    }
                                    ?>
                                </tr>
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
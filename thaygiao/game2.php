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
    $n=count($lmID_arr);
    for($i = 0; $i < $n; $i++) {
        if($i != $n-1) {
            $lmID_arr[$i] = NULL;
        }
    }
    $mau_arr=array("#EF5350","#96c8f3","yellow","#e7a53f","#96c8f3");
    $num=count_so_ve(0);

    $ngayarr=array();
    $query1="SELECT content FROM options WHERE type='buoi-phat-ve' ORDER BY content ASC";
    $result1=mysqli_query($db,$query1);
    while($data1=mysqli_fetch_assoc($result1)) {
        $ngayarr[] = $data1["content"];
    }

    $now=date("Y-m-d");
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

			    <?php if($num!=0) { ?>
			    setTimeout(function() {
                    $("td.count-ticket").each(function (index, element) {
                        var id = $(element).closest("tr").attr("data-id");
                        if (id != 0 && $.isNumeric(id)) {
                            $.ajax({
                                async: true,
                                data: "id3=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-game/",
                                success: function (result) {
                                    $(element).find("span").html(result);
                                    $(element).find("span").val(result);
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    });
                }, 1000);
			    <?php } ?>

			});
		</script>
        <script type="text/javascript">
            window.onload = function () {
                var chartCa = new CanvasJS.Chart("chartCa",
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
                        },
                        axisY: {
                            gridThickness: 1,
                            indexLabelFontFamily:"Arial" ,
                            gridColor: "Silver",
                            tickColor: "silver",
                            labelFontColor: "#3E606F",
                            labelFontSize: 14,
                            labelFontWeight: "normal",
                            maximum: 230,
                            interval: 50
                        },
                        dataPointMaxWidth: 35,
                        data: [
                            {
                                type: "column",
                                showInLegend: false,
                                indexLabelPlacement: "outside",
                                indexLabelFontSize: 12,
                                yValueFormatString: "#",
                                indexLabelFontWeight: "bold",
                                indexLabelFontFamily:"Arial" ,
                                indexLabel: "{orther}",
                                labelFontSize: 14,
                                toolTipContent: "Đã đặt: {orther} chỗ",
                                click: function(e) {
                                    window.location.href=e.dataPoint.content;
                                },
                                dataPoints: [
                                    <?php
                                        $datastr="";
                                        $n=count($ngayarr);
                                        for($i=0;$i<$n;$i++) {
                                            $num=0;
                                            $query2="SELECT g.ID_N,o.content,COUNT(l.ID_STT) AS dem FROM game_group AS g 
                                                LEFT JOIN hocvien_info AS l ON l.ID_N=g.ID_N AND l.ca='".$ngayarr[$i]."'
                                                LEFT JOIN options AS o ON o.type='da-phat-ve' AND o.note=g.ID_N AND o.note2='".$ngayarr[$i]."'
                                                GROUP BY g.ID_N";
                                            $result2 = mysqli_query($db, $query2);
                                            while($data2 = mysqli_fetch_assoc($result2)) {
                                                if($data2["content"]) {
                                                    $num+=$data2["content"];
                                                } else {
                                                    $num+=$data2["dem"];
                                                }
                                            }
                                            if($num==0) {$num=1;}
                                            echo "{ y: $num, orther: '$num', indexLabelFontColor: '#3E606F', color: '#3E606F', label: '" . format_dateup($ngayarr[$i]) . "', content: 'http://localhost/www/TDUONG/thaygiao/hoc-vien-list/$ngayarr[$i]/'},";
                                            $datastr .= "{ y: " . (200 - $data2["dem"]) . ", indexLabelFontColor: '#3E606F', color: '#3E606F', label: '" . format_dateup($ngayarr[$i]) . "', content: 'http://localhost/www/TDUONG/thaygiao/hoc-vien-list/$ngayarr[$i]/'},";
                                        }
                                    ?>
                                ]
                            },
                            {
                                type: "column",
                                showInLegend: false,
                                indexLabelPlacement: "outside",
                                indexLabelFontSize: 12,
                                yValueFormatString: "#",
                                indexLabelFontWeight: "bold",
                                indexLabelFontFamily: "Arial" ,
                                indexLabel: "{orther}",
                                labelFontSize: 14,
                                toolTipContent: "Phát vé thành công: {y} vé",
                                click: function(e) {
                                    window.location.href=e.dataPoint.content;
                                },
                                dataPoints: [
                                    <?php
                                        $datastr2="";
                                        for($i=0;$i<$n;$i++) {
                                            if(date_create($ngayarr[$i]) < date_create($now)) {
                                                $num = 0;
                                                $query2 = "SELECT COUNT(i.ID_STT) AS dem FROM hocvien_info AS i WHERE i.ca='" . $ngayarr[$i] . "' AND i.diemdanh='1'";
                                                $result2 = mysqli_query($db, $query2);
                                                $data2 = mysqli_fetch_assoc($result2);
                                                echo "{ y: $data2[dem], orther: '$data2[dem]', indexLabelFontColor: '#69b42e', color: '#69b42e', content: 'http://localhost/www/TDUONG/thaygiao/hoc-vien-list/$ngayarr[$i]/'},";
                                            } else {
                                                echo "{ y: 0, orther: '', indexLabelFontColor: '#69b42e', color: '#69b42e', content: 'http://localhost/www/TDUONG/thaygiao/hoc-vien-list/$ngayarr[$i]/'},";
                                            }
                                        }
                                    ?>
                                ]
                            },
                            {
                                type: "column",
                                showInLegend: false,
                                indexLabelPlacement: "outside",
                                indexLabelFontSize: 12,
                                yValueFormatString: "#",
                                indexLabelFontWeight: "bold",
                                indexLabelFontFamily: "Arial" ,
                                indexLabel: "{orther}",
                                labelFontSize: 14,
                                toolTipContent: "Phát vé thất bại: {y} vẽ",
                                click: function(e) {
                                    window.location.href=e.dataPoint.content;
                                },
                                dataPoints: [
                                    <?php
                                        $datastr2="";
                                        for($i=0;$i<$n;$i++) {
                                            if (date_create($ngayarr[$i]) < date_create($now)) {
                                                $num = 0;
                                                $query2 = "SELECT COUNT(i.ID_STT) AS dem FROM hocvien_info AS i WHERE i.ca='" . $ngayarr[$i] . "' AND i.diemdanh='0'";
                                                $result2 = mysqli_query($db, $query2);
                                                $data2 = mysqli_fetch_assoc($result2);
                                                echo "{ y: $data2[dem], orther: '$data2[dem]', indexLabelFontColor: 'red', color: 'red', content: 'http://localhost/www/TDUONG/thaygiao/hoc-vien-list/$ngayarr[$i]/'},";
                                            } else {
                                                echo "{ y: 0, orther: '', indexLabelFontColor: 'red', color: 'red', content: 'http://localhost/www/TDUONG/thaygiao/hoc-vien-list/$ngayarr[$i]/'},";
                                            }
                                        }
                                    ?>
                                ]
                            }
                        ]
                    });
                chartCa.render();
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
<!--                                <tr>-->
<!--                                    <th style="background: #3E606F;"><span>Số người nhận vé</span></th>-->
<!--                                    <td colspan="6" id="sum"><span>--><?php //echo $num; ?><!--</span></td>-->
<!--                                </tr>-->
                                <tr>
                                    <th colspan="7" style="width: 100%;">
                                        <div id='chartCa' style='width:90%;height: 250px;margin: 25px auto 0 auto;'></div>
                                    </th>
                                </tr>
                                <tr>
                                    <?php
                                    $n=count($lmID_arr);
                                    for($i=0;$i<$n;$i++) {
                                        if($lmID_arr[$i]==NULL) {
                                            continue;
                                        }
                                        echo"<th style='background: #3E606F;width:".format_diem(100/$n)."%'><span>Danh sách nhóm ".$lmID_arr[$i]["name"]."</span></th>";
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <?php
                                    for($i=0;$i<$n;$i++) {
                                        if($lmID_arr[$i]==NULL) {
                                            continue;
                                        }
                                        echo"<td style='padding:0;vertical-align: top;'>
                                            <table class='table'>
                                                <tr>
                                                    <td><span>Nhóm</span></td>
                                                    <td><span>Số vé</span></td>
                                                    <td><span>SL thành viên</span></td>
                                                    <td><span>Mật khẩu</span></td>
                                                </tr>";
                                            $query="SELECT g.ID_N,g.name,g.password,COUNT(l.ID_STT) AS dem FROM game_group AS g 
                                            LEFT JOIN hocvien_info AS l ON l.ID_N=g.ID_N
                                            WHERE g.ID_LM='".$lmID_arr[$i]["ID_LM"]."' 
                                            GROUP BY g.ID_N
                                            ORDER BY dem DESC,g.ID_N ASC";
                                            $result=mysqli_query($db,$query);
                                            while($data=mysqli_fetch_assoc($result)) {
                                                $nID=$data["ID_N"];
                                                echo"<tr class='nhom' data-id='$nID'>
                                                    <td><span><a href='http://localhost/www/TDUONG/thaygiao/hoc-vien-chi-tiet/$nID/' target='_blank'>#$nID, $data[name]</a></span></td>
                                                    <td style='width: 15%;'><span>$data[dem]</span></td>
                                                    <td class='count-ticket' style='width: 15%;'><span></span></td>";
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
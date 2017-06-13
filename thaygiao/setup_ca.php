<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["mon"]) && is_numeric($_GET["mon"])) {
		$lmID=$_GET["lm"];
        $monID=$_GET["mon"];
	} else {
		$lmID=0;
        $monID=0;
	}
	if($lmID!=0) {
        $lmID = $_SESSION["lmID"];
    }
    $monID = $_SESSION["mon"];
	$mon_lop_name=get_lop_mon_name($lmID);
    if($lmID!=0) {
		$thu_string=array("Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	} else {
		$thu_string=array("Chủ Nhật");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>CÁC CA HỌC THUỘC MÔN <?php echo mb_strtoupper($mon_lop_name,"UTF-8"); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
            .main-bot {width:100%;}.main-top {width:100%;position:relative;height:400px;}#chartContainer {width:100%;height:100%;}
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN > #main-mid .status table tr td {overflow:hidden;}#MAIN > #main-mid .status table tr td > nav {width:100%;height:100%;}#MAIN > #main-mid .status table tr td > div.tab-num {position:absolute;z-index:9;right:-20px;top:-5px;background:#3E606F;width:60px;height:25px;-ms-transform: rotate(45deg);-webkit-transform: rotate(45deg); transform: rotate(45deg);}#MAIN > #main-mid .status table tr td > div.tab-num span {color:#FFF;line-height:30px;font-size:12px;}#MAIN > #main-mid .status table tr td > nav > div {float:left;}#MAIN > #main-mid .status table tr td > nav > div.tab-left {width:65%;}#MAIN > #main-mid .status table tr td > nav > div.tab-right {width:25%;text-align:left;}#MAIN > #main-mid .status table tr td > nav > div.siso {float:none;display:none;}#MAIN > #main-mid .status table tr td > nav > div.siso input {width:20%;display:inline-table;}#MAIN > #main-mid .status table tr td > nav > div.tab-right i {font-size:22px;cursor:pointer;}#MAIN > #main-mid > div .status .table-2 {display:inline-table;}#MAIN #main-mid #bang-ca tr td span > i {font-size:22px;}#MAIN > #main-mid .status table#bang-chon {position:fixed;z-index:9;right:5%;background:#FFF;}#MAIN > #main-mid .status table#bang-chon tr td.chon-ca:hover {background:yellow;}#MAIN > #main-mid .status table#bang-chon tr td span {font-size:12px;}#MAIN > #main-mid .status table#bang-chon tr td.done-ca {opacity:0.3;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN > #main-mid .status #bang-ca tr.con-ca").each(function(index, element) {
                if(!$(element).find("td").hasClass("has-ca")) {
					$(element).remove();
				}
            });
			
			function get_bang_ca(thu) {
				$.ajax({
					async: false,
					data: "thu0=" + thu,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-ca/",
					success: function(result) {
						$("#bang-chon").html(result);
					}
				});
			}
			
			$("#MAIN > #main-mid .status #bang-ca tr.big-ca").each(function(index, element) {
                cum = $(element).attr("data-cum");
				num = $(element).closest("table").find("tr.con-ca-"+cum).length;
				if(num!=0) {
					$(element).find("td input.del_cum").remove();
				}
            });
			
			var max_dem = parseInt($("#max-dem").val()) + 1;
			$("#bang-ca tr.big-ca td").attr("colspan",max_dem);
			$(".has-col").closest("td").attr("colspan",max_dem);
			$("#bang-ca tr th").css("width",(100/max_dem)+"%");
			
			$("#bang-ca tbody > .num-ca").each(function(index, element) {
                if($(element).val()!=0) {
					$("#bang-ca tr.big-ca:eq("+index+")").find("td select").css("opacity","0.3").html("<option value='0'>Ca chính</option>");
				}
            });
		});
		</script>
        <script type="text/javascript">
            //document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
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
                            labelFontSize: 12,
                            labelFontWeight: "normal",
                        },
                        axisY: {
                            title: "SỐ LƯỢNG CA HIỆN TẠI",
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
                            shared:false,
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
                        dataPointMaxWidth: 45,
                        data: [
                        <?php
                            $color_arr=array("green","blue","orange","brown","gray");
                            $dem=0;
                            $result0=get_all_lop_mon2($monID);
                            while($data0=mysqli_fetch_assoc($result0)) {
                        ?>
                            {
                                type: "stackedColumn",
                                showInLegend: false,
                                indexLabelFontFamily:"Arial" ,
                                indexLabelFontColor: "#FFF",
                                indexLabelFontWeight: "bold",
                                indexLabelFontSize: 16,
                                indexLabelPlacement: 'inside',
                                name: 'LỚP <?php echo $data0["name"]; ?>',
                                color: "<?php echo $color_arr[$dem]; ?>",
                                toolTipContent: "<a href = {content}> SĨ SỐ {name}: {y}<br />{more}</a>",
                                dataPoints: [
                                    <?php
                                    if($lmID!=0 && $lmID!=$data0["ID_LM"]) {

                                    } else {
                                        $result = get_all_cahoc_lop($lmID, $monID);
                                        while ($data = mysqli_fetch_assoc($result)) {
                                            if ($data["thu"] != 1) {
                                                $thu = "T$data[thu]";
                                            } else {
                                                $thu = "CN";
                                            }
                                            if($lmID!=0) {
                                                echo "{label: '$thu, $data[gio]', y: " . get_num_hs_ca_codinh2($data["ID_CA"],$data0["ID_LM"]) . ", indexLabel: '{y}', name: 'LỚP $data0[name]', more: '', content: 'http://localhost/www/TDUONG/thaygiao/ca-co-dinh/$data[ID_CA]/'},";
                                            } else {
                                                echo "{label: '$thu, $data[gio]', y: " . get_num_hs_ca_codinh2($data["ID_CA"],$data0["ID_LM"]) . ", indexLabel: '{y}', name: 'LỚP $data0[name]', more: 'Đề G: ".get_num_hs_ca_de($data["ID_CA"],$data0["ID_LM"],"G")."<br />Đề B: ".get_num_hs_ca_de($data["ID_CA"],$data0["ID_LM"],"B")."<br />Đề Y: ".get_num_hs_ca_de($data["ID_CA"],$data0["ID_LM"],"Y")."', content: 'http://localhost/www/TDUONG/thaygiao/ca-co-dinh/$data[ID_CA]/'},";
                                            }
                                        }
                                    }
                                    ?>
                                ]
                            },
                        <?php
                            $dem++;
                            }
                        ?>
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
                	<h2>CÁC CA HỌC THUỘC MÔN <span style="font-weight:600;"><?php echo $mon_lop_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <div class="main-top" style="display:block;overflow:auto;" id="main-wapper">
                                <div></div>
                                <div id="chartContainer" style="min-width: 1000px;">
                                </div>
                            </div>
                            <div class="main-bot" style="display:block;">
                                <table class="table" style="margin-top: 25px;">
                                    <tr>
                                        <td class="hidden"><span>Dạng hiển thị</span></td>
                                        <th><input type="submit" class="submit" value="Liệt kê" onclick="location.href='http://localhost/www/TDUONG/thaygiao/ca/<?php echo $lmID."/".$monID; ?>/'" /></th>
                                        <th><input type="submit" class="submit" value="Bảng chọn" onclick="location.href='http://localhost/www/TDUONG/thaygiao/cai-dat-ca/<?php echo $lmID."/".$monID; ?>/'" /></th>
                                        <?php
                                            if($lmID!=0) {
                                                echo"<th><input type='submit' style='background:red;' class='submit' value='Kiểm tra' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/cai-dat-ca/0/$monID/'\" /></th>";
                                            } else {
                                                echo"<th><input type='submit' style='background:red;' class='submit' value='Ca học' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/cai-dat-ca/$_SESSION[lmID]/$monID/'\" /></th>";
                                            }
                                        ?>
                                    </tr>
                                </table>
                                <table class="table table-2" style="width:49%;margin-top:25px;" id="bang-ca">
                                <?php
                                    $ca_gio=array();
                                    $query2="SELECT ID_GIO,gio,buoi,ID_LM FROM cagio WHERE ID_MON='$monID' ORDER BY buoi ASC,thutu ASC";
                                    $result2=mysqli_query($db,$query2);
                                    while($data2=mysqli_fetch_assoc($result2)) {
                                        $ca_gio[]=array(
                                            "gioID" => $data2["ID_GIO"],
                                            "gio" => $data2["gio"],
                                            "buoi" => substr($data2["buoi"],0,1),
                                            "lmID" => $data2["ID_LM"]
                                        );
                                    }
                                    //usort($ca_gio, "buoi_sort");

                                    $max_dem=0;
                                    $dia_diem="";$first=false;
                                    if($lmID!=0) {
                                        $result = get_all_cum2($lmID);
                                    } else {
                                        $result = get_cum_kt($monID);
                                    }
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr class='big-ca' data-cum='$data[ID_CUM]'>
                                            <td colspan=''>
                                                <input class='input input_name' type='text' style='float:left;width:35%;' value='$data[name]' />
                                                <select class='input' style='float:left;width:25%;height:auto;margin:0 2.5% 0 2.5%;padding-left:2.5%'>
                                                    <option value='0'>Ca chính</option>";
                                                    $result1=get_same_cum($lmID,$monID);
                                                    while($data1=mysqli_fetch_assoc($result1)) {
                                                        echo"<option value='$data1[ID_CUM]' ";if($data["link"]==$data1["ID_CUM"]){echo"selected='selected'";}echo">Liên kết $data1[name]</option>";
                                                    }
                                                echo"</select>
                                            </td>
                                        </tr>
                                        <tr style='background:#3E606F;'>";
                                            $thu_array=array();
                                            $dem=0;
                                            if($data["link"]!=0) {
                                                $big_cum=$data["link"];
                                            } else {
                                                $big_cum=$data["ID_CUM"];
                                            }
                                            $query5="SELECT DISTINCT thu FROM cahoc WHERE cum='$big_cum' ORDER BY thu ASC";
                                            $result5=mysqli_query($db,$query5);
                                            while($data5=mysqli_fetch_assoc($result5)) {
                                                if($data5["thu"]==1) {
                                                    echo"<th><span>Chủ nhật</span></th>";
                                                } else {
                                                    echo"<th><span>Thứ $data5[thu]</span></th>";
                                                }
                                                $thu_array[$dem]=$data5["thu"];
                                                $dem++;
                                            }
                                            if($lmID!=0) {
                                                for ($i = $dem; $i < 3; $i++) {
                                                    if ($data["link"] != 0) {
                                                        echo "<th style='width:10%;'></th>";
                                                    } else {
                                                        echo "<th style='width:10%;'><span><i class='fa fa-plus-circle add_thu' data-cum='$big_cum' style='cursor:pointer' title='Thêm thứ'></i></span></th>";
                                                    }
                                                }
                                            }
                                        echo"</tr>";
                                        $dem2=0;
                                        for($i=0;$i<count($ca_gio);$i++) {
                                            if($data["link"]!=0) {
                                                if($lmID==$ca_gio[$i]["lmID"]) {
                                                    continue;
                                                }
                                            } else {
                                                if($lmID!=$ca_gio[$i]["lmID"]) {
                                                    continue;
                                                }
                                            }
                                            echo"<tr class='con-ca con-ca-$big_cum'>";
                                                for($j=0;$j<count($thu_array);$j++) {
//                                                    $query3="SELECT c.ID_CA,c.thu,c.siso,c.max,d.name FROM cahoc AS c INNER JOIN dia_diem AS d ON d.ID_DD=c.ID_DD WHERE c.thu='".$thu_array[$j]."' AND c.ID_GIO='".$ca_gio[$i]["gioID"]."' AND c.cum='$big_cum'";
                                                    $query3="SELECT c.ID_CA,c.thu,c.siso,c.max FROM cahoc AS c WHERE c.thu='".$thu_array[$j]."' AND c.ID_GIO='".$ca_gio[$i]["gioID"]."' AND c.cum='$big_cum'";
                                                    $result3=mysqli_query($db,$query3);
                                                    if(mysqli_num_rows($result3)!=0) {
                                                        $data3=mysqli_fetch_assoc($result3);
//                                                        $show=false;
//                                                        if($data3["name"]!=$dia_diem) {
//                                                            if(!$first) {
//                                                                $dia_diem=$data3["name"];
//                                                                $first=true;
//                                                            } else {
//                                                                $show=true;
//                                                            }
//                                                        }
                                                        $num=get_num_hs_ca_hientai($data3["ID_CA"]);
                                                        echo"<td class='has-ca'>
                                                            <div class='tab-num' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/ca-hien-tai/$data3[ID_CA]/'\" style='cursor:pointer'><span>$num</span></div>
                                                            <nav>
                                                                <div class='tab-left'><span class='tab-gio'>".$ca_gio[$i]["gio"]."</span></div>";
                                                                if($data["link"]!=0) {
                                                                    echo"<div class='tab-right'><i class='fa fa-lock'></i></div>";
                                                                } else {
                                                                    echo"<div class='tab-right'><i class='fa fa-check-square-o kill_ca' data-caID='$data3[ID_CA]'></i></div>";
                                                                    $dem2++;
                                                                }
//                                                                if($show) {
//                                                                    echo"<div class='clear'></div>
//                                                                    <div class='siso' style='display:block;margin-top:10px;'>
//                                                                        <span style='font-size:10px;display:inline-block;width:100%;'>$data3[name]</span>
//                                                                    </div";
//                                                                }
                                                            echo"</nav>
                                                        </td>";
                                                    } else {
                                                        echo"<td></td>";
                                                    }
                                                }
                                            echo"</tr>";
                                        }
                                        echo"<input type='hidden' value='$dem2' class='num-ca' data-cum='$big_cum' />";
                                        $max_dem=$max_dem>$dem?$max_dem:$dem;
                                    }
                                ?>
<!--                                    <tr>-->
<!--                                        <td colspan=""><span class="has-col">Địa điểm học: --><?php //echo $dia_diem; ?><!--</span></td>-->
<!--                                    </tr>-->
                                    <tr>
                                        <td style="border: none;" colspan="">
                                            <input type="hidden" value="<?php echo $max_dem; ?>" id="max-dem" class="has-col" />
                                        </td>
                                    </tr>
                                </table>
                                <table class="table table-2" style="width:44%;top:40%;" id="bang-chon">
                                </table>
                                <input type="hidden" id="hin-data" value="0" />
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
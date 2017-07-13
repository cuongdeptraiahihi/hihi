<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["cdID"]) && is_numeric($_GET["cdID"])) {
		$cdID=$_GET["cdID"];
	} else {
		$cdID=0;
	}
    if(isset($_GET["loai"]) && is_numeric($_GET["loai"])) {
        $loai=$_GET["loai"];
    } else {
        $loai=1;
    }
	$result0=get_one_chuyende($cdID);
	$data0=mysqli_fetch_assoc($result0);
	$lmID=$data0["ID_LM"];
    $monID=get_mon_of_lop($lmID);
    if(isset($_GET["buoiID"]) && is_numeric($_GET["buoiID"])) {
        $buoiID=$_GET["buoiID"];
    } else {
        $buoiID=get_new_buoikt($monID,0,1);
    }

    $total_all = $dem_all = 0;
    $data_arr = array();
    if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) {
        $query = "SELECT h.ID_HS,h.cmt,h.fullname,m.de,m.date_in,COUNT(d.ID_STT) AS dem FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
        INNER JOIN chuyende_diem AS d ON d.ID_CD='$cdID' AND d.ID_HS=h.ID_HS AND d.ID_LM='$lmID' AND d.diem LIKE '0/%'
        WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')
        GROUP BY h.ID_HS
        ORDER BY dem DESC";
        $result = mysqli_query($db, $query);
        while ($data = mysqli_fetch_assoc($result)) {
            $dem_all+=$data["dem"];

            $data_arr[] = array(
                "ID_HS" => $data["ID_HS"],
                "cmt" => $data["cmt"],
                "fullname" => $data["fullname"],
                "de" => $data["de"],
                "date_in" => $data["date_in"],
                "diemtb" => $data["dem"]
            );
        }
        $query = "SELECT h.ID_HS,COUNT(d.ID_STT) AS dem FROM hocsinh AS h 
        INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
        INNER JOIN chuyende_diem AS d ON d.ID_CD='$cdID' AND d.ID_HS=h.ID_HS AND d.ID_LM='$lmID' AND d.diem NOT LIKE 'X/%'
        WHERE h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID')";
        $result = mysqli_query($db, $query);
        $data = mysqli_fetch_assoc($result);
        $total_all += $data["dem"];
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TÀI LIỆU <?php echo mb_strtoupper($data0["title"],"UTF-8"); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;margin-right:0;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN #main-mid .status table#list-tailieu").delegate("tr td input.delete", "click", function() {
				tlID = $(this).attr("data-tlID");
				del_tr = $(this).closest("tr");
				if(confirm("Bạn có chắc chắn xóa tài liệu này?")) {
					$.ajax({
						async: true,
						data: "tlID0=" + tlID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tailieu/",
						success: function(result) {
							del_tr.fadeOut("fast");
						}
					});
				}
				return false;
			});

            $("#bieu-do").click(function() {
                if(confirm("Sẽ mất nhiều thời gian?")) {
                    $.ajax({
                        async: true,
                        data: "bieudo=all",
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-thongke/",
                        success: function () {
                            location.reload();
                        }
                    });
                    return false;
                } else {
                    return false;
                }
            });
		});
		</script>
        <?php if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) { ?>
        <script type="text/javascript">
            window.onload = function () {
                var chart = new CanvasJS.Chart("chartContainer",
                {
                    theme: "theme2",
                    toolTip:{
                        enabled: false
                    },
                    backgroundColor: "",
                    animationEnabled: true,
                    interactivityEnabled: false,
                    legend:{
                        fontFamily: "helvetica",
                        horizontalAlign: "center",
                        fontSize: 12,
                        fontColor: "#FFF",
                    },
                    data: [
                        {
                            type: "doughnut",
                            startAngle: -90,
                            innerRadius: "75%",
                            showInLegend: false,
                            legendText: "{name}",
                            indexLabelFontFamily:"helvetica" ,
                            indexLabelFontSize: 14,
                            indexLabelMaxWidth: 100,
                            indexLabelFontWeight: "normal",
                            dataPoints: [
                                <?php $tinh=($dem_all/$total_all)*100; ?>
                                {  y: <?php echo (100-$tinh); ?>, color: "gray", indexLabel: "Nắm được: <?php echo format_phantram(100-$tinh); ?>", indexLabelFontColor: "#3E606F"},
                                {  y: <?php echo $tinh; ?>, color: "yellow", indexLabel: "Không nắm được: <?php echo format_phantram($tinh) ?>", indexLabelFontColor: "#3E606F"},
                            ]
                        }
                    ]
                });
                chart.render();
            }
        </script>
        <?php } ?>
        <script type="text/javascript" src="http://localhost/www/TDUONG/thaygiao/js/canvasjs.min.js"></script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
<!--            	<div id="main-left">-->
<!--                    -->
<!--                    <div>-->
<!--                    	<h3>Menu</h3>-->
<!--                        <ul>-->
<!--                            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/chuyen-de/--><?php //echo $lmID; ?><!--/"><i class="fa fa-newspaper-o"></i>CHUYÊN ĐỀ</a></li>-->
<!--                            --><?php
//								$result1=get_all_chuyende_con($lmID);
//								while($data1=mysqli_fetch_assoc($result1)) {
//									echo"<li class='action'><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu-chuyen-de/$data1[ID_CD]/'><i class='fa fa-folder-o'></i>$data1[title]</a></li>";
//								}
//                                $result1=get_danhmuc($lmID,$monID);
//                                while($data1=mysqli_fetch_assoc($result1)) {
//                                    echo"<li class='action'><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu-danh-muc/$data1[ID_DM]/'><i class='fa fa-folder-o'></i>$data1[title]</a></li>";
//                                }
//							?>
<!--                        </ul>-->
<!--                    </div>-->
<!--                    -->
<!--                </div>-->

                <?php
                    if(isset($_POST["loc-ok"])) {
                        if(isset($_POST["loc-chon"])) {
                            $loai_chon=$_POST["loc-chon"];
                            if($loai_chon==0 || $loai_chon==1) {
                                header("location:http://localhost/www/TDUONG/thaygiao/tai-lieu-chuyen-de/$cdID/$loai_chon/");
                                exit();
                            }
                        }
                    }
                ?>
                
                <div id="main-mid">
                	<h2>Tài liệu chuyên đề <span style="font-weight:600;"><?php echo $data0["title"] ?></span></h2>
                	<div>
                    	<div class="status">
<!--                            --><?php //if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0) { ?>
<!--                            <div id="chartContainer" style="width: 100%;height:250px;">-->
<!---->
<!--                            </div>-->
<!--                            --><?php //} ?>
<!--                            <form action="http://localhost/www/TDUONG/thaygiao/tai-lieu-chuyen-de/--><?php //echo $cdID; ?><!--/" method="post">-->
<!--                                <table class="table" id="list-hang" style="margin-top: 25px;">-->
<!--                                    <tr>-->
<!--                                        <td><input type="submit" name="bieu-do" id="bieu-do" class="submit" value="Thống kê" /></td>-->
<!--                                        <td colspan="2"><span>Chọn kiểu lọc</span></td>-->
<!--                                        <td colspan="3">-->
<!--                                            <select class="input" style="width: 100%;height: auto;" name="loc-chon">-->
<!--                                                <option value="1" --><?php //if($loai==1){echo"selected='selected'";} ?><!--><!--30 người thấp nhất</option>-->
<!--                                            </select>-->
<!--                                        </td>-->
<!--                                        <td><input type="submit" name="loc-ok" class="submit" value="Lọc" /></td>-->
<!--                                    </tr>-->
<!--                                    --><?php //if(isset($_SESSION["bieudo"]) && $_SESSION["bieudo"]==0 && $loai==1) { ?>
<!--                                        <tr style="background:#3E606F;">-->
<!--                                            <th style="width: 10%;"><span>STT</span></th>-->
<!--                                            <th style="width: 20%;"><span>Họ tên</span></th>-->
<!--                                            <th style="width: 10%;"><span>Mã số</span></th>-->
<!--                                            <th style="width: 15%;"><span>Ngày vào học</span></th>-->
<!--                                            <th style="width: 10%;"><span>Đề</span></th>-->
<!--                                            <th><span>Số lần không làm được</span></th>-->
<!--                                            <th style="width: 15%;"><span>Trang cá nhân</span></th>-->
<!--                                        </tr>-->
<!--                                        --><?php
//                                        for ($i = 0; $i < count($data_arr); $i++) {
//                                            if ($i % 2 != 0) {
//                                                echo "<tr style='background: #D1DBBD;'>";
//                                            } else {
//                                                echo "<tr>";
//                                            }
//                                            echo "<td><span>" . ($i + 1) . "</span></td>
//                                                <td><span>" . $data_arr[$i]["fullname"] . "</span></td>
//                                                <td><span>" . $data_arr[$i]["cmt"] . "</span></td>
//                                                <td><span>" . format_dateup($data_arr[$i]["date_in"]) . "</span></td>
//                                                <td><span>" . $data_arr[$i]["de"] . "</span></td>
//                                                <td style='text-align:left;'><span>" . get_soc($data_arr[$i]["diemtb"]) . " " . $data_arr[$i]["diemtb"] . " lần</span></td>
//                                                <td><a href='https://localhost/www/TDUONG/dang-nhap/" . $data_arr[$i]["ID_HS"] . "//' target='_blank'>Xem</a></td>
//                                            </tr>";
//                                            if ($loai == 1 && $i == 19) {
//                                                break;
//                                            }
//                                        }
//                                    }
//                                    ?>
<!--                                </table>-->
<!--                            </form>-->
                            <table class="table" id="list-tailieu" style="margin-top: 25px;">
                                <tr>
                                    <td colspan="5"></td>
                                    <td><input type='submit' class='submit' onclick="location.href='http://localhost/www/TDUONG/thaygiao/up-tai-lieu/<?php echo $lmID."/".$monID; ?>/'" value='Thêm tài liệu' /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;"><span>STT</span></th>
                                    <th style="width:30%;"><span>Tiêu đề</span></th>
                                    <th style="width:10%;"><span>Ngày up</span></th>
                                    <th style="width:10%";><span>View</span></th>
                                    <th style="width:15%;"><span>Giá</span></th>
                                    <th style="width:20%;"><span></span></th>
                                </tr>
                                <?php
									$dem=0;
									$result2=get_tailieu_dm($cdID,0);
									while($data2=mysqli_fetch_assoc($result2)) {
                                        $query="SELECT COUNT(ID_STT) AS dem FROM log WHERE content='$data2[ID_TL]' AND type='xem-tai-lieu'";
                                        $result=mysqli_query($db,$query);
                                        $data=mysqli_fetch_assoc($result);
										echo"<tr>
											<td><span>".($dem+1)."</span></td>
											<td><span>$data2[name]</span></td>
											<td><span>".format_dateup($data2["dateup"])."</span></td>
											<td><span>$data[dem]</span></td>
											<td><span>";	
												if($data2["price"]==0) {
													echo"FREE";
												} else {
													echo format_price($data2["price"]);
												}
											echo"</span></td>
											<td>"; ?>
                                            	<input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/xem-tai-lieu/<?php echo $data2["ID_TL"]; ?>/'" value="Xem" />
												<input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/sua-tai-lieu/<?php echo $data2["ID_TL"]; ?>/'" value="Sửa" />
												<input type="submit" class="submit delete" data-tlID="<?php echo $data2["ID_TL"]; ?>" value='Xóa' />
											<?php 
                                            echo"</td>
										</tr>";
										$dem++;
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
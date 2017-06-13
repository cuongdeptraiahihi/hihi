<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 600);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["monID"]) && is_numeric($_GET["monID"])) {
		$monID=$_GET["monID"];
	} else {
		$monID=0;
	}
	if(isset($_GET["lopID"]) && is_numeric($_GET["lopID"])) {
		$lopID=$_GET["lopID"];
	} else {
		$lopID=0;
	}
	$monID=$_SESSION["mon"];
	$lopID=$_SESSION["lop"];
	$mon_name=get_mon_name($monID);
	$lop_name=get_lop_name($lopID);
	$diem_string=$_SESSION["diem_string"];
	
	$dem_buoi=count_buoi_kt();
	if($dem_buoi<10) {
		$dem_begin=0;
	} else {
		$dem_begin=$dem_buoi-10;
	}
	
	$date_in=get_lop_in($lopID);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>KIỂM TRA TIỀN PHẠT</title>
        
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
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}#list-danhsach {width:6000px !important;background:#FFF;}#list-danhsach tr th {position:relative;}#list-danhsach tr th > span {font-size:12px; !important;}#list-danhsach tr td > span {font-size:12px; !important;}#list-danhsach tr th i {position:absolute;right:0;bottom:0;font-size:14px;cursor:pointer;}.thang-lock:hover {color:blue;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/iscroll.js"></script>
        <script>
			var myScroll;
			function loaded () {
				myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false});
			}
			
			document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
		
			$(document).ready(function() {
				/*$("#MAIN #main-mid .status > input.thuong_a").each(function(index, element) {
                    $("span#thuong_"+index).html("Thưởng: " + $(element).val());
                });
				$("#MAIN #main-mid .status > input.phat_a").each(function(index, element) {
                    $("span#phat_"+index).html("Phạt: " + $(element).val());
                });*/
			});
		</script>
       
	</head>

    <body onload="loaded()">
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <?php
				$xuat=false;
				if(isset($_POST["xuat-ok"])) {
					$xuat=true;
					include ("include/PHPExcel/IOFactory.php");
					$objPHPExcel = new PHPExcel(); 
					$objPHPExcel->setActiveSheetIndex(0); 
				}
			?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Kiểm tra phạt điểm kiểm tra theo môn <span style='font-weight:600;'><?php echo $mon_name; ?></span>, lớp <span style='font-weight:600;'><?php echo $lop_name; ?></span></h2>
                	<div>
                    	<div class="status" style="overflow-x:scroll;" id="main-wapper">
                        	<div></div>
                                <table class="table" id="list-danhsach">
                                	<tr>
                                    	<td colspan="3">
                                        	<form action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post">
                                            	<input type="submit" class="submit" name="xuat-ok" value="Xuất Excel" id="xuat-ok" />
                                           	</form>
                                      	</td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                    	<th style="width:50px;"><span>STT</span></th>
                                    	<th style="width:70px;"><span>Mã</span></th>
                                    	<th style="width:180px;"><span>Họ và tên</span></th>
                                        <th style="width:50px;"><span>Đề</span></th>
                                        <th><span>Tiền thưởng</span></th>
                                        <th><span>Tiền phạt</span></th>
                                        <?php
											$rowCount = 1; 
											$col = 'A';
											if($xuat) {
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "STT");$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã");$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họ và Tên");$col++;
											}
										
											$query3="SELECT ngay FROM buoikt WHERE ngay>'$date_in' ORDER BY ID_BUOI ASC";
											$result3=mysqli_query($db,$query3);
											while($data3=mysqli_fetch_assoc($result3)) {
												echo"<th colspan='2'><span>".format_date($data3["ngay"])."</span></th>";
												if($xuat) {$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_date($data3["ngay"]));}$col++;
											}
										?>
                                    </tr>
                                    <!--<tr>
                                    	<td colspan="4"></td>
                                        <?php
											/*$dem=0;
											$thuong_a=$phat_a=array();
											$query3="SELECT ID_BUOI FROM buoikt ORDER BY ID_BUOI ASC";
											$result3=mysqli_query($db,$query3);
											while($data3=mysqli_fetch_assoc($result3)) {
												echo"<td><span id='thuong_$dem'></span></td>
												<td><span id='phat_$dem'></span></td>";
												$thuong_a[$data3["ID_BUOI"]]=0;
												$phat_a[$data3["ID_BUOI"]]=0;
												$dem++;
											}*/
										?>
                                    </tr>-->
                                    <?php
										$rowCount=2;
									
										$lido=$lido_mau=array();
										$result3=get_all_lido2();
										while($data3=mysqli_fetch_assoc($result3)) {
											$lido[$data3["ID_LD"]]=$data3["name"];
											$lido_mau[$data3["ID_LD"]]=$data3["mau"];
										}
										$dem=0;
										$query="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh_mon.de,hocsinh_mon.date_in FROM hocsinh INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=hocsinh.ID_HS AND hocsinh_mon.ID_LM='$lmID' WHERE hocsinh.lop='$lopID' ORDER BY hocsinh.cmt ASC";
										$result=mysqli_query($db,$query);
										while($data=mysqli_fetch_assoc($result)) {
											
											$col="A";
											
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
											echo"<td><span>".($dem+1)."</span></td>
											<td><span>$data[cmt]</span></td>
											<td><span>$data[fullname]</span></td>
											<td><span>$data[de]</span></td>
											<td><span>".format_price(get_thuong_hs_kt($data["ID_HS"]))."</span></td>
											<td><span>".format_price(get_phat_hs_kt($data["ID_HS"]))."</span></td>";
											
											if($xuat) {
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, ($dem+1));$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["cmt"]);$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["fullname"]);$col++;
											}
											
											$date=date_create($data["date_in"]);
											$query2="SELECT $diem_string.*,buoikt.ID_BUOI,buoikt.ngay FROM buoikt LEFT JOIN diemkt ON $diem_string.ID_BUOI=buoikt.ID_BUOI AND $diem_string.ID_HS='$data[ID_HS]' WHERE buoikt.ngay>'$date_in' ORDER BY buoikt.ID_BUOI ASC";
											$result2=mysqli_query($db,$query2);
											while($data2=mysqli_fetch_assoc($result2)) {
												$today=date_create($data2["ngay"]);
												if($today<$date) {
													echo"<td colspan='2'><span>Chưa học</span></td>";
												} else {
													/*$diff=date_diff($date,$today);
													$kc=$diff->format("%a");*/
													if(is_numeric($data2["loai"])) {
														switch($data2["loai"]) {
															case 0:
																echo"<td><span>$data2[diem] ($data2[de])</span></td>";
																break;
															case 1:
																echo"<td style='background:#3E606F;'><span style='color:#FFF'>$data2[diem] ($data2[de])</span></td>";
																break;
															case 2:
																echo"<td><span>Nghỉ học</span></td>";
																break;
															case 3:
																echo"<td style='background:".$lido_mau[$data2["note"]].";'><span style='color:#FFF'>".$lido[$data2["note"]]."</span></td>";
																break;
															case 4:
																echo"<td><span>Mất bài,<br />nghỉ có phép</span></td>";
																break;
															case 5:
																echo"<td style='background:green;'><span style='color:#FFF'>Không đi thi</span></td>";
																break;
															default:
																echo"<td><span></span></td>";
																break;
														}
													} else {
														echo"<td><span></span></td>";
													}
													if(!check_binh_voi($data2["ID_HS"],$data2["ID_BUOI"],$diem_string)) {
														$phat=get_phat_diemkt($data2["ID_HS"],$data2["diem"],$data2["de"],$data2["loai"],$data2["note"],$monID,$mon_name,$data2["ID_BUOI"],$data2["ngay"],false);
														/*if($phat<0) {
															$phat_a[$data2["ID_BUOI"]]+=$phat;
														} else {
															$thuong_a[$data2["ID_BUOI"]]+=$phat;
														}*/
														echo"<td><span>".format_money_vnd($phat)."</span></td>";
														if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_money_vnd($phat));$col++;}
													} else {
														echo"<td><span>_</span></td>";
														if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "_");$col++;}
													}
												} 
											}
											echo"</tr>";
											$dem++;
											$rowCount++;
										}
									?>
                                </table>
                                <div class="clear"></div>
                                <?php
									/*$query3="SELECT ID_BUOI FROM buoikt ORDER BY ID_BUOI ASC";
									$result3=mysqli_query($db,$query3);
									while($data3=mysqli_fetch_assoc($result3)) {
										echo"<input type='hidden' value='".format_money_vnd($thuong_a[$data3["ID_BUOI"]])."' class='thuong_a' />";
										echo"<input type='hidden' value='".format_money_vnd($phat_a[$data3["ID_BUOI"]])."' class='phat_a' />";
									}*/
								?>
                            </div>
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
		header('Content-Disposition: attachment; filename="tien-phat.xlsx"');
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		// Write the Excel file to filename some_excel_file.xlsx in the current directory
		$objWriter->save('php://output');
	}

	ob_end_flush();
	require_once("../model/close_db.php");
?>
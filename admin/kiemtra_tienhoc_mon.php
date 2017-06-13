<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 120);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
		$lmID=$_GET["lm"];
	} else {
		$lmID=0;
	}
	if(isset($_GET["xuat"]) && is_numeric($_GET["xuat"])) {
		$xuat_ok=$_GET["xuat"];
	} else {
		$xuat_ok=0;
	}
	$lmID=$_SESSION["lmID"];
    $monID=$_SESSION["mon"];
    $mon_name=get_mon_name($monID);
    $lop_mon_name=get_lop_mon_name($lmID);
	$num_thang=$_SESSION["thang"];

    $prce_dau_thang=get_muctien("dau_thang_sau_$mon_name");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>KIỂM TRA TIỀN HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;}.khoang-ma,.only-ma {display:none;}.main-top > ul {float:left;}.main-top #main-left {width:50%;}.main-top #main-left li {width:100%;height:380px;margin-bottom:25px;}.main-top #main-left li ol {display:inline-table;width:43%;height:100%;padding:0 6% 0 0;}.chart-top {width:100%;background:#3E606F;text-align:center;height:35px;}.chart-top span {color:#FFF;text-transform:uppercase;font-size:14px;line-height:35px;}#main-left li ol .chart-main {width:100%;height:310px;margin-top:20px;}.main-top #main-right {width:50%;}.main-top #main-right li {float:left;}#main-right li .chart-main2 > ol {text-align:center;background:#3E606F;height:30px;margin-top:5px;overflow:hidden;}#main-right li .chart-main2 > ol a {color:#FFF;font-size:12px;line-height:30px;}#list-danhsach {width:150% !important;background:#FFF;}#list-danhsach tr th {position:relative;}#list-danhsach tr th > span {font-size:12px; !important;}#list-danhsach tr td > span {font-size:12px; !important;}#list-danhsach tr td span.nghi-info {background:#ffffa5;width:100%;font-size:22px !important;position:absolute;z-index:9;left:0;bottom:0;}.check-phone > input {float:left;width:75%;}.check-phone > span {float:left;width:25%;}.edit-done {display:none;}#list-danhsach tr th i {position:absolute;right:0;bottom:0;font-size:14px;cursor:pointer;}.thang-lock:hover {color:blue;}
			
			.fixed {
			  position: fixed !important;
			  left: 0px;
			}
			
			.table-header {
			  position: fixed;
			  margin-left: 50px;
			  z-index: 3;
			}

        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script>
			var myScroll;
			function loaded () {
				myScroll = new IScroll('#main-wapper', { scrollX: true, scrollY: false, mouseWheel: false});
			}
			document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
		
			$(document).ready(function() {
				var prevTop = 0;
				var prevLeft = 0;
				
				$(window).scroll(function(event){
					var currentTop = $(this).scrollTop();
				  var currentLeft = $(this).scrollLeft();
				  
				  if(prevLeft !== currentLeft) {
					prevLeft = currentLeft;
					$('.header').css({'left': -$(this).scrollLeft()})
				  }
				  if(prevTop !== currentTop) {
					prevTop = currentTop;
					$('.leftCol').css({'top': -$(this).scrollTop() + 40})
				  }
				});
				
				$(".thang-lock").click(function() {
					if(confirm("Bạn có chắc chắn mở tháng này?")) {
						date = $(this).attr("data-date");
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "open=" + date + "&monID=" + <?php echo $monID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-tienhoc/",
							success: function(result) {
								if(result=="none") {
									alert("Bạn đã mở tháng này rồi!");
									$("#popup-loading").fadeOut("fast");
									$("#BODY").css("opacity","1");
								} else {
									location.reload();
								}
							}
						});
					}
				});

				$(".thang-unlock").click(function() {
					if(confirm("Bạn có chắn chắn đóng tháng này?")) {
						date = $(this).attr("data-date");
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "close=" + date + "&monID=" + <?php echo $monID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-tienhoc/",
							success: function(result) {
								location.reload();
							}
						});
					}
				});
				
				$("#xuat-ok").click(function() {
					if(confirm("Bạn có chắc chắn ko? Quá trình sẽ mất nhiều thời gian!")) {
						return true;
					} else {
						return false;
					}
				});
			});
		</script>
       
	</head>

    <body onload="loaded()">
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <?php
				$xuat=false;
				if(isset($_POST["xuat-ok"]) || $xuat_ok==1) {
					$xuat=true;
					include ("include/PHPExcel/IOFactory.php");
					$objPHPExcel = new PHPExcel(); 
					$objPHPExcel->setActiveSheetIndex(0); 
				}
			?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Kiểm tra tiền học theo môn <span style='font-weight:600;'><?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                        	<div class="main-bot">
                        	<div class="clear" id="main-wapper" style="width:100%;overflow:auto;">
                                <div></div>
                                <table class="table" id="list-danhsach" style="position:relative;">
									<?php if($xuat_ok==1) { ?>
                                	<tr>
                                    	<td colspan="3">
                                        	<form action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post">
                                                <input type="submit" class="submit" name="xuat-ok" value="Xuất Excel trang hiện tại" id="xuat-ok" />
                                            </form>
                                        </td>
                                    </tr>

                                	<tr style="background:#3E606F;">
                                    	<th style="width:50px;"><span>STT</span></th>
                                        <th style="width:100px;"><span>Mã số</span></th>
                                    	<th style="width:180px;"><span>Họ và tên</span></th>
                                        <th style="width:100px;"><span>Vào học</span></th>
                                        <th style="width:70px;"><span>Giảm giá<br />(%)</span></th>
									<?php } else { ?>
									<tr style="background:#3E606F;">
									<?php } ?>
                                        <?php
										
											$rowCount = 1; 
											$col = 'A';
											if($xuat) {
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "STT");$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã số");$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họ và tên");$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Vào học");$col++;
												$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Giảm giá (%)");$col++;
											}
										
											$now_nam=date("Y");
											$now_thang=date("m");
											$now_ngay=date("j");;
											$days_of_month=array("31","28","31","30","31","30","31","31","30","31","30","31");
											$temp=split_month(get_lop_mon_in($lmID));
											$nam=$temp[0];
											$thang=$temp[1];
											$open_array=array();
											for($i=1;$i<=24;$i++) {
												$open=check_open_tienhoc("$nam-$thang",$monID);
												if($open) {
													echo"<th><span>$thang/".($nam%100)."<i class='fa fa-unlock thang-unlock' data-date='$nam-$thang' title='Đã mở khóa'></i></span></th>";
													$open_array["$nam-$thang"]=1;
												} else {
													echo"<th><span>$thang/".($nam%100)."<i class='fa fa-lock thang-lock' data-date='$nam-$thang' title='Đang khóa'></i></span></th>";
													$open_array["$nam-$thang"]=0;
												}
												if($xuat){$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "$thang/".($nam%100));$col++;}
												
												$thang++;
												if($thang==13) {
													$thang="01";
													$nam++;
												} else {
													if($thang<10) {
														$thang="0".$thang;
													} else {
														$thang="$thang";
													}
												}
											}
										?>
                                    </tr>
                                    <?php
										if($xuat_ok==1) {

											$rowCount = 2;

											$dem = 0;
											$query = "SELECT h.ID_HS,h.cmt,h.fullname,m.date_in,g.discount,n.date FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN giam_gia AS g ON g.ID_HS=h.ID_HS AND g.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC";
											$result = mysqli_query($db, $query);
											while ($data = mysqli_fetch_assoc($result)) {

												// Lấy giảm giá
												$discount = $data["discount"];
												if ($discount == 0 || !isset($discount)) {
													$discount = "";
												}

												$col = 'A';
												if ($xuat) {
													$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, ($dem + 1));
													$col++;
													$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $data["cmt"]);
													$col++;
													$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $data["fullname"]);
													$col++;
													$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, format_dateup($data["date_in"]));
													$col++;
													$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, $discount);
													$col++;
												}

												if ($dem % 2 != 0) {
													echo "<tr style='background:#D1DBBD'>";
												} else {
													echo "<tr>";
												}
												echo "<td><span>" . ($dem + 1) . "</span></td>
												<td><span>$data[cmt]</span></td>";
												if (isset($data["date"])) {
													echo "<td style='background:green;'><span style='color:#FFF;'>$data[fullname]</span></td>";
												} else {
													echo "<td><span>$data[fullname]</span></td>";
												}
												echo "<td><span>" . format_dateup($data["date_in"]) . "</span></td>
												<td><span>$discount</span></td>";
												$tien_hoc = array();
												$query2 = "SELECT money,date_dong FROM tien_hoc WHERE ID_HS='$data[ID_HS]' AND ID_LM='$lmID' ORDER BY date_dong ASC";
												$result2 = mysqli_query($db, $query2);
												while ($data2 = mysqli_fetch_assoc($result2)) {
												    if(isset($tien_hoc[$data2["date_dong"]])) {
                                                        $tien_hoc[$data2["date_dong"]] += $data2["money"];
                                                    } else {
                                                        $tien_hoc[$data2["date_dong"]] = $data2["money"];
                                                    }
												}
												$nam = $temp[0];
												$thang = $temp[1];
												for ($i = 1; $i <= $num_thang; $i++) {
													if ($nam % 4 == 0) {
														$last_day = 29;
													} else {
														$last_day = $days_of_month[$thang - 1];
													}

													if (isset($tien_hoc["$nam-$thang"])) {
														// Đã đóng tiền
														echo "<td><span>" . format_price_short($tien_hoc["$nam-$thang"]) . "</span></td>";
														if ($xuat) {
															$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_price_short($tien_hoc["$nam-$thang"]), PHPExcel_Cell_DataType::TYPE_STRING);
															$col++;
														}
													} else {
														// Chưa đóng tiền
														$old = false;
														if ($nam < 2016 || ($nam == 2016 && $thang <= 6)) {
															$old = true;
														}
														$now_in2 = date_create("$nam-$thang-02");
														if (stripos($data["date_in"], "$nam-$thang") === false) {
															$temp2 = explode("-", $data["date_in"]);
															$nam_in = $temp2[0];
															$thang_in = $temp2[1];
															if ($nam < $nam_in || ($nam == $nam_in && $thang < $thang_in)) {
																echo "<td style='background:yellow;'><span></span></td>";
																if ($xuat) {
																	$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFFF00');
																	$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "");
																	$col++;
																}
															} else {
																// Học lâu rồi
																$open = $open_array["$nam-$thang"];
																if ($nam < $now_nam || ($nam == $now_nam && $thang <= $now_thang) || $open) {
																	if (!check_hs_nghi($data["ID_HS"], $lmID)) {
																		if (!check_nghi_dai_thang("$nam-$thang", $data["ID_HS"], $lmID)) {
																			// Ko nghỉ hẳn và ko nghỉ dài
																			$price = du_kien_tien_hoc("$nam-$thang", date("Y-m-d"), $data["ID_HS"], $mon_name);
																			echo "<td style='background:red;'><span style='color:#FFF;'>" . format_price_short($price) . "</span></td>";
																			if ($xuat) {
																				$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
																				$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_price_short($price), PHPExcel_Cell_DataType::TYPE_STRING);
																				$col++;
																			}
																		} else {
																			// Ko nghỉ hẳn nhưng có nghỉ dài
																			echo "<td style='background:green;'><span></span></td>";
																			if ($xuat) {
																				$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('008000');
																				$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "");
																				$col++;
																			}
																		}
																	} else {
																		$date_nghi = get_date_nghi($data["ID_HS"], $lmID);
																		$date_nghi2 = date_create($date_nghi);
																		if ($date_nghi2 <= $now_in2) {
																			// Nghỉ từ trước
																			echo "<td style='background:green;'><span></span></td>";
																			if ($xuat) {
																				$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('008000');
																				$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "");
																				$col++;
																			}
																		} else if (stripos($date_nghi, "$nam-$thang") === false) {
																			// Nghỉ sau đó
																			$price = du_kien_tien_hoc("$nam-$thang", date("Y-m-d"), $data["ID_HS"], $mon_name);
																			echo "<td style='background:red;'><span style='color:#FFF;'>" . format_price_short($price) . "</span></td>";
																			if ($xuat) {
																				$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
																				$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_price_short($price), PHPExcel_Cell_DataType::TYPE_STRING);
																				$col++;
																			}
																		} else {
																			// Nghỉ trong tháng đó
																			if ($old) {
																				echo "<td style='background:green;'><span></span></td>";
																				if ($xuat) {
																					$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('008000');
																					$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "");
																					$col++;
																				}
																			} else {
																			    $price = count_hs_di_hoc($data["ID_HS"], $data["date_in"], "$nam-$thang", $lmID, $monID, 0) * get_muctien("tien_hoc_buoi");
																				echo "<td style='background:red;'><span style='color:#FFF;'>" . format_price_short($price - $price * $discount / 100) . "</span></td>";
																				if ($xuat) {
																					$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
																					$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_price_short($price - $price * $discount / 100), PHPExcel_Cell_DataType::TYPE_STRING);
																					$col++;
																				}
																			}
																		}
																	}
																} else {
																	echo "<td><span>_</span></td>";
																	if ($xuat) {
																		$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "");
																		$col++;
																	}
																}
															}
														} else {
															// Mới học trong tháng đó
															if (!check_hs_nghi($data["ID_HS"], $lmID) && !check_nghi_dai_thang("$nam-$thang", $data["ID_HS"], $lmID)) {
																// Không nghỉ hẳn và cũng không nghỉ dài
																$price = $prce_dau_thang;
																echo "<td style='background:red;'><span style='color:#FFF;'>" . format_price_short($price - $price * $discount / 100) . "</span></td>";
																if ($xuat) {
																	$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF0000');
																	$objPHPExcel->getActiveSheet()->setCellValueExplicit("$col" . $rowCount, format_price_short($price - $price * $discount / 100), PHPExcel_Cell_DataType::TYPE_STRING);
																	$col++;
																}
															} else {
																echo "<td style='background:green;'><span></span></td>";
																if ($xuat) {
																	$objPHPExcel->getActiveSheet()->getStyle("$col" . $rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('008000');
																	$objPHPExcel->getActiveSheet()->SetCellValue("$col" . $rowCount, "");
																	$col++;
																}
															}
														}
													}

													$thang++;
													if ($thang == 13) {
														$thang = "01";
														$nam++;
													} else {
														$thang = format_month_db($thang);
													}
												}
												echo "</tr>";
												$dem++;
												$rowCount++;
											}
										}
									?>
                                </table>
                           	</div>
                            <div class="clear"></div>
                            </div>
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
		header('Content-Disposition: attachment; filename="tien-hoc.xlsx"');
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		// Write the Excel file to filename some_excel_file.xlsx in the current directory
		$objWriter->save('php://output');
	}

	ob_end_flush();
	require_once("../model/close_db.php");
?>
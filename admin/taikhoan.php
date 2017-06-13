<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["am"]) && is_numeric($_GET["am"])) {
		$am=$_GET["am"];
	} else {
		$am=0;
	}
	$lmID=$_SESSION["lmID"];
	$lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>QUẢN LÝ TIỀN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.fa {font-size:1.375em !important;}
            table tr td span a {text-decoration: underline;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#xuat-ok").click(function() {
				if(confirm("Bạn có chắc chắn ko? Quá trình sẽ mất nhiều thời gian!")) {
					return true;
				} else {
					return false;
				}
			});
            $("#loi-nhuan-am").click(function() {
                var dem = 1;
                $("#MAIN #main-mid .status .table tr.tr-info").each(function(index,element) {
                    loin = $(element).find("> input.loi-nhuan").val();
                    if(loin>=0) {
                        $(element).hide();
                    } else {
                        $(element).find("td:first-child span").html(dem);
                        dem++;
                    }
                });
            });
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
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
                <?php
					if($am==1) {
                		echo"<h2>Danh sách học sinh tài khoản âm <span style='font-weight:600;'>môn $lop_mon_name</span></h2>";
					} else {
						echo"<h2>Quản lý tiền <span style='font-weight:600;'>môn $lop_mon_name</span></h2>";
					}
				?>
                	<div>
                    	<div class="status">	
                         	<table class="table">
                            	<tr>
                                    <th style="border: none;"><input class='submit' type='submit' value='Tài khoản âm' onclick="location.href='http://localhost/www/TDUONG/admin/tai-khoan/<?php echo $lmID; ?>/1/'" /></th>
                                    <th style="border: none;"><input class='submit' type='submit' value='Lợi nhuận âm' id="loi-nhuan-am" /></th>
                                    <th style="border: none;"><input class='submit' type='submit' value='Tất cả' onclick="location.href='http://localhost/www/TDUONG/admin/tai-khoan/<?php echo $lmID; ?>/2/'" /></th>
                                    <th style="border: none;">
                                    	<form action="<?php echo "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; ?>" method="post">
                                        	<input class='submit' type='submit' value='Xuất Excel' id="xuat-ok" name="xuat-ok" />
                                        </form>
                                    </th>
                                </tr>
                            	<tr style="background:#3E606F">
                                	<th class='hidden' style="width:5%;"><span>STT</span></th>
                                    <th style="width:15%;"><span>Họ tên - Mã số</span></th>
                                    <th style="width:10%;"><span>Tài khoản</span></th>
                                    <th class="need-ex" style="width:30%;" colspan="3"><span>Tổng thu</span>
                                    	<div class="explain"><p>Tổng tiền thu vào từ học sinh này</p></div>
                                    </th>
                                    <th class="need-ex" style="width:30%;" colspan="3"><span>Tổng chi</span>
                                    	<div class="explain"><p>Tổng tiền chi ra cho học sinh này</p></div>
                                    </th>
                                    <th class="hidden" style="width:10%;"><span>Lợi nhuận</span></th>
                                </tr>
                                <?php
									$rowCount = 1; 
									$col = 'A';
									if($xuat) {
										$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "STT");$col++;
										$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họ tên");$col++;
										$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã sô");$col++;
                                        $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tài khoản");$col++;
										$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tổng thu");$col++;
                                        $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
										$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tổng chi");$col++;
                                        $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
                                        $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Lợi nhuận");$col++;
									}
									
									$rowCount = 2;
								
									if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
										$position=$_GET["begin"];
									} else {
										$position=0;
									}
									$dem=0;$display=30;
									if($am==1) {
										$result=get_all_tien_am($lmID);
									} else if($am==2) {
									    $query="SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt_bo,h.sdt_me,h.taikhoan,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC";
										$result=mysqli_query($db,$query);
									} else {
                                        $query="SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt_bo,h.sdt_me,h.taikhoan,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC LIMIT $position,$display";
										$result=mysqli_query($db,$query);
									}
									while($data=mysqli_fetch_assoc($result)) {
										
										$col="A";
										
										$thuong=get_thuong_hs_con($data["ID_HS"]);
										$phat=get_phat_hs_con($data["ID_HS"]);
                                        $da_nap=get_da_nap_hs($data["ID_HS"]);
                                        $da_rut=get_da_rut_hs($data["ID_HS"]);
                                        if(isset($data["ID_N"])) {
                                            echo "<tr class='tr-info' style='background:#000;'>";
                                        } else {
                                            if ($dem % 2 != 0) {
                                                echo "<tr class='tr-info' style='background:#D1DBBD;'>";
                                            } else {
                                                echo "<tr class='tr-info'>";
                                            }
                                        }
										if($phat-$da_nap<0) {
										    $con1=0;
                                        } else {
                                            $con1=$phat-$da_nap;
                                        }
                                        if($thuong-$da_rut<0) {
                                            $con2=0;
                                        } else {
                                            $con2=$thuong-$da_rut;
                                        }
                                        if($con2>=$con1) {
                                            $con2=$con2-$con1;
                                            $con1=0;
                                        } else {
                                            $con1=$con1-$con2;
                                            $con2=0;
                                        }
                                        $loi_n=$da_nap+$con1-($da_rut+$con2);
										echo"
											<td class='hidden'><span>".($dem+1)."</span></td>
											<td><span><a href='$data[facebook]' target='_blank'>$data[fullname]</a><br /><a href='https://localhost/www/TDUONG/dang-nhap/$data[ID_HS]/".get_hs_ph_sdt($data["sdt_bo"],$data["sdt_me"])."/' target='_blank'>($data[cmt])</a></span></td>
											<td><span>".format_money_vnd($data["taikhoan"])."</span></td>
											<td style='width:10%;'><span>Đã nạp: ".format_money_vnd($da_nap)."</span></td>
											<td><span>Cần thu: ".format_money_vnd($con1)."</span></td>
											<td><input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/admin/phat/$data[ID_HS]/'\" value='Xem' /></td>
											<td style='width:10%;'><span>Đã rút: ".format_money_vnd($da_rut)."</span></td>
											<td><span>Cần chi: ".format_money_vnd($con2)."</span></td>
											<td><input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/admin/thuong/$data[ID_HS]/'\" value='Xem' /></td>
											<td class='hidden'><span>".format_money_vnd($loi_n)."</span></td>
											<input type='hidden' class='loi-nhuan' value='$loi_n' />
										</tr>";
										
										if($xuat){
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, ($dem+1));$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["fullname"]);$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["cmt"]);$col++;
                                            $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_money_vnd($data["taikhoan"]));$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_money_vnd($da_nap));$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_money_vnd($con1));$col++;
											$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_money_vnd($da_rut));$col++;
                                            $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_money_vnd($con2));$col++;
                                            $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_money_vnd($loi_n));$col++;
										}
										
										$dem++;
										$rowCount++;
									}
									if($dem==0) {
										echo"<tr><td colspan='10'><span>Không có dữ liệu</span></td></tr>";
									}
								?>
                        	</table>
                        </div>
                        <?php
						if($am==0) {
							$query3="SELECT h.ID_HS FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID'";
							$result3=mysqli_query($db,$query3);
							$sum=mysqli_num_rows($result3);
							$sum_page=ceil($sum/$display);
							if($sum_page>1) {
								$current=($position/$display)+1;
						?>
                        <div class="page-number">
                        	<ul>
                            <?php
								if($current!=1) {
									$prev=$position-$display;
									echo"<li><a href='http://localhost/www/TDUONG/admin/tai-khoan/page-$prev/'><</a></li>";
								}
								for($page=1;$page<=$sum_page;$page++) {
									$begin=($page-1)*$display;
									if($page==$current) {
										echo"<li><a href='http://localhost/www/TDUONG/admin/tai-khoan/page-$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
									} else {
										echo"<li><a href='http://localhost/www/TDUONG/admin/tai-khoan/page-$begin/'>$page</a></li>";
									}
								}
								if($current!=$sum_page) {
									$next=$position+$display;
									echo"<li><a href='http://localhost/www/TDUONG/admin/tai-khoan/page-$next/'>></a></li>";
								}
							?>
                            </ul>
                        </div>
                        <?php
							}
						}
						?>
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
		header('Content-Disposition: attachment; filename="thong-ke-phat.xlsx"');
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
		// Write the Excel file to filename some_excel_file.xlsx in the current directory
		$objWriter->save('php://output');
	}

	ob_end_flush();
	require_once("../model/close_db.php");
?>
<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"])) {
		$hsID=$_GET["hsID"];
	} else {
		$hsID=0;
	}
	$result0=get_hs_short_detail2($hsID);
	$data0=mysqli_fetch_assoc($result0);
	if($hsID!=0) {
		$ma=$data0["cmt"];
	} else {
		$ma="";
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SỬA ĐỀ</title>
        
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
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
			$(document).ready(function() {
				
				$("#search-hs").keyup(function() {
					text = $(this).val().trim();
					if(text != '' && text != '%' && text != ' ' && text != '_' && text.length==7) {
						$.ajax({
							async: true,
							data: "search=" + text,
							type: "get",
							url: "http://localhost/www/TDUONG/admin/xuly-search-hs/",
							success: function(result) {
								$("#search-box > ul").html(result);
								$("#search-box").fadeIn("fast");
							}
						});
						return false;
					} else {
						$("#search-box").fadeOut("fast");
					}
				});
				
				$("#search-box ul").delegate("li a", "click", function() {
					cmt = $(this).attr("data-cmt");
					$("#search-hs").val(cmt);
					$("#search-box").fadeOut("fast");
				});
				
				$("#search-ok").click(function() {
					cmt = $("#search-hs").val();
					if(cmt!="" && cmt!="%" && cmt!=" " && cmt!="_") {
						return true;
					} else {
						alert("Xin vui lòng nhập đầy đủ thông tin và chính xác!");
						return false;
					}
				});
				
				$(".date_in").datepicker({
					dateFormat: "yy-mm-dd"
				});
				
				$("#hs-de tr td select.date_kt").change(function() {
					me = $(this);
					monID = $(this).closest("tr").find("td span.de_kt").attr("data-monID");
					ngay = $(this).find("option:selected").val();
					if(ngay!="" && $.isNumeric(monID) && monID!=0) {
						get_de_kt(ngay,<?php echo $hsID; ?>,monID);
					}
				});
				
				function get_de_kt(ngay, hsID, lmID) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity", "0.3");
					$.ajax({
						async: true,
						data: "ngay=" + ngay + "&hsID0=" + hsID + "&lmID=" + lmID,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
						success: function(result) {
							me.closest("tr").find("td span.de_kt").html(result);
							$("#BODY").css("opacity", "1");
							$("#popup-loading").fadeOut("fast");
						}
					});
				}
				
				$("#hs-de tr td input.edit-only-de").click(function() {
					me = $(this);
					monID = $(this).closest("tr").find("td span.de_kt").attr("data-monID");
					date_in = $(this).closest("tr").find("td input.date_in").val();
					de = $(this).closest("tr").find("td select.de_in option:selected").val();
                    ngay = $(this).closest("tr").find("td select.date_kt option:selected").val();
					if($.isNumeric(monID) && monID!=0 && date_in!="" && (de=="B" || de=="G" || de=="Y") && ngay!="") {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity", "0.3");
						$.ajax({
							async: true,
							data: "ngay3=" + ngay + "&date_in2=" + date_in + "&de2=" + de + "&lmID2=" + monID + "&hsID2=" + <?php echo $hsID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
							success: function(result) {
								me.val("Xong").css("background","blue");
								$("#BODY").css("opacity", "1");
								$("#popup-loading").fadeOut("fast");
							}
						});
					}
				});
				
				$("#hs-de tr td input.edit-de-kt").click(function() {
					me = $(this);
					monID = $(this).closest("tr").find("td span.de_kt").attr("data-monID");
					de = $(this).closest("tr").find("td select.de_in option:selected").val();
					ngay = $(this).closest("tr").find("td select.date_kt option:selected").val();
					if($.isNumeric(monID) && monID!=0 && ngay!="" && (de=="B" || de=="G" || de=="Y")) {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity", "0.3");
						$.ajax({
							async: true,
							data: "ngay2=" + ngay + "&de2=" + de + "&lmID2=" + monID + "&hsID2=" + <?php echo $hsID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
							success: function(result) {
								me.val("Xong").css("background","blue");
								get_de_kt(ngay,<?php echo $hsID; ?>,monID);
								$("#BODY").css("opacity", "1");
								$("#popup-loading").fadeOut("fast");
							}
						});
					}
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
            
            <div id="MAIN">
            	
                
                
                <?php
					$cmt=$ID=NULL;
					if(isset($_GET["search-ok"])) {
						
						if(isset($_GET["search-hs"])) {
							$cmt=$_GET["search-hs"];
							$ID=get_hs_id($cmt);
						}
						
						if($cmt && $ID) {
							header("location:http://localhost/www/TDUONG/admin/sua-de/$ID/");
							exit();
						}
					}
				?>
                
                <div id="main-mid">
                	<h2>TRANG CHỦ</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/sua-de/" method="get">
                            	<table class="table">
                                    <tr>
                                    	<td style="width:50%;"><span>Mã</span></td>
                                        <td style="width:50%;">
                                            <input type="text" name="search-hs" value="<?php echo $ma; ?>" placeholder="Mã số học sinh..." autocomplete="off" class="input" id="search-hs" />
                                            <nav id="search-box">
                                                <ul>
                                                </ul>
                                            </nav>
                                        </td>
                                  	</tr>
                                    <tr>
                                        <th colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="search-ok" type="submit" id="search-ok" value="Sửa" /></th>
                                    </tr>
                                </table>
                            </form>
                            <table class="table" id="hs-de" style="margin-top:25px;">
                            <?php
								if($hsID!=0) {
									echo"<tr>
										<td style='border: none;' colspan='6'><span>$data0[cmt] - $data0[fullname] - ".format_dateup($data0["birth"])." - ".get_gender($data0["gender"])."</span></td>
									</tr>
									<tr style='background:#3E606F'>
										<th style='width:20%;'><span>Môn</span></th>
										<th class='hidden' style='width:15%;'><span>Ngày vào học</span></th>
										<th style='width:15%;'><span>Hiện tại</span></th>
										<th class='hidden' style='width:15%;'><span>Bài KT</span></th>
										<th style='width:20%;'><span>Tháng</span></th>
										<th><span></span></th>
									</tr>";
									$query="SELECT h.*,m.name,m.ID_MON,h.ID_LM FROM hocsinh_mon AS h INNER JOIN lop_mon AS m ON m.ID_LM=h.ID_LM WHERE h.ID_HS='$hsID' ORDER BY h.ID_LM ASC";
									$result=mysqli_query($db,$query);
									while($data=mysqli_fetch_assoc($result)) {
										$dekt="";
										$query2="SELECT d.de FROM diemkt AS d INNER JOIN buoikt AS b ON b.ID_BUOI=d.ID_BUOI AND b.ID_MON='$data[ID_MON]' AND b.ngay LIKE '".date("Y-m")."-%' WHERE d.ID_HS='$hsID' AND d.ID_LM='$data[ID_LM]' ORDER BY d.ID_BUOI ASC";
										$result2=mysqli_query($db,$query2);
										while($data2=mysqli_fetch_assoc($result2)) {
											$dekt.=", $data2[de]";
										}
										$dekt=substr($dekt,1);
										echo"<tr>
											<td><span>$data[name]</span></td>
											<td class='hidden'><input type='text' class='input date_in' value='$data[date_in]' style='text-align:center' /></td>
											<td>
												<select class='input de_in' style='width:100%;height:auto;'>
												    <option value='Y' ";if($data["de"]=="Y"){echo"selected='selected'";}echo">Y</option>
													<option value='B' ";if($data["de"]=="B"){echo"selected='selected'";}echo">B</option>
													<option value='G' ";if($data["de"]=="G"){echo"selected='selected'";}echo">G</option>
												</select>
											</td>
											<td class='hidden'><span class='de_kt' data-monID='$data[ID_LM]'>$dekt</span></td>
											<td>
												<select class='input date_kt' style='width:100%;height:auto;text-align:center'>";
												$now_nam=date("Y");
												$now_thang=date("m");
												$i=4;
												while($i>=0) {
													echo"<option value='$now_nam-$now_thang'>$now_thang/$now_nam</option>";
													$now_nam=get_last_year($now_thang,$now_nam);
													$now_thang=get_last_month($now_thang);
													$i--;
												}
												echo"</select>
											</td>
											<td>
												<input class='submit edit-only-de' type='submit' value='Sửa đề hiện tại' /><br />
												<input class='submit edit-de-kt' type='submit' value='Sửa đề các bài KT' />
											</td>
										</tr>";
									}
								}
							?>
                            	<tr>
                                	<td colspan="6" style="text-align: left;padding-left: 15px;"><span>Sửa đề hiện tại là chỉ sửa đề hiện tại mà e đó đang có (sẽ lỗi dữ liệu nếu thời điểm sửa là giữa tháng, lí do vì nếu sửa giữa tháng, các bài KT trước đó của tháng ấy vẫn mang đề cũ => Sử dụng Sửa đề các bài KT sau khi sửa đề)</span></td>
                                </tr>
                                <tr>
                                	<td colspan="6" style="text-align: left;padding-left: 15px;"><span>Sửa đề các bài kiểm tra là sửa đề của tất cả các bài kiểm tra trong tháng đó thành đề hiện tại</span></td>
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
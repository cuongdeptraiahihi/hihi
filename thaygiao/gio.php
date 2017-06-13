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
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
    $mon_lop_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>CÁC KHUNG GIỜ THUỘC LỚP <?php echo $mon_lop_name; ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit", "click", function() {
				return gioID = $(this).attr("data-gioID"), del_tr = $(this).closest("tr"), del_tr.css("opacity", "0.3"), gio = del_tr.find("td input.gio_" + gioID).val(), buoi = del_tr.find("td select.buoi_" + gioID).val(), thutu = del_tr.find("td input.thutu_" + gioID).val(), gio!="" && thutu!="" ? ($.ajax({
					async: true,
					data: "gioID0=" + gioID + "&gio=" + gio + "&buoi=" + buoi + "&thutu=" + thutu,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-gio/",
					success: function(t) {
						location.reload()
					}
				}), !1) : void 0
			}), $("#add-gio").click(function() {
				$("#input-add").val(""), $("#popup-add").fadeIn("fast"), $("#BODY").css("opacity", "0.3")
			}), $(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast"), $("#BODY").css("opacity", "1")
			}), $("#popup-ok").click(function() {
				gio = $("#gio-add").val(), lm = $("#lop-add").val(), buoi = $("#buoi-add").val(), thutu = $("#thutu-add").val(), "" != gio && $.isNumeric(lm) ? $.ajax({
					async: true,
					data: "gio=" + gio + "&mon=" + <?php echo $monID; ?> + "&lm=" + lm + "&buoi=" + buoi + "&thutu=" + thutu,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-gio/",
					success: function(t) {
						$("#popup-add").fadeOut("fast"), $("#BODY").css("opacity", "1"), location.reload()
					}
				}) : alert("Bạn vui lòng nhập đầy đủ thông tin và chính xác!")
			}), $("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
				return gioID = $(this).attr("data-gioID"), del_tr = $(this).closest("tr"), gioID!=0 && $.isNumeric(gioID) ? ($.ajax({
					async: true,
					data: "gioID1=" + gioID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-gio/",
					success: function(t) {
						del_tr.fadeOut("fast")
					}
				}), !1) : void 0
			})
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm khung giờ</p>
            <div style="width:90%;margin:15px auto 15px auto;">
                <ul class="ul-2">
                    <li style="width:49%;"><input type="text" class="input" id="gio-add" placeholder="16h-17h30" /></li>
                    <li style="width:49%;">
                    	<select class="input" id="lop-add" style="width:100%;">
                        	<option value="<?php echo $lmID; ?>"><?php echo $mon_lop_name; ?></option>
                    	</select>
               		</li>
                </ul>
                <ul class="ul-2">
                    <li style="width:49%;">
                    	<select class="input" id="buoi-add" style="width:100%;">
                        	<option value="1S">Sáng</option>
                            <option value="2C">Chiều</option>
                            <option value="3T">Tối</option>
                    	</select>
                   	</li>
                    <li style="width:49%;">
                    	<select class="input" style="height:auto;width:100%" id="thutu-add">
                       	<?php
							$thutu_array=array("a","b","c","d","e","f","g");
							for($i=0;$i<count($thutu_array);$i++) {
								echo"<option value='$thutu_array[$i]'>$thutu_array[$i]</option>";
							}
						?>
                    	</select>
               		</li>
                </ul>
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>CÁC KHUNG GIỜ THUỘC LỚP <span style="font-weight:600"><?php echo $mon_lop_name; ?></span></h2>
                	<div>
                    	<div class="status">
                        	<table class="table">
                                <tr>
                                    <td class="hidden"><span>Liên quan</span></td>
                                    <th style="border: none;"><input type="submit" class="submit" value="Khung giờ" onclick="location.href='http://localhost/www/TDUONG/thaygiao/gio/<?php echo $lmID."/".$monID; ?>/'" /></th>
                                    <th style="border: none;"></th>
                                    <th style="border: none;"></th>
                                </tr>
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
                            	<table class="table" style="margin-top:25px;">
                                	<tr>
                                        <td colspan="6"><input type="submit" class="submit" id="add-gio" value="Thêm khung giờ" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>STT</span></th>
                                        <th style="width:25%;"><span>Mô tả</span></th>
                                        <th class="hidden" style="width:15%"><span>Buổi</span></th>
                                        <th class="hidden" style="width:15%"><span>Thứ tự</span></th>
                                        <th style="width:15%;"><span>Lớp</span></th>
                                        <th class="hidden" style="width:15%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_all_cagio_lop($monID,$lmID);
										while($data=mysqli_fetch_assoc($result)) {
											if(isset($_SESSION["new_gio"])) {
												if($data["ID_GIO"]==$_SESSION["new_gio"]) {
													echo"<tr style='background:#ffffa5'>";
												} else {
													if($dem%2!=0) {
														echo"<tr style='background:#D1DBBD'>";
													} else {
														echo"<tr>";
													}
												}
												$_SESSION["new_gio"]=0;
											} else {
												if($dem%2!=0) {
													echo"<tr style='background:#D1DBBD'>";
												} else {
													echo"<tr>";
												}
											}
									?> 
                                    	<td><span><?php echo ($dem+1);?></span></td>
                                        <td><input type="text" class="input gio_<?php echo $data["ID_GIO"];?>" value="<?php echo $data["gio"];?>" /></td>
                                        <td class="hidden">
                                        	<select class="input buoi_<?php echo $data["ID_GIO"];?>" style="height:auto">
                                            	<option value="1S" <?php if($data["buoi"]=="1S"){echo"selected='selected'";} ?>>Sáng</option>
                                                <option value="2C" <?php if($data["buoi"]=="2C"){echo"selected='selected'";} ?>>Chiều</option>
                                                <option value="3T" <?php if($data["buoi"]=="3T"){echo"selected='selected'";} ?>>Tối</option>
                                            </select>
                                        </td>
                                        <td class="hidden"><input type="text" class="input thutu_<?php echo $data["ID_GIO"];?>" value="<?php echo $data["thutu"];?>" /></td>
                                        <td><span><?php echo $mon_lop_name; ?></span></td>
                                        <td class="hidden">
                                            <input type="submit" class="submit edit" data-gioID="<?php echo $data["ID_GIO"];?>" value="Sửa" />
                                            <?php
												if(count_ca_base_gio($data["ID_GIO"])==0) {
											?>
                                            <input type="submit" class="submit delete" data-gioID="<?php echo $data["ID_GIO"];?>" value="Xóa" />
                                            <?php } ?>
                                      	</td>
                                    </tr>
									<?php 
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
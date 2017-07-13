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
        
        <title>CÁC CA HỌC THUỘC LỚP <?php echo $mon_lop_name; ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:45px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit", "click", function() {
				return caID = $(this).attr("data-caID"), del_tr = $(this).closest("tr"), del_tr.css("opacity", "0.3"), siso = del_tr.find("td input.siso_" + caID).val(), toida = del_tr.find("td input.max_" + caID).val(), cum = del_tr.find("td select.cum_" + caID).val(), ddID = del_tr.find("td select.diadiem_" + caID).val(), $.isNumeric(siso) && $.isNumeric(toida) && $.isNumeric(ddID) && $.isNumeric(cum) ? ($.ajax({
					async: true,
					data: "caID0=" + caID + "&siso=" + siso + "&max=" + toida + "&cum=" + cum + "&ddID=" + ddID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-ca/",
					success: function(t) {
						del_tr.css("opacity", "1")
					}
				}), !1) : void 0
			}), $("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
				return confirm("Bạn có chắc chắn xóa ca này không?") ? (caID = $(this).attr("data-caID"), del_tr = $(this).closest("tr"), $.ajax({
					async: true,
					data: "caID=" + caID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-ca/",
					success: function(t) {
						del_tr.fadeOut("fast")
					}
				}), !1) : void 0
			}), $("#add-ca").click(function() {
				$("#input-add").val(""), $("#popup-add").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), $("#siso-add").val(""), $("#max-add").val("")
			}), $(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast"), $("#BODY").css("opacity", "1")
			}), $("#popup-ok").click(function() {
				thu = $("#thu-add").val(), siso = $("#siso-add").val(), toida = $("#max-add").val(), gio = $("#gio-add option:selected").val(), cum = $("#cum-add").val(), "" != siso && "" != toida && siso <= toida && cum > 0 && thu > 0 && thu <= 7 ? $.ajax({
					async: true,
					data: "thu=" + thu + "&siso=" + siso + "&max=" + toida + "&gio=" + gio + "&cum=" + cum,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-ca/",
					success: function(t) {
						$("#popup-add").fadeOut("fast"), $("#BODY").css("opacity", "1"), location.reload()
					}
				}) : alert("Bạn vui lòng nhập đầy đủ thông tin và chính xác!")
			})
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm ca học</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            	<ul class="ul-3">
                	<li>
                        <select class="input" id="thu-add" style="width:100%">
                        <?php
							if($lmID!=0) {
								for($i=2;$i<=7;$i++) {
									echo"<option value='$i'>".$thu_string[$i-2]."</option>";
								}
							} else {
								echo"<option value='1'>".$thu_string[0]."</option>";
							}
                        ?>
                        </select>
                  	</li>
                    <li style="margin:0 1% 0 1%;"><input class="input" id="siso-add" placeholder="Sĩ số" type="number" min="0" step="1" /></li>
                    <li><input class="input" placeholder="Max" id="max-add" type="number" min="0" step="1"/></li>
              	</ul>
                <ul class="ul-2">
                    <li style="width:70%;">
                    	<select class="input" id="gio-add" style="width:100%">
                    <?php
                        $result1=get_all_gio_lop($monID, $lmID);
                        while($data1=mysqli_fetch_assoc($result1)) {
                            echo"<option value='$data1[ID_GIO]'>Ca $data1[gio]</option>";
                        }
                    ?>
                    	</select>
                   	</li>
                    <li style="width:28%;">
                    	<select class="input" style="height:auto;width:100%" id="cum-add">
                       	<?php
							if($lmID!=0) {
								echo"<option value='0'>Cụm</option>";
								$result2=get_all_cum($lmID,$monID);
								while($data2=mysqli_fetch_assoc($result2)) {
									echo"<option value='$data2[ID_CUM]'>$data2[name]</option>";
								}
							} else {
								$result2=get_cum_kt($monID);
								$data2=mysqli_fetch_assoc($result2);
								echo"<option value='$data2[ID_CUM]'>$data2[name]</option>";
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
                	<h2>CÁC CA HỌC THUỘC MÔN <span style="font-weight:600;"><?php echo $mon_lop_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <table class="table">
                                <tr>
                                    <td class="hidden"><span>Liên quan</span></td>
                                    <td style="border: none;"><input type="submit" class="submit" value="Khung giờ" onclick="location.href='http://localhost/www/TDUONG/thaygiao/gio/<?php echo $lmID."/".$monID; ?>/'" /></td>
                                    <td style="border: none;"></td>
                                    <td style="border: none;"></td>
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
                                    <td colspan="6"><input type="submit" class="submit" id="add-ca" value="Thêm ca học" /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;"><span>Thứ</span></th>
                                    <th style="width:15%;"><span>Giờ</span></th>
                                    <th class="hidden" style="width:20%;"><span>Hiện tại/Sĩ số/Max</span></th>
                                    <th style="width:10%;"><span>Cụm</span></th>
                                    <th class="hidden" style="width:15%;"><span>Địa điểm</span></th>
                                    <th class="hidden"  style="width:30%;"><span>Danh sách</span></th>
                                </tr>
                                <?php
                                    $dem=0;
                                    $result = get_all_cahoc_lop($lmID,$monID);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        $num=get_num_hs_ca_hientai($data["ID_CA"]);
                                        if(isset($_SESSION["new_ca"])) {
                                            if($data["ID_CA"]==$_SESSION["new_ca"]) {
                                                echo"<tr style='background:#ffffa5'>";
                                            } else {
                                                if($dem%2!=0) {
                                                    echo"<tr style='background:#D1DBBD'>";
                                                } else {
                                                    echo"<tr>";
                                                }
                                            }
                                            $_SESSION["new_ca"]=0;
                                        } else {
                                            if($dem%2!=0) {
                                                echo"<tr style='background:#D1DBBD'>";
                                            } else {
                                                echo"<tr>";
                                            }
                                        }
                                ?> 
                                    <td><span><?php if($lmID!=0) {echo $thu_string[$data["thu"]-2];} else {echo $thu_string[$data["thu"]-1];}?></span></td>
                                    <td><span><?php echo $data["gio"];?></span></td>
                                    <td class='hidden'><span><?php echo $num."/</span><input class='input input-2 siso_$data[ID_CA]' value='$data[siso]' type='number' min='0' step='1' /> (<input type='number' value='$data[max]' class='input input-2 max_$data[ID_CA]' min='0' step='1' />)";?></span></td>
                                    <td>
                                        <select class="input cum_<?php echo $data["ID_CA"];?>" style="height:auto;">
                                        <?php
                                            if($lmID!=0) {
                                                $result2=get_all_cum($lmID,$monID);
                                                while($data2=mysqli_fetch_assoc($result2)) {
                                                    echo"<option value='$data2[ID_CUM]' ";if($data["cum"]==$data2["ID_CUM"]){echo"selected='selected'";}echo">$data2[name]</option>";
                                                }
                                            } else {
                                                echo"<option value='4'>Kiểm tra</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                    <td class='hidden'>
                                    	<select class="input diadiem_<?php echo $data["ID_CA"];?>" style="height:auto;">
										<?php
                                            $result3=get_all_dia_diem();
                                            while($data3=mysqli_fetch_assoc($result3)) {
                                                echo"<option value='$data3[ID_DD]' ";if($data3["ID_DD"]==$data["ID_DD"]){echo"selected='selected'";}echo">$data3[name]</option>";
                                            }
                                        ?>
                                        </select>
                                    </td>
                                    <td class='hidden'>
                                        <input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/ca-hien-tai/<?php echo $data["ID_CA"];?>/'" value="Hiện tại" />
                                        <input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/ca-co-dinh/<?php echo $data["ID_CA"];?>/'" value="Cố định" />
                                        <input type="submit" class="submit edit" data-caID="<?php echo $data["ID_CA"];?>" value="Sửa" />
                                        <?php
                                            if($num==0) {
                                                echo"<input type='submit' class='submit delete' data-caID='$data[ID_CA]' value='Xóa' />";
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php 
                                        $dem++;
                                    }
                                    if($dem==0) {
                                        echo"<tr><td colspan='6'><span>Không có dữ liệu</span></td></tr>";
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
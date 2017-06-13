<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>MÔN HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}.input {text-align:center;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit_ok", "click", function() {
				monID = $(this).attr("data-monID");
				del_tr = $(this).closest("tr");
				del_tr.css("opacity","0.3");
				name = del_tr.find("td input.edit_name").val();
				thang = del_tr.find("td input.edit_thang").val();
				tien = del_tr.find("td input.edit_tien").val();
				tienID = del_tr.find("td input.edit_tien").attr("data-tienID");
                is_phat = is_tinh = nhayde = auto = 0;
                if(del_tr.find("td input.is_phat").is(":checked")) {
                    is_phat = 1;
                }
                if(del_tr.find("td input.is_tinh").is(":checked")) {
                    is_tinh = 1;
                }
                nhayde = del_tr.find("td input.nhayde").val();
                if(nhayde == "") {nhayde = 0;}
                auto = del_tr.find("td input.auto").val();
                if(auto == "") {auto = 0;}
				if(name!="" && $.isNumeric(monID) && $.isNumeric(thang) && $.isNumeric(tienID) && $.isNumeric(tien) && $.isNumeric(is_phat) && $.isNumeric(is_tinh) && $.isNumeric(nhayde) && auto != "") {
					$.ajax({
						async: true,
						data: "monID0=" + monID + "&name=" + name + "&thang=" + thang + "&tien=" + tien + "&tienID=" + tienID + "&is_phat=" + is_phat + "&is_tinh=" + is_tinh + "&nhayde=" + nhayde + "&auto=" + auto,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
						success: function(result) {
							del_tr.css("opacity","1");
						}
					});
				}
				return false;
			});

            $("#MAIN #main-mid .status .table").delegate("tr td input.edit_ok2", "click", function() {
                lmID = $(this).attr("data-lmID");
                del_tr = $(this).closest("tr");
                del_tr.css("opacity","0.3");
                name = del_tr.find("td input.edit_name").val();
                date_in = del_tr.find("td input.edit_date").val();
                if(name!="" && $.isNumeric(lmID)) {
                    $.ajax({
                        async: true,
                        data: "lmID0=" + lmID + "&name=" + name + "&date_in=" + date_in,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            del_tr.css("opacity","1");
                        }
                    });
                }
                return false;
            });
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">

                <div id="main-mid">
                	<h2>DANH SÁCH CÁC MÔN</span></h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                                <tr>
                                    <td class='hidden' colspan="4"><span></span></td>
                                    <td colspan="2"><input type="submit" class="submit" id="add_mon" value="Thêm môn mới" /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th class='hidden' style="width:10%;"><span>ID</span></th>
                                    <th style="width:10%;"><span>Môn</span></th>
                                    <th class='hidden' style="width:15%;"><span>Số tháng</span></th>
                                    <th style="width:20%;"><span>Định mức tiền học</span></th>
                                    <th class='hidden'><span>Cài đặt</span></th>
                                    <th style="width:15%;"><span></span></th>
                                </tr>
                                <?php
                                    $dem=0;
                                    $result=get_all_mon();
                                    while($data=mysqli_fetch_assoc($result)) {
                                        $tienID=$tien=$tienID2=$tien2=0;
                                        $result2=get_full_muc_tien("tien_hoc_".unicode_convert($data["name"]));
                                        if(mysqli_num_rows($result2)!=0) {
                                            $data2=mysqli_fetch_assoc($result2);
                                            $tienID=$data2["ID_TIEN"];
                                            $tien=$data2["tien"];
                                        }
                                        if($dem%2!=0) {
                                            echo"<tr style='background:#D1DBBD'>";
                                        } else {
                                            echo"<tr>";
                                        }
                                        echo"<td class='hidden'><span>$data[ID_MON]</span></td>
                                            <td><input type='text' class='input edit_name' value='$data[name]' /></td>
                                            <td class='hidden'><input type='number' min='1' step='0.5' class='input edit_thang' value='$data[thang]' /></td>
                                            <td><input type='text' class='input edit_tien' data-tienID='$tienID' value='$tien' /></td>
                                            <td class='hidden' style='text-align: center;'>
                                                <div class='clear'>
                                                    <span style='width:40%;'>Phạt?</span>";
                                            if($data["is_phat"] == 1) {
                                                echo "<input type='checkbox' class='check is_phat' style='width:15%;' checked='checked' />
                                                </div>";
                                            } else {
                                                echo "<input type='checkbox' class='check is_phat' style='width:15%;' />
                                                </div>";
                                            }
                                            echo"<div class='clear'>
                                                    <span style='margin-top: 10px;width:40%;'>Biểu đồ tính toán?</span>";
                                            if($data["is_tinh"] == 1) {
                                                echo "<input type='checkbox' class='check is_tinh' style='width:15%;margin-top: 10px;' checked='checked' />
                                                </div>";
                                            } else {
                                                echo "<input type='checkbox' class='check is_tinh' style='width:15%;margin-top: 10px;' />
                                                </div>";
                                            }
                                            echo"<div class='clear'>
                                                    <span style='margin-top: 10px;width:40%;'>Điểm TB nhảy đề (0 để tắt)</span>
                                                <input type='number' min='0' max='10' class='input nhayde' value='$data[is_nhayde]' style='text-align: left;width:15%;margin-top: 10px;' />
                                            </div>";
                                            echo"<div class='clear'>
                                                    <span style='margin-top: 10px;width:40%;'>Điểm từng câu (0 để tắt)</span>
                                                <input type='text' class='input auto' value='$data[is_auto]' style='text-align: left;width:15%;margin-top: 10px;' />
                                            </div>";
                                            echo"</td>
                                            <td><input type='submit' class='submit edit_ok' data-monID='$data[ID_MON]' value='Sửa' /></td>
                                        </tr>";
                                        $dem++;
                                    }
                                echo"</table>
                            <table class='table' style='margin-top: 25px;'>
                                <tr>
                                    <td class='hidden' colspan='3'><span></span></td>
                                    <td colspan='2'><input type='submit' class='submit' id='add_lop_mon' value='Thêm lớp môn mới' /></td>
                                </tr>
                                <tr style='background:#3E606F;'>
                                    <th class='hidden' style='width:10%;'><span>ID</span></th>
                                    <th style='width:15%;'><span>Môn</span></th>
                                    <th class='hidden'><span>Đi học : Nghỉ học</span></th>
                                    <th class='hidden'><span>Ngày vào học</span></th>
                                    <th><span></span></th>
                                </tr>";
                                $dem=0;
                                $result=get_all_lop_mon();
                                while($data=mysqli_fetch_assoc($result)) {
                                    if($dem%2!=0) {
                                        echo"<tr style='background:#D1DBBD'>";
                                    } else {
                                        echo"<tr>";
                                    }
                                    echo"<td class='hidden'><span>$data[ID_LM]</span></td>
                                            <td><input type='text' class='input edit_name' value='$data[name]' /></td>
                                            <td class='hidden'>".count_hs_mon_lop($data["ID_LM"])." / ".count_hs_nghi_mon($data["ID_LM"])."</td>
                                            <td class='hidden'><input type='text' class='input edit_date' value='$data[date_in]' /></td>
                                            <td><input type='submit' class='submit edit_ok2' data-lmID='$data[ID_LM]' value='Sửa' /></td>
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
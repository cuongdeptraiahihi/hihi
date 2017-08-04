<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["cumID"]) && is_numeric($_GET["cumID"]) && isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["mon"]) && is_numeric($_GET["mon"])) {
        $cum=$_GET["cumID"];
        $lmID=$_GET["lm"];
        $monID=$_GET["mon"];
    } else {
        $cum=0;
        $lmID=$_SESSION["lmID"];
        $monID=$_SESSION["mon"];
    }
    if($lmID!=0) {
        $mon_lop_name = get_lop_mon_name($lmID);
    } else {
        $mon_lop_name = get_mon_name($monID);
    }

    $ngay=get_last_cum_date($cum,$lmID,$monID);
    $ngay_full=get_cum_date_thu($cum,$lmID,$monID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>XEM NGHỈ HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

		<?php
			echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/bocuc.css'>";
		?>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:600px;}#chartContainer {width:100%;height:100%;}#chartPhu {position:absolute;z-index:9;right:0;width:400px;top:0;height:200px;overflow:hidden;border-radius:200px;}#chartContainer2 {width:100%;height:100%;}.khoang-ma,.only-ma {display:none;}#list-danhsach {background:#FFF;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script>
			$(document).ready(function() {
                $("table#list-nghihoc tr td textarea.note").typeWatch({
                    captureLength: 3,
                    callback: function (value) {
                        $(this).outerHeight($(this).height()).outerHeight(this.scrollHeight);
                        del_tr = $(this).closest("tr");
                        var ngay = $(this).attr("data-ngay");
                        hsID = $(this).attr("data-hsID");
                        note = value;
                        note = note.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                        if($.isNumeric(hsID) && hsID!=0) {
                            if(ngay == 0) {
                                $.ajax({
                                    async: true,
                                    data: "hsID1=" + hsID + "&note1=" + note,
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                                    success: function (result) {
                                        console.log("Đã lưu");
                                    }
                                });
                            } else {
                                $.ajax({
                                    async: true,
                                    data: "hsID1=" + hsID + "&ngay1=" + ngay + "&note2=" + note,
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
                                    success: function (result) {
                                        del_tr.find("td:last-child input").attr("data-nID",result);
                                        console.log("Đã lưu: " + result);
                                    }
                                });
                            }
                        } else {
                            alert("Lỗi dữ liệu!");
                        }
                    }
                });

                $("table#list-nghihoc tr td input.check-phone").click(function() {
                    var hsID = $(this).attr("data-hsID");
                    var sdt = $(this).attr("data-sdt");
                    if($.isNumeric(sdt) && sdt > 0 && $.isNumeric(hsID)) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "hsID6=" + hsID + "&sdt=" + sdt,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-thongtin/",
                            success: function (result) {
                                $("#popup-loading").fadeOut("fast");
                                $("#BODY").css("opacity","1");
                            }
                        });
                    }
                });

                $("table#list-nghihoc tr td input.note-old").click(function() {
                    var me = $(this);
                    var del_tr = $(this).closest("tr");
                    var hsID = $(this).attr("data-hsID");
                    if(hsID != 0 && $.isNumeric(hsID)) {
                        me.val("...").removeClass("note-old");
                        $.ajax({
                            async: true,
                            data: "hsID_main_note=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
                            success: function (result) {
                                me.removeClass("note-old").addClass("note-close").remove();
                                del_tr.find("td.td-note").append(result);
                                del_tr.find("td textarea.note").each(function (index,element) {
                                    $(element).outerHeight(this.scrollHeight);
                                });
                                $.ajax({
                                    async: true,
                                    data: "hsID_his=" + hsID + "&lmID_his=" + <?php echo $lmID; ?> + "&monID_his=" + <?php echo $monID; ?>,
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                                    success: function (result2) {
                                        del_tr.find("td.td-note span.history-nghi").html(result2);
                                    }
                                });
                            }
                        });
                    }
                });

                $("table#list-nghihoc tr td textarea.note").each(function (index,element) {
                    $(element).outerHeight(this.scrollHeight);
                });

                $("input.check-da-nhan").change(function () {
                    var sttID = $(this).attr("data-sttID");
                    if($(this).is(":checked")) {
                        nhan = 1;
                    } else {
                        nhan = 0;
                    }
                    if($.isNumeric(sttID) && sttID!=0 && (nhan==0 || nhan==1)) {
                        $.ajax({
                            async: true,
                            data: "sttID1=" + sttID + "&nhan=" + nhan,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                            success: function (result) {
                                console.log(result);
                            }
                        });
                    }
                });

                $("input.check-xac-nhan").change(function () {
                    var sttID = $(this).attr("data-sttID");
                    if($(this).is(":checked")) {
                        xacnhan = 1;
                    } else {
                        xacnhan = 0;
                    }
                    if($.isNumeric(sttID) && sttID!=0 && (xacnhan==0 || xacnhan==1)) {
                        $.ajax({
                            async: true,
                            data: "sttID1=" + sttID + "&xacnhan=" + xacnhan,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                            success: function (result) {
                                console.log(result);
                            }
                        });
                    }
                });

                $("input.check-phep").change(function () {
                    hsID = $(this).attr("data-hsID");
                    cumID = <?php echo $cum; ?>;
                    if($(this).is(":checked")) {
                        is_phep = 1;
                    } else {
                        is_phep = 0;
                    }
                    if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && (is_phep==0 || is_phep==1)) {
                        $.ajax({
                            async: false,
                            data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?> + "&is_bao=1",
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
                            success: function(result) {
                                console.log(result);
                            }
                        });
                    }
                });

                var mydem = 0;
                $("table#list-nghihoc tr td:last-child").each(function(index,element) {
                    if($(element).hasClass("noneed")) {
                        $(this).closest("tr").remove();
                    } else {
                        $(element).closest("tr").find("th:first-child span").html(mydem);
                        mydem++;
                    }
                });

                $("input.nghi-dai").click(function () {
                    if(confirm("Học sinh này sẽ được điểm danh có phép các buổi thuộc đoạn nghỉ dài, bạn có chắc chắn?")) {
                        var me = $(this);
                        var hsID = $(this).attr("data-hsID");
                        var ngay = $(this).attr("data-ngay");
                        if ($.isNumeric(hsID) && hsID != 0 && ngay != "") {
                            $.ajax({
                                async: false,
                                data: "hsID0=" + hsID + "&date_start=" + ngay + "&date_end=00/00/0000&note=&lmID0=" + <?php echo $lmID; ?>,
                                type: "post",
                                url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                                success: function (result) {
                                    console.log(result);
                                    me.removeClass("nghi-dai").addClass("huy-nghi-dai").css("background", "#96c8f3").attr("data-ngay", "").val("Đang nghỉ dài");
                                }
                            });
                        }
                    }
                });

                $("input.huy-nghi-dai").click(function () {
                    var me = $(this);
                    var hsID = $(this).attr("data-hsID");
                    if($.isNumeric(hsID) && hsID!=0) {
                        $.ajax({
                            async: false,
                            data: "hsID2=" + hsID + "&lmID2=" + <?php echo $lmID; ?>,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                            success: function(result) {
                                console.log(result);
                                me.removeClass("huy-nghi-dai").addClass("nghi-dai").css("background","#EF5350").attr("data-ngay","<?php echo $ngay ?>").val("Nghỉ dài");
                            }
                        });
                    }
                });

                $("table#list-nghihoc tr").each(function (index,element) {
                    if($(element).find("td:last-child input.check-xac-nhan").is(":checked")) {
                        $(element).find("th:eq(1)").css("background","yellow");
                        $(element).find("th:eq(1) span, th:eq(1) span a").css("color","#3E606F");
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
                $error=false;
                $file=NULL;
                $html = "";
                if(isset($_POST["xem"])) {
                    if($_FILES["submit-file"]["error"]>0) {
                        $error=true;
                    } else {
                        $file = $_FILES["submit-file"]["name"];
                        move_uploaded_file($_FILES["submit-file"]["tmp_name"], "../import/" . $_FILES["submit-file"]["name"]);
                        include("include/PHPExcel/IOFactory.php");
                        $html = "<table class='table' style='margin-top:25px;'>";
                        $objPHPExcel = PHPExcel_IOFactory::load("../import/" . $file);
                        $stt = $dem = 0;
                        $html .= '<tr style=\'background:#3E606F;\'>';
                        $html .= '<th style=\'width:5%;\'><span>STT</span></th>';
                        $html .= '<th style=\'width:10%;\'><span>Vân tay</span></th>';
                        $html .= '<th style=\'width:15%;\'><span>Mã số</span></th>';
                        $html .= '<th style=\'width:20%;\'><span>Họ tên</span></th>';
                        $html .= '<th><span>Kết quả</span></th>';
                        $html .= '<th style=\'width:15%;\'><span>Điểm danh nhanh</span></th>';
                        $html .= '</tr>';
                        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                            $highestRow = $worksheet->getHighestRow();
                            $temp_van=array();
                            for ($row = 1; $row <= $highestRow; $row++) {
                                $vantay = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                                if(!isset($temp_van[$vantay]) && $vantay!=0) {
                                    $temp_van[$vantay] = 0;
                                    $result_arr = get_hs_by_van($vantay,$lmID,$monID);
                                    for ($i = 0; $i < count($result_arr); $i++) {
                                        $string = "";
                                        if (check_exited_diemdanh($cum, $result_arr[$i]["ID_HS"], $lmID, $monID)) {
                                            $string = "Đã được điểm danh";
                                            $html .= "<tr>";
                                            $dem++;
                                        } else {
                                            $html .= "<tr style='background:yellow;'>";
                                        }
                                        $html .= '<td><span>' . ($stt + 1) . '</span></td>';
                                        $html .= '<td><span>' . $vantay . '</span></td>';
                                        $html .= '<td><span>' . $result_arr[$i]["cmt"] . '</span></td>';
                                        $html .= '<td><span>' . $result_arr[$i]["fullname"] . '</span></td>';
                                        $html .= '<td><span>' . $string . '</span></td>';
                                        if($string=="") {
                                            $html .= '<td><span><input class="submit diem-danh" data-hsID="'.$result_arr[$i]["ID_HS"].'" type="button" value="Đi muộn, ko KT" /></span></td>';
                                        } else {
                                            $html .= '<td><span></span></td>';
                                        }
                                        $html .= "</tr>";
                                        $stt++;
                                    }
                                }
                            }
                        }

                        $result=get_all_diemdanh_cum($cum,$lmID,$monID);
                        while($data=mysqli_fetch_assoc($result)) {
                            if(!isset($temp_van[$data["vantay"]])) {
                                $html .= "<tr style='background:cyan;'>";
                                $html .= '<td><span>' . ($stt + 1) . '</span></td>';
                                $html .= '<td><span>' . $data["vantay"] . '</span></td>';
                                $html .= '<td><span>' . $data["cmt"] . '</span></td>';
                                $html .= '<td><span>' . $data["fullname"] . '</span></td>';
                                $html .= '<td><span>Quên vân tay</span></td>';
                                $html .= '<td><span></span></td>';
                                $html .= "</tr>";
                                $dem++;
                            }
                        }

                        $html .= "<tr>";
                        $html .= '<td><span>' . $stt . '</span></td>';
                        $html .= '<td colspan=\'3\'><span>Thống kê dữ liệu</span></td>';
                        $html .= '<td><span>Đã điểm danh: ' . $dem . ' / Chưa điểm danh: ' . ($stt-$dem) . '</span></td>';
                        $html .= '<td><span></span><td>';
                        $html .= "</tr>";
                        $html .= '</table>';
                    }
                }
                ?>
                
                <div id="main-mid">
                    <h2><span style="font-weight:600;">DANH SÁCH NGHỈ HỌC môn <?php echo $mon_lop_name; ?></span><br /><?php echo $ngay_full; ?><br /><strong>Xem trước</strong></h2>
                	<div>
                    	<div class="status">
                            <div class="main-bot" style="display:block;">
                                <div class="clear" id="main-wapper2" style="width:100%;overflow:auto;">
                                <div></div>
                                <?php
                                    if($html != "") {
                                        echo $html;
                                    } else {
                                ?>
                                <table class="table" id="list-nghihoc">
                                    <tr>
                                        <form action="http://localhost/www/TDUONG/admin/xem-nghi-hoc/<?php echo $cum; ?>/<?php echo $lmID; ?>/<?php echo $monID; ?>/" method="post" enctype="multipart/form-data">
                                            <td class="hidden"></td>
                                            <td class="hidden" colspan="2"><span>So sánh vân tay với điểm danh</span></td>
                                            <td class="hidden"><input type="file" class="submit" name="submit-file" /></td>
                                            <td class="hidden"><input class="submit" name="xem" type="submit" value="So sánh" /></td>
                                        </form>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style='background: #3E606F;width:5%;' class="hidden" style="width:10%;"><span>STT</span></th>
                                        <th style="width:15%;"><span>Học sinh</span></th>
                                        <th style="width:15%;"><span>Thông tin</span></th>
                                        <th class="hidden"><span>Ghi chú</span></th>
                                        <th class="hidden" style="width:15%;"><span>Trạng thái</span></th>
									</tr>
                                    <?php
                                        $stt=0;
										if($lmID!=0) {
                                            $query2="SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt_bo,h.sdt_me,o.note,m.date_in,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN hocsinh_note AS o ON o.ID_HS=h.ID_HS AND o.ngay='$ngay' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC";
										} else {
                                            $query2="SELECT h.ID_HS,h.cmt,h.fullname,h.facebook,h.sdt_bo,h.sdt_me,o.note,m.date_in,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM AND l.ID_MON='$monID' LEFT JOIN hocsinh_note AS o ON o.ID_HS=h.ID_HS AND o.ngay='$ngay' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS ORDER BY h.cmt ASC";
										}
										$result2=mysqli_query($db,$query2);
										while($data2=mysqli_fetch_assoc($result2)) {
											$date_in=date_create($data2["date_in"]);
											if(isset($data2["ID_N"])) {
												continue;
											} else {
												if($stt%2!=0) {
													echo"<tr style='background:#D1DBBD'>";
												} else {
													echo"<tr>";
												}
											}
												echo"<th style='background: #3E606F;' class='hidden'><span>".($stt+1)."</span></th>
											    <th style='background: #EF5350;'><span><a href='".formatFacebook($data2["facebook"])."' target='_blank'>$data2[fullname]</a><br /><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data2[cmt]/' target='_blank'>($data2[cmt])</a><br />".get_hs_lich_hoc($data2["ID_HS"],$lmID,$monID)."</span></th>
												<td style='text-align:left;padding-left:15px;'><span style='line-height:26px;'>
                                                    <input type='checkbox' class='check check-phone' data-hsID='$data2[ID_HS]' data-sdt='$data2[sdt_bo]' ";if(is_numeric($data2["sdt_bo"]) && $data2["sdt_bo"]!=""){if(is_check_phone($data2["ID_HS"],$data2["sdt_bo"])==1){echo"checked='checked'";}}echo" style='margin-right: 5px;' />Bố: ".format_mobile_click($data2["sdt_bo"])."
                                                    <br />
                                                    <input type='checkbox' class='check check-phone' data-hsID='$data2[ID_HS]' data-sdt='$data2[sdt_me]' ";if(is_numeric($data2["sdt_me"]) && $data2["sdt_me"]!=""){if(is_check_phone($data2["ID_HS"],$data2["sdt_me"])==1){echo"checked='checked'";}}echo" style='margin-right: 5px;' />Mẹ: ".format_mobile_click($data2["sdt_me"])."
                                                </span></td>
                                                <td class='hidden td-note'>
                                                    <span class='history-nghi' style='float:left;margin-left:15px;margin-bottom:5px;'></span>
                                                    <input type='submit' class='submit note-old' style='float:right;margin-right:15px;margin-bottom:5px;' data-hsID='$data2[ID_HS]' value='Note + Điểm danh' />";
                                                    if(check_nghi_dai($ngay,$data2["ID_HS"],$lmID)) {
                                                        echo"<input type='submit' class='submit huy-nghi-dai' style='background:#96c8f3;float:right;margin-right:5px;margin-bottom:5px;' data-hsID='$data2[ID_HS]' value='Đang nghỉ dài' />";
                                                    } else {
                                                        echo"<input type='submit' class='submit nghi-dai' data-ngay='".format_dateup($ngay)."' style='float:right;margin-right:5px;margin-bottom:5px;' data-hsID='$data2[ID_HS]' value='Nghỉ dài' />";
                                                    }
											        echo"<br /><textarea class='input note' placeholder='Tự động lưu' rows='1' data-ngay='$ngay' data-hsID='$data2[ID_HS]' style='clear:both;resize:none;box-sizing: border-box;overflow: hidden;'>".str_replace("<br />","\n",$data2["note"])."</textarea>
                                                </td>";
                                                $date=date_create($ngay);
                                                if($date<$date_in) {
                                                    echo"<td class='noneed hidden'><span>C</span></td>";
                                                } else {
                                                    $result4=check_di_hoc($data2["ID_HS"],$cum,$lmID,$monID);
                                                    if($result4!=false) {
                                                        echo "<td class='noneed hidden'><span class='fa fa-check'></span></td>";
                                                    } else {
                                                        $result3=get_diemdanh_nghi_buoi($cum,$data2["ID_HS"],$lmID,$monID);
                                                        $data3=mysqli_fetch_assoc($result3);
                                                        echo"<td style='text-align:left;padding-left:15px;' class='hidden'>
                                                            <span style='line-height:26px;'><input type='checkbox' class='check check-da-nhan' data-sttID='$data3[ID_STT]' ";if($data3["nhan"]==1) {echo"checked='checked'";}echo" style='margin-right: 5px;' />Đã nhắn tin?
                                                            <br />
                                                            <input type='checkbox' class='check check-xac-nhan' data-sttID='$data3[ID_STT]' ";if($data3["confirm"]==1) {echo"checked='checked'";}echo" style='margin-right: 5px;' />Đã xác nhận?
                                                            <br />
                                                            <input type='checkbox' class='check check-phep' data-hsID='$data2[ID_HS]' ";if($data3["is_phep"]==1) {echo"checked='checked'";}echo" style='margin-right: 5px;' /><strong>Có phép?</strong>
                                                            </span>
                                                        </td>";
                                                    }
                                                }
											echo"</tr>";
											$stt++;
										}
									?>
                                    <tr></tr>
                                </table>
                                <?php } ?>
                                </div>
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
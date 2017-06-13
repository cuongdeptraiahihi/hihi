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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>NGHỈ HỌC DÀI HẠN</title>
        
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
			#MAIN > #main-mid {width:100%;}.fa {font-size:1.375em !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			
			$("input#them").click(function() {
				$("#date-start, #date-end").val("");
				$("#popup-add").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
			});
			
			$("input.date").datepicker({
				dateFormat: "dd/mm/yy"
			});
			
			$(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast");
				$("#BODY").css("opacity","1");
			});
			
			$("#popup-ok").click(function() {
				hsID = <?php echo $hsID; ?>;
                note = $("#note-add").val();
                lmID = $("#mon-add").val();
                if($("#nghi-thang").is(":checked")) {
                    date_thang = $("#date-thang").val();
                    data_ajax = "hsID0=" + hsID + "&date_thang=" + date_thang + "&note=" + note + "&lmID0=" + lmID;
                } else {
                    date_start = $("#date-start").val();
                    date_end = $("#date-end").val();
                    if(date_end=="") {
                        date_end = "00/00/0000";
                    }
                    data_ajax = "hsID0=" + hsID + "&date_start=" + date_start + "&date_end=" + date_end + "&note=" + note + "&lmID0=" + lmID;
                }
				if($.isNumeric(hsID) && hsID!=0 && data_ajax!="") {
				    $("#popup-ok").hide();
					$("#popup-add").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					$.ajax({
						async: true,
						data: data_ajax,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
						success: function(result) {
							if(result!="ok") {
								alert(result);
                                $("#popup-ok").show();
							} else {
								location.reload();
							}
							$("#popup-add").fadeOut("fast");
							$("#BODY").css("opacity","1");
							
						}
					});
				} else {
					alert("Vui lòng nhập đầy đủ thông tin!");
				}
			});
			
			$("#MAIN #main-mid .status .table tr td input.edit").click(function() {
				del_tr = $(this).closest("tr");
				del_tr.css("opacity","0.3");
				sttID = $(this).attr("data-sttID");
				date_start = del_tr.find("td input.start").val();
				date_end = del_tr.find("td input.end").val();
                if(date_end=="") {
                    date_end = "00/00/0000";
                }
				note = del_tr.find("td input.note").val();
				if($.isNumeric(sttID) && sttID!=0 && date_start!="" && date_end!="") {
					$.ajax({
						async: true,
						data: "sttID0=" + sttID + "&date_start=" + date_start + "&date_end=" + date_end + "&note=" + note,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
						success: function(result) {
							del_tr.css("opacity","1");
						}
					});
				}
			});
			
			$("#MAIN #main-mid .status .table tr td input.del").click(function() {
				del_tr = $(this).closest("tr");
				sttID = $(this).attr("data-sttID");
				if(confirm("Bạn có chắc chắn?")) {
					if($.isNumeric(sttID) && sttID!=0) {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "sttID=" + sttID,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
							success: function(result) {
								del_tr.fadeOut("fast");
								$("#popup-loading").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					}
				}
			});

            $("#nghi-thang").change(function() {
                if($(this).is(":checked")) {
                    $("#date-thang").show();
                    $("#date-start, #date-end").hide();
                } else {
                    $("#date-start, #date-end").show();
                    $("#date-thang").hide();
                }
            })
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
        
        <div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm đợt nghỉ</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            	<input id="date-start" class="input date" autocomplete="off" placeholder="Ngày bắt đầu" />
                <input id="date-end" class="input date" style="margin-top:10px;" autocomplete="off" placeholder="Ngày kết thúc (Có thể trống)" />
                <input id="date-thang" class="input date2" autocomplete="off" placeholder="Chọn tháng: 8/2016, 12/2015,.." style="display: none;" />
                <input id="nghi-thang" type="checkbox" style="margin-top:10px;margin-right: 10px;" class="check" /><span style="font-size: 14px;">Nghỉ cả tháng</span>
                <input id="note-add" class="input" style="margin-top:10px;" autocomplete="off" placeholder="Lý do" value="" />
                <select id="mon-add" class="input" style="height: auto;width: 100%;margin-top: 10px;">
                    <?php
                    $result=get_all_mon_hocsinh($hsID);
                    for($i=0;$i<count($result);$i++) {
                        echo"<option value='".$result[$i]["lmID"]."'>".$result[$i]["name"]."</option>";
                    }
                    ?>
                </select>
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2><strong><?php echo $data0["fullname"]." - ".$data0["cmt"]; ?></strong><input type="submit" class="submit" style="float:right;" value='Thêm nghỉ dài' id="them" /></h2>
                    <h2>Nghỉ học dài hạn <strong>biết thời điểm đầu và cuối</strong></h2>
                	<div>
                    	<div class="status">	
                         	<table class="table">
                            	<tr style="background:#3E606F">
                                    <th style="width:20%;"><span>Bắt đầu</span></th>
                                    <th style="width:20%;"><span>Kết thúc</span></th>
                                    <th class="hidden" style="width:25%;"><span>Lý do</span></th>
                                    <th style="width:15%;"><span>Môn</span></th>
                                    <th style="width:15%;"><span>Trạng thái</span></th>
                                    <th><span></span></th>
                                </tr>
                            <?php
								$dem=0;
								$result=get_nghi_dai($hsID,0);
								while($data=mysqli_fetch_assoc($result)) {
									echo"<tr>
										<td><input type='text' class='input start date' value='".format_dateup($data["start"])."' style='text-align:center' /></td>
										<td><input type='text' class='input end date' value='".format_dateup($data["end"])."' style='text-align:center' /></td>
										<td class=\"hidden\"><input type='text' class='input note' value='$data[note]' placeholder='Ghi chú' /></td>
										<td><span>$data[name]</span></td>
										<td>".get_trang_thai_nghi($data["start"],$data["end"])."</td>
										<td>
											<input type='submit' class='submit edit' value='Sửa' data-sttID='$data[ID_STT]' />
											<input type='submit' class='submit del' value='Xóa' data-sttID='$data[ID_STT]' />
										</td>
									</tr>";
									$dem++;
								}
								if($dem==0) {
									echo"<tr><td colspan='6'><span>Không có dữ liệu</span></td></tr>";
								}
							?>
                            </table>
                      	</div>      
                    </div>
                    <h2>Nghỉ học dài hạn <strong>chỉ biết thời điểm đầu</strong></h2>
                    <div>
                        <div class="status">
                            <table class="table">
                                <tr style="background:#3E606F">
                                    <th><span>Bắt đầu</span></th>
                                    <th class="hidden" style="width:25%;"><span>Lý do</span></th>
                                    <th style="width:15%;"><span>Môn</span></th>
                                    <th style="width:15%;"><span>Trạng thái</span></th>
                                    <th><span></span></th>
                                </tr>
                                <?php
                                $dem=0;
                                $result=get_nghi_dai($hsID,1);
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<tr>
										<td><input type='text' class='input start date' value='".format_dateup($data["start"])."' style='text-align:center' /></td>
										<td class=\"hidden\"><input type='text' class='input note' value='$data[note]' placeholder='Ghi chú' /></td>
										<td><span>$data[name]</span></td>
										<td>".get_trang_thai_nghi($data["start"],$data["end"])."</td>
										<td>
											<input type='submit' class='submit edit' value='Sửa' data-sttID='$data[ID_STT]' />
											<input type='submit' class='submit del' value='Xóa' data-sttID='$data[ID_STT]' />
										</td>
									</tr>";
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
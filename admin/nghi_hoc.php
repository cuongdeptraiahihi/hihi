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
    $lmID=$_SESSION["lmID"];
	$monID=$_SESSION["mon"];
	$lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>HỌC SINH NGHỈ HỌC</title>
        
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
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			$("#add").click(function() {
				$("#input-add").val("");
				$("#popup-add").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
			});
			
			$(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast");
				$("#BODY").css("opacity","1");
			});
			
			$("#popup-ok").click(function() {
				maso = $("#input-add").val();
				date_nghi = $("#date-add").val();
				lmID = <?php echo $lmID;?>;
				if(maso!="" && $.isNumeric(lmID) && date_nghi!="") {
					$("#popup-add").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					$.ajax({
						async: true,
						data: "maso=" + maso + "&date=" + date_nghi + "&lmID=" + lmID,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
						success: function(result) {
							if(result!="ok") {
								alert(result);
							} else {
								location.reload();
							}
							$("#popup-add").fadeOut("fast");
							$("#BODY").css("opacity","1");
							
						}
					});
				} else {
					alert("Vui lòng nhập mã học sinh!");
				}
			});
			
			$("#MAIN #main-mid .status .table tr td input.delete").click(function() {
				del_tr = $(this).closest("tr");
				nID = $(this).attr("data-n");
				if(confirm("Bạn có chắc chắn xóa học sinh này khỏi danh sách nghỉ học?")) {
					if($.isNumeric(nID)) {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "nID=" + nID,
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

            $("#MAIN #main-mid .status .table tr td input.edit").click(function() {
                del_tr = $(this).closest("tr");
                me = $(this);
                hsID = $(this).attr("data-hsID");
                nID = $(this).attr("data-n");
                date_out = del_tr.find("td input.date_out").val();
                note = del_tr.find("td textarea.note").val();
                note = note.replace(/\+/g,"-");
                if($.isNumeric(hsID) && $.isNumeric(nID) && date_out != "" && note != "") {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "hsID=" + hsID + "&nID0=" + nID + "&date_out=" + date_out + "&note=" + note,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                        success: function(result) {
                            me.val("Xong").css("background","blue");
                            $("#popup-loading").fadeOut("fast");
                            $("#BODY").css("opacity","1");
                        }
                    });
                }
            });
			
			$("#date-add").datepicker({
				dateFormat: "yy-mm-dd"
			});

//            var mee = "<?php //echo date("d/m/Y"); ?>//: ";
//            $("table#list-nghihoc tr td textarea.note").click(function () {
//                if($(this).val() == "" || $(this).val() == " ") {
//                    $(this).val("- " + mee);
//                }
//            });
//
//            $("table#list-nghihoc tr td textarea.note").keyup(function (e) {
//                dem = $(this).attr("data-dem");
//                if(e.keyCode == 13) {
//                    var content = $.trim($("table#list-nghihoc tr td textarea.note-" + dem).val());
//                    $("table#list-nghihoc tr td textarea.note-" + dem).val(content + "\n- " + mee);
//                } else if(e.keyCode == 8 || e.keyCode == 46) {
//                    var content = $.trim($("table#list-nghihoc tr td textarea.note-" + dem).val());
//                    dodai = content.length;
//                    if(content.substring(dodai - mee.length - 2).trim() == ("- " + mee.substring(0,mee.length-1)).trim()) {
//                        $("table#list-nghihoc tr td textarea.note-" + dem).val(content.substring(0,dodai - mee.length -2) + " ");
//                    }
//                } else {
//
//                }
//            });

            $("table#list-nghihoc tr td textarea.note").each(function (index,element) {
                $(element).outerHeight(this.scrollHeight);
            });

            $("table#list-nghihoc tr td textarea.note").typeWatch({
                captureLength: 3,
                callback: function (value) {
                    $(this).outerHeight($(this).height()).outerHeight(this.scrollHeight);
                    del_tr = $(this).closest("tr");
                    hsID = $(this).attr("data-hsID");
                    nID = $(this).attr("data-n");
                    date_out = del_tr.find("td input.date_out").val();
                    note = value;
                    note = note.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                    if($.isNumeric(hsID) && $.isNumeric(nID) && date_out != "" && note != "") {
                        $.ajax({
                            async: true,
                            data: "hsID=" + hsID + "&nID0=" + nID + "&date_out=" + date_out + "&note=" + note,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-nghihoc/",
                            success: function(result) {
                                console.log("Đã lưu");
                            }
                        });
                    }
                }
            });
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
        
        <div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm học sinh</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            	<input id="input-add" class="input" autocomplete="off" placeholder="Mã học sinh" />
                <input id="date-add" class="input" style="margin-top:10px;" autocomplete="off" placeholder="2016-05-06" />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>QUẢN LÝ NGHỈ HỌC <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <table class="table" id="list-nghihoc">
                            	<tr>
                                    <td colspan="5"><input class="submit" type="submit" id="add" value="Thêm nghỉ" /></td>
                                </tr>
                            	<tr style="background:#3E606F;">
                                    <th class='hidden' style="width:5%;"><span>STT</span></th>
                                    <th style="width:20%;"><span>Học sinh</span></th>
                                    <th class='hidden' style="width:15%;"><span>Bắt đầu nghỉ</span></th>
                                    <th><span>Ghi chú</span></th>
                                    <th style="width:10%;"><span></span></th>
                               	</tr>
                                <?php
									$dem=0;
									$result2=get_all_hs_nghi($lmID);
									while($data2=mysqli_fetch_assoc($result2)) {
										if($dem%2!=0) {
											echo"<tr style='background:#D1DBBD'>";
										} else {
											echo"<tr>";
										}
										echo"<th style='background: #3E606F;' class='hidden'><span>".($dem+1)."</span></th>
											<th style='background: #EF5350;'><span><a href='".formatFacebook($data2["facebook"])."' target='_blank'>$data2[fullname]</a><br /><a href='http://localhost/www/TDUONG/admin/hoc-sinh-chi-tiet/ma/$data2[cmt]/' target='_blank'>$data2[cmt]</a></span></td>
											<td class='hidden'><input type='text' class='input date_out' value='".format_dateup($data2["date"])."' style='text-align:center;' /></td>
											<td><textarea class='input note' data-n='$data2[ID_N]' data-hsID='$data2[ID_HS]' rows='1' placeholder='Tự động lưu' style='resize:none;box-sizing: border-box;overflow: hidden;'>".str_replace("<br />","\n",$data2["note"])."</textarea></td>
											<td>
												<input type='submit' class='submit delete' data-n='$data2[ID_N]' value='Học lại' />
											</td>
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
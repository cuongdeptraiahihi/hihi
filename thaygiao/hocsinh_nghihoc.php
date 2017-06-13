<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["lm2"]) && is_numeric($_GET["lm2"]) && isset($_GET["cumID"]) && is_numeric($_GET["cumID"]) && isset($_GET["loai"]) && is_numeric($_GET["loai"])) {
		$lmID=$_GET["lm"];
        $lmID2=$_GET["lm2"];
		$cumID=$_GET["cumID"];
        $loai=$_GET["loai"];
	} else {
		$lmID=0;
        $lmID2=0;
		$cumID=0;
        $loai=0;
	}
	if($lmID!=0) {
        $monID = get_mon_of_lop($lmID);
        $lop_mon_name = get_lop_mon_name($lmID);
    } else {
        $monID=$_SESSION["mon"];
        $lop_mon_name = get_mon_name($monID);
    }
    $ngay=get_last_cum_date($cumID,$lmID,$monID);
    $ngay_full=get_cum_date_thu($cumID,$lmID,$monID);
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

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}
            table tr td span a {text-decoration: underline;}
            #list-nghihoc tr th:hover div.show-info, #list-nghihoc tr td:hover div.show-info {display: block !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {

//            $("table#list-nghihoc tr").delegate("td input.phep","click",function() {
//                me = $(this);
//                hsID = $(this).attr("data-hsID");
//                cumID = <?php //echo $cumID; ?>//;
//                is_phep = 1;
//                if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && (is_phep==0 || is_phep==1)) {
//                    me.css("opacity","0.3");
//                    $.ajax({
//                        async: false,
//                        data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + <?php //echo $lmID; ?>// + "&monID=" + <?php //echo $monID; ?>// + "&is_bao=1",
//                        type: "post",
//                        url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
//                        success: function(result) {
//                            if(result=="ok") {
//                                me.removeClass("phep").addClass("ko-phep").val("Phép").css("background","blue");
//                            } else {
//                                alert("Lỗi dữ liệu!");
//                            }
//                            me.css("opacity","1");
//                        }
//                    });
//                }
//            });

//            $("table#list-nghihoc tr").delegate("td input.ko-phep","click",function() {
//                me = $(this);
//                hsID = $(this).attr("data-hsID");
//                cumID = <?php //echo $cumID; ?>//;
//                is_phep = 0;
//                if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && (is_phep==0 || is_phep==1)) {
//                    me.css("opacity","0.3");
//                    $.ajax({
//                        async: false,
//                        data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + <?php //echo $lmID; ?>// + "&monID=" + <?php //echo $monID; ?>// + "&is_bao=1",
//                        type: "post",
//                        url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
//                        success: function(result) {
//                            if(result=="ok") {
//                                me.removeClass("ko-phep").addClass("phep").val("Ko Phép").css("background","red");
//                            } else {
//                                alert("Lỗi dữ liệu!");
//                            }
//                            me.css("opacity","1");
//                        }
//                    });
//                }
//            });

//            $("table#list-nghihoc tr").delegate("td input.del","click",function() {
//                me = $(this);
//                hsID = $(this).attr("data-hsID");
//                cumID = <?php //echo $cumID; ?>//;
//                if(confirm("Bạn có chắc chắn không?") && $.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0) {
//                    me.css("opacity","0.3");
//                    $.ajax({
//                        async: false,
//                        data: "cumID_out=" + cumID + "&hsID_out=" + hsID + "&lmID_out=" + <?php //echo $lmID; ?>// + "&monID_out=" + <?php //echo $monID; ?>//,
//                        type: "post",
//                        url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
//                        success: function(result) {
//                            me.closest("tr").remove();
//                        }
//                    });
//                }
//            });

//            var mee = "<?php //echo date("d/m/Y"); ?>//: ";
//            $("table#list-nghihoc tr td textarea.note").click(function () {
//                dem = $(this).attr("data-dem");
//                if($("table#list-nghihoc tr td textarea.note-" + dem).val() == "" || $("table#list-nghihoc tr td textarea.note-" + dem).val() == " ") {
//                    $("table#list-nghihoc tr td textarea.note-" + dem).val("- " + mee);
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
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
                                success: function (result) {
                                    console.log("Đã lưu");
                                }
                            });
                        } else {
                            $.ajax({
                                async: true,
                                data: "hsID1=" + hsID + "&ngay1=" + ngay + "&note2=" + note,
                                type: "post",
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
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
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
                                success: function (result2) {
                                    del_tr.find("td.td-note span.history-nghi").html(result2);
                                }
                            });
                        }
                    });
                }
            });

//            $("input.check-chuy").click(function() {
//                var me = $(this);
//                var nID = $(this).attr("data-nID");
//                if($(this).hasClass("is_chuy")) {
//                    is_hot = 0;
//                } else {
//                    is_hot = 1;
//                }
//                if(is_hot==1 || is_hot==0) {
//                    if($.isNumeric(nID) && nID!=0) {
//                        me.val("...");
//                        $.ajax({
//                            async: true,
//                            data: "is_hot=" + is_hot + "&nID_hot=" + nID,
//                            type: "post",
//                            url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
//                            success: function (result) {
//                                console.log(result);
//                                if(is_hot==1) {
//                                    me.addClass("is_chuy").removeAttr("style").css("background","red");
//                                } else {
//                                    me.removeClass("is_chuy").css({"background":"cyan","border":"none","opacity":"0.4"});
//                                }
//                                me.val("OK");
//                            }
//                        });
//                    } else {
//                        me.closest("tr").find("td textarea").attr("placeholder","Vui lòng nhập nội dung...");
//                    }
//                }
//            });

            $("table#list-nghihoc tr td textarea.note").each(function (index,element) {
                $(element).outerHeight(this.scrollHeight);
            });

//            setTimeout(function() {
//                $("span.history-nghi").each(function(index,element) {
//                    var hsID = $(element).attr("data-hsID");
//                    if(hsID != 0 && $.isNumeric(hsID)) {
//                        $.ajax({
//                            async: true,
//                            data: "hsID_his=" + hsID + "&lmID_his=" + <?php //echo $lmID; ?>// + "&monID_his=" + <?php //echo $monID; ?>//,
//                            type: "post",
//                            url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
//                            success: function (result) {
//                                $(element).html(result);
//                            }
//                        });
//                    }
//                });
//            },1000);

            $("input#open-action").click(function() {
                $("table#list-nghihoc tr td.hidden, table#list-nghihoc tr th.hidden").toggle();
            });

            $("#confirm").click(function () {
                if(confirm("Bạn có chắc chắn?")) {
                    return true;
                } else {
                    return false;
                }
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
                        success: function (result) {
                            console.log(result);
                        }
                    });
                }
            });

            $("input.check-phep").change(function () {
                hsID = $(this).attr("data-hsID");
                cumID = <?php echo $cumID; ?>;
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
                        success: function(result) {
                            console.log(result);
                        }
                    });
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
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
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
            <p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
        </div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>

            <?php
                $is_bao=false;
                if(isset($_POST["confirm"])) {
                    $is_bao=true;
                }
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2><span style="font-weight:600;">DANH SÁCH NGHỈ HỌC môn <?php echo $lop_mon_name; ?></span><br /><?php echo $ngay_full; ?></h2>
                	<div>
                    	<div class="status">
                            <table class="table" id="list-nghihoc">
                                <tr>
                                    <td style="border: none;" colspan="5">
                                        <form action="http://localhost/www/TDUONG/thaygiao/hoc-sinh-nghi-hoc/<?php echo $cumID; ?>/<?php echo $lmID; ?>/<?php echo $lmID2; ?>/<?php echo $loai; ?>/" method="post">
                                            <?php
                                                if(check_done_options($cumID,"thong-bao-nghi",$lmID,$monID)) {
                                                    echo"<span>Đã thông báo tới học sinh</span>";
                                                } else {
                                                    echo"<input class='submit' id=\"confirm\" name=\"confirm\" type='submit' value='Thông báo tới học sinh' />";
                                                }
                                            ?>
                                            <input class='submit' type='button' onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-nghi-hoc/<?php echo $cumID; ?>/<?php echo $lmID; ?>/<?php echo $lmID2; ?>/4/'" value='Lọc đã nhắn tin' />
                                            <input class='submit' type='button' onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-nghi-hoc/<?php echo $cumID; ?>/<?php echo $lmID; ?>/<?php echo $lmID2; ?>/5/'" value='Lọc đã xác nhận' />
                                        </form>
                                    </td>
                                </tr>
<!--                                --><?php
//                                    if($_SESSION["mobile"]==1) {
//                                        echo"<tr>
//                                            <td colspan='5'><input class='submit' id='open-action' type='submit' value='Mở thao tác' /></td>
//                                        </tr>";
//                                    }
//                                ?>
                            	<tr style="background:#3E606F;">
                                    <th style='background: #3E606F;width:5%;' class="hidden" style="width:10%;"><span>STT</span></th>
                                    <th style="width:15%;"><span>Học sinh</span></th>
                                    <th style="width:15%;"><span>Thông tin</span></th>
                                    <th class="hidden"><span>Ghi chú</span></th>
                                    <th class="hidden" style="width:15%;"><span>Trạng thái</span></th>
                               	</tr>
                                <?php
									$dem=0;
                                    if($loai==0 || $loai==1) {
                                        $loai=3;
                                    }
									$result2=get_all_hs_nghi_buoi($cumID,$ngay,$loai,$lmID,$lmID2);
									while($data2=mysqli_fetch_assoc($result2)) {
//									    add_hs_note_only($data2["ID_HS"],$ngay,"",0,$lmID);
										if($dem%2!=0) {
											echo"<tr style='background:#D1DBBD'>";
										} else {
											echo"<tr>";
										}
										echo"<th style='background: #3E606F;' class='hidden'><span>".($dem+1)."</span></th>
											<th style='background: #EF5350;position:relative;'>
											    <span><a href='".formatFacebook($data2["facebook"])."' target='_blank'>$data2[fullname]</a><br /><a href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/ma/$data2[cmt]/' target='_blank'>($data2[cmt])</a><br />".get_hs_lich_hoc($data2["ID_HS"],$lmID,$monID)."</span>";
                                                if($data2["sdtp"] != "" && $data2["anh"] != "") {
                                                    echo "<div class='show-info' style='display:none;position:absolute;z-index:9;top:0;left:0;background:#3E606F;padding:5px;'>
                                                        <p>$data2[sdtp]</p>
                                                        <img src='https://localhost/www/TDUONG/hocsinh/$data2[anh]' style='width:150px;' />
                                                    </div>";
                                                }
                                            echo"</th>
											<td style='text-align:left;padding-left:15px;'><span style='line-height:26px;'>
											<input type='checkbox' class='check check-phone' data-hsID='$data2[ID_HS]' data-sdt='$data2[sdt_bo]' ";if(is_numeric($data2["sdt_bo"]) && $data2["sdt_bo"]!=""){if(is_check_phone($data2["ID_HS"],$data2["sdt_bo"])==1){echo"checked='checked'";}}echo" style='margin-right: 5px;' />Bố: ".format_mobile_click($data2["sdt_bo"])."
											<br />
											<input type='checkbox' class='check check-phone' data-hsID='$data2[ID_HS]' data-sdt='$data2[sdt_me]' ";if(is_numeric($data2["sdt_me"]) && $data2["sdt_me"]!=""){if(is_check_phone($data2["ID_HS"],$data2["sdt_me"])==1){echo"checked='checked'";}}echo" style='margin-right: 5px;' />Mẹ: ".format_mobile_click($data2["sdt_me"])."
											</span></td>
											<td class='hidden td-note'>
											    <span class='history-nghi' style='float:left;margin-left:15px;margin-bottom:5px;'></span>
											    <input type='submit' class='submit note-old' style='float:right;margin-right:15px;margin-bottom:5px;' data-hsID='$data2[ID_HS]' value='Note + Điểm danh' />";
                                                if(check_nghi_dai($ngay,$data2["ID_HS"],$lmID2)) {
                                                    echo"<input type='submit' class='submit huy-nghi-dai' style='background:#96c8f3;float:right;margin-right:5px;margin-bottom:5px;' data-hsID='$data2[ID_HS]' value='Đang nghỉ dài' />";
                                                } else {
                                                    echo"<input type='submit' class='submit nghi-dai' data-ngay='".format_dateup($ngay)."' style='float:right;margin-right:5px;margin-bottom:5px;' data-hsID='$data2[ID_HS]' value='Nghỉ dài' />";
                                                }
											    echo"<br /><textarea class='input note' placeholder='Tự động lưu' rows='1' data-ngay='$ngay' data-hsID='$data2[ID_HS]' style='clear:both;resize:none;box-sizing: border-box;overflow: hidden;'>".str_replace("<br />","\n",$data2["note"])."</textarea>
                                            </td>
											<td style='text-align:left;padding-left:15px;' class='hidden'>
                                                <span style='line-height:26px;'><input type='checkbox' class='check check-da-nhan' data-sttID='$data2[stt]' ";if($data2["nhan"]==1) {echo"checked='checked'";}echo" style='margin-right: 5px;' />Đã nhắn tin?
                                                <br />
                                                <input type='checkbox' class='check check-xac-nhan' data-sttID='$data2[stt]' ";if($data2["confirm"]==1) {echo"checked='checked'";}echo" style='margin-right: 5px;' />Đã xác nhận?
                                                <br />
                                                <input type='checkbox' class='check check-phep' data-hsID='$data2[ID_HS]' ";if($data2["is_phep"]==1) {echo"checked='checked'";}echo" style='margin-right: 5px;' /><strong>Có phép?</strong>
                                                </span>";
                                                if($is_bao) {
                                                    if ($data2["is_phep"] == 1) {
                                                        delete_thongbao($data2["ID_HS"], $cumID, "nghi-hoc", $lmID);
                                                        add_thong_bao_hs($data2["ID_HS"], $cumID, "Bạn đã nghỉ học Có phép cụm học ngày $ngay_full", "nghi-hoc", $lmID);
                                                    } else {
                                                        delete_thongbao($data2["ID_HS"], $cumID, "nghi-hoc", $lmID);
                                                        add_thong_bao_hs($data2["ID_HS"], $cumID, "Bạn đã nghỉ học Ko phép cụm học ngày $ngay_full", "nghi-hoc", $lmID);
                                                    }
                                                }
											echo"</td>
										</tr>";
										$dem++;
									}
								?>
                                <tr></tr>
                            </table>
                       	</div>
                       
                    </div>
               	</div>
            
            </div>
        
        </div>
        
    </body>
</html>

<?php
    if($is_bao) {
        add_options($cumID,"thong-bao-nghi",$lmID,$monID);
        header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-nghi-hoc/$cumID/$lmID/$lmID2/$loai/");
        exit();
    }
	ob_end_flush();
	require_once("../model/close_db.php");
?>
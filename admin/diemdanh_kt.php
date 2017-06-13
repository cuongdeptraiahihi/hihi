<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $monID=$_SESSION["mon"];
    $lmID=0;
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	$birth=date("d/m");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>ĐIỂM DANH CA KIỂM TRA</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status #search-box {width:100%;}.check {width:20px;height:20px;margin-right:10px;}
			#MAIN > #main-mid > div .status .table-2 {width:49%;display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:14px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}input.active {background:blue;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			function a() {
				$("#search-hs").val("");
				$(".table tr.info-buoikt").remove();
				$("#hs-info, #ca-regis, #ca-result").html(""), $("#ca-result").css("background", "none"), $("#co-dung").prop("checked", !0)
			}
			
			function get_info_buoikt(hsID, buoiID) {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity", "0.3");
				$.ajax({
					async: true,
					data: "hsID6=" + hsID + "&buoiID6=" + buoiID + "&monID6=" + <?php echo $monID; ?>,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
					success: function(result) {
						//alert(result);
						$(".table tr.info-buoikt").remove();
						$(result).insertBefore($("#nhap").closest("tr"));
						$("#BODY").css("opacity", "1");
						$("#popup-loading").fadeOut("fast");
					}
				});
			}
		
			function t(a, c, e) {
				$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), $.ajax({
					async: true,
					data: "ngay=" + a + "&mon=" + <?php echo $monID; ?> + "&lmID=" + <?php echo $lmID; ?> + "&ca=" + c + "&cd=" + e,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
					success: function(a) {
						$("#table-history").html("<tr style='background:#3E606F;'><th style='width:10%;' class='hidden'><span>STT</span></th><th style='width:20%;' class='hidden'><span>Học sinh</span></th><th style='width:10%;'><span>Mã</span></th><th style='width:15%;'><span>Nháp / Giấy</span></th><th style='width:15%;'><span>Tình trạng</span></th><th style='width:15%;'><span>Ca đăng ký</span></th><th style='width:15%;'><span></span></th></tr>" + a);
						$("#status-dd").html($("#status-in").val());
						if($("#status-in").attr("data-du")==0) {
							$("#status-dd").css("background","red");
						} else {
							$("#status-dd").css("background","blue");
						}
						$("#BODY").css("opacity", "1");
						$("#popup-loading").fadeOut("fast");
					}
				})
			}
			//$("#select-mon").change(function() {
				$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), ngay = $("#select-buoi option:selected").attr("data-ngay"), "" != ngay && $.ajax({
					async: true,
                    data: "monID2=" + <?php echo $monID; ?> + "&lmID2=" + <?php echo $lmID; ?> + "&ngay=" + ngay,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-mon/",
					success: function(a) {
						$("#select-ca").html(a), $("#popup-loading").fadeOut("fast"), $("#BODY").css("opacity", "1")
					}
				})
			/*}),*/ $("#search-hs").click(function() {
				a(), (0 == $("#select-mon").val() || 0 == $("#select-ca").val()) && alert("Bạn hãy chọn đầy đủ thông tin bên trái!")
			}), $("#search-hs").keyup(function() {
					ma = $(this).val().trim();
                    if(ma.length==4) {
                        $("#nhap").attr("data-prelop",ma);
                    }
					if(ma.length>=1) {
                        $.ajax({
                            async: true,
                            data: "search_full=" + ma + "&monID=" + <?php echo $monID; ?>,
                            type: "get",
                            url: "http://localhost/www/TDUONG/admin/xuly-search-hs/",
                            success: function(a) {
                                $("#search-box > ul").html(a), $("#search-box").fadeIn("fast")
                            }
                        });
					} else {
						$("#search-box").fadeOut("fast");
					}
			}), $("#nhap").click(function() {
				$("#popup-loading").fadeIn("fast");
                $("#BODY").css("opacity", "0.3");
                ngay = $("#select-buoi option:selected").attr("data-ngay");
                buoiID = $("#select-buoi option:selected").val();
                caID = $("#select-ca").val();
                cdID = 0;
                hsID = $("#search-hs").attr("data-hsID");
                cum = 4;
                is_hoc = 0
                is_tinh = 0;
                is_kt = 0;
                made = $("#hs-made").val();
                caCheck = $("#ca-result").attr("data-result");
                if($("#hs-isde").is(":checked")) {
                    is_de=1;
                } else {
                    is_de=0;
                }
                if($("#is-du").is(":checked")) {
                    is_du=1;
                } else {
                    is_du=0;
                }
                code = "none";
                if($("#is-huy").is(":checked")) {
                    is_huy=1;
                    code = $("input#code-huy").val();
                } else {
                    is_huy=0;
                }
                if($(this).hasClass("nhap_new")) {
                    ajax_data = "ngay=" + ngay + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?> + "&caID=" + caID + "&cum0=" + cum + "&cdID=" + cdID + "&hsID=" + hsID + "&is_hoc=" + is_hoc + "&is_tinh=" + is_tinh + "&is_kt=" + is_kt + "&caCheck=" + caCheck + "&buoiID3=" + buoiID + "&nhap3=" + $("#hs-nhap").val() + "&giay3=" + $("#hs-giay").val() + "&is_de3=" + is_de + "&is_du3=" + is_du + "&is_huy3=" + is_huy + "&is_code3=" + code + "&made3=" + made;
                } else {
                    sttID = $(this).attr("data-sttID");
                    ajax_data = "ngay=" + ngay + "&hsID3=" + hsID + "&is_hoc3=" + is_hoc + "&is_tinh3=" + is_tinh + "&is_kt3=" + is_kt + "&sttID3=" + sttID + "&cum0=" + $("#select-ca option:selected").attr("data-cum") + "&buoiID3=" + buoiID + "&lmID=" + <?php echo $lmID; ?> +"&monID=" + <?php echo $monID; ?> +"&nhap3=" + $("#hs-nhap").val() + "&giay3=" + $("#hs-giay").val() + "&is_de3=" + is_de + "&is_du3=" + is_du + "&is_huy3=" + is_huy + "&is_code3=" + code + "&made3=" + made;
                }
                if($.isNumeric(caID) && $.isNumeric(cdID) && $.isNumeric(hsID) && (0==is_hoc || 1==is_hoc) && (0==is_tinh || 1==is_tinh) && (1==is_kt || 0==is_kt) && (0==caCheck || 1==caCheck) && 0 != caID && 0 != hsID && (is_huy==0 || (is_huy==1 && code!="none" && code!=""))) {
                    $.ajax({
                        async: true,
                        data: ajax_data,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
                        success: function (a) {
                            "ok" != a ? alert(a) : t(ngay, caID, cdID), $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast"), $("#nhap").addClass("submit-done")
                        }
                    });
                } else {
                    alert("Xin hãy nhập đầy đủ thông tin và chính xác!");
                    $("#BODY").css("opacity", "1");
                    $("#popup-loading").fadeOut("fast");
                }
			}), $("#search-box ul").delegate("li a", "click", function() {
				$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), hsID = $(this).attr("data-hsID"), ngay = $("#select-buoi option:selected").attr("data-ngay"), buoiID = $("#select-buoi option:selected").val(), caID = $("#select-ca option:selected").val(), cdID = 0, maso = $(this).attr("data-cmt"), $("#search-hs").val(maso), cum = $("#select-ca option:selected").attr("data-cum"), $("#search-hs").attr("data-hsID", hsID), $.isNumeric(hsID) && 0 != caID && 0 != hsID && $.isNumeric(caID) && $.isNumeric(cdID) ? $.ajax({
					async: true,
					data: "hsID0=" + hsID + "&ngay2=" + ngay + "&monID=" + <?php echo $monID; ?> + "&lmID=" + <?php echo $lmID; ?> + "&ca2=" + caID + "&cd2=" + cdID + "&cum=" + cum + "&is_quyen=0",
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
					success: function(a) {
						$("#search-box").fadeOut("fast"), "none" == a ? alert("Học sinh này chưa đăng ký học cụm này") : (obj = jQuery.parseJSON(a), -1 != obj.birth.indexOf("<?php echo $birth; ?>") ? $("#hs-info").html(obj.fullname + " ( " + obj.van + " ) " + " - " + obj.birth + " (" + obj.de + ") " + "<i class='fa fa-birthday-cake' style='margin-left:10px;font-size:1.375em;'></i>") : $("#hs-info").html(obj.fullname + " ( " + obj.van + " ) " + " - " + obj.birth + " (" + obj.de + ") "), $("#ca-regis").html(obj.ca_hientai), "new" == obj.action ? (obj.caID_send == caID ? $("#ca-result").html("Đúng ca").css("background", "#3E606F").attr("data-result", 1) : $("#ca-result").html("Sai ca").css("background", "red").attr("data-result", 0), $("#nhap").attr("class", "submit nhap_new"), $("#nhap").val("Nhập")) : (1 == obj.ca_check ? $("#ca-result").html("Đúng ca").css("background", "#3E606F").attr("data-result", 1) : $("#ca-result").html("Sai ca").css("background", "red").attr("data-result", 0), $("#nhap").attr("class", "submit nhap_edit"), $("#nhap").val("Sửa"), $("#nhap").attr("data-sttID", obj.caID_send))), get_info_buoikt(hsID,buoiID), $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast")
					}
				}) : (alert("Thiếu dữ liệu hoặc dữ liệu ko chính xác!"), $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast"))
			}), $("#select-mon, #select-lop, #select-ca").change(function() {
				ngay = $("#select-buoi option:selected").attr("data-ngay"), caID = $("#select-ca").val(), 0 == caID ? $("#select-ca").addClass("new-change") : $("#select-ca").removeClass("new-change"), $.isNumeric(caID) && 0 != caID && (a(), t(ngay, caID, 0))
			}), $("#table-history").delegate("tr td input.delete", "click", function() {
				del_tr = $(this).closest("tr"), sttID = $(this).attr("data-sttID"), confirm("Bạn có chắc chắn xóa học sinh này khỏi danh sách điểm danh không?") && $.ajax({
					async: true,
					data: "sttIDx=" + sttID,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
					success: function(a) {
						del_tr.fadeOut("fast")
					}
				})
			}), $("#MAIN #main-mid").click(function() {
				$("#wrong").fadeOut("fast"), $("#status").fadeOut("fast")
			}), $("#select-buoi").change(function() {
				new_date = $(this).find("option:selected").attr("data-ngay"), $.ajax({
					async: true,
                    data: "new_date=" + new_date + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?>,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
					success: function(a) {
						$("#select-ca").html("<select class='input' style='height:auto;width:100%;' id='select-ca'><option value='0'>Chọn ca hiện hành</option>" + a + "</select>"), $("#select-ca").addClass("new-change")
					}
				})
			}), $("#xuat-nghi").click(function() {
					var dem = 0;
					if($(this).hasClass("active")) {
						$("#table-history tr").each(function(index, element) {
							dem++;
							$(element).show();
						});
						$(this).removeClass("active");
					} else {
						$("#table-history tr").each(function(index, element) {
							if($(element).hasClass("tr-ok")) {
								$(element).hide();
							} else {
								dem++;
								$(element).show();
							}
						});
						$("input.active").removeClass("active");
						$(this).addClass("active");
					}
					dem = dem-4;
					$("#so-luong").html("Có "+dem+" học sinh");
						/*monID = $("#select-mon").val();
						lopID = $("#select-lop").val();
						ngay = $("#select-date").val();
						caID = $("#select-ca").val();
						cdID = 0;
						if($.isNumeric(monID) && $.isNumeric(lopID) && $.isNumeric(caID) && monID!=0 && lopID!=0 && caID!=0 && ngay!="") {
							$("#popup-loading").fadeIn("fast");
							$("#BODY").css("opacity", "0.3");
							$.ajax({
								async: true,
								data: "ngay4=" + ngay + "&lopID4=" + lopID + "&monID4=" + monID + "&caID4=" + caID + "&cdID4=" + cdID,
								type: "post",
								url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
								success: function(a) {
									$("#table-history").html("<tr style='background:#3E606F;'><th style='width:10%;' class='hidden'><span>STT</span></th><th style='width:20%;' class='hidden'><span>Học sinh</span></th><th style='width:10%;'><span>Mã</span></th><th style='width:15%;'><span>SĐT</span></th><th style='width:35%;'><span>Lý do</span></th><th style='width:10%;'><span></span></th></tr>" + a);
									$("#BODY").css("opacity", "1");
									$("#popup-loading").fadeOut("fast");
								}
							});
						} else {
							alert("Vui lòng nhập đủ thông tin phía trên!");
						}*/
				}), /*$("#table-history").delegate("tr:last-child td input.add_nghi","click",function() {
					if(confirm("Bạn có chắc chắn đã điểm danh xong?")) {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity", "0.3");
						me = $(this);
						ddID = $(this).attr("data-dd");
						ajax_data="[";
						$("#table-history tr").each(function(index, element) {
                            hsID = $(element).find("td input.input").attr("data-hsID");
							lydo = $(element).find("td input.input").val();
							if($.isNumeric(hsID) && hsID!=0 && lydo!="") {
								ajax_data+='{"hsID":"'+hsID+'","lydo":"'+lydo+'"},';
							}
                        });
						ajax_data+='{"ddID":"'+ddID+'"}';
						ajax_data+="]";
						if($.isNumeric(ddID) && ddID!=0 && ajax_data!="") {
							$.ajax({
								async: true,
								data: "data=" + ajax_data,
								type: "post",
								url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
								success: function(a) {
									if(a=="ok") {
										me.val("Xong").css("background","blue");
									} else {
										me.val("Lỗi").css("background","red");
									}
									$("#BODY").css("opacity", "1");
									$("#popup-loading").fadeOut("fast");
								}
							});
						} else {
							alert("Dữ liệu lỗi!");
							$("#BODY").css("opacity", "1");
							$("#popup-loading").fadeOut("fast");
						}
					}
				}),*/ $("#table-history").delegate("tr td input.input","keyup",function(e) {
					if(e.keyCode==40) {
						$(this).closest("tr").next("tr").find("td input.input").focus();
					}
					if(e.keyCode==38) {
						$(this).closest("tr").prev("tr").find("td input.input").focus();
					}
				}), $("#table-history").delegate("tr td:last-child input.add_only_nghi","click",function() {
					me = $(this);
					del_tr = $(this).closest("tr");
					del_tr.css("opacity", "0.3");
					cumID = $(this).attr("data-cumID");
					hsID = $(this).attr("data-hsID");
					is_phep = del_tr.find("td select.input option:selected").val();
					if($.isNumeric(cumID) && cumID!=0 && $.isNumeric(hsID) && hsID!=0 && (is_phep==1 || is_phep==0)) {
						//alert(ddID + "-" + hsID + "-" + lydo);
						$.ajax({
							async: true,
                            data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?> + "&is_bao=0",
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
							success: function(a) {
								if(a=="ok") {
									me.closest("td").find("input.del_only_nghi").remove();
									me.val("Sửa").css("background","blue");
									me.closest("td").prepend("<input type='submit' class='submit del_only_nghi' data-hsID='"+hsID+"' data-cumID='"+cumID+"' value='Xóa' />");
								} else {
									me.val("Lỗi").css("background","red");
								}
								del_tr.css("opacity", "1");
							}
						});
					} else {
						alert("Lỗi dữ liệu!");
						del_tr.css("opacity", "1");
					}
				}), $("#table-history").delegate("tr td:last-child input.del_only_nghi","click",function() {
					me = $(this);
					del_tr = $(this).closest("tr");
					del_tr.css("opacity", "0.3");
					cumID = $(this).attr("data-cumID");
					hsID = $(this).attr("data-hsID");
					if($.isNumeric(cumID) && cumID!=0 && $.isNumeric(hsID) && hsID!=0) {
						//alert(ddID + "-" + hsID + "-" + lydo);
						$.ajax({
							async: true,
                            data: "cumID0=" + cumID + "&hsID0=" + hsID + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
							success: function(a) {
								if(a=="ok") {
									me.closest("td").find("input.add_only_nghi").remove();
									me.val("Nhập").removeClass("del_only_nghi").addClass("add_only_nghi");
								} else {
									me.val("Lỗi").css("background","red");
								}
								del_tr.css("opacity", "1");
							}
						});
					} else {
						alert("Lỗi dữ liệu!");
						del_tr.css("opacity", "1");
					}
				}), $("#xuat-ca").click(function() {
					var dem = 0;
					if($(this).hasClass("active")) {
						$("#table-history tr").each(function(index, element) {
							dem++;
							$(element).show();
						});
						$(this).removeClass("active");
					} else {
						$("#table-history tr").each(function(index, element) {
							if($(element).hasClass("tr-dug")) {
								$(element).hide();
							} else {
								dem++;
								$(element).show();
							}
						});
						$("input.active").removeClass("active");
						$(this).addClass("active");
					}
					dem = dem-4;
					$("#so-luong").html("Có "+dem+" học sinh");
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
                
                <div id="main-mid">
                	<h2>ĐIỂM DANH CA KIỂM TRA</h2>
                	<div>
                    	<div class="status">
                        	<table class="table table-2">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin cố định</span></th></tr>
                                <tr>
                                	<td class='hidden' style="width:35%;"><span>Ngày điểm danh</span></td>
                                	<td style="width:65%;">
                                    	<select class="input" style="height:auto;width:100%;" id="select-buoi" name="buoiID">
                                        <?php
											$result0=get_all_buoikt($monID,10);
											while($data0=mysqli_fetch_assoc($result0)) {
												echo"<option value='$data0[ID_BUOI]' data-ngay='$data0[ngay]'>".format_dateup($data0["ngay"])."</option>";
											}
										?>
                                        </select>
                                  	</td>
                              	</tr>
                                <tr>
                                	<td class='hidden'><span>Môn</span></td>
                                    <td style="width:65%;">
                                        <select class="input" style="height:auto;width:100%;background:#FFF;" id="select-mon">
                                            <?php
                                            echo"<option value='$monID'>Môn ".get_mon_name($monID)."</option>";
                                            ?>
                                        </select>
                                    </td>
                              	</tr>
                                <tr>
                                	<td class='hidden'><span>Ca học</span></td>
                                    <td>
                                    	<select class="input" style="height:auto;width:100%;" id="select-ca">
                                            <option value="0">Chọn ca hiện hành</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                	<td class='hidden'><span>Trạng thái</span></td>
                                    <td><span id="status-dd" style="display:block;color:#FFF;padding:5px 0 5px 0;text-align:center;"></span></td>
                                </tr>
<!--                                <tr>-->
<!--                                	<td colspan="2"><input type="submit" class="submit" value="Lọc học sinh nghỉ" style="width:100%;" id="xuat-nghi" /></td>-->
<!--                                </tr>-->
                                <tr>
                                	<th colspan="2"><input type="submit" class="submit" value="Lọc sai ca" style="width:100%;" id="xuat-ca" /></th>
                                </tr>
                            </table>
                            <table class="table table-2">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin học sinh</span></th></tr>
                                <tr>
                                	<td class='hidden' style="width:35%;"><span>Mã số</span></td>
                                    <td style="width:65%;">
                                    	<input type="text" class="input" id="search-hs" placeholder="19991234, 19986236, .." autocomplete="off" />
                                    	<nav id="search-box" style="left:10px;top:auto;">
                                            <ul>
                                            </ul>
                                        </nav>
                                    </td>
                                </tr>
                                <tr>
                                	<td class='hidden'><span>Thông tin</span></td>
                                	<td><span id="hs-info" style="font-weight:600;font-size:14px;"></span></td>
                                </tr>
                                <tr>
                                	<td class='hidden'><span>Ca đăng ký</span></td>
                                    <td><p class="td-p" id="ca-regis"></p><p class="td-p" id="ca-result" data-result=""></p></td>
                                </tr>
                                <tr>
                                	<th colspan="2"><input class="submit nhap_new" type="submit" id="nhap" value="Nhập" style="width:100%;font-size:1.375em;" /></th>
                                </tr>
                            </table>
                            <table class="table" style="margin-top:25px;" id="table-history">
                            	<tr style="background:#3E606F;">
                                 	<th style="width:10%;" class="hidden"><span>STT</span></th>
                                 	<th style="width:25%;" class="hidden"><span>Học sinh</span></th>
                                 	<th style="width:10%;"><span>Mã</span></th>
                                    <th style="width:15%;"><span>Nháp / Giấy</span></th>
                                	<th style='width:15%;'><span>Tình trạng</span></th>
                                	<th style="width:15%;" class='hidden'><span>Ca đăng ký</span></th>
                                 	<th style="width:10%;"><span></span></th>
                            	</tr>
                                <tr><td colspan="7"><span>Vui lòng chọn đầy đủ thông tin cố định phía trên</span></td></tr>
                            </table>
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
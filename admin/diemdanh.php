<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
	$now=date("d/m/Y");
	$birth=date("/m/");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>ĐIỂM DANH CA HỌC</title>
        
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
			#MAIN > #main-mid > div .status .table-2 {width:49%;display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:14px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:8px 10px 8px 10px;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}input.active {background:blue;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
			$(document).ready(function() {
				
				function a() {
					pre_lop = $("#nhap").attr("data-prelop");
					if(pre_lop!="") {
                        $("#search-hs").val(pre_lop);
					} else {
						$("#search-hs").val("");
					}
					$("#hs-info, #hs-nghi, #ca-regis, #ca-result").html(""), $("#hs-info").closest("td").find("input").remove(), $("#ca-result").css("background", "none");
//                    $("#check_fixed").is(":checked") ? $("#is_kt").prop("checked", !0) : $("#co-dung").prop("checked", !0)
				}

                $("i.refresh-list").click(function() {
                    ngay = $("#select-date").val();
                    caID = $("#select-ca").val();
                    cdID = 0;
                    if(ngay!="" && $.isNumeric(caID) && caID!=0) {
                        t(ngay, caID, cdID);
                    }
                });
			
				function t(a, c, e) {
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), $.ajax({
						async: true,
						data: "ngay=" + a + "&mon=" + <?php echo $monID; ?> + "&lmID=" + <?php echo $lmID; ?> + "&ca=" + c + "&cd=" + e,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
						success: function(a) {
							$("#table-history").html("<tr style='background:#3E606F;'><th style='width:10%;' class='hidden'><span>STT</span></th><th style='width:20%;' class='hidden'><span>Học sinh</span></th><th style='width:10%;'><span>Mã</span></th><th style='width:15%;'><span>Học bài</span></th><th style='width:15%;'><span>Tính toán</span></th><th style='width:15%;'><span>Ca đăng ký</span></th><th style='width:15%;'><span></span></th></tr>" + a);
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
				//$("#select-lop").change(function() {
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), temp = $("#select-date").val().split("/"), ngay = temp[2] + "-" + temp[1] + "-" + temp[0], "" != ngay ? ($.ajax({
						async: true,
						data: "monID2=" + <?php echo $monID; ?> + "&lmID2=" + <?php echo $lmID; ?> + "&ngay=" + ngay,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-mon/",
						success: function(a) {
							$("#select-ca").html(a), $("#popup-loading").fadeOut("fast"), $("#BODY").css("opacity", "1")
						}
					})) : ($("#select-lop option:first").prop("selected", !0), alert("Bạn hãy chọn Môn và Ngày!"), $("#popup-loading").fadeOut("fast"), $("#BODY").css("opacity", "1"))
				/*}),*/ $("#search-hs").click(function() {
					a(), (0 == $("#select-mon").val() || 0 == $("#select-lop").val() || 0 == $("#select-ca").val()) && alert("Bạn hãy chọn đầy đủ thông tin bên trái!")
				}), $("#search-hs").keyup(function() {
						ma = $(this).val().trim();
                        if(ma.length==4) {
                            $("#nhap").attr("data-prelop",ma);
                        }
						if(ma.length>=1) {
                            $.ajax({
                                async: true,
                                data: "search_short=" + ma + "&lmID=" + <?php echo $lmID; ?>,
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
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), ngay = $("#select-date").val(), caID = $("#select-ca").val(), cdID = 0, hsID = $("#search-hs").attr("data-hsID"), cum = $("#select-ca option:selected").attr("data-cum"), is_kt = 1, $("#co-dung").is(":checked") && (is_hoc = 1, is_tinh = 1), $("#co-sai").is(":checked") && (is_hoc = 1, is_tinh = 0), $("#ko-sai").is(":checked") && (is_hoc = 0, is_tinh = 0), $("#is_kt").is(":checked") && (is_hoc = 0, is_tinh = 0, is_kt = 0), caCheck = $("#ca-result").attr("data-result"), $(this).hasClass("nhap_new") ? ajax_data = "ngay=" + ngay + "&monID=" + <?php echo $monID; ?> + "&lmID=" + <?php echo $lmID; ?> + "&caID=" + caID + "&cum0=" + cum + "&cdID=" + cdID + "&hsID=" + hsID + "&is_hoc=" + is_hoc + "&is_tinh=" + is_tinh + "&is_kt=" + is_kt + "&caCheck=" + caCheck : (sttID = $(this).attr("data-sttID"), ajax_data = "ngay=" + $("#select-date").val() + "&hsID3=" + hsID + "&is_hoc3=" + is_hoc + "&is_tinh3=" + is_tinh + "&is_kt3=" + is_kt + "&sttID3=" + sttID + "&cum0=" + $("#select-ca option:selected").attr("data-cum") + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?>), !($.isNumeric(caID) && $.isNumeric(cdID) && $.isNumeric(hsID)) || 0 != is_hoc && 1 != is_hoc || 0 != is_tinh && 1 != is_tinh || 0 != is_kt && 1 != is_kt || 0 != caCheck && 1 != caCheck || 0 == caID || 0 == hsID ? (alert("Xin hãy nhập đầy đủ thông tin và chính xác!"), $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast")) : $.ajax({
						async: true,
						data: ajax_data,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
						success: function(a) {
							"ok" != a ? alert(a) : $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast"), $("#nhap").addClass("submit-done")
						}
					})
				}), $("#search-box ul").delegate("li a", "click", function() {
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), $(".nhap").removeClass("submit-done"), hsID = $(this).attr("data-hsID"), ngay = $("#select-date").val(), caID = $("#select-ca option:selected").val(), cdID = 0, maso = $(this).attr("data-cmt"), $("#search-hs").val(maso), cum = $("#select-ca option:selected").attr("data-cum"), is_quyen = $("#select-ca option:selected").attr("data-quyen"), $("#search-hs").attr("data-hsID", hsID), $.isNumeric(hsID) && 0 != caID && 0 != hsID && $.isNumeric(caID) && $.isNumeric(cdID) && $.isNumeric(is_quyen) ? $.ajax({
						async: true,
						data: "hsID0=" + hsID + "&ngay2=" + ngay + "&monID=" + <?php echo $monID; ?> + "&lmID=" + <?php echo $lmID; ?> + "&ca2=" + caID + "&cd2=" + cdID + "&cum=" + cum + "&is_quyen=" + is_quyen,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
						success: function(a) {
							$("#search-box").fadeOut("fast");
							if(a=="none") {
								alert("Học sinh này chưa đăng ký học cụm này!");
							} else if(a=="quyen") {
								alert("Học sinh này không được phép đăng ký ca này!");
							} else {
								obj = jQuery.parseJSON(a);
								if(obj.birth.indexOf("<?php echo $birth; ?>")!=-1) {
									$("#hs-info").html(obj.fullname + " ( " + obj.van + " ) " + " - " + obj.birth + "<i class='fa fa-birthday-cake' style='margin-left:10px;font-size:1.375em;'></i>" + "<br /><br />SĐT: " + obj.sdt + "<br />SĐT Bố: " + obj.sdt_bo + "<br />SĐT Mẹ: " + obj.sdt_me);
								} else {
									$("#hs-info").html(obj.fullname + " ( " + obj.van + " ) " + " - " + obj.birth + "<br /><br />SĐT: " + obj.sdt + "<br />SĐT Bố: " + obj.sdt_bo + "<br />SĐT Mẹ: " + obj.sdt_me);
								}
								if(obj.lichsu_nghi==1) {
								    $("#hs-info").closest("td").append("<input class='check' type='checkbox' id='tang-sn' style='float:right;' />");
                                } else {
                                    $("#hs-info").closest("td").append("<input class='check' checked='checked' type='checkbox' id='tang-sn' style='float:right;' />");
                                }
//								$("#hs-nghi").html(obj.lichsu_nghi);
								$("#ca-regis").html(obj.ca_hientai);
                                if(obj.special_note!="none") {
                                    $("#REMIND").show();
                                    $("#REMIND ul").html("<li><p><strong>" + $("#search-hs").val() + "</strong> " + obj.special_note + "</p><a href='javascript:void(0)' style='float:right;' id='special-hide'>Ẩn</a><a href='javascript:void(0)' style='float:right;margin-right:20px;' id='special-close' data-hsID='"+hsID+"'>Hoàn thành</a><div class='clear'></div></li>");
                                } else {
                                    $("#REMIND").hide();
                                }
								if(obj.action=="new") {
									if(obj.caID_send==caID) {
										$("#ca-result").html("Đúng ca").css("background", "#3E606F").attr("data-result", 1);
									} else {
										$("#ca-result").html("Sai ca").css("background", "red").attr("data-result", 0);
									}
									$("#nhap").attr("class", "submit nhap_new");
									$("#nhap").val("Nhập").attr("data-sttID", 0);
								} else {
									if(obj.ca_check==1) {
										$("#ca-result").html("Đúng ca").css("background", "#3E606F").attr("data-result", 1);
									} else {
										$("#ca-result").html("Sai ca").css("background", "red").attr("data-result", 0);
									}
									$("#check_fixed").prop("checked", false);
									if(obj.is_kt==1) {
										if(obj.is_hoc==1 && obj.is_tinh==1) {
											$("#co-dung").prop("checked", true);
										} else if(obj.is_hoc==1 && obj.is_tinh==0) {
											$("#co-sai").prop("checked", true);
										} else {
											$("#ko-sai").prop("checked", true);
										}
									} else {
										$("#is_kt").prop("checked", true);
									}
									$("#nhap").attr("class", "submit nhap_edit");
									$("#nhap").val("Sửa").attr("data-sttID", obj.caID_send);
								}
								$.ajax({
									async: true,
									data: "hsID0=" + hsID,
									type: "post",
									url: "http://localhost/www/TDUONG/admin/xuly-taikhoan/",
									success: function(b) {
										$("#lich-su-phat").html(b);
									}
								});
							}
							$("#BODY").css("opacity", "1");
							$("#popup-loading").fadeOut("fast");
						}
					}) : (alert("Thiếu dữ liệu hoặc dữ liệu ko chính xác!"), $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast"))
				}), $("#select-mon, #select-lop, #select-ca").change(function() {
					ngay = $("#select-date").val(), caID = $("#select-ca").val(), 0 == caID ? $("#select-ca").addClass("new-change") : $("#select-ca").removeClass("new-change"), $.isNumeric(caID) && 0 != caID && (a(), t(ngay, caID, 0));
                    $("#kiem-tra").attr("onclick","location.href='http://localhost/www/TDUONG/admin/kiem-tra-diem-danh/" + ngay.replace(/\//g,"-") + "/" + caID + "/" + <?php echo $lmID; ?> + "/" + <?php echo $monID; ?> + "/'");
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
				}), $("#select-date").change(function() {
					temp = $(this).val().split("/"), new_date = temp[2] + "-" + temp[1] + "-" + temp[0], $.ajax({
						async: true,
						data: "new_date=" + new_date + "&lmID=" + <?php echo $lmID; ?> + "&monID=" + <?php echo $monID; ?>,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
						success: function(a) {
							$("#select-ca").html("<select class='input' style='height:auto;width:100%;' id='select-ca'><option value='0'>Chọn ca hiện hành</option>" + a + "</select>"), $("#select-ca").addClass("new-change")
						}
					})
				}), $("#select-date").datepicker({
					dateFormat: "dd/mm/yy"
				}), $("#xuat-nghi").click(function() {
                    var dem = 0;
                    if ($(this).hasClass("active")) {
                        $("#table-history tr").each(function (index, element) {
                            dem++;
                            $(element).show();
                        });
                        $(this).removeClass("active");
                    } else {
                        $("#table-history tr").each(function (index, element) {
                            if ($(element).hasClass("tr-ok")) {
                                $(element).hide();
                            } else {
                                dem++;
                                $(element).show();
                            }
                        });
                        $("input.active").removeClass("active");
                        $(this).addClass("active");
                    }
                    dem = dem - 4;
                    $("#so-luong").html("Có " + dem + " học sinh");
                }), $("#table-history").delegate("tr td input.input","keyup",function(e) {
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
				}), $("table#hoc-sinh-info").delegate("tr td input#tang-sn","click",function() {
				    if($(this).is(":checked")) {
				        tang=1;
                    } else {
                        tang=0;
                    }
                    hsID=$("#search-hs").attr("data-hsID");
                    if((tang==0 || tang==1) && hsID!=0) {
                        $.ajax({
                            async: true,
                            data: "hsID7=" + hsID + "&tang=" + tang,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
                            success: function(a) {
                                console.log(a);
                            }
                        });
                    }
                });
				/*$("#phat-nong").delegate("div input.add_phat","click",function() {
					del_tr = $(this).closest("td");
					if(del_tr.find("> div").length<5) {
						del_tr.append("<div style='margin-bottom:5px;'><input type='number' min='10' max='100' step='10' class='input tien-phat' value='10' placeholder='10' style='width:15%;float:left;' /><input type='text' style='float:left;width:40%;margin-left:10px;' class='input lydo-phat' placeholder='Lý do' /><input type='submit' class='submit kill_phat' style='float:left;margin-left:5px;' value='-' /></div>");
					}
				}), $("#phat-nong").delegate("div input.kill_phat","click",function() {
					del_tr = $(this).closest("div");
					del_tr.remove();
				}), $(".add_phat_nong").click(function() {
					if(confirm("Bạn có chắc chắn?")) {
						me = $(this);
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity", "0.3");
						td = $(this).closest("tr").find("td#phat-nong");
						hsID = $("#search-hs").attr("data-hsID");
						ajax_data="[";
						dem = 0;
						td.find("> div").each(function(index, element) {
							tien = $(element).find("input.tien-phat").val();
							lydo = $(element).find("input.lydo-phat").val();
							if($.isNumeric(tien) && tien<=100 && tien>=10 && lydo!="") {
								ajax_data+='{"tien":"'+tien+'","lydo":"'+lydo+'"},';
							} else {
								dem++;
							}
						});
						ajax_data+='{"hsID":"'+hsID+'"}';
						ajax_data+="]";
						if(ajax_data!="" && $.isNumeric(hsID) && hsID!=0 && dem==0) {
							//alert(ajax_data);
							$.ajax({
								async: true,
								data: "data=" + ajax_data,
								type: "post",
								url: "http://localhost/www/TDUONG/admin/xuly-taikhoan/",
								success: function(a) {
									if(a=="ok") {
										me.css("background","blue");
									} else {
										me.val("Lỗi").css("background","red");
									}
									$("#BODY").css("opacity", "1");
									$("#popup-loading").fadeOut("fast");
								}
							});
						} else {
							alert("Dữ liệu không chính xác: "+dem);
							$("#BODY").css("opacity", "1");
							$("#popup-loading").fadeOut("fast");
						}
					}
				}), $("#lich-su-phat").delegate("div input.done-phat","click",function() {
					if(confirm("Bạn có chắc chắn?")) {
						vaoID = $(this).attr("data-vaoID");
						div = $(this).closest("div");
						div.css("opacity","0.3");
						$.ajax({
							async: true,
							data: "idVAO0=" + vaoID,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-taikhoan/",
							success: function(a) {
								div.css("opacity","1");
							}
						});
					}
				}), $("#lich-su-phat").delegate("div input.delete-phat","click",function() {
					if(confirm("Bạn có chắc chắn xóa phạt nóng này?")) {
						vaoID = $(this).attr("data-vaoID");
						div = $(this).closest("div");
						div.css("opacity","0.3");
						$.ajax({
							async: true,
							data: "idVAO2=" + vaoID,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-taikhoan/",
							success: function(a) {
								div.fadeOut("fast");
							}
						});
					}
				});*/
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
                	<h2>ĐIỂM DANH CA HỌC</h2>
                	<div>
                    	<div class="status">
                        	<table class="table table-2">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin cố định</span></th></tr>
                                <tr>
                                	<td class='hidden' style="width:35%;"><span>Môn</span></td>
                                    <td style="width:65%;">
                                        <select class="input" style="height:auto;width:100%;background:#FFF;" id="select-mon">
                                        <?php
											echo"<option value='$lmID'>Môn ".get_lop_mon_name($lmID)."</option>";
                                        ?>
                                        </select>
                                    </td>
                              	</tr>
                                <tr>
                                	<td class='hidden'><span>Ngày điểm danh</span></td>
                                	<td><input class="input" id="select-date" type="text" value="<?php echo $now; ?>" /></td>
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
                                	<td class='hidden'><span>Trạng thái<i class="fa fa-refresh refresh-list" style="margin-left: 10px;font-size:18px;"></i></span></td>
                                    <td><span id="status-dd" style="display:block;color:#FFF;padding:5px 0 5px 0;text-align:center;"></span></td>
                                </tr>
<!--                                <tr>-->
<!--                                	<td colspan="2"><input type="submit" class="submit" value="Lọc học sinh nghỉ" style="width:100%;" id="xuat-nghi" /></td>-->
<!--                                </tr>-->
                                <tr>
                                    <th colspan="2"><input type="submit" class="submit" style="width: 45%;" value="Kiểm tra" id="kiem-tra" /><input type="submit" class="submit" style="width: 45%;" value="Lọc sai ca" id="xuat-ca" /></th>
                                </tr>
                                <!--<tr>
                                	<td><span>Chuyên đề</span></td>
                                    <td>
                                    	<select class="input" style="height:auto;width:100%;" id="select-cd">
                                        	<option value="0">Chọn chuyên đề</option>
                                        </select>
                                    </td>
                                </tr>-->
                            </table>
                            <table class="table table-2" id="hoc-sinh-info">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin học sinh</span></th></tr>
                                <tr>
                                	<td class='hidden' style="width:35%;"><span>Mã số</span></td>
                                    <td style="width:65%;">
                                    	<input type="text" class="input" id="search-hs" placeholder="19991234, 19982365, .." autocomplete="off" />
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
<!--                                <tr>-->
<!--                                    <td class='hidden'><span>Lịch sử nghỉ</span></td>-->
<!--                                    <td><span id="hs-nghi"></span></td>-->
<!--                                </tr>-->
                                <tr>
                                	<td class='hidden'><span>Kiểm tra đầu giờ</span></td>
                                    <td style="text-align:center;">
                                    	<div style="margin-bottom:20px;"><p>Học bài, tính đúng</p><input type="radio" name="check_hoc" class="check" id="co-dung" checked="checked" /></div>
                                        <div style="margin-bottom:20px;"><p>Học bài, tính sai</p><input type="radio" name="check_hoc" class="check" id="co-sai" /></div>
                                        <div style="margin-bottom:20px;"><p>Không học bài</p><input type="radio" name="check_hoc" class="check" id="ko-sai" /></div>
                                        <div><p>Ko kiểm tra / Đi muộn</p><input type="radio" name="check_hoc" class="check" id="is_kt" /><input type="checkbox" class="check" id="check_fixed" /></div>
                                    </td>
                                </tr>
                                <!--<tr>
                                	<td class="hidden"><span>Phạt</span><input type="submit" class="submit add_phat_nong" style="margin-left:40px;" value="Nhập phạt mới" /></td>
                                    <td style="text-align:center;" id="phat-nong">
                                    	<div style="margin-bottom:5px;"><input type="number" min="10" max="100" step="10" class="input tien-phat" value="10" placeholder="10" style="width:15%;float:left;" /><input type="text" style="float:left;width:40%;margin-left:10px;" class="input lydo-phat" placeholder="Lý do" /><input type="submit" class="submit add_phat" style="float:left;margin-left:5px;" value="+" /></div>
                                    </td>
                                </tr>
                                <tr>
                                	<td class="hidden"><span>Tiền phạt nóng còn NỢ</span></td>
                                    <td id="lich-su-phat">
                                    </td>
                                </tr>-->
                                <tr>
                                	<th colspan="2"><input class="submit nhap_new" data-prelop="" type="submit" id="nhap" value="Nhập" style="width:100%;font-size:1.375em;" /></th>
                                </tr>
                            </table>
                            <table class="table" style="margin-top:25px;" id="table-history">
                            	<tr style="background:#3E606F;">
                                 	<th style="width:10%;" class="hidden"><span>STT</span></th>
                                 	<th style="width:25%;" class="hidden"><span>Học sinh</span></th>
                                 	<th style="width:10%;"><span>Mã</span></th>
                                    <th style="width:15%;"><span>Học bài</span></th>
                                	<th style='width:15%;'><span>Tính toán</span></th>
                                	<th style="width:15%;" class='hidden'><span>Ca đăng ký</span></th>
                                 	<th style="width:10%;"><span></span></th>
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
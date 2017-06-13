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
        
        <title>NHẬP TIỀN HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status #search-box {width:100%;}.check {width:20px;height:20px;margin-right:10px;}
			#MAIN > #main-mid > div .status .table-2 {display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status .table-2 tr td span i {font-size:1.5em;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:22px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}.mon-lich {display:none;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			
			$("#info-hs").delegate("#add-cmt","click",function() {
                $("#add-cmt").val("");
			});
			
			$("#info-hs").delegate("#add-cmt","keyup",function() {
			//$("#add-cmt").keyup(function() {
				text = $(this).val();
				if(text!="" && text.length>=7) {
					$.ajax({
						async: true,
						data: "search=" + text,
						type: "get",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-search-hs/",
						success: function(result) {
							$("#search-box > ul").html(result);
							$("#search-box").fadeIn("fast");
						}
					});
				}
			});
			
			$("#add-cmt").click(function() {
				$(this).val("");
				$(this).attr("data-hsID",0);
				$("#info-mon").hide();
			});
			
			$("#info-hs").delegate("#search-box ul li a", "click", function() {
				cmt = $(this).attr("data-cmt");
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				$("#add-cmt").val(cmt);
				oID = $("#oid").val();
				ngay = $("#add-ngay").val();
				if(cmt!="" && cmt!=" " && cmt!="%" && cmt!="_" && cmt.length>=7 && ngay!="" && oID!="") {
					$.ajax({
						async: true,
						data: "cmt=" + cmt,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							var obj = jQuery.parseJSON(result);
							$("#show-name").html(obj.fullname);
							$("#show-birth").html(obj.birth);
							$("#info-thaotac").show();
							$("#info-action").html("");
							get_tien_hoc_form(cmt,ngay,oID);
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
						}
					});
				} else {
					alert("Chứng minh thư không hợp lệ!");
					$("#popup-loading").fadeOut("fast");
					$("#BODY").css("opacity","1");
				}
				$("#search-box").fadeOut("fast");
			});
			
			$("#info-lichsu").delegate("tr td.tien-total","click",function() {
				$(this).find("span.tien-show").toggle();
			});
			
			function get_list(code, ngay) {
				$.ajax({
					async: true,
					data: "code0=" + Base64.encode(code) + "&ngay=" + ngay,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
					success: function(result) {
						if(result!="none") {
							obj = jQuery.parseJSON(result);
							$("#info-lichsu").html("<tr style='background:#3E606F;'><th colspan='6'><span>Lịch sử nhập trong ngày</span></th><th><form action='http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/"+code+"/"+ngay+"/' method='post'><input type='submit' class='submit' value='Xuất Excel' /></form></th></tr><tr><td style='width:5%;'><span>STT</span></td><td style='width:15%;'><span>Học sinh</span></td><td style='width:10%;'><span>Mã số</span></td><td><span>Nội dung</span></td><td style='width:10%;'><span>Tiền</span></td><td style='width:10%;'><span>Loại</span></td><td style='width:15%;'><span>Ghi chú</span></td></tr>"+obj.content);
						} else {
							alert("Không tồn tại mã này!");
						}
						$("#popup-loading").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				});
			}
			
			$("#ok-ma").click(function() {
				ngay = $("#add-ngay").val();
				code = $("#add-ma").val();
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				if(code!="" && code!=" " && code!="%" && code!="_" && ngay!="") {
					$.ajax({
						async: true,
						data: "code0=" + Base64.encode(code) + "&ngay=" + ngay,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result!="none") {
								$("#info-thaotac").hide();
								$("#info-action").html("");
								obj = jQuery.parseJSON(result);
								$("#info-hs").html("<tr style='background:#3E606F;'><th colspan='2'><span>Thông tin học sinh</span></th></tr><tr><td style='width:35%;'><span>Mã học sinh</span></td><td style='width:65%;'><input class='input' id='add-cmt' autocomplete='off' name='add-cmt' type='text' placeholder='99-0002' /><nav id='search-box'><ul></ul></nav></td></tr><tr><td><span>Họ tên</span></td><td><span id='show-name'></span></td></tr><tr><td><span>Ngày sinh</span></td><td><span id='show-birth'></span></td></tr><tr><th colspan='2'><input type='hidden' value='"+obj.ID_O+"' id='oid' /></th></tr>");
								$("#show-trogiang").html(obj.name);
								$("#info-lichsu").html("<tr style='background:#3E606F;'><th colspan='6'><span>Lịch sử nhập trong ngày</span></th><th><form action='http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/"+code+"/"+ngay+"/' method='post'><input type='submit' class='submit' value='Xuất Excel' /></form></th></tr><tr><td style='width:10%;'><span>STT</span></td><td style='width:20%;'><span>Học sinh</span></td><td style='width:10%;'><span>Mã số</span></td><td><span>Nội dung</span></td><td style='width:10%;'><span>Tiền</span></td><td style='width:10%;'><span>Loại</span></td><td style='width:15%;'><span>Ghi chú</span></td></tr>"+obj.content);
							} else {
								alert("Không tồn tại mã này!");
							}
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
						}
					});
				} else {
					alert("Bạn vui lòng nhập đầy đủ thông tin và chính xác!");
					$("#popup-loading").fadeOut("fast");
					$("#BODY").css("opacity","1");
				}
			});
			
			$("#info-lichsu").delegate("tr:first-child th input.submit","click",function() {
				if(confirm("Xuất ra Excel sẽ mất khoảng thời gian!")) {
					return true;
				} else {
					return false;
				}
			});
			
			function get_tien_hoc_form(cmt,ngay,oID) {
				$(".btn").css("background","#3E606F");
				$("#nhap-tien").css("background","blue");
				$.ajax({
					async: true,
					data: "cmt1=" + cmt + "&ngay1=" + ngay + "&oID=" + oID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
					success: function(result) {
						if(result!="none") {
							$("#info-action").html(result);
						} else {
							alert("Không tồn tại học sinh này!");
						}
						$("#popup-loading").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				});
			}
			
			$("#nhap-tien").click(function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				cmt = $("#add-cmt").val();
				oID = $("#oid").val();
				ngay = $("#add-ngay").val();
				if(cmt!="" && cmt.length>=7 && ngay!="" && oID!="") {
					get_tien_hoc_form(cmt,ngay,oID);
				} else {
					alert("Xin vui lòng nhập đủ thông tin!");
					$("#popup-loading").fadeOut("fast");
					$("#BODY").css("opacity","1");
				}
			});
			
			$("#nap-tien").click(function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				cmt = $("#add-cmt").val();
				oID = $("#oid").val();
				ngay = $("#add-ngay").val();
				if(cmt!="" && cmt.length>=7 && ngay!="" && oID!="") {
					$(".btn").css("background","#3E606F");
					$(this).css("background","blue");
					$.ajax({
						async: true,
						data: "cmt2=" + cmt + "&ngay2=" + ngay + "&oID2=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result!="none") {
								$("#info-action").html("<tr style='background:#3E606F;'><th colspan='6'><span>Nạp tiền</span></th></tr><tr><td style='width:15%;'><span>Số tiền nạp</span></td><td style='width:25%;'><input type='number' class='input tien' min='0' placeholder='10000, 20000, ..' /></td><td style='width:15%;'><input type='text' class='input seri' placeholder='Seri hóa đơn' /></td><td style='width:15%;'><span>Ngày đóng</span></td><td><input type='text' class='input date_dong' placeholder='12/4/2016' value='<?php echo date("d/m/Y"); ?>' /></td><td style='width:15%;'><input type='submit' class='submit' id='ok-nap' value='Nạp tiền' /></td></tr>"+result);
							} else {
								alert("Không tồn tại học sinh này!");
							}
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
						}
					});
				} else {
					alert("Xin vui lòng nhập đủ thông tin!");
					$("#popup-loading").fadeOut("fast");
					$("#BODY").css("opacity","1");
				}
			});
			
			$("#rut-tien").click(function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				cmt = $("#add-cmt").val();
				oID = $("#oid").val();
				ngay = $("#add-ngay").val();
				if(cmt!="" && cmt.length>=7 && ngay!="" && oID!="") {
					$(".btn").css("background","#3E606F");
					$(this).css("background","blue");
					$.ajax({
						async: true,
						data: "cmt3=" + cmt + "&ngay3=" + ngay + "&oID3=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result!="none") {
								$("#info-action").html("<tr style='background:#3E606F;'><th colspan='5'><span>Rút tiền</span></th></tr><tr><td style='width:15%;'><span>Số tiền rút</span></td><td style='width:30%;'><input type='number' class='input tien' min='0' placeholder='10000, 20000, ..' /></td><td style='width:15%;'><span>Ngày đóng</span></td><td><input type='text' class='input date_dong' placeholder='12/4/2016' value='<?php echo date("d/m/Y"); ?>' /></td><td style='width:15%;'><input type='submit' class='submit' id='ok-rut' value='Rút tiền' /></td></tr>"+result);
							} else {
								alert("Không tồn tại học sinh này!");
							}
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
						}
					});
				} else {
					alert("Xin vui lòng nhập đủ thông tin!");
					$("#popup-loading").fadeOut("fast");
					$("#BODY").css("opacity","1");
				}
			});
			
			$(".popup").click(function() {
				$(this).fadeOut(250);
				$("#MAIN").css("opacity", "1");
			});
			
			$("#add-ngay").datepicker({
				dateFormat: "yy-mm-dd"
			});

            $("#info-action").delegate("tr td input.money, tr td input.dong","click",function() {
                 $(this).val("");
            });
			
			$("#info-action").delegate("tr td input.sua-nap","click",function() {
				he = $(this);
				me = $(this).closest("tr");
				me.css("opacity","0.3");
				idRA = $(this).attr("data-idRA");
				tien = me.find("td input.tien").val();
                seri = me.find("td input.seri").val();
				date_dong = me.find("td input.date_dong").val();
				cmt = $("#add-cmt").val();
				oID = $("#oid").val();
				ngay = $("#add-ngay").val();
				if($.isNumeric(tien) && tien>0 && date_dong!="" && $.isNumeric(idRA) && idRA!=0 && cmt!="" && oID!="" && ngay!="") {
					$.ajax({
						async: true,
						data: "tien5=" + tien + "&seri=" + seri + "&date_dong5=" + date_dong + "&ngay5=" + ngay + "&idRA5=" + idRA + "&cmt5=" + cmt + "&oID5=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result=="ok") {
								he.val("Xong!").css("background","blue").attr("disabled","disabled");
								me.find("td input.xoa-nap").remove();
								get_list(code,ngay);
							} else {
								alert(result);
							}
							me.css("opacity","1");
						}
					});
				} 
			});
			
			$("#info-action").delegate("tr td input.xoa-nap","click",function() {
				he = $(this);
				me = $(this).closest("tr");
				me.css("opacity","0.3");
				idRA = $(this).attr("data-idRA");
				if($.isNumeric(idRA) && idRA!=0) {
					$.ajax({
						async: true,
						data: "&idRA6=" + idRA,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result=="ok") {
								me.remove();
								get_list(code,ngay);
							} else {
								alert(result);
								me.css("opacity","1");
							}
						}
					});
				}
			});
			
			$("#info-action").delegate("tr td input.sua-rut","click",function() {
				he = $(this);
				me = $(this).closest("tr");
				me.css("opacity","0.3");
				idVAO = $(this).attr("data-idVAO");
				tien = me.find("td input.tien").val();
				date_dong = me.find("td input.date_dong").val();
				cmt = $("#add-cmt").val();
				oID = $("#oid").val();
				ngay = $("#add-ngay").val();
				if($.isNumeric(tien) && tien>0 && date_dong!=""  && $.isNumeric(idVAO) && idVAO!=0 && cmt!="" && oID!="" && ngay!="") {
					$.ajax({
						async: true,
						data: "tien4=" + tien + "&date_dong4=" + date_dong + "&ngay4=" + ngay + "&idVAO4=" + idVAO + "&cmt4=" + cmt + "&oID4=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result=="ok") {
								he.val("Xong!").css("background","blue").attr("disabled","disabled");
								me.find("td input.xoa-rut").remove();
								get_list(code,ngay);
							} else {
								alert(result);
							}
							me.css("opacity","1");
						}
					});
				}
			});
			
			$("#info-action").delegate("tr td input.xoa-rut","click",function() {
				he = $(this);
				me = $(this).closest("tr");
				me.css("opacity","0.3");
				idVAO = $(this).attr("data-idVAO");
				if($.isNumeric(idVAO) && idVAO!=0) {
					$.ajax({
						async: true,
						data: "&idVAO7=" + idVAO,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result=="ok") {
								me.remove();
								get_list(code,ngay);
							} else {
								alert(result);
								me.css("opacity","1");
							}
						}
					});
				}
			});
			
			$("#info-action").delegate("#ok-nap","click",function() {
				if(confirm("Bạn có chắc chắn?")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					tien = $(this).closest("tr").find("td input.tien").val();
                    seri = $(this).closest("tr").find("td input.seri").val();
					date_dong = $(this).closest("tr").find("td input.date_dong").val();
					cmt = $("#add-cmt").val();
					oID = $("#oid").val();
					code = $("#add-ma").val();
					ngay = $("#add-ngay").val();
					me = $(this);
					if($.isNumeric(tien) && tien>0 && cmt!="" && code!="" && oID!="" && ngay!="" && date_dong!="") {
						$.ajax({
							async: true,
							data: "tien1=" + tien + "&seri=" + seri + "&date_dong=" + date_dong + "&ngay4=" + ngay + "&cmt0=" + cmt + "&ma=" + code + "&oID=" + oID,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
							success: function(result) {
								if(result=="ok") {
									me.val("Xong!").css("background","blue");
									me.closest("tr").find("td input.input").val("");
									get_list(code,ngay);
								} else {
									alert(result);
								}
								$("#popup-loading").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
						$("#popup-loading").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				}
			});
			
			$("#info-action").delegate("#ok-rut","click",function() {
				if(confirm("Bạn có chắc chắn?")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					tien = $(this).closest("tr").find("td input.tien").val();
					date_dong = $(this).closest("tr").find("td input.date_dong").val();
					cmt = $("#add-cmt").val();
					oID = $("#oid").val();
					code = $("#add-ma").val();
					ngay = $("#add-ngay").val();
					me = $(this);
					if($.isNumeric(tien) && tien>0 && cmt!="" && code!="" && oID!="" && ngay!="" && date_dong!="") {
						$.ajax({
							async: true,
							data: "tien2=" + tien + "&date_dong=" + date_dong + "&ngay4=" + ngay + "&cmt0=" + cmt + "&ma=" + code + "&oID=" + oID,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
							success: function(result) {
								if(result=="ok") {
									me.val("Xong!").css("background","blue");
									me.closest("tr").find("td input.input").val("");
									get_list(code,ngay);
								} else {
									alert(result);
								}
								$("#popup-loading").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
						$("#popup-loading").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				}
			});
			
			$("#info-action").delegate("tr td input.add-thang","click",function() {
				me = $(this).closest("tr");
				monID = $(this).attr("data-monID");
				tr = Base64.decode($("#pre-tr").val());
				$(tr).insertBefore(me);
				me.prev("tr").attr("data-monID",monID);
                get_must_tien(me.prev("tr").find("td:first-child select.select-thang"));
			});

            $("#info-action").delegate("tr td:first-child select.select-thang","change",function() {
                get_must_tien($(this));
            });

            function get_must_tien(select) {
                var me = select.closest("tr");
                var thang = select.find("option:selected").val();
                var cmt = $("#add-cmt").val();
                var lmID = select.closest("tr").attr("data-monID");
                $("#popup-loading").fadeIn("fast");
                $("#BODY").css("opacity","0.3");
                if($.isNumeric(lmID) && lmID!=0 && cmt!="" && thang!="") {
                    $.ajax({
                        async: true,
                        data: "cmt6=" + cmt + "&thang6=" + thang + "&lmID6=" + lmID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
                        success: function(result) {
                            if(result=="none") {
                                me.find("td input.money").val("1000");
                            } else {
                                console.log("Oke");
                                me.find("td input.money").val(result).attr("disabled", "disabled");
                            }
                            $("#popup-loading").fadeOut("fast");
                            $("#BODY").css("opacity","1");
                        }
                    });
                } else {
                    console.log("Lỗi");
                    $("#popup-loading").fadeOut("fast");
                    $("#BODY").css("opacity","1");
                }
            }
			
			$("#info-action").delegate("tr td:last-child input.kill-thang","click",function() {
				$(this).closest("tr").remove();
			});
			
			$("#info-action").delegate("tr td:last-child input.del-thang","click",function() {
				if(confirm("Bạn có chắc chắn xóa? Hành động này không thể hoàn tác!")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					me = $(this).closest("tr");
					sttID = $(this).attr("data-sttID");
					oID = $("#oid").val();
					code = $("#add-ma").val();
					ngay = $("#add-ngay").val();
					if($.isNumeric(sttID) && sttID>0 && code!="" && oID!="" && ngay!="") {
						$.ajax({
							async: true,
							data: "sttID1=" + sttID + "&code1=" + code + "&oID1=" + oID,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
							success: function(result) {
								if(result=="ok") {
									me.remove();
									get_list(code,ngay);
								} else {
									alert(result);
								}
								$("#popup-loading").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
						$("#popup-loading").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				}
			});
			
			$("#info-action").delegate("tr td input.money","keyup",function() {
				tien = $(this).val();
				if(tien>=100 && tien<=9999 && $.isNumeric(tien)) {
					$(this).css("background","#ffffa5");
				} else {
					$(this).css("background","#96c8f3");
				}
			});
			
			$("#info-action").delegate("tr th input#ok-nhap","click",function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				me = $(this);
				cmt = $("#add-cmt").val();
				oID = $("#oid").val();
				code = $("#add-ma").val();
				ngay = $("#add-ngay").val();
				if(cmt!="" && oID!="" && code!="" && ngay!="") {
					ajax_data="[";
					$("#info-action tr.active-tr").each(function(index, element) {
						lmID = $(element).attr("data-monID");
						sttID = $(element).attr("data-sttID");
						date_dong = $(element).find("td select.input option:selected").val();
						money = $(element).find("td input.money").val();
                        seri = $(element).find("td input.code").val();
						date_dong2 = $(element).find("td input.dong").val();
						note = $(element).find("td input.note").val();
						if($.isNumeric(seri) && seri>=0 && date_dong!="" && date_dong2!="" && money>=90 && money<=9999 && $.isNumeric(money) && $.isNumeric(lmID) && lmID!=0 && $.isNumeric(sttID)) {
							ajax_data+='{"money":"'+money+'","seri":"'+seri+'","date_dong":"'+date_dong+'","date_dong2":"'+date_dong2+'","note":"'+note+'","lmID":"'+lmID+'","sttID":"'+sttID+'"},';
						}
					});
					ajax_data+='{"cmt":"'+cmt+'","oID":"'+oID+'","code":"'+code+'"}';
					ajax_data+="]";
					$.ajax({
						async: true,
						data: "ajax_data=" + ajax_data,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
						success: function(result) {
							if(result=="ok") {
								me.val("Xong!").css("background","blue").attr("disabled","disabled");
								get_list(code,ngay);
							} else {
								alert(result);
								me.val("Lỗi!").css("background","red");
							}
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
						}
					});
				} else {
					alert("Dữ liệu không chính xác!");
					$("#popup-loading").fadeOut("fast");
					$("#BODY").css("opacity","1");
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
            
            <div id="MAIN">
                
                <div id="main-mid">
					<h2>Nhập tiền học </h2>
                	<div>
                    	<div class="status">
                            <table class="table table-2" style="width:49%;" id="info-baomat">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Tra cứu - Thông tin trợ giảng</span></th></tr>
                                <tr>
                                	<td style="width:35%;"><span>Ngày nhập (ngày thao tác)</span></td>
									<td style="width:65%;"><input type="text" id="add-ngay" value="<?php echo date("Y-m-d"); ?>" autocomplete="off" class="input" placeholder="Click để chọn.." /></td>
                                </tr>
                                <tr>
                                	<td><span>Mã trợ giảng</span></td>
									<td><input type="password" id="add-ma" autocomplete="off" class="input" /></td>
                                </tr>
                                <tr>
                                	<td><span>Trợ giảng</span></td>
                                    <td><span id="show-trogiang" style="font-weight:600;"></span></td>
                                </tr>
                                <tr>
                                  	<th colspan="2" style="text-align:center;"><input type="submit" id="ok-ma" style="width:50%;font-size:1.375em;" class="submit" value="Lấy dữ liệu" /></th>
                                </tr>
                            </table>
                            <table class="table table-2" style="width:49%;" id="info-hs">
                                <tr style="background:#3E606F;"><th colspan="2"><span>Thông tin học sinh</span></th></tr>
                                <tr>
                                	<td colspan="2" style="text-align:center;"><span>Vui lòng nhập thông tin trợ giảng</span></td>
                                </tr>
                           	</table>
                            <table class="table" id="info-thaotac" style="margin-top:25px;display:none;">
                            	<tr style="background:#3E606F;"><th colspan="3"><span>Thao tác</span></th></tr>
                            	<tr>
                                	<td style="width:33%;"><input type="submit" id="nhap-tien" style="width:50%;font-size:1.375em;" class="submit btn" value="Nhập tiền học" /></td>
                                    <td style="width:33%;"><input type="submit" id="nap-tien" style="width:50%;font-size:1.375em;" class="submit btn" value="Nạp tiền" /></td>
                                    <td style="width:33%;"><input type="submit" id="rut-tien" style="width:50%;font-size:1.375em;" class="submit btn" value="Rút tiền" /></td>
                                </tr>
                            </table>
                            <table class="table" id="info-action" style="margin-top:25px;">
                            	
                            </table>
                            <table class="table" id="info-lichsu" style="margin-top:25px;">
                            	<tr style="background:#3E606F;"><th colspan="6"><span>Lịch sử nhập trong ngày</span></th></tr>
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
<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
    $monID=$_SESSION["mon"];
    $lopID=$_SESSION["lop"];
    $auto = format_diem2(get_auto_diem($monID));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>NHẬP ĐIỂM TỰ LUẬN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.check {width:20px;height:20px;}#MAIN #main-mid .status .table #search-box {position:absolute;z-index:9;background:#96c8f3;border:1px solid #FFF;width:250px;display:none;}#MAIN > #main-mid .status .table #search-box li {padding:0 10px 0 10px;height:35px;border-bottom:1px solid #FFF;letter-spacing:normal;font-weight:normal;text-align:left;}#MAIN > #main-mid .status .table #search-box li p {color:#3E606F;font-size:14px;line-height:35px;}#MAIN > #main-mid .status .table #search-box li a {line-height:35px;display:block;color:#3E606F;font-size:14px;}#MAIN > #main-mid .status .table #search-box li:hover {background:#3E606F;}#MAIN > #main-mid .status .table #search-box li:hover a {color:#FFF;}#MAIN > #main-mid .status .table #search-box li a span {font-size:0.625em;float:right;}#MAIN > #main-mid .status button.submit {display:inline-block;float:right;margin:10px 0 10px 10px;}#MAIN > #main-mid .status .table tr td {padding:10px !important;}
			#MAIN > #main-mid > div .status .table-2 {width:49%;display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:22px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {padding:7px 0 7px 0;background:#3E606F;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result span {color:#FFF;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			var cau_y=["a","b","c","d","e","f","g","h"];
			
			//clean_chuyende();
			
			$("#info-hs").delegate("#search-hs","click",function() {
                pre_lop = $(".nhap").attr("data-prelop");
                if(pre_lop!="") {
                    $("#search-hs").val(pre_lop);
                } else {
                    $("#search-hs").val("");
                }
				if($("#select-mon").val()==0 || $("#select-lop").val()==0) {
					alert("Bạn hãy chọn Môn và Khóa!");
				}
			});
			
			$("#info-hs").delegate("#search-hs","keyup",function() {
				ma = $(this).val();
                if(ma.length==4) {
                    $(".nhap").attr("data-prelop",ma);
                }
				if(ma.length>=7) {
                    $.ajax({
                        async: true,
                        data: "search_short=" + ma + "&lmID=" + <?php echo $lmID; ?>,
                        type: "get",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-search-hs/",
                        success: function(result) {
                            $("#search-box > ul").html(result);
                            $("#search-box").fadeIn("fast");
                        }
                    });
				}
			});
			
			$("#info-hs").delegate("#search-box ul li a", "click", function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				$(".nhap").removeClass("submit-done");
				$(".nhap").removeClass("submit-lost");
				buoiID = $("#select-buoi option:selected").val();
				hsID = $(this).attr("data-hsID");
				maso = $(this).attr("data-cmt");
				//temp = maso.split("-");
				$("#search-hs").val(maso);
				$("#search-hs").attr("data-hsID",hsID);
				if($.isNumeric(buoiID) && $.isNumeric(hsID) && hsID!=0) {
                    $.ajax({
                        async: true,
                        data: "hsID=" + hsID + "&buoiID=" + buoiID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
                        success: function(result) {
                            obj = jQuery.parseJSON(result);
                            if(obj.action=="new") {
                                $("#hs-info").html(obj.fullname + " - " + obj.birth);
                                $("#hs-de").html(obj.de + "<a style='margin-left:10px;text-decoration:underline;' href='http://localhost/www/TDUONG/thaygiao/sua-de/"+hsID+"/'>Sửa đề</a>").attr("data-de",obj.de);
                                $(".nhap").attr("data-action",obj.action)
                                            .val("Nhập");
                                de_last = $("#de-hin").val();
                                if(de_last!=obj.de) {
                                    reset_chuyende();
                                    $("#de-hin").val(obj.de);
                                    get_last_chuyende(buoiID, obj.de);
                                } else {
                                    $("#diem-chuyende tr").each(function(index, element) {
                                        $(element).find("td:last input.point").val("");
                                    });
                                }
                                $("#diem").html("Tổng: ... điểm");
                                $("#note").val(0);
                                $("#on-lop").prop("checked",true);

                            } else if(obj.action=="nghi") {
                                alert("Học sinh này đã nghỉ học!");
                            } else {
                                if(obj[0].de != obj[0].dekt) {
                                    $("#hs-de").html(obj[0].de + "(cũ: " + obj[0].dekt + ")" + "<a style='margin-left:10px;text-decoration:underline;' href='http://localhost/www/TDUONG/thaygiao/sua-de/"+hsID+"/'>Sửa đề</a>");
                                } else {
                                    $("#hs-de").html(obj[0].de + "<a style='margin-left:10px;text-decoration:underline;' href='http://localhost/www/TDUONG/thaygiao/sua-de/"+hsID+"/'>Sửa đề</a>");
                                }
                                $("#hs-de").attr("data-de",obj[0].de);
                                de_last = $("#de-hin").val();
                                $("#note").val(obj[0].note);
                                $("#hs-info").html(obj[0].fullname + " - " + obj[0].birth);
                                $(".nhap").attr("data-action",obj[0].action)
                                            .val("Sửa");
                                if(obj[0].loai==0) {
                                    $("#on-lop").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                    reset_chuyende();
                                }
                                if(obj[0].loai==1) {
                                    $("#on-home").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                    reset_chuyende();
                                }
                                if(obj[0].loai==3) {
                                    $("#on-huy").prop("checked",true);
                                    $("#lydo").fadeIn("fast");
                                    $("#diem-chuyende tr").each(function(index, element) {
                                        $(element).find("td:last input.point").val("");
                                    });
                                }
                                if(obj[0].loai==4) {
                                    $("#on-lose").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                    $("#diem-chuyende tr").each(function(index, element) {
                                        $(element).find("td:last input.point").val("");
                                    });
                                }
                                if(obj[0].loai==5) {
                                    $("#on-nghi").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                    $("#diem-chuyende tr").each(function(index, element) {
                                        $(element).find("td:last input.point").val("");
                                    });
                                }
                                if($.isNumeric(obj[0].diem)) {
                                    $("#diem").html("Tổng: " + obj[0].diem + " điểm");
                                } else {
                                    $("#diem").html("Tổng: ... điểm");
                                }
                                if(obj.lenght==1) {
                                    //alert(de_last+"-"+obj[0].dekt);
                                    $("#de-hin").val(obj[0].dekt);
                                    get_last_chuyende(buoiID, obj[0].dekt);
                                } else {
                                    $("#de-hin").val(obj[0].dekt);
                                    for(i=1;i<obj.length;i++) {
                                        if(obj[i].y==0) {
                                            $("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='"+obj[i].sttID+"'><td><span>Câu "+obj[i].cau+"</span></td><td><input type='text'' class='input cdpoint' value='"+obj[i].totalCD+"' /></td><td><select class='input select-cd' style='height:auto;width:100%;'><option value='"+obj[i].idCD+"'>"+obj[i].nameCD+"</option></select></td><td><input class='submit add_y' type='submit' value='+' /></td><td class='hidden'><span>Câu "+obj[i].cau+"</span></td><td><input type='text'' class='input point' value='"+obj[i].meCD+"' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
                                            //<input class='submit kill_cau' type='submit' value='Xóa' />
                                        } else {
                                            if(obj[i].y==1) {
                                                $("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"'><td><span>Câu "+obj[i].cau+"</span></td><td colspan='4' style='text-align:left;'><input class='submit add_y2' type='submit' value='+' /></td><td><input type='text' style='opacity:0.1' class='input' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
                                            }
                                            $("<tr class='cau-y cau-y"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-y='"+obj[i].y+"' data-cd='"+obj[i].idCD+"' data-sttID='"+obj[i].sttID+"'><td><span>"+obj[i].cau+cau_y[obj[i].y-1]+")</span></td><td><input type='text'' class='input cdpoint' value='"+obj[i].totalCD+"' /></td><td><select class='input select-cd' style='height:auto;width:100%;'><option value='"+obj[i].idCD+"'>"+obj[i].nameCD+"</option></select></td><td></td><td class='hidden'><span>"+obj[i].cau+cau_y[obj[i].y-1]+")</span></td><td><input type='text'' class='input point' value='"+obj[i].meCD+"' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
                                        }
                                    }
                                }
                            }
                            $("#search-box").fadeOut("fast");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                            $(".nhap").show();
                        }
                    });
				} else {
					alert("Xin vui lòng nhập đầy đủ thông tin!");
                    $(".nhap").show();
				}
			});
			
			$("#select-buoi, #select-mon, #select-lop").change(function() {
				lmID = $("#select-mon").val();
				if(lmID==0) {
					$("#select-mon").addClass("new-change");
				} else {
					$("#select-mon").removeClass("new-change");
				}
				if($.isNumeric(lmID) && lmID!=0) {
					$.ajax({
						async: true,
						data: "lmID2=" + <?php echo $lmID; ?>,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
						success: function(result) {
							$("#chuyende-hin").val(result);
							clean_chuyende();
						}
					});
				}
				if($(this).hasClass("buoi")) {
					clean_chuyende();
				}
				buoiID = $("#select-buoi").val();
				if($.isNumeric(buoiID) && $.isNumeric(lmID) && buoiID!=0 && lmID!=0) {
					$("#phu-diem").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/phu-diem/"+buoiID+"/"+lmID+"/'");
					$("#tinh-diemtb").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/diemtb-buoi/"+buoiID+"/"+lmID+"/'");
					//$("#xet-phat").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/xet-phat/"+buoiID+"/"+lopID+"/"+monID+"/'");
					$("#kq-thachdau").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/ket-qua-thach-dau/"+buoiID+"/"+lmID+"/'");
					$("#kq-ngoisao").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/ket-qua-ngoi-sao/"+buoiID+"/"+"/"+lmID+"/'");
					clean_hsinfo();
					get_danhsach(buoiID);
				}
			});
			
			$("#phu-diem, #tinh-diemtb, #kq-thachdau, #kq-ngoisao, #xet-phat").click(function() {
				if(confirm("Bạn có chắc chắn thực hiện hành động này? Không thể hoàn tác!")) {
					return true;
				} else {
				    $(this).removeAttr("onclick");
					return false;
				}
			});
			
			function get_last_chuyende(buoiID, dekt) {
				$.ajax({
					async: true,
					data: "buoiID4=" + buoiID + "&dekt4=" + dekt + "&lmID4=" + <?php echo $lmID; ?>,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
					success: function(result) {
						if(result!="none") {
							obj = jQuery.parseJSON(result);
							for(i=0;i<obj.length;i++) {
								if(obj[i].y==0) {
									$("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='"+obj[i].sttID+"'><td><span>Câu "+obj[i].cau+"</span></td><td><input type='text'' class='input cdpoint' value='"+obj[i].totalCD+"' /></td><td><select class='input select-cd' style='height:auto;width:100%;'><option value='"+obj[i].idCD+"'>"+obj[i].nameCD+"</option></select></td><td><input class='submit add_y' type='submit' value='+' /></td><td class='hidden'><span>Câu "+obj[i].cau+"</span></td><td><input type='text'' class='input point' value='' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
									//<input class='submit kill_cau' type='submit' value='Xóa' />
								} else {
									if(obj[i].y==1) {
										$("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"'><td><span>Câu "+obj[i].cau+"</span></td><td colspan='4' style='text-align:left;'><input class='submit add_y2' type='submit' value='+' /></td><td><input type='text' style='opacity:0.1' class='input' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
									}
									$("<tr class='cau-y cau-y"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-y='"+obj[i].y+"' data-cd='"+obj[i].idCD+"' data-sttID='"+obj[i].sttID+"'><td><span>"+obj[i].cau+cau_y[obj[i].y-1]+")</span></td><td><input type='text'' class='input cdpoint' value='"+obj[i].totalCD+"' /></td><td><select class='input select-cd' style='height:auto;width:100%;'><option value='"+obj[i].idCD+"'>"+obj[i].nameCD+"</option></select></td><td></td><td class='hidden'><span>"+obj[i].cau+cau_y[obj[i].y-1]+")</span></td><td><input type='text'' class='input point' value='' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
								}
							}
						} else {
							clean_chuyende();
						}
					}
				});
			}
			
			function clean_hsinfo() {
				$("#info-hs").html("<tr style='background:#3E606F;'><th colspan='2'><span>Thông tin học sinh</span></th></tr><tr><td style='width:35%;' class='hidden'><span>Mã số</span></td><td style='width:65%;'><input type='text' class='input' id='search-hs' placeholder='1999123, 1998231,..' autocomplete='off' /><nav id='search-box' style='left:10px;top:auto;'><ul></ul></nav></td></tr><tr><td class='hidden'><span>Thông tin</span></td><td><span id='hs-info' style='font-weight:600;font-size:22px;'></span></td></tr><tr><td class='hidden'><span>Đề</span></td><td style='text-align:center;'><p id='ca-result'><span id='hs-de' style='font-weight:600;font-size:22px;' data-de=''></span></p></td></tr><tr><td class='hidden'><span>Hình thức</span></td><td style='text-align:center;'><div style='margin-bottom:20px;'><p>Làm bài trên lớp</p><input type='radio' name='check_hoc' class='check' id='on-lop' checked='checked' /></div><div style='margin-bottom:20px;'><p>Làm bài ở nhà</p><input type='radio' name='check_hoc' class='check' id='on-home' /></div></div><div style='margin-bottom:20px;'><p>Hủy bài</p><input type='radio' name='check_hoc' class='check' id='on-huy' /></div><div style='margin-bottom:20px;'><p>Không đi thi</p><input type='radio' name='check_hoc' class='check' id='on-nghi' /></div><div><p>Mất bài, nghỉ có phép</p><input type='radio' name='check_hoc' class='check' id='on-lose' /></td></tr><tr id='lydo' style='display:none;'><td class='hidden'><span>Lý do</span></td><td><select class='input' style='height:auto;width:100%:' id='note'><option value='0'>Chọn lí do</option><?php $result1=get_all_lido();
											while($data1=mysqli_fetch_assoc($result1)) {
												echo"<option value='$data1[ID_LD]'>$data1[name]</option>";
											}
										?></select></td></tr><tr><th class='hidden'><span></span></th><th><input class='submit nhap' type='submit' data-action='new' value='Nhập' style='width:100%;font-size:1.375em;' /><input class='submit nhap' type='submit' data-action='new' value='Nhập' style='position:fixed;z-index:9;right:0;bottom:20px;width:100px;' /></th></tr>");
			}
			
			function reset_chuyende() {
				//$("#diem-chuyende tr:eq(2)").remove();
				$("#diem-chuyende").html("<tr style='background:#3E606F;'><th colspan='4'><span>Danh sách các câu</span></th><th colspan='2'><span>Điểm thành phần</span></th></tr><tr><td style='width:15%;'></td><td style='text-align:center;width:10%;'><span>Điểm</span></td><td style='text-align:center;width:25%;'><span>Chuyên đề</span></td><td style='width:15%;'></td><td style='width:20%;' class='hidden'></td><td style='width:15%;'></td></tr><tr id='tr-last'><td colspan='4' style='text-align:center;'><input class='submit add_cau' type='submit' value='Thêm câu' style='width:50%;' /></td><td colspan='2' style='width:35%;'><span id='diem' style='font-weight:600;font-size:22px;'>Tổng: ... điểm</span></td></tr>");
			};
			
			function clean_chuyende() {
				chuyende = $("#chuyende-hin").val();
                <?php if($auto == 0) { ?>
				$("#diem-chuyende").html("<tr style='background:#3E606F;'><th colspan='4'><span>Danh sách các câu</span></th><th colspan='2'><span>Điểm thành phần</span></th></tr><tr><td style='width:15%;'></td><td style='text-align:center;width:10%;'><span>Điểm</span></td><td style='text-align:center;width:25%;'><span>Chuyên đề</span></td><td style='width:15%;'></td><td style='width:20%;' class='hidden'></td><td style='width:15%;'></td></tr><tr class='cau-big cau-big1' data-cau='1'><td><span>Câu 1</span></td><td><input type='text' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit add_y' type='submit' value='+' /></td><td class='hidden'><span>Câu 1</span></td><td><input type='text' class='input point' /></td></tr><tr id='tr-last'><td colspan='4' style='text-align:center;'><input class='submit add_cau' type='submit' value='Thêm câu' style='width:50%;' /></td><td colspan='2' style='width:35%;'><span id='diem' style='font-weight:600;font-size:22px;'>Tổng: ... điểm</span></td></tr>");
                <?php } else { ?>
                $("#diem-chuyende").html("<tr style='background:#3E606F;'><th colspan='4'><span>Danh sách các câu</span></th><th colspan='2'><span>Điểm thành phần</span></th></tr><tr><td style='width:15%;'></td><td style='text-align:center;width:10%;'><span>Điểm</span></td><td style='text-align:center;width:25%;'><span>Chuyên đề</span></td><td style='width:15%;'></td><td style='width:20%;' class='hidden'></td><td style='width:15%;'></td></tr><tr class='cau-big cau-big1' data-cau='1'><td><span>Câu 1</span></td><td><input type='text' value='<?php echo $auto; ?>' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit add_y' type='submit' value='+' /></td><td class='hidden'><span>Câu 1</span></td><td><input type='text' class='input point' /></td></tr><tr id='tr-last'><td colspan='4' style='text-align:center;'><input class='submit add_cau' type='submit' value='Thêm câu' style='width:50%;' /></td><td colspan='2' style='width:35%;'><span id='diem' style='font-weight:600;font-size:22px;'>Tổng: ... điểm</span></td></tr>");
                <?php } ?>
			};
			
			function get_danhsach(buoiID) {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				$.ajax({
					async: true,
					data: "buoiID2=" + buoiID + "&lmID2=" + <?php echo $lmID; ?>,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
					success: function(result) {
						$("#list-diemdanh").html("<tr style='background:#3E606F;'><th style='width:10%' class='hidden'><span>STT</span></th><th style='width:10%:'><span>Mã</span></th><th style='width:25%;' class='hidden'><span>Học sinh</span></th><th style='width:10%:'><span>Đề</span></th><th style='width:10%;'><span>Điểm</span></th><th style='width:15%;'><span>Hình thức</span></th><th style='width:20%;'><span></span></th></tr>"+result);
						$("#status-dd").html($("#status-in").val());
						if($("#status-in").attr("data-du")==0) {
							$("#status-dd").css("background","red");
						} else {
							$("#status-dd").css("background","blue");
						}
						$("#BODY").css("opacity","1");
						$("#popup-loading").fadeOut("fast");
					}
				});
			}
			
			$("#diem-chuyende").delegate(".add_cau","click",function() {
				stt = $("#diem-chuyende tr.cau-big").length + 1;
				chuyende = $("#chuyende-hin").val();
                <?php if($auto == 0) { ?>
				$("<tr class='cau-big cau-big"+stt+"' data-cau='"+stt+"'><td><span>Câu "+stt+"</span></td><td><input type='text' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit add_y' type='submit' value='+' /><input class='submit kill_cau' type='submit' value='Xóa' /></td><td class='hidden'><span>Câu "+stt+"</span></td><td><input type='text'' class='input point' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
                <?php } else { ?>
                $("<tr class='cau-big cau-big"+stt+"' data-cau='"+stt+"'><td><span>Câu "+stt+"</span></td><td><input type='text' value='<?php echo $auto; ?>' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit add_y' type='submit' value='+' /><input class='submit kill_cau' type='submit' value='Xóa' /></td><td class='hidden'><span>Câu "+stt+"</span></td><td><input type='text'' class='input point' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
                <?php } ?>
			});
			
			$("#diem-chuyende").delegate("tr.cau-big td input.add_y", "click", function() {
				this_tr = $(this).closest(".cau-big");
				this_tr.removeAttr("data-cd");
				chuyende = $("#chuyende-hin").val();
				pos = this_tr.attr("data-cau");
				string = "";
				for(i=0;i<2;i++) {
                    <?php if($auto == 0) { ?>
					string+="<tr class='cau-y cau-y"+pos+"' data-cau='"+pos+"' data-y='"+(i+1)+"'><td><span>"+pos+cau_y[i]+")</span></td><td><input type='text' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit kill_y' type='submit' value='Xóa' /></td><td class='hidden'><span>"+pos+cau_y[i]+")</span></td><td><input type='text'' class='input point' /></td></tr>";
                    <?php } else { ?>
                    string+="<tr class='cau-y cau-y"+pos+"' data-cau='"+pos+"' data-y='"+(i+1)+"'><td><span>"+pos+cau_y[i]+")</span></td><td><input type='text' value='<?php echo $auto; ?>' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit kill_y' type='submit' value='Xóa' /></td><td class='hidden'><span>"+pos+cau_y[i]+")</span></td><td><input type='text'' class='input point' /></td></tr>";
                    <?php } ?>
				}
				this_tr.html("<td><span>Câu "+pos+"</span></td><td colspan='4' style='text-align:left;'><input class='submit add_y2' type='submit' value='+' /><input class='submit kill_cau' type='submit' value='Xóa' /></td><td><input type='text' style='opacity:0.1' class='input' /></td>");
				$(string).insertAfter(this_tr);
			});
			
			$("#diem-chuyende").delegate("tr.cau-big td input.add_y2", "click", function() {
				this_tr = $(this).closest(".cau-big");
				chuyende = $("#chuyende-hin").val();
				pos = this_tr.attr("data-cau");
				pos_con = $("#diem-chuyende tr.cau-y"+pos).length;
                <?php if($auto == 0) { ?>
				$("<tr class='cau-y cau-y"+pos+"' data-cau='"+pos+"' data-y='"+(pos_con+1)+"'><td><span>"+pos+cau_y[pos_con]+")</span></td><td><input type='text' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit kill_y' type='submit' value='Xóa' /></td><td class='hidden'><span>"+pos+cau_y[pos_con]+")</span></td><td><input type='text'' class='input point' /></td></tr>").insertAfter($("#diem-chuyende tr.cau-y"+pos+":last"));
                <?php } else { ?>
                $("<tr class='cau-y cau-y"+pos+"' data-cau='"+pos+"' data-y='"+(pos_con+1)+"'><td><span>"+pos+cau_y[pos_con]+")</span></td><td><input type='text' value='<?php echo $auto; ?>' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit kill_y' type='submit' value='Xóa' /></td><td class='hidden'><span>"+pos+cau_y[pos_con]+")</span></td><td><input type='text'' class='input point' /></td></tr>").insertAfter($("#diem-chuyende tr.cau-y"+pos+":last"));
                <?php } ?>
			});
			
			$("#diem-chuyende").delegate("tr.cau-y td input.kill_y", "click", function() {
				pos_cau = $(this).closest("tr.cau-y").attr("data-cau");
				chuyende = $("#chuyende-hin").val();
				$(this).closest("tr.cau-y").fadeOut("fast").remove();
				$("#diem-chuyende tr.cau-y"+pos_cau).each(function(index, element) {
					$(this).find("td:first span, td:eq(4) span").html((pos+cau_y[index])+")");
					$(this).attr("data-y",(index+1));
                });
				if($("#diem-chuyende tr.cau-y"+pos_cau).length==0) {
					$("#diem-chuyende tr.cau-big"+pos_cau).html("<td><span>Câu "+pos_cau+"</span></td><td><input type='text' class='input cdpoint' /></td><td><select class='input select-cd' style='height:auto;width:100%;'>"+chuyende+"</select></td><td><input class='submit add_y' type='submit' value='+' /><input class='submit kill_cau' type='submit' value='Xóa' /></td><td class='hidden'><span>Câu "+pos_cau+"</span></td><td><input type='text'' class='input point' /></td>");
				}
			});
			
			$("#diem-chuyende").delegate("tr.cau-big td input.kill_cau", "click", function() {
				this_tr = $(this).closest(".cau-big");
				pos = this_tr.attr("data-cau");
				$("#diem-chuyende tr.cau-y"+pos).fadeOut("fast").remove();
				this_tr.fadeOut("fast").remove();
				$("#diem-chuyende tr.cau-big").each(function(index, element) {
					$(this).attr("class","cau-big cau-big"+(index+1));
                    $(this).attr("data-cau",index+1);
					$(this).find("td:first span, td:eq(4) span").html("Câu "+(index+1));
                });
			});
			
			$("#info-hs").delegate("tr td div input#on-huy","change",function() {
				if($(this).is(":checked")) {
					$("#lydo").fadeIn("fast");
				} else {
					$("#lydo").fadeOut("fast");
				}
			});
			
			$("#info-hs").delegate("tr td div input#on-lop, tr td div input#on-home, tr td div input#on-lose, tr td div input#on-nghi","change",function() {
				if($(this).is(":checked")) {
					$("#lydo").fadeOut("fast");
				} 
			});
			
			$("#diem-chuyende").delegate("tr td:last-child input","keydown", function(e) {
				if(e.keyCode==40) {
					$(this).closest("tr").next("tr").find("td:last-child input").focus();
				}
				if(e.keyCode==38) {
					$(this).closest("tr").prev("tr").find("td:last-child input").focus();
				}
			});
			
			$("#diem-chuyende").delegate("tr td input.cdpoint","keydown", function(e) {
				if(e.keyCode==40) {
					$(this).closest("tr").next("tr").find("td input.cdpoint").focus();
				}
				if(e.keyCode==38) {
					$(this).closest("tr").prev("tr").find("td input.cdpoint").focus();
				}
			});
			
			$("#diem-chuyende").delegate("tr td:last-child input.point", "keyup", function(e) {
                <?php if($auto == 0) { ?>
				if(e.keyCode==49) {
					$(this).val(0);
				} else if(e.keyCode==50){
					$(this).val(0.25);
				} else if(e.keyCode==51){
					$(this).val(0.5);
				} else if(e.keyCode==52){
					$(this).val(0.75);
				} else if(e.keyCode==53){
					$(this).val(1);
				} else if(e.keyCode==54){
					$(this).val(1.25);
				} else if(e.keyCode==55){
					$(this).val(1.5);
				} else if(e.keyCode==56){
					$(this).val(1.75);
				} else if(e.keyCode==57){
					$(this).val(2);
				}
                <?php } else { ?>
                if(e.keyCode==49) {
                    $(this).val("X");
                } else if(e.keyCode==50) {
                    $(this).val(0);
                } else if(e.keyCode==51) {
                    $(this).val(<?php echo $auto; ?>);
                }
                <?php } ?>
				
				total = 0;
				if((($(this).val()>10 || $(this).val()<0) && $(this).val()!="X") || $(this).val().indexOf(",")!=-1 || $(this).closest("tr").find("td select.select-cd").val()==0) {
					$(this).css("background","#96c8f3");
				} else {
					$(this).css("background","#ffffa5");
					if($(this).val() > $(this).closest("tr").find("td input.cdpoint").val() && $(this).val()!="X") {
						$(this).css("background","#96c8f3");
					}
				}
				$("#diem-chuyende tr").each(function(index, element) {
					temp = parseFloat($(this).find("td:last input.point").val());
					if(temp<=10 && temp>=0 && (total+temp<=10)) {
						total += temp;
					}
				}); 
				$("#diem").attr("data-diem",total)
							.html("Tổng: "+total+" điểm");
			});


			$("#diem-chuyende").delegate("tr td input.cdpoint", "keyup", function(e) {
                <?php if($auto == 0) { ?>
				if(e.keyCode==49) {
					$(this).val(0.5);
				} else if(e.keyCode==50){
					$(this).val(1);
				} else if(e.keyCode==51){
					$(this).val(1.5);
				} else if(e.keyCode==52){
					$(this).val(2);
				}
                <?php } else { ?>
                if(e.keyCode==49) {
                    $(this).val(<?php echo $auto; ?>);
                }
                <?php } ?>
				if($(this).val()>10 || $(this).val()<0 || $(this).val().indexOf(",")!=-1) {
					$(this).css("background","#96c8f3");
				} else {
					$(this).css("background","#ffffa5");
				}
			});
			
			$("#diem-chuyende").delegate("tr td select.select-cd", "change", function() {
				myCD = $(this).val();
				if(myCD==0) {
					$(this).css("background","#96c8f3");
					$(this).closest("tr").removeAttr("data-cd");
				} else {
					$(this).closest("tr").attr("data-cd",myCD);
					$(this).css("background","#ffffa5");
				}
			});

            $("#list-diemdanh").delegate("tr td input.kolay", "click", function() {
                me = $(this);
                oID = $(this).attr("data-oID");
                hsID = $(this).attr("data-hsID");
                buoiID = $("#select-buoi").val();
                if($.isNumeric(oID) && $.isNumeric(buoiID) && buoiID!=0 && $.isNumeric(hsID)) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "oID=" + oID + "&buoiID5=" + buoiID + "&hsID5=" + hsID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
                        success: function(result) {
                            if(result!=0) {
                                me.css("background","red").attr("data-oID",result);
                            } else {
                                me.css("background","#3E606F").attr("data-oID",0);
                            }
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                }
            });
			
			$("#list-diemdanh").delegate("tr td input.delete", "click", function() {
				if(confirm("Bạn có chắc chắn muốn xóa điểm học sinh này?")) {
					idDiem = $(this).attr("data-idDiem");
					hsID = $(this).attr("data-hsID");
					buoiID = $("#select-buoi").val();
                    del_tr = $(this).closest("tr");
					if($.isNumeric(idDiem) && $.isNumeric(buoiID) && buoiID!=0 && $.isNumeric(hsID)) {
                        $(this).remove();
					    $.ajax({
							async: true,
							data: "idDiem=" + idDiem + "&buoiID3=" + buoiID + "&hsID3=" + hsID,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
							success: function(result) {
								del_tr.fadeOut("fast");
								$("#BODY").css("opacity","1");
								$("#popup-loading").fadeOut("fast");
							}
						});
					}
				}
			});

            $("i.refresh-list").click(function() {
                buoiID = $("#select-buoi").val();
                if($.isNumeric(buoiID) && buoiID!=0) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    get_danhsach(buoiID);
                }
            });
			
			$("#info-hs").delegate(".nhap","click",function() {
			    $(".nhap").hide();
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				action = $(this).attr("data-action");
				buoiID = $("#select-buoi").val();
				hsID = $("#search-hs").attr("data-hsID");
				de = $("#hs-de").attr("data-de");
				note = 0;
				diem = 0;
				is_tinh = 1;
				if($("#on-lop").is(":checked")) {
					loai = 0;
				}
				if($("#on-home").is(":checked")) {
					loai = 1;
				}
				if($("#on-huy").is(":checked")) {
					loai = 3;
					note = $("#note option:selected").val();
					is_tinh = 0;
				}
				if($("#on-lose").is(":checked")) {
					loai = 4;
					diem = "X";
					is_tinh = 0;
				}
				if($("#on-nghi").is(":checked")) {
					loai = 5;
					diem = 0;
					is_tinh = 0;
				}
				check = true;
				ajax_data="[";
				if($.isNumeric(buoiID) && buoiID!=0 && $.isNumeric(hsID) && (de=="B" || de=="G" || de=="Y") && $.isNumeric(loai) && ($.isNumeric(diem)) && is_tinh==1) {
					//alert("me");
					$("#diem-chuyende tr").each(function(index, element) {
						myCD = $(element).attr("data-cd");
						myCau = $(element).attr("data-cau");
						if(myCD!=0 && $.isNumeric(myCD)) {
							cdpoint = 0;
							tppoint = 0;
							temp = parseFloat($(element).find("td:eq(1) input.cdpoint").val());
							if(temp<=10 && temp>=0 && (cdpoint+temp<=10)) {
								cdpoint = temp; 
							}
							if($(element).find("td:last input.point").val()!="X") {
                                temp_tp = parseFloat($(element).find("td:last input.point").val());
                                if ((temp_tp <= 10 && temp_tp >= 0 && (tppoint + temp_tp <= 10))) {
                                    tppoint = temp_tp;
                                }
                                diem += tppoint;
                            } else {
                                tppoint = $(element).find("td:last input.point").val();
                            }
							myY = $(element).attr("data-y");
							if(myY) {
							} else {
								myY = 0;
							}
							if(action=="new") {
								ajax_data+='{"idCD":"'+myCD+'","diemCD":"'+tppoint+'/'+cdpoint+'","cau":"'+myCau+'","y":"'+myY+'"},';
							} else {
								sttID = $(element).attr("data-sttID");
								ajax_data+='{"idCD":"'+myCD+'","diemCD":"'+tppoint+'/'+cdpoint+'","cau":"'+myCau+'","y":"'+myY+'","sttID":"'+sttID+'"},';
							}
						} else {
							check=false;
						}
					}); 
				}
				ajax_data+='{"hsID":"'+hsID+'","buoiID":"'+buoiID+'","de":"'+de+'","diem":"'+diem+'","loai":"'+loai+'","note":"'+note+'"}';
				ajax_data+="]";
				if($.isNumeric(buoiID) && $.isNumeric(hsID) && (de=="B" || de=="G" || de=="Y") && ($.isNumeric(diem) || diem=="X") && ajax_data!="" && ((loai==3 && note!=0) || (loai!=3 && note==0))) {
					if(action=="new") {
						data = "data=" + ajax_data;
					} else {
						data = "data_update=" + ajax_data;
					}
					//alert(data);
					$.ajax({
						async: true,
						data: data,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
						success: function(result) {
							//alert(result);
                            //get_danhsach(buoiID);
							$("#BODY").css("opacity","1");
							$("#popup-loading").fadeOut("fast");
							if(result=="ok") {
								$(".nhap").addClass("submit-done");
								$(".nhap").attr("data-action","old")
										.val("Sửa");
							} else if(result=="dulieu"){
								alert("Dữ liệu điểm tổng chuyên đề không chính xác! Dữ liệu vẫn đc nhập nhưng hãy sửa lại!");
								$(".nhap").addClass("submit-lost");
								$(".nhap").attr("data-action","old")
										.val("Sửa");
							} else {
								alert("Dữ liệu không chính xác!");
								$(".nhap").addClass("submit-lost");
							}
							$("html,body").animate({scrollTop:0},250);
                            $(".nhap").show();
						}
					});
				} else {
					alert("Xin vui lòng nhập đầy đủ thông tin!");
					$("#BODY").css("opacity","1");
					$("#popup-loading").fadeOut("fast");
                    $(".nhap").show();
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
                	<h2>NHẬP ĐIỂM TỰ LUẬN</h2>
                	<div>
                    	<div class="status">
                        	<table class="table table-2">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin cố định</span></th></tr>
                                <tr>
                                	<td class='hidden' style="width:35%;"><span>Buổi kiểm tra</span></td>
                                	<td style="width:65%;">
                                    	<select class="input buoi" id="select-buoi" style="height:auto;width:100%;">
                                        	<option value="0">Chọn buổi kiểm tra</option>
                                            <?php
												$result5=get_all_buoikt($monID,10);
												while($data5=mysqli_fetch_assoc($result5)) {
													echo"<option value='$data5[ID_BUOI]'>".format_dateup($data5["ngay"])."</option>";
												}
											?>
                                       	</select>
                                  	</td>
                              	</tr>
                                <tr>
                                	<td class='hidden'><span>Môn</span></td>
                                    <td>
                                        <select class="input" style="height:auto;width:100%;background:#FFF;" id="select-mon">
                                        <?php
											echo"<option value='$_SESSION[lmID]'>Môn ".get_lop_mon_name($_SESSION["lmID"])."</option>";
                                            /*$result=get_all_mon_admin();
                                            for($i=0;$i<count($result);$i++) {
                                                echo"<option value='".$result[$i]["monID"]."' data-name='".$result[$i]["name"]."' ";if($_SESSION["mon"]==$result[$i]["monID"]){echo"selected='selected'";}echo">Môn ".$result[$i]["name"]."</option>";
                                            }*/
                                        ?>
                                        </select>
                                    </td>
                              	</tr>
                                <tr>
                                	<td class='hidden'><span>Trạng thái<i class="fa fa-refresh refresh-list" style="margin-left: 10px;font-size:18px;"></i></span></td>
                                    <td><span id="status-dd" style="display:block;color:#FFF;padding:5px 0 5px 0;text-align:center;"></span></td>
                                </tr>
                                <tr>
                                	<td class='hidden'><span>Phủ điểm 0 hàng loạt cho học sinh không đi thi, nghỉ học</span></td>
                                	<td><input type="submit" class="submit" value="Phủ điểm 0" onclick="" style="width:100%;" id="phu-diem" /></td>
                                </tr>
                                <tr>
                                	<td class='hidden'><span>Tính điểm TB của buổi kiểm tra này (B,G)</span></td>
                                	<td><input type="submit" class="submit" value="Tính điểm TB" onclick="" style="width:100%;" id="tinh-diemtb" /></td>
                                </tr>
                                <tr>
                                	<td class='hidden'><span>Kết quả thách đấu</span></td>
                                	<td><input type="submit" class="submit" value="KQ Thách đấu" onclick="" style="width:100%;" id="kq-thachdau" /></td>
                                </tr>
                                <tr>
                                	<td class='hidden'><span>Kết quả ngôi sao</span></td>
                                	<td><input type="submit" class="submit" value="KQ Ngôi sao" onclick="" style="width:100%;" id="kq-ngoisao" /></td>
                                </tr>
                            </table>
                            <table class="table table-2" id="info-hs">
                           		<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin học sinh</span></th></tr>
                                <tr><td colspan="2" style="text-align:center;"><span>Vui lòng chọn đủ thông tin bên trái!</span></td></tr>
                            </table>
                            <table class="table" style="margin-top:25px;" id="diem-chuyende">
                            </table>
                            <table class="table" id="list-diemdanh" style="margin-top:25px;">
                            	
                            </table>
                            <input type="hidden" value="" id="chuyende-hin" />	
                            <input type="hidden" value="" id="de-hin" />
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
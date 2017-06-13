<?php
	ob_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	session_start();
    if(!$_SESSION['id']) {
        header('location: http://localhost/www/TDUONG/trogiang/dang-nhap/');
        exit();
    }
    $id=$_SESSION['id'];
    $now=date("d/m/Y");
    $birth=date("/m/");
	$mau="#FFF";

    $result0=get_info_tro_giang($id);
    $data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>ĐIỂM DANH</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/hover.css" />
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/bocuc.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->     
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/trogiang/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}table tr:last-child td:first-child, table tr:last-child td:last-child {border-bottom-left-radius:0;border-bottom-right-radius:0;}#MAIN .main-div #main-info #main-1-left .table-tkb tr td {border-bottom:1px solid rgba(0,0,0,0.15);padding-bottom:5px;}
            #MAIN > #main-mid > div .status #search-box {width:100%;}.check {width:20px;height:20px;margin-right:10px;}
            /*.hideme {margin-left:-150%;opacity:0;}*/
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
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

                function t(a, c, e) {
                    $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), lmID= $("#select-mon").find("option:selected").val(), monID=$("#select-mon").find("option:selected").attr("data-mon"), $.ajax({
                        async: true,
                        data: "ngay=" + a + "&mon=" + monID + "&lmID=" + lmID + "&ca=" + c + "&cd=" + e,
                        type: "post",
                        url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
                        success: function(a) {
                            $("#table-history").html("<tr style='background:none;'><th style='width:10%;' class='hidden'><span>STT</span></th><th style='width:20%;' class='hidden'><span>Học sinh</span></th><th style='width:10%;'><span>Mã</span></th><th style='width:15%;'><span>Học bài</span></th><th style='width:15%;'><span>Tính toán</span></th><th style='width:15%;'><span>Ca đăng ký</span></th><th style='width:15%;'><span></span></th></tr>" + a);
                            $("#status-dd").html($("#status-in").val());
                            if($("#status-in").attr("data-du")==0) {
                                $("#status-dd").css("background","rgba(255,0,0,2.5)");
                            } else {
                                $("#status-dd").css("background","blue");
                            }
                            $("#BODY").css("opacity", "1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    })
                }
                //$("#select-lop").change(function() {
                $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), lmID= $("#select-mon").find("option:selected").val(), monID=$("#select-mon").find("option:selected").attr("data-mon") , temp = $("#select-date").val().split("/"), ngay = temp[2] + "-" + temp[1] + "-" + temp[0], "" != ngay ? ($.ajax({
                    async: true,
                    data: "monID2=" + monID + "&lmID2=" + lmID + "&ngay=" + ngay,
                    type: "post",
                    url: "http://localhost/www/TDUONG/trogiang/xuly-mon/",
                    success: function(a) {
                        $("#select-ca").html(a), $("#popup-loading").fadeOut("fast"), $("#BODY").css("opacity", "1")
                    }
                })) : ($("#select-lop option:first").prop("selected", !0), alert("Bạn hãy chọn Môn và Ngày!"), $("#popup-loading").fadeOut("fast"), $("#BODY").css("opacity", "1"))
//                /*}),*/ $("#search-hs").click(function() {
//                    a(), (0 == $("#select-mon").val() || 0 == $("#select-lop").val() || 0 == $("#select-ca").val()) && alert("Bạn hãy chọn đầy đủ thông tin bên trái!")
//                });
                $("#search-hs").keyup(function() {
                    ma = $(this).val().trim();
                    var lmID= $("#select-mon").find("option:selected").val();
                    if(ma.length==4) {
                        $("#nhap").attr("data-prelop",ma);
                    }
                    if(ma.length>=1) {
                        $.ajax({
                            async: true,
                            data: "search_short=" + ma + "&lmID=" + lmID,
                            type: "get",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-search-hs/",
                            success: function(a) {
                                $("#search-box > ul").html(a), $("#search-box").fadeIn("fast")
                            }
                        });
                    } else {
                        $("#search-box").fadeOut("fast");
                    }
                }), $("#nhap").click(function() {
                    $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"),lmID= $("#select-mon").find("option:selected").val(), monID=$("#select-mon").find("option:selected").attr("data-mon"), ngay = $("#select-date").val(), caID = $("#select-ca").val(), cdID = 0, hsID = $("#search-hs").attr("data-hsID"), cum = $("#select-ca option:selected").attr("data-cum"), is_kt = 1, $("#co-dung").is(":checked") && (is_hoc = 1, is_tinh = 1), $("#co-sai").is(":checked") && (is_hoc = 1, is_tinh = 0), $("#ko-sai").is(":checked") && (is_hoc = 0, is_tinh = 0), $("#is_kt").is(":checked") && (is_hoc = 0, is_tinh = 0, is_kt = 0), caCheck = $("#ca-result").attr("data-result"), $(this).hasClass("nhap_new") ? ajax_data = "ngay=" + ngay + "&monID=" + monID + "&lmID=" + lmID + "&caID=" + caID + "&cum0=" + cum + "&cdID=" + cdID + "&hsID=" + hsID + "&is_hoc=" + is_hoc + "&is_tinh=" + is_tinh + "&is_kt=" + is_kt + "&caCheck=" + caCheck : (sttID = $(this).attr("data-sttID"), ajax_data = "ngay=" + $("#select-date").val() + "&hsID3=" + hsID + "&is_hoc3=" + is_hoc + "&is_tinh3=" + is_tinh + "&is_kt3=" + is_kt + "&sttID3=" + sttID + "&cum0=" + $("#select-ca option:selected").attr("data-cum") + "&lmID=" + lmID + "&monID=" + monID), !($.isNumeric(caID) && $.isNumeric(cdID) && $.isNumeric(hsID))   || 0 != is_hoc && 1 != is_hoc || 0 != is_tinh && 1 != is_tinh || 0 != is_kt && 1 != is_kt || 0 != caCheck && 1 != caCheck || 0 == caID || 0 == hsID ? (alert("Xin hãy nhập đầy đủ thông tin và chính xác!"), $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast")) : $.ajax({
                        async: true,
                        data: ajax_data,
                        type: "post",
                        url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
                        success: function(a) {
                            "ok" != a ? alert(a) : t(ngay, caID, cdID), $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast"), $("#nhap").addClass("submit-done")
                        }
                    })
                }), $("#search-box ul").delegate("li a", "click", function() {
                    $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), $(".nhap").removeClass("submit-done"), lmID= $("#select-mon").find("option:selected").val(), monID=$("#select-mon").find("option:selected").attr("data-mon"), hsID = $(this).attr("data-hsID"), ngay = $("#select-date").val(), caID = $("#select-ca option:selected").val(), cdID = 0, maso = $(this).attr("data-cmt"), $("#search-hs").val(maso), cum = $("#select-ca option:selected").attr("data-cum"), is_quyen = $("#select-ca option:selected").attr("data-quyen"), $("#search-hs").attr("data-hsID", hsID), $.isNumeric(lmID) && 0 != lmID && $.isNumeric(monID) && 0 != monID && $.isNumeric(hsID) && 0 != caID && 0 != hsID && $.isNumeric(caID) && $.isNumeric(cdID) && $.isNumeric(is_quyen) ? $.ajax({
                        async: true,
                        data: "hsID0=" + hsID + "&ngay2=" + ngay + "&monID=" + monID + "&lmID=" + lmID + "&ca2=" + caID + "&cd2=" + cdID + "&cum=" + cum + "&is_quyen=" + is_quyen,
                        type: "post",
                        url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
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
                                        $("#ca-result").html("Đúng ca").css("background", "rgba(62,96,111,2.5)").attr("data-result", 1);
                                    } else {
                                        $("#ca-result").html("Sai ca").css("background", "rgba(255,0,0,2.5)").attr("data-result", 0);
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
                                    url: "http://localhost/www/TDUONG/trogiang/xuly-taikhoan/",
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
                    var lmID= $("#select-mon").find("option:selected").val();
                    var monID=$("#select-mon").find("option:selected").attr("data-mon");
                    ngay = $("#select-date").val(), caID = $("#select-ca").val(), 0 == caID ? $("#select-ca").addClass("new-change") : $("#select-ca").removeClass("new-change"), $.isNumeric(caID) && 0 != caID && (a(), t(ngay, caID, 0));
                    $("#kiem-tra").attr("onclick","location.href='http://localhost/www/TDUONG/admin/kiem-tra-diem-danh/" + ngay.replace(/\//g,"-") + "/" + caID + "/" + lmID + "/" + monID + "/'");
                }), $("#table-history").delegate("tr td input.delete", "click", function() {
                    del_tr = $(this).closest("tr"), sttID = $(this).attr("data-sttID"), confirm("Bạn có chắc chắn xóa học sinh này khỏi danh sách điểm danh không?") && $.ajax({
                        async: true,
                        data: "sttIDx=" + sttID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
                        success: function(a) {
                            del_tr.fadeOut("fast")
                        }
                    })
                }), $("#MAIN #main-mid").click(function() {
                    $("#wrong").fadeOut("fast"), $("#status").fadeOut("fast")
                }), $("#select-date").keyup(function() {
                    temp = $(this).val().split("/"), new_date = temp[2] + "-" + temp[1] + "-" + temp[0], lmID= $("#select-mon").find("option:selected").val(), monID=$("#select-mon").find("option:selected").attr("data-mon"), $.ajax({
                        async: true,
                        data: "new_date=" + new_date + "&lmID=" + lmID + "&monID=" + monID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
                        success: function(a) {
                            $("#select-ca").html("<select class='input' style='height:auto;width:100%;' id='select-ca'><option value='0'>Chọn ca hiện hành</option>" + a + "</select>"), $("#select-ca").addClass("new-change")
                        }
                    })
                });
                $("#select-mon, #select-lop, #select-ca").change(function() {
                    ngay = $("#select-date").val(), caID = $("#select-ca").val(), lmID= $("#select-mon").find("option:selected").val(), monID=$("#select-mon").find("option:selected").attr("data-mon"), 0 == caID ? $("#select-ca").addClass("new-change") : $("#select-ca").removeClass("new-change"), $.isNumeric(caID) && 0 != caID && lmID != 0 && $.isNumeric(lmID) && monID != 0 && $.isNumeric(monID) && (a(), t(ngay, caID, 0));
                    $("#kiem-tra").attr("onclick","location.href='http://localhost/www/TDUONG/admin/kiem-tra-diem-danh/" + ngay.replace(/\//g,"-") + "/" + caID + "/" + lmID + "/" + monID + "/'");
                });
                $("#xuat-ca").click(function() {
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
                    $("table#hoc-sinh-info").delegate("tr td input#tang-sn","click",function() {
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
                            url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
                            success: function(a) {
                                console.log(a);
                            }
                        });
                    }
                });

                $("#table-history").delegate("tr td input.input","keyup",function(e) {
                    if(e.keyCode==40) {
                        $(this).closest("tr").next("tr").find("td input.input").focus();
                    }
                    if(e.keyCode==38) {
                        $(this).closest("tr").prev("tr").find("td input.input").focus();
                    }
                });
                    $("#table-history").delegate("tr td:last-child input.add_only_nghi","click",function() {
                    me = $(this);
                    del_tr = $(this).closest("tr");
                    del_tr.css("opacity", "0.3");
                    cumID = $(this).attr("data-cumID");
                    hsID = $(this).attr("data-hsID");
                    is_phep = del_tr.find("td select.input option:selected").val();
                    monID = $("#select-mon").find("option:selected").attr("data-mon");
                    lmID = $("#select-mon").find("option:selected").val();
                    if($.isNumeric(monID) && monID!=0 && $.isNumeric(lmID) && lmID!=0 && $.isNumeric(cumID) && cumID!=0 && $.isNumeric(hsID) && hsID!=0 && (is_phep==1 || is_phep==0)) {
                        //alert(ddID + "-" + hsID + "-" + lydo);
                        $.ajax({
                            async: true,
                            data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + lmID + "&monID=" + monID + "&is_bao=0",
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
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
                });
                    $("#table-history").delegate("tr td:last-child input.del_only_nghi","click",function() {
                    me = $(this);
                    del_tr = $(this).closest("tr");
                    del_tr.css("opacity", "0.3");
                    cumID = $(this).attr("data-cumID");
                    hsID = $(this).attr("data-hsID");
                    lmID = $("#select-mon").find("option:selected").val();
                    monID = $("#select-mon").find("option:selected").attr("data-mon");
                    if($.isNumeric(cumID) && cumID!=0 && $.isNumeric(hsID) && hsID!=0 && lmID!=0 && $.isNumeric(lmID) && monID!=0 && $.isNumeric(monID)) {
                        $.ajax({
                            async: true,
                            data: "cumID0=" + cumID + "&hsID0=" + hsID + "&lmID=" + lmID + "&monID=" + monID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
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
                });

            });
		</script>
       
	</head>

    <body>
        <div class="popup" id="popup-loading">
            <p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
        </div>
        <?php
        if(isset($_SESSION["error_msg"]) && $_SESSION["error_msg"]) {
            echo"<div class='popup animated bounceIn' style='display:block;'>
                        <div>
                            <p class='title'>".$_SESSION["error_msg"]."</p>
                        </div>
                    </div>";
            $_SESSION["error_msg"]=NULL;
        }
        ?>
                             
      	<div id="BODY">
            
            <div id="MAIN">
                <div id="main-mid">
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height: 98px;">Điểm danh học sinh</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                        <img src="http://localhost/www/TDUONG/trogiang/avata/placeholder.jpg"/>
                        <a href="http://localhost/www/TDUONG/trogiang/ho-so/" title="Hồ sơ cá nhân">
                            <p><?php echo $data0["name"];?></p>
                            <i class="fa fa-pencil"></i>
                        </a>
                    </div>
                    <!--<div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>-->
                </div>
                
                <div class="main-div animated bounceInUp status">
                    <div id="main-info">
                    	<div class="main-1-left back" style="margin-right:2%;">
                        	<div>
                            	<p class="main-title">Thông tin cố định</p>
                            </div>
                            <table class="table-tkb">
                                <tr>
                                    <td class='hidden' style="width:35%;"><span>Môn</span></td>
                                    <td style="width:55%;">
                                        <select class="input" style="height:auto;width:100%;background:none;" id="select-mon">
                                            <?php
                                                    $result = get_all_lop_mon();
                                                    while($data=mysqli_fetch_assoc($result)) {
                                                        echo "<option data-mon='$data[ID_MON]' value='$data[ID_LM]' ";
                                                        echo " ><span>$data[name]</span></option>";
                                                    }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='hidden'><span>Ngày điểm danh</span></td>
<!--                                    <td><input class="input" id="select-date" type="text" value="--><?php //echo $now; ?><!--" /></td>-->
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
                                    <td class='hidden'><span>Trạng thái</span></td>
                                    <td><span id="status-dd" style="display:block;color:#FFF;padding:5px 0 5px 0;text-align:center;"></span></td>
                                </tr>
                                <tr>
                                    <th colspan="2"><input type="submit" class="submit" value="Kiểm tra" id="kiem-tra" /><input type="submit" class="submit" value="Lọc sai ca" id="xuat-ca" /></th>
                                </tr>
                          	</table>
                       	</div>
                        <div class="main-1-left back">
                            <div>
                            	<p class="main-title">Thông tin học sinh</p>
                            </div>
                            <table class="table-tkb">
                                <tr>
                                    <td class='hidden' style="width:35%;"><span>Mã số</span></td>
                                    <td style="width:65%;">
                                        <input type="text" class="input" id="search-hs" placeholder="19991234, 19982365, .." autocomplete="off" />
                                        <nav id="search-box" style="left:10px;top:auto;position:relative;">
                                            <ul>
                                            </ul>
                                        </nav>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='hidden'><span>Thông tin</span></td>
                                    <td style="text-align:left;"><span id="hs-info" style="font-weight:600;font-size:14px;"></span></td>
                                </tr>
                                <tr>
                                    <td class='hidden'><span>Ca đăng ký</span></td>
                                    <td><p class="td-p" id="ca-regis"></p><p class="td-p" id="ca-result" data-result=""></p></td>
                                </tr>
                                <tr>
                                    <td class='hidden'><span>Kiểm tra đầu giờ</span></td>
                                    <td style="text-align:center;">
                                        <div style="margin-bottom:20px;"><span>Học bài, tính đúng</span> <input type="radio" name="check_hoc" class="check" id="co-dung" checked="checked" /></div>
                                        <div style="margin-bottom:20px;"><span>Học bài, tính sai</span> <input type="radio" name="check_hoc" class="check" id="co-sai" /></div>
                                        <div style="margin-bottom:20px;"><span>Không học bài</span> <input type="radio" name="check_hoc" class="check" id="ko-sai" /></div>
                                        <div><span>Ko kiểm tra / Đi muộn</span> <input type="radio" name="check_hoc" class="check" id="is_kt" /><input type="checkbox" class="check" id="check_fixed" /></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="2"><input class="submit nhap_new" data-prelop="" type="submit" id="nhap" value="Nhập" style="font-size:1.375em;" /></th>
                                </tr>
                            </table>
                      	</div>
                        <div class="main-div animated bounceInUp">
                            <div id="main-info">
                                <div class="main-1-left back" style="margin-right:2%;width:100%;">
                                    <table class="table-tkb" style="width:100%;" id="table-history">
                                        <tr>
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
                            <div class="clear"></div>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
                </div>

            </div>
        	<div class="clear"></div>
        </div>
        <?php require_once("include/MENU.php"); ?>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
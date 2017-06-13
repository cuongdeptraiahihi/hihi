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
        
        <title>NHẬP ĐIỂM TRẮC NGHIỆM</title>
        
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
            #MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:14px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}
            /*table#diem-chuyende tr td p {display: block;float:left;text-align: center;width: 16%;opacity: 0.3;position: relative;margin-top: -10px;}*/
            /*table#diem-chuyende tr td p > span {position: absolute;z-index: 9;top:100%;width: 30%;left: 35%;font-size: 17px !important;}*/
            table.diem-form {width:32% !important;float:left;}
            table.diem-form tr td {text-align: center !important;}
            table.diem-form tr td p {display: block;float:left;text-align: center;width: 16%;opacity: 0.3;position: relative;margin-bottom: 20px;}
            table.diem-form tr td p > span {position: absolute;z-index: 9;top:100%;width: 30%;left: 35%;font-size: 15px !important;}
            table.diem-form tr td p input.check {width:15px;height:15px;}
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

            var da_arr = ["A","B","C","D","E","F","G","H","I"];

            $("#info-hs").delegate("input#ma-de","click",function() {
                console.log("Click ma de!");
                $(this).typeWatch({
                    captureLength: 3,
                    callback: function (value) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        buoiID = $("#select-buoi option:selected").val();
                        hsID = $("#search-hs").attr("data-hsID");
                        if(value.length >= 3 && $.isNumeric(buoiID) && $.isNumeric(hsID) && hsID!=0) {
                            $.ajax({
                                async: true,
                                data: "made8=" + value + "&hsID8=" + hsID + "&buoiID8=" + buoiID,
                                type: "post",
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
                                success: function (result) {
                                    clean_chuyende();
//                                        alert(result);
                                    obj = jQuery.parseJSON(result);
                                    $("#ma-de").val(obj[0].made).removeAttr("disabled");
                                    $("#diem").html("Tổng: ... điểm");
                                    $("#note").val(0);
                                    $("#on-lop").prop("checked",true);
                                    var num_cau = 50;
                                    var num_each = 17;
                                    var curr_table = 1;
                                    var curr_cau = 0;
                                    for(i=1;i<obj.length;i++) {
//                                        $("table.diem-form-" + curr_table).append("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='0'><td><span>"+obj[i].cau+" - <strong></strong></span></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p><input type='text' style='display: none;' class='input note-dapan' placeholder='Đáp án khác' /></td></tr>");
                                        $("table.diem-form-" + curr_table).append("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='0'><td><span>"+obj[i].cau+" - <strong></strong></span></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p style='float: right;'><input type='radio' value='0' class='check point' name='radio-point-"+i+"' /><span>E</span></p></td></tr>");
//                                    $("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='0'><td><span>"+obj[i].cau+"</span></td><td><select class='input select-cd' style='height:auto;width:100%;'><option value='"+obj[i].idCD+"'>"+obj[i].nameCD+"</option></select></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p></td><td><input type='text' style='display:none;' class='input note-dapan' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
                                        curr_cau++;
                                        if(curr_table < 3 && curr_cau == num_each) {
                                            curr_cau=0;
                                            curr_table++;
                                        }
                                    }
                                    $("#BODY").css("opacity", "1");
                                    $("#popup-loading").fadeOut("fast");
                                }
                            });
                        } else {
                            console.log("Lỗi");
                            $("#BODY").css("opacity", "1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    }
                });
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
                        data: "hsID7=" + hsID + "&buoiID7=" + buoiID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
                        success: function(result) {
                            clean_chuyende();
                            $("#lydo").fadeOut("fast");
                            $("#ma-huy").val("");
                            obj = jQuery.parseJSON(result);
                            console.log(result);
                            if(obj[0].action=="new") {
                                $("#hs-info").html(obj[0].fullname + " - " + obj[0].birth);
                                $("#ma-de").val(obj[0].made).removeAttr("disabled");
                                $("#hs-de").html(obj[0].de + "<a style='margin-left:10px;text-decoration:underline;' href='http://localhost/www/TDUONG/thaygiao/sua-de/"+hsID+"/'>Sửa đề</a>").attr("data-de",obj[0].de);
                                $(".nhap").attr("data-action",obj[0].action)
                                            .val("Nhập");
                                de_last = $("#de-hin").val();
                                if(de_last!=obj[0].de) {
                                    $("#de-hin").val(obj[0].de);
                                }
                                $("#diem").html("Tổng: ... điểm");
                                $("#note").val(0);
                                $("#on-lop").prop("checked",true);
                                var num_cau = 50;
                                var num_each = 17;
                                var curr_table = 1;
                                var curr_cau = 0;
                                for(i=1;i<obj.length;i++) {
//                                    $("table.diem-form-" + curr_table).append("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='0'><td><span>"+obj[i].cau+" - <strong></strong></span></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p><input type='text' style='display: none;' class='input note-dapan' placeholder='Đáp án khác' /></td></tr>");
                                    $("table.diem-form-" + curr_table).append("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='0'><td><span>"+obj[i].cau+" - <strong></strong></span></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p style='float: right;'><input type='radio' value='0' class='check point' name='radio-point-"+i+"' /><span>E</span></p></td></tr>");
//                                    $("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='0'><td><span>"+obj[i].cau+"</span></td><td><select class='input select-cd' style='height:auto;width:100%;'><option value='"+obj[i].idCD+"'>"+obj[i].nameCD+"</option></select></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p></td><td><input type='text' style='display:none;' class='input note-dapan' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
                                    curr_cau++;
                                    if(curr_table < 3 && curr_cau == num_each) {
                                        curr_cau=0;
                                        curr_table++;
                                    }
                                }
                            } else if(obj[0].action=="nghi") {
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
                                $("#ma-de").val(obj[0].made).attr("disabled","disabled");
                                $("#hs-info").html(obj[0].fullname + " - " + obj[0].birth);
                                $(".nhap").attr("data-action",obj[0].action)
                                            .val("Sửa");
                                if(obj[0].loai==0) {
                                    $("#on-lop").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                }
                                if(obj[0].loai==1) {
                                    $("#on-home").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                }
                                if(obj[0].loai==3) {
                                    $("#on-huy").prop("checked",true);
                                    $("#lydo").fadeIn("fast");
                                }
                                if(obj[0].loai==4) {
                                    $("#on-lose").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                }
                                if(obj[0].loai==5) {
                                    $("#on-nghi").prop("checked",true);
                                    $("#lydo").fadeOut("fast");
                                }
                                if($.isNumeric(obj[0].diem)) {
                                    $("#diem").html("Tổng: " + obj[0].diem + " điểm");
                                } else {
                                    $("#diem").html("Tổng: ... điểm");
                                }
                                $("#de-hin").val(obj[0].dekt);
                                var num_cau = 50;
                                var num_each = 17;
                                var curr_table = 1;
                                var curr_cau = 0;
                                for(i=1;i<obj.length;i++) {
//                                    $("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='"+obj[i].sttID+"'><td><span>"+obj[i].cau+"</span></td><td><select class='input select-cd' style='height:auto;width:100%;'><option value='"+obj[i].idCD+"'>"+obj[i].nameCD+"</option></select></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p></td><td><input type='text' style='display:none;' class='input note-dapan' /></td></tr>").insertBefore("#diem-chuyende tr#tr-last");
//                                    $("#diem-chuyende tr.cau-big"+obj[i].cau).find("td.dap-an-td p input.point:eq("+(obj[i].y - 1)+")").prop("checked",true);
//                                    if(obj[i].y==5) {
//                                        $("#diem-chuyende tr.cau-big"+obj[i].cau).find("td:last-child input.note-dapan").show().val(obj[i].note);
//                                    }
//                                    $("table.diem-form-" + curr_table).append("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='"+obj[i].sttID+"'><td><span>"+obj[i].cau+" - <strong>" + da_arr[obj[i].y - 1] + "</strong></span></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p><input type='text' style='display: none;' class='input note-dapan' placeholder='Đáp án khác' /></td></tr>");
                                    $("table.diem-form-" + curr_table).append("<tr class='cau-big cau-big"+obj[i].cau+"' data-cau='"+obj[i].cau+"' data-cd='"+obj[i].idCD+"' data-sttID='"+obj[i].sttID+"'><td><span>"+obj[i].cau+" - <strong>" + da_arr[obj[i].y - 1] + "</strong></span></td><td class='dap-an-td'><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p style='float: right;'><input type='radio' value='0' class='check point' name='radio-point-"+i+"' /><span>E</span></p></td></tr>");
                                    $("table.diem-form-" + curr_table + " tr.cau-big"+obj[i].cau).find("td.dap-an-td p input.point:eq("+(obj[i].y - 1)+")").prop("checked",true);
                                    if(obj[i].y==0) {
                                        $("table.diem-form-" + curr_table + " tr.cau-big"+obj[i].cau).find("td.dap-an-td p input.point:eq("+(obj[i].y - 1)+")").closest("p").css({"background":"none"});
                                    } else {
                                        $("table.diem-form-" + curr_table + " tr.cau-big"+obj[i].cau).find("td.dap-an-td p input.point:eq("+(obj[i].y - 1)+")").closest("p").css({"background":"grey"});
                                    }
                                    if(obj[i].y==5) {
                                        $("table.diem-form-" + curr_table + " tr.cau-big"+obj[i].cau).find("td.dap-an-td input.note-dapan").show().val(obj[i].note);
                                    }
                                    curr_cau++;
                                    if(curr_table < 3 && curr_cau == num_each) {
                                        curr_cau=0;
                                        curr_table++;
                                    }
                                }
                            }

                            caID = $("#select-ca option:selected").val();
                            if(caID!=0) {
                                ngay = $("#select-buoi option:selected").attr("data-ngay");
                                cum = $("#select-ca option:selected").attr("data-cum");
                                $.ajax({
                                    async: true,
                                    data: "hsID0=" + hsID + "&ngay2=" + ngay + "&monID=" + <?php echo $monID; ?> +"&lmID=" + 0 + "&ca2=" + caID + "&cd2=" + 0 + "&cum=" + cum + "&is_quyen=0",
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
                                    success: function (a) {
                                        obj = jQuery.parseJSON(a);
                                        $("#ca-regis").html(obj.ca_hientai);
                                        if (obj.action == "new") {
                                            if (obj.caID_send == caID) {
                                                $("#ca-result").html("Đúng ca").css("background", "#3E606F").attr("data-result", 1);
                                            } else {
                                                $("#ca-result").html("Sai ca").css("background", "red").attr("data-result", 0);
                                            }
                                            $(".nhap").attr("class", "submit nhap nhap_new");
                                            $(".nhap").val("Nhập");
                                        } else {
                                            if (obj.ca_check == 1) {
                                                $("#ca-result").html("Đúng ca").css("background", "#3E606F").attr("data-result", 1);
                                            } else {
                                                $("#ca-result").html("Sai ca").css("background", "red").attr("data-result", 0);
                                            }
                                            $(".nhap").attr("class", "submit nhap nhap_edit");
                                            $(".nhap").val("Sửa");
                                            $(".nhap").attr("data-sttID", obj.caID_send);
                                        }
                                    }
                                });
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
				buoiID = $("#select-buoi").val();
				if($.isNumeric(buoiID) && $.isNumeric(lmID) && buoiID!=0 && lmID!=0) {
                    $("#cap-nhat").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/cap-nhat-diem/"+buoiID+"/"+lmID+"/bgo-luyenthi/'");
                    $("#cap-nhat2").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/cap-nhat-diem/"+buoiID+"/"+lmID+"/luyenthi-bgo/'");
					$("#phu-diem").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/phu-diem/"+buoiID+"/"+lmID+"/'");
					$("#tinh-diemtb").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/diemtb-buoi/"+buoiID+"/"+lmID+"/'");
					//$("#xet-phat").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/xet-phat/"+buoiID+"/"+lopID+"/"+monID+"/'");
					$("#kq-thachdau").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/ket-qua-thach-dau/"+buoiID+"/"+lmID+"/'");
					$("#kq-ngoisao").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/ket-qua-ngoi-sao/"+buoiID+"/"+"/"+lmID+"/'");
					clean_hsinfo();
					get_danhsach(buoiID);
                    $("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), ngay = $("#select-buoi").find("option:selected").attr("data-ngay"), "" != ngay && $.ajax({
                        async: true,
                        data: "monID2=" + <?php echo $monID; ?> + "&lmID2=" + 0 + "&ngay=" + ngay,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(a) {
                            $("#select-ca").html(a), $("#popup-loading").fadeOut("fast"), $("#BODY").css("opacity", "1")
                        }
                    })
				}
			});
			
			$("#cap-nhat, #cap-nhat2, #phu-diem, #tinh-diemtb, #kq-thachdau, #kq-ngoisao, #xet-phat").click(function() {
				if(confirm("Bạn có chắc chắn thực hiện hành động này? Không thể hoàn tác!")) {
					return true;
				} else {
				    $(this).removeAttr("onclick");
					return false;
				}
			});
			
			function clean_hsinfo() {
				$("#info-hs").html("<tr style='background:#3E606F;'><th colspan='2'><span>Thông tin học sinh</span></th></tr><tr><td style='width:35%;' class='hidden'><span>Mã số</span></td><td style='width:65%;'><input type='text' class='input' id='search-hs' placeholder='1999123, 1998231,..' autocomplete='off' /><nav id='search-box' style='left:10px;top:auto;'><ul></ul></nav></td></tr><tr><td class='hidden'><span>Mã đề</span></td><td><input type='text' class='input' id='ma-de' placeholder='Điền mã đề' /></td></tr><tr><td class='hidden'><span>Thông tin</span></td><td><span id='hs-info' style='font-weight:600;font-size:22px;'></span></td></tr><tr> <td class='hidden'><span>Ca đăng ký</span></td><td><p class='td-p' id='ca-regis'></p><p class='td-p' id='ca-result' data-result=''></p></td></tr><tr><td class='hidden'><span>Đề</span></td><td style='text-align:center;'><p id='ca-result'><span id='hs-de' style='font-weight:600;font-size:22px;' data-de=''></span></p></td></tr><tr><td class='hidden'><span>Điểm</span></td><td><span id='diem'></span></td></tr><tr><td class='hidden'><span>Hình thức</span></td><td style='text-align:center;'><div style='margin-bottom:20px;'><p>Làm bài trên lớp</p><input type='radio' name='check_hoc' class='check' id='on-lop' checked='checked' /></div><div style='margin-bottom:20px;'><p>Làm bài ở nhà</p><input type='radio' name='check_hoc' class='check' id='on-home' /></div></div><div style='margin-bottom:20px;'><p>Hủy bài</p><input type='radio' name='check_hoc' class='check' id='on-huy' /></div><div style='margin-bottom:20px;'><p>Không đi thi</p><input type='radio' name='check_hoc' class='check' id='on-nghi' /></div><div><p>Mất bài, nghỉ có phép</p><input type='radio' name='check_hoc' class='check' id='on-lose' /></td></tr><tr id='lydo' style='display:none;'><td class='hidden'><span>Lý do</span></td><td><select class='input' style='height:auto;width:100%:' id='note'><option value='0'>Chọn lí do</option><?php $result1=get_all_lido();
											while($data1=mysqli_fetch_assoc($result1)) {
												echo"<option value='$data1[ID_LD]'>$data1[name]</option>";
											}
										?></select></td></tr><tr><td class='hidden'><span>Mã trợ giảng hủy bài</span></td><td><input type='password' id='ma-huy' class='input' placeholder='Mã trợ giảng, cách nhau dấu phẩy' /></td></tr><tr><th class='hidden'></th><th><span id='diem'></span></th></tr><tr><th class='hidden'><span></span></th><th><input class='submit nhap' type='submit' data-action='new' value='Nhập' style='width:100%;font-size:1.375em;' /><input class='submit nhap' type='submit' data-action='new' value='Nhập' style='position:fixed;z-index:9;right:0;bottom:20px;width:100px;' /></th></tr>");
			}
			
			function clean_chuyende() {
			    $("#diem-form-big").html('<table class="table table-3 diem-form diem-form-1"><tr style="background:#3E606F;"><th style="width:20%;"><span>Câu</span></th><th><span>Đáp án</span></th></tr></table><table class="table table-3 diem-form diem-form-2" style="margin: 0 2% 0 2%;"><tr style="background:#3E606F;"><th style="width:20%;"><span>Câu</span></th><th><span>Đáp án</span></th></tr></table><table class="table table-3 diem-form diem-form-3"><tr style="background:#3E606F;"><th style="width:20%;"><span>Câu</span></th><th><span>Đáp án</span></th></tr></table>');
//				$("#diem-chuyende").html("<tr style='background:#3E606F;'><th colspan='2'><span>Danh sách các câu</span></th><th colspan='2'><span>Điểm thành phần</span></th></tr><tr><td style='width:5%;'><span>Câu</span></td><td style='text-align:center;width:20%;'><span>Chuyên đề</span></td><td><span>Đáp án</span></td><td style='width:15%;'><span>Đáp án khác</span></td></tr><tr id='tr-last'><td colspan='2' style='text-align:center;'></td><td colspan='2' style='width:35%;'><span id='diem' style='font-weight:600;font-size:22px;'>Tổng: ... điểm</span></td></tr>");
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
						$("#list-diemdanh").html("<tr style='background:#3E606F;'><th style='width:10%' class='hidden'><span>STT</span></th><th style='width:10%:'><span>Mã</span></th><th style='width:20%;' class='hidden'><span>Học sinh</span></th><th style='width:10%:'><span>Đề</span></th><th style='width:15%;'><span>Điểm</span></th><th style='width:15%;'><input type='button' class='submit' id='loc-ko-lam' value='Lọc làm ở nhà' /></th><th style='width:20%;'><span></span></th></tr>"+result);
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

            $("#diem-form-big").delegate("table tr","click",function(e) {
                $("#diem-form-big table tr td.dap-an-td p input.point").attr("disabled","disabled");
                $(this).find("td.dap-an-td p input.point").removeAttr("disabled");
                if($(this).find("td.dap-an-td p input.point").is(":checked")) {
                    if ($(this).find("td.dap-an-td p input.point:checked").val() != 5) {
                        $(this).find("td.dap-an-td p input.point:checked").focus();
                    }
                } else {
                    $(this).find("td.dap-an-td p:eq(0) input.point").focus();
                }
            });

            $("#diem-form-big").delegate("table tr td.dap-an-td input.point","click", function(e) {
                $(this).closest("td").find("p input.point").removeAttr("disabled");
                $(this).closest("td").find("p").css("opacity","0.3");
                $(this).closest("p").css("opacity","1");
                if($(this).val()==5) {
                    $(this).closest("td").find("input.note-dapan").show();
                } else {
                    $(this).closest("td").find("input.note-dapan").hide();
                }
                $(this).closest("tr").find("td:first-child span strong").html(da_arr[$(this).closest("p").index()]);
            });

            $("#diem-form-big").delegate("table tr td.dap-an-td input.point","keyup", function(e) {
                $(this).closest("td").find("p").css({"opacity":"0.3","background":"none"});
                if($(this).val()==0) {
                    $(this).closest("p").css({"opacity":"1"});
                } else {
                    $(this).closest("p").css({"opacity":"1","background":"grey"});
                }
                if($(this).val()==5) {
                    $(this).closest("tr").find("td:last-child input.note-dapan").show();
                } else {
                    $(this).closest("tr").find("td:last-child input.note-dapan").hide();
                }
                if(e.keyCode==40) {
                    var here = $(this).closest("p").index() - 1;
                    $(this).closest("td").find("p input.point").attr("disabled","disabled");
                    $(this).closest("tr").next("tr").find("td.dap-an-td input.point").removeAttr("disabled");
                    $(this).closest("td").find("p").css({"opacity":"0.3","background":"none"});
                    if($(this).closest("p").index()==0) {
                        console.log("last");
                        $(this).closest("td").find("p:eq(4)").css({"opacity":"1"}).find("input.point").focus().prop("checked",true);
                    } else {
                        $(this).closest("p").prev("p").css({"opacity":"1","background":"grey"}).find("input.point").focus().prop("checked", true);
                        if($(this).closest("p").prev("p").find("input.point").val()==5) {
                            $(this).closest("td").find("input.note-dapan").show();
                        } else {
                            $(this).closest("td").find("input.note-dapan").hide();
                        }
                    }
                    if($(this).closest("tr").next("tr").find("td.dap-an-td p input.point").is(":checked")) {
                        $(this).closest("tr").next("tr").find("td.dap-an-td p input.point:checked").focus().prop("checked",true);
                    } else {
                        $(this).closest("tr").next("tr").find("td.dap-an-td p:eq("+here+") input.point").focus().prop("checked",true);
                    }
                    $(this).closest("tr").next("tr").find("td.dap-an-td p input.point:checked").closest("p").css("opacity", "1");
                    if($(this).closest("tr").is(":last-child")) {
                        console.log("next table");
                        var me = $(this).closest("table").next("table").find("tr:eq(1) td.dap-an-td");
                        me.find("p input.point").removeAttr("disabled");
                        if(me.find("p input.point").is(":checked")) {
                            me.find("p input.point:checked").closest("p").css("opacity", "1").find("input.point").focus().prop("checked", true);
                        } else {
                            me.find("p:eq(0)").css("opacity", "1").find("input.point").focus().prop("checked", true);
                        }
                    }
                }
                if(e.keyCode==38) {
                    var here = $(this).closest("p").index() + 1;
                    $(this).closest("td").find("p input.point").attr("disabled","disabled");
                    $(this).closest("tr").prev("tr").find("td.dap-an-td input.point").removeAttr("disabled");
                    $(this).closest("td").find("p").css({"opacity":"0.3","background":"none"});
                    if($(this).closest("p").index()==4) {
                        console.log("first");
                        $(this).closest("td").find("p:eq(0)").css({"opacity":"1","background":"grey"}).find("input.point").focus().prop("checked",true);
                    } else {
                        $(this).closest("p").next("p").css({"opacity":"1","background":"grey"}).find("input.point").focus().prop("checked", true);
                        if($(this).closest("p").next("p").find("input.point").val()==5) {
                            $(this).closest("td").find("input.note-dapan").show();
                        } else {
                            $(this).closest("td").find("input.note-dapan").hide();
                        }
                    }
                    if($(this).closest("tr").prev("tr").find("td.dap-an-td p input.point").is(":checked")) {
                        $(this).closest("tr").prev("tr").find("td.dap-an-td p input.point:checked").focus().prop("checked",true);
                    } else {
                        $(this).closest("tr").prev("tr").find("td.dap-an-td p:eq("+here+") input.point").focus().prop("checked",true);
                    }
                    $(this).closest("tr").prev("tr").find("td.dap-an-td p input.point:checked").closest("p").css("opacity", "1");
                }
//                if(e.keyCode==96) {
//                    $(this).closest("td").find("p input.point").attr("disabled","disabled");
//                    var here = $(this).closest("tr").index();
//                    var me = $(this).closest("table").next("table").find("tr:eq(1) td.dap-an-td");
//                    me.find("p input.point").removeAttr("disabled");
//                    if(me.find("p input.point").is(":checked")) {
//                        me.find("p input.point:checked").closest("p").css("opacity", "1").find("input.point").focus().prop("checked", true);
//                    } else {
//                        me.find("p:eq(0)").css("opacity", "1").find("input.point").focus().prop("checked", true);
//                    }
//                }
//                if(e.keyCode==17 || e.keyCode==47) {
//                if(e.keyCode==96) {
//                    $(this).closest("td").find("p input.point").attr("disabled","disabled");
//                    var here = $(this).closest("tr").index();
//                    var me = $(this).closest("table").prev("table").find("tr:eq(1) td.dap-an-td");
//                    me.find("p input.point").removeAttr("disabled");
//                    if(me.find("p input.point").is(":checked")) {
//                        me.find("p input.point:checked").closest("p").css("opacity", "1").find("input.point").focus().prop("checked", true);
//                    } else {
//                        me.find("p:eq(5)").css("opacity", "1").find("input.point").focus().prop("checked", true);
//                    }
//                }
                $(this).closest("tr").find("td:first-child span strong").html(da_arr[$(this).closest("td").find("p input.point:checked").closest("p").index()]);
                $(this).closest("td").find("p:eq(4)").css({"background":"none"});
//                console.log(e.keyCode);
            });

            $("#list-diemdanh").delegate("tr th input#loc-ko-lam", "click", function() {
                if(!$(this).hasClass("active")) {
                    $("table#list-diemdanh tr").each(function (index, element) {
                        if(index > 1) {
                            if ($(element).find("td.td-nghi").length) {
                                $(element).show();
                            } else {
                                $(element).hide();
                            }
                        }
                    });
                    $(this).addClass("active");
                } else {
                    $("table#list-diemdanh tr").show();
                    $(this).removeClass("active");
                }
            });

            $("#list-diemdanh").delegate("tr td input.unlock", "click", function() {
                me = $(this);
                nID = $(this).attr("data-nID");
                hsID = $(this).attr("data-hsID");
                if($.isNumeric(nID) && nID!=0 && $.isNumeric(hsID)) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "nID_unlock=" + nID + "&hsID_unlock=" + hsID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
                        success: function(result) {
                            me.closest("td").html("<span>Đã mở khóa</span>");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
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
                made = $("#ma-de").val();
				note = 0;
				diem = 0;
				is_tinh = 1;
                is_huy = 0;
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
                    is_huy = 1;
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
				how = $("#select-where option:selected").val();
				check = true;

				ajax_data="[";
				if(made!="" && $.isNumeric(buoiID) && buoiID!=0 && $.isNumeric(hsID) && (de=="B" || de=="G" || de=="Y") && $.isNumeric(loai) && ($.isNumeric(diem)) && is_tinh==1) {
					//alert("me");
                    $("#diem-form-big table.diem-form").each(function(index2,element2) {
                        $(element2).find("tr").each(function (index, element) {
                            myCD = $(element).attr("data-cd");
                            myCau = $(element).attr("data-cau");
                            if (myCD != 0 && $.isNumeric(myCD)) {
                                tppoint = $(element).find("td.dap-an-td p input.point:checked").val();
                                if (tppoint == 5) {
                                    noteDapan = $(element).find("td.dap-an-td input.note-dapan").val();
                                } else {
                                    noteDapan = "";
                                }
                                myY = 0;
                                if (action == "new") {
                                    ajax_data += '{"idCD":"' + myCD + '","diemCD":"' + tppoint + '","cau":"' + myCau + '","y":"' + myY + '","noteda":"' + Base64.encode(noteDapan) + '"},';
                                } else {
                                    sttID = $(element).attr("data-sttID");
                                    ajax_data += '{"idCD":"' + myCD + '","diemCD":"' + tppoint + '","cau":"' + myCau + '","y":"' + myY + '","noteda":"' + Base64.encode(noteDapan) + '","sttID":"' + sttID + '"},';
                                }
                            } else {
                                check = false;
                            }
                        });
                    });
				}
				ajax_data+='{"hsID":"'+hsID+'","buoiID":"'+buoiID+'","de":"'+de+'","diem":"'+diem+'","loai":"'+loai+'","note":"'+note+'","made":"'+made+'","how":"'+how+'"}';
				ajax_data+="]";
				if($("#select-ca option:selected").val()!=0 && $.isNumeric(buoiID) && $.isNumeric(hsID) && (de=="B" || de=="G" || de=="Y") && ($.isNumeric(diem) || diem=="X") && ajax_data!="" && ((loai==3 && note!=0 && $("#ma-huy").val()!="") || (loai!=3 && note==0))) {
					if(action=="new") {
						data = "data2=" + ajax_data;
					} else {
						data = "data_update2=" + ajax_data;
					}
//					alert(data);
					$.ajax({
						async: true,
						data: data,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-nhapdiem/",
						success: function(result) {
//							alert(result);
                            //get_danhsach(buoiID);
							$("#BODY").css("opacity","1");
							$("#popup-loading").fadeOut("fast");
							if(result=="dulieu"){
								alert("Dữ liệu điểm tổng chuyên đề không chính xác! Dữ liệu vẫn đc nhập nhưng hãy sửa lại!");
								$(".nhap").addClass("submit-lost");
								$(".nhap").attr("data-action","old")
										.val("Sửa");
							} else if(result=="khong") {
                                alert("Đề không có câu nào!");
                                $(".nhap").addClass("submit-lost");
                            } else if(result=="diem") {
                                alert("Điểm có định dạng không đúng!");
                                $(".nhap").addClass("submit-lost");
                            } else if(result=="loi") {
								alert("Dữ liệu không chính xác!");
								$(".nhap").addClass("submit-lost");
							} else {
							    console.log("Điểm: " + result);
                                $("#diem").html("Điểm: " + result);
                                $(".nhap").addClass("submit-done");
                                $(".nhap").attr("data-action","old")
                                    .val("Sửa");
                            }
							$("html,body").animate({scrollTop:0},250);
                            diem_danh(is_huy);
                            $(".nhap").show();
						}
					});
				} else {
					alert("Xin vui lòng nhập đầy đủ thông tin!");
					$("#BODY").css("opacity","1");
					$("#popup-loading").fadeOut("fast");
                    $(".nhap").show();
				}

				function diem_danh(is_huy) {
				    var made = $("#ma-de").val();
				    var code = $("#ma-huy").val();
				    var hsID = $("#search-hs").attr("data-hsID");
				    var caID = $("#select-ca option:selected").val();
                    var cum = $("#select-ca option:selected").attr("data-cum");
                    var ngay = $("#select-buoi option:selected").attr("data-ngay");
                    var buoiID = $("#select-buoi option:selected").val();
                    var is_hoc = 0
                    var is_tinh = 0;
                    var is_kt = 0;
                    var is_de = 1;
                    var is_du = 1;
                    var cdID = 0;
                    var caCheck = $("#ca-result").attr("data-result");
                    if($(".nhap").hasClass("nhap_new")) {
                        ajax_data = "ngay=" + ngay + "&lmID=" + 0 + "&monID=" + <?php echo $monID; ?> + "&caID=" + caID + "&cum0=" + cum + "&cdID=" + 0 + "&hsID=" + hsID + "&is_hoc=" + is_hoc + "&is_tinh=" + is_tinh + "&is_kt=" + is_kt + "&caCheck=" + caCheck + "&buoiID3=" + buoiID + "&nhap3=" + 1 + "&giay3=" + 1 + "&is_de3=" + 1 + "&is_du3=" + 1 + "&is_huy3=" + is_huy + "&is_code3=" + code + "&made3=" + made;
                    } else {
                        var sttID = $(".nhap").attr("data-sttID");
                        ajax_data = "ngay=" + ngay + "&hsID3=" + hsID + "&is_hoc3=" + is_hoc + "&is_tinh3=" + is_tinh + "&is_kt3=" + is_kt + "&sttID3=" + sttID + "&cum0=" + $("#select-ca option:selected").attr("data-cum") + "&lmID=" + 0 +"&monID=" + <?php echo $monID; ?> + "&buoiID3=" + buoiID + "&nhap3=" + 1 + "&giay3=" + 1 + "&is_de3=" + 1 + "&is_du3=" + 1 + "&is_huy3=" + is_huy + "&is_code3=" + code + "&made3=" + made;
                    }
                    if($.isNumeric(caID) && $.isNumeric(hsID) && (0==is_hoc || 1==is_hoc) && (0==is_tinh || 1==is_tinh) && (1==is_kt || 0==is_kt) && (0==caCheck || 1==caCheck) && 0 != caID && 0 != hsID && (is_huy==0 || (is_huy==1 && code!="none" && code!=""))) {
//                        alert(ajax_data);
                        $.ajax({
                            async: true,
                            data: ajax_data,
                            type: "post",
                            url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
                            success: function (a) {
                                "ok" != a ? alert(a) : $("#BODY").css("opacity", "1"), $("#popup-loading").fadeOut("fast"), $("#nhap").addClass("submit-done")
                            }
                        });
                    } else {
                        console.log("Xin hãy nhập đầy đủ thông tin và chính xác!" + caID + "-" + hsID + "-" + is_hoc + "-" + is_tinh + "-" + is_kt + "-" + caCheck + "-" + is_huy + "-" + code);
                        $("#BODY").css("opacity", "1");
                        $("#popup-loading").fadeOut("fast");
                    }
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
                	<h2>NHẬP ĐIỂM TRẮC NGHIỆM</h2>
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
													echo"<option value='$data5[ID_BUOI]' data-ngay='$data5[ngay]'>".format_dateup($data5["ngay"])."</option>";
												}
											?>
                                       	</select>
                                  	</td>
                              	</tr>
                                <tr>
                                    <td class='hidden'><span>Ca (có thể trống)</span></td>
                                    <td>
                                        <select class="input" style="height:auto;width:100%;" id="select-ca">
                                            <option value="0">Chọn ca hiện hành</option>
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
                                    <td class="hidden"><span>Ghi dữ liệu</span></td>
                                    <td>
                                        <select class="input" style="height:auto;width:100%;" id="select-where">
                                            <option value="0">Chỉ trên trang Bgo</option>
                                            <option value="1">Chỉ trên trang Luyện thi</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class='hidden'><span>Cập nhật điểm giữa Bgo và Luyện thi</span></td>
                                    <td><input type="submit" class="submit" value="Bgo->Luyện thi" onclick="" style="width:45%;float:left;" id="cap-nhat" /><input type="submit" class="submit" value="Luyện thi->Bgo" onclick="" style="width:45%;float:left;" id="cap-nhat2" /></td>
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
                                	<td class='hidden'><span>Kết quả thách đấu - ngôi sao</span></td>
                                	<td><input type="submit" class="submit" value="KQ Thách đấu" onclick="" style="width:45%;float:left;" id="kq-thachdau" /><input type="submit" class="submit" value="KQ Ngôi sao" onclick="" style="width:45%;float:left;" id="kq-ngoisao" /></td>
                                </tr>
                            </table>
                            <table class="table table-2" id="info-hs">
                           		<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin học sinh</span></th></tr>
                                <tr><td colspan="2" style="text-align:center;"><span>Vui lòng chọn đủ thông tin bên trái!</span></td></tr>
                            </table>
                            <div style="margin-top: 25px;clear:both;width:100%;" id="diem-form-big">
<!--                                <table class="table table-3 diem-form">-->
<!--                                    <tr style="background:#3E606F;">-->
<!--                                        <th style="width:20%;"><span>Câu</span></th>-->
<!--                                        <th><span>Đáp án</span></th>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <td><span>1 - <strong>D</strong></span></td>-->
<!--                                        <td><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p><input type='text' style="margin-top: 20px;" class='input note-dapan' placeholder='Đáp án khác' /></td>-->
<!--                                    </tr>-->
<!--                                </table>-->
<!--                                <table class="table table-3 diem-form" style="margin:0 2% 0 2%;">-->
<!--                                    <tr style="background:#3E606F;">-->
<!--                                        <th style="width:20%;"><span>Câu</span></th>-->
<!--                                        <th><span>Đáp án</span></th>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <td><span>1 - <strong>D</strong></span></td>-->
<!--                                        <td><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p><input type='text' style="margin-top: 20px;" class='input note-dapan' placeholder='Đáp án khác' /></td>-->
<!--                                    </tr>-->
<!--                                </table>-->
<!--                                <table class="table table-3 diem-form">-->
<!--                                    <tr style="background:#3E606F;">-->
<!--                                        <th style="width:20%;"><span>Câu</span></th>-->
<!--                                        <th><span>Đáp án</span></th>-->
<!--                                    </tr>-->
<!--                                    <tr>-->
<!--                                        <td><span>1 - <strong>D</strong></span></td>-->
<!--                                        <td><p><input type='radio' value='1' class='check point' name='radio-point-"+i+"' /><span>A</span></p><p><input type='radio' value='2' class='check point' name='radio-point-"+i+"' /><span>B</span></p><p><input type='radio' value='3' class='check point' name='radio-point-"+i+"' /><span>C</span></p><p><input type='radio' value='4' class='check point' name='radio-point-"+i+"' /><span>D</span></p><p><input type='radio' value='5' class='check point' name='radio-point-"+i+"' /><span>E</span></p><p><input type='radio' value='6' class='check point' name='radio-point-"+i+"' /><span>F</span></p><input type='text' style="margin-top: 20px;" class='input note-dapan' placeholder='Đáp án khác' /></td>-->
<!--                                    </tr>-->
<!--                                </table>-->
                            </div>
<!--                            <table class="table" style="margin-top:25px;" id="diem-chuyende">-->
<!--                            </table>-->
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
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
	$ip=$_SERVER['REMOTE_ADDR'];
	$mau="#FFF";
    $result0=get_info_tro_giang($id);
    $data0=mysqli_fetch_assoc($result0);

	if($data0["fb"]=="X" || $data0["fb"]=="") {
		$face="#";
	} else {
		$face=$data0["fb"];
	}
    if(isset($_GET["ngay"])) {
        $ngay=$_GET["ngay"];
    } else {
        $ngay=date("Y-m-d");
    }
    if(isset($_GET["maso"]) && valid_maso($_GET["maso"])) {
        $maso=$_GET["maso"];
    } else {
        $maso="";
    }
    $thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
    $now=date("d/m/Y");
    $birth=date("d/m");
    $me="";
    $result1=get_all_lop();
    while($data1=mysqli_fetch_assoc($result0)) {
        $nam=$data1["name"];
        $pre_id = get_next_hs_lop($data1["ID_LOP"]);
        $me .= format_maso($pre_id, $data1["name"]) . ", ";
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>HỒ SƠ</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/trogiang/css/hover.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/imgareaselect-animated.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/trogiang/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:16px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:6em;color:yellow;}#chart-li1 ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}#chart-li1 ul li > div p {color:#FFF;font-size:16px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
			.fa {font-size:150%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){
                $("#select-hs").change(function() {
                    var me = $(this).val();
                    if(me==0) {
                        $("#select-lop").hide();
                        $("#add-cmt").show();
                    } else if(me==1) {
                        $("#add-cmt").hide();
                        $("#select-lop").show();
                    }
                });
                $("#add-cmt").click(function () {
                    pre_lop = $(".add-new").attr("data-prelop");
                    if(pre_lop!="") {
                        $(this).val(pre_lop);
                    } else {
                        $(this).val("");
                    }
                });

                $("#add-cmt").keyup(function() {
                    value = $(this).val().trim();
                    if(value.length == 4) {
                        $(".add-new").attr("data-prelop",value);
                    }
                    if(value!="" && value!=" " && value!="%" && value!="_" && value.length==7) {
                        get_hoc_sinh(value);
                    }
                });
                function get_hoc_sinh(cmt) {
//                    $("#popup-loading").fadeIn("fast");
//                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "cmt=" + cmt,
                        type: "post",
                        url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                        success: function(result) {
                            if(result!="none") {
                                obj = jQuery.parseJSON(result);
                                $("#select-hs option:first-child").prop("selected",true);
                                $("#select-lop").hide();
                                $("#add-cmt").show().val(obj.cmt);
                                $("#add-van").val(obj.van);
                                $("#add-name").val(obj.fullname);
                                $("#add-birth").val(obj.birth);
                                if(obj.hot == 1) {
                                    $("input.check-hot").prop("checked",true);
                                } else {
                                    $("input.check-hot").prop("checked",false);
                                }
                                $("input.check-hot").attr("data-hsID",obj.hsID);
                                if(obj.gender==1) {
                                    $("#add-nam").prop("checked",true);
                                }
                                if(obj.gender==0) {
                                    $("#add-nu").prop("checked",true);
                                }
                                $("#add-taikhoan").html(obj.taikhoan);
                                $("#add-thuong").attr("href","http://localhost/www/TDUONG/trogiang/thuong/"+obj.hsID+"/");
                                $("#add-phat").attr("href","http://localhost/www/TDUONG/trogiang/phat/"+obj.hsID+"/");
                                $("#add-face").val(obj.face);
                                $("#link-face").attr("href",obj.face);
                                $("#add-truong").val(obj.name_truong).attr("data-truong",obj.truong);
                                /*$("#add-truong option").each(function(index, element) {
                                 if($(element).val()==obj.truong) {
                                 $(element).prop("selected",true);
                                 }
                                 });*/
                                $("#add-sdt").val(obj.sdt);
                                $("#add-name-bo").val(obj.name_bo);
                                $("#add-job-bo").val(obj.job_bo);
                                $("#add-face-bo").val(obj.face_bo);
                                $("#add-sdt-bo").val(obj.sdt_bo);
                                $("#add-mail-bo").val(obj.mail_bo);
                                $("#add-name-me").val(obj.name_me);
                                $("#add-job-me").val(obj.job_me);
                                $("#add-face-me").val(obj.face_me);
                                $("#add-sdt-me").val(obj.sdt_me);
                                $("#add-mail-me").val(obj.mail_me);
                                if(obj.check_sdt==1) {
                                    $("#check-sdt").prop("checked",true);
                                } else {
                                    $("#check-sdt").prop("checked",false);
                                }
                                if(obj.check_sdt_bo==1) {
                                    $("#check-sdt-bo").prop("checked",true);
                                } else {
                                    $("#check-sdt-bo").prop("checked",false);
                                }
                                if(obj.check_sdt_me==1) {
                                    $("#check-sdt-me").prop("checked",true);
                                } else {
                                    $("#check-sdt-me").prop("checked",false);
                                }
                                if(obj.check_hop==1) {
                                    $("#check-hop").prop("checked",true);
                                } else {
                                    $("#check-hop").prop("checked",false);
                                }
                                $(".add-new").attr("data-hsID", obj.hsID)
                                    .val("Sửa");
                                get_info_mon(obj.hsID);
                                get_hs_note(obj.hsID);
                            } else {
                                clean_data();
                            }
//                            $("#BODY").css("opacity","1");
//                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                }

                function clean_data() {
                    $("#add-truong").val("").attr("data-truong",0);
                    $("#info-hs tr").each(function(index, element) {
                        if(index>0) {
                            $(element).find("td input.input").val("");
                            $(element).find("td input.check").prop("checked",false);
                        }
                    });
                    get_new_mon();
                    $("input.check-hot").attr("data-hsID", "0").prop("checked",false);
                    $(".add-new").attr("data-hsID", "0").val("Nhập");
                    $("table#info-note tr.tr-note").remove();
                }

                function get_new_mon() {
                    var content = "<tr style='background:#3E606F;'><th colspan='3'><span>Thông tin môn học</span></th></tr><?php $cum_sl=0;$result=get_all_lop_mon(); while($data=mysqli_fetch_assoc($result)) {echo"<tr class='mon-big' data-monID='$data[ID_LM]'><td style='width:25%;'><span class='info-$data[ID_LM]'></span></td><td style='width:25%;'><span>$data[name]</span></td><td style='width:50%;'><span class='add-mon'><i class='fa fa-toggle-off' data-monID='$data[ID_LM]'></i></span></td></tr>";$result3=get_all_cum($data["ID_LM"],$data["ID_MON"]);$dem=1;while($data3=mysqli_fetch_assoc($result3)) {echo"<tr class='mon-lich mon-lich_$data[ID_LM]'><td></td><td><span>Buổi $dem</span></td><td><select class='input chose-ca' style='height:auto;width:100%'><option value='0'>Chọn ca</option>";$result2=get_cahoc_cum_lop($data3["ID_CUM"], $data["ID_LM"], $data["ID_MON"]);while($data2=mysqli_fetch_assoc($result2)) {echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]'>".$thu_string[$data2["thu"]-1].", ca $data2[gio], sĩ số ".get_num_hs_ca_codinh($data2["ID_CA"])."</option>";}echo"</select></td></tr>";$dem++;}echo"<tr class='mon-lich mon-lich_$data[ID_LM]'><td colspan='2'><span>Lịch thi cuối tuần</span></td><td><select class='input chose-ca' style='height:auto;width:100%'><option value='0'>Chọn ca</option>";$result2=get_cakt_mon($data["ID_MON"]);while($data2=mysqli_fetch_assoc($result2)) {echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]'>".$thu_string[$data2["thu"]-1].", ca $data2[gio], sĩ số ".get_num_hs_ca_codinh($data2["ID_CA"])."</option>";}echo"</select></td></tr>";}?>";
                    $("#info-mon").html(content);
                }

                $("#info-hs tr td input.input").keyup(function(e) {
                    if(e.keyCode==40) {
                        $(this).closest("tr").next("tr").find("td input.input").focus();
                    }
                    if(e.keyCode==38) {
                        $(this).closest("tr").prev("tr").find("td input.input").focus();
                    }
                });

                function get_info_mon(hsID) {
                    $.ajax({
                        async: true,
                        data: "hsID=" + hsID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                        success: function(result) {
                            $("#info-mon").html(result);
                            $("table#info-mon tr.mon-big td input.date-out").datepicker({
                                dateFormat: 'dd/mm/yy',
                                defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
                            });
                        }
                    });
                }

                $("#info-mon").delegate("tr td span.add-mon i.fa-toggle-off","click",function() {
                    $(this).attr("class","fa fa-toggle-on");
                    monID = $(this).attr("data-monID");
                    $(".mon-lich_"+monID).fadeIn("fast");
                });

                $("#info-mon").delegate("tr td span.add-mon i.fa-toggle-on","click",function() {
                    $(this).attr("class","fa fa-toggle-off");
                    monID = $(this).attr("data-monID");
                    del_tr = $(this).closest("tr");
                    del_tr.find("td span.info-"+monID).html("");
                    $(".mon-lich_"+monID).fadeOut("fast");
                });

                $("#info-mon").delegate("tr.tien-hoc td span i.btn-edit-price","click",function() {
                    var me = $(this);
                    $(this).closest("span").find("input.edit-price").show();
                    $("#info-mon tr.tien-hoc td span input.edit-price").typeWatch({
                        captureLength: 1,
                        callback: function (value) {
                            var lmID = $(this).attr("data-lmID");
                            var thang = $(this).attr("data-thang");
                            var hsID = $(".add-new").attr("data-hsID");
                            if($.isNumeric(lmID) && lmID!=0 && $.isNumeric(hsID) && hsID!=0) {
                                $.ajax({
                                    async: false,
                                    data: "hsID=" + hsID + "&lmID=" + lmID + "&thang=" + thang + "&price=" + value,
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/trogiang/xuly-tienhoc/",
                                    success: function(result) {
                                        me.removeClass("fa-pencil").addClass("fa-check");
                                        console.log("Ok!");
                                    }
                                });
                            } else {
                                console.log("Lỗi dữ liệu");
                            }
                        }
                    });
                });

                $("#info-mon").delegate("tr.tien-hoc td a#lich-su-mk","click",function() {
                    hsID = $(this).attr("data-hsID");
                    if($.isNumeric(hsID) && hsID!=0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "hsID5=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function(result) {
                                $("#pop-title").html("Lịch sử đổi mật khẩu");
                                $("#pop-content").html(result);
                                $("#popup-up").fadeIn("fast");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    }
                });

                $("#info-mon").delegate("tr.tien-hoc td a#lich-su-login","click",function() {
                    hsID = $(this).attr("data-hsID");
                    if($.isNumeric(hsID) && hsID!=0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "hsID3=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function(result) {
                                $("#pop-title").html("Lịch sử đăng nhập");
                                $("#pop-content").html(result);
                                $("#popup-up").fadeIn("fast");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    }
                });

                $("#info-mon").delegate("tr td a.lich-su-doi-ca","click",function() {
                    lmID = $(this).attr("data-monID");
                    hsID = $(this).attr("data-hsID");
                    if($.isNumeric(lmID) && lmID!=0 && $.isNumeric(hsID) && hsID!=0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity","0.3");
                        $.ajax({
                            async: true,
                            data: "hsID4=" + hsID + "&lmID4=" + lmID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function(result) {
                                $("#pop-title").html("Lịch sử đổi ca");
                                $("#pop-content").html(result);
                                $("#popup-up").fadeIn("fast");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    }
                });

                $("#info-mon").delegate("tr.tien-hoc td span i.xem_lich","click",function() {
                    date_arr = $(this).attr("data-lich");
                    temp = date_arr.split(",");
                    $("#popup-check #lich-check").multiDatesPicker({
                        defaultDate: new Date(temp[0])
                    });
                    for(i=0; i<temp.length;i++) {
                        $("#popup-check #lich-check").multiDatesPicker("addDates", [new Date(temp[i])]);
                    }
                    $("#popup-check").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                });


                $("#info-hs").delegate("tr td input.check-phone","click",function() {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    hsID = $(".add-new").attr("data-hsID");
                    sdt = $(this).closest("td").next("td").find("input.input").val();
                    if($.isNumeric(hsID) && hsID!=0 && $.isNumeric(sdt) && sdt!="") {
                        $.ajax({
                            async: true,
                            data: "hsID6=" + hsID + "&sdt=" + sdt,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-thongtin/",
                            success: function (result) {
                                $("#popup-loading").fadeOut("fast");
                                $("#BODY").css("opacity","1");
                            }
                        });
                    } else {
                        alert("Lỗi dữ liệu!");
                        $("#popup-loading").fadeOut("fast");
                        $("#BODY").css("opacity","1");
                    }
                });


                $(".add-new").click(function() {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    hsID = $(this).attr("data-hsID");
                    //pre = $(this).attr("data-pre");
                    lop = $("#select-lop option:selected").val();
                    cmt = $("#add-cmt").val();
                    van = $("#add-van").val();
                    name = $("#add-name").val();
                    birth = $("#add-birth").val();
                    gender = 0;
                    if($("#add-nam").is(":checked")) {
                        gender = 1;
                    }
                    if($("#add-nu").is(":checked")) {
                        gender = 0;
                    }
                    pass = $("#add-pass").val();
                    face = Base64.encode($("#add-face").val());
                    //truong = $("#add-truong option:selected").val();
                    truong = $("#add-truong").attr("data-truong");
                    check_sdt = 0;
                    check_sdt_bo = 0;
                    check_sdt_me = 0;
                    check_hop = 0;
                    sdt = $("#add-sdt").val();
                    name_bo = $("#add-name-bo").val();
                    job_bo = $("#add-job-bo").val();
                    face_bo = $("#add-face-bo").val();
                    sdt_bo = $("#add-sdt-bo").val();
                    mail_bo = $("#add-mail-bo").val();
                    name_me = $("#add-name-me").val();
                    job_me = $("#add-job-me").val();
                    face_me = $("#add-face-me").val();
                    sdt_me = $("#add-sdt-me").val();
                    mail_me = $("#add-mail-me").val();
                    if($("#check-sdt").is(":checked")) {
                        check_sdt = 1;
                    }
                    if($("#check-hop").is(":checked")) {
                        check_hop = 1;
                    }
                    if($("#check-sdt-bo").is(":checked")) {
                        check_sdt_bo = 1;
                    }
                    if($("#check-sdt-me").is(":checked")) {
                        check_sdt_me = 1;
                    }
                    ajax_data="[";
                    $("#MAIN #main-mid .status table#info-mon tr.mon-big").each(function(index, element) {
                        lmID = $(element).attr("data-monID");
                        date_in = $(element).find("td:first-child input.date-in").val();
                        if(!date_in) {
                            date_in="";
                        }
                        date_out = $(element).find("td:first-child input.date-out").val();
                        if(!date_out) {
                            date_out="";
                        }
                        mon_i = $(element).find("td:last-child span.add-mon i");
                        discount = $(element).closest("table").find("tr.tien-hoc_"+lmID+" td:first-child input.giam-gia").val();
                        if(!discount) {
                            discount=0;
                        }
                        if(hsID!=0) {
                            remove = 0;
                            if(mon_i.attr("class") == "fa fa-toggle-on") {
                                remove = 0;
                            }
                            if(mon_i.attr("class") == "fa fa-toggle-off") {
                                remove = 1;
                            }
                            has_ca = false;
                            $(element).closest("table").find("tr.mon-lich_"+lmID).each(function(index2, element2) {
                                ca_on = $(element2).find("td select.chose-ca option:selected");
                                caID = ca_on.val();
                                if(caID!=0 && caID!="" && caID && $.isNumeric(caID)) {
                                    cum = ca_on.attr("data-cum");
                                    ajax_data+='{"lmID":"'+lmID+'","date_in":"'+date_in+'","date_out":"'+date_out+'","discount":"'+discount+'","caID":"'+caID+'","cum":"'+cum+'","remove":"'+remove+'"},';
                                    has_ca = true;
                                }
                            });
                            if(!has_ca) {
                                ajax_data+='{"lmID":"'+lmID+'","date_in":"'+date_in+'","date_out":"'+date_out+'","discount":"'+discount+'","remove":"'+remove+'"},';
                            }
                        } else {
                            if(mon_i.attr("class") == "fa fa-toggle-on") {
                                has_ca = false;
                                $(element).closest("table").find("tr.mon-lich_"+lmID).each(function(index2, element2) {
                                    ca_on = $(element2).find("td select.chose-ca option:selected");
                                    caID = ca_on.val();
                                    if(caID!=0 && caID!="" && caID && $.isNumeric(caID)) {
                                        cum = ca_on.attr("data-cum");
                                        ajax_data+='{"lmID":"'+lmID+'","date_in":"'+date_in+'","date_out":"'+date_out+'","discount":"'+discount+'","caID":"'+caID+'","cum":"'+cum+'"},';
                                        has_ca = true;
                                    }
                                });
                                if(!has_ca) {
                                    ajax_data+='{"lmID":"'+lmID+'","date_in":"'+date_in+'","date_out":"'+date_out+'","discount":"'+discount+'"},';
                                }
                            }
                        }
                    });
                    ajax_data+='{"lop":"'+lop+'","cmt":"'+cmt+'","van":"'+van+'","name":"'+name+'","birth":"'+birth+'","gender":"'+gender+'","pass":"'+pass+'","face":"'+face+'","truong":"'+truong+'","sdt":"'+sdt+'","name_bo":"'+name_bo+'","job_bo":"'+job_bo+'","face_bo":"'+face_bo+'","sdt_bo":"'+sdt_bo+'","mail_bo":"'+mail_bo+'","name_me":"'+name_me+'","job_me":"'+job_me+'","face_me":"'+face_me+'","sdt_me":"'+sdt_me+'","mail_me":"'+mail_me+'","check_sdt":"'+check_sdt+'","check_hop":"'+check_hop+'","check_sdt_bo":"'+check_sdt_bo+'","check_sdt_me":"'+check_sdt_me+'","hsID":"'+hsID+'"}';
                    ajax_data+="]";
                    if((hsID!=0 || (hsID==0 && lop!=0)) && name!="" && birth!="" && (gender==0 || gender==1) && ajax_data!="") {
                        //alert(ajax_data);
                        $.ajax({
                            async: true,
                            data: "data=" + ajax_data,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function(result) {
                                alert(result);
                                location.reload();
                                $(".add-new").val(result);
                                $("#BODY").css("opacity","1");
                                $("#popup-loading").fadeOut("fast");
                            }
                        });
                    } else {
                        alert("Xin hãy nhập đầy đủ thông tin và chính xác!");
                        $("#BODY").css("opacity","1");
                        $("#popup-loading").fadeOut("fast");
                    }
                });



                $("#reset-mk").click(function() {
                    $("#add-pass").val($("#add-cmt").val());
                });

                $(".popup-close").click(function() {
                    $(".popup").fadeOut("fast")
                    $("#BODY").css("opacity", "1");
                });

                $("input.check-hot").change(function() {
                    var hsID = $(this).attr("data-hsID");
                    if($(this).is(":checked")) {
                        is_hot = 1;
                    } else {
                        is_hot = 0;
                    }
                    if(is_hot==1 || is_hot==0) {
                        $.ajax({
                            async: true,
                            data: "is_hot=" + is_hot + "&hsID_hot=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function (result) {
                                console.log(result);
                            }
                        });
                    }
                });

                function get_hs_note(hsID) {
                    if($.isNumeric(hsID) && hsID != 0) {
                        $.ajax({
                            async: true,
                            data: "hsID_note=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function (result) {
                                $("table#info-note tr.tr-note").remove();
                                $("table#info-note").append(result);
                                var dem = 0;
                                $("table#info-note tr.tr-note").each(function(index,element) {
                                    if($(element).find("td:first span").attr("data-ready") == 0) {
                                        dem++;
                                    }
                                });
                                $("table#info-note tr.tr-note td textarea").each(function(index,element) {
                                    $(element).outerHeight(this.scrollHeight);
                                });
                                if(dem>0) {
                                    $("span.note-count").html("<b>" + dem + "</b>");
                                } else {
                                    $("span.note-count").html("").remove();
                                }
                                if($("textarea#note-dac-biet").val()=="") {
                                    $("textarea#note-dac-biet").hide();
                                }
                                $("table#info-note textarea.note-day").typeWatch({
                                    captureLength: 3,
                                    callback: function (value) {
                                        $(this).outerHeight($(this).height()).outerHeight(this.scrollHeight);
                                        del_tr = $(this).closest("tr");
                                        var ngay = del_tr.find("td:first-child span").attr("data-date");
                                        hsID = $(this).attr("data-hsID");
//                        note = $(this).val();
                                        note = value;
                                        note = note.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                                        if ($.isNumeric(hsID) && hsID != 0 && ngay!="") {
                                            if(ngay == 0) {
                                                $.ajax({
                                                    async: true,
                                                    data: "hsID1=" + hsID + "&note1=" + note,
                                                    type: "post",
                                                    url: "http://localhost/www/TDUONG/trogiang/xuly-nghihoc/",
                                                    success: function (result) {
                                                        console.log("Đã lưu");
                                                    }
                                                });
                                            } else {
                                                $.ajax({
                                                    async: true,
                                                    data: "hsID1=" + hsID + "&ngay1=" + ngay + "&note2=" + note,
                                                    type: "post",
                                                    url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                                                    success: function (result) {
                                                        del_tr.find("td:last-child input").attr("data-nID",result);
                                                        console.log("Đã lưu: " + result);
                                                    }
                                                });
                                            }
                                        } else {
                                            console.log("Lỗi dữ liệu!");
                                        }
                                    }
                                });
//                            console.log(dem);
                            }
                        });
                    }
                }

                $("table#info-note").delegate("textarea#note-dac-biet","click",function() {
                    $("table#info-note textarea#note-dac-biet").typeWatch({
                        captureLength: 3,
                        callback: function (value) {
                            var me = $(this);
                            var hsID = $(".add-new").attr("data-hsID");
                            var nID = $(this).attr("data-nID");
                            if(!$.isNumeric(nID)) {nID = 0;}
//                        var note = $(this).val();
                            var note = value;
                            note = note.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                            if ($.isNumeric(hsID) && hsID!=0 && $.isNumeric(nID) && note!="" && note!="none") {
                                $.ajax({
                                    async: false,
                                    data: "content2=" + note + "&hsID2=" + hsID + "&nID2=" + nID,
                                    type: "post",
                                    url: "http://localhost/www/TDUONG/trogiang/xuly-mon/",
                                    success: function(result) {
                                        me.attr("data-nID",result);
                                        console.log("Note Special done!");
                                    }
                                });
                            }
                        }
                    });
                });

                $("table#info-note").delegate("textarea.note-day","click",function() {
                    $("table#info-note textarea.note-day").typeWatch({
                        captureLength: 3,
                        callback: function (value) {
                            $(this).outerHeight($(this).height()).outerHeight(this.scrollHeight);
                            del_tr = $(this).closest("tr");
                            var ngay = del_tr.find("td:first-child span").attr("data-date");
                            hsID = $(this).attr("data-hsID");
//                        note = $(this).val();
                            note = value;
                            note = note.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
                            if ($.isNumeric(hsID) && hsID != 0 && ngay!="") {
                                if(ngay == 0) {
                                    $.ajax({
                                        async: true,
                                        data: "hsID1=" + hsID + "&note1=" + note,
                                        type: "post",
                                        url: "http://localhost/www/TDUONG/trogiang/xuly-nghihoc/",
                                        success: function (result) {
                                            console.log("Đã lưu");
                                        }
                                    });
                                } else {
                                    $.ajax({
                                        async: true,
                                        data: "hsID1=" + hsID + "&ngay1=" + ngay + "&note2=" + note,
                                        type: "post",
                                        url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                                        success: function (result) {
                                            del_tr.find("td:last-child input").attr("data-nID",result);
                                            console.log("Đã lưu: " + result);
                                        }
                                    });
                                }
                            } else {
                                console.log("Lỗi dữ liệu!");
                            }
                        }
                    });
                });

                $("table#info-note").delegate("input.check-hot","click",function() {
                    var me = $(this);
                    var hsID = $(this).attr("data-hsID");
                    if($(this).hasClass("is_chuy")) {
                        is_hot = 0;
                    } else {
                        is_hot = 1;
                    }
                    if(is_hot==1 || is_hot==0) {
                        me.val("...");
                        $.ajax({
                            async: true,
                            data: "is_hot=" + is_hot + "&hsID_hot=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function (result) {
                                console.log(result);
                                if(is_hot==1) {
                                    me.addClass("is_chuy").removeAttr("style").css("background","red");
                                } else {
                                    me.removeClass("is_chuy").css({"background":"cyan","border":"none","opacity":"0.4"});
                                }
                                me.val("OK");
                            }
                        });
                    }
                });

                $("table#info-note").delegate("input.check-chuy","click",function() {
                    var me = $(this);
                    var nID = $(this).attr("data-nID");
                    if($(this).hasClass("is_chuy")) {
                        is_hot = 0;
                    } else {
                        is_hot = 1;
                    }
                    if(is_hot==1 || is_hot==0) {
                        me.val("...");
                        $.ajax({
                            async: true,
                            data: "is_hot=" + is_hot + "&nID_hot=" + nID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-hocsinh/",
                            success: function (result) {
                                console.log(result);
                                if(is_hot==1) {
                                    me.addClass("is_chuy").removeAttr("style").css("background","red");
                                } else {
                                    me.removeClass("is_chuy").css({"background":"cyan","border":"none","opacity":"0.4"});
                                }
                                me.val("OK");
                            }
                        });
                    }
                });

                $("table#info-note").delegate("tr td span.span-explain","click",function() {
                    if($(this).hasClass("active")) {
                        $(this).removeClass("active");
                        $("table#info-note tr td span.span-explain > nav").hide();
                    } else {
                        $(this).addClass("active");
                        $(this).find("nav").fadeIn("fast");
                    }
                });

                $("table#info-note").delegate("tr td input#them-note","click",function() {
                    $("<tr><td><span data-date='' data-ready='0'></span><input type='text' class='input note-ngay' placeholder='<?php echo date("d/m"); ?>' /></td><td colspan='2'><textarea style='resize:none;box-sizing: border-box;overflow: hidden;' placeholder='Tự động lưu' rows='1' data-hsID='" + $(this).attr("data-hsID") + "' class='input note-day'></textarea></td></td></tr>").insertBefore("table#info-note tr:last-child");
                });

                $("table#info-note").delegate("tr:last-child td input#them-note","click",function() {
                    $("table#info-note tr td:first-child input.note-ngay").datepicker({
                        dateFormat: 'dd/mm',
                        defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
                    });
                });

                $("table#info-note").delegate("tr td:first-child input.note-ngay","change",function() {
                    var ngay = $(this).val();
                    if(ngay!="") {
                        var temp = ngay.split("/");
                        $(this).closest("td").find("span").attr("data-date","<?php echo date("Y"); ?>-" + temp[1] + "-" + temp[0]);
                    }
                });

                $("table#info-note").delegate("tr td:last-child p.note-phep","click",function() {
                    me = $(this);
                    hsID = $(this).attr("data-hsID");
                    cumID = $(this).attr("data-cumID");
                    is_phep = $(this).attr("data-isphep");
                    lmID = $(this).attr("data-lmID");
                    monID = $(this).attr("data-monID");
                    if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && (is_phep==0 || is_phep==1)) {
                        $.ajax({
                            async: false,
                            data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + lmID + "&monID=" + monID + "&is_bao=1",
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-diemdanh/",
                            success: function(result) {
                                console.log(result);
                                if(is_phep==1) {
                                    me.removeAttr("style").attr("data-isphep",0).removeClass("xau").addClass("tot");
                                } else {
                                    me.css("text-decoration","line-through").attr("data-isphep",1).removeClass("tot").addClass("xau");
                                }
                            }
                        });
                    }
                });

                $("table#info-note").delegate("tr td:last-child p.note-tin","click",function() {
                    me = $(this);
                    var sttID = $(this).attr("data-sttID");
                    var nhan = $(this).attr("data-nhan");
                    if($.isNumeric(sttID) && sttID!=0 && (nhan==0 || nhan==1)) {
                        $.ajax({
                            async: true,
                            data: "sttID1=" + sttID + "&nhan=" + nhan,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-nghihoc/",
                            success: function (result) {
                                console.log(result);
                                if(nhan==1) {
                                    me.removeAttr("style").attr("data-nhan",0).removeClass("xau").addClass("tot");
                                } else {
                                    me.css("text-decoration","line-through").attr("data-nhan",1).removeClass("tot").addClass("xau");
                                }
                            }
                        });
                    }
                });

                $("table#info-note").delegate("tr td:last-child p.note-xac-nhan","click",function() {
                    me = $(this);
                    var sttID = $(this).attr("data-sttID");
                    var xacnhan = $(this).attr("data-nhan");
                    if($.isNumeric(sttID) && sttID!=0 && (xacnhan==0 || xacnhan==1)) {
                        $.ajax({
                            async: true,
                            data: "sttID1=" + sttID + "&xacnhan=" + xacnhan,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-nghihoc/",
                            success: function (result) {
                                console.log(result);
                                if(xacnhan==1) {
                                    me.removeAttr("style").attr("data-nhan",0).removeClass("xau").addClass("tot");
                                } else {
                                    me.css("text-decoration","line-through").attr("data-nhan",1).removeClass("tot").addClass("xau");
                                }
                            }
                        });
                    }
                });

                $("table#info-note").delegate("tr th#open-dac-biet","click",function() {
                    $("textarea#note-dac-biet").fadeIn("fast");
                });

                $("table#info-note").delegate("tr td span#note-done","click",function() {
                    var hsID = $(this).attr("data-hsID");
                    if($.isNumeric(hsID) && hsID != 0) {
                        $.ajax({
                            async: true,
                            data: "hsID_special=" + hsID,
                            type: "post",
                            url: "http://localhost/www/TDUONG/trogiang/xuly-mon/",
                            success: function (result) {
                                $("textarea#note-dac-biet").val("").fadeOut("fast");
                                get_hs_note(hsID);
                            }
                        });
                    }
                });

                $("#special-note").click(function () {
                    $(".popup").fadeOut("fast");
                    $("#text-add","#sign-add","#hs-add").val("");
                    $("#hs-add").val($("#add-cmt").val());
                    $("#popup-note").fadeIn("fast");
                });



            });
		</script>
       
	</head>

    <body>

        <div class="popup" id="popup-loading">
            <p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
        </div>

        <div class="popup" id="popup-up" style="width:70%;left:10%;padding:2.5%;">
            <div class="popup-close"><i class="fa fa-close"></i></div>
            <p style="margin-bottom:10px;margin-left:-7.5%;" id="pop-title"></p>
            <div id="pop-content" style="width:100%;text-align:left;"></div>
        </div>

        <div class="popup" id="popup-check" style="width:70%;left:10%;padding:2.5%;">
            <div class="popup-close"><i class="fa fa-close"></i></div>
            <p style="margin-bottom:10px;margin-left:-7.5%;">Kiểm tra tiền học</p>
            <span id="lich-check" style=""></span>
        </div>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            
            	<div class="main-div back animated bounceInUp" id="main-top">
                    <div id="main-person">
                        <h1 style="line-height:98px;">THÔNG TIN HỌC SINH</h1>
                        <div class="clear"></div>
                    </div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                        <a href="http://localhost/www/TDUONG/trogiang/ho-so/" title="Hồ sơ cá nhân">
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                    <!--<div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>-->
                </div>

                <div class="main-div animated bounceInUp">
                    <ul>
                        <li id="chart-li1" class="li-3">
                            <div class="main-2 back"><h3>Thông tin học sinh</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div style="width:35%;">
                                        <select class="input" id="select-hs">
                                            <option value="0" selected="selected">HS cũ</option>
                                            <option value="1">HS mới</option>
                                        </select>
                                    </div>
                                    <div style="width:58%;margin-left:14px;">
                                        <input class="input" id="add-cmt" type="text" value="" placeholder="<?php echo $me; ?>" />
                                        <select class="input" id="select-lop" style="display: none;">
                                            <option value='0'>Chọn lớp</option>
                                            <?php
                                            $result=get_all_lop();
                                            while($data=mysqli_fetch_assoc($result)) {
                                                echo"<option value='$data[ID_LOP]'>Lớp $data[name]</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </li>

                                <li>
                                    <div><p>Vân tay</p></div>
                                    <div style="width:58%;"><input class="input" id="add-van" type="number" value="0" min="0" max="99999" placeholder="12345" />
                                        <nav id="search-van" class="search-div" style="left:10px;top:auto;">
                                        </nav></div>
                                </li>

                                <li>
                                    <div><p>Họ tên *</p></div>
                                    <div style="width:58%;"><input class="input" id="add-name" type="text" placeholder="Đinh Văn K" />
                                        <nav id="search-name" class="search-div" style="left:10px;top:auto;">
                                        </nav></div>
                                </li>

                                <li>
                                    <div><p>Ngày sinh *</p></div>
                                    <div style="width:58%;"><input class="input" id="add-birth" type="text" placeholder="1996-04-12" /></div>
                                </li>

                                <li>
                                        <div><p><input type="radio" class="check" name="gender" id="add-nam" /><span>Nam</span></p></div>
                                        <div><p><input type="radio" class="check" name="gender" id="add-nu" style="margin-left:40px;" /><span>Nữ</span></p></div>
                                </li>

                                <li>
                                    <div><p>Mật khẩu</p></div>
                                    <div style="width:58%;"><input class="input" id="add-pass" type="text" autocomplete="off" style="width:55%;" /><a href="javascript:void(0)" id="reset-mk" style="margin-left:10px;color:white;text-decoration:underline;">Reset</a></div>
                                </li>

                                <li>
                                    <div><p>Tài khoản</p></div>
                                    <div style="width:58%;">
                                        <ul style="background:none;">
                                        <li><span id="add-taikhoan" style="color:white;"></span></li>
                                        <li><span style="color:white;"><a href="#" id="add-thuong" target="_blank" style="color:white;text-decoration:underline;">Cộng tiền</a> - <a href="#" id="add-phat" target="_blank" style="color:white;text-decoration:underline;">Trừ tiền</a></span></li>
                                        </ul>
                                    </div>
                                </li>

                                <li>
                                    <div><p><a href="#" id="link-face" target="_blank" style="color:white;text-decoration:underline;">Facebook</a></p></div>
                                    <div style="width:58%;"><input class="input" id="add-face" type="url" placeholder="Link facebook" /></div>
                                </li>

                                <li>
                                    <div><p>Trường</p></div>
                                    <div style="width:58%;"><input type="text" class="input" placeholder="Nhập tên trường" id="add-truong" data-truong="0" />
                                        <nav id="search-truong" class="search-div2" style="left:10px;top:auto;">
                                        </nav></div>
                                </li>

                                <li>
                                    <div><p>SĐT<input type="checkbox" id="check-sdt" class="check check-phone" style="float:right;" /></p></div>
                                    <div style="width:58%;"><input class="input" id="add-sdt" type="text" placeholder="0974659271" />
                                        <nav id="search-sdt-number" class="search-div" style="left:10px;top:auto;">
                                        </nav>
                                </li>

                                <li>
                                    <div style="width:100%;"><p>Phụ huynh đã họp?</p><input type="checkbox" id="check-hop" class="check" style="float: right;" /></div>
                                </li>
                            </ul>

                            <div class="main-2 back" style="margin-top:3px;"><h3>Thông tin bố</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div><p>Họ tên</p></div>
                                    <div style="width:58%;"><input class="input" id="add-name-bo" type="text" /></div>
                                </li>
                                <li>
                                    <div><p>Nghề nghiệp</p></div>
                                    <div style="width:58%;"><input class="input" id="add-job-bo" type="text" /></div>
                                </li>
                                <li>
                                    <div><p>Facebook</p></div>
                                    <div style="width:58%;"><input class="input" id="add-face-bo" type="text" /></div>
                                </li>
                                <li>
                                    <div><p>SĐT</p><input type="checkbox" id="check-sdt-bo" class="check check-phone" style="float:right;" /></div>
                                    <div style="width:58%;"><input class="input" id="add-sdt-bo" type="text" placeholder="0974659271" /></div>
                                </li>
                                <li>
                                    <div><p>Email</p></div>
                                    <div style="width:58%;"><input class="input" id="add-mail-bo" type="text" /></div>
                                </li>
                            </ul>

                            <div class="main-2 back" style="margin-top:3px;"><h3>Thông tin mẹ</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div><p>Họ tên</p></div>
                                    <div style="width:58%;"><input class="input" id="add-name-me" type="text" /></div>
                                </li>
                                <li>
                                    <div><p>Nghề nghiệp</p></div>
                                    <div style="width:58%;"><input class="input" id="add-job-me type="text" /></div>
                                </li>
                                <li>
                                    <div><p>Facebook</p></div>
                                    <div style="width:58%;"><input class="input" id="add-face-me" type="text" /></div>
                                </li>
                                <li>
                                    <div><p>SĐT</p><input type="checkbox" id="check-sdt-me" class="check check-phone" style="float:right;" /></div>
                                    <div style="width:58%;"><input class="input" id="add-sdt-me" type="text" placeholder="0974659271" /></div>
                                </li>
                                <li>
                                    <div><p>Email</p></div>
                                    <div style="width:58%;"><input class="input" id="add-mail-me" type="text" /></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li1 info-mon" class="li-3" style="width:65.55%;margin-right:0;">
                            <div class="main-2 back"><h3>Thông tin môn học</h3></div>
                            <ul style="margin-top: 3px;">
                            <div class="main-div animated bounceInUp">
                                <div id="main-info">
                                    <div class="main-1-left" style="padding-top:2px;margin-right:0;max-height:none;width:100%;float:none;">
                                        <table class="table table-2 table-tkb" style="width:100%;" id="info-mon">
                                        </table>
                                    </div>
                                </div>
                            </div>
                            </ul>
                        </li>

                        <li id="chart-li1 info-note" class="li-3" style="width:100%;float:none;clear:both;padding:15px;">
                            <div class="main-2 back"><h3>Ghi chú</h3></div>
                            <ul>
                                <div class="main-div animated bounceInUp">
                                    <div class="main-1-left" style="padding-top:2px;margin-right:0;max-height:none;width: 100%;float: none;">
                                        <table class="table table-2 table-tkb" style="width:100%;color:white;" id="info-note">
                                            <tr class="tr-note"><th><span></span></th></tr>
                                        </table>
                                    </div>
                            </div>
                            </ul>
                    </li>

<!--                <li id="main-tb" class="li-3" style="float:none;">-->
<!--                      <div class="main-2 back"><h3>Ghi chú</h3></div>-->
<!--                       <ul style="margin-top:3px;">-->
<!--                       </ul>-->
<!--                </li>-->
                        <div class="clear"></div>
                </div>
            </ul>
                <table class="table">
                    <tr>
                        <th colspan="8"><input type="submit" style="width:10%;font-size:1.375em;height:50px;position: fixed;left:20px;bottom:10px;z-index:99;" class="submit add-new" value="Nhập" data-hsID="0" /></th>
                    </tr>
                </table>
            </div>
        </div>
        <?php require_once("include/MENU.php"); ?>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
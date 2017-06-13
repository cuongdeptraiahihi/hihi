<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
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
    $result0=get_all_lop();
    while($data0=mysqli_fetch_assoc($result0)) {
        $nam=$data0["name"];
        $pre_id = get_next_hs_lop($data0["ID_LOP"]);
        $me .= format_maso($pre_id, $data0["name"]) . ", ";
    }
    $monID=$_SESSION["mon"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THÔNG TIN CHI TIẾT HỌC SINH</title>
        
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
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status #search-box {width:80%;}.check {width:20px;height:20px;margin-right:10px;}
			#MAIN > #main-mid > div .status .table-2 {display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status .table-2 tr td span i {font-size:1.5em;}#MAIN > #main-mid > div .status table tr td > a, #MAIN > #main-mid > div .status table tr td > div > a {font-size:12px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:22px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}.mon-lich {display:none;}
            #MAIN table#info-note tr td.td-info:hover > nav {display: block;}
            #MAIN table#info-note tr td:last-child p.tot {background: #69b42e;color:#FFF;}
            #MAIN table#info-note tr td:last-child p.xau {background: #EF5350;color:#FFF;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.multidatespicker.js"></script>
        <script>
		$(document).ready(function() {

		    <?php
                if($maso!="") {
		            echo"get_hoc_sinh('$maso');";
                }
            ?>

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

			$("#info-mon").delegate("tr.tien-hoc td a#lich-su-login","click",function() {
				hsID = $(this).attr("data-hsID");
				if($.isNumeric(hsID) && hsID!=0) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					$.ajax({
						async: true,
						data: "hsID3=" + hsID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
						success: function(result) {
							$("#pop-title").html("Lịch sử đăng nhập");
							$("#pop-content").html(result);
							$("#popup-up").fadeIn("fast");
							$("#popup-loading").fadeOut("fast");
						}
					});
				}
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-thongtin/",
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

			$("#info-mon").delegate("tr.tien-hoc td a#lich-su-mk","click",function() {
				hsID = $(this).attr("data-hsID");
				if($.isNumeric(hsID) && hsID!=0) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					$.ajax({
						async: true,
						data: "hsID5=" + hsID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
						success: function(result) {
							$("#pop-title").html("Lịch sử đổi mật khẩu");
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
						url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
						success: function(result) {
							$("#pop-title").html("Lịch sử đổi ca");
							$("#pop-content").html(result);
							$("#popup-up").fadeIn("fast");
							$("#popup-loading").fadeOut("fast");
						}
					});
				}
			});

			get_new_mon();

			$("#add-birth").datepicker({
				dateFormat: 'yy-mm-dd',
				yearRange: '1990:<?php echo date("Y"); ?>',
				changeMonth: true,
				changeYear: true,
				defaultDate: new Date('<?php echo"".($nam)."-01-01"; ?>')
			});

			$("#hs-date").datepicker({
				dateFormat: 'yy-mm-dd',
				defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
			});

			$("#hs-date").change(function() {
				ngay = $(this).val();
				if(ngay=="") {
					ngay="#";
				}
				$("#xem-hs-date").attr("onclick","location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/"+ngay+"/'");
			});

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

			$("#BODY").click(function() {
				$(".search-div,.search-div2").fadeOut("fast");
			});

			$(".search-div ul").delegate("li a", "click", function() {
				value = $(this).attr("data-cmt");
				get_hoc_sinh(value);
				$(".search-div").fadeOut("fast");
			});

			$(".search-div2 ul").delegate("li a", "click", function() {
				truong = $(this).attr("data-truong");
				name_truong = $(this).html();
				$("#add-truong").val(name_truong).attr("data-truong",truong);
				$(".search-div2").fadeOut("fast");
			});

			$("#add-truong").keyup(function() {
                value = $(this).val();
                if(value!="" && value!=" " && value!="%" && value!="_") {
                    $.ajax({
                        async: true,
                        data: "search_truong=" + value,
                        type: "get",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-search-hs/",
                        success: function(a) {
                            $("#search-truong > ul").html(a), $("#search-truong").fadeIn("fast");
                        }
                    });
                }
			});

			$("#add-van").keyup(function() {
				value = $(this).val();
				if($.isNumeric(value) && value!=0) {
                    $.ajax({
                        async: true,
                        data: "search_van=" + value,
                        type: "get",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-search-hs/",
                        success: function(a) {
                            $("#search-van > ul").html(a), $("#search-van").fadeIn("fast")
                        }
                    });
				}
			});

            $("#add-sdt").keyup(function() {
                value = $(this).val();
                if($.isNumeric(value) && value!=0) {
                    $.ajax({
                        async: true,
                        data: "search_sdt=" + value,
                        type: "get",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-search-hs/",
                        success: function(a) {
                            $("#search-sdt-number > ul").html(a), $("#search-sdt-number").fadeIn("fast")
                        }
                    });
                }
            });

			$("#add-name").keyup(function() {
				value = $(this).val();
				if(value!="" && value!=" " && value!="%" && value!="_" && value.length>=7) {
                    $.ajax({
                        async: true,
                        data: "search_name=" + value,
                        type: "get",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-search-hs/",
                        success: function(a) {
                            $("#search-name > ul").html(a), $("#search-name").fadeIn("fast")
                        }
                    });
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
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				$.ajax({
					async: true,
					data: "cmt=" + cmt,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
					success: function(result) {
//						alert(result);
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
							$("#add-thuong").attr("href","http://localhost/www/TDUONG/thaygiao/thuong/"+obj.hsID+"/");
							$("#add-phat").attr("href","http://localhost/www/TDUONG/thaygiao/phat/"+obj.hsID+"/");
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
						$("#BODY").css("opacity","1");
						$("#popup-loading").fadeOut("fast");
					}
				});
			}

			function get_info_mon(hsID) {
				$.ajax({
					async: true,
					data: "hsID=" + hsID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
					success: function(result) {
						$("#info-mon").html("<tr style='background:#3E606F;'><th colspan='4'><span>Thông tin môn học</span></th></tr>" + result);
                        $("table#info-mon tr.mon-big td input.date-out").datepicker({
                            dateFormat: 'dd/mm/yy',
                            defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
                        });
					}
				});
			}

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
						url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
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
				var content = "<tr style='background:#3E606F;'><th colspan='3'><span>Thông tin môn học</span></th></tr><?php $cum_sl=0;$result=get_all_lop_mon(); while($data=mysqli_fetch_assoc($result)) {if($data["ID_MON"]!=$monID){continue;}echo"<tr class='mon-big' data-monID='$data[ID_LM]'><td style='width:25%;'><span class='info-$data[ID_LM]'></span></td><td style='width:25%;'><span>$data[name]</span></td><td style='width:50%;'><span class='add-mon'><i class='fa fa-toggle-off' data-monID='$data[ID_LM]'></i></span></td></tr>";$result3=get_all_cum($data["ID_LM"],$data["ID_MON"]);$dem=1;while($data3=mysqli_fetch_assoc($result3)) {echo"<tr class='mon-lich mon-lich_$data[ID_LM]'><td></td><td><span>Buổi $dem</span></td><td><select class='input chose-ca' style='height:auto;width:100%'><option value='0'>Chọn ca</option>";$result2=get_cahoc_cum_lop($data3["ID_CUM"], $data["ID_LM"], $data["ID_MON"]);while($data2=mysqli_fetch_assoc($result2)) {echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]'>".$thu_string[$data2["thu"]-1].", ca $data2[gio], sĩ số ".get_num_hs_ca_codinh($data2["ID_CA"])."</option>";}echo"</select></td></tr>";$dem++;}echo"<tr class='mon-lich mon-lich_$data[ID_LM]'><td colspan='2'><span>Lịch thi cuối tuần</span></td><td><select class='input chose-ca' style='height:auto;width:100%'><option value='0'>Chọn ca</option>";$stt=1;$result2=get_cakt_mon($data["ID_MON"]);while($data2=mysqli_fetch_assoc($result2)) {echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]'>$stt - ".$thu_string[$data2["thu"]-1].", ca $data2[gio], sĩ số ".get_num_hs_ca_codinh($data2["ID_CA"])."</option>";$stt++;}echo"</select></td></tr>";}?>";
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
                        success: function (result) {
                            console.log(result);
                        }
                    });
                }
            });

//            var mee = "<?php //echo date("d/m/Y"); ?>//: ";
//            $("#info-note").delegate("textarea.note-day","click",function () {
//                if($(this).val() == "" || $(this).val() == " ") {
//                    $(this).val("- " + mee);
//                }
//            });

            $("#info-mon").delegate("tr.tien-hoc td span i.del-edit-price","click",function() {
                var me = $(this);
                var oID = $(this).attr("data-oID");
                if($.isNumeric(oID) && oID!=0) {
                    $.ajax({
                        async: false,
                        data: "oID0=" + oID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
                        success: function(result) {
                            me.hide();
                            console.log("Ok!");
                        }
                    });
                }
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
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-tienhoc/",
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
                                url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
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
                            console.log("Lỗi dữ liệu!");
                        }
                    }
                });
            });

//            $("#info-note").delegate("textarea.note-day","keyup",function (e) {
//                $(this).outerHeight($(this).height()).outerHeight(this.scrollHeight);
////                if(e.keyCode == 13) {
////                    var content = $.trim($(this).val());
////                    $(this).val(content + "\n- " + mee);
////                } else if(e.keyCode == 8 || e.keyCode == 46) {
////                    var content = $.trim($(this).val());
////                    dodai = content.length;
////                    if(content.substring(dodai - mee.length - 2).trim() == ("- " + mee.substring(0,mee.length-1)).trim()) {
////                        $(this).val(content.substring(0,dodai - mee.length -2) + " ");
////                    }
////                } else {
////
////                }
//                del_tr = $(this).closest("tr");
//                var ngay = del_tr.find("td:first-child span").attr("data-date");
//                hsID = $(this).attr("data-hsID");
//                note = $(this).val();
//                note = note.replace(/\+/g,"-").replace(/(?:\r\n|\r|\n)/g, "<br />");
//                if ($.isNumeric(hsID) && hsID != 0 && ngay!="") {
//                    if(ngay == 0) {
//                        $.ajax({
//                            async: true,
//                            data: "hsID1=" + hsID + "&note1=" + note,
//                            type: "post",
//                            url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
//                            success: function (result) {
//                                console.log("Đã lưu");
//                            }
//                        });
//                    } else {
//                        $.ajax({
//                            async: true,
//                            data: "hsID1=" + hsID + "&ngay1=" + ngay + "&note2=" + note,
//                            type: "post",
//                            url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
//                            success: function (result) {
//                                del_tr.find("td:last-child input").attr("data-nID",result);
//                                console.log("Đã lưu: " + result);
//                            }
//                        });
//                    }
//                } else {
//                    console.log("Lỗi dữ liệu!");
//                }
//            });

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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
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

//            $("table#info-mon").delegate("tr","click",function() {
//
//            });

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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-diemdanh/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-nghihoc/",
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
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
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

            function get_hs_note(hsID) {
                if($.isNumeric(hsID) && hsID != 0) {
                    $.ajax({
                        async: true,
                        data: "hsID_note=" + hsID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-hocsinh/",
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
                                        console.log("Lỗi dữ liệu!");
                                    }
                                }
                            });
//                            console.log(dem);
                        }
                    });
                }
            }
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
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
        
        	<?php require_once("include/TOP.php"); ?>

            <?php
                if(isset($_POST["xem-hs-date"])) {
                    if(isset($_POST["hs-date"])) {
                        $date=$_POST["hs-date"];
                        header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/$date/");
                        exit();
                    } else {
                        header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/");
                        exit();
                    }
                }
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>THÔNG TIN CHI TIẾT HỌC SINH</h2>
                	<div>
                    	<div class="status">
                        	<table class="table table-2" style="width:29%;" id="info-hs">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin học sinh</span></th></tr>
                                <tr>
                                	<td style="width:35%;">
                                        <select class="input" id="select-hs">
                                            <option value="0" selected="selected">HS cũ</option>
                                            <option value="1">HS mới</option>
                                        </select>
                                    </td>
                                	<td style="width:65%;">
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
                                    </td>
                              	</tr>
                                <tr>
                                	<td><span>Vân tay</span></td>
                                    <td><input class="input" id="add-van" type="number" value="0" min="0" max="99999" placeholder="12345" />
                                    	<nav id="search-van" class="search-div" style="left:10px;top:auto;">
                                            <ul>
                                            </ul>
                                        </nav>
                                    </td>
                              	</tr>
                                <tr>
                                	<td><span>Họ tên *</span></td>
                                    <td><input class="input" id="add-name" type="text" placeholder="Đinh Văn K" />
                                    	<nav id="search-name" class="search-div" style="left:10px;top:auto;">
                                            <ul>
                                            </ul>
                                        </nav>
                                    </td>
                              	</tr>
                                <tr>
                                	<td><span>Ngày sinh *</span></td>
                                    <td><input class="input" id="add-birth" type="text" placeholder="1996-04-12" /></td>
                              	</tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;">
                                    	<input type="radio" class="check" name="gender" id="add-nam" /><span>Nam</span>
                                        <input type="radio" class="check" name="gender" id="add-nu" style="margin-left:40px;" /><span>Nữ</span>
                                  	</td>
                              	</tr>
                                <tr>
                                	<td><span>Mật khẩu</span></td>
                                    <td><input class="input" id="add-pass" type="text" autocomplete="off" style="width:55%;" /><a href="javascript:void(0)" id="reset-mk" style="margin-left:10px;">Reset</a></td>
                              	</tr>
                                <tr>
                                	<td rowspan="2"><span>Tài khoản</span></td>
                                    <td><span id="add-taikhoan"></span></td>
                              	</tr>
                                <tr>
                                    <td><a href="#" id="add-thuong" target="_blank">Cộng tiền</a> - <a href="#" id="add-phat" target="_blank">Trừ tiền</a></td>
                              	</tr>
                                <tr>
                                	<td><a href="#" id="link-face" target="_blank">Facebook</a></td>
                                	<td><input class="input" id="add-face" type="url" placeholder="Link facebook" /></td>
                                </tr>
                                <tr>
                                	<td><span>Trường</span></td>
                                    <td><input type="text" class="input" placeholder="Nhập tên trường" id="add-truong" data-truong="0" />
                                    	<nav id="search-truong" class="search-div2" style="left:10px;top:auto;">
                                            <ul>
                                            </ul>
                                        </nav>
                                    	<!--<select class="input" style="height:auto;width:100%;" id="add-truong">
                                            <option value="0">Chọn Trường</option>
                                        	<?php
												/*$result1=get_all_truong();
												while($data1=mysqli_fetch_assoc($result1)) {
													echo"<option value='$data1[ID_T]'>$data1[name]</option>";
												}*/	
											?>
                                        </select>-->
                                    </td>
                                </tr>
                                <tr>
                                	<td><span>SĐT</span><input type="checkbox" id="check-sdt" class="check check-phone" style="float:right;" /></td>
                                    <td>
                                        <input class="input" id="add-sdt" type="text" placeholder="0974659271" />
                                        <nav id="search-sdt-number" class="search-div" style="left:10px;top:auto;">
                                            <ul>
                                            </ul>
                                        </nav>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2"><span>Phụ huynh đã họp?</span><input type="checkbox" id="check-hop" class="check" style="float: right;" /></td>
                                </tr>
                                <tr style="background:#3E606F;"><th colspan="2"><span>Thông tin Bố</span></th></tr>
                                <tr>
                                	<td><span>Họ tên</span></td>
                                    <td><input class="input" id="add-name-bo" type="text" /></td>
                                </tr>
                                <tr>
                                	<td><span>Nghề nghiệp</span></td>
                                    <td><input class="input" id="add-job-bo" type="text" /></td>
                                </tr>
                                <tr>
                                	<td><span>Facebook</span></td>
                                    <td><input class="input" id="add-face-bo" type="text" /></td>
                                </tr>
                                <tr>
                                	<td><span>SĐT</span><input type="checkbox" id="check-sdt-bo" class="check check-phone" style="float:right;" /></td>
                                    <td><input class="input" id="add-sdt-bo" type="text" placeholder="0974659271" /></td>
                                </tr>
                                <tr>
                                	<td><span>Email</span></td>
                                    <td><input class="input" id="add-mail-bo" type="text" /></td>
                                </tr>
                                <tr style="background:#3E606F;"><th colspan="2"><span>Thông tin Mẹ</span></th></tr>
                                <tr>
                                	<td><span>Họ tên</span></td>
                                    <td><input class="input" id="add-name-me" type="text" /></td>
                                </tr>
                                <tr>
                                	<td><span>Nghề nghiệp</span></td>
                                    <td><input class="input" id="add-job-me" type="text" /></td>
                                </tr>
                                <tr>
                                	<td><span>Facebook</span></td>
                                    <td><input class="input" id="add-face-me" type="text" /></td>
                                </tr>
                                <tr>
                                	<td><span>SĐT</span><input type="checkbox" id="check-sdt-me" class="check check-phone" style="float:right;" /></td>
                                    <td><input class="input" id="add-sdt-me" type="text" placeholder="0974659271" /></td>
                                </tr>
                                <tr>
                                	<td><span>Email</span></td>
                                    <td><input class="input" id="add-mail-me" type="text" /></td>
                                </tr>
                            </table>
                            <table class="table table-2" style="width:35%;" id="info-mon">
                            	<tr style="background:#3E606F;"><th colspan="3"><span>Thông tin môn học</span></th><td><input type="hidden" class="date_out" /></td></tr>
                                <tr><td colspan="3" style="text-align:center;"><span>Nhập 1 mã học sinh để xem thông tin</span></td></tr>
                            </table>
                            <table class="table table-2" style="width:35%;" id="info-note">
                                <tr class="tr-note" style="background:#3E606F;"><th colspan="3"><span>Ghi chú</span></th></tr>
<!--                                <tr style="background:#3E606F;"><th colspan="3"><span>Ghi chú chính</span><a href="javascript:void(0)" id="special-note" style="float:right;margin-right: 10px;color:yellow;text-decoration: underline;">Đặc biệt</a></th></tr>-->
                            </table>
                            <table class="table">
                            	<tr>
                            		<th colspan="8"><input type="submit" style="width:25%;font-size:1.375em;height:50px;position: fixed;right:0;bottom:10px;z-index:99;" class="submit add-new" value="Nhập" data-hsID="0" /></th>
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
<?php
	ob_start();
	session_start();
    ini_set('max_execution_time', 300);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["mon"]) && is_numeric($_GET["mon"])) {
        $lmID=$_GET["lm"];
        $monID=$_GET["mon"];
    } else {
        $lmID=$_SESSION["lmID"];
        $monID=$_SESSION["mon"];
    }
	if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"])) {
		$hsID=$_GET["hsID"];
	} else {
		$hsID=0;
	}
	if(isset($_GET["loai"]) && is_numeric($_GET["loai"])) {
		$loai=$_GET["loai"];
	} else {
		$loai=0;
	}
    if($lmID != 0) {
        $_SESSION["lmID"] = $lmID;
    }
    $_SESSION["mon"]=$monID;
    $mon_lop_name=get_lop_mon_name($lmID);
	
	$now=date("Y-m");
	$last=get_last_time(date("Y"),date("m"));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>LỊCH SỬ ĐIỂM DANH</title>
        
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
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid .status {position:relative;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:600px;}#chartContainer {width:100%;height:100%;}#chartPhu {position:absolute;z-index:9;right:0;width:400px;top:0;height:200px;overflow:hidden;border-radius:200px;}#chartContainer2 {width:100%;height:100%;}.khoang-ma,.only-ma {display:none;}#list-danhsach {background:#FFF;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/iscroll.js"></script>
        <script>
			<?php if($_SESSION["mobile"]==0) { ?>
			//document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
			window.onload = function() {
				var myScroll2 = new IScroll('#main-wapper2', { scrollX: true, scrollY: false, mouseWheel: false});
			}
			<?php } ?>
			$(document).ready(function() {
				
				var count_me = 0;
				$("table#list-danhsach tr").each(function(index, element) {
					dem = 0;
					$(element).find("td").each(function(index2, element2) {
						if($(element2).hasClass("hs-ko")) {
							dem++;
						} else {
							if(dem<2) {
								dem = 0;
							}
						}
					});
					if(dem>=2 && index>0) {
						count_me++;
					}
				});
				$("#status").html("Số học sinh nghỉ 2 buôi liên tiếp: " + count_me);
				
				$("#loc-nghi").click(function() {
					$("table#list-danhsach tr").each(function(index, element) {
						dem = 0;
                        $(element).find("td").each(function(index2, element2) {
                            if($(element2).hasClass("hs-ko")) {
								dem++;
							} else {
								if(dem<2) {
									dem = 0;
								}
							}
                        });
						if(dem<2 && index>0) {
							$(element).hide();
						} else {
							$(element).show();
						}
                    });
				});

				$("#popup-ok").click(function() {
				    var hsID = $(this).attr("data-hsID");
				    var ddID = $(this).attr("data-ddID");
				    var cumID = $(this).attr("data-cum");
				    var loai = $("#select-diemdanh").find("option:selected").val();
                    if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && $.isNumeric(ddID) && ddID!=0) {
                        $.ajax({
                            async: true,
                            data: "loai_dd=" + loai + "&ddID_dd=" + ddID + "&hsID_dd=" + hsID + "&cumID_dd=" + cumID + "&lmID_dd=" + <?php echo $lmID; ?> + "&monID_dd=" + <?php echo $monID; ?>,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
                            success: function(result) {
                                if(result == "ok-1") {
                                    $("#popup-confirm").fadeOut("fast");
                                    $(".hs-diemdanh.cum-" + cumID).find("span").html("OK");
                                } else if(result == "ok-2") {
                                    $("#popup-confirm").fadeOut("fast");
                                    $(".hs-diemdanh.cum-" + cumID).find("span").html("Nghỉ");
                                } else {
                                    alert("Lỗi: " + result);
                                }
                                console.log(result);
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                });

                $("table#list-danhsach tr td.hs-diemdanh").click(function() {
                    var me = $(this);
                    var hsID = $(this).attr("data-hsID");
                    var ddID = $(this).attr("data-ddID");
                    var index = $(this).index();
                    var cumID = $("table#list-danhsach tr:first-child th:eq(" + index + ")").attr("data-cum");
                    if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && $.isNumeric(ddID) && ddID!=0) {
                        $("#popup-ok").attr("data-hsID", hsID).attr("data-ddID", ddID).attr("data-cum", cumID);
                        $("#popup-confirm").fadeIn("fast");
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
				});
				
//				$("table#list-danhsach tr td.hs-ko").click(function() {
//					me = $(this);
//					hsID = $(this).attr("data-hsID");
//					index = $(this).index();
//					cumID = $("table#list-danhsach tr:first-child th:eq("+index+")").attr("data-cum");
//					if($(this).hasClass("active")) {
//						is_phep = 0;
//					} else {
//						is_phep = 1;
//					}
//					if($.isNumeric(hsID) && $.isNumeric(cumID) && hsID!=0 && cumID!=0 && (is_phep==0 || is_phep==1)) {
//						me.css("opacity","0.3");
//						$.ajax({
//							async: true,
//                            data: "cumID=" + cumID + "&hsID=" + hsID + "&is_phep=" + is_phep + "&lmID=" + <?php //echo $lmID; ?>// + "&monID=" + <?php //echo $monID; ?>// + "&is_bao=0",
//							type: "post",
//							url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
//							success: function(result) {
//								if(result=="ok") {
//									if(is_phep==0) {
//                                        me.css("background","green");
//									    me.html("<span style='color:#FFF'>Ko phép</span>");
//										me.removeClass("active");
//									}
//									if(is_phep==1) {
//									    me.css("background","orange");
//										me.html("<span style='color:#FFF'>Phép</span><input type='checkbox' style='display: none;' class='check' checked='checked' />");
//										me.addClass("active");
//									}
//								} else {
//									alert("Lỗi dữ liệu!");
//								}
//								me.css("opacity","1");
//							}
//						});
//					}
//				});
//
//                $("table#list-danhsach tr td span.hs-diem-danh").click(function() {
//                    me = $(this);
//                    hsID = $(this).attr("data-hsID");
//                    ddID = $(this).attr("data-ddID");
//                    index = $(this).index();
//                    cumID = $("table#list-danhsach tr:first-child th:eq("+index+")").attr("data-cum");
//                    if(confirm("Bạn có chắc chắn không?")) {
//                        if ($.isNumeric(hsID) && $.isNumeric(ddID) && hsID != 0 && ddID != 0) {
//                            me.css("opacity", "0.3");
//                            $.ajax({
//                                async: true,
//                                data: "ddID_dd=" + ddID + "&hsID_dd=" + hsID + "&cumID_dd=" + cumID + "&lmID_dd=<?php //echo $lmID; ?>//&monID_dd=<?php //echo $monID; ?>//",
//                                type: "post",
//                                url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
//                                success: function (result) {
//                                    if (result == "ok") {
//                                        $("table#list-danhsach tr td.hs-ko:eq("+index+")").html("<span>3</span>").css("background","#FFF");
//                                        me.html("");
//                                    } else {
//                                        alert("Lỗi dữ liệu!");
//                                    }
//                                    me.css("opacity", "1");
//                                }
//                            });
//                        }
//                    }
//                });
//
//				$("table#list-danhsach tr th").click(function() {
//					cumID = $(this).attr("data-cum");
//					dai = $("table#list-danhsach tr").length;
//					del_td = $(this).index();
//					if($(this).hasClass("active")) {
//						$("table#list-danhsach tr").each(function(index, element) {
//							$(element).show();
//                        });
//						$(this).removeClass("active");
//					} else {
//						$("table#list-danhsach tr").each(function(index, element) {
//                        	if(index>0 && index<dai-2) {
//								if($(element).find("td:eq("+del_td+")").hasClass("hs-ko")) {
//									$(element).show();
//								} else {
//									$(element).hide();
//								}
//							}
//                        });
//						$(this).addClass("active");
//					}
//				});
//
//				$("table#list-danhsach tr:last-child td input.nhap_nghi").click(function() {
//					if(confirm("Bạn có chắc chắn thực hiện hành động này?")) {
//						$("#popup-loading").fadeIn("fast");
//						$("#BODY").css("opacity","0.3");
//						cumID = $(this).attr("data-cum");
//						me = $(this);
//						ajax_data="[";
//						$("table#list-danhsach tr td.cum-"+cumID).each(function(index, element) {
//							is_phep = 0;
//                            if($(element).has("input.check")) {
//								if($(element).find("input.check").is(":checked")) {
//									is_phep = 1;
//								}
//							}
//							hsID = $(element).attr("data-hsID");
//							ajax_data+='{"hsID":"'+hsID+'","is_phep":"'+is_phep+'"},';
//                        });
//						ajax_data+='{"cumID":"'+cumID+'","lmID":"<?php //echo $lmID; ?>//","monID":"<?php //echo $monID; ?>//"}';
//						ajax_data+="]";
//                        is_bao = 0;
//                        if(confirm("Bạn có muốn thông báo cho học sinh?")) {
//                            is_bao = 1;
//                        } else {
//                            is_bao = 0;
//                        }
//						if($.isNumeric(cumID) && cumID!=0 && ajax_data!="" && (is_bao==1 || is_bao==0)) {
//							$.ajax({
//								async: true,
//								data: "data=" + ajax_data + "&is_bao=" + is_bao,
//								type: "post",
//								url: "http://localhost/www/TDUONG/admin/xuly-diemdanh/",
//								success: function(result) {
//									if(result=="ok") {
//										alert("Thành công");
//										me.val("Xong").css("background","blue");
//									} else {
//										alert(result);
//										me.val("Lỗi").css("background","red");
//									}
//									$("#BODY").css("opacity","1");
//									$("#popup-loading").fadeOut("fast");
//								}
//							});
//						}
//					}
//				});
//
//				$("table#list-danhsach").delegate("tr td:first-child > span.check-nghi > i.fa-square-o","click", function() {
//				//$("#list-danhsach tr td > span.check-nghi > i.fa-square-o").click(function() {
//					del_tr = $(this).closest("tr");
//					hsID = $(this).attr("data-hsID");
//					me_i = $(this);
//					if(confirm("Bạn có chắc chắn cho học sinh này nghỉ học?") && $.isNumeric(hsID) && hsID!=0) {
//						del_tr.css("opacity","0.3");
//						$.ajax({
//							async: true,
//							data: "hsID2=" + hsID + "&lmID2=" + <?php //echo $lmID; ?>//,
//							type: "post",
//							url: "http://localhost/www/TDUONG/admin/xuly-thongtin/",
//							success: function(result) {
//								del_tr.fadeOut("fast");
//							}
//						});
//					}
//				});
			});
		</script>
       
	</head>
    
    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>

        <div class="popup" id="popup-confirm" style="width:30%;left:35%;">
            <div class="popup-close"><i class="fa fa-close"></i></div>
            <p style="text-transform:uppercase;">Điểm danh</p>
            <div style="width:90%;margin:15px auto 15px auto;">
                <select class="input" id="select-diemdanh">
                    <option value="dihoc-tinhdung">Đi học - Tính đúng</option>
                    <option value="dihoc-tinhsai">Đi học - Tính sai</option>
                    <option value="dimuon-kokt">Đi muộn hoặc không kiểm tra</option>
                    <option value="nghicophep">Nghỉ có phép</option>
                    <option value="nghikophep">Nghỉ không phép</option>
                </select>
            </div>
            <div>
                <button class="submit" id="popup-ok">OK</button>
            </div>
        </div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Lịch sử điểm danh <span style="font-weight:600;">môn <?php echo $mon_lop_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <div class="main-bot" style="display:block;">
							<?php if($_SESSION["mobile"]==0) { ?>
                            	<table class="table table3">
                                    <?php if($hsID != 0) { ?>
                                	<tr>
                                        <td style="width:16.5%;"><span>0 - Không học bài</span></td>
                                    	<td style="width:16.5%;"><span>1 - Có học, tính sai</span></td>
                                        <td style="width:16.5%;"><span>2 - Có học, tính đúng</span></td>
                                        <td style="width:16.5%;"><span>3 - Đi muộn, ko KT</span></td>
                                        <td style="width:16.5%;"><span>C - Chưa học</span></td>
                                  	</tr>
                                    <?php } ?>
                                    <tr>
                                        <?php if($lmID!=0) { ?>
                                        <td colspan="5"><input type="submit" class="submit" value="Buổi kiểm tra" onclick="location.href='http://localhost/www/TDUONG/admin/hoc-sinh-diem-danh/0/<?php echo $monID; ?>/<?php echo $loai; ?>/<?php echo $hsID; ?>/'" /></td>
                                    	<?php } else { ?>
                                        <td colspan="5"><input type="submit" class="submit" value="Ca học" onclick="location.href='http://localhost/www/TDUONG/admin/hoc-sinh-diem-danh/<?php echo $_SESSION["lmID"]; ?>/<?php echo $monID; ?>/<?php echo $loai; ?>/<?php echo $hsID; ?>/'" /></td>
                                        <?php } ?>
<!--                                        <td colspan="2"></td>-->
<!--                                        <td colspan="2"><span id="status"></span></td>-->
<!--                                        <td><input type="submit" class="submit" value="Lọc nghỉ >= 2" id="loc-nghi" /></td>-->
                                    </tr>
                                </table>
							<?php } ?>
                                <div class="clear" id="main-wapper2" style="width:100%;overflow:auto;">
                                <div></div>
                                <table class="table" id="list-danhsach" style="margin-top:25px;">
                                	<tr style="background:#3E606F;">
                                    	<th style="min-width:60px;"><span>Nghỉ hẳn</span></th>
                                    	<th style="min-width:60px;"><span>STT</span></th>
                                        <th style="min-width:60px;"><span>Mã số</span></th>
                                    	<th style="min-width:120px;"><span>Tên học sinh</span></th>
										<?php
											$dem_cum=0;$cumID=0;$dem=0;$con=$string="";$cum_arr=$dem_arr=array();
											if($loai==1) {
											    $query0="SELECT DISTINCT ID_CUM FROM diemdanh_buoi WHERE (date LIKE '$now-%' OR date LIKE '$last-%') AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM DESC,date DESC LIMIT 11";
												$result0=mysqli_query($db,$query0);
                                                while($data0=mysqli_fetch_assoc($result0)) {
                                                    $string.=",'$data0[ID_CUM]'";
                                                }
                                                $query1="SELECT ID_CUM,date FROM diemdanh_buoi WHERE ID_CUM IN (".substr($string,1).") AND (date LIKE '$now-%' OR date LIKE '$last-%') AND ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM DESC,date DESC";
                                            } else {
												$query1="SELECT ID_CUM,date FROM diemdanh_buoi WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_CUM DESC,date DESC";
											}
											$result1=mysqli_query($db,$query1);
											$all=mysqli_num_rows($result1);
											while($data1=mysqli_fetch_assoc($result1)) {
											    //echo"$data1[ID_CUM] - $data1[date]<br />";
                                                $thu = date("w",strtotime($data1["date"])) + 1;
												if(($cumID!=$data1["ID_CUM"] && $dem!=0) || ($dem==$all-1)) {
													echo"<th style='min-width:60px;' data-cum='$cumID'><span>".substr($con,6)."</span></th>";
													$con="";
													if($dem==$all-1 && $cumID!=$data1["ID_CUM"]) {
														if(stripos($con,format_date($data1["date"]))===false) {
															$con.="<br />".format_date($data1["date"])." (T$thu)";
														}
														echo"<th style='min-width:60px;' data-cum='$cumID'><span>".substr($con,6)."</span></th>";
													}
												}
												$dem++;
												if(stripos($con,format_date($data1["date"]))===false) {
													$con.="<br />".format_date($data1["date"])." (T$thu)";
												}
												if($cumID!=$data1["ID_CUM"]) {
													$cum_arr[]=$data1["ID_CUM"];
													$dem_arr[]=0;
												}
												$cumID=$data1["ID_CUM"];
											}
										?>
									</tr>
                                    <?php
                                        if (isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
                                            $position = $_GET["begin"];
                                        } else {
                                            $position = 0;
                                        }
                                        $stt=$position;
                                        $display = 30;
										if($lmID!=0) {
											if($hsID!=0) {
												$query2="SELECT h.ID_HS,h.cmt,h.fullname,m.date_in,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' WHERE h.ID_HS='$hsID' ORDER BY h.cmt ASC";
											} else {
												$query2="SELECT h.ID_HS,h.cmt,h.fullname,m.date_in,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC LIMIT $position,$display";
											}
										} else {
											if($hsID!=0) {
												$query2="SELECT h.ID_HS,h.cmt,h.fullname,m.date_in,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS WHERE h.ID_HS='$hsID' ORDER BY h.cmt ASC";
											} else {
												$query2="SELECT h.ID_HS,h.cmt,h.fullname,m.date_in,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS ORDER BY h.cmt ASC LIMIT $position,$display";
											}
										}
										$diemdanh_str="";
										$result2=mysqli_query($db,$query2);
										while($data2=mysqli_fetch_assoc($result2)) {
											$date_in=date_create($data2["date_in"]);
											if(isset($data2["ID_N"]) && $hsID==0) {
												continue;
											} else {
												if($stt%2!=0) {
													echo"<tr style='background:#D1DBBD'>";
												} else {
													echo"<tr>";
												}
											}
												echo"<td><span class='check-nghi'><i class='fa fa-square-o' data-hsID='$data2[ID_HS]' style='font-size:1.5em !important;'></i></span></td>
												<td><span>".($stt+1)."</span></td>
												<td><span>$data2[cmt]<span></td>
												<td><span>$data2[fullname]</span></td>";
												if($loai==1) {
													$query3="SELECT ID_STT,ID_CUM,date FROM diemdanh_buoi WHERE ID_CUM IN (".substr($string,1).") AND (date LIKE '$now-%' OR date LIKE '$last-%') AND ID_LM='$lmID' AND ID_MON='$monID' GROUP BY ID_CUM ORDER BY ID_CUM DESC,date DESC";
												} else {
													$query3="SELECT ID_STT,ID_CUM,date FROM diemdanh_buoi WHERE ID_LM='$lmID' AND ID_MON='$monID' GROUP BY ID_CUM ORDER BY ID_CUM DESC,date DESC";
												}
												$result3=mysqli_query($db,$query3);
												$me=0;
												while($data3=mysqli_fetch_assoc($result3)) {
													$date=date_create($data3["date"]);
													if($date<$date_in) {
														echo"<td><span>C</span></td>";
//                                                        $diemdanh_str.="<td><span></span></td>";
													} else {
														$result4=check_di_hoc($data2["ID_HS"],$data3["ID_CUM"],$lmID,$monID);
														if($result4!=false) {
														    if($hsID != 0) {
                                                                $data4 = mysqli_fetch_assoc($result4);
                                                                if ($data4["is_kt"] == 0) {
                                                                    echo "<td class='hs-diemdanh cum-$data3[ID_CUM]' data-hsID='$data2[ID_HS]' data-ddID='$data3[ID_STT]'><span>3</span></td>";
                                                                } else {
                                                                    if ($data4["is_hoc"] == 1 && $data4["is_tinh"] == 1) {
                                                                        echo "<td class='hs-diemdanh cum-$data3[ID_CUM]' data-hsID='$data2[ID_HS]' data-ddID='$data3[ID_STT]'><span>2</span></td>";
                                                                    } else if ($data4["is_hoc"] == 1 && $data4["is_tinh"] == 0) {
                                                                        echo "<td class='hs-diemdanh cum-$data3[ID_CUM]' data-hsID='$data2[ID_HS]' data-ddID='$data3[ID_STT]'><span>1</span></td>";
                                                                    } else if ($data4["is_hoc"] == 0 && $data4["is_tinh"] == 0) {
                                                                        echo "<td class='hs-diemdanh cum-$data3[ID_CUM]' data-hsID='$data2[ID_HS]' data-ddID='$data3[ID_STT]'><span>0</span></td>";
                                                                    } else {
                                                                        echo "<td style='background:red;'><span>Lỗi</span></td>";
                                                                    }
                                                                }
                                                            } else {
                                                                echo "<td><span class='fa fa-check'></span></td>";
                                                            }
//                                                            $diemdanh_str.="<td><span></span></td>";
														} else {
															if(get_lydo_nghi($data3["ID_CUM"],$data2["ID_HS"],$lmID,$monID)) {
                                                                echo"<td class='hs-diemdanh cum-$data3[ID_CUM] active' data-hsID='$data2[ID_HS]' data-ddID='$data3[ID_STT]' style='background:orange;'><span style='color:#FFF'>Phép</span><input type='checkbox' class='check' style='display: none;' checked='checked' /></td>";
															} else {
																echo"<td class='hs-diemdanh cum-$data3[ID_CUM]' data-hsID='$data2[ID_HS]' data-ddID='$data3[ID_STT]' style='background:green;'><span style='color:#FFF'>Ko phép</span></td>";
															}
//															$diemdanh_str.="<td><span style='font-size: 15px;' class='hs-diem-danh' data-ddID='$data3[ID_STT]' data-hsID='$data2[ID_HS]'><i class='fa fa-check-square-o'></i></button></span></td>";
															$dem_arr[$me]++;
														}
													}
													$me++;
												}
											echo"</tr>";
											$stt++;
										}
										if($hsID==0) {
											echo "<tr>
												<td colspan='4'><span>Số lượng nghỉ</span></td>";
											for ($i = 0; $i < count($dem_arr); $i++) {
												echo "<td><span>$dem_arr[$i]</span></td>";
											}
											echo "</tr>";
//											if ($lmID != 0) {
//											echo "<tr>
//												<td colspan='4'><span>Nhập học sinh nghỉ</span></td>";
//												for ($i = 0; $i < count($cum_arr); $i++) {
//													if (check_done_options($cum_arr[$i], "diemdanh-nghi", $lmID, $monID)) {
//														echo "<td><span>Đã nhập</span></td>";
//													} else {
//														echo "<td><input type='submit' class='submit nhap_nghi' value='Nhập' data-cum='$cum_arr[$i]' /></td>";
//													}
//												}
//												echo "</tr>";
//											}
										} else {
											$me=0;
											for($i=0;$i<count($dem_arr);$i++) {
												$me+=$dem_arr[$i];
											}
											$me2=0;
											for($i=0;$i<count($cum_arr);$i++) {
												$me2++;
											}
											echo"<tr>
												<td colspan='4'><span>Nghỉ $me buổi học / Tổng $me2 buổi</span></td>";
//												echo $diemdanh_str;
											echo"</tr>";
										}
									?>
                                </table>
                                </div>
                            </div>
                        </div>
                        <?php if($loai != 3) { ?>
                        <div class="page-number">
                            <ul>
                                <li><a href='http://localhost/www/TDUONG/admin/hoc-sinh-diem-danh/<?php echo $lmID; ?>/<?php echo $monID; ?>/<?php echo $loai; ?>/page/<?php if($position>0){echo ($position-30);}else{echo 0;} ?>/'><</a></li>
                                <li><a href='http://localhost/www/TDUONG/admin/hoc-sinh-diem-danh/<?php echo $lmID; ?>/<?php echo $monID; ?>/<?php echo $loai; ?>/page/<?php echo ($position+30); ?>/'>></a></li>
                            </ul>
                        </div>
                        <?php } ?>
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
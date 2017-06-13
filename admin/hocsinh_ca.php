<?php
	ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
    if(isset($_GET["hsID"]) && isset($_GET["lmID"]) && is_numeric($_GET["hsID"]) && is_numeric($_GET["lmID"])) {
        $hsID=$_GET["hsID"];
        $lmID=$_GET["lmID"];
    } else {
        $hsID=0;
        $lmID=0;
    }
    $monID=get_mon_of_lop($lmID);
    $code=md5("123456");
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID, $lmID);
	$data0=mysqli_fetch_assoc($result0);

    $cagio=array();
    $query1="SELECT ID_GIO,gio FROM cagio WHERE ID_LM='$lmID' AND ID_MON='$monID' ORDER BY ID_GIO ASC";
    $result1=mysqli_query($db,$query1);
    while($data1=mysqli_fetch_assoc($result1)) {
        $cagio[]=array(
            "gioID" => $data1["ID_GIO"],
            "gio" => $data1["gio"]
        );
    }
    $n=count($cagio);
	
	$ontime=true;
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>ĐỔI CA</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/tongquan.css"/>
        
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
            body {font-family: Arial, Helvetica, sans-serif;}#BODY {padding-top: 30px;}
            #myback {background-image:url(https://localhost/www/TDUONG/images/kiet.jpg);}.back, #NAVBAR>ul>li, #chart-li1 ul, #MAIN .main-div > ul > li > ul, #back-one > ul > nav li .noi {background:rgba(0,0,0,0.15);}#MAIN .main-div #main-table table tr#tr-me, #MAIN .main-div #main-table table tr.tr-big, .page-number ul li:hover {background:rgba(0,0,0,0.35);}
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#3E606F;cursor:pointer;}#COLOR i:hover {color:#3E606F;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:22px;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}table tr:last-child td:first-child, table tr:last-child td:last-child {border-bottom-left-radius:0;border-bottom-right-radius:0;}#MAIN > .main-div .main-1-left table tr td {overflow:hidden;position:relative;}#MAIN > .main-div .main-1-left table tr td > nav {width:100%;height:100%;}#MAIN > .main-div .main-1-left table tr td > div.tab-num {position:absolute;z-index:9;right:-20px;top:-5px;background:rgba(0,0,0,0.15);width:60px;height:30px;-ms-transform: rotate(45deg);-webkit-transform: rotate(45deg); transform: rotate(45deg);}#MAIN > .main-div .main-1-left table tr td > div.tab-num span {color:#FFF;line-height:35px;font-size:12px !important;}#MAIN > .main-div .main-1-left table tr td > nav > div {float:left;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-left {width:65%;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-right {width:25%;padding-left:5%;text-align:left;}#MAIN > .main-div .main-1-left table tr td > nav > div.tab-right i {font-size:22px;cursor:pointer;color:#FFF;}
			
			
			
			/*#MAIN > .main-div .main-1-left table tr td > nav .tab-top {}#MAIN > .main-div .main-1-left table tr td > nav .tab-top span {font-weight:600;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot p {margin:10px 0 0 0;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot p i {font-size:22px;cursor:pointer;}#MAIN > .main-div .main-1-left table tr td > nav .tab-bot > span {display:block;}*/
			/*.hideme {margin-left:-150%;opacity:0;}*/
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				$(".btn_tam").click(function() {
					caID = $(this).attr("data-caID");
                    $(this).closest(".popup").find("button.submit2").hide();
                    $(this).closest(".popup").find("p.title").html("Đang đổi ca...");
                    if(caID!="") {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.1");
                        $.ajax({
                            async: !1,
                            data: "ca=" + caID + "&hs=" + <?php echo $hsID; ?> + "&lmID=" + <?php echo $lmID; ?>,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-ca/",
                            success: function (a) {
                                alert(a), location.reload();
                            }
                        });
                    }
				}), $(".btn_codinh").click(function() {
					caID = $(this).attr("data-caID");
                    $(this).closest(".popup").find("button.submit2").hide();
                    $(this).closest(".popup").find("p.title").html("Đang đổi ca...");
                    if(caID!="") {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.1");
                        $.ajax({
                            async: !1,
                            data: "ca3=" + caID + "&hs=" + <?php echo $hsID; ?> + "&lmID=" + <?php echo $lmID; ?>,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-ca/",
                            success: function (a) {
                                alert(a), location.reload();
                            }
                        });
                    }
				}), $(".btn_back").click(function() {
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.1"), caID = $(this).attr("data-caID");
                    $(this).closest(".popup").find("button.submit2").hide();
                    $(this).closest(".popup").find("p.title").html("Đang đổi ca...");
                    if(caID!="") {
                        $.ajax({
                            async: !1,
                            data: "ca2=" + caID + "&hs=" + <?php echo $hsID; ?> + "&lmID=" + <?php echo $lmID; ?>,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-ca/",
                            success: function (a) {
                                alert(a), location.reload();
                            }
                        });
                    }
				}), $(".popup .popup-close").click(function() {
					$(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
				}), $("#MAIN .main-div #main-info table tr td i.fa-square-o").click(function() {
                    $(".popup").fadeOut("fast");
					caID = $(this).attr("data-caID"), $(this).hasClass("codinh") ? ($(".btn_back").attr("data-caID", caID), $("#backca").fadeIn("fast")) : ($(".btn_codinh, .btn_tam").attr("data-caID", caID), $("#doica").fadeIn("fast")), $("#BODY").css("opacity", "0.1")
				}), $("#MAIN .main-div #main-info table tr td i.fa-check-square-o").click(function() {
					//caID = $(this).attr("data-caID"), $(".btn_thoat2").attr("data-caID", caID), $("#thoatca").fadeIn("fast"), $("#BODY").css("opacity", "0.1")
				}), /*$(".btn_thoat2").click(function() {
					$("#popup-loading").fadeIn("fast"), $("#BODY").css("opacity", "0.1"), caID = $(this).attr("data-caID"), $.ajax({
						async: !1,
						data: "caID0=" + caID,
						type: "post",
						url: "https://localhost/www/TDUONG/xuly-tam/",
						success: function(a) {
							alert("Lịch học của bạn sẽ chở về ban đầu!"), location.reload()
						}
					})
				}),*/ $("#MAIN .main-div #main-info table tr td i.fa-ban").click(function() {
                    $(".popup").fadeOut("fast");
					$("#noneca").fadeIn("fast");
					$("#BODY").css("opacity", "0.1");
				});
				var a = $("#tkb-hin").val();
				var b = $("#tkb-hin2").val();
				$("#tkb-show").html(a);
				$("#tkb-show2").html(b);
				var t = $("#kt-hin").val();
				$("#kt-show").html(t), $("#MAIN > .main-div .main-1-left table tr.con-ca").each(function(a, t) {
					$(t).find("td").hasClass("has-ca") || $(t).remove()
				})
				var max_dem = $("#max-dem").val();
				$(".table-tkb tr th").css("width", (100 / max_dem) + "%");
				$(".table-tkb tr.big-ca th").removeAttr("style").attr("colspan", max_dem);
				
				$(".btn-exit").click(function() {
					$(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
				});
			});
		</script>
       
	</head>

    <body>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>
    
    	<div class="popup animated bounceIn" id="doica">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
            	<p class="title">Bạn muốn đổi cố định hay đổi tạm?</p>
                <div>
                	<button class="submit2 btn_codinh">Cố định</button>
                    <button class="submit2 btn_tam">Tạm</button>
                </div>
            </div>
        </div>
        
        <div class="popup animated bounceIn" id="backca">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
            	<p class="title">Bạn muốn trở về ca cố định?</p>
                <div>
                	<button class="submit2 btn_back"><i class="fa fa-check"></i></button>
                </div>
            </div>
        </div>
        
        <div class="popup animated bounceIn" id="thoatca">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
        	<div>
            	<p class="title">Bạn muốn thoát ca này?</p>
                <div>
                    <button class="submit2 btn_thoat2"><i class="fa fa-check"></i></button>
                </div>
            </div>
        </div>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            	
              	<div class="main-div back animated bounceInUp" id="main-top">
                	<div id="main-person">
                		<h1 style="line-height:98px;">Đổi lịch học và lịch thi</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                        <a href="javascript:void(0)" title="Hồ sơ cá nhân">
                        	<p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                </div>
                
                <div class="main-div animated bounceInUp">
                    <div id="main-info">
                    	<div class="main-1-left back" style="margin-right:2%;max-height:none;">
                        	<div>
                            	<p class="main-title">Lịch học cố định trong tuần</p>
                                <p id="tkb-show">Thứ 2 - Thứ 4 - Thứ 6</p>
                            </div>
                            <table class="table-tkb" style="border-spacing:0 3px;">
                            <?php
								$ca_gio=array();
								$query2="SELECT ID_GIO,gio,buoi,ID_LM FROM cagio WHERE ID_MON='$monID' ORDER BY buoi ASC,thutu ASC";
								$result2=mysqli_query($db,$query2);
								while($data2=mysqli_fetch_assoc($result2)) {
									$ca_gio[]=array(
										"gioID" => $data2["ID_GIO"],
										"gio" => $data2["gio"],
										"buoi" => substr($data2["buoi"],0,1),
										"lmID" => $data2["ID_LM"]
									);
								}
								usort($ca_gio, "buoi_sort");
								
								$max_dem=0;
								$tkb=array();
								$dia_diem="";$first=false;
								$result=get_all_cum($lmID,$monID);
								while($data=mysqli_fetch_assoc($result)) {
                            		echo"<tr style='text-transform:uppercase;' class='big-ca'>
										<th colspan=''><span>$data[name]</span></th>
									</tr>
									<tr style='text-transform:uppercase;'>";
									$thu_array=array();
									$dem=0;
									$query5="SELECT DISTINCT thu FROM cahoc WHERE cum='$data[ID_CUM]' ORDER BY thu ASC";
									$result5=mysqli_query($db,$query5);
									while($data5=mysqli_fetch_assoc($result5)) {
										echo"<th><span>Thứ $data5[thu]</span></th>";
										$thu_array[$dem]=$data5["thu"];
										$dem++;
									}	
									echo"</tr>";
									for($i=0;$i<count($ca_gio);$i++) {
										if($lmID!=$ca_gio[$i]["lmID"]) {
											continue;
										}
										echo"
										<tr class='con-ca'>";
											for($j=0;$j<count($thu_array);$j++) {
                                                $query3="SELECT c.ID_CA,c.thu,d.name FROM cahoc AS c 
												INNER JOIN ca_quyen AS q ON q.ID_HS='$hsID' AND q.ID_CA=c.ID_CA 
												INNER JOIN dia_diem AS d ON d.ID_DD=c.ID_DD 
												WHERE c.thu='".$thu_array[$j]."' AND c.ID_GIO='".$ca_gio[$i]["gioID"]."' AND c.cum='$data[ID_CUM]'";												$result3=mysqli_query($db,$query3);
												if(mysqli_num_rows($result3)!=0) {
													$data3=mysqli_fetch_assoc($result3);
													$show=false;
													if($data3["name"]!=$dia_diem) {
														if(!$first) {
															$dia_diem=$data3["name"];
															$first=true;
														} else {
															$show=true;
														}
													}
                                                    $num=get_num_hs_ca_hientai($data3["ID_CA"]);
                                                    $has=check_hs_in_ca_hientai($data3["ID_CA"], $hsID);
                                                    $has_codinh=check_hs_in_ca_codinh($data3["ID_CA"], $hsID);
													if($has_codinh) {
														$tkb[]="Thứ ".$data3["thu"];
													}
													if($has) {
														if($has_codinh) {
															echo"<td class='has-ca' style='background:rgba(255,255,255,0.15);'>";
														} else {
															echo"<td class='has-ca' style='background:rgba(255,250,3,0.2);'>";
														}
													} else {
														echo"<td class='has-ca'>";
													}
													echo"<div class='tab-num'><span>$num</span></div>
													<nav>
														<div class='tab-left'><span class='tab-gio'>".$ca_gio[$i]["gio"]."</span></div>";
                                                        echo"<div class='tab-right'><i class='";if($has_codinh){echo"codinh";}echo" fa ";if($has){echo"fa-check-square-o";}else{echo"fa-square-o";}echo"' data-caID='".encode_data($data3["ID_CA"],$code)."'></i></div>";
														if($show) {
															echo"<div class='clear'></div>
															<div class='siso' style='display:block;margin-top:10px;float:none;'>
																<span style='font-size:10px;display:inline-block;margin-top:5px;'>$data3[name]</span>
															</div>";
														}
														echo"</nav>
													</td>";
												} else {
													echo"<td></td>";
												}
											}
										echo"</tr>";
									}
									$max_dem=$max_dem>$dem?$max_dem:$dem;
									echo"<tr><td colspan='$max_dem' style='background:none'></td></tr>";
								}
								echo"<tr><td colspan='$max_dem'><span>Địa điểm học: $dia_diem</span></td></tr>";
							?>
                            </table>
                            <input type="hidden" value="<?php echo implode(" - ",$tkb);?>" id="tkb-hin" />
                        </div>
                        <div class="main-1-left back">
                            <div>
                            	<p class="main-title">Ca thi vào chủ nhật</p>
								<p id="kt-show">8h - 10h</p>
                            </div>
                            <table class="table-tkb">
                           	<?php
								$kt=NULL;
								$dia_diem="";$first=false;
								$result4=get_cakt_mon($monID);
								$j=0;
								while($data4=mysqli_fetch_assoc($result4)) {
									$show=false;
									if($data4["name"]!=$dia_diem) {
										if(!$first) {
											$dia_diem=$data4["name"];
											$first=true;
										} else {
											$show=true;
										}
									}
                                    $num=get_num_hs_ca_hientai($data4["ID_CA"]);
                                    $has=check_hs_in_ca_hientai($data4["ID_CA"], $hsID);
                                    $has_codinh=check_hs_in_ca_codinh($data4["ID_CA"], $hsID);
									if($has_codinh) {
										$kt=$data4["gio"];
									}
									echo"<tr>";
										if($show) {
											echo"<td style='width:50%;'><span>$data4[gio] (".substr($data4["buoi"],1,1).")</span><br /><span style='font-size:10px;margin-top:5px;display:block;'>$data4[name]</span></td>";
										} else {
											echo"<td style='width:50%;'><span>$data4[gio] (".substr($data4["buoi"],1,1).")</span></td>";
										}
										if($has) {
											if($has_codinh) {
												echo"<td class='has-ca' style='background:rgba(255,255,255,0.15);'>";
											} else {
												echo"<td class='has-ca' style='background:rgba(255,250,3,0.2);'>";
											}
										} else {
											echo"<td class='has-ca'>";
										}
										//echo"<span><i class='";if($has_codinh){echo"codinh";}echo" check-buoi fa ";if($has){echo"fa-check-square-o";}else{echo"fa-square-o";}echo"' data-caID='".base64_encode($data4["ID_CA"])."'></i></span><span style='float:right'>$num</span></td>
										echo"<div class='tab-num'><span>$num</span></div>
											<nav>
												<div class='tab-right' style='text-align:center;width:100%;padding:0;'>";
                                                echo"<i class='";if($has_codinh){echo"codinh";}echo" fa ";if($has){echo"fa-check-square-o";}else{echo"fa-square-o";}echo"' data-caID='".encode_data($data4["ID_CA"],$code)."'></i>";
											echo"</div></nav>
										</td>";
									echo"</tr>";
									$j++;
								}
								echo"<tr><td colspan='2'><span>Địa điểm thi: $dia_diem</span></td></tr>";
							?>
                            	<!--<tr>
                                	<td style="background:rgba(255,250,3,0.2)"><span>Tạm</span></td>
                                    <td style="background:rgba(255,255,255,0.15)"><span>Cố định</span></td>
                                </tr>-->
                            </table>
                            <input type="hidden" value="<?php echo $kt; ?>" id="kt-hin" />
                        </div>
                    </div>
                </div>
                
                <div class="clear"></div>
                
<!--                <div class="main-div animated bounceInUp" style="margin-top:15px;">-->
<!--                	<div id="main-info">-->
<!--                    	<div class="main-1-left back" style="margin-right:2%;max-height:none;">-->
<!--                        	<div>-->
<!--                            	<p class="main-title">Lịch học tăng cường</p>-->
<!--                                <p id="tkb-show2">Thứ 2 - Thứ 4 - Thứ 6</p>-->
<!--                            </div>-->
<!--                            <table class="table-tkb" style="border-spacing:0 3px;">-->
<!--                            --><?php
//                            	$tkb=array();
//								$dia_diem="";$first=false;
//                                $result=get_all_cum_link($lmID,$monID);
//								while($data=mysqli_fetch_assoc($result)) {
//                            		echo"<tr style='text-transform:uppercase;' class='big-ca'>
//										<th colspan=''><span>$data[name]</span></th>
//									</tr>
//									<tr style='text-transform:uppercase;'>";
//									$thu_array=array();
//									$dem=0;
//									$query5="SELECT DISTINCT thu FROM cahoc WHERE cum='$data[link]' ORDER BY thu ASC";
//									$result5=mysqli_query($db,$query5);
//									while($data5=mysqli_fetch_assoc($result5)) {
//										echo"<th><span>Thứ $data5[thu]</span></th>";
//										$thu_array[$dem]=$data5["thu"];
//										$dem++;
//									}
//									echo"</tr>";
//									for($i=0;$i<count($ca_gio);$i++) {
//										if($lmID==$ca_gio[$i]["lmID"]) {
//											continue;
//										}
//										echo"
//										<tr class='con-ca'>";
//											for($j=0;$j<count($thu_array);$j++) {
//                                                $query3="SELECT c.ID_CA,c.thu,d.name FROM cahoc AS c
//												INNER JOIN ca_quyen AS q ON q.ID_HS='$hsID' AND q.ID_CA=c.ID_CA
//												INNER JOIN dia_diem AS d ON d.ID_DD=c.ID_DD
//												WHERE c.thu='".$thu_array[$j]."' AND c.ID_GIO='".$ca_gio[$i]["gioID"]."' AND c.cum='$data[link]'";												$result3=mysqli_query($db,$query3);
//												if(mysqli_num_rows($result3)!=0) {
//													$data3=mysqli_fetch_assoc($result3);
//													$show=false;
//													if($data3["name"]!=$dia_diem) {
//														if(!$first) {
//															$dia_diem=$data3["name"];
//															$first=true;
//														} else {
//															$show=true;
//														}
//													}
//                                                    $num=get_num_hs_ca_hientai($data3["ID_CA"]);
//                                                    $has=check_hs_in_ca_hientai($data3["ID_CA"], $hsID);
//                                                    $has_codinh=check_hs_in_ca_codinh($data3["ID_CA"], $hsID);
//													if($has_codinh) {
//														$tkb[]="Thứ ".$data3["thu"];
//													}
//													if($has) {
//														if($has_codinh) {
//															echo"<td class='has-ca' style='background:rgba(255,255,255,0.15);'>";
//														} else {
//															echo"<td class='has-ca' style='background:rgba(255,250,3,0.2);'>";
//														}
//													} else {
//														echo"<td class='has-ca'>";
//													}
//													echo"<div class='tab-num'><span>$num</span></div>
//													<nav>
//														<div class='tab-left'><span class='tab-gio'>".$ca_gio[$i]["gio"]."</span></div>";
//                                                        echo"<div class='tab-right'><i class='";if($has_codinh){echo"codinh";}echo" fa ";if($has){echo"fa-check-square-o";}else{echo"fa-square-o";}echo"' data-caID='".encode_data($data3["ID_CA"],$code)."'></i></div>";
//														if($show) {
//															echo"<div class='clear'></div>
//															<div class='siso' style='display:block;margin-top:10px;float:none;'>
//																<span style='font-size:10px;display:inline-block;margin-top:5px;'>$data3[name]</span>
//															</div>";
//														}
//														echo"</nav>
//													</td>";
//												} else {
//													echo"<td></td>";
//												}
//											}
//										echo"</tr>";
//									}
//									$max_dem=$max_dem>$dem?$max_dem:$dem;
//									echo"<tr><td colspan='$max_dem' style='background:none'></td></tr>";
//								}
//								echo"<tr><td colspan='$max_dem'><span>Địa điểm học: $dia_diem</span></td></tr>";
//                            ?>
<!--                            </table>-->
<!--                            <input type="hidden" value="--><?php //echo implode(" - ",$tkb);?><!--" id="tkb-hin2" />-->
                            <input type="hidden" value="<?php echo $max_dem; ?>" id="max-dem" />
<!--                       	</div>-->
<!--                  	</div>-->
<!--                </div>-->
            </div>
        	<div class="clear"></div>
        </div>
        <div id="myback"></div>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID,$lmID);
	$data0=mysqli_fetch_assoc($result0);
	$thang=count_ngoisao_win($hsID, $lmID);
	$thua=count_ngoisao_lose($hsID, $lmID);
	
	$check0=check_khoa($lmID);
	
	$date=getdate(date("U"));
	$current=$date["wday"]+1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>NGÔI SAO HY VỌNG</title>
        
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
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:6em;color:yellow;}#chart-li1 ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}#chart-li1 ul li > div p {color:#FFF;font-size:14px;line-height: 22px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
			
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			<?php if($_SESSION["test"]==0 && !$check0 && $data0["de"]=="G" && $current!=1) { ?>
            $(document).ready(function() {
                $("#echo_count").html("<span>" + $("#count_thachdau").val() + "</span>"), $("#send").click(function() {
                    return confirm("Bạn có chắc chắn chọn ngôi sao hy vọng? Bạn sẽ bị trừ tiền cược và hoàn lại nếu bạn thắng!") ? ($("#send").hide(), tien = $("#tien-coc").val(), $("#loading").fadeIn("fast"), $("#BODY").css("opacity", "0.3"), $.ajax({
                        url: "https://localhost/www/TDUONG/xuly-ngoisao/",
                        async: !1,
                        data: "ngoisao=" + tien,
                        type: "post",
                        success: function(t) {
                            $("#loading").fadeOut("fast"), $("#BODY").css("opacity", "1"), alert(t), location.reload()
                        }
                    }), !1) : void 0
                }), $("#main-tb ul li .td-action button.delete").click(function() {
                    return confirm("Bạn có chắc chắn muốn hủy ngôi sao hy vọng?") ? (nsID = $(this).attr("data-nsID"), del_li = $(this).closest("li"), $.ajax({
                        url: "https://localhost/www/TDUONG/xuly-ngoisao/",
                        async: !1,
                        data: "nsID=" + nsID,
                        type: "post",
                        success: function(t) {
                            $("#loading").fadeOut("fast"), $("#BODY").css("opacity", "1"), "" == t ? del_li.fadeOut("fast") : alert(t)
                        }
                    }), !1) : void 0
                })
            });
//            <!--$(document).ready(function(){$("#echo_count").html("<span>"+$("#count_thachdau").val()+"</span>"),$("#send").click(function(){return confirm("Bạn có chắc chắn chọn ngôi sao hy vọng? Bạn sẽ bị trừ <?php //echo format_price(get_muctien("hope_star_coc")); ?>// phí đặt cọc")?($("#send").hide(),$("#loading").fadeIn("fast"),$("#BODY").css("opacity","0.3"),$.ajax({url:"https://localhost/www/TDUONG/xuly-ngoisao/",async:!1,data:"ngoisao=true",type:"post",success:function(t){$("#loading").fadeOut("fast"),$("#BODY").css("opacity","1"),alert(t),location.reload()}}),!1):void 0}),$("#main-tb ul li .td-action button.delete").click(function(){return confirm("Bạn có chắc chắn muốn hủy ngôi sao hy vọng?")?(nsID=$(this).attr("data-nsID"),del_li=$(this).closest("li"),$.ajax({url:"https://localhost/www/TDUONG/xuly-ngoisao/",async:!1,data:"nsID="+nsID,type:"post",success:function(t){$("#loading").fadeOut("fast"),$("#BODY").css("opacity","1"),""==t?del_li.fadeOut("fast"):alert(t)}}),!1):void 0})});-->
			<?php } ?>
		</script>
        <script type="text/javascript">
			window.onload = function () {
				var chart = new CanvasJS.Chart("chartContainerNgoiSao",
				{
					theme: "theme2",
					toolTip:{
						enabled: false
					},	
					backgroundColor: "",
					animationEnabled: true,
					interactivityEnabled: false,
					legend:{
						fontFamily: "helvetica",
						horizontalAlign: "center",
						fontSize: 12,
						fontColor: "#FFF",
					},
					data: [
					{       
						type: "doughnut",
						startAngle: -90,
						innerRadius: "75%",
						showInLegend: false,
						legendText: "{name}",
						indexLabelFontFamily:"helvetica" ,
						indexLabelFontSize: 14,
						indexLabelMaxWidth: 100,
						indexLabelFontWeight: "normal",
						dataPoints: [
							{  y: <?php echo $thang;?>, color: "<?php echo $mau;?>", indexLabel: "Thắng: {y}", indexLabelFontColor: "#FFF"},
							{  y: <?php echo $thua;?>, color: "rgba(255,255,255,0.15)", indexLabel: "Thua: {y}", indexLabelFontColor: "#000"},
						]
					}
					]
				});
				chart.render();
			}
        </script>
        <script type="text/javascript" src="https://localhost/www/TDUONG/js/canvasjs.min.js"></script>
       
	</head>

    <body>
    
    	<?php if($check0) { ?>
    	<div class="popup animated bounceIn" style="display:block;">
      		<p>Chức năng ngôi sao hy vọng hiện đang được tạm khóa!</p>
      	</div>
        <?php } else if($data0["taikhoan"]<0) { ?>
        <div class="popup animated bounceIn" style="display:block;">
            <div>
                <p class="title">Tài khoản của bạn đang bị âm, bạn không thể sử dụng ngôi sao hy vọng!<br />(<?php echo format_price($data0["taikhoan"]); ?>)</p>
            </div>
      	</div>
        <?php } else if($data0["de"]!="G") { ?>
            <div class="popup animated bounceIn" style="display:block;">
                <div>
                    <p class="title">Chỉ đề G mới có thể dùng ngôi sao hy vọng! Bạn hãy cố gắng lên!!!</p>
                </div>
            </div>
        <?php } else {} ?>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            
            	<div class="main-div back animated bounceInUp" id="main-top">
                	<div class="ask">
                        <i class="fa fa-question-circle" style="color:<?php echo $mau;?>"></i>
                        <div class="sub-ask">
                            <ul>
                                <li style="margin-bottom:0"><span><span style="font-size:8px">&#9899;</span> Đây là nơi bạn chọn ngôi sao hy vọng cho tuần kiểm tra tới. Nếu điểm số của bạn >= 9 thì bạn sẽ được x2 tiền thưởng</span></li>
                            	<div class="clear"></div>
                            </ul>
                        </div>
                   	</div>
                	<div id="main-person">
                		<h1 style="line-height:98px;">Ngôi sao hy vọng</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                        <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                </div>
                
                <div class="main-div animated bounceInUp">
                	<ul>
                    	<li id="chart-li1" class="li-3">
                        	<div class="main-2 back"><h3>Ngôi sao hy vọng</h3></div>
                            <ul style="margin-top:3px;">
                            	<li>
                                	<div style="width:100%;text-align:center;"><p>X2 số tiền cược trong bài kiểm tra tiếp theo.</p></div>
                                </li>
                                <li>
                                    <div id="main-star" class="hvr-buzz">
                                        <i class="fa fa-star"></i>
                                        <p>X2</p>
                                    </div>
                                </li>
                                <li>
                                    <div><input type="number" min="0" step="10000" max="100000" id="tien-coc" class="input" placeholder="20000" /></div>
                                    <div style="width:50%;"><p>Tiên cược (nghìn đ)</p></div>
                                </li>
                                <li>
                                    <div><input type="text" disabled="disabled" value="<?php echo format_dateup(get_next_CN());?>" class="input" /></div>
                                    <div><p>Ngày thi</p></div>
                                </li>
                                <li style="border-bottom:none">
                               	<?php if($_SESSION["test"]==0) { ?>
                                    <div style="width:59%;"><p>Tài khoản: <?php echo format_price($data0["taikhoan"]); ?></p></div>
                               	<?php } else { ?>
                                	<div style="width:59%;"><p>Tài khoản: </p></div>
								<?php } ?>
                                    <div class="td-action" style="width:38%;margin-top:0;text-align:right;"><button class="submit cancle hvr-buzz" id="send">Chọn</button></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li2" class="li-3">
                        	<div class="main-2 back"><h3>Thống kê thắng - thua</h3></div>
                            <div class="main-chart4" style="height:270px;">
                            	<div class="chart" id="chartContainerNgoiSao"></div>
                            </div>
                        </li>
                        <li id="main-tb" class="li-3">
                        	<div class="main-2 back"><h3>Ngôi sao</h3></div>
                            <ul style="margin-top:3px;">
                            	<li><div style="width:100%;text-align:center;"><p>Kết quả sẽ có vào thứ Năm sau buổi kiểm tra tiếp theo.</p></div></li>
                            <?php
								$dem=0;
								$result=get_current_ngoisao($hsID, $lmID);
								$avata=get_avata_hs($hsID);
								while($data=mysqli_fetch_assoc($result)) {
									echo"<li>
										<div class='td-content'>
											<img src='https://localhost/www/TDUONG/hocsinh/avata/$avata' />
											<p>Bạn đã chọn ngôi sao hy vọng cho buổi kiểm tra vào ngày ".format_dateup($data["buoi"])." với số tiền cược ".format_price($data["tien"]);
											echo"</p>
											<div class='clear'></div>
										</div>
										<div class='td-action'>";
											if($data["status"]=='pending') {
												//echo"<button class='submit delete hvr-buzz' data-nsID='".base64_encode($data["ID_STT"])."'>Hủy</button>";
												echo"<button class='submit'>Đã chọn</button>";
											} 
										echo"</div>
									</li>";
									$dem++;
								}
								echo"<input type='hidden' value='$dem' id='count_thachdau' />";
							?>
                            </ul>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
                
                <div class="main-div animated bounceInUp">
                	<div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Lịch sử ngôi sao hy vọng</h3></div>
                    <div id="main-table">
                    	<table>
                        	<tr id="table-head" class="back tr-big">
                            	<th style="width:15%;"><span>STT</span></th>
                                <th style="width:15%;"><span>Ngày</span></th>
                                <th style="width:35%;" colspan="2"><span>Kết quả</span></th>
                                <th style="width:15%;"><span>Điểm</span></th>
                                <th style="width:20%;"><span>Trạng thái</span></th>
                      		</tr>
                            <?php
								$result=get_history_ngoisao($hsID, $lmID);
								while($data=mysqli_fetch_assoc($result)) {
									$ngay=format_date($data["buoi"]);
									$hs1=mysqli_fetch_assoc(get_hs_short_detail2($data["ID_HS"]));
									$who_win=$data["ketqua"];
									if($who_win==1) {
										$hs1_kq="+".format_money_vnd((get_muctien("hope_star")*2));
										$hs1_icon="fa-thumbs-up";
									} else {
										$hs1_kq="-".format_money_vnd(get_muctien("hope_star_coc"));
										$hs1_icon="fa-thumbs-down";
									}
									echo"<tr class='back'>
										<td><span>".($dem+1)."</span></td>
										<td><span>$ngay</span></td>
										<td><span>$hs1_kq</span></td>
										<td><span class='fa $hs1_icon'><i></i></span></td>
										<td><span>$data[diem]</span></td>
										<td><span>";
										if($data["status"]=="pending") {
											echo"Đang chờ chấp nhận";
										} else if($data["status"]=="accept") {
											echo"Đang chờ kết quả";
										} else {
											echo"Hoàn thành";
										}
										echo"</span></td>
									</tr>";
									$dem++;
								}
							?>
                        </table>
                    </div>
                </div>
                <?php require_once("include/IN.php"); ?>	 
            </div>
        
        </div>
        <?php require_once("include/MENU.php"); ?>
    </body>
</html>

<?php
	ob_end_flush();
	require_once("model/close_db.php");
?>
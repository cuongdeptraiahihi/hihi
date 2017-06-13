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
	$result1=get_hs_short_detail($hsID,$lmID);
	$data1=mysqli_fetch_assoc($result1);
	$thang=count_thachdau_win($hsID, $lmID);
	$thua=count_thachdau_lose($hsID, $lmID);
	
	$check0=check_khoa($lmID);
	
	$date=getdate(date("U"));
	$current=$date["wday"]+1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THÁCH ĐẤU</title>
        
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
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			<?php if($_SESSION["test"]==0 && !$check0 && $data1["taikhoan"]>=get_muctien("thach-dau") && $data1["de"]=="G" && $current!=1) { ?>
			$(document).ready(function(){

                $("#maso").keyup(function() {
                   maso = $(this).val();
                    if(maso.length==7 && maso.indexOf("-")==2 && maso!=$(this).attr("data-temp")) {
                        $("#loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            url: "https://localhost/www/TDUONG/xuly-thachdau/",
                            async: !1,
                            data: "maso0=" + Base64.encode(maso),
                            type: "post",
                            success: function (result) {
                                $("#loading").fadeOut("fast");
                                $("#BODY").css("opacity", "1");
                                if($.isNumeric(result)) {
                                    $("#chap").val(result);
                                    if(result>=0) {
                                        $("#chap-mota").html("Bạn sẽ chấp "+Math.abs(result)+" điểm");
                                    } else {
                                        $("#chap-mota").html("Bạn sẽ được chấp "+Math.abs(result)+" điểm");
                                    }
                                } else {
                                    alert(result);
                                }
                                $("#maso").attr("data-temp",maso);
                            }
                        });
                        return false;
                    }
                });

			    $("#echo_count").html("<span>"+$("#count_thachdau").val()+"</span>");
                $("#send").click(function() {
                    me = $(this);
                    var t = $("#maso").val();
                    a = $("#chap").val();
                    if ($.isNumeric(a) && a >= -4 && 4 >= a && "" != t) {
                        if ("<?php echo base64_encode($_SESSION["cmt"]); ?>" == Base64.encode(t)) {
                            alert("Bạn không thể thách đấu chính mình!");
                        } else if (confirm("Nếu bạn mang bài về nhà làm thì sẽ thua luôn! Bạn có chắc chắn gửi lời thách đấu này?")) {
                            me.hide();
                            $("#loading").fadeIn("fast");
                            $("#BODY").css("opacity", "0.3");
                            $.ajax({
                                url: "https://localhost/www/TDUONG/xuly-thachdau/",
                                async: !1,
                                data: "maso=" + Base64.encode(t) + "&chap=" + a,
                                type: "post",
                                success: function (t) {
                                    $("#loading").fadeOut("fast");
                                    $("#BODY").css("opacity", "1");
                                    alert(t);
                                    location.reload();
                                }
                            });
                        } else {
                            alert("Vui lòng cung cấp đầy đủ thông tin và chính xác!");
                        }
                    } else {
                        alert("Vui lòng cung cấp đầy đủ thông tin và chính xác!");
                    }
                    return false;
                });
                $("#main-tb ul li .td-action button.delete").click(function(){return confirm("Bạn có chắc chắn xóa lời thách đấu này không?")?($(this).closest(".td-action").find("button").hide(),tdID=$(this).attr("data-tdID"),del_li=$(this).closest("li"),$.ajax({url:"https://localhost/www/TDUONG/xuly-thachdau/",async:!1,data:"tdID="+tdID,type:"post",success:function(t){$("#loading").fadeOut("fast"),$("#BODY").css("opacity","1"),location.reload()}}),!1):void 0}),$("#main-tb ul li .td-action button.cancle").click(function(){return confirm("Bạn có chắc chắn từ chối lời thách đấu này không?")?($(this).closest(".td-action").find("button").hide(),tdID=$(this).attr("data-tdID"),$.ajax({url:"https://localhost/www/TDUONG/xuly-thachdau/",async:!1,data:"tdID0="+tdID,type:"post",success:function(t){$("#loading").fadeOut("fast"),$("#BODY").css("opacity","1"),location.reload()}}),!1):void 0}),$("#main-tb ul li .td-action button.accept").click(function(){return confirm("Bạn có chắc chắn đồng ý lời thách đấu này không?")?($(this).closest(".td-action").find("button").hide(),tdID=$(this).attr("data-tdID"),$.ajax({url:"https://localhost/www/TDUONG/xuly-thachdau/",async:!1,data:"tdID1="+tdID,type:"post",success:function(t){$("#loading").fadeOut("fast"),$("#BODY").css("opacity","1"),location.reload()}}),!1):void 0}),$("#MAIN .main-div #main-table table").delegate("tr td input.view","click",function(){return $("#BODY").css("opacity","0.3"),tdID=$(this).attr("data-tdID"),del_tr=$(this).closest("tr"),$.ajax({async:!1,data:"tdID2="+tdID,type:"post",url:"https://localhost/www/TDUONG/xuly-thachdau/",success:function(t){obj=jQuery.parseJSON(t),del_tr.find("td span.kq-1").html(obj.diem1),del_tr.find("td span.kq-2").html(obj.diem2),$("#BODY").css("opacity","1")}}),!1})});
			<?php } ?>
		</script>
        <script type="text/javascript">
			window.onload = function () {
				var chart = new CanvasJS.Chart("chartContainerThachdau",
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

        <div class="popup animated bounceIn" id="popup-loading">
            <p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
        </div>
    
    	<?php if($check0) { ?>
    	<div class="popup animated bounceIn" style="display:block;">
      		<p>Chức năng thách đấu hiện đang được tạm khóa!</p>
      	</div>
        <?php } else if($data1["taikhoan"]<0) { ?>
            <div class="popup animated bounceIn" style="display:block;">
                <div>
                    <p class="title">Tài khoản của bạn đang bị âm, bạn không thể thách đấu!<br />(<?php echo format_price($data1["taikhoan"]); ?>)</p>
                </div>
            </div>
		<?php } else if($data1["de"]!="G") { ?>
            <div class="popup animated bounceIn" style="display:block;">
                <div>
                    <p class="title">Chỉ đề G mới có thể thách đấu! Bạn hãy cố gắng lên!!!</p>
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
                                <li style="margin-bottom:0"><span><span style="font-size:8px">&#9899;</span> Đây là nơi bạn gửi lời thách đấu cho bạn bè (cùng đề kiểm tra) cho bài kiểm tra tuần tới!</span></li>
                            	<div class="clear"></div>
                            </ul>
                        </div>
                   	</div>
                	<div id="main-person">
                		<h1 style="line-height:98px;">Quản lý thách đấu</h1>
                        <div class="clear"></div>
                   	</div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data1["avata"]; ?>" />
                        <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data1["cmt"];?> (<?php echo $data1["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                    <!--<div id="main-code"><h2><?php echo $data1["cmt"];?></h2></div>-->
                </div>
                
                <div class="main-div animated bounceInUp">
                	<ul>
                    	<li id="chart-li1" class="li-3">
                        	<div class="main-2 back"><h3>Gửi lời thách đấu</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div><input type="text" class="input" id="maso" placeholder="99-0xxx" autocomplete="off" data-temp="" /></div>
                                    <div><p>Mã số</p></div>
                                </li>
                                <li>
                                    <div><input type="number" min="-4" step="0.25" max="4" id="chap" disabled="disabled" class="input" value="0" /></div>
                                    <div><p>Chấp (điểm)</p></div>
                                </li>
                                <li>
                                    <div><input type="text" disabled="disabled" min="0" class="input" value="<?php echo get_muctien("thach-dau"); ?>" style="opacity:0.3;" /></div>
                                    <div><p>Số tiền (đ)</p></div>
                                </li>
                                <li>
                                    <div><input type="text" disabled="disabled" value="<?php echo format_dateup(get_next_CN());?>" class="input" style="opacity:0.3;" /></div>
                                    <div><p>Ngày thi</p></div>
                                </li>
                                <li>
                                    <div><input type="text" disabled="disabled" value="<?php echo $data1["level"];?>" class="input" style="opacity:0.3;" /></div>
                                    <div><p>Level</p></div>
                                </li>
                                <li>
                                    <div style="width: 100%;"><p id="chap-mota">Mô tả:</p></div>
                                </li>
                                <li style="border-bottom:none">
                                <?php if($_SESSION["test"]==0) { ?>
                                    <div style="width:59%;"><p>Tài khoản: <?php echo format_price($data1["taikhoan"]); ?></p></div>
                               	<?php } else { ?>
                                	<div style="width:59%;"><p>Tài khoản: </p></div>
								<?php } ?>
                                    <div class="td-action" style="width:38%;margin-top:0;text-align:right;"><button class="submit cancle hvr-buzz" id="send">Gửi</button></div>
                                </li>
                            </ul>
                        </li>
                        <li id="chart-li2" class="li-3">
                        	<div class="main-2 back"><h3>Thống kê thắng - thua</h3></div>
                            <div class="main-chart4" style="height:270px;">
                            	<div class="chart" id="chartContainerThachdau"></div>
                            </div>
                        </li>
                        <li id="main-tb" class="li-3">
                        	<div class="main-2 back"><h3>Lời thách đấu</h3></div>
                            <ul style="margin-top:3px;">
                            <?php
						$dem=0;
						$result=get_current_thachdau($hsID, $lmID);
						while($data=mysqli_fetch_assoc($result)) {
							if($data["ID_HS"]==$hsID) {
								echo"<li>
									<div class='td-content'>
										<img src='https://localhost/www/TDUONG/hocsinh/avata/$data1[avata]' />
										<p>";
                                        if($data["chap"]>=0) {
										    echo"Bạn đã thách đấu với mã số ".get_cmt_hs($data["ID_HS2"])." và chấp $data[chap] điểm vào ngày ".format_dateup($data["buoi"]);
                                        } else {
                                            echo"Bạn đã thách đấu với mã số ".get_cmt_hs($data["ID_HS2"])." và bạn được chấp $data[chap] điểm vào ngày ".format_dateup($data["buoi"]);
                                        }
										echo"</p>
										<div class='clear'></div>
									</div>
									<div class='td-action'>";
										if($data["status"]=='pending') {
											//echo"<button class='submit delete hvr-buzz' data-tdID='".base64_encode($data["ID_STT"])."'>Xóa</button>";
											echo"<button class='submit'>Đã gửi</button>";
										} else {
											echo"<button class='submit'>Đã chấp nhận</button>";
										}
									echo"</div>
								</li>";
							} else {
								echo"<li>
									<div class='td-content'>
										<img src='https://localhost/www/TDUONG/hocsinh/avata/".get_avata_hs($data["ID_HS"])."' />
										<p>";
                                        if($data["chap"]>=0) {
                                            echo"Mã số XXX đã thách đấu bạn và đã chấp bạn $data[chap] điểm vào ngày ".format_dateup($data["buoi"]);
                                        } else {
                                            echo"Mã số XXX đã thách đấu bạn và bạn sẽ chấp $data[chap] điểm vào ngày ".format_dateup($data["buoi"]);
                                        }
										echo"</p>
										<div class='clear'></div>
									</div>
									<div class='td-action'>";
										if($data["status"]=='pending') {
											echo"<button class='submit cancle hvr-buzz' data-tdID='".base64_encode($data["ID_STT"])."'>Từ chối</button>
											<button class='submit accept hvr-buzz' data-tdID='".base64_encode($data["ID_STT"])."'>Chấp nhận</button>";
										} else {
											echo"<button class='submit'>Đã chấp nhận</button>";
										}
									echo"</div>
								</li>";
							}
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
                	<div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Lịch sử thách đấu</h3></div>
                    <div id="main-table">
                    	<table>
                        	<tr id="table-head" class="back tr-big">
                            	<th style="width:10%;"><span>STT</span></th>
                                <th style="width:15%;"><span>Ngày</span></th>
                                <th style="width:20%;"><span>Đi thách đấu</span></th>
                                <th style="width:20%;"><span>Nhận thách đấu</span></th>
                                <th style="width:15%;"><span>Chấp</span></th>
                                <th style="width:20%;"><span></span></th>
                      		</tr>
                            <?php
								$result=get_history_thachdau($hsID, $lmID);
								while($data=mysqli_fetch_assoc($result)) {
									$result1=get_hs_short_detail2($data["ID_HS"]);
									$data1=mysqli_fetch_assoc($result1);
									$result2=get_hs_short_detail2($data["ID_HS2"]);
									$data2=mysqli_fetch_assoc($result2);
									if($data["ketqua"]==$data["ID_HS"]) {
										$kq1="<p class='see-kq win' style='right:10px;'>WIN</p>";
										$kq2="<p class='see-kq lose' style='left:10px;'>LOSE</p>";
									} else if($data["ketqua"]==$data["ID_HS2"]){
										$kq1="<p class='see-kq lose' style='right:10px;'>LOSE</p>";
										$kq2="<p class='see-kq win' style='left:10px;'>WIN</p>";
									} else if($data["ketqua"]=="X"){
										$kq1="<p class='see-kq draw' style='right:10px;'>DRAW</p>";
										$kq2="<p class='see-kq draw' style='left:10px;'>DRAW</p>";
									} else {
										$kq1="";
										$kq2="";
									}
									echo"<tr class='back'>
										<td><span>".($dem+1)."</span></td>
										<td><span>".format_dateup($data["buoi"])."</span></td>";
										echo"<td><span>".$data1["fullname"]."<br />".$data1["cmt"]."<br /><span class='ketqua kq-1'></span></span>$kq1</td>";
										echo"<td><span>".$data2["fullname"]."<br />".$data2["cmt"]."<br /><span class='ketqua kq-2'></span></span>$kq2</td>
										<td><span>".$data["chap"]." điểm</span></td>
										<td>";
											if($data["status"]=="done") {
												echo"<input class='submit view' data-tdID='".base64_encode($data["ID_STT"])."' type='submit' value='Xem kết quả' />";
											}
										echo"</td>
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
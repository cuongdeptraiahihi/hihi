<?php
	ob_start();
	//session_start();
	require_once("model/open_db.php");
	require_once("model/model.php");
	session_start();
	require_once("access_hocsinh.php");
	require_once("model/is_mobile.php");
	$ip=$_SERVER['REMOTE_ADDR'];
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
    $me=md5("123456");
	
	$error=0;
	if(isset($_GET["error"]) && is_numeric($_GET["error"])) {
		$error=$_GET["error"];
	}
	
	$mau="#FFF";
	$result0=get_hs_short_detail($hsID,$lmID);
	$data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SÁCH THAM KHẢO</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/tongquan.css"/>
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/hover.css" />
        <link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/imgareaselect-animated.css" />
        <!--<link rel="stylesheet" type="text/css" href="https://localhost/www/TDUONG/css/materialize.min.css" />-->
        <link href="https://localhost/www/TDUONG/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="https://localhost/www/TDUONG/css/font-awesome.min.css">
        
        <style>
			<?php require_once("include/CSS.php"); ?>
			#COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#69b42e;cursor:pointer;}#COLOR i:hover {color:#246E2C;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:<?php echo $mau;?>;width:7px;height:7px;margin-left:3px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:37%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}#main-tb ul li {color:<?php echo $mau;?>;font-size:14px;padding:5px 10px 5px 10px;border-radius:10px;}#main-tb ul li .td-content p {line-height:22px;letter-spacing:0.5px;float:left;width:188px;}#main-tb ul li .td-content img {height:65px;float:left;width:65px;margin-right:10px;border:2px solid <?php echo $mau;?>;border-radius:1000px;}#main-tb ul li .td-action {margin-top:10px;text-align:right;clear:both;border-radius:10px;}#chart-li1 ul li {padding:5px 10px 5px 10px;}#chart-li1 ul li > div {display:inline-block;width:40%;}#chart-li1 ul li > div#main-star {text-align:center;width:100%;padding:15px 0 15px 0;position:relative;cursor:pointer;}#chart-li1 ul li > div#main-star i {font-size:6em;color:yellow;}#chart-li1 ul li > div#main-star p {position:absolute;z-index:9;color:#000;font-size:22px;font-weight:600;top:40%;}#chart-li1 ul li > div p {color:#FFF;font-size:14px;line-height: 22px;}.see-kq {width:70px;margin:5px auto 5px auto;font-weight:600;font-size:1.5em;background:rgba(0,0,0,0.15);}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.ketqua {font-weight:600;font-size:1.25em;background:rgba(0,0,0,0.15);opacity:0.5;}#MAIN .main-div > ul > li > ul {border-radius:10px;padding:5px 0px 5px 0px;}
			/*.hideme {margin-left:-150%;opacity:0;}*/
            div.div-dad {
                position: relative;
                width: 100% !important;
                overflow: hidden;
            }
			div.div-overplay {
                position: absolute;
                z-index: 99;
                top: 70%;
                left: 0;
                background: rgba(0,0,0,0.7);
                width: 90%;
                height: 90%;
                padding: 5%;
            }
            div.div-dad:hover div.div-overplay {
                background: rgba(0,0,0,0.75);
            }
            .li-3 > ul {
                margin-bottom: 20px;
            }
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){
                $(".mua-sach").click(function () {
                    if(confirm("Bạn có chắc chắn mua cuốn sách này?")) {
                        return true;
                    } else {
                        return false;
                    }
                });
                $("#mua-ok").click(function () {
//                    window.location.href = "https://localhost/www/TDUONG/shop/";
                });
                $(".donhang-xoa").click(function () {
                    var del_tr = $(this).closest("tr");
                    var id = $(this).attr("data-dh");
                    if(confirm("Bạn có chắc chắn không?")) {
                        if (id != "") {
                            $("#popup-loading").fadeIn("fast");
                            $("#BODY").css("opacity", "0.3");
                            $.ajax({
                                async: true,
                                data: "sach_xoa=" + id,
                                type: "post",
                                url: "https://localhost/www/TDUONG/xuly-tailieu/",
                                success: function (result) {
                                    if(result == "ok") {
                                        del_tr.fadeOut("fast");
                                    } else {
                                        alert("Lỗi: " + result);
                                    }
                                    $("#popup-loading").fadeOut("fast");
                                    $("#BODY").css("opacity", "1");
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
			});
		</script>
       
	</head>

    <body>
    
    	<div class="popup animated bounceIn" id="popup-loading">
      		<p><img src="https://localhost/www/TDUONG/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
            if(isset($_GET["error"]) && is_numeric($_GET["error"])) {
                $error_code = addslashes($_GET["error"]);
                switch ($error_code) {
                    case 101:
                        $error_msg = "Hãy chọn mua 1 cuốn sách!";
                        break;
                    case 100:
                        $error_msg = "Dữ liệu không chính xác!";
                        break;
                    case 103:
                        $error_msg  = "Không tồn tại cuốn sách này!";
                        break;
                    case 102:
                        $error_msg = "Bạn không đủ tiền trong tài khoản, bạn có ".format_price(get_tien_hs($hsID));
                        break;
                    case 200:
                        $error_msg = "Bạn đã mua thành công! Hãy gặp thầy để nhận sách nhé!";
                        break;
                    default:
                        $error_msg = "Unknow";
                        break;
                }
                echo"<div class='popup animated bounceIn' style='display: block;'>
                    <div>
                        <p class='title'>$error_msg</p>
                        <div>
                            <button class='submit2 btn_ok' id='mua-ok'><i class='fa fa-check'></i></button>
                        </div>
                    </div>
                </div>";
            }
		?>
        
        <?php
			/*$error="";
			$ava=NULL;
			if(isset($_POST["avata-ok"])) {
				if($_FILES["avata"]["error"]>0) {
				} else {
					$ava=$_FILES["avata"]["name"];
				}
				
				if($ava && valid_image($ava)) {
					move_uploaded_file($_FILES["avata"]["tmp_name"],"hocsinh/avata/".$_FILES["avata"]["name"]);	
					update_avata_hs($hsID,$ava);
				} else {
					$error="<div class='popup' style='display:block'>
						<p>Vui lòng up ảnh có định dạng .png, .jpg, .gif!</p>
					</div>";
				}
			}
			
			echo $error;*/
		?>
                             
      	<div id="BODY">
            
            <div id="MAIN">
            
            	<div class="main-div back animated bounceInUp" id="main-top">
                    <div id="main-person">
                        <h1 style="line-height: 98px;">Sách tham khảo</h1>
                        <div class="clear"></div>
                    </div>
                    <div id="main-avata">
                    	<img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $data0["avata"]; ?>" />
                        <a href="https://localhost/www/TDUONG/ho-so/" title="Hồ sơ cá nhân">
                        	<p><?php echo $data0["cmt"];?> (<?php echo $data0["de"];?>)</p>
                            <i class="fa fa-pencil"></i>
                        </a>
                   	</div>
                    <!--<div id="main-code"><h2><?php echo $data0["cmt"];?></h2></div>-->
                </div>

                <div class="main-div animated bounceInUp">
                    <?php
                        $dem=0;
                        $result=get_list_sach($lmID);
                        while($data=mysqli_fetch_assoc($result)) {
                            $dem++;
                            if($dem == 1 || $dem % 4 == 1) {
                                echo"<ul>";
                            }
                            echo"<li id='chart-li1' class='li-4'>
                                <ul>
                                    <li>
                                        <div class='div-dad'>";
                                            if(stripos($data["image"], "http") === false) {
                                                echo"<img src='https://localhost/www/TDUONG/sach/$data[image]/' style='width: 100%;height: auto;' />";
                                            } else {
                                                echo"<img src='$data[image]' style='width: 100%;height: auto;' />";
                                            }
                                            echo"<form action='https://localhost/www/TDUONG/shop-mua/' method='post'>
                                                <div class='div-overplay'>
                                                    <p><input type='hidden' name='id-sach' value='".encode_data($data["ID_S"],$me)."' /></p>
                                                    <p><span style='text-decoration: underline;'>Giá:</span> <strong>".format_price($data["tien"] - ($data["tien"]*$data["discount"]/100))."</strong></p>
                                                    <div>
                                                        <button type='submit' name='mua-sach' class='submit mua-sach' style='margin-top: 10px;'>Mua ngay</button>
                                                        <select class='submit' name='sl-sach' style='padding: 9px;'>
                                                            <option value='1'>1</option>
                                                            <option value='2'>2</option>
                                                            <option value='3'>3</option>
                                                            <option value='4'>4</option>
                                                            <option value='5'>5</option>
                                                            <option value='6'>6</option>
                                                            <option value='7'>7</option>
                                                            <option value='8'>8</option>
                                                            <option value='9'>9</option>
                                                            <option value='10'>10</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>";
                            if($dem % 4 == 0) {
                                echo"</ul>";
                            }
                        }
                        if($dem == 0) {
                            echo"<ul><li id='chart-li1'>
                                <ul>
                                    <li>
                                        <div class='div-dad'>
                                            <p>Hiện không có sách nào được bán!</p>
                                        </div>
                                    </li>
                                </ul>
                            </li></ul>";
                        }
                    ?>
                    <div class="clear"></div>
                </div>
                <div class="main-div animated bounceInUp">
                    <div class="main-1 back" style="border-bottom:none;border-bottom-left-radius:0;border-bottom-right-radius:0;"><h3>Lịch sử mua sách</h3></div>
                    <div id="main-table">
                        <table>
                            <tr id="table-head" class="back tr-big">
                                <th style="width:5%;"><span>STT</span></th>
                                <th><span>Sách</span></th>
                                <th><span>Giá tiền</span></th>
                                <th style="width:5%;"><span>SL</span></th>
                                <th><span>Thành tiền</span></th>
                                <th style="width:10%;"><span>Thời điểm</span></th>
                                <th style="width:10%;"><span></span></th>
                            </tr>
                            <?php
                                $dem=1;
                                $query="SELECT s.*,a.name FROM sach_mua AS s
                                    INNER JOIN sach AS a ON a.ID_S=s.ID_S
                                    WHERE s.ID_HS='$hsID'
                                    ORDER BY s.ID_STT DESC";
                                $result=mysqli_query($db,$query);
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<tr class='back'>
                                        <td><span>$dem</span></td>
                                        <td><span>$data[name]</span></td>
                                        <td><span>".format_price($data["tien"])."</span></td>
                                        <td><span>$data[sl]</span></td>
                                        <td><span>".format_price($data["total"])."</span></td>
                                        <td><span>".format_datetime($data["datetime"])."</span></td>
                                        <td>";
                                            if($data["status"]==1) {
                                                echo"<span>Hoàn thành</span>";
                                            } else if($data["status"]==0) {
                                                echo"<input type='submit' class='submit donhang-xoa' data-dh='".encode_data($data["ID_STT"],$me)."' value='Hủy' />";
                                            } else {

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
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
    $staff2=$kieu2=0;
    $ngay2=$ngay_start2=$ngay_end2=$seri_start2=$seri_end2="";
    if(isset($_GET["staff"]) && is_numeric($_GET["staff"]) && isset($_GET["kieu"]) && is_numeric($_GET["kieu"])) {
        $staff2=$_GET["staff"];
        $kieu2=$_GET["kieu"];
        switch ($kieu2) {
            case 1: {
                $ngay2=$_GET["ngay"];
                break;
            }
            case 2: {
                $ngay_start2=$_GET["ngay_start"];
                $ngay_end2=$_GET["ngay_end"];
                break;
            }
            case 3: {
                $seri_start2 = $_GET["seri_start"];
                $seri_end2 = $_GET["seri_end"];
                break;
            }
            default:
                $error=true;
                break;
        }
    }

    $result0=get_info_tro_giang($id);
    $data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>KIỂM TRA TIỀN</title>
        
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
			.fa {font-size:250%;}
            #table-result tr td, #table-result tr th  {padding:5px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function(){
//                $("input.select-date").datepicker({
//                    dateFormat: 'yy-mm-dd',
//                    defaultDate: new Date('<?php //echo date("Y-m-d"); ?>//')
//                });
                $("li.kieu-more").hide();
                <?php
                switch ($kieu2) {
                    case 1:
                        echo'$("li#kieu-ngay").fadeIn("fast");';
                        break;
                    case 2:
                        echo'$("li#kieu-khoang-ngay").fadeIn("fast");';
                        break;
                    case 3:
                        echo'$("li#kieu-khoang-seri").fadeIn("fast");';
                        break;
                    default:
                        break;
                }
                ?>
                $("select#select-kieu").change(function() {
                    var value = parseInt($(this).val());
                    switch(value) {
                        case 1:
                            $("li.kieu-more").hide();
                            $("li#kieu-ngay").fadeIn("fast");
                            break;
                        case 2:
                            $("li.kieu-more").hide();
                            $("li#kieu-khoang-ngay").fadeIn("fast");
                            break;
                        case 3:
                            $("li.kieu-more").hide();
                            $("li#kieu-khoang-seri").fadeIn("fast");
                            break;
                        default:
                            $("li.kieu-more").fadeOut("fast");
                            break;
                    }
                });
                $("input.detail-hoc").click(function() {
                    var oID = $(this).attr("data-oID");
                    $("table#table-result tr.tien-hoc-" + oID).fadeToggle("fast");
                });
                $("input.detail-nap").click(function() {
                    var oID = $(this).attr("data-oID");
                    $("table#table-result tr.tien-nap-" + oID).fadeToggle("fast");
                });
            });
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
            <?php
            $error=false;
            $staff=$kieu=0;
            $ngay=$ngay_start=$ngay_end=$seri_start=$seri_end="";
            if(isset($_POST["xem"])) {
                if(isset($_POST["staff"])) {
                    $staff=$_POST["staff"];
                }
                if(isset($_POST["kieu"])) {
                    $kieu=$_POST["kieu"];
                    switch ($kieu) {
                        case 1: {
                            if(isset($_POST["kieu-ngay"])) {
                                $ngay=$_POST["kieu-ngay"];
                            }
                            break;
                        }
                        case 2: {
                            if(isset($_POST["kieu-ngay-start"])) {
                                $ngay_start=trim($_POST["kieu-ngay-start"]);
                            }
                            if(isset($_POST["kieu-ngay-end"])) {
                                $ngay_end=trim($_POST["kieu-ngay-end"]);
                            }
                            break;
                        }
                        case 3: {
                            if (isset($_POST["kieu-seri-start"])) {
                                $seri_start = trim($_POST["kieu-seri-start"]);
                            }
                            if (isset($_POST["kieu-seri-end"])) {
                                $seri_end = trim($_POST["kieu-seri-end"]);
                            }
                            break;
                        }
                        default:
                            $error=true;
                            break;
                    }
                }

                if(($ngay!="" || ($ngay_start!="" && $ngay_end!="") || ($seri_start && $seri_end)) && !$error) {
                    switch ($kieu) {
                        case 1:
                            header("location:http://localhost/www/TDUONG/trogiang/kiem-tra-tien/ngay/$staff/$ngay/");
                            exit();
                            break;
                        case 2:
                            header("location:http://localhost/www/TDUONG/trogiang/kiem-tra-tien/khoang-ngay/$staff/$ngay_start/$ngay_end/");
                            exit();
                            break;
                        case 3:
                            header("location:http://localhost/www/TDUONG/trogiang/kiem-tra-tien/khoang-seri/$staff/$seri_start/$seri_end/");
                            exit();
                            break;
                        default:
                            header("location:http://localhost/www/TDUONG/trogiang/kiem-tra-tien/");
                            exit();
                            break;
                    }
                }
            }
            ?>
            
            <div id="MAIN">
            
            	<div class="main-div back animated bounceInUp" id="main-top">
                    <div id="main-person">
                        <h1 style="line-height:98px;">KIỂM TRA TIỀN</h1>
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

                <form action="http://localhost/www/TDUONG/trogiang/kiem-tra-tien/" method="post">
                <div class="main-div animated bounceInUp">
                    <ul>
                        <li id="chart-li1" class="li-3">
                            <div class="main-2 back"><h3>Tùy chọn</h3></div>
                            <ul style="margin-top:3px;">
                                <li>
                                    <div><p>Trợ giảng</p></div>
                                    <div style="width:58%;"> <select class="input" style="height:auto;width:100%;" id="select-staff" name="staff">
                                            <option value="0" <?php if($staff2==0){echo"selected='selected'";} ?>>Tất cả</option>
                                            <?php
                                            $query="SELECT o.ID_O,o.note,o.note2,i.* FROM options AS o INNER JOIN info_trogiang AS i ON i.ID_O=o.ID_O WHERE o.note LIKE '%(chính)' AND o.type='tro-giang-code' ORDER BY o.ID_O DESC";
                                            $result=mysqli_query($db,$query);
                                            while($data=mysqli_fetch_assoc($result)) {
                                                echo"<option value='$data[ID_O]' ";if($data["ID_O"]==$staff2){echo"selected='selected'";}echo" >$data[note]</option>";
                                            }
                                            ?>
                                        </select></div>
                                </li>

                                <li>
                                    <div><p>Kiểu kiểm tra</p></div>
                                    <div style="width:58%;"><select class="input" style="height:auto;width:100%;" id="select-kieu" name="kieu">
                                            <option value="0" <?php if($kieu2==0){echo"selected='selected'";} ?>>Chọn kiểu</option>
                                            <option value="1" <?php if($kieu2==1){echo"selected='selected'";} ?>>Ngày</option>
                                            <option value="2" <?php if($kieu2==2){echo"selected='selected'";} ?>>Khoảng ngày</option>
                                            <option value="3" <?php if($kieu2==3){echo"selected='selected'";} ?>>Khoảng số seri</option>
                                        </select></div>
                                </li>

                                <li id="kieu-ngay" class="kieu-more">
                                    <div><p>Ngày kiểm tra</p></div>
                                    <div style="width:58%;"><input class="input select-date" name="kieu-ngay" value="<?php echo $ngay2; ?>" type="text" placeholder="Click vào chọn ngày" /></div>
                                </li>

                                <li id="kieu-khoang-ngay" class="kieu-more">
                                        <div><p><input class="input select-date" name="kieu-ngay-start" value="<?php echo $ngay_start2; ?>" type="text" placeholder="Ngày bắt đầu" /></p></div>
                                        <div><p><input class="input select-date" name="kieu-ngay-end" value="<?php echo $ngay_end2; ?>" type="text" placeholder="Ngày kết thúc" /></div>
                                </li>

                                <li id="kieu-khoang-seri" class="kieu-more">
                                    <div><p><input class="input" name="kieu-seri-start" type="text" value="<?php echo $seri_start2; ?>" placeholder="Số bắt đầu" /></p></div>
                                    <div style="width:58%;"><p><input class="input" name="kieu-seri-end" type="text" value="<?php echo $seri_end2; ?>" placeholder="Số kết thúc" /></p></div>
                                </li>

                                <li>
                                    <div></div>
                                    <div style="width: 58%;"><th colspan="2"><p><input class="submit" style="width:50%;font-size:1.375em;float:right;" name="xem" type="submit" id="xem" value="Xem" /></p></th></div>
                                </li>
                            </ul>


                        </li>
                        <li id="chart-li1 info-mon" class="li-3" style="width:65.34%;">
                            <div class="main-2 back"><h3>Kết quả</h3></div>
                            <ul style="margin-top:3px;">
                            <div class="main-div animated bounceInUp"
                                <div id="main-info">
                                    <div class="main-1-left" style="padding-top:2px;margin-right:0;max-height:none;width: 100%;float: none; margin-top:3px;">
                                        <table class="table table-2" id="table-result" style="width: 100%;display: inline-table;color:white;text-align:center;padding:7px;">
                                            <tr class="back">
                                                <th style="width: 10%;"><span>STT</span></th>
                                                <th><span>Học sinh</span></th>
                                                <th style="width: 20%;"><span>Ngày nhập</span></th>
                                                <th style="width: 20%;"><span>Seri</span></th>
                                                <th style="width: 20%;"><span>Số tiền</span></th>
                                            </tr>
                                            <?php
                                            $total=$dem=0;
                                            if($kieu2!=0) {
                                                if ($staff2 != 0) {
                                                    $query = "SELECT ID_O,note FROM options WHERE type='tro-giang-code' AND ID_O='$staff2' ORDER BY ID_O ASC";
                                                    $result = mysqli_query($db, $query);
                                                    $data = mysqli_fetch_assoc($result);
                                                    $temp = show_tien($data["ID_O"], $data["note"]);
                                                    $dem += $temp[1];
                                                    $total += $temp[0];
                                                    echo "<tr><td colspan='5'></td></tr>
                                        <tr style='background:rgba(62,96,111,0.5);'>
                                            <th><span>$dem</span></th>
                                            <th colspan='3'><span style='text-transform:uppercase;'>Tổng tiền thu được tất cả</span></th>
                                            <th style='background:rgba(239,83,80,0.5);'><span>" . format_price($total) . "</span></th>
                                        </tr>";
                                                } else {
                                                    $query = "SELECT ID_O,note FROM options WHERE type='tro-giang-code' AND note LIKE '%(chính)' ORDER BY ID_O ASC";
                                                    $result = mysqli_query($db, $query);
                                                    while ($data = mysqli_fetch_assoc($result)) {
                                                        $temp = show_tien($data["ID_O"], $data["note"]);
                                                        $dem += $temp[1];
                                                        $total += $temp[0];
                                                    }
                                                    echo "<tr><td colspan='6'></td></tr>
                                        <tr style='background:rgba(62,96,111,0.5);'>
                                            <th><span>$dem</span></th>
                                            <th colspan='3'><span style='text-transform:uppercase;'>Tổng tiền thu được tất cả</span></th>
                                            <th style='background:rgba(239,83,80,0.5);'><span>" . format_price($total) . "</span></th>
                                        </tr>";
                                                }
                                            }
                                            ?>
                                        </table>
                                    </div>
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
            </form>
            </ul>
            </div>
        </div>
        <?php require_once("include/MENU.php"); ?>
    </body>
</html>
<?php
function show_tien($oID,$name) {
    global $db, $ngay2, $ngay_start2, $ngay_end2, $seri_start2, $seri_end2;

    if($ngay2!="") {
        $string1="t.date_nhap='$ngay2' AND";
        $string2="t.date='$ngay2' AND";
    } else if($ngay_start2!="" && $ngay_end2!="") {
        $string1="t.date_nhap>='$ngay_start2' AND t.date_nhap<='$ngay_end2' AND";
        $string2="t.date>='$ngay_start2' AND t.date<='$ngay_end2' AND";
    } else if($seri_start2!="" && $seri_end2!=""){
        $string1="t.code>='$seri_start2' AND t.code<='$seri_end2' AND";
        $string2="t.code>='$seri_start2' AND t.code<='$seri_end2' AND";
    } else {
        $string1="";
        $string2="";
    }

    $total=$dem=0;

    $dem1 = 0;
    $total1 = $total2 = 0;
    echo "<tr style='background:rgba(62,96,111,0.5);'><th colspan='5'><span>Trợ giảng $name</span></th></tr>";
    $query2 = "SELECT t.*,h.cmt,m.name FROM tien_hoc AS t 
        INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS 
        INNER JOIN lop_mon AS m ON m.ID_LM=t.ID_LM
        WHERE $string1 t.who='$oID' ORDER BY t.code DESC,t.ID_STT DESC";
    $result2 = mysqli_query($db, $query2);
    while ($data2 = mysqli_fetch_assoc($result2)) {
        $note = "";
        if ($data2["note"] != "") {
            $note = "<br />" . $data2["note"];
        }
        echo "<tr class='tien-hoc-$oID' style='display:none;'>
                <td><span>" . ($dem1 + 1) . "</span></td>
                <td><span>$data2[cmt]</span></td>
                <td><span>" . format_dateup($data2["date_nhap"]) . "</span></td>
                <td><span>$data2[code]</span></td>
                <td><span>" . format_price($data2["money"]) . "</span></td>
            </tr>";
        $total1 += $data2["money"];
        $dem1++;
    }
    if ($dem1 != 0) {
        echo "<tr style='background:rgba(150,200,243,0.5);'>
                <td><span>$dem1</span></td>
                <td><input type='button' class='submit detail-hoc' value='Xem' data-oID='$oID' /></td>
                <td colspan='2'><span>Tổng tiền học của $name thu</span></td>
                <td><span>" . format_price($total1) . "</span></td>
            </tr>";
    }
    $dem2 = 0;
    $query2 = "SELECT t.*,h.cmt FROM tien_ra AS t 
        INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS 
        WHERE $string2 t.object='$oID' AND t.string='nap-tien' ORDER BY t.code DESC";
    $result2 = mysqli_query($db, $query2);
    while ($data2 = mysqli_fetch_assoc($result2)) {
        echo "<tr class='tien-nap-$oID' style='display:none;'>
                <td><span>" . ($dem2 + 1) . "</span></td>
                <td><span>$data2[cmt]</span></td>
                <td><span>" . format_dateup($data2["date"]) . "</span></td>
                <td><span>$data2[code]</span></td>
                <td><span>" . format_price($data2["price"]) . "</span></td>
            </tr>";
        $total2 += $data2["price"];
        $dem2++;
    }
    if ($dem2 != 0) {
        echo "<tr style='background:rgba(150,200,243,0.5);'>
                <td><span>$dem2</span></td>
                <td><input type='button' class='submit detail-nap' value='Xem' data-oID='$oID' /></td>
                <td colspan='2'><span>Tổng tiền nạp của $name thu</span></td>
                <td><span>" . format_price($total2) . "</span></td>
            </tr>";
    }
    echo "<tr style='background:rgba(239,83,80,0.5);'>
            <th><span>" . ($dem1 + $dem2) . "</span></th>
            <th colspan='3'><span style='text-transform:uppercase;'>Tổng tiền của $name thu</span></th>
            <th><span>" . format_price($total1 + $total2) . "</span></th>
        </tr>";
    $total += $total1 + $total2;
    $dem += $dem1 + $dem2;
    return array($total,$dem);
}
ob_end_flush();
require_once("../model/close_db.php");
?>

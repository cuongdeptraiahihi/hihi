<?php
ob_start();
session_start();
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
if(isset($_GET["date"])) {
    $date=$_GET["date"];
} else {
    $date="0000-00";
}
$monID=$_SESSION["mon"];
//$mau=get_user_mau($hsID);
$mau="#3E606F";

$result2=get_mon_info($monID);
$data2=mysqli_fetch_assoc($result2);
$mon_name=$data2["name"];
$diem_string=$data2["diem"];

?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>BẢNG LƯƠNG TRỢ GIẢNG</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/tongquan_admin2.css"/>
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css" />
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">

        <style>
            #COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#3E606F;cursor:pointer;}#COLOR i:hover {color:#3E606F;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-table table td {border:1px solid <?php echo $mau?>;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}#MAIN .main-div .main-chart3 nav.chart-button:hover {background:<?php echo $mau;?>;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:#3E606F;width:7px;height:7px;margin-left:2.5px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:35%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border:1px solid <?php echo $mau; ?>;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.back {background-color:<?php echo $mau; ?>;}

            #MAIN .main-div #main-table .table td span {color:#3E606F;font-size:10px;}#MAIN .main-div #main-table .table td span.span-boi {font-weight:600;text-transform:uppercase;}#MAIN .main-div #main-table .table td.td-boi {background:#3E606F;border-right:1px solid #FFF;}#MAIN .main-div #main-table .table td.td-boi span {color:#FFF;font-weight:600;text-transform:uppercase;}#MAIN .main-div #main-table .table td.td-boi span.span-boi2 {font-size:22px;}.input {background:none;color:#3E606F;font-size:10px;}

            #UPDATE {position:fixed;right:10px;top:10px;z-index:99;}
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {
                $("#tong-thuong-show").html($("#tong-thuong").val());
                $("#tong-hs-show").html($("#tong-hs").val() + " em");
            });
        </script>

    </head>

    <body>

    <div class="popup" id="popup-loading">
        <p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
    </div>

    <div id="BODY">

        <div id="MAIN">

            <div id="UPDATE">
            </div>

            <div class="main-div back" id="main-top">
                <h1>
                    Bảng lương trợ giảng<br />Môn <?php echo $mon_name;?><br /><?php echo format_month($date); ?>
                </h1>
            </div>

            <div class="main-div hideme">
                <div id="main-table">
                    <table class="table" style="margin-top:25px;">
                        <tr>
                            <td class="td-boi" style="width:25%;"><span>Tổng tiền <?php echo format_month($date); ?></span></td>
                            <td style="width:25%;"><span class="span-boi" id="tong-thuong-show"></span></td>
                            <td class="td-boi" style="width:25%;"><span>Số trợ giảng</span></td>
                            <td style="width:25%;"><span class="span-boi" id="tong-hs-show"></span></td>
                        </tr>
                    </table>
                    <table class="table" style="margin-top:25px;">
                        <tr>
                            <td class="td-boi" style="width:5%;"><span>STT</span></td>
                            <td class="td-boi" style="width:15%;"><span>Họ và Tên</span></td>
                            <td class="td-boi" style="width:10%;"><span>SĐT</span></td>
                            <td class="td-boi" style="width:10%;"><span>Ngày vào làm</span></td>
                            <td class="td-boi" style="width:10%;"><span>Lương</span></td>
                            <td class="td-boi" style="width:20%;"><span>Mô tả</span></td>
                            <td class="td-boi" style="width:15%;"><span>Người trả</span></td>
                            <td class="td-boi" style="width:15%;"><span>Ghi chú</span></td>
                        </tr>
                        <?php
                        $dem=$total=0;
                        $query="SELECT o.ID_O,o.note,o.note2,i.mota,i.phone,i.date_in,t.money FROM options AS o INNER JOIN info_trogiang AS i ON i.date_in<='$date-07' AND i.ID_O=o.ID_O INNER JOIN tien_thanh_toan AS t ON t.datetime='$date' AND t.object=o.ID_O AND t.ID_MON='$monID' AND t.note='tro_giang' WHERE o.type='tro-giang-code' ORDER BY i.date_in ASC,o.note ASC";
                        $result=mysqli_query($db,$query);
                        while($data=mysqli_fetch_assoc($result)) {

                            $who=get_who_paid($data["ID_O"]);

                            if($dem%2!=0) {
                                echo"<tr style='background:#D1DBBD'>";
                            } else {
                                echo"<tr>";
                            }
                            echo"<td><span>".($dem+1)."</span></td>
                                <td><span>$data[note]</span></td>
                                <td><span>$data[phone]</span></td>
                                <td><span>".format_dateup($data["date_in"])."</span></td>
                                <td><span>".format_price($data["money"])."</span></td>
                                <td><span>$data[mota]</span></td>
                                <td><span>$who</span></td>";
                                if($data["note2"]==1) {
                                    echo"<td><span></span></td>";
                                } else {
                                    echo"<td style='background: yellow'><span>Nay đã nghỉ</span></td>";
                                }
                            echo"</tr>";
                            $total+=$data["money"];
                            $dem++;
                        }
                        echo"<tr>
                            <td class='td-boi' style='border-bottom: 1px solid #FFF;' colspan='4'><span>Tổng cộng</span></td>
                            <td class='td-boi' style='border-bottom: 1px solid #FFF;'><span>".format_price($total)."</span></td>
                            <td class='td-boi' style='border-bottom: 1px solid #3E606F;' colspan='3'><span></span></td>
                        </tr>";
                        echo"<input type='hidden' value='".format_price($total)."' id='tong-thuong' />
								<input type='hidden' value='$dem' id='tong-hs' />";
                        ?>
                    </table>
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
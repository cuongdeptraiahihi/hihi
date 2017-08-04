<?php
    ob_start();
    session_start();
    require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
    if(isset($_GET["date"]) && isset($_GET["lm"]) && is_numeric($_GET["lm"]) && isset($_GET["loai"]) && is_numeric($_GET["loai"])) {
        $lmID=$_GET["lm"];
        $date=$_GET["date"];
        $loai=$_GET["loai"];
    } else {
        $lmID=0;
        $date="0000-00";
        $loai=0;
    }
    $monID=get_mon_of_lop($lmID);
    //$mau=get_user_mau($hsID);
    $mau="#3E606F";
    $mon_name=get_mon_name($monID);
    $mon_lop_name=get_lop_mon_name($lmID);

    $staff=array();
    $query="SELECT ID_O,note FROM options WHERE type='tro-giang-code' ORDER BY ID_O DESC";
    $result=mysqli_query($db,$query);
    while($data=mysqli_fetch_assoc($result)) {
        $staff[$data["ID_O"]]=$data["note"];
    }

    $muc_tien=get_muctien("tien_hoc_".unicode_convert($mon_name));
    $tien_tra=get_muctien("tien_hoc_tra_".unicode_convert($mon_name));
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>BẢNG LƯƠNG CHI TIẾT</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/tongquan_admin2.css"/>
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css" />
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">

        <style>
            #COLOR {position:absolute;z-index:99;top:10px;left:10px;width:auto;height:auto;}#COLOR i {font-size:44px;color:#3E606F;cursor:pointer;}#COLOR i:hover {color:#3E606F;}#COLOR i:hover ul {display:block;}#COLOR ul {width:180px;height:180px;display:none;background:#FFF;padding-left:10px;padding-top:10px;border:1px solid #dfe0f4;}#COLOR ul li {float:left;width:50px;height:50px;margin-right:10px;margin-bottom:10px;}#COLOR ul li .user-color {width:100%;height:100%;cursor:pointer;opacity:0.4;}#COLOR ul li .user-color:hover {opacity:1;}#MAIN .main-div #main-table table td {border:1px solid <?php echo $mau?>;}#MAIN .main-div #main-info > div p, #MAIN .main-div .main-note p, #MAIN .main-div #main-info #main-1-mid ul li span, #MAIN .main-div #main-info #main-1-mid ul li i, #MAIN .main-div .main-num p, #MAIN .main-div #main-table table td span, #MAIN .main-div #main-chart button i, #MAIN .main-div ul > ol .main-title, #MAIN .main-div ul > ol .main-point, #MAIN .main-div .main-chart3 nav.chart-line ul li i, .progress-indicator>li, .progress-indicator>li .bubble, .ask:hover .sub-ask ul li, #MAIN .main-div .main-chart3 nav.chart-button > a, #MAIN .main-div #main-chart #chart-len ul li span {color:<?php echo $mau?>;line-height:22px;}#progress1>li .bubble{margin-top:6px;}#progress2>li .bubble{margin-top:3.5px;}.progress-indicator>li.completed .bubble{background-color:<?php echo $mau; ?>}.progress-indicator>li .bubble:after, .progress-indicator>li .bubble:before {background-color:<?php echo $mau; ?>;position:relative;}.progress-indicator>li .bubble:before {top:12px;}.progress-indicator>li .bubble:after {top:7px;}.progress-indicator>li.non-completed .bubble:after, .progress-indicator>li.non-completed .bubble:before {display:none;}#MAIN .main-div .main-chart3 nav.chart-button:hover {background:<?php echo $mau;?>;}.line-ver {border-left:1px dashed <?php echo $mau;?>;width:1px;position:absolute;z-index:-1;left:5.5px;}.progress-indicator>li.non-still .bubble {background-color:#3E606F;width:7px;height:7px;margin-left:2.5px;}.last-completed .bubble:before, .last-completed .bubble:after {display:none !important;}#MAIN .main-div .main-chart4 .chart-info {position:absolute;z-index:9;top:35%;}#MAIN .main-div .main-chart4 .chart-info p {font-size:2.75em;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.check-buoi {font-size:1.25em;cursor:pointer;color:<?php echo $mau;?>;}#MAIN .main-div #main-info table tr td span i.codinh {color:#FFF;}#main-chart2 ul {width:90%;margin:auto;height:45px;}#main-chart2 ul li {float:left;text-align:center;padding:7px 0 7px 0;border:1px solid <?php echo $mau; ?>;border-radius:20px;}#main-chart2 ul li p {color:<?php echo $mau;?>;font-size:1.375em;}.back {background-color:<?php echo $mau; ?>;}

            #MAIN .main-div #main-table .table td span {color:#3E606F;font-size:10px;}#MAIN .main-div #main-table .table td span.span-boi {font-weight:600;text-transform:uppercase;}#MAIN .main-div #main-table .table td.td-boi {background:#3E606F;border-right:1px solid #FFF;}#MAIN .main-div #main-table .table td.td-boi span {color:#FFF;font-weight:600;text-transform:uppercase;}#MAIN .main-div #main-table .table td.td-boi span.span-boi2 {font-size:22px;}.input {background:none;color:#3E606F;font-size:10px;}

            #UPDATE {position:fixed;right:10px;top:10px;z-index:99;}#table-info tr td:hover {background: #3E606F;}#table-info tr td:hover span {color:#FFF !important;}
        </style>

        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {
                $("#tong-thuong-show").html($("#tong-thuong").val());
                $("#tong-hs-show").html($("#tong-hs").val() + " em");

                setTimeout(function() {
                    var ajax_data = "[";
                    $("table#table-info tr").each(function (index, element) {
                        var td_cur = $(element).find("td:last-child");
                        if(td_cur.hasClass("td-else")) {
                            var hsID = td_cur.attr("data-hsID");
                            var date_in = td_cur.attr("data-in");
                            var old = td_cur.attr("data-old");
                            ajax_data += '{"index":"' + index + '","hsID":"' + hsID + '","date_in":"' + date_in + '","old":"' + old + '"},';
                        }
                    });
                    ajax_data += '{"lmID":"<?php echo $lmID; ?>","monID":"<?php echo $monID; ?>","date":"<?php echo $date; ?>"}';
                    ajax_data += "]";
                    if(ajax_data != "") {
                        $.ajax({
                            async: true,
                            data: "ajax_data=" + ajax_data,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-bangluong/",
                            success: function (result) {
                                if(result != "none") {
                                    var obj = jQuery.parseJSON(result);
                                    $("table#table-info tr").each(function (index, element) {
                                        var td_cur = $(element).find("td:last-child");
                                        if (td_cur.hasClass("td-else")) {
                                            td_cur.html("<span>" + obj[index].tien + "</span>");
                                            td_cur.removeClass("td-else");
                                        }
                                    });
                                }
                            }
                        });
                    }
                },500);
            });
        </script>

    </head>

    <body>

    <div class="popup" id="popup-loading">
        <p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
    </div>

    <div id="BODY">

        <div id="MAIN">

            <div id="UPDATE">
            </div>

            <div class="main-div back" id="main-top">
                <h1>
                    Bảng lương chi tiết<br />Môn <?php echo $mon_lop_name;?><br />
                    <?php
                    switch($loai) {
                        case 1:
                            echo"Học sinh đóng tiền học bình thường";
                            break;
                        case 2:
                            echo"Học sinh được giảm học phí";
                            break;
                        case 3:
                            echo"Học sinh đóng không đủ tháng";
                            break;
                        case 4:
                            echo"Học sinh chưa đóng học phí";
                            break;
                        case 5:
                            echo"Học sinh nghỉ hẳn";
                            break;
                        case 6:
                            echo"Tiền thưởng + Lương trợ giảng";
                            break;
                    }
                    ?>, <?php echo format_month($date); ?>
                </h1>
            </div>

            <div class="main-div hideme">
                <div id="main-table">
                    <table class="table" style="margin-top:25px;">
                        <tr>
                            <td class="td-boi" style="width:25%;"><span>Tổng tiền <?php echo format_month($date); ?></span></td>
                            <td style="width:25%;"><span class="span-boi" id="tong-thuong-show"></span></td>
                            <td class="td-boi" style="width:25%;"><span>Số học sinh</span></td>
                            <td style="width:25%;"><span class="span-boi" id="tong-hs-show"></span></td>
                        </tr>
                    </table>
                    <table class="table" id="table-info" style="margin-top:25px;">
                        <tr>
                            <td class="td-boi" style="width:5%;"><span>STT</span></td>
                            <td class="td-boi" style="width:10%;"><span>Mã</span></td>
                            <td class="td-boi" style="width:20%;"><span>Họ và Tên</span></td>
                            <td class="td-boi" style="width:15%;"><span>Ngày vào học</span></td>
                            <td class="td-boi" style="width:10%;"><span>Số tiền</span></td>
                            <td class="td-boi" style="width:10%;"><span>Ngày đóng</span></td>
                            <td class="td-boi" style="width:30%;"><span>Ghi chú</span></td>
                        </tr>
                        <?php
                        $temp=explode("-",$date);
                        $old=false;
                        if($temp[0]<2016 || ($temp[0]==2016 && $temp[1]<=6)) {
                            $old=true;
                        }
                        $who="";
                        $dem=$temp=$total=0;
                        $query = array();
                        switch($loai) {
                            case 1:
                                $query[]="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh_mon.date_in,tien_hoc.money AS price,tien_hoc.date_dong2,tien_hoc.note,tien_hoc.who FROM tien_hoc INNER JOIN hocsinh ON hocsinh.ID_HS=tien_hoc.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=tien_hoc.ID_HS AND hocsinh_mon.ID_LM='$lmID' WHERE hocsinh.ID_HS NOT IN (SELECT ID_HS FROM giam_gia WHERE giam_gia.ID_MON='$monID') AND tien_hoc.money>=$muc_tien AND tien_hoc.ID_LM='$lmID' AND tien_hoc.date_dong='$date' ORDER BY hocsinh.cmt ASC";
                                break;
                            case 2:
                                $query[]="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh_mon.date_in,tien_hoc.money AS price,tien_hoc.date_dong2,tien_hoc.note,tien_hoc.who FROM tien_hoc INNER JOIN hocsinh ON hocsinh.ID_HS=tien_hoc.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=tien_hoc.ID_HS AND hocsinh_mon.ID_LM='$lmID' INNER JOIN giam_gia ON giam_gia.ID_HS=tien_hoc.ID_HS AND giam_gia.ID_MON='$monID' WHERE tien_hoc.ID_LM='$lmID' AND tien_hoc.date_dong='$date' ORDER BY hocsinh.cmt ASC";
                                break;
                            case 3:
                                $query[]="SELECT hocsinh.ID_HS,hocsinh.cmt,hocsinh.fullname,hocsinh.sdt,hocsinh.sdt_bo,hocsinh.sdt_me,hocsinh_mon.date_in,tien_hoc.money AS price,tien_hoc.date_dong2,tien_hoc.note,tien_hoc.who FROM tien_hoc INNER JOIN hocsinh ON hocsinh.ID_HS=tien_hoc.ID_HS INNER JOIN hocsinh_mon ON hocsinh_mon.ID_HS=tien_hoc.ID_HS AND hocsinh_mon.ID_LM='$lmID' WHERE hocsinh.ID_HS NOT IN (SELECT ID_HS FROM giam_gia WHERE giam_gia.ID_MON='$monID') AND tien_hoc.money<$muc_tien AND tien_hoc.ID_LM='$lmID' AND tien_hoc.date_dong='$date' ORDER BY hocsinh.cmt ASC";
                                break;
                            case 4:
                                //$query="SELECT h.cmt,h.fullname,m.date_in FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND (m.date_in<'$date-01' OR m.date_in LIKE '$date-%') AND m.ID_MON='$monID' WHERE h.ID_HS NOT IN (SELECT ID_HS FROM tien_hoc WHERE ID_MON='$monID' AND date_dong='$date') AND h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_MON='$monID' AND (date<'$date-01' OR date LIKE '$date-%')) AND h.lop='$lopID' ORDER BY h.cmt ASC";
                                $query[]="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt,h.sdt_bo,h.sdt_me,h.note,m.date_in FROM hocsinh AS h
                                INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.date_in<='$date-".get_last_day($date)."' AND m.ID_LM='$lmID' 
                                WHERE h.ID_HS NOT IN (SELECT ID_HS FROM tien_hoc WHERE ID_LM='$lmID' AND date_dong='$date') 
                                AND h.ID_HS NOT IN (SELECT ID_HS FROM nghi_temp WHERE start<='$date-01' AND end>='$date-".get_last_day($date)."' AND ID_LM='$lmID') 
                                AND h.ID_HS NOT IN (SELECT ID_HS FROM giam_gia WHERE discount='100' AND ID_MON='$monID') 
                                AND h.ID_HS NOT IN (SELECT ID_HS FROM hocsinh_nghi WHERE ID_LM='$lmID') 
                                AND h.ID_HS NOT IN (SELECT note2 FROM options WHERE (content='0' OR content='0k') AND type='edit-tien-hoc-$lmID' AND note='$date')
                                ORDER BY h.cmt ASC";
                                $query[]="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt,h.sdt_bo,h.sdt_me,h.note,m.date_in,o.content FROM hocsinh AS h
                                INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' AND m.date_in>='$date-".get_last_day($date)."'
                                INNER JOIN options AS o ON o.content!='0' AND o.content!='0k' AND o.type='edit-tien-hoc-$lmID' AND o.note='$date' AND o.note2=h.ID_HS
                                WHERE h.ID_HS NOT IN (SELECT ID_HS FROM tien_hoc WHERE ID_LM='$lmID' AND date_dong='$date') 
                                ORDER BY h.cmt ASC";
                                break;
                            case 5:
                                $query[]="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt,h.sdt_bo,h.sdt_me,m.date_in,t.money AS price,t.date_dong2,t.note,t.who FROM hocsinh_nghi AS n INNER JOIN hocsinh AS h ON h.ID_HS=n.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' LEFT JOIN tien_hoc AS t ON t.ID_HS=h.ID_HS AND t.ID_LM='$lmID' AND t.date_dong='$date' WHERE n.ID_LM='$lmID' AND n.date LIKE '$date-%'";
                                break;
                            case 6:
                                $query[]="SELECT h.ID_HS,h.cmt,h.fullname,h.sdt,h.sdt_bo,h.sdt_me,m.date_in,t.price,t.date_dong AS date_dong2,t.note FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND (m.date_in<'$date-01' OR m.date_in LIKE '$date-%') AND m.ID_LM='$lmID' INNER JOIN buoikt AS b ON b.ngay LIKE '$date-%' AND b.ID_MON='$monID' INNER JOIN tien_ra AS t ON t.ID_HS=h.ID_HS AND t.string='kiemtra_$lmID' AND t.object=b.ID_BUOI ORDER BY h.cmt ASC";
                                break;
                        }
                        //$query="SELECT m.ID_HS,h.cmt,h.fullname,h.birth,b.ngay,d.diem,d.de,t.price FROM hocsinh_mon AS m INNER JOIN hocsinh AS h ON h.ID_HS=m.ID_HS AND h.lop='$lopID' INNER JOIN buoikt AS b ON b.ngay LIKE '$date-%' INNER JOIN diemkt AS d ON d.ID_BUOI=b.ID_BUOI AND d.ID_HS=m.ID_HS INNER JOIN tien_ra AS t ON t.ID_HS=m.ID_HS AND t.string='kiemtra_$monID' AND t.object=b.ID_BUOI ORDER BY m.ID_HS ASC,b.ID_BUOI ASC";
                        $temp = explode("-", $date);
                        $nam = $temp[0];
                        $thang = $temp[1];

                        for($i = 0; $i < count($query); $i++) {
                            $result = mysqli_query($db, $query[$i]);
                            echo mysqli_error($db);
                            while ($data = mysqli_fetch_assoc($result)) {

                                if ($loai != 4 && $loai != 6) {
                                    if (isset($staff[$data["who"]]) && stripos($who, $staff[$data["who"]]) === false) {
                                        $who .= ", " . $staff[$data["who"]];
                                    }
                                }

                                if ($dem % 2 != 0) {
                                    echo "<tr style='background:#D1DBBD'>";
                                } else {
                                    echo "<tr>";
                                }
                                echo "<td><span>" . ($dem + 1) . "</span></td>
										<td><span>$data[cmt]</span></td>
										<td class='need-ex'>
										    <span>$data[fullname]</span>
									        <div class='explain'><span>SĐT: $data[sdt]<br />SĐT Bố: $data[sdt_bo]<br />SĐT Mẹ: $data[sdt_me]</span></div>
									    </td>
										<td><span>" . format_dateup($data["date_in"]) . "</span></td>";
                                if ($loai != 4) {
                                    if ($loai == 1) {
                                        if($nam >= 2017 && $thang >= 6) {
                                            echo "<td><span>" . format_price($data["price"] * 2 / 3) . " (".format_price($data["price"]).")</span></td>";
                                            $total += $data["price"] * 2 / 3;
                                        } else {
                                            if ($data["price"] < $muc_tien) {
                                                echo "<td style='background:yellow'><span>" . format_price($data["price"] * 2 / 3) . " (".format_price($data["price"]).")</span></td>";
                                                $total += $data["price"] * 2 / 3;
                                            } else {
                                                echo "<td><span>" . format_price($tien_tra) . " (".format_price($data["price"]).")</span></td>";
                                                $total += $tien_tra;
                                            }
                                        }
                                    } else {
                                        echo "<td><span>" . format_price($data["price"] * 2 / 3) . " (".format_price($data["price"]).")</span></td>";
                                        $total += $data["price"] * 2 / 3;
                                    }
                                    if (isset($data["date_dong2"])) {
                                        echo "<td><span>" . format_dateup($data["date_dong2"]) . "</span></td>";
                                    } else {
                                        echo "<td><span></span></td>";
                                    }
                                    if ($loai != 6 && isset($staff[$data["who"]])) {
                                        echo "<td><span>" . $staff[$data["who"]] . ": $data[note]</span></td>";
                                    } else {
                                        echo "<td><span>$data[note]</span></td>";
                                    }
                                } else {
                                    $string = "";
                                    if(isset($data["content"])) {
                                        $string = format_price($data["content"]*1000)." <strong>(bắt buộc)</strong>";
                                        $class = "";
                                    } else {
//                                        $me = check_chua_dong_hoc($data["ID_HS"], $data["date_in"], $lmID, $monID, $date, $old);
//                                        if (!is_numeric($me)) {
//                                            $string = $me;
//                                        } else {
//                                            $string = format_price($me);
//                                        }
                                        $class = "td-else";
                                    }
                                    if($old) {
                                        $old2=1;
                                    } else {
                                        $old2=0;
                                    }
                                    echo "<td colspan='2'><span>".nl2br($data["note"])."</span></td>
								    <td class='$class' data-hsID='$data[ID_HS]' data-in='$data[date_in]' data-old='$old'><span>$string</span></td>";
                                }
                                echo "</tr>";
                                $dem++;
                            }
                        }
                        if($loai==6) {
                            $query2="SELECT money,datetime FROM tien_thanh_toan WHERE datetime LIKE '$date-%' AND ID_MON='$monID' AND note='tro_giang' ORDER BY ID_STT ASC";
                            $result2=mysqli_query($db,$query2);
                            while($data2=mysqli_fetch_assoc($result2)) {
                                echo"<tr>
											<td colspan='4'><span>Trợ giảng</span></td>
											<td><span>".format_price($data2["money"])."</span></td>
											<td><span>".format_dateup($data2["datetime"])."</span></td>
											<td><span></span></td>
										</tr>";
                                $total+=$data2["money"];
                            }
                        }
                        if($loai!=4) {
                            echo"<tr>
									<td class='td-boi' colspan='4'><span>Tổng cộng</span></td>
									<td class='td-boi'><span>".format_price($total)."</span></td>
									<td class='td-boi'><span>Trợ giảng</span></td>
									<td class='td-boi'><span>".substr($who,2)."</span></td>
								</tr>";
                        }
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
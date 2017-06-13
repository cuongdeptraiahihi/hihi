<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
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
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.multidatespicker.js"></script>
        <script>
		$(document).ready(function() {
            $("input.select-date").datepicker({
                dateFormat: 'yy-mm-dd',
                defaultDate: new Date('<?php echo date("Y-m-d"); ?>')
            });
            $("tr.kieu-more").hide();
            <?php
                switch ($kieu2) {
                    case 1:
                        echo'$("tr#kieu-ngay").fadeIn("fast");';
                        break;
                    case 2:
                        echo'$("tr#kieu-khoang-ngay").fadeIn("fast");';
                        break;
                    case 3:
                        echo'$("tr#kieu-khoang-seri").fadeIn("fast");';
                        break;
                    default:
                        break;
                }
            ?>
            $("select#select-kieu").change(function() {
                var value = parseInt($(this).val());
                switch(value) {
                    case 1:
                        $("tr.kieu-more").hide();
                        $("tr#kieu-ngay").fadeIn("fast");
                        break;
                    case 2:
                        $("tr.kieu-more").hide();
                        $("tr#kieu-khoang-ngay").fadeIn("fast");
                        break;
                    case 3:
                        $("tr.kieu-more").hide();
                        $("tr#kieu-khoang-seri").fadeIn("fast");
                        break;
                    default:
                        $("tr.kieu-more").fadeOut("fast");
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
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>

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
                                header("location:http://localhost/www/TDUONG/admin/kiem-tra-tien/ngay/$staff/$ngay/");
                                exit();
                                break;
                            case 2:
                                header("location:http://localhost/www/TDUONG/admin/kiem-tra-tien/khoang-ngay/$staff/$ngay_start/$ngay_end/");
                                exit();
                                break;
                            case 3:
                                header("location:http://localhost/www/TDUONG/admin/kiem-tra-tien/khoang-seri/$staff/$seri_start/$seri_end/");
                                exit();
                                break;
                            default:
                                header("location:http://localhost/www/TDUONG/admin/kiem-tra-tien/");
                                exit();
                                break;
                        }
                    }
                }
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Kiểm tra tiền</h2>
                	<div>
                    	<div class="status">
                            <form action="http://localhost/www/TDUONG/admin/kiem-tra-tien/" method="post" style="width:29%;display: inline-table;">
                                <table class="table table-2" style="width:100%;">
                                    <tr style="background:#3E606F;">
                                        <th colspan="2"><span>Tùy chọn</span></th>
                                    </tr>
                                    <tr>
                                        <td style="width:50%;"><span>Trợ giảng</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" id="select-staff" name="staff">
                                                <option value="0" <?php if($staff2==0){echo"selected='selected'";} ?>>Tất cả</option>
                                                <?php
                                                    $query="SELECT o.ID_O,o.note,o.note2 FROM options AS o WHERE o.note LIKE '%(chính)' AND o.type='tro-giang-code' ORDER BY o.ID_O DESC";
                                                    $result=mysqli_query($db,$query);
                                                    while($data=mysqli_fetch_assoc($result)) {
                                                        echo"<option value='$data[ID_O]' ";if($data["ID_O"]==$staff2){echo"selected='selected'";}echo" >$data[note]</option>";
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>Kiểu kiểm tra</span></td>
                                        <td>
                                            <select class="input" style="height:auto;width:100%;" id="select-kieu" name="kieu">
                                                <option value="0" <?php if($kieu2==0){echo"selected='selected'";} ?>>Chọn kiểu</option>
                                                <option value="1" <?php if($kieu2==1){echo"selected='selected'";} ?>>Ngày</option>
                                                <option value="2" <?php if($kieu2==2){echo"selected='selected'";} ?>>Khoảng ngày</option>
                                                <option value="3" <?php if($kieu2==3){echo"selected='selected'";} ?>>Khoảng số seri</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr id="kieu-ngay" class="kieu-more">
                                        <td><span>Ngày kiểm tra</span></td>
                                        <td><input class="input select-date" name="kieu-ngay" value="<?php echo $ngay2; ?>" type="text" placeholder="Click vào chọn ngày" /></td>
                                    </tr>
                                    <tr id="kieu-khoang-ngay" class="kieu-more">
                                        <td><input class="input select-date" name="kieu-ngay-start" value="<?php echo $ngay_start2; ?>" type="text" placeholder="Ngày bắt đầu" /></td>
                                        <td><input class="input select-date" name="kieu-ngay-end" value="<?php echo $ngay_end2; ?>" type="text" placeholder="Ngày kết thúc" /></td>
                                    </tr>
                                    <tr id="kieu-khoang-seri" class="kieu-more">
                                        <td><input class="input" name="kieu-seri-start" type="text" value="<?php echo $seri_start2; ?>" placeholder="Số bắt đầu" /></td>
                                        <td><input class="input" name="kieu-seri-end" type="text" value="<?php echo $seri_end2; ?>" placeholder="Số kết thúc" /></td>
                                    </tr>
                                    <tr>
                                        <th colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Xem" /></th>
                                    </tr>
                                </table>
                            </form>
                            <table class="table table-2" id="table-result" style="width: 70%;display: inline-table;">
                                <tr style="background:#3E606F;">
                                    <th colspan="6"><span>Kết quả</span></th>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width: 5%;"><span>STT</span></th>
                                    <th style="width: 10%;"><span>Học sinh</span></th>
                                    <th><span>Nội dung</span></th>
                                    <th style="width: 13%;"><span>Ngày nhập</span></th>
                                    <th style="width: 12%;"><span>Seri</span></th>
                                    <th style="width: 15%;"><span>Số tiền</span></th>
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
                                            echo "<tr><td colspan='6'></td></tr>
                                        <tr style='background:#3E606F;'>
                                            <th><span>$dem</span></th>
                                            <th colspan='4'><span style='text-transform:uppercase;'>Tổng tiền thu được tất cả</span></th>
                                            <th style='background:#EF5350;'><span>" . format_price($total) . "</span></th>
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
                                        <tr style='background:#3E606F;'>
                                            <th><span>$dem</span></th>
                                            <th colspan='4'><span style='text-transform:uppercase;'>Tổng tiền thu được tất cả</span></th>
                                            <th style='background:#EF5350;'><span>" . format_price($total) . "</span></th>
                                        </tr>";
                                        }
                                    }
                                ?>
                            </table>
                        </div>
               	    </div>
            
                </div>
            </div>
        
        </div>
        
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
        echo "<tr style='background:#3E606F;'><th colspan='6'><span>Trợ giảng $name</span></th></tr>";
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
                <td style='text-align:left;padding-left:15px;'><span>Ngày " . format_dateup($data2["date_dong2"]) . " đóng tiền học tháng " . format_month($data2["date_dong"]) . " môn $data2[name] $note</span></td>
                <td><span>" . format_dateup($data2["date_nhap"]) . "</span></td>
                <td><span>$data2[code]</span></td>
                <td><span>" . format_price($data2["money"]) . "</span></td>
            </tr>";
            $total1 += $data2["money"];
            $dem1++;
        }
        if ($dem1 != 0) {
            echo "<tr style='background:#96c8f3;'>
                <td><span>$dem1</span></td>
                <td><input type='submit' class='submit detail-hoc' value='Xem' data-oID='$oID' /></td>
                <td colspan='3'><span>Tổng tiền học của $name thu</span></td>
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
                <td style='text-align:left;padding-left:15px;'><span>Ngày " . format_dateup($data2["date_dong"]) . ": $data2[note]</span></td>
                <td><span>" . format_dateup($data2["date"]) . "</span></td>
                <td><span>$data2[code]</span></td>
                <td><span>" . format_price($data2["price"]) . "</span></td>
            </tr>";
            $total2 += $data2["price"];
            $dem2++;
        }
        if ($dem2 != 0) {
            echo "<tr style='background:#96c8f3;'>
                <td><span>$dem2</span></td>
                <td><input type='submit' class='submit detail-nap' value='Xem' data-oID='$oID' /></td>
                <td colspan='3'><span>Tổng tiền nạp của $name thu</span></td>
                <td><span>" . format_price($total2) . "</span></td>
            </tr>";
        }
        echo "<tr style='background:#EF5350;'>
            <th><span>" . ($dem1 + $dem2) . "</span></th>
            <th colspan='4'><span style='text-transform:uppercase;'>Tổng tiền của $name thu</span></th>
            <th><span>" . format_price($total1 + $total2) . "</span></th>
        </tr>";
        $total += $total1 + $total2;
        $dem += $dem1 + $dem2;
        return array($total,$dem);
    }
	ob_end_flush();
	require_once("../model/close_db.php");
?>
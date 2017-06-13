<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["thang"])) {
        $thang=$_GET["thang"];
    } else {
        $thang=date("Y-m");
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>HỌC SINH HỦY BÀI</title>
        
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
                $ngay="";
                if(isset($_POST["xem"])) {
                    if(isset($_POST["thang"])) {
                        $month=format_date_o($_POST["thang"]);
                        header("location:http://localhost/www/TDUONG/admin/hoc-sinh-huy-bai/$month/");
                        exit();
                    }
                }
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Thống kê hủy bài <span style="font-weight: 600;">tháng <?php echo format_month($thang); ?></span></h2>
                	<div>
                    	<div class="status">
                            <form action="http://localhost/www/TDUONG/admin/hoc-sinh-huy-bai/" method="post" style="width:29%;display: inline-table;">
                                <table class="table table-2" style="width:100%;">
                                    <tr style="background:#3E606F;">
                                        <th colspan="2"><span>Tùy chọn</span></th>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%;"><span>Chọn tháng</span></td>
                                        <td><input class="input" name="thang" type="text" value="<?php echo format_month($thang); ?>" placeholder="3/2016, 5/2016,..." /></td>
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
                                    <th style="width: 10%;"><span>STT</span></th>
                                    <th style="width: 15%;"><span>Mã số</span></th>
                                    <th style="width: 20%;"><span>Họ và tên</span></th>
                                    <th><span>Buổi</span></th>
                                    <th style="width: 15%;"><span>Môn</span></th>
                                </tr>
                                <?php
                                    $total=0;
                                    $query = "SELECT ID_O,note FROM options WHERE type='tro-giang-code' ORDER BY ID_O ASC";
                                    $result = mysqli_query($db, $query);
                                    while ($data = mysqli_fetch_assoc($result)) {
                                        $dem=0;
                                        $query2="SELECT i.ID_HS,h.cmt,h.fullname,b.ngay,m.name FROM info_buoikt AS i
                                        INNER JOIN hocsinh AS h ON h.ID_HS=i.ID_HS
                                        INNER JOIN buoikt AS b ON b.ID_BUOI=i.ID_BUOI AND b.ID_MON=i.ID_MON AND b.ngay LIKE '$thang-%'
                                        INNER JOIN mon AS m ON m.ID_MON=i.ID_MON
                                        WHERE i.note LIKE '%,$data[ID_O],%' ORDER BY b.ngay DESC,h.cmt ASC";
                                        $result2 = mysqli_query($db, $query2);
                                        if(mysqli_num_rows($result2)!=0) {
                                            echo"<tr style='background:#EF5350;'>
                                                <th colspan='5'><span>$data[note]</span></th>
                                            </tr>";
                                            while ($data2 = mysqli_fetch_assoc($result2)) {
                                                echo "<tr>
                                                    <td><span>" . ($dem + 1) . "</span></td>
                                                    <td><span>$data2[cmt]</span></td>
                                                    <td><span>$data2[fullname]</span></td>
                                                    <td><span>" . format_dateup($data2["ngay"]) . "</span></td>
                                                    <td><span>$data2[name]</span></td>
                                                </tr>";
                                                $dem++;
                                            }
                                        }
                                        $total+=$dem;
                                    }
                                ?>
                                <tr style="background:#3E606F;">
                                    <th colspan="6"><span>Tổng bài hủy trong tháng: <?php echo $total; ?> bài</span></th>
                                </tr>
                                <tr></tr>
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
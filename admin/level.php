<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	$lmID=$_SESSION["lmID"];
	$lop_mon_name=get_lop_mon_name($lmID);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>LEVEL</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:400px;}#chartContainer {width:100%;height:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
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
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Level <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <table class="table">
                                <tr>
                                    <td colspan="5"></td>
                                    <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/level-thuong/'" value="Phần thưởng" /></td>
                                    <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/level/'" value="Xếp hạng" /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;"><span>STT</span></th>
                                    <th style="width:25%;"><span>Họ tên</span></th>
                                    <th style="width:10%;"><span>Mã số</span></th>
                                    <th style="width:10%;"><span>Đề</span></th>
                                    <th style="width:10%;"><span>Số trận thắng</span></th>
                                    <th style="width:10%;"><span>Level</span></th>
                                    <th style="width:10%;"><span>Trang cá nhân</span></th>
                                </tr>
                                <?php
                                $dem=0;
                                $result=get_all_hs_level("X",$lmID);
                                while($data=mysqli_fetch_assoc($result)) {
                                    if($dem%2!=0) {
                                        echo"<tr style='background:#D1DBBD;'>";
                                    } else {
                                        echo"<tr>";
                                    }
                                    ?>
                                    <td><span><?php echo ($dem+1);?></span></td>
                                    <td><span><?php echo $data["fullname"]; ?></span></td>
                                    <td><span><?php echo $data["cmt"]; ?></span></td>
                                    <td><span><?php echo $data["de"]; ?></span></td>
                                    <td><span><?php echo count_thachdau_win($data["ID_HS"],$lmID); ?></span></td>
                                    <td><span><?php echo $data["level"]; ?></span></td>
                                    <td><a href="https://localhost/www/TDUONG/dang-nhap/<?php echo $data["ID_HS"]."/".get_sdt_phuhuynh($data["ID_HS"]); ?>/">Xem</a></td>
                                </tr>
                                <?php
                                        $dem++;
                                    }
                                    if($dem==0) {
                                        echo"<tr><td colspan='7'><span>Không có dữ liệu</span></td></tr>";
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
	ob_end_flush();
	require_once("../model/close_db.php");
?>
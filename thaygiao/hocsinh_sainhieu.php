<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    if(isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
        $lmID=$_GET["lm"];
    } else {
        $lmID=0;
    }
    $lmID=$_SESSION["lmID"];
    $monID=get_mon_of_lop($lmID);
	$lop_mon_name=get_lop_mon_name($lmID);
    if(isset($_GET["buoiID"]) && is_numeric($_GET["buoiID"])) {
        $buoiID=$_GET["buoiID"];
    } else {
        $buoiID=get_new_buoikt($monID,0,1);
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THỐNG KÊ CÂU SAI NHIỀU</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">

                <?php
                    if(isset($_POST["xem"])) {
                        if(isset($_POST["buoi"]) && is_numeric($_POST["buoi"]) && $_POST{"buoi"}!=0) {
                            $buoi=$_POST["buoi"];
                            header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-sai-nhieu/$lmID/$buoi/");
                            exit();
                        } else {
                            header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-sai-nhieu/$lmID/");
                            exit();
                        }
                    }
                ?>

                <div id="main-mid">
                	<h2>Thống kê câu sai nhiều <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <form action="http://localhost/www/TDUONG/thaygiao/hoc-sinh-sai-nhieu/<?php echo $lmID; ?>/" method="post">
                                <table class="table">
                                    <tr>
                                        <td style="width:50%;"><span>Buổi kiểm tra</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" name="buoi">
                                            <?php
                                                $result=get_all_buoikt($monID,10);
                                                while ($data=mysqli_fetch_assoc($result)) {
                                                    echo"<option value='$data[ID_BUOI]'"; if($data["ID_BUOI"]==$buoiID){echo"selected='selected'";}echo">".format_dateup($data["ngay"])."</option>";
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Xem thống kê" /></td>
                                    </tr>
                                </table>
                            </form>
                            <table class="table" style="margin-top: 25px;">
                                <tr style="background: #3E606F;">
                                    <th style="width: 10%;"><span>STT</span></th>
                                    <th style="width: 10%;"><span>Câu</span></th>
                                    <th style="width: 10%;"><span>Mã</span></th>
                                    <th><span>Tên chuyên đề</span></th>
                                    <th style="width: 15%;"><span>Số lần sai</span></th>
                                </tr>
                                <?php
                                    $monID=get_mon_of_lop($lmID);
                                    $me="";
                                    $de_arr=array("B","G","Y");
                                    for($j=0;$j<count($de_arr);$j++) {
                                        echo"<tr style='background: #3E606F;'><th colspan='5'><span>Đề $de_arr[$j]</span></th></tr>";
                                        $ketqua = array();
                                        $dem = 0;
                                        $query2 = "SELECT DISTINCT c.cau FROM chuyende_diem AS c INNER JOIN diemkt AS m ON m.ID_BUOI='$buoiID' AND m.ID_HS=c.ID_HS AND m.de='$de_arr[$j]' INNER JOIN chuyende AS d ON d.ID_CD=c.ID_CD AND d.ID_LM='$lmID' WHERE c.ID_BUOI='$buoiID' AND c.ID_LM='$lmID' AND c.diem LIKE '0/%' $me ORDER BY c.cau ASC,c.y ASC";
                                        $result2 = mysqli_query($db, $query2);
                                        while ($data2 = mysqli_fetch_assoc($result2)) {
                                            $query3 = "SELECT COUNT(c.ID_STT) AS dem FROM chuyende_diem AS c INNER JOIN diemkt AS m ON m.ID_BUOI='$buoiID' AND m.ID_HS=c.ID_HS AND m.de='$de_arr[$j]' INNER JOIN chuyende AS d ON d.ID_CD=c.ID_CD AND d.ID_LM='$lmID' WHERE c.ID_BUOI='$buoiID' AND c.ID_LM='$lmID' AND c.diem LIKE '0/%' $me AND c.cau='$data2[cau]'";
                                            $result3 = mysqli_query($db, $query3);
                                            $data3 = mysqli_fetch_assoc($result3);
                                            $query4 = "SELECT d.maso,d.title FROM chuyende_diem AS c INNER JOIN diemkt AS m ON m.ID_BUOI='$buoiID' AND m.ID_HS=c.ID_HS AND m.de='$de_arr[$j]' INNER JOIN chuyende AS d ON d.ID_CD=c.ID_CD AND d.ID_LM='$lmID' WHERE c.ID_BUOI='$buoiID' AND c.ID_LM='$lmID' AND c.diem LIKE '0/%' $me AND c.cau='$data2[cau]' LIMIT 1";
                                            $result4 = mysqli_query($db, $query4);
                                            $data4 = mysqli_fetch_assoc($result4);
                                            $ketqua[] = array(
                                                "cau" => $data2["cau"],
                                                "maso" => $data4["maso"],
                                                "title" => $data4["title"],
                                                "diemtb" => $data3["dem"]
                                            );
                                            $dem++;
                                        }
                                        usort($ketqua, "diemtb_sort_desc");
                                        if (count($ketqua) >= 10) {
                                            for ($i = 0; $i < 10; $i++) {
                                                echo "<tr>
                                            <td><span>" . ($i + 1) . "</span></td>
                                            <td><span>" . $ketqua[$i]["cau"] . "</span></td>
                                            <td><span>" . $ketqua[$i]["maso"] . "</span></td>
                                            <td><span>" . $ketqua[$i]["title"] . "</span></td>
                                            <td><span>" . $ketqua[$i]["diemtb"] . "</span></td>
                                        </tr>";
                                            }
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
	ob_end_flush();
	require_once("../model/close_db.php");
?>
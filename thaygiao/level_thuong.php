<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"])) {
		$hsID=$_GET["hsID"];
	} else {
		$hsID=0;
	}
	$lmID=$_SESSION["lmID"];
	$lop_mon_name=get_lop_mon_name($lmID);
    $result0=get_hs_short_detail($hsID,$lmID);
    $data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THẺ MIỄN PHẠT</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}.main-bot {width:100%;}.main-top {width:100%;position:relative;height:400px;}#chartContainer {width:100%;height:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		    $("#MAIN #main-mid .status .table tr td input.xong").click(function() {
		        sttID = $(this).attr("data-sttID");
                if($.isNumeric(sttID) && sttID!=0 && confirm("Bạn có chắc chắn không?")) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "sttID=" + sttID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-thachdau/",
                        success: function(result) {
                            location.reload();
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                } else {
                    $("#BODY").css("opacity","1");
                    $("#popup-loading").fadeOut("fast");
                }
            });

            $("#MAIN #main-mid .status .table tr td input.xoa").click(function() {
                sttID = $(this).attr("data-sttID");
                del_tr = $(this).closest("tr");
                if($.isNumeric(sttID) && sttID!=0 && confirm("Bạn có chắc chắn không?")) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "sttID=" + sttID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-taikhoan/",
                        success: function(result) {
                            del_tr.fadeOut("fast");
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                } else {
                    $("#BODY").css("opacity","1");
                    $("#popup-loading").fadeOut("fast");
                }
            });

            $("#popup-ok").click(function() {
                maso = $("#maso-add").val();
                content = $("#content-add").val();
                if(maso!="" && content!="" && confirm("Bạn có chắc chắn không?")) {
                    $("#popup-loading").fadeIn("fast");
                    $("#BODY").css("opacity","0.3");
                    $.ajax({
                        async: true,
                        data: "maso=" + maso + "&content=" + content,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-taikhoan/",
                        success: function(result) {
                            location.reload();
                            $("#BODY").css("opacity","1");
                            $("#popup-loading").fadeOut("fast");
                        }
                    });
                }
            });

            $("#search-hs").click(function() {
                 $(this).val("");
            });

            $("#add-thuong").click(function() {
                $("#popup-add").fadeIn("fast");
                $("#BODY").css("opacity","0.3");
            });
		});
		</script>
       
	</head>

    <body>

        <div class="popup" id="popup-add" style="width:40 %;left:30%;">
            <div class="popup-close"><i class="fa fa-close"></i></div>
            <p style="text-transform:uppercase;">Thêm thưởng</p>
            <div style="width:90%;margin:15px auto 15px auto;">
                <input id="maso-add" class="input" autocomplete="off" placeholder="Mã học sinh" value="<?php echo $data0["cmt"]; ?>" />
                <input id="content-add" class="input" autocomplete="off" placeholder="Nội dung" style="margin-top:10px;" />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
        </div>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>

            <?php
                $maso=NULL;
                if(isset($_POST["search-ok"])) {
                    if(isset($_POST["search-hs"])) {
                        $maso=$_POST["search-hs"];
                    }
                    if(valid_maso($maso)) {
                        $id=get_hs_id($maso);
                        if(is_numeric($id) && $id!=0) {
                            header("location:http://localhost/www/TDUONG/thaygiao/level-thuong/$id/");
                            exit();
                        } else {
                            header("location:http://localhost/www/TDUONG/thaygiao/level-thuong/");
                            exit();
                        }
                    } else {
                        header("location:http://localhost/www/TDUONG/thaygiao/level-thuong/");
                        exit();
                    }
                }
            ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Thẻ miễn phạt <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            <table class="table">
                                <tr>
                                    <form action="http://localhost/www/TDUONG/thaygiao/level-thuong/" method="post">
                                        <td colspan="3"><span>Tìm kiếm</span></td>
                                        <td><input type="text" class="input" placeholder="Mã học sinh" id="search-hs" name="search-hs" value="<?php echo $data0["cmt"]; ?>" /></td>
                                        <td><input type="submit" class="submit" value="Tìm" name="search-ok" /></td>

                                    </form>
                                    <td><input type="submit" class="submit" value="Thêm" id="add-thuong" /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;"><span>STT</span></th>
                                    <th style="width:20%;"><span>Họ tên</span></th>
                                    <th style="width:10%;"><span>Mã số</span></th>
                                    <th><span>Nội dung</span></th>
                                    <th style="width: 15%;"><span>Thời gian</span></th>
                                    <th style="width:15%;"><span></span></th>
                                </tr>
                                <?php
                                $dem=0;
                                if($hsID!=0) {
                                    $query = "SELECT h.ID_HS,h.cmt,h.fullname,l.* FROM log AS l INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=l.ID_HS AND m.ID_LM='$lmID' WHERE l.ID_HS='$hsID' AND l.content NOT LIKE '%(Đã nhận)' AND (l.type='len-level-$lmID' OR l.type='the-mien-phat') ORDER BY l.datetime DESC,h.cmt ASC";
                                } else {
                                    $query = "SELECT h.ID_HS,h.cmt,h.fullname,l.* FROM log AS l INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=l.ID_HS AND m.ID_LM='$lmID' WHERE l.content NOT LIKE '%(Đã nhận)' AND (l.type='len-level-$lmID' OR l.type='the-mien-phat') ORDER BY l.datetime DESC,h.cmt ASC";
                                }
                                $result=mysqli_query($db,$query);
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
                                    <td><span><?php echo $data["content"]; ?></span></td>
                                    <td><span><?php echo format_datetime($data["datetime"]); ?></span></td>
                                    <td>
                                    <?php
                                        if(stripos($data["content"],"(Đã nhận)")===false) {
                                            echo"<input type='submit' class='submit xong' data-sttID='$data[ID_STT]' value='Xong' />";
                                        }
                                        echo"<input type='submit' class='submit xoa' data-sttID='$data[ID_STT]' value='Xóa' />";
                                    ?>
                                    </td>
                                </tr>
                                <?php
                                        $dem++;
                                    }
                                if($hsID!=0) {
                                    $query = "SELECT h.ID_HS,h.cmt,h.fullname,l.* FROM log AS l INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=l.ID_HS AND m.ID_LM='$lmID' WHERE l.ID_HS='$hsID' AND l.content LIKE '%(Đã nhận)' AND l.type='len-level-$lmID' ORDER BY l.datetime DESC,h.cmt ASC";
                                } else {
                                    $query = "SELECT h.ID_HS,h.cmt,h.fullname,l.* FROM log AS l INNER JOIN hocsinh AS h ON h.ID_HS=l.ID_HS INNER JOIN hocsinh_mon AS m ON m.ID_HS=l.ID_HS AND m.ID_LM='$lmID' WHERE l.content LIKE '%(Đã nhận)' AND l.type='len-level-$lmID' ORDER BY l.datetime DESC,h.cmt ASC";
                                }
                                $result=mysqli_query($db,$query);
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
                                    <td><span><?php echo $data["content"]; ?></span></td>
                                    <td><span><?php echo format_datetime($data["datetime"]); ?></span></td>
                                    <td></td>
                                    </tr>
                                <?php
                                    $dem++;
                                }
                                    if($dem==0) {
                                        echo"<tr><td colspan='6'><span>Không có dữ liệu</span></td></tr>";
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
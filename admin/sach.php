<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID = $_SESSION["lmID"];
    $monID = $_SESSION["mon"];
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
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}.fa {font-size:1.375em !important;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
		    $(".donhang-done").click(function () {
		        var del_tr = $(this).closest("tr");
                var id = $(this).attr("data-dh");
                if(confirm("Bạn có chắc chắn không?")) {
                    if ($.isNumeric(id) && id > 0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: true,
                            data: "sach_done=" + id,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-tailieu/",
                            success: function (result) {
                                del_tr.fadeOut("fast");
                                $("#popup-loading").fadeOut("fast");
                                $("#BODY").css("opacity", "1");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                }
            });
            $(".donhang-xoa").click(function () {
                var del_tr = $(this).closest("tr");
                var id = $(this).attr("data-dh");
                if(confirm("Bạn có chắc chắn không?")) {
                    if ($.isNumeric(id) && id > 0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: true,
                            data: "sach_xoa=" + id,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-tailieu/",
                            success: function (result) {
                                del_tr.fadeOut("fast");
                                $("#popup-loading").fadeOut("fast");
                                $("#BODY").css("opacity", "1");
                            }
                        });
                    } else {
                        alert("Dữ liệu không chính xác!");
                    }
                }
            });
            $(".donhang-back").click(function () {
                var del_tr = $(this).closest("tr");
                var id = $(this).attr("data-dh");
                if(confirm("Bạn có chắc chắn không?")) {
                    if ($.isNumeric(id) && id > 0) {
                        $("#popup-loading").fadeIn("fast");
                        $("#BODY").css("opacity", "0.3");
                        $.ajax({
                            async: true,
                            data: "sach_back=" + id,
                            type: "post",
                            url: "http://localhost/www/TDUONG/admin/xuly-tailieu/",
                            success: function (result) {
                                del_tr.fadeOut("fast");
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
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Đơn hàng</h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:5%;"><span>STT</span></th>
                                        <th style="width:15%;"><span>Thời gian</span></th>
                                        <th style="width:10%;"><span>Mã số</span></th>
                                        <th><span>Sách</span></th>
                                        <th><span>Giá tiền</span></th>
                                        <th style="width:5%;"><span>SL</span></th>
                                        <th><span>Thành tiền</span></th>
                                        <th style="width:15%;"><span></span></th>
                                    </tr>
                                    <?php
                                        $dem=1;
										$query="SELECT s.*,h.cmt,h.fullname,a.name FROM sach_mua AS s
										INNER JOIN hocsinh AS h ON h.ID_HS=s.ID_HS
										INNER JOIN sach AS a ON a.ID_S=s.ID_S 
										WHERE s.status='0'
										ORDER BY s.ID_STT DESC";
										$result=mysqli_query($db,$query);
										while($data=mysqli_fetch_assoc($result)) {
										    echo"<tr>
                                                <td><span>$dem</span></td>
                                                <td><span>".format_dateup($data["datetime"])."</span></td>
                                                <td><span>$data[cmt]</span></td>
                                                <td><span>$data[name]</span></td>
                                                <td><span>".format_price($data["tien"])."</span></td>
                                                <td><span>$data[sl]</span></td>
                                                <td><span>".format_price($data["total"])."</span></td>
                                                <td>
                                                    <input type='submit' class='submit donhang-done' data-dh='$data[ID_STT]' value='Xong' />
                                                    <input type='submit' class='submit donhang-xoa' data-dh='$data[ID_STT]' value='Xóa' />
                                                </td>
                                            </tr>";
										    $dem++;
                                        }
									?>
                                </table>
                        </div>
                    </div>
               	</div>
                <div id="main-mid">
                    <h2>Lịch sử</h2>
                    <div>
                        <div class="status">
                            <table class="table">
                                <tr style="background:#3E606F;">
                                    <th style="width:5%;"><span>STT</span></th>
                                    <th style="width:20%;"><span>Họ tên</span></th>
                                    <th style="width:10%;"><span>Mã số</span></th>
                                    <th><span>Sách</span></th>
                                    <th><span>Giá tiền</span></th>
                                    <th style="width:5%;"><span>SL</span></th>
                                    <th><span>Thành tiền</span></th>
                                    <th style="width:15%;"><span></span></th>
                                </tr>
                                <?php
                                $dem=1;
                                $query="SELECT s.*,h.cmt,h.fullname,a.name FROM sach_mua AS s
                                INNER JOIN hocsinh AS h ON h.ID_HS=s.ID_HS
                                INNER JOIN sach AS a ON a.ID_S=s.ID_S 
                                WHERE s.status='1'
                                ORDER BY s.ID_STT DESC";
                                $result=mysqli_query($db,$query);
                                while($data=mysqli_fetch_assoc($result)) {
                                    echo"<tr>
                                        <td><span>$dem</span></td>
                                        <td><span>$data[fullname]</span></td>
                                        <td><span>$data[cmt]</span></td>
                                        <td><span>$data[name]</span></td>
                                        <td><span>".format_price($data["tien"])."</span></td>
                                        <td><span>$data[sl]</span></td>
                                        <td><span>".format_price($data["total"])."</span></td>
                                        <td>
                                            <input type='submit' class='submit donhang-back' data-dh='$data[ID_STT]' value='Hoàn tác' />
                                            <input type='submit' class='submit donhang-xoa' data-dh='$data[ID_STT]' value='Xóa' />
                                        </td>
                                    </tr>";
                                    $dem++;
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
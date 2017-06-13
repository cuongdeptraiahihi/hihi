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
    if(isset($_GET["mon"]) && is_numeric($_GET["mon"])) {
        $monID=$_GET["mon"];
    } else {
        $monID=0;
    }
    if($monID!=0 && $lmID!=0) {
        $lmID = $_SESSION["lmID"];
        $monID = $_SESSION["mon"];
    }
    $lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>DANH MỤC MÔN <?php echo mb_strtoupper($lop_mon_name); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {
                $("#MAIN #main-mid .status .table").delegate("tr td input.edit", "click", function() {
                    return dmID = $(this).attr("data-dmID"), del_tr = $(this).closest("tr"), del_tr.css("opacity", "0.3"), title = del_tr.find("td input.title").val(), "" != title && $.ajax({
                        async: true,
                        data: "dmID=" + dmID + "&title=" + title,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-danhmuc/",
                        success: function(t) {
                            del_tr.css("opacity", "1")
                        }
                    }), !1
                }), $("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
                    return dmID = $(this).attr("data-dmID"), del_tr = $(this).closest("tr"), confirm("Bạn có chắc chắn xóa danh mục này?") && $.ajax({
                        async: true,
                        data: "dmID0=" + dmID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-danhmuc/",
                        success: function(t) {
                            del_tr.fadeOut("fast")
                        }
                    }), !1
                }), $(".addnew").click(function() {
                    $("#input-add").val(""), $("#popup-add").fadeIn("fast"), $("#BODY").css("opacity", "0.3")
                }), $(".popup-close").click(function() {
                    $(".popup").fadeOut("fast"), $("#BODY").css("opacity", "1")
                });
                $("#popup-ok").click(function() {
                    title = $("#title-add").val(), lmID = <?php echo $lmID; ?>, monID = <?php echo $monID; ?>, "" != title && $.ajax({
                        async: true,
                        data: "title0=" + title + "&lmID=" + lmID + "&monID=" + monID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-danhmuc/",
                        success: function(t) {
                            $("#popup-add").fadeOut("fast"), $("#BODY").css("opacity", "1"), location.reload()
                        }
                    })
                });
            });
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm danh mục</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            	<input id="title-add" class="input" autocomplete="off" placeholder="Tên danh mục" />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                    <?php
                    if($monID!=0 && $lmID!=0) {
                        echo"<h2>DANH SÁCH CÁC DANH MỤC NGOÀI CHUYÊN ĐỀ <span style='font-weight:600;'>Môn $lop_mon_name</span></h2>";
                    } else {
                        echo"<h2>DANH SÁCH CÁC DANH MỤC CÔNG CỘNG</h2>";
                    }
                    ?>
                	<div>
                    	<div class="status">	
                            <table class="table">
                                <tr>
                                	<td colspan="3"></td>
                                    <td><input type='submit' class='submit addnew' value='Thêm danh mục' /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;"><span>STT</span></th>
                                    <th style="width:35%;"><span>Tiêu đề</span></th>
                                    <th style="width:15%;"><span>Số tài liệu</span></th>
                                    <th style="width:20%;"><span></span></th>
                                </tr>
                                <?php
									$dem=0;
									$reuslt=get_all_danhmuc($monID);
									while($data=mysqli_fetch_assoc($reuslt)) {
										$num=count_tailieu_sl2($data["ID_DM"]);
										echo"<tr>
											<td><span>".($dem+1)."</span></td>
											<td><input type='text' class='input title' value='$data[title]' /></span></td>
											<td><span>$num</span</td>
											<td>
												<input type='submit' class='submit edit' data-dmID='$data[ID_DM]' value='Sửa' />";
												if($num==0) {
													echo"<input type='submit' class='submit delete' data-dmID='$data[ID_DM]' value='Xóa' />";
												}
											echo"</td>
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
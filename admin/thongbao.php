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
        
        <title>THÔNG BÁO</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input {text-align:center;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {

            $("#MAIN #main-mid .status .table").delegate("tr td input.off", "click", function() {
                tbID = $(this).attr("data-tbID");
                how = "old";
                change_thongbao(tbID,how);
            });

            $("#MAIN #main-mid .status .table").delegate("tr td input.again", "click", function() {
                tbID = $(this).attr("data-tbID");
                how = "new";
                change_thongbao(tbID,how);
            });

            function change_thongbao(tbID,how) {
                if($.isNumeric(tbID) && tbID!=0 && (how=="new" || how=="old")) {
                    $.ajax({
                        async: true,
                        data: "tbID2=" + tbID + "&how=" + how,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                        success: function(result) {
                            location.reload();
                        }
                    });
                }
            }

            $("#MAIN #main-mid .status .table").delegate("tr td input.edit", "click", function() {
                tbID = $(this).attr("data-tbID");
                del_tr = $(this).closest("tr");
                del_tr.css("opacity",0.3);
                content = del_tr.find("td textarea.edit_content").val();
                if($.isNumeric(tbID) && tbID!=0 && content!="") {
                    $.ajax({
                        async: true,
                        data: "tbID1=" + tbID + "&content=" + Base64.encode(content),
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-mon/",
                        success: function(result) {
                            del_tr.css("opacity",1);
                        }
                    });
                }
            });

			$("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
				tbID = $(this).attr("data-tbID");
				del_tr = $(this).closest("tr");
				if(confirm("Bạn có chắc chắn muốn xóa thông báo này không?") && $.isNumeric(tbID) && tbID!=0) {
					$.ajax({
						async: true,
						data: "tbID=" + tbID,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-mon/",
						success: function(result) {
							del_tr.fadeOut("fast");
						}
					});
				}
			});

			$("#add_tb").click(function() {
				$("#content-add").val("");
				$("#popup-add").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
			});

			$(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast");
				$("#BODY").css("opacity","1");
			});

			$("#popup-ok").click(function() {
				content = $("#content-add").val();
				if(content!="") {
					$.ajax({
						async: true,
						data: "content=" + content + "&lmID3=" + <?php echo $lmID; ?>,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-mon/",
						success: function(result) {
							$("#popup-add").fadeOut("fast");
							if(result=="ok") {
								$("#BODY").css("opacity","1");
								location.reload();
							} else {
								alert(result);
								$("#BODY").css("opacity","1");
							}
						}
					});
				}
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:70%;left:15%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm thông báo</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            <textarea id="content-add" class="input" placeholder="Nội dung" style="text-align: left;" rows="5"></textarea>
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Thông báo tới học sinh <span style="font-weight: 600;"><?php echo "môn $lop_mon_name"; ?></span></h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                    	<td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/xuat-sms/'" value="SMS" /></td>
                                        <td></td>
                                        <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/thong-bao-nang-cao/'" value="PUSH EXCEL" /></td>
                                        <td><input type="submit" class="submit" id="add_tb" value="Thêm thông báo" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>Thời gian</span></th>
                                        <th><span>Nội dung</span></th>
                                        <th style="width: 15%;"><span>Trạng thái</span></th>
                                        <th style="width:15%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
                                        $query="SELECT * FROM thongbao WHERE ID_HS='0' AND ID_LM='$lmID' AND danhmuc='all' ORDER BY datetime DESC";
										$result=mysqli_query($db,$query);
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD;'>";
											} else {
												echo"<tr>";
											}
											echo"<td><span>".format_datetime($data["datetime"])."</span></td>
												<td><textarea class='input edit_content' style='text-align:left;' rows='3'>$data[content]</textarea></td>";
												if($data["status"]=="new") {
												    echo"<td><span>Đang phát</span></td>";
                                                } else {
                                                    echo"<td><span>Hoàn thành</span></td>";
                                                }
												echo"<td>
													<input type='submit' class='submit edit' data-tbID='$data[ID_TB]' value='Sửa' />";
                                                    if($data["status"]=="new") {
                                                        echo"<input type='submit' class='submit off' data-tbID='$data[ID_TB]' value='Tắt' />";
                                                    } else {
                                                        echo"<input type='submit' class='submit again' data-tbID='$data[ID_TB]' value='Báo lại' />";
                                                    }
                                                    echo"<input type='submit' class='submit delete' data-tbID='$data[ID_TB]' value='Xóa' />
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
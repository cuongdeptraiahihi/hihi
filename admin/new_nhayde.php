<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["de"])) {
        $new_de=$_GET["de"];
	} else {
		$new_de="X";
	}
    if(isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
        $lmID=$_GET["lm"];
    } else {
        $lmID=0;
    }
	$lmID=$_SESSION["lmID"];
	$lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>DANH SÁCH NHẢY ĐỀ</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
            #FIX, #TOP {display: none;}
            #MAIN {margin-top: 40px;}
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			$("#MAIN #main-mid .table tr td input.back").click(function() {
				if(confirm("Bạn có chắc chắn chuyển học sinh này về đề ban đầu?")) {
					de = $(this).attr("data-de");
					hsID = $(this).attr("data-hsID");
					del_tr = $(this).closest("tr");
					$.ajax({
						async: true,
						data: "hsID=" + hsID + "&de=" + de + "&lmID=" + <?php echo $lmID; ?>,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-nhayde/",
						success: function(result) {
							del_tr.fadeOut("fast");
						}
					});
				}
			});
			
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                <div id="main-mid">
                	<h2>Danh sách chuyển đề từ <span style="font-weight:600;">
                    	<?php
							if($new_de=="B") {
                                echo"Y,G sang B";
                            } else if($new_de=="G") {
                                echo"B sang G";
							} else {
								echo"B sang Y";
							}
							echo", môn $lop_mon_name";
						?>
                    </span></h2>
                	<div>
                    	<div class="status">
                            	<table class="table">
                                	<tr>
                                    	<td></td>
                                        <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/moi-chuyen-de/<?php echo $lmID; ?>/B/'" value="Y,G sang B" /></td>
                                        <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/moi-chuyen-de/<?php echo $lmID; ?>/G/'" value="B sang G" /></td>
                                        <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/moi-chuyen-de/<?php echo $lmID; ?>/Y/'" value="B sang Y" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th><span>Mã số</span></th>
                                        <th style="width:20%;"><span>Điểm TB</span></th>
                                        <th style="width:15%;"><span>Đề mới</span></th>
                                        <th style="width:35%;"><span>Học sinh</span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_new_nhayde($lmID, $new_de);
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?>
                                        <td><span><?php echo $data["cmt"]; ?></span></td>
                                        <td><span><?php echo $data["diemtb"]; ?></span></td>
                                        <td><span><?php echo $data["new_de"];?></span></td>
                                        <td><span><?php echo $data["fullname"]; ?></span></td>
                                    </tr>
									<?php 
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
<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["tlID"]) && is_numeric($_GET["tlID"])) {
		$tlID=$_GET["tlID"];
	} else {
		$tlID=0;
	}
	$result0=get_one_tailieu($tlID);
	$data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title><?php echo mb_strtoupper($data0["title"]); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN #main-mid .status table tr td input.delete").click(function() {
				if(confirm("Bạn có chắc chắn muốn xóa bình luận này?")) {
					cID = $(this).attr("data-cID");
					del_tr = $(this).closest("tr");
					$.ajax({
						async: true,
						data: "cID=" + cID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tailieu/",
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
                	<h2><?php echo $data0["title"]; ?></h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                                <tr style="background:#3E606F;">
                                    <th style="width:5%;"><span>STT</span></th>
                                    <th style="width:25%;"><span>Học sinh</span></th>
                                    <th style="width:10%;"><span>CMT</span></th>
                                    <th style="width:30%;"><span>Nội dung</span></th>
                                    <th style="width:20%";><span>Thời gian</span></th>
                                    <th style="width:10%;"><span></span></th>
                                </tr>
                                <?php
									if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
										$position=$_GET["begin"];
									} else {
										$position=0;
									}
									$dem=0;$display=30;
									$result2=get_tailieu_cmt($tlID,$position,$display);
									while($data2=mysqli_fetch_assoc($result2)) {
										$result1=get_hs_short_detail2($data2["ID_HS"]);
										$data1=mysqli_fetch_assoc($result1);
										echo"<tr>
											<td><span>".($dem+1)."</span></td>
											<td><span>$data1[fullname]</span></td>
											<td><span>$data1[cmt]</span></td>
											<td><span>$data2[content]</span></td>
											<td><span>".format_datetime($data2["datetime"])."</span></td>
											<td>
												<input type='submit' class='submit delete' data-cID='".base64_encode($data2["ID_C"])."' value='Xóa' />
                                            </td>
										</tr>";
										$dem++;
									}
									if($dem==0) {
										echo"<tr><td colspan='6'><span>Không có bình luận nào!</span></td></tr>";
									}
								?>
                            </table>
                        </div>
                        <?php
							$result3=get_all_tailieu_cmt($tlID);
							$sum=mysqli_num_rows($result3);
							$sum_page=ceil($sum/$display);
							if($sum_page>1) {
								$current=($position/$display)+1;
						?>
                        <div class="page-number">
                        	<ul>
                            <?php
								if($current!=1) {
									$prev=$position-$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/page-$prev/'><</a></li>";
								}
								for($page=1;$page<=$sum_page;$page++) {
									$begin=($page-1)*$display;
									if($page==$current) {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/page-$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
									} else {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/page-$begin/'>$page</a></li>";
									}
								}
								if($current!=$sum_page) {
									$next=$position+$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/page-$next/'>></a></li>";
								}
							?>
                            </ul>
                        </div>
                        <?php
							}
						?>
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
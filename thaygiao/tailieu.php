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
	if($monID!=0 && $lmID!=3) {
        $monID = $_SESSION["mon"];
        $lmID = $_SESSION["lmID"];
    }
	$mon_name=get_mon_name($monID);
	$lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TÀI LIỆU</title>
        
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
			$("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
				tlID = $(this).attr("data-tlID");
				del_tr = $(this).closest("tr");
				if(confirm("Bạn có chắc chắn xóa tài liệu này?")) {
					$.ajax({
						async: true,
						data: "tlID0=" + tlID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-tailieu/",
						success: function(result) {
							del_tr.fadeOut("fast");
						}
					});
				}
				return false;
			});
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                <?php
					if($monID!=0 && $lmID!=0) {
				?>
                	<h2>Tài liệu mới nhất môn <span style="font-weight:600;"><?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                            	<tr>
                                    <td colspan="2"></td>
                                	<td><input type='submit' class='submit' onclick="location.href='http://localhost/www/TDUONG/thaygiao/danh-muc/<?php echo $lmID."/".$monID; ?>/'" value='Danh mục ngoài chuyên đề' /></td>
                                    <td colspan="2"><input type='submit' class='submit' onclick="location.href='http://localhost/www/TDUONG/thaygiao/chuyen-de/<?php echo $lmID; ?>/'" value='Danh sách các chuyên đề' /></td>
                                </tr>
                            	<tr style="background:#3E606F;">
                                	<th style="width:10%;"><span>STT</span></th>
                                    <th style="width:10%;"><span>Mã</span></th>
                                    <th><span>Tên chuyên đề</span></th>
                                    <th><span>Số tài liệu</span></th>
                                    <th style="width:10%;"><span></span></th>
                                </tr>
                                <?php
									$dem=0;
									$result1=get_all_chuyende_con($lmID);
									while($data1=mysqli_fetch_assoc($result1)) {
										echo"<tr>
											<td><span>".($dem+1)."</span></td>
											<td><span>$data1[maso]</span></td>
											<td><span>$data1[title]</span></td>
											<td><span>".count_tailieu_sl($data1["ID_CD"])."</span></td>
											<td><input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/tai-lieu-chuyen-de/$data1[ID_CD]/'\" value='Xem' /></td>
                                        </tr>";
									}
									$result1=get_danhmuc($lmID,$monID);
                                    while($data1=mysqli_fetch_assoc($result1)) {
                                        echo"<tr>
											<td><span>".($dem+1)."</span></td>
											<td><span>#</span></td>
											<td><span>$data1[title]</span></td>
											<td><span>".count_tailieu_sl2($data1["ID_DM"])."</span></td>
											<td><input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/tai-lieu-chuyen-de/$data1[ID_DM]/'\" value='Xem' /></td>
                                        </tr>";
                                    }
								?>
                            </table>
                            <table class="table" style="margin-top:25px;">	
               	<?php } else { ?>
                	<h2>Tài liệu miễn phí công cộng</h2>
                    <div>
                    	<div class="status">
                        	<table class="table">
                <?php } ?>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><input type='submit' class='submit' onclick="location.href='http://localhost/www/TDUONG/thaygiao/danh-muc/<?php echo $lmID."/".$monID; ?>/'" value='Danh mục' /></td>
                                    <td><input type='submit' class='submit' onclick="location.href='http://localhost/www/TDUONG/thaygiao/up-tai-lieu/<?php echo $lmID."/".$monID; ?>/'" value='Thêm tài liệu' /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:5%;"><span>STT</span></th>
                                    <th style="width:30%;"><span>Tiêu đề</span></th>
                                    <th style="width:10%;"><span>Ngày up</span></th>
                                    <th style="width:10%";><span>View</span></th>
                                    <th style="width:10%;"><span>Giá</span></th>
                                    <th style='width:15%;'><span>Danh mục</span></th>
                                    <th style="width:20%;"><span></span></th>
                                </tr>
                                <?php
								if($monID!=0 && $lmID!=0) {
									if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
										$position=$_GET["begin"];
									} else {
										$position=0;
									}
									$dem=0;$display=30;
									$result2=get_tailieu($lmID,$monID,$position,$display);
                                    //echo mysqli_error($db);
								} else {
									$dem=0;
									$result2=get_tailieu_dm(0,0);
								}
//                                <td><span>".count_tailieu_like($data2["ID_TL"])." / ".count_tailieu_cmt($data2["ID_TL"])."</span></td>
									while($data2=mysqli_fetch_assoc($result2)) {
									    $query="SELECT COUNT(ID_STT) AS dem FROM log WHERE content='$data2[ID_TL]' AND type='xem-tai-lieu'";
                                        $result=mysqli_query($db,$query);
                                        $data=mysqli_fetch_assoc($result);
										echo"<tr>
											<td><span>".($dem+1)."</span></td>
											<td><span>$data2[name]</span></td>
											<td><span>".format_dateup($data2["dateup"])."</span></td>
											<td><span>$data[dem]</span></td>
											<td><span>";	
												if($data2["price"]==0) {
													echo"FREE";
												} else {
													echo format_price($data2["price"]);
												}
											echo"</span></td>
											<td><span>$data2[title]</span></td>
											<td>
                                            	<input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/xem-tai-lieu/$data2[ID_TL]/'\" value='Xem' />
												<input type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/sua-tai-lieu/$data2[ID_TL]/'\" value='Sửa' />
												<input type='submit' class='submit delete' data-tlID='$data2[ID_TL]' value='Xóa' />
											</td>
										</tr>";
										$dem++;
									}
								?>
                            </table>
                        </div>
                        <?php
						if($monID!=0 && $lmID!=0) {
							$result3=get_all_tailieu($lmID);
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
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/$lmID/$monID/page-$prev/'><</a></li>";
								}
								for($page=1;$page<=$sum_page;$page++) {
									$begin=($page-1)*$display;
									if($page==$current) {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/$lmID/$monID/page-$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
									} else {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/$lmID/$monID/page-$begin/'>$page</a></li>";
									}
								}
								if($current!=$sum_page) {
									$next=$position+$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu/$lmID/$monID/page-$next/'>></a></li>";
								}
							?>
                            </ul>
                        </div>
                        <?php
							}
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
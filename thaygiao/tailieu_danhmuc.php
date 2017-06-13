<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["dmID"]) && is_numeric($_GET["dmID"])) {
		$dmID=$_GET["dmID"];
	} else {
		$dmID=0;
	}
	$result0=get_one_danhmuc($dmID);
	$data0=mysqli_fetch_assoc($result0);
	$monID=$data0["ID_MON"];
	$lopID=$data0["ID_LOP"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TÀI LIỆU <?php echo mb_strtoupper($data0["title"],"UTF-8"); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:77.5%;margin-right:0;}
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
            
            	<div id="main-left">
                    
                    <div>
                    	<h3>Menu</h3>
                        <ul>
                            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/chuyen-de/<?php echo $lmID."/".$monID; ?>/"><i class="fa fa-newspaper-o"></i>CHUYÊN ĐỀ</a></li>
                            <?php
								$result1=get_all_chuyende_con($data0["ID_LOP"],$data0["ID_MON"]);
								while($data1=mysqli_fetch_assoc($result1)) {
									echo"<li class='action'><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu-chuyen-de/$data1[ID_CD]/'><i class='fa fa-folder-o'></i>$data1[title]</a></li>";
								}
                                $result1=get_danhmuc($data0["ID_LOP"],$data0["ID_MON"]);
                                while($data1=mysqli_fetch_assoc($result1)) {
                                    echo"<li class='action'><a href='http://localhost/www/TDUONG/thaygiao/tai-lieu-danh-muc/$data1[ID_DM]/'><i class='fa fa-folder-o'></i>$data1[title]</a></li>";
                                }
							?>
                        </ul>
                    </div>
                    
                </div>
                
                <div id="main-mid">
                	<h2>Tài liệu chuyên đề <span style="font-weight:600;"><?php echo $data0["title"] ?></span></h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                                <tr>
                                    <td colspan="5"></td>
                                    <td><input type='submit' class='submit' onclick="location.href='http://localhost/www/TDUONG/thaygiao/up-tai-lieu/<?php echo $data0["ID_MON"]; ?>/'" value='Thêm tài liệu' /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th style="width:10%;"><span>STT</span></th>
                                    <th style="width:30%;"><span>Tiêu đề</span></th>
                                    <th style="width:10%;"><span>Ngày up</span></th>
                                    <th style="width:10%";><span>Like/Cmt</span></th>
                                    <th style="width:15%;"><span>Giá</span></th>
                                    <th style="width:20%;"><span></span></th>
                                </tr>
                                <?php
									$dem=0;
									$result2=get_tailieu_dm(0,$dmID);
									while($data2=mysqli_fetch_assoc($result2)) {
										echo"<tr>
											<td><span>".($dem+1)."</span></td>
											<td><span>$data2[name]</span></td>
											<td><span>".format_dateup($data2["dateup"])."</span></td>
											<td><span>".count_tailieu_like($data2["ID_TL"])." / ".count_tailieu_cmt($data2["ID_TL"])."</span></td>
											<td><span>";	
												if($data2["price"]==0) {
													echo"FREE";
												} else {
													echo format_price($data2["price"]);
												}
											echo"</span></td>
											<td>"; ?>
                                            	<input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/xem-tai-lieu/<?php echo $data2["ID_TL"]; ?>/'" value="Xem" />
												<input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/sua-tai-lieu/<?php echo $data2["ID_TL"]; ?>/'" value="Sửa" />
												<input type="submit" class="submit delete" data-tlID="<?php echo $data2["ID_TL"]; ?>" value='Xóa' />
											<?php 
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
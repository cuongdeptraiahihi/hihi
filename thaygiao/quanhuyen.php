<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
	if(isset($_GET["tp"]) && is_numeric($_GET["tp"])) {
		$tpID=$_GET["tp"];
	} else {
		$tpID=0;
	}
	$tp_name=get_tp_name($tpID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>DANH SÁCH QUẬN HUYỆN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit", "click", function() {
				quanID = $(this).attr("data-quanID");
				del_tr = $(this).closest("tr");
				del_tr.css("opacity","0.3");
				quan = del_tr.find("td input.quan_"+quanID).val();
					$.ajax({
						async: true,
						data: "quanID=" + quanID + "&quan=" + quan,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-truong/",
						success: function(result) {
							del_tr.find("td input.quan_"+quanID).val(result);
							del_tr.css("opacity","1");
						}
					});
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
                            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/tinh-tp/"><i class="fa fa-map"></i>Tỉnh/Thành phố</a></li>
							<li class="action"><a href="http://localhost/www/TDUONG/thaygiao/pre-quan-huyen/"><i class="fa fa-map-pin"></i>Quận/Huyện</a></li>
                            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/pre-truong-hoc/"><i class="fa fa-university"></i>Trường cấp 3</a></li>
                        </ul>
                    </div>
                </div>
                
                <div id="main-mid">
                	<h2>Danh sách quận huyện thuộc <span style="font-weight:600;"><?php echo $tp_name; ?></span></h2>
                	<div>
                    	<div class="status">
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>Mã</span></th>
                                        <th style="width:45%;"><span>Tên quận/huyện</span></th>
                                        <th style="width:20%;"><span>Số trường</span></th>
                                        <th style="width:20%;"><span></span></th>
                                    </tr>
                                    <?php
										if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
											$position=$_GET["begin"];
										} else {
											$position=0;
										}
										$dem=0;$display=20;
										$result=get_all_quan_sort($tpID, $position, $display);
										while($data=mysqli_fetch_assoc($result)) {
											$num=count_truong_quan($data["ID_QH"]);
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
                                    	<td><span><?php echo $data["ID_QH"];?></span></td>
                                        <td><input class="quan_<?php echo $data["ID_QH"]?> input" type="text" value="<?php echo $data["quanhuyen"]; ?>" /></td>
                                        <td><span><?php echo $num; ?></span></td>
                                        <td>
                                            <input type="submit" class="submit edit" data-quanID="<?php echo $data["ID_QH"];?>" value="Sửa" />
                                      	</td>
                                    </tr>
									<?php 
											$dem++;
										}
									?>
                                </table>
                        </div>
                        <?php
							$result3=get_all_quan();
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
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/quan-huyen/$tpID/page-$prev/'><</a></li>";
								}
								for($page=1;$page<=$sum_page;$page++) {
									$begin=($page-1)*$display;
									if($page==$current) {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/quan-huyen/$tpID/page-$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
									} else {
										echo"<li><a href='http://localhost/www/TDUONG/thaygiao/quan-huyen/$tpID/page-$begin/'>$page</a></li>";
									}
								}
								if($current!=$sum_page) {
									$next=$position+$display;
									echo"<li><a href='http://localhost/www/TDUONG/thaygiao/quan-huyen/$tpID/page-$next/'>></a></li>";
								}
							?>
                            </ul>
                        </div>
                        <?php
							}
						?>
                    </div>
               	</div>
                
                <div id="main-right">
                	<div>
                        <h3>Danh sách<br />Tỉnh/Thành phố</h3>
                        <ul>
                        <?php
							$result2=get_all_tinhtp();
							while($data2=mysqli_fetch_assoc($result2)) {
								echo"<li><a href='http://localhost/www/TDUONG/thaygiao/quan-huyen/$data2[ID_TP]/' ";if($data2["ID_TP"]==$tpID){echo"style='font-weight:600'";}echo">$data2[ID_TP]. $data2[thanhpho]</a></li>";
							}
						?>
                        </ul>
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
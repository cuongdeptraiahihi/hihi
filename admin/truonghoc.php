<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["tp"]) && is_numeric($_GET["tp"]) && isset($_GET["quan"]) && is_numeric($_GET["quan"])) {
		$tpID=$_GET["tp"];
		$quanID=$_GET["quan"];
	} else {
		$tpID=0;
		$quanID=0;
	}
	$tp_name=get_tp_name($tpID);
	$quan_name=get_quan_name($quanID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>DANH SÁCH TRƯỜNG HỌC</title>
        
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
				truongID = $(this).attr("data-truongID");
				del_tr = $(this).closest("tr");
				del_tr.css("opacity","0.3");
				truong = del_tr.find("td input.truong_"+truongID).val();
					$.ajax({
						async: true,
						data: "truongID=" + truongID + "&truong=" + truong,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-truong/",
						success: function(result) {
							del_tr.find("td input.truong_"+truongID).val(result);
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
                
                <div id="main-mid">
                <?php
					if($quanID==0 && $tpID==0) {
                		echo"<h2>Danh sách tất cả trường học</h2>";
                  	} else {
                    	echo"<h2>Danh sách trường học thuộc <span style='font-weight:600;'>$quan_name, $tp_name</span></h2>";
                    }
				?>
                	<div>
                    	<div class="status">
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>Mã</span></th>
                                        <th style="width:45%;"><span>Tên trường</span></th>
                                        <th style="width:20%;"><span>Số học sinh</span></th>
                                        <th style="width:20%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										if($quanID==0 && $tpID==0) {
											$result=get_all_truong();
										} else {
											$result=get_all_truong_sort($quanID);
										}
										while($data=mysqli_fetch_assoc($result)) {
											$num=count_hs_truong($data["ID_T"]);
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
                                    	<td><span><?php echo $data["ID_T"];?></span></td>
                                        <td><input class="truong_<?php echo $data["ID_T"]?> input" type="text" value="<?php echo $data["name"]; ?>" /></td>
                                        <td><span><?php echo $num; ?></span></td>
                                        <td>
                                        	<input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/hoc-sinh-truong/<?php echo $data["ID_T"];?>/0/'" value="Học sinh" />
                                            <input type="submit" class="submit edit" data-truongID="<?php echo $data["ID_T"];?>" value="Sửa" />
                                      	</td>
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
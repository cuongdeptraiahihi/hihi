<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["ddID"]) && is_numeric($_GET["ddID"])) {
		$ddID=$_GET["ddID"];
	} else {
		$ddID=0;
	}
	$result0=get_one_dia_diem($ddID);
	$data0=mysqli_fetch_assoc($result0);
	
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>ĐỊA ĐIỂM <?php echo mb_strtoupper($data0["name"],"UTF-8"); ?></title>
        
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
			$(document).ready(function(){
				
				$("#MAIN #main-mid .status table tr td select.change-diadiem").change(function() {
					del_tr = $(this).closest("tr");
					caID = $(this).attr("data-caID");
					ddID = $(this).find("option:selected").val();
					ca = $(this).attr("data-ca");
					if($.isNumeric(ddID) && $.isNumeric(caID) && ca!="") {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "caID=" + caID + "&ddID0=" + ddID + "&ca=" + ca,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-diadiem/",
							success: function(result) {
								location.reload();
								$("#popup-loading").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					}
				});
			});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                <div id="main-mid">
                	<h2>Địa điểm học: <span style="font-weight:600;"><?php echo $data0["name"]; ?></span></h2>
                	<div>
                    	<div class="status">
                            <table class="table">
                                <tr style="background:#3E606F;">
                                    <th style="width:15%;"><span>Thứ</span></th>
                                    <th style="width:15%;"><span>Giờ</span></th>
                                    <th style="width:20%;"><span>Hiện tại/Sĩ số/Max</span></th>
                                    <th style="width:10%;"><span>Môn</span></th>
                                    <th style="width:30%;"><span>Địa điểm</span></th>
                                </tr>
                                <?php
									$dem=0;
									$result1=get_all_lop_mon();
									while($data1=mysqli_fetch_assoc($result1)) {
										$query="SELECT c.*,g.* FROM cahoc AS c INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO WHERE c.ID_DD='$ddID' ORDER BY c.thu ASC";
										$result=mysqli_query($db,$query);
										while($data=mysqli_fetch_assoc($result)) {
											$num=get_num_hs_ca_hientai($data["ID_CA"]);
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
										<td><span><?php echo $thu_string[$data["thu"]-1]; ?></span></td>
										<td><span><?php echo $data["gio"];?></span></td>
										<td><span><?php echo $num." / $data[siso] / $data[max]";?></span></td>
										<td><span><?php echo $data1["name"]; ?></span></td>
										<td>
                                        	<select class="input change-diadiem" style="height:auto;" data-caID="<?php echo $data["ID_CA"]; ?>" data-ca="<?php echo $data1["ca"]; ?>">
                                            <?php
												$result3=get_all_dia_diem();
												while($data3=mysqli_fetch_assoc($result3)) {
													echo"<option value='$data3[ID_DD]' ";if($data3["ID_DD"]==$data["ID_DD"]){echo"selected='selected'";}echo">$data3[name]</option>";
												}
											?>
                                            </select>
										</td>
									</tr>
									<?php 
											$dem++;
										}
										if($dem==0) {
											echo"<tr><td colspan='6'><span>Không có dữ liệu</span></td></tr>";
										}
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
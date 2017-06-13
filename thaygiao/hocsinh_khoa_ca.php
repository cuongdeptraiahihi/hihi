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
	$lmID=$_SESSION["lmID"];
	$lop_mon_name=get_mon_name($lmID);
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>KHÓA CA HỌC MÔN <?php echo mb_strtoupper($lop_mon_name,"UTF-8"); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN #main-mid .status form table tr td span i {font-size:1.125em;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			$("#MAIN #main-mid .table").delegate("tr td.con-action > span i.fa-check-square-o", "click", function() {
				ca = $(this);
				caID = ca.attr("data-caID");
				hsID = ca.attr("data-hsID");
				if(caID!=0 && hsID!=0 && $.isNumeric(caID) && $.isNumeric(hsID)) {
					$.ajax({
						async: true,
						data: "caID=" + caID + "&hsID=" + hsID + "&action=off",
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-unlock-ca/",
						success: function(result) {
							if(result) {
								ca.attr("class","fa fa-square-o");
							}
						}
					});
				} else {
					alert("Dữ liệu không chính xác!");
				}
			});
			
			$("#MAIN #main-mid .table").delegate("tr td.con-action > span i.fa-square-o", "click", function() {
				ca = $(this);
				caID = ca.attr("data-caID");
				hsID = ca.attr("data-hsID");
				if(caID!=0 && hsID!=0 && $.isNumeric(caID) && $.isNumeric(hsID)) {
					$.ajax({
						async: true,
						data: "caID=" + caID + "&hsID=" + hsID + "&action=on",
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-unlock-ca/",
						success: function(result) {
							if(result) {
								ca.attr("class","fa fa-check-square-o");
							}
						}
					});
				} else {
					alert("Dữ liệu không chính xác!");
				}
			});
			
			$("#MAIN #main-mid .table").delegate("tr td.dad-action > span i.fa-square-o", "click", function() {
				if(confirm("Bạn có chắc chắn mở hết ca cho toàn bộ học sinh không?")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					ca = $(this);
					caID = ca.attr("data-caID");
					lmID = <?php echo $lmID;?>;
					if(caID!=0 && lmID!=0 && $.isNumeric(caID) && $.isNumeric(lmID)) {
						$.ajax({
							async: true,
							data: "caID_all=" + caID + "&lmID=" + lmID + "&action=on",
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-unlock-ca/",
							success: function(result) {
								ca.attr("class","fa fa-check-square-o");
								$("#MAIN #main-mid .table tr td.caID_"+caID+" span i").attr("class","fa fa-check-square-o");
								$("#BODY").css("opacity","1");
								$("#popup-loading").fadeOut("fast");
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
					}
				}
			});
			
			$("#MAIN #main-mid .table").delegate("tr td.dad-action > span i.fa-check-square-o", "click", function() {
				if(confirm("Bạn có chắc chắn khóa hết ca cho toàn bộ học sinh không?")) {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.3");
					ca = $(this);
					caID = ca.attr("data-caID");
					lmID = <?php echo $lmID;?>;
					if(caID!=0 && lmID!=0 && $.isNumeric(caID) && $.isNumeric(lmID)) {
						$.ajax({
							async: true,
							data: "caID_all=" + caID + "&lmID=" + lmID + "&action=off",
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-unlock-ca/",
							success: function(result) {
								ca.attr("class","fa fa-square-o");
								$("#MAIN #main-mid .table tr td.caID_"+caID+" span i").attr("class","fa fa-square-o");
								$("#BODY").css("opacity","1");
								$("#popup-loading").fadeOut("fast");
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
					}
				}
			});
			
			$("#MAIN #main-mid .table tr td.dad-action").each(function(index, element) {
                unlock = $("#unlock_"+index).val();
				if(unlock=="all") {
					$(element).find("span i").attr("class","fa fa-check-square-o");
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
                	<h2>Khóa ca học môn <span style="font-weight:600"><?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status" style="width:auto;overflow-x:scroll;">
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                    	<th style="max-width:100px;min-width:100px;" rowspan="2"><span></span></th>
                                    <?php
										$result1=get_all_cum2($lmID);
										while($data1=mysqli_fetch_assoc($result1)) {
											if($data1["link"]!=0) {
												$big_cum=$data1["link"];
											} else {
												$big_cum=$data1["ID_CUM"];
											}
											$query5="SELECT c.ID_CA FROM cahoc AS c 
											INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' 
											WHERE c.cum='$big_cum'";
											$result5=mysqli_query($db,$query5);
											$dem=mysqli_num_rows($result5);
											echo"<th style='max-width:150px;min-width:150px;' colspan='$dem'><span style='font-size:10px;'>$data1[name]</span></th>";
										}
									?>
                                    </tr>
                                    <tr style="background:#3E606F;">
                                    <?php
										$caID=array();
										$result1=get_all_cum2($lmID);
										while($data1=mysqli_fetch_assoc($result1)) {
											if($data1["link"]!=0) {
												$big_cum=$data1["link"];
											} else {
												$big_cum=$data1["ID_CUM"];
											}
											$query5="SELECT c.ID_CA,c.thu,g.gio FROM cahoc AS c 
											INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.ID_LM='$lmID' 
											WHERE c.cum='$big_cum'";
											$result5=mysqli_query($db,$query5);
											while($data5=mysqli_fetch_assoc($result5)) {
												echo"<th><span style='font-size:10px;'>Thứ $data5[thu]<br />$data5[gio]</span></th>";
												$caID[]=$data5["ID_CA"];
											}
										}
									?>	
                                    </tr>
                                    <tr style="background:#D1DBBD">
                                    	<td><span>Tất cả</span></td>
                                        <?php
											$open_arr=array();
											for($i=0;$i<count($caID);$i++) {
												echo"<td class='dad-action'><span><i class='fa fa-square-o' data-caID='$caID[$i]'></i></span></td>";
												$open_arr[$caID[$i]]=0;
											}
										?>
                                    </tr>
                                    <?php
										$dem=0;
										$query="SELECT h.ID_HS,h.cmt,n.ID_N FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_LM='$lmID' 
										LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=h.ID_HS AND n.ID_LM='$lmID' ORDER BY h.cmt ASC";
										$result=mysqli_query($db,$query);
										while($data=mysqli_fetch_assoc($result)) {
											
											if(isset($data["ID_N"])) {
												continue;
											}
											
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
											echo"<td><span>$data[cmt]</span></td>";
											for($i=0;$i<count($caID);$i++) {
												echo"<td class='con-action caID_$caID[$i]'><span>";
												if(check_unlock_ca_hs($data["ID_HS"], $caID[$i])) {
													echo"<i class='fa fa-check-square-o' data-caID='$caID[$i]' data-hsID='$data[ID_HS]'></i>";
													$open_arr[$caID[$i]]++;
												} else {
													echo"<i class='fa fa-square-o' data-caID='$caID[$i]' data-hsID='$data[ID_HS]'></i>";
												}
												echo"</span></td>";
											}
											echo"</tr>";
											$dem++;
										}
									?>
                                </table>
                                
                                <?php
									for($i=0;$i<count($caID);$i++) {
										if($open_arr[$caID[$i]]==$dem) {
											echo"<input type='hidden' value='all' id='unlock_$i' />";
										} else {
											echo"<input type='hidden' value='none' id='unlock_$i' />";
										}
									}
								?>
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
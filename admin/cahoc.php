<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
	if(isset($_GET["mon"]) && is_numeric($_GET["mon"]) && isset($_GET["lop"]) && is_numeric($_GET["lop"])) {
		$monID=$_GET["mon"];
		$lopID=$_GET["lop"];
	} else {
		$monID=0;
		$lopID=0;
	}
	$monID=$_SESSION["mon"];
	$lopID=$_SESSION["lop"];
	$result0=get_mon_info($monID);
	$data0=mysqli_fetch_assoc($result0);
	$cahoc_string=$data0["ca"];
	$ca_codinh_string=$data0["ca_codinh"];
	$ca_hientai_string=$data0["ca_hientai"];
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>QUẢN LÝ CA HỌC</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function(){$("#select-mon").change(function(){monID=$(this).val(),$.ajax({async: true,data:"monID="+monID,type:"post",url:"http://localhost/www/TDUONG/admin/xuly-mon/",success:function(){}})}),$("#select-mon, #select-lop").change(function(){monID=$("#select-mon").val(),0==monID?$("#select-mon").addClass("new-change"):$("#select-mon").removeClass("new-change"),lopID=$("#select-lop").val(),0==lopID?$("#select-lop").addClass("new-change"):$("#select-lop").removeClass("new-change")}),$("#xem").click(function(){return monID=$("#select-mon").val(),lopID=$("#select-lop").val(),$.isNumeric(monID)&&$.isNumeric(lopID)&&0!=monID&&0!=lopID?!0:(alert("Vui lòng nhập đầy đủ thông tin!"),!1)})});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	<div id="main-left">
                
                	<?php require_once("include/LEFT.php"); ?>
                    
                    <div>
                    	<h3>Menu</h3>
                        <ul>
                        	<li class="action"><a href="http://localhost/www/TDUONG/admin/pre-ca/"><i class="fa fa-calendar"></i>Danh sách ca học</a></li>
                        	<li class="action"><a href="http://localhost/www/TDUONG/admin/pre-gio/"><i class="fa fa-table"></i>Các khung giờ</a></li>
                        </ul>
                    </div>
                </div>
                
                <?php
					$mon=$lop=NULL;
					if(isset($_GET["xem"])) {
						if(isset($_GET["monID"])) {
							$mon=$_GET["monID"];
						}
						if(isset($_GET["lopID"])) {
							$lop=$_GET["lopID"];
						}
						if($mon && $lop) {
							header("location:http://localhost/www/TDUONG/admin/ca-hoc/$mon/$lop/");
							exit();
						}
					}
				?>
                
                <div id="main-mid">
                	<h2>QUẢN LÝ CA HỌC</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/ca-hoc/" method="get" style="position:relative;">
                        		<table class="table" style="width:70%;">
                                    <tr>
                                    	<td style="width:50%;"><span>Môn</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" id="select-mon" name="monID">
                                                <option value="0">Chọn môn</option>
                                            <?php
                                                $result=get_all_mon_admin();
                                                for($i=0;$i<count($result);$i++) {
                                                    echo"<option value='".$result[$i]["monID"]."' data-name='".$result[$i]["name"]."' ";if($monID==$result[$i]["monID"]){echo"selected='selected'";}echo">Môn ".$result[$i]["name"]."</option>";
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><span>Khóa</span></td>
                                        <td>
                                            <select class="input" style="height:auto;width:100%;" id="select-lop" name="lopID">
                                                <option value="0">Chọn khóa</option>
                                            <?php
                                                $result5=get_all_lop();
                                                while($data5=mysqli_fetch_assoc($result5)) {
                                                    echo"<option value='$data5[ID_LOP]' data-lop='$data5[name]' ";if($lopID==$data5["ID_LOP"]){echo"selected='selected'";}echo">$data5[name]</option>";
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <nav id="on-button"><input class="submit" name="xem" type="submit" id="xem" value="Xem" /></nav>
                                </table>
                         	</form>	
                            <?php
								if($monID!=0 && $lopID!=0) {
							?>
                            <table class="table" style="margin-top:25px;">
                            	<tr style="background:#3E606F;"><th><span>Thông tin ca cố định</span></th></tr>
                            </table>
                            <div id="main-1-left">
                            <table class="table-tkb">
                            	<tr>
                                	<th style="border-right:1px solid #3E606F;"><span>CA HỌC</span></th>
                               	<?php
									for($i=2;$i<=7;$i++) {
										if($i%2!=0) {
											echo"<th style='border-right:1px solid #3E606F;'><span>Thứ $i</span></th>";
										} else {
											echo"<th><span>Thứ $i</span></th>";
										}
									}
								?>
                                </tr>
                                <?php
									$tkb=array();
									$query1="SELECT * FROM cagio WHERE lop='$lopID' AND loai='hoc' AND ID_MON='$monID' ORDER BY ID_GIO ASC";
									$result1=mysqli_query($db,$query1);
									while($data1=mysqli_fetch_assoc($result1)) {
										echo"<tr>
											<td style='border-right:1px solid #3E606F;'><span>$data1[gio]</span></td>";
										$query3="SELECT * FROM $cahoc_string WHERE ID_GIO='$data1[ID_GIO]' ORDER BY thu ASC";
										$result3=mysqli_query($db,$query3);
										$j=0;
										while($data3=mysqli_fetch_assoc($result3)) {
											$num=get_num_hs_ca_codinh($data3["ID_CA"], $ca_codinh_string);
											if($j%2!=0) {
												echo"<td style='border-right:1px solid #3E606F;'>";
											} else {
												echo"<td>";
											}
											echo"<span>$num</span></td>";
											$j++;
										}
										echo"</tr>";
									}
									sort($tkb);
								?>
                            </table>
                            </div>
                            <div id="main-1-right">
                            <table class="table-kt">
                            	<tr>
                                	<th colspan="2"><span>CA THI</span></th>
                                </tr>
                           	<?php
								$query4="SELECT * FROM cagio INNER JOIN $cahoc_string ON $cahoc_string.ID_GIO=cagio.ID_GIO WHERE cagio.lop='3' AND cagio.loai='kiemtra' AND cagio.ID_GIO%2=0 ORDER BY cagio.ID_GIO ASC";
								$result4=mysqli_query($db,$query4);
								$j=0;
								while($data4=mysqli_fetch_assoc($result4)) {
									$num=get_num_hs_ca_codinh($data4["ID_CA"], $ca_codinh_string);
									if($j%2!=0) {
										echo"<tr style='background:#D1DBBD;'>";
									} else {
										echo"<tr>";
									}
									echo"
										<td><span>$data4[gio]</span></td>
										<td><p>$num</p></td>
									</tr>";
									$j++;
								}
							?>
                            </table>
                            <table class="table-kt">
                            <?php
								$query4="SELECT * FROM cagio INNER JOIN $cahoc_string ON $cahoc_string.ID_GIO=cagio.ID_GIO WHERE cagio.lop='3' AND cagio.loai='kiemtra' AND cagio.ID_GIO%2!=0 ORDER BY cagio.ID_GIO ASC";
								$result4=mysqli_query($db,$query4);
								$j=0;
								while($data4=mysqli_fetch_assoc($result4)) {
									$num=get_num_hs_ca_codinh($data4["ID_CA"], $ca_codinh_string);
									if($j%2!=0) {
										echo"<tr style='background:#D1DBBD;'>";
									} else {
										echo"<tr>";
									}
									echo"
										<td><span>$data4[gio]</span></td>
										<td><p>$num</p></td>
									</tr>";
									$j++;
								}
							?>
                            </table>
                            </div>
                            <table class="table" style="margin-top:25px;">
                            	<tr style="background:#3E606F;"><th><span>Thông tin ca hiện tại</span></th></tr>
                            </table>
                            <div id="main-1-left">
                            <table class="table-tkb">
                            	<tr>
                                	<th style="border-right:1px solid #3E606F;"><span>CA HỌC</span></th>
                               	<?php
									for($i=2;$i<=7;$i++) {
										if($i%2!=0) {
											echo"<th style='border-right:1px solid #3E606F;'><span>Thứ $i</span></th>";
										} else {
											echo"<th><span>Thứ $i</span></th>";
										}
									}
								?>
                                </tr>
                                <?php
									$tkb=array();
									$query1="SELECT * FROM cagio WHERE lop='$lopID' AND loai='hoc' AND ID_MON='$monID' ORDER BY ID_GIO ASC";
									$result1=mysqli_query($db,$query1);
									while($data1=mysqli_fetch_assoc($result1)) {
										echo"<tr>
											<td style='border-right:1px solid #3E606F;'><span>$data1[gio]</span></td>";
										$query3="SELECT * FROM $cahoc_string WHERE ID_GIO='$data1[ID_GIO]' ORDER BY thu ASC";
										$result3=mysqli_query($db,$query3);
										$j=0;
										while($data3=mysqli_fetch_assoc($result3)) {
											$num=get_num_hs_ca_hientai($data3["ID_CA"], $ca_hientai_string);
											if($j%2!=0) {
												echo"<td style='border-right:1px solid #3E606F;'>";
											} else {
												echo"<td>";
											}
											echo"<span>$num</span></td>";
											$j++;
										}
										echo"</tr>";
									}
									sort($tkb);
								?>
                            </table>
                            </div>
                            <div id="main-1-right">
                            <table class="table-kt">
                            	<tr>
                                	<th colspan="2"><span>CA THI</span></th>
                                </tr>
                           	<?php
								$query4="SELECT * FROM cagio INNER JOIN $cahoc_string ON $cahoc_string.ID_GIO=cagio.ID_GIO WHERE cagio.lop='3' AND cagio.loai='kiemtra' AND cagio.ID_GIO%2=0 ORDER BY cagio.ID_GIO ASC";
								$result4=mysqli_query($db,$query4);
								$j=0;
								while($data4=mysqli_fetch_assoc($result4)) {
									$num=get_num_hs_ca_hientai($data4["ID_CA"], $ca_hientai_string);
									if($j%2!=0) {
										echo"<tr style='background:#D1DBBD;'>";
									} else {
										echo"<tr>";
									}
									echo"
										<td><span>$data4[gio]</span></td>
										<td><span></span></td>
										<td><p>$num</p></td>
									</tr>";
									$j++;
								}
							?>
                            </table>
                            <table class="table-kt">
                            <?php
								$query4="SELECT * FROM cagio INNER JOIN $cahoc_string ON $cahoc_string.ID_GIO=cagio.ID_GIO WHERE cagio.lop='3' AND cagio.loai='kiemtra' AND cagio.ID_GIO%2!=0 ORDER BY cagio.ID_GIO ASC";
								$result4=mysqli_query($db,$query4);
								$j=0;
								while($data4=mysqli_fetch_assoc($result4)) {
									$num=get_num_hs_ca_hientai($data4["ID_CA"], $ca_hientai_string);
									if($j%2!=0) {
										echo"<tr style='background:#D1DBBD;'>";
									} else {
										echo"<tr>";
									}
									echo"
										<td><span>$data4[gio]</span></td>
										<td><span></span></td>
										<td><p>$num</p></td>
									</tr>";
									$j++;
								}
							?>
                            </table>
                            </div>
                            <?php } ?>
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
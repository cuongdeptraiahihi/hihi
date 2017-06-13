<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>DANH SÁCH QUẬN/HUYỆN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			$("#xem").click(function() {
				tpID = $("#select-tinhtp").val();
				if($.isNumeric(tpID) && tpID!=0) {
					return true;
				} else {
					alert("Vui lòng nhập đầy đủ thông tin!");
					return false;
				}
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <?php
					$tp=NULL;
					if(isset($_GET["xem"])) {
						if(isset($_GET["tpID"])) {
							$tp=$_GET["tpID"];
						}
						if($tp) {
							header("location:http://localhost/www/TDUONG/admin/quan-huyen/$tp/");
							exit();
						}
					}
				?>
                
                <div id="main-mid">
                	<h2>Danh sách trường học</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/pre-quan-huyen/" method="get">
                        		<table class="table">
                                    <tr>
                                    	<td style="width:50%;"><span>Tỉnh/Thành phô</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" id="select-tinhtp" name="tpID">
                                                <!--<option value="0">Chọn Tỉnh/Thành phô</option>-->
                                            <?php
												$result1=get_all_tinhtp();
												while($data1=mysqli_fetch_assoc($result1)) {
													echo"<option value='$data1[ID_TP]'>$data1[thanhpho]</option>";
												}
											?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Xem" /></td>
                                    </tr>
                                </table>
                         	</form>
                            <table class="table" style="margin-top:25px;">
                            	<tr>
                                	<td><input class="submit" style="width:50%;font-size:1.375em;" type="submit" onclick="location.href='http://localhost/www/TDUONG/admin/tinh-tp/'" value="Tỉnh/Thành Phố" /></td>
                                    <td><input class="submit" style="width:50%;font-size:1.375em;" type="submit" onclick="location.href='http://localhost/www/TDUONG/admin/pre-quan-huyen/'" value="Quận huyện" /></td>
                                </tr>
                           	</table>
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
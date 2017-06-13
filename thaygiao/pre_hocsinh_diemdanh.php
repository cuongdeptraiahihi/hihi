<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
    $monID=$_SESSION["mon"];
	$lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>LỊCH SỬ ĐIỂM DANH</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			$("#xem").click(function() {
				loai = $("#select-loai").val();
				if($.isNumeric(loai) && loai!=0) {
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
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <?php
					$loai=NULL;
					if(isset($_GET["xem"])) {
						if(isset($_GET["loai"])) {
							$loai=$_GET["loai"];
						}
						if($loai) {
							header("location:http://localhost/www/TDUONG/thaygiao/hoc-sinh-diem-danh/$lmID/$monID/$loai/");
							exit();
						}
					}
				?>
                
                <div id="main-mid">
                	<h2>Lịch sử điểm danh <span style="font-weight:600;">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/thaygiao/pre-hoc-sinh-diem-danh/" method="get">
                        		<table class="table">
                                	<tr style="background:#3E606F;">
                                    	<th colspan="2"><span>Lịch sử điểm danh</span></th>
                                    </tr>
                                    <tr>
                                    	<td style="width:50%;"><span>Thời gian</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" id="select-loai" name="loai">
                                            	<option value="1">Tháng hiện nay và tháng gần nhất (khuyên dùng)</option>
                                                <option value="2">Tất cả</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Xem" /></td>
                                    </tr>
                                </table>
                         	</form>
                            
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
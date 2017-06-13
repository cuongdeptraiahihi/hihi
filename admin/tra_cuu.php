<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
    $lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>TRA CỨU HỌC SINH</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/admin/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
            table tr td a {text-decoration: underline;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			$("#search-hs").keyup(function() {
			    var maso = $(this).val().trim();
                if(maso.length == 7) {
                    $.ajax({
                        async: true,
                        data: "maso=" + maso,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-hocsinh/",
                        success: function (result) {
                            $("#ket-qua").html(result);
                        }
                    });
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
                
                <div id="main-mid">
                	<h2>TRA CỨU SỐ ĐIỆN THOẠI</h2>
                	<div>
                    	<div class="status">
                        	<table class="table">
                            	<tr>
                                	<td><input type="tel" class="input" id="search-hs" placeholder="Nhập vào 1 mã học sinh" /></td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;padding-left: 15px;line-height:30px;"><span id="ket-qua"></span></td>
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
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
        
        <title>TÌM HÓA ĐƠN</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/jquery-ui.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status #search-box {width:100%;}.check {width:20px;height:20px;margin-right:10px;}
			#MAIN > #main-mid > div .status .table-2 {display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status .table-2 tr td span i {font-size:1.5em;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:22px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}.mon-lich {display:none;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			
			$("#add-ngay").typeWatch({
				captureLength: 10,
				callback: function(value) {
					if(value!="" && value!=" " && value!="%" && value!="_") {
						$("#popup-loading").fadeIn("fast");
						$("#BODY").css("opacity","0.3");
						$.ajax({
							async: true,
							data: "search_ngay=" + value,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-tienhoc/",
							success: function(result) {
								$("#info-kq").html(result);
								$("#popup-loading").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					}
				}
			});
			
			$("#add-ngay").datepicker({
				dateFormat: "yy-mm-dd"
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
					<h2>Tìm hóa đơn</h2>
                	<div>
                    	<div class="status">
                            <table class="table table-2" style="width:39%;" id="info-ngay">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin</span></th></tr>
                                <tr>
                                	<td style="width:35%;"><span>Ngày đóng (ghi trên hóa đơn)</span></td>
									<td style="width:65%;"><input type="text" id="add-ngay" autocomplete="off" class="input" placeholder="Click để chọn.." /></td>
                                </tr>
                            </table>
                            <table class="table table-2" style="width:59%;" id="info-kq">
                                <tr style="background:#3E606F;"><th colspan="2"><span>Kết quả</span></th></tr>
                                <tr>
                                	<td colspan="2" style="text-align:center;"><span>Vui lòng chọn 1 ngày</span></td>
                                </tr>
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
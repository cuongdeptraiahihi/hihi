<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	$monID=$_SESSION["mon"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>ĐỔI MẬT KHẨU</title>
        
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
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status #search-box {width:80%;}.check {width:20px;height:20px;margin-right:10px;}
			#MAIN > #main-mid > div .status .table-2 {display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status .table-2 tr td span i {font-size:1.5em;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:22px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}.mon-lich {display:none;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/admin/js/jquery-ui.js"></script>
        <script>
			$(document).ready(function() {
				
				$(".add-new").click(function() {
					old = $("#add-old").val();
					mk1 = $("#add-mk1").val();
					mk2 = $("#add-mk2").val();
					if(confirm("Bạn có chắc chắn đổi mật khẩu?")) {
						if(mk1==mk2 && mk1!="" && mk2!="" && old!="") {
							return true;
						} else {
							alert("Vui lòng nhập đầy đủ thông tin!");
							return false;
						}
					}
				});
				
				$(".popup").click(function() {
					$("#BODY").css("opacity","1");
					$(".popup").fadeOut("fast");
				});
			});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/admin/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
			$error="";
			$old=$mk1=$mk2=NULL;
			if(isset($_POST["add-new"])) {
				if(isset($_POST["add-old"])) {
					$old=$_POST["add-old"];
				}
				if(isset($_POST["add-mk1"])) {
					$mk1=mysql_escape_string($_POST["add-mk1"]);
				}
				if(isset($_POST["add-mk2"])) {
					$mk2=mysql_escape_string($_POST["add-mk2"]);
				}
				if($old && $mk1 && $mk2 && $old!="" && $mk1!="" && $mk2!="") {
					if(valid_text($old) && valid_text($mk1) && valid_text($mk2) && valid_pass($mk1) && valid_pass($mk2)) {
					    if($mk1==$mk2) {
                            $result = login_admin($_SESSION["username"], md5($old));
                            if (mysqli_num_rows($result) != 0) {
                                $query2 = "UPDATE admin SET password='" . md5($mk1) . "' WHERE username='" . $_SESSION["username"] . "' AND level='1' AND note='boss'";
                                mysqli_query($db, $query2);
                                $error = "<div class='popup' style='display:block'>
                                <p>Đổi mật khẩu thành công!</p>
                            </div>";
                            } else {
                                $error = "<div class='popup' style='display:block'>
                                    <p>Mật khẩu cũ không chính xác!</p>
                                </div>";
                            }
                        } else {
                            $error = "<div class='popup' style='display:block'>
                                <p>Mật khẩu nhập lại không chính xác!</p>
                            </div>";
                        }
					} else {
						$error="<div class='popup' style='display:block'>
							<p>Mật khẩu không thỏa mãn!</p>
						</div>";
					}
				} else {
					$error="<div class='popup' style='display:block'>
						<p>Vui lòng nhập đầy đủ thông tin và chính xác!</p>
					</div>";
				}
			}
			
			echo $error;
		?>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>Đổi mật khẩu</h2>
                	<div>
                    	<div class="status">
                        <form action="http://localhost/www/TDUONG/admin/doi-mat-khau/" method="post">
                            <table class="table">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Đổi mật khẩu</span></th></tr>
                                <tr>
                                	<td colspan="2"><span>Mật khẩu chứa ít nhất 6 kí tự và bắt đầu bằng chữ cái</span></td>
                                </tr>
                                <tr>
                                    <td><span>Mật khẩu cũ</span></td>
                                    <td><input class="input" id="add-old" type="password" name="add-old" /></td>
                                </tr>
                                <tr>
                                	<td><span>Mật khẩu mới</span></td>
                                    <td><input class="input" id="add-mk1" type="password" name="add-mk1" /></td>
                                </tr>
                                <tr>
                                	<td><span>Nhập lại mật khẩu mới</span></td>
                                    <td><input class="input" id="add-mk2" type="password" name="add-mk2" /></td>
                                </tr>
                                <tr>
                            		<th colspan="2" style="text-align:center;"><input type="submit" style="width:50%;font-size:1.375em;" class="submit add-new" value="Thay đổi" name="add-new" /></th>
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
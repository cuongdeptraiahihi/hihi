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
        
        <title>MỚI NHẢY ĐỀ</title>
        
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
			
			$("#xem").click(function() {
				monID = $("#select-mon").val();
				lopID = $("#select-lop").val();
				de = $("#select-de").val();
				if($.isNumeric(monID) && monID!=0 && $.isNumeric(lopID) && lopID!=0 && de!="") {
					return true;
				} else {
					alert("Vui lòng nhập đầy đủ thông tin!");
					return false;
				}
			});
			
			$("#select-mon, #select-lop, #select-de").change(function() {
				monID = $("#select-mon").val();
				if(monID==0) {
					$("#select-mon").addClass("new-change");
				} else {
					$("#select-mon").removeClass("new-change");
				}
				lopID = $("#select-lop").val();
				if(lopID==0) {
					$("#select-lop").addClass("new-change");
				} else {
					$("#select-lop").removeClass("new-change");
				}
				de = $("#select-de").val();
				if(de=="") {
					$("#select-de").addClass("new-change");
				} else {
					$("#select-de").removeClass("new-change");
				}
			});
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <?php
					$mon=$lop=$de=NULL;
					if(isset($_GET["xem"])) {
						if(isset($_GET["monID"])) {
							$mon=$_GET["monID"];
						}
						if(isset($_GET["lopID"])) {
							$lop=$_GET["lopID"];
						}
						if(isset($_GET["de"])) {
							$de=$_GET["de"];
						}
						if($mon && $lop && $de) {
							header("location:http://localhost/www/TDUONG/admin/moi-chuyen-de/$mon/$lop/$de/");
							exit();
						}
					}
				?>
                
                <div id="main-mid">
                	<h2>Mới nhảy đề</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/admin/nhay-de/" method="get">
                        		<table class="table">
                                    <tr>
                                    	<td style="width:50%;"><span>Môn</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" id="select-mon" name="monID">
                                                <option value="0">Chọn môn</option>
                                            <?php
                                                $result=get_all_mon_admin();
                                                for($i=0;$i<count($result);$i++) {
                                                    echo"<option value='".$result[$i]["monID"]."' data-name='".$result[$i]["name"]."'>Môn ".$result[$i]["name"]."</option>";
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
                                                    echo"<option value='$data5[ID_LOP]' data-lop='$data5[name]'>$data5[name]</option>";
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><span>Đề cũ sang Đề mới</span></td>
                                        <td>
                                            <select class="input" id="select-de" style="height:auto;width:100%;" name="de">
                                                <option value="">Chọn kiểu chuyển đề</option>
                                                <option value="BtoG">Đề B lên đề G</option>
                                                <option value="GtoB">Đề G xuống đề B</option>
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
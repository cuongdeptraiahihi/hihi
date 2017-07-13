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
        
        <title>UNLOCK VIDEO</title>
        
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
		$(document).ready(function() {
			$("#select-lop").change(function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				lopID = $(this).val();
				monID = $("#select-mon").val();
				if($.isNumeric(lopID) && $.isNumeric(monID) && lopID!=0 && monID!=0) {
					$.ajax({
						async: true,
						data: "monID2=" + monID + "&lopID2=" + lopID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
						success: function(result) {
							$("#select-cd").html(result);
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
						}
					});
				}
			});
			
			$("#select-mon, #select-lop, #select-cd").change(function() {
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
				cdID = $("#select-cd").val();
				if(cdID==0) {
					$("#select-cd").addClass("new-change");
				} else {
					$("#select-cd").removeClass("new-change");
				}
			});
			
			$("#xem").click(function() {
				monID = $("#select-mon").val();
				lopID = $("#select-lop").val();
				cdID = $("#select-cd").val();
				if($.isNumeric(monID) && $.isNumeric(lopID) && $.isNumeric(cdID) && monID!=0 && cdID!=0 && lopID!=0) {
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
					$mon=$cd=$lop=NULL;
					if(isset($_GET["xem"])) {
						if(isset($_GET["monID"])) {
							$mon=$_GET["monID"];
						}
						if(isset($_GET["cdID"])) {
							$cd=$_GET["cdID"];
						}
						if(isset($_GET["lopID"])) {
							$lop=$_GET["lopID"];
						}
						if($mon && $cd && $lop) {
							header("location:http://localhost/www/TDUONG/thaygiao/mo-video-hang-loat/$mon/$lop/$cd/");
							exit();
						}
					}
				?>
                
                <div id="main-mid">
                	<h2>MỞ VIDEO HÀNG LOẠT</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/thaygiao/pre-mo-video-hang-loat/" method="get">
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
                                                $result5=get_all_lop2();
                                                while($data5=mysqli_fetch_assoc($result5)) {
                                                    echo"<option value='$data5[ID_LOP]' data-lop='$data5[name]'>$data5[name]</option>";
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><span>Chuyên đề</span></td>
                                        <td>
                                            <select class="input" id="select-cd" style="height:auto;width:100%;" name="cdID">
                                                <option value="0">Chọn chuyên đề</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Mở" /></td>
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
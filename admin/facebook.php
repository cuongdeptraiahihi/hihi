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
        
        <title>GROUP FACEBOOK</title>
        
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
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
			$(document).ready(function() {
				$("#MAIN #main-mid .status table tr td .edit").click(function() {
					$("#popup-loading").fadeIn("fast");
					$("#BODY").css("opacity","0.1");
					oID = $(this).attr("data-oID");
					if(!$.isNumeric(oID)) {
						oID = 0;
					}
					lmID = $(this).attr("data-lmID");
					face = $(this).closest("tr").find("td input.input").val();
					if(lmID!=0 && $.isNumeric(lmID) && face!="" && $.isNumeric(oID)) {
						$.ajax({
							url: "http://localhost/www/TDUONG/admin/xuly-facebook/",
							async: true,
							data: "oID=" + oID + "&face=" + face + "&lmID=" + lmID,
							type: "post",
							success: function(result) {
								$(".popup").fadeOut("fast");
								$("#BODY").css("opacity","1");
							}
						});
					} else {
						alert("Dữ liệu không chính xác!");
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
                	<h2>Group Facebook</h2>
                	<div>
                    	<div class="status">
                        	<table class="table">
                            	<tr style="background:#3E606F;">
                                	<th style="width:10%;"><span>STT</span></th>
                                    <th><span>Group</span></th>
                                    <th style="width:20%;"><span>Môn</span></th>
                                    <th style="width:15%;"><span></span></th>
                                </tr>
                                <?php
									$dem=0;
									$result=get_all_lop_mon();
									while($data=mysqli_fetch_assoc($result)) {
                                        $result2=get_group_facebook($data["ID_LM"]);
                                        $data2=mysqli_fetch_assoc($result2);
                                        if($dem%2!=0) {
                                            echo"<tr style='background:#D1DBBD'>";
                                        } else {
                                            echo"<tr>";
                                        }
                                            echo"<td><span>".($dem+1)."</span></td>
                                            <td><input class='input' type='text' value='$data2[content]' /></td>
                                            <td><span>$data[name]</span></td>
                                            <td><input type='submit' class='submit edit' data-oID='$data2[ID_O]' data-lmID='$data[ID_LM]' value='Sửa' /></td>
                                        </tr>";
                                        $dem++;
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
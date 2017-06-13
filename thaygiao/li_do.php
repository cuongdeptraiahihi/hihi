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
        
        <title>CÁC LÍ DO</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#select-mon").change(function() {
				monID = $(this).val();
				$.ajax({
					async: true,
					data: "monID=" + monID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-mon/",
					success: function() {
						location.reload();
					}
				});
			});
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit", "click", function() {
					del_tr = $(this).closest("tr");
					del_tr.css("opacity","0.3");
					ldID = $(this).attr("data-ldID");
					name = del_tr.find("td input.name_"+ldID).val();
					mau = del_tr.find("td input.mau_"+ldID).val();
					if($.isNumeric(ldID) && name!="" && mau!="") {
						$.ajax({
							async: true,
							data: "ldID=" + ldID + "&name2=" + name + "&mau2=" + mau,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-lido/",
							success: function(result) {
								del_tr.find("td input.name_"+ldID).val(name);
								del_tr.find("td input.mau_"+ldID).val(mau);
								del_tr.find("td span.name-string").html(result);
								del_tr.css("opacity","1");
							}
						});
						return false;
					}
			});
			
			$("#add-lido").click(function() {
				$("#input-add").val("");
				$("#popup-add").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
			});
			
			$(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast");
				$("#BODY").css("opacity","1");
			});
			
			$("#popup-ok").click(function() {
				name = $("#name").val();
				mau = $("#mau").val();
				if(name!="" && mau!="") {
					$.ajax({
						async: true,
						data: "name=" + name + "&mau=" + mau,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-lido/",
						success: function(result) {
							$("#popup-add").fadeOut("fast");
							$("#BODY").css("opacity","1");
							location.reload();
						}
					});
				} else {
					alert("Bạn vui lòng nhập đầy đủ thông tin!");
				}
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm lí do</p>
            <div style="width:90%;margin:15px auto 15px auto;">
                 <input class="input" type="text" id="name" placeholder="Lí do..." />
                 <input class="input" type="text" id="mau" placeholder="red, blue, brown, yellow, none,..." style="width:40%;margin-top:10px;" />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>CÁC LÍ DO HỦY BÀI</h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                        <td colspan="4"></td>
                                        <td><input type="submit" class="submit" value="Thêm lí do" id="add-lido" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>STT</span></th>
                                        <th style="width:30%;"><span>Lí do</span></th>
                                        <th style="width:20%;"><span>Chuỗi</span></th>
                                        <th style="width:15%;"><span>Màu</span></th>
                                        <th style="width:20%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_all_lido();
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
                                    	<td><span><?php echo ($dem+1);?></span></td>
                                        <td><input type="text" class="input name_<?php echo $data["ID_LD"];?>" value="<?php echo $data["name"];?>" /></td>
                                        <td><span class="name-string"><?php echo $data["string"]; ?></span></td>
                                        <td><input type="text" class="input mau_<?php echo $data["ID_LD"];?>" value="<?php echo $data["mau"];?>" /></td>
                                        <td>
                                            <input type="submit" class="submit edit" data-ldID="<?php echo $data["ID_LD"];?>" value="Sửa" />
                                      	</td>
                                    </tr>
									<?php 
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
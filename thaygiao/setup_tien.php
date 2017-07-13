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
        
        <title>CẤU HÌNH MỨC TIỀN</title>
        
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
			#MAIN > #main-mid {width:100%;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit", "click", function() {
				tienID = $(this).attr("data-tienID");
				del_tr = $(this).closest("tr");
				del_tr.css("opacity","0.3");
				mota = del_tr.find("td input.mota_"+tienID).val();
				tien = del_tr.find("td input.tien_"+tienID).val();
					$.ajax({
						async: true,
						data: "tienID=" + tienID + "&mota=" + mota + "&tien=" + tien,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-muctien/",
						success: function(result) {
							obj = jQuery.parseJSON(result);
							del_tr.find("td input.mota_"+tienID).val(obj.mota);
							del_tr.find("td input.tien_"+tienID).val(obj.tien);
							del_tr.css("opacity","1");
						}
					});
				return false;
			});
			
			$("#add_tien").click(function() {
				$("#input-add").val("");
				$("#popup-add").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				$("#mota-add").val("");
				$("#tien-add").val("");
				$("#string-add").val("");
			});
			
			$("#add_tien2").click(function() {
				$("#popup-add2").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				$("#lido-add > option:first").prop("selected",true);
				$("#tien-add2").val("");
			});
			
			$(".popup-close").click(function() {
				$(".popup").fadeOut("fast");
				$("#BODY").css("opacity","1");
			});
			
			$("#popup-ok").click(function() {
				mota = $("#mota-add").val();
				tien = $("#tien-add").val();
				string = $("#string-add").val();
				$.ajax({
					async: true,
					data: "mota=" + mota + "&tien=" + tien + "&string=" + string,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-muctien/",
					success: function(result) {
						if(result==0) {
							alert("Chuỗi không được trùng!");
						} else {
							new_tr = $("#MAIN #main-mid .status .table tr").length;
							if(new_tr%2!=0) {
								new_tr_color="";
							} else {
								new_tr_color=" style='background:#D1DBBD'";
							}
							$("#MAIN #main-mid .status .table").append("<tr"+new_tr_color+"><td><span>"+new_tr+"</span></td><td><input class='mota_"+result+" input' type='text' value='"+mota+"' /></td><td><input class='tien_"+result+" input' type='number' min='0' value='"+tien+"' /></td><td><span>"+string+"</span></td><td><input type='submit' class='submit edit' data-tienID='"+result+"' value='Sửa' /></td></tr>");
						}
						$("#popup-add").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				});
			});
			
			$("#popup-ok2").click(function() {
				lido = $("#lido-add > option:selected").val();
				tien = $("#tien-add2").val();
				$.ajax({
					async: true,
					data: "lido=" + lido + "&tien2=" + tien,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-muctien/",
					success: function(result) {
						if(result==0) {
							alert("Chuỗi không được trùng!");
						} else {
							new_tr = $("#MAIN #main-mid .status .table tr").length;
							if(new_tr%2!=0) {
								new_tr_color="";
							} else {
								new_tr_color=" style='background:#D1DBBD'";
							}
							$("#MAIN #main-mid .status .table").append("<tr"+new_tr_color+"><td><span>"+new_tr+"</span></td><td><input class='mota_"+result+" input' type='text' value='"+mota+"' /></td><td><input class='tien_"+result+" input' type='number' min='0' value='"+tien+"' /></td><td><span>"+string+"</span></td><td><input type='submit' class='submit edit' data-tienID='"+result+"' value='Sửa' /></td></tr>");
						}
						$("#popup-add2").fadeOut("fast");
						$("#BODY").css("opacity","1");
					}
				});
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm mức tiền</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            	<input id="mota-add" class="input" autocomplete="off" placeholder="Mô tả" />
                <ul class="ul-2">
                	<li style="width:45%;"><input type="number" class="input" min="0" id="tien-add" placeholder="Tiền" /></li>
                    <li style="width:45%;"><input type="text" id="string-add" class="input" placeholder="Chuỗi" /></li>
                </ul>
           	</div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
        
        <div class="popup" id="popup-add2" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm mức tiền phạt bài kiểm tra</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            	<select style="width:100%;height:100%;" class="input" id="lido-add">
                	<option value="0">Chọn lý do hủy bài</option>
                    <?php
						$result1=get_all_lido();
						while($data1=mysqli_fetch_assoc($result1)) {
							echo"<option value='$data1[ID_LD]'>$data1[name]</option>";
						}
					?>
                </select>
            	<input type="number" style="margin-top:10px;" class="input" min="0" id="tien-add2" placeholder="Tiền" />
           	</div>
            <div><button class="submit" id="popup-ok2">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>Cấu hình mức tiền</h2>
                	<div>
                    	<div class="status">
                            	<table class="table">
                                	<tr>
                                    	<td colspan="3"></td>
                                        <td><input type="submit" class="submit" id="add_tien2" value="Thêm mức tiền phạt KT" /></td>
                                        <td><input type="submit" class="submit" id="add_tien" value="Thêm mức tiền" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>STT</span></th>
                                        <th style="width:35%;"><span>Mô tả</span></th>
                                        <th style="width:15%;"><span>Số tiền</span></th>
                                        <th style="width:20%;"><span>Chuỗi</span></th>
                                        <th style="width:15%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_all_muctien();
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
                                    	<td><span><?php echo ($dem+1);?></span></td>
                                        <td><input class="mota_<?php echo $data["ID_TIEN"]?> input" type="text" value="<?php echo $data["mota"]; ?>" /></td>
                                        <td><input class="tien_<?php echo $data["ID_TIEN"]?> input" type="number" min="0" value="<?php echo $data["tien"]; ?>" /></td>
                                        <td><span><?php echo $data["string"];?></span></td>
                                        <td><input type="submit" class="submit edit" data-tienID="<?php echo $data["ID_TIEN"];?>" value="Sửa" /></td>
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
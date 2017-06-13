<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["lm"]) && is_numeric($_GET["lm"])) {
		$lmID=$_GET["lm"];
	} else {
		$lmID=0;
	}
	$lmID=$_SESSION["lmID"];
    $monID=$_SESSION["mon"];
	$lop_mon_name=get_lop_mon_name($lmID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>CÁC CHUYÊN ĐỀ MÔN <?php echo mb_strtoupper($lop_mon_name); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}.con-action {background:#3E606F;width:90%;margin:0 auto 10px auto;padding-bottom:5px;}.con-action li {}.con-action .con-left {}.con-action .con-right {text-align: right;clear: both;}.con-action input.input {margin-bottom:5px;}.con-action a {color:#3E606F;font-size:14px;display:block;}.con-action a:hover {text-decoration:underline;}.con-more {padding-top:5px;cursor:pointer;}.con-more:hover {background:#96c8f3;}.con-action .con-add {width:100%;}.con-action .con-add span {font-size:22px;}.con-action .con-add i {margin-right:5px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN #main-mid .status .table").delegate("tr td .con-action .con-right button.edit", "click", function() {
				return cdID = $(this).attr("data-cdID"), del_tr = $(this).closest("ul"), del_tr.css("opacity", "0.3"), title_input = del_tr.find(".con-left input.title_" + cdID), title = title_input.val(), maso_input = del_tr.find(".con-left input.maso_" + cdID), maso = maso_input.val(), "" != title && "" != maso && $.ajax({
					async: true,
					data: "cdID=" + cdID + "&title=" + title + "&maso=" + maso,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
					success: function(t) {
						title_input.val(t), del_tr.css("opacity", "1")
					}
				}), !1
			}), $("#MAIN #main-mid .status .table").delegate("tr td .con-action .con-right button.kill", "click", function() {
				return cdID = $(this).attr("data-cdID"), del_tr = $(this).closest("ul"), confirm("Bạn có chắc chắn xóa chuyên đề này?") && $.ajax({
					async: true,
					data: "cdID0=" + cdID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
					success: function(t) {
						del_tr.addClass("con-more"), del_tr.css("opacity", "0.5"), del_tr.html("<li class='con-add undo' data-cdID='" + cdID + "'><span><i class='fa fa-undo'></i>Hoàn tác</span></li>")
					}
				}), !1
			}), $("#MAIN #main-mid .status .table").delegate("tr td .con-more li.undo", "click", function() {
				return cdID = $(this).attr("data-cdID"), del_tr = $(this).closest("ul"), $.ajax({
					async: true,
					data: "cdID1=" + cdID,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
					success: function(t) {
						obj = jQuery.parseJSON(t), del_tr.removeClass("con-more"), del_tr.css("opacity", "1"), del_tr.html("<li class='con-left'><input class='maso_" + cdID + " input' type='text' value='" + obj.maso + "' style='display:inline-block;width:15%;' /><input class='title_" + cdID + " input' type='text' value='" + obj.title + "' style='display:inline-block;width:60%;' /></li><li class='con-right'><input type='submit' class='submit edit' data-cdID='" + cdID + "' value='Sửa' /><input type='submit' class='submit delete' data-cdID='" + cdID + "' value='Xóa' /></li>")
					}
				}), !1
			}), $(".add-dad").click(function() {
				$("#input-add").val(""), $("#maso-add").val(""), $("#popup-add").fadeIn("fast"), $("#BODY").css("opacity", "0.3")
			}), $(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast"), $("#BODY").css("opacity", "1")
			}), $("#popup-ok").click(function() {
				title = $("#input-add").val(), maso = $("#maso-add").val(), dad = 0, lmID = <?php echo $lmID; ?>, "" != title && $.isNumeric(dad) && $.ajax({
					async: true,
					data: "maso0=" + maso + "&title0=" + title + "&dad=" + dad + "&lmID=" + lmID + "&monID=" + <?php echo $monID; ?>,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
					success: function(t) {
						$("#popup-add").fadeOut("fast"), $("#BODY").css("opacity", "1"), location.reload()
					}
				})
			}), $("#MAIN #main-mid .status .table tr td .con-more li.new").click(function() {
				del_tr = $(this).closest("td"), dad = $(this).attr("data-dad"), lmID = <?php echo $lmID; ?>, $.ajax({
					async: true,
					data: "lmIDnew=" + lmID + "&dad0=" + dad,
					type: "post",
					url: "http://localhost/www/TDUONG/thaygiao/xuly-chuyende/",
					success: function(t) {
						location.reload();
					}
				})
			})
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm chuyên đề cha</p>
            <div style="width:90%;margin:15px auto 15px auto;">
           		<input id="maso-add" class="input" autocomplete="off" placeholder="Mã chuyên đề" />
            	<input id="input-add" class="input" autocomplete="off" placeholder="Tiêu đề chuyên đề" style="margin-top:10px;" />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>Các chuyên đề <span style="font-weight:600">môn <?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                    	<td class="hidden"><input type="submit" class="submit add-dad" value='Thêm chuyên đề cha' /></td>
                                        <td></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th class="hidden" style="width:40%;"><span>Chuyên đề cha</span></th>
                                        <th style="width:60%;"><span>Chuyên đề con</span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_chuyende_dad($lmID);
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
											echo"<td class='hidden'>
												<ul class='con-action'>
													<li class='con-left' style='width:100%;'>	
														<input class='maso_$data[ID_CD] input' type='text' value='$data[maso]' style='float:left;width:20%;' />
														<input class='title_$data[ID_CD] input' type='text' value='$data[title]' style='float:right;width:60%;' />
													</li>
													<li class='con-right' style='width:100%;margin-top:5px;'>
														<button type='submit' class='submit edit' data-cdID='$data[ID_CD]'>Sửa</button>";
													if(count_chuyende_con($data["ID_CD"])==0) {
														echo"<button type='submit' class='submit kill' data-cdID='$data[ID_CD]'>Xóa</button>";
													}
													echo"</li>
												</ul>
											</td>
											<td>";
											$result1=get_chuyende_con($data["ID_CD"]);
											while($data1=mysqli_fetch_assoc($result1)) {
												if($data1["del"]==0) {
													echo"<ul class='con-action con-more' style='opacity:0.5'>
														<li class='con-add undo' data-cdID='$data1[ID_CD]'><span><i class='fa fa-undo'></i>Hoàn tác</span></li>
													</ul>";
												} else {
													echo"<ul class='con-action'>
														<li class='con-left'>	
															<input class='maso_$data1[ID_CD] input' type='text' value='$data1[maso]' style='float:left;width:20%;' />
															<input class='title_$data1[ID_CD] input' type='text' value='$data1[title]' style='float:right;width:60%;' />
														</li>
														<li class='con-right'>
															<button type='submit' class='submit' onclick=\"location.href='http://localhost/www/TDUONG/thaygiao/tai-lieu-chuyen-de/$data1[ID_CD]/'\">Xem</button>
															<button type='submit' class='submit edit' data-cdID='$data1[ID_CD]'>Sửa</button>
															<button type='submit' class='submit kill' data-cdID='$data1[ID_CD]'>Xóa</button>
														</li>
													</ul>";
												}
											}
										?>
                                        	<ul class="con-action con-more con-new">
                                            	<li class="con-add new" data-dad="<?php echo $data["ID_CD"]; ?>"><span><i class="fa fa-plus"></i>Thêm</span></li>
                                            </ul>
                                        </td>
                                    </tr>
									<?php 
											$dem++;
										}
									?>
                                    <tr>
                                    	<td class="hidden"><input type="submit" class="submit add-dad" value='Thêm chuyên đề cha' /></td>
                                        <td></td>
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
<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");
	if(isset($_GET["mon"]) && is_numeric($_GET["mon"]) && isset($_GET["cd"]) && is_numeric($_GET["cd"])) {
		$monID=$_GET["mon"];
		$cdID=$_GET["cd"];
	} else {
		$monID=0;
		$cdID=0;
	}
	$result0=get_one_chuyende($cdID);
	$data0=mysqli_fetch_assoc($result0);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>VIDEO</title>
        
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
			$("#select-mon").change(function() {
				monID = $(this).val();
				$.ajax({
					async: true,
					data: "monID=" + monID,
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-mon/",
					success: function() {
						$.ajax({
							async: true,
							data: "monID3=" + monID,
							type: "post",
							url: "http://localhost/www/TDUONG/admin/xuly-chuyende/",
							success: function(result) {
								$("#select-cd").html(result);
							}
						});
					}
				});
			});
			
			$("#search-video").keyup(function() {
				text = $(this).val();
				if(text != '' && text != '%' && text != ' ' && text != '_') {
					$.ajax({
						async: true,
						data: "search=" + text,
						type: "get",
						url: "http://localhost/www/TDUONG/admin/xuly-search-video/",
						success: function(result) {
							$("#search-box > ul").html(result);
							$("#search-box").fadeIn("fast");
						}
					});
					return false;
				} else {
					$("#search-box").fadeOut("fast");
				}
			});
			
			$("#select-mon, #select-cd").change(function() {
				monID = $("#select-mon").val();
				if(monID==0) {
					$("#select-mon").addClass("new-change");
				} else {
					$("#select-mon").removeClass("new-change");
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
				cdID = $("#select-cd").val();
				if($.isNumeric(monID) && $.isNumeric(cdID) && monID!=0 && cdID!=0) {
					return true;
				} else {
					alert("Vui lòng nhập đầy đủ thông tin!");
					return false;
				}
			});
			
			$("#MAIN #main-mid .table tr td input.delete").click(function() {
				if(confirm("Bạn có chắc chắn không?")) {
					videoID = $(this).attr("data-videoID");
					del_tr = $(this).closest("tr");
					$.ajax({
						async: true,
						data: "videoID=" + videoID,
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-video/",
						success: function(result) {
							del_tr.fadeOut("fast");
						}
					});
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
					$mon=$cd=NULL;
					if(isset($_GET["xem"])) {
						if(isset($_GET["monID"])) {
							$mon=$_GET["monID"];
						}
						if(isset($_GET["cdID"])) {
							$cd=$_GET["cdID"];
						}
						if($mon && $cd) {
							header("location:http://localhost/www/TDUONG/admin/videos/$mon/$cd/");
							exit();
						}
					}
				?>
                
                <div id="main-mid">
                	<h2>VIDEOS</h2>
                	<div>
                    	<div class="status">
                        	<table class="table">
                            	<tr><td><input type="submit" class="submit" style="width:50%;font-size:1.375em;" onclick="location.href='http://localhost/www/TDUONG/admin/up-video/'" value="Up video" /></td></tr>
                            </table>
                        	<form action="http://localhost/www/TDUONG/admin/videos/" method="get">
                        		<table class="table" style="margin-top:25px;">
                                    <tr>
                                    	<td style="width:50%;"><span>Môn</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" id="select-mon" name="monID">
                                                <option value="0">Chọn môn</option>
                                            <?php
                                                $result=get_all_mon_admin();
                                                for($i=0;$i<count($result);$i++) {
                                                    echo"<option value='".$result[$i]["monID"]."' data-name='".$result[$i]["name"]."' ";if($monID==$result[$i]["monID"]){echo"selected='selected'";}echo">Môn ".$result[$i]["name"]."</option>";
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
                                            <?php
                                                $result=get_all_chuyende_all($monID);
                                                while($data=mysqli_fetch_assoc($result)) {
                                                    if($data["dad"]!=0) {
                                                        echo"<option value='$data[ID_CD]' ";if($cdID==$data["ID_CD"]){echo"selected='selected'";}echo">$data[name] - $data[maso] - $data[title]</option>";
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td colspan="2"><input class="submit" style="width:50%;font-size:1.375em;" name="xem" type="submit" id="xem" value="Xem" /></td>
                                    </tr>
                                </table>
                         	</form>
                            <?php
								if($monID!=0 && $cdID!=0) {
							?>
                            	<table class="table" style="margin-top:25px;">
                                	<tr style="background:#3E606F;">
                                        <th style="width:5%;"><span>Mã</span></th>
                                        <th style="width:35%;"><span>Mô tả</span></th>
                                        <th style="width:15%;"><span>Giá</span></th>
                                        <th style="width:25%;"><span>Chuyên đề</span></th>
                                        <th style="width:25%;"><span></span></th>
                                    </tr>
                                    <?php
										if(isset($_GET["begin"]) && is_numeric($_GET["begin"])) {
											$position=$_GET["begin"];
										} else {
											$position=0;
										}
										$dem=0;$display=30;
										$result=get_video_chuyende($position, $display, $cdID);
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
                                    	<td><span><?php echo $data["ID_VIDEO"];?></span></td>
                                        <td><span><?php echo $data["title"]; ?></span><br /><span class="dateup">(<?php echo format_dateup($data["dateup"]); ?>)</span></td>
                                        <td><span><?php if($data["price"]!=0){echo format_price($data["price"]);}else{echo"FREE";} ?></span></td>
                                        <td><span><?php echo $data0["name"]." - ".$data0["maso"]." - ".$data0["title"]; ?></span></td>
                                        <td>
                                            <input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/video/<?php echo $data["ID_VIDEO"];?>/'" value="Xem" />
                                            <input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/admin/sua-video/<?php echo $data["ID_VIDEO"];?>/'" value="Sửa" />
                                            <input type="submit" class="submit delete" data-videoID="<?php echo $data["ID_VIDEO"];?>" value="Xóa" />
                                      	</td>
                                    </tr>
									<?php 
											$dem++;
										}
									?>
                                </table>
                        </div>
                        <?php
							$result2=get_all_video_cd($cdID);
							$sum=mysqli_num_rows($result2);
							$sum_page=ceil($sum/$display);
							if($sum_page>1) {
								$current=($position/$display)+1;
						?>
                        <div class="page-number">
                        	<ul>
                            <?php
								if($current!=1) {
									$prev=$position-$display;
									echo"<li><a href='http://localhost/www/TDUONG/admin/videos/page-$prev/'><</a></li>";
								}
								for($page=1;$page<=$sum_page;$page++) {
									$begin=($page-1)*$display;
									if($page==$current) {
										echo"<li><a href='http://localhost/www/TDUONG/admin/videos/page-$begin/' style='font-weight:bold;text-decoration:underline;'>$page</a></li>";
									} else {
										echo"<li><a href='http://localhost/www/TDUONG/admin/videos/page-$begin/'>$page</a></li>";
									}
								}
								if($current!=$sum_page) {
									$next=$position+$display;
									echo"<li><a href='http://localhost/www/TDUONG/admin/videos/page-$next/'>></a></li>";
								}
							?>
                            </ul>
                        </div>
                        <?php
							}
						}
						?>
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
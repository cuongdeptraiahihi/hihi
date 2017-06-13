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
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>       
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status .table-action #search-box {width:60%;}
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
			
			$("#search-hs").click(function() {
				monID = $("#select-mon").val();
				if(monID != 0 && $.isNumeric(monID)) {
					
				} else {
					alert("Vui lòng chọn môn học!");
				}
			});
			
			$("#search-hs").keyup(function() {
				text = $(this).val();
				monID = $("#select-mon").val();
				if(text != '' && text != '%' && text != ' ' && text != '_' && monID != 0 && $.isNumeric(monID)) {
					$.ajax({
						async: true,
						data: "search_short=" + text + "&monID=" + monID,
						type: "get",
						url: "http://localhost/www/TDUONG/admin/xuly-search-hs/",
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
			
			$("#MAIN #main-mid .table").delegate("tr td.con-action > span i.fa-check-square-o", "click", function() {
				video = $(this);
				videoID = video.attr("data-videoID");
				hsID = video.attr("data-hsID");
				$.ajax({
					async: true,
					data: "videoID=" + videoID + "&hsID=" + hsID + "&action=off",
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
					success: function(result) {
						if(result) {
							video.removeClass("fa fa-check-square-o");
							video.addClass("fa fa-square-o");
						}
					}
				});
				return false;
			});
			
			$("#MAIN #main-mid .table").delegate("tr td.con-action > span i.fa-square-o", "click", function() {
				video = $(this);
				videoID = video.attr("data-videoID");
				hsID = video.attr("data-hsID");
				$.ajax({
					async: true,
					data: "videoID=" + videoID + "&hsID=" + hsID + "&action=on",
					type: "post",
					url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
					success: function(result) {
						if(result) {
							video.removeClass("fa fa-square-o");
							video.addClass("fa fa-check-square-o");
						}
					}
				});
				return false;
			});
			
			$("#MAIN #main-mid .table").delegate("tr th > span i.fa-square-o", "click", function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				video = $(this);
				hsID = video.attr("data-hsID");
				cdID = video.attr("data-cdID");
				setTimeout(function() {
					$.ajax({
						async: true,
						data: "hsID=" + hsID + "&cdID=" + cdID + "&actionAll=on",
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
						success: function(result) {
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
							if(result) {
								video.removeClass("fa fa-square-o");
								video.addClass("fa fa-check-square-o");
								$("#MAIN #main-mid .table tr td.con-action span i").attr("class","fa fa-check-square-o");
							}
						}
					});
				},1000);
				return false;
			});
			
			$("#MAIN #main-mid .table").delegate("tr th > span i.fa-check-square-o", "click", function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				video = $(this);
				hsID = video.attr("data-hsID");
				cdID = video.attr("data-cdID");
				setTimeout(function() {
					$.ajax({
						async: true,
						data: "hsID=" + hsID + "&cdID=" + cdID + "&actionAll=off",
						type: "post",
						url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
						success: function(result) {
							$("#popup-loading").fadeOut("fast");
							$("#BODY").css("opacity","1");
							if(result) {
								video.removeClass("fa fa-check-square-o");
								video.addClass("fa fa-square-o");
								$("#MAIN #main-mid .table tr td.con-action span i").attr("class","fa fa-square-o");
							}
						}
					});
				},1000);
				return false;
			});
			
			$("#search-box ul").delegate("li a", "click", function() {
				$("#popup-loading").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
				cdID = $("#select-cd").val();
				hsID = $(this).attr("data-hsID");
				maso = $(this).attr("data-cmt");
				$("#search-hs").val(maso);
				setTimeout(function() {
					$.ajax({
						async: true,
						data: "hsID0=" + hsID + "&cdID0=" + cdID,
						type: "get",
						url: "http://localhost/www/TDUONG/admin/xuly-unlock-video/",
						success: function(result) {
							$("#search-box").fadeOut("fast");
							$("#BODY").css("opacity","1");
							$("#MAIN #main-mid #video-unlock").html("<tr style='background:#3E606F;'><th style='width:15%;'><span>Mã video</span></th><th style='width:40%;'><span>Mô tả</span></th><th style='width:30%;'><span>Chuyên đề</span></th><th style='width:15%;'><span><i class='fa fa-square-o' data-hsID='"+ hsID +"' data-cdID='"+ cdID +"' style='cursor:pointer;'></i></span></th></tr>"+result);
							$("#popup-loading").fadeOut("fast");
						}
					});
				},500);
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
                	<h2>MỞ VIDEO CHO HỌC SINH</h2>
                	<div>
                    	<div class="status">
                        	<table class="table">
                            	<tr>
                                    	<td style="width:50%;"><span>Môn</span></td>
                                        <td style="width:50%;">
                                            <select class="input" style="height:auto;width:100%;" id="select-mon" name="monID">
                                                <option value="0">Chọn môn</option>
                                            <?php
                                                $result=get_all_mon_admin();
                                                for($i=0;$i<count($result);$i++) {
                                                    echo"<option value='".$result[$i]["monID"]."' data-name='".$result[$i]["name"]."' >Môn ".$result[$i]["name"]."</option>";
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                    	<td><span>Chuyên đề</span></td>
                                        <td>
                                            <select class="input" style="height:auto;width:100%;" id="select-cd" name="cdID">
                                                <option value="0">Chọn chuyên đề</option>
                                            </select>
                                        </td>
                                    </tr>
                                	<tr>
                                    	<td><span>Search học sinh</span></td>
                                        <td>
                                        	<input type="text" name="search-hs" placeholder="1991326, 1999652, ..." autocomplete="off" class="input" id="search-hs" />
                                            <nav id="search-box">
                                                <ul>
                                                </ul>
                                            </nav>
                                        </td>
                                    </tr>
                                    
                                </table>
                            	<table class="table" style="margin-top:25px;" id="video-unlock">
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
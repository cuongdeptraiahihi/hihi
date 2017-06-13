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
        
        <title>THỜI GIAN ĐỔI CA FREE</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/jquery-ui.css">
        
        <style>
            #FIX {display: none;}
			#MAIN > #main-mid {width:100%;}#MAIN #main-mid .status table tr td input.none {opacity:0.3;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function(){$("#MAIN #main-mid .status table tr td input.input").datepicker({dateFormat:"yy-mm-dd"}),$("#MAIN #main-mid .status table").delegate("tr td span i.fa-toggle-off","click",function(){$(this).attr("class","fa fa-toggle-on"),$(this).closest("tr").find("td input.input").removeClass("none").removeAttr("disabled"),$(this).closest("tr").find("td input.submit").fadeIn("fast")}),$("#MAIN #main-mid .status table").delegate("tr td span i.fa-toggle-on","click",function(){$("#popup-loading").fadeIn("fast"),$("#BODY").css("opacity","0.3"),lmID=$(this).attr("data-lmID"),$.ajax({async: true,data:"lmID="+lmID,type:"post",url:"http://localhost/www/TDUONG/thaygiao/xuly-catime/",success:function(t){location.reload()}})}),$("#MAIN #main-mid .status table").delegate("tr td input.submit","click",function(){$("#popup-loading").fadeIn("fast"),$("#BODY").css("opacity","0.3"),start=$(this).closest("tr").find("td input.start").val(),end=$(this).closest("tr").find("td input.end").val(),lmID=$(this).attr("data-lmID"),$.ajax({async: true,data:"lmID0="+lmID+"&start="+start+"&end="+end,type:"post",url:"http://localhost/www/TDUONG/thaygiao/xuly-catime/",success:function(t){$("#popup-loading").fadeOut("fast"),$("#BODY").css("opacity","1")}})})});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>Thời gian đổi ca free</span></h2>
                	<div>
                    	<div class="status">	
                            <table class="table">
                            	<tr style="background:#3E606F;">
                                	<th style="width:20%;"><span>Môn</span></th>
                                    <th style="width:10%;"><span></span></th>
                                    <th style="width:25%;"><span>Bắt đầu</span></th>
                                    <th style="width:25%;"><span>Kết thúc</span></th>
                                    <td style="width:10%;"><span></span></td>
                                </tr>
                                <?php
                                    $query2="SELECT l.*,d.start,d.end FROM lop_mon AS l 
                                    LEFT JOIN doica_time AS d ON d.ID_LM=l.ID_LM ORDER BY l.ID_LM ASC";
                                    $result2=mysqli_query($db,$query2);
                                    while($data2=mysqli_fetch_assoc($result2)) {
                                        echo"<tr>
                                            <td><span>$data2[name]</span></td>";
                                            if(isset($data2["start"]) && isset($data2["end"])) {
                                                echo"<td><span><i class='fa fa-toggle-on' style='font-size:22px;' data-lmID='$data2[ID_LM]'></i></span></td>
                                                <td><input class='input start' type='text' value='$data2[start]' /></td>
                                                <td><input class='input end' type='text' value='$data2[end]' /></td>
                                                <td><input type='submit' class='submit' value='Lưu' data-lmID='$data2[ID_LM]' /></td>";
                                            } else {
                                                echo"<td><span><i class='fa fa-toggle-off' style='font-size:22px;' data-lmID='$data2[ID_LM]'></i></span></td>
                                                <td><input class='input none start' type='text' disabled='disabled' /></td>
                                                <td><input class='input none end' type='text' disabled='disabled' /></td>
                                                <td><input type='submit' class='submit' value='Lưu' style='display:none' data-lmID='$data2[ID_LM]' /></td>";
                                            }
                                        echo"</tr>";
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
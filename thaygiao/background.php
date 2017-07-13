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
        
        <title>BACKGROUND</title>
        
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
			#MAIN > #main-mid {width:100%;}.con-action {background:#ffffa5;width:70%;margin:0 auto 20px auto;padding-bottom:5px;}.con-action li {display:inline-table;}.con-action .con-left {width:68%;}.con-action .con-right {width:30%;}.con-action input.input {margin-bottom:5px;}.con-action a {color:#3E606F;font-size:14px;display:block;}.con-action a:hover {text-decoration:underline;}.con-more {padding-top:5px;cursor:pointer;}.con-more:hover {background:#96c8f3;}.con-action .con-add {width:100%;}.con-action .con-add span {font-size:22px;}.con-action .con-add i {margin-right:5px;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function(){$("#MAIN #main-mid .status .table").delegate("tr td input.chose","click",function(){return backID=$(this).attr("data-backID"),$.ajax({async: true,data:"backID0="+backID,type:"post",url:"http://localhost/www/TDUONG/thaygiao/xuly-background/",success:function(t){location.reload()}}),!1}),$("#MAIN #main-mid .status .table").delegate("tr td input.delete","click",function(){return confirm("Bạn có chắc chắn xóa ảnh này không?")?(backID=$(this).attr("data-backID"),$.ajax({async: true,data:"backID="+backID,type:"post",url:"http://localhost/www/TDUONG/thaygiao/xuly-background/",success:function(t){location.reload()}}),!1):void 0})});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>THAY ĐỔI BACKGROUND</h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                        <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-tong-quan/<?php echo get_hs_id_rand($monID); ?>/1/'" value="Test" /></td>
                                    	<td></td>
                                        <td><input type="submit" class="submit" onclick="location.href='http://localhost/www/TDUONG/thaygiao/add-background/'" value="Thêm ảnh" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>STT</span></th>
                                        <th style="width:60%;"><span>Background</span></th>
                                        <th style="width:25%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_background();
										while($data=mysqli_fetch_assoc($result)) {
											if($data["note"]=="active") {
												echo"<tr style='background:#96c8f3;'>";
											} else {
												echo"<tr>";
											}
											echo"<td><span>".($dem+1)."</span></td>
											<td><img src='https://localhost/www/TDUONG/images/$data[content]' style='height:100px;width:auto;' /></td>
											<td>";
											if($data["note"]=="none") {
												echo"<input type='submit' class='submit chose' data-backID='$data[ID_O]' value='Chọn' />
												<input type='submit' class='submit delete' data-backID='$data[ID_O]' value='Xóa' />";
											} else {
												echo"<span>Đang hoạt động</span>";
											}
											echo"</td>
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
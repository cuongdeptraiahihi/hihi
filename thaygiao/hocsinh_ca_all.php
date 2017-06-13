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
    $lop_mon_name=get_lop_mon_name($lmID);
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>SET CA HÀNG LOẠT MÔN <?php echo mb_strtoupper($lop_mon_name,"UTF-8"); ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status #search-box {width:80%;}.check {width:20px;height:20px;margin-right:10px;}
			#MAIN > #main-mid > div .status .table-2 {display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status .table-2 tr td span i {font-size:1.5em;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:22px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}.mon-lich {display:none;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function(){$(".add-ok").click(function(){confirm("Bạn có chắc chắn muốn set ca hàng loạt?")&&($("#popup-loading").fadeIn("fast"),$("#BODY").css("opacity","0.3"),ma1=$("#add-ma1").val(),ma2=$("#add-ma2").val(),ajax_data="[",$("#info-ca tr").each(function(a,c){caID=$(c).find("td select option:selected").val(),0!=caID&&$.isNumeric(caID)&&(cum=$(c).find("td select option:selected").attr("data-cum"),ajax_data+='{"caID":"'+caID+'","cum":"'+cum+'"},')}),ajax_data+='{"ma1":"'+ma1+'","ma2":"'+ma2+'","lmID":"'+<?php echo $lmID; ?>+'"}',ajax_data+="]",""!=ajax_data?$.ajax({async: true,data:"data="+ajax_data,type:"post",url:"http://localhost/www/TDUONG/thaygiao/xuly-ca/",success:function(a){"ok"==a?$(".add-ok").val("OK").css("background","blue"):alert(a),$("#BODY").css("opacity","1"),$("#popup-loading").fadeOut("fast")}}):(alert("Vui lòng nhập dữ liệu đầy đủ và chính xác!"),$("#BODY").css("opacity","1"),$("#popup-loading").fadeOut("fast")))})});
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
                	<h2>Set ca hàng loạt môn <span style="font-weight:600"><?php echo $lop_mon_name; ?></span></h2>
                	<div>
                    	<div class="status">	
                            <table class="table table-2" style="width:39%;" id="info-hs">
                                <tr style="background:#3E606F;"><th colspan="2"><span>Cài đặt</span></th></tr>
                                <tr>
                                    <td style="width:35%;"><span>Mã số đầu</span></td>
                                    <td style="width:65%;"><input class="input" id="add-ma1" type="text" placeholder="99-0002" /></td>
                                </tr>
                                <tr>
                                    <td><span>Mã số cuối</span></td>
                                    <td><input class="input" id="add-ma2" type="text" placeholder="99-0017" /></td>
                                </tr>
                            </table>
                            <table class="table table-2" style="width:59%;" id="info-ca">
                                <tr style="background:#3E606F;"><th colspan="4"><span>Chọn ca theo cụm</span></th></tr>
                                <?php
                                    $monID=get_mon_of_lop($lmID);
                                    $result=get_all_cum($lmID,$monID);
                                    while($data=mysqli_fetch_assoc($result)) {
                                        echo"<tr>
                                            <td style='width:50%;'><span>$data[name]</span></td>
                                            <td><select class='input' style='height:auto;width:100%;' id='select-ca'>
												<option value='0'>Chọn ca</option>";
                                            $result2=get_cahoc_cum_lop($data["ID_CUM"],$lmID,$monID);
                                            while($data2=mysqli_fetch_assoc($result2)) {
                                                echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]'>".$thu_string[$data2["thu"]-1].", ca $data2[gio]</option>";
                                            }
                                            echo"</select></td>
                                        </tr>";
                                    }
									echo"<tr>
										<td><span>Kiểm tra cuối tuần</span></td>
										<td><select class='input' style='height:auto;width:100%;' id='select-ca'>
											<option value='0'>Chọn ca</option>";
										$result2=get_cakt_mon($monID);
										while($data2=mysqli_fetch_assoc($result2)) {
											echo"<option value='$data2[ID_CA]' data-cum='$data2[cum]'>".$thu_string[$data2["thu"]-1].", ca $data2[gio]</option>";
										}
										echo"</select></td>
									</tr>";
                                ?>
                            </table>
                            <table class="table" style="margin-top:25px;">
                                <tr>
                                    <td><input type="submit" style="width:50%;font-size:1.375em;" class="submit add-ok" value="Nhập" /></td>
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
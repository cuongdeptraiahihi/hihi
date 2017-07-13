<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
    $lop_mon_name=get_lop_mon_name($lmID);
    $monID=get_mon_of_lop($lmID);
    $mon_name=get_mon_name($monID);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>THỐNG KÊ BÀI KIỂM TRA</title>
        
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
			#MAIN > #main-mid {width:100%;}.input-2 {display:inline-block;width:30px;}#MAIN #main-mid .status .table tr td span.ketqua {font-weight:600;font-size:1.25em;}.see-kq {position:absolute;z-index:9;top:10px;font-weight:600;font-size:1.5em;}.win {color:red;}.lose {color:black;}.draw {color:yellow;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			
			$("#add_buoikt").click(function() {
				buoi = $("#buoikt_date").val();
				me = $(this);
				if(confirm("Bạn có chắc chắn muốn thêm buổi kiểm tra mới?")) {
					if(buoi!="") {
						$.ajax({
							async: true,
							data: "buoi=" + buoi + "&monID=" + <?php echo $monID; ?>,
							type: "post",
							url: "http://localhost/www/TDUONG/thaygiao/xuly-thongke/",
							success: function(result) {
								me.val("Đã thêm!").css("background","blue");
							}
						});
					} else {
						alert("Vui lòng chọn ngày kiểm tra!");
					}
				}
				return false;
			});
			
			$("#buoikt_date").datepicker({
				dateFormat: "yy-mm-dd"
			})
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

                <div id="main-mid">
                    <h2>NHẬP ĐIỂM KIỂM TRA</h2>
                    <div>
                        <div class="status">
                            <table class="table">
                                <tr>
                                    <td class="hidden"></td>
                                    <td colspan="2"><input type="text" class="input" id="buoikt_date" placeholder="Click vào để chọn buổi kiểm tra mới <?php echo $mon_name; ?>" /></td>
                                    <td colspan="2"><input type="submit" class="submit" id="add_buoikt" style="width:70%;font-size:1.375em;" value="Thêm buổi KT mới" /></td>
                                </tr>
                                <tr style="background:#3E606F;">
                                    <th class="hidden" style="width:10%;"><span>STT</span></th>
                                    <th style="width:10%;"><span></span></th>
                                    <th style="width:35%;"><span>Nội dung</span></th>
                                    <th style="width:15%;"><span></span></th>
                                    <th class="hidden" style="width:30%;"><span>Ghi chú</span></th>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>1</span></td>
                                    <td><span class="fa fa-pencil-square-o"></span></td>
                                    <td><span>Thêm điểm tự luân</span></td>
                                    <td><input class='submit' type='submit' value='Thêm' onclick="location.href='http://localhost/www/TDUONG/thaygiao/nhap-diem/'" /></td>
                                    <td class="hidden"><span>Nhập điểm kiểu cũ</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>2</span></td>
                                    <td><span class="fa fa-pencil-square"></span></td>
                                    <td><span>Thêm điểm trắc nghiệm</span></td>
                                    <td><input class='submit' type='submit' value='Thêm' onclick="location.href='http://localhost/www/TDUONG/thaygiao/nhap-diem2/'" /></td>
                                    <td class="hidden"><span>Nhập điểm kiểu trắc nghiệm mới</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>3</span></td>
                                    <td><span class="fa fa-pencil"></span></td>
                                    <td><span>Thêm điểm tổng</span></td>
                                    <td><input class='submit' type='submit' value='Thêm' onclick="location.href='http://localhost/www/TDUONG/thaygiao/nhap-diem4/'" /></td>
                                    <td class="hidden"><span>Chỉ nhập tổng điểm từ file excel</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>4</span></td>
                                    <td><span class="fa fa-level-up"></span></td>
                                    <td><span>Xét chuyển đề</span></td>
                                    <td><input class='submit' type='submit' value='Xét' onclick="location.href='http://localhost/www/TDUONG/thaygiao/xet-nhay-de/'" /></td>
                                    <td class="hidden"><span>Xét chuyển đề hàng tháng</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>5</span></td>
                                    <td><span class="fa fa-eye"></span></td>
                                    <td><span>Mới chuyển đề</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/moi-chuyen-de/<?php echo $lmID; ?>/G/'" /></td>
                                    <td class="hidden"><span>Danh sách các em học sinh mới chuyển đề</span></td>
                                </tr>
                                <tr>
                                    <td class="hidden"><span>6</span></td>
                                    <td><span class="fa fa-pie-chart"></span></td>
                                    <td><span>Thống kê câu sai nhiều</span></td>
                                    <td><input class='submit' type='submit' value='Xem' onclick="location.href='http://localhost/www/TDUONG/thaygiao/hoc-sinh-sai-nhieu/<?php echo $lmID; ?>/'" /></td>
                                    <td class="hidden"><span>Xem câu nào học sinh sai nhiều nhất</span></td>
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
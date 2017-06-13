<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	if(isset($_GET["hsID"]) && is_numeric($_GET["hsID"])) {
		$hsID=$_GET["hsID"];
	} else {
	    $hsID=0;
    }
    $result2=get_hs_short_detail2($hsID);
    $data2=mysqli_fetch_assoc($result2);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>QUẢN LÝ TIỀN THU VÀO</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->

        <?php
        if($_SESSION["mobile"]==1) {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/mbocuc.css'>";
        } else {
            echo"<link rel='stylesheet' type='text/css' href='http://localhost/www/TDUONG/thaygiao/css/bocuc.css'>";
        }
        ?>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="../	css/font-awesome-4.5.0/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#refresh {cursor:pointer;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit","click",function() {
                del_tr = $(this).closest("tr");
                del_tr.css("opacity","0.3");
			    idVAO = $(this).attr("data-idVAO");
                note = del_tr.find("td input.note_td").val();
                price = del_tr.find("td input.price_td").val();
                if($.isNumeric(idVAO) && idVAO!=0 && note!="" && price!="") {
                    $.ajax({
                        async: true,
                        data: "idVAO=" + idVAO + "&note=" + note + "&price=" + price,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-taikhoan/",
                        success: function (result) {
                            del_tr.css("opacity","1");
                        }
                    });
                } else {
                    alert("Lỗi dữ liệu!");
                    del_tr.css("opacity","1");
                }
			});

            $("#MAIN #main-mid .status .table").delegate("tr td input.edit2","click",function() {
                del_tr = $(this).closest("tr");
                del_tr.css("opacity","0.3");
                idRA = $(this).attr("data-idRA");
                note = del_tr.find("td input.note_td").val();
                price = del_tr.find("td input.price_td").val();
                if($.isNumeric(idRA) && idRA!=0 && note!="" && price!="") {
                    $.ajax({
                        async: true,
                        data: "idRA=" + idRA + "&note=" + note + "&price=" + price,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-taikhoan/",
                        success: function (result) {
                            del_tr.css("opacity","1");
                        }
                    });
                } else {
                    alert("Lỗi dữ liệu!");
                    del_tr.css("opacity","1");
                }
            });
			
			$(".refresh").click(function() {
				location.reload();
			});

            $("#MAIN #main-mid .status .table tr td input.delete").click(function() {
                idVAO = $(this).attr("data-idVAO");
                me = $(this).closest("tr");
                if($.isNumeric(idVAO) && idVAO!=0 && confirm("Bạn có chắc chắn muốn xóa? Hành động không thể hoàn tác!")) {
                    $.ajax({
                        async: true,
                        data: "idVAO2=" + idVAO,
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-taikhoan/",
                        success: function(result) {
                            me.fadeOut("fast");
                            $(".refresh").html("Cập nhật");
                        }
                    });
                }
            });
			
			$("#MAIN #main-mid .status .table tr td input.delete2").click(function() {
				idRA = $(this).attr("data-idRA");
				me = $(this).closest("tr");
				if($.isNumeric(idRA) && idRA!=0 && confirm("Bạn có chắc chắn muốn xóa? Hành động không thể hoàn tác!")) {
					$.ajax({
						async: true,
						data: "idRA2=" + idRA,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-taikhoan/",
						success: function(result) {
							me.fadeOut("fast");
							$(".refresh").html("Cập nhật");
						}
					});
				}
			});

            $("#MAIN #main-mid .status .table tr td input.add").click(function() {
                del_tr = $(this).closest("tr");
                del_tr.css("opacity","0.3");
                note = del_tr.find("td input.note_td").val();
                price = del_tr.find("td input.price_td").val();
                if(note != "" && price != "") {
                    $.ajax({
                        async: true,
                        data: "hsID2=" + <?php echo $hsID; ?> + "&note2=" + note + "&price2=" + price + "&action=phat",
                        type: "post",
                        url: "http://localhost/www/TDUONG/thaygiao/xuly-taikhoan/",
                        success: function (result) {
                            del_tr.css("opacity","1");
                            location.reload();
                        }
                    });
                } else {
                    del_tr.css("opacity","1");
                    alert("Dữ liệu không chính xác hoặc Số tiền là bội số của 10.000đ");
                }
            });
		});
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
                
                <div id="main-mid">
                	<h2>QUẢN LÝ TIỀN THU VÀO</h2>
                	<div>
                    	<div class="status">
                            	<table class="table">
                                    <?php
                                    if($hsID != 0) {
                                        echo"<tr>
                                                <td><span>".date("d/m")."</span></td>
                                                <td><span>$data2[cmt]</span></td>
                                                <td><input type='text' class='input note_td' placeholder='Nội dung phạt' /></td>
                                                <td><input type='text' class='input price_td' placeholder='Số tiền dạng 10.000đ, 30.000đ' style='text-align:center;' /></td>
                                                <td><input type='submit' class='submit add' value='Thêm' /></td>
                                            </tr>";
                                    }
                                    ?>
                                	<tr style="background:#3E606F;">
                                    	<th style="width:15%;"><span>Thời gian</span></th>
                                        <th style="width:15%;"><span>CMT</span></th>
                                        <th style="width:35%;"><span>Nội dung</span></th>
                                        <th style="width:15%;"><span>Số tiền</span></th>
                                        <th style="width:15%;"><span></span></th>
                                    </tr>
                                    <tr><td colspan="5"><span>Đã nạp</span></td></tr>
                                    <?php
										$total1=0;
                                        $result=get_nap_hocsinh($hsID);
										$dem=0;
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD;'>";
											} else {
												echo"<tr>";
											}
											echo"<td><span>".format_date($data["date"])." ($data[note2])</span></td>
												<td><span>$data2[cmt]</span></td>
												<td><input type='text' class='input note_td' value='$data[note]' /></td>
												<td><input type='text' class='input price_td' value='".format_price($data["price"])."' style='text-align:center;' /></td>
												<td><input type='submit' class='submit edit2' data-idRA='$data[ID_RA]' value='Sửa' /><input type='submit' class='submit delete2' data-idRA='$data[ID_RA]' value='Xóa' /></td>
											</tr>";
											$total1=$total1+$data["price"];
											$dem++;
										}
										if($dem==0) {
											echo"<tr><td colspan='5'><span>Không có dữ liệu</span></td></tr>";
										}
									?>
                                    <tr style="background:#3E606F;">
                                    	<th colspan="3"><span>Tổng cộng</span></th>
                                        <th><span class="refresh"><?php echo format_price($total1);?></span></th>
                                        <th><span></span></th>
                                    </tr>
                                    <tr><td colspan="5"><span>Cần thu</span></td></tr>
                                    <?php
                                    $total1=0;
                                    $result=get_phat_hs_con2($hsID);
                                    $dem=0;
                                    while($data=mysqli_fetch_assoc($result)) {
                                        if($dem%2!=0) {
                                            echo"<tr style='background:#D1DBBD;'>";
                                        } else {
                                            echo"<tr>";
                                        }
                                        echo"<td><span>".format_date($data["date"])."</span></td>
												<td><span>$data2[cmt]</span></td>
												<td><input type='text' class='input note_td' value='$data[note]' /></td>
												<td><input type='text' class='input price_td' value='".format_price($data["price"])."' style='text-align:center;' /></td>
												<td><input type='submit' class='submit edit' data-idVAO='$data[ID_VAO]' value='Sửa' /><input type='submit' class='submit delete' data-idVAO='$data[ID_VAO]' value='Xóa' /></td>
											</tr>";
                                        $total1=$total1+$data["price"];
                                        $dem++;
                                    }
                                    if($dem==0) {
                                        echo"<tr><td colspan='5'><span>Không có dữ liệu</span></td></tr>";
                                    }
                                    ?>
                                    <tr style="background:#3E606F;">
                                        <th colspan="3"><span>Tổng cộng</span></th>
                                        <th><span class="refresh"><?php echo format_price($total1);?></span></th>
                                        <th><span></span></th>
                                    </tr>
                                </table>
                            <div class="clear"></div>
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
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
        
        <title>TRỢ GIẢNG</title>
        
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
			#MAIN > #main-mid {width:100%;}.input {text-align:center;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script src="http://localhost/www/TDUONG/thaygiao/js/jquery-ui.js"></script>
        <script>
		$(document).ready(function() {
			$("input.edit_kg").datepicker({ dateFormat: 'dd/mm/yy' });
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.edit_ok", "click", function() {
				oID = $(this).attr("data-oID");
				del_tr = $(this).closest("tr");
				del_tr.css("opacity","0.3");
				name = del_tr.find("td input.edit_name").val();
                phone = del_tr.find("td input.edit_phone").val();
                date_in = del_tr.find("td input.edit_date").val();
                mota = del_tr.find("td input.edit_mota").val();
                price = del_tr.find("td input.edit_price").val();
				code = del_tr.find("td input.edit_code").val();
                ajax_data="[";
                sum = 0;
                del_tr.find("td.who_pay input.add_pay").each(function(index,element) {
                    is_pay = 0;
                    id = $(element).val();
                    phan = 0;
                    if($(element).is(":checked")) {
                        is_pay = 1;
                        phan = $(element).closest("td").find("input.add_phan_"+id).val();
                        sum = parseInt(sum) + parseInt(phan);
                    }
                    ajax_data+='{"idA":"'+id+'","is_pay":"'+is_pay+'","phan":"'+phan+'"},';
                });
                ajax_data+='{"oID":"'+oID+'","name":"'+name+'","phone":"'+phone+'","date_in":"'+date_in+'","mota":"'+mota+'","price":"'+price+'","code":"'+code+'"}';
                ajax_data+="]";
				if(name!="" && $.isNumeric(price) && date_in!="" && $.isNumeric(oID) && oID!=0 && ajax_data!="" && sum>=0 && sum<=100) {
					$.ajax({
						async: true,
						data: "data=" + ajax_data,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
						success: function(result) {
							if(result=="ok") {
								del_tr.css("opacity","1");
								location.reload();
							} else {
								alert("Đã tồn tại mã này!");
								del_tr.css("opacity","1");
							}
						}
					});
				} else {
                    alert("Dữ liệu không chính xác!");
                    del_tr.css("opacity","1");
                }
			});
			
			$("#MAIN #main-mid .status .table").delegate("tr td input.delete", "click", function() {
				oID = $(this).attr("data-oID");
				del_tr = $(this).closest("tr");
				if(confirm("Bạn có chắc chắn muốn xóa lớp không?") && $.isNumeric(oID) && oID!=0) {
					$.ajax({
						async: true,
						data: "oID0=" + oID,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
						success: function(result) {
							location.reload();
						}
					});
				}
			});
			
			$("#add_khoa").click(function() {
				$("#name-add, #code-add").val("");
				$("#popup-add").fadeIn("fast");
				$("#BODY").css("opacity","0.3");
			});
			
			$(".popup-close").click(function() {
				$("#popup-add").fadeOut("fast");
				$("#BODY").css("opacity","1");
			});
			
			$("#popup-ok").click(function() {
				name = $("#name-add").val();
				code = $("#code-add").val();
				if(name!="" && code!="" && $.isNumeric(code)) {
					$.ajax({
						async: true,
						data: "name0=" + name + "&code0=" + code,
						type: "post",
						url: "http://localhost/www/TDUONG/thaygiao/xuly-trogiang/",
						success: function(result) {
							$("#popup-add").fadeOut("fast");
							if(result=="ok") {
								$("#BODY").css("opacity","1");
								location.reload();
							} else {
								alert("Đã tồn tại mã này!");
								$("#BODY").css("opacity","1");
							}
						}
					});
				}
			});
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-add" style="width:40%;left:30%;">
        	<div class="popup-close"><i class="fa fa-close"></i></div>
      		<p style="text-transform:uppercase;">Thêm trợ giảng</p>
            <div style="width:90%;margin:15px auto 15px auto;">
            <input id="name-add" class="input" autocomplete="off" placeholder="Tên trợ giảng" type="text" />
            <input id="code-add" class="input" placeholder="Mã trợ giảng (chữ số)" type="password" style="margin-top:10px;" />
            </div>
            <div><button class="submit" id="popup-ok">Thêm</button></div>
      	</div>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
                <?php
                    if(isset($_POST["ok-fix"])) {
                        $query="SELECT o.ID_O,i.ID_I FROM options AS o LEFT JOIN info_trogiang AS i ON i.ID_O=o.ID_O WHERE o.type='tro-giang-code' ORDER BY o.ID_O ASC";
                        $result=mysqli_query($db,$query);
                        while($data=mysqli_fetch_assoc($result)) {
                            if(!isset($data["ID_I"])) {
                                $query2 = "INSERT INTO info_trogiang(mota,price,phone,date_in,ID_O)
                                                            value('','','',now(),'$data[ID_O]')";
                                mysqli_query($db, $query2);
                            }
                        }
                        header("location:http://localhost/www/TDUONG/thaygiao/tro-giang/");
                        exit();
                    }
                ?>
                
                <div id="main-mid">
                	<h2>DANH SÁCH CÁC TRỢ GIẢNG</span></h2>
                	<div>
                    	<div class="status">	
                            	<table class="table">
                                	<tr>
                                    	<td colspan="6"><span></span></td>
                                        <td>
                                            <form action="http://localhost/www/TDUONG/thaygiao/tro-giang/" method="post">
                                                <input type="submit" class="submit" name="ok-fix" value="Sửa chữa" />
                                            </form>
                                        </td>
                                        <td><input type="submit" class="submit" id="add_khoa" value="Thêm trợ giảng mới" /></td>
                                    </tr>
                                	<tr style="background:#3E606F;">
                                        <th style="width:10%;"><span>Tên</span></th>
                                        <th style="width:10%;"><span>SĐT</span></th>
                                        <th style="width:10%;"><span>Ngày vào</span></th>
                                        <th><span>Mô tả</span></th>
                                        <th style="width:10%;"><span>Lương</span></th>
                                        <th style="width:10%;"><span>Mã mới</span></th>
                                        <th style="width:20%"><span>Người trả</span></th>
                                        <th style="width:10%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$query="SELECT o.ID_O,o.note,o.note2,i.* FROM options AS o INNER JOIN info_trogiang AS i ON i.ID_O=o.ID_O WHERE o.type='tro-giang-code' ORDER BY o.ID_O DESC";
										$result=mysqli_query($db,$query);
										while($data=mysqli_fetch_assoc($result)) {

										    if($data["note2"]==1) {
										        $style="";
                                            } else {
                                                $style="opacity:0.3";
                                            }

											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD;$style'>";
											} else {
												echo"<tr style='$style'>";
											}
											echo"<td><input type='text' class='input edit_name' value='$data[note]' /></td>
												<td><input type='text' class='input edit_phone' value='$data[phone]' /></td>
												<td><input type='text' class='input edit_date' value='".format_dateup($data["date_in"])."' /></td>
												<td><input type='text' class='input edit_mota' value='$data[mota]' placeholder='Mô tả công việc' /></td>
												<td><input type='number' min='0' class='input edit_price' value='$data[price]' placeholder='1000000' /></td>
												<td><input type='password' class='input edit_code' placeholder='(Nếu thay)' /></td>
												<td class='who_pay'>";
                                                $result2=get_who_pay($data["ID_O"]);
                                                while($data2=mysqli_fetch_assoc($result2)) {
                                                    if(isset($data2["ID_P"])) {
                                                        echo "<div class='clear'>
                                                            <span style='margin:10px 5px 0 0;width:40%;float:left;'>$data2[fullname]</span>
                                                            <input type='checkbox' checked='checked' class='check add_pay' style='width:15%;float:left;margin-top:10px;' value='$data2[ID]' />
                                                            <input type='number' min='0' max='100' class='input add_phan_$data2[ID]' style='width:20%;float:left;' value='$data2[phan]' />
                                                        </div>";
                                                    } else {
                                                        echo "<div class='clear'>
                                                            <span style='margin:10px 5px 0 0;width:40%;float:left;'>$data2[fullname]</span>
                                                            <input type='checkbox' class='check add_pay' value='$data2[ID]' style='width:15%;float:left;margin-top:10px;' />
                                                            <input type='number' min='0' max='100' class='input add_phan_$data2[ID]' value='$data2[phan]' style='width:20%;float:left;' />
                                                        </div>";
                                                    }
                                                }
                                                echo"</td>
												<td>
													<input type='submit' class='submit edit_ok' data-oID='$data[ID_O]' value='Sửa' />";
                                                if($data["note2"]==1) {
													echo"<input type='submit' class='submit delete' data-oID='$data[ID_O]' value='Xóa' />";
												} else {
                                                    echo"<input type='submit' class='submit delete' data-oID='$data[ID_O]' value='Làm' />";
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
<?php
	ob_start();
	session_start();
	require_once("../model/open_db.php");
    require_once("../model/model.php");
    require_once("access_admin.php");
    $lmID=$_SESSION["lmID"];
	$monID=$_SESSION["mon"];
	if(is_numeric($_GET["ca"])) {
		$caID=$_GET["ca"];
	} else {
		$caID=0;
	}
	$result0=get_info_ca($caID);
	$data0=mysqli_fetch_assoc($result0);
	$thu_string=array("Chủ Nhật","Thứ Hai","Thứ Ba","Thứ Tư","Thứ Năm","Thứ Sáu","Thứ Bảy");
    $mon_lop_name=get_lop_mon_name($lmID);
	$title=$thu_string[$data0["thu"]-1].", ca ".$data0["gio"].", môn $mon_lop_name";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title><?php echo $title; ?></title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/admin/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/admin/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/admin/css/font-awesome.min.css">
        
        <style>
			
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
            $(document).ready(function() {
                $("#MAIN #main-mid .table tr td a i.fa-trash").click(function() {
                    confirm("Bạn có chắc chắn hủy đăng ký tạm của học sinh này? Lịch học của học sinh này sẽ được đưa về ban đầu!") && (hsID = $(this).attr("data-hsID"), del_tr = $(this).closest("tr"), $.ajax({
                        async: true,
                        data: "hsID=" + hsID + "&caID2=" + <?php echo $caID; ?>,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-ca/",
                        success: function(t) {
                            del_tr.fadeOut("fast")
                        }
                    }))
                }), $("#count-tam").html("Tạm: " + $("#echo-tam").val())
            });
		</script>
       
	</head>

    <body>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	<div id="main-left">
                    <div>
                    	<h3>Thao tác</h3>
                        <ul>
                        	<li class="action"><a href="http://localhost/www/TDUONG/admin/ca-co-dinh/<?php echo $caID;?>/"><i class="fa fa-angle-double-right"></i>Xem danh sách cố định</a></li>
                            <li><div class='div-color' style="background:#ffffa5;"></div><p>Đăng ký tạm</p></li>
                        </ul>
                    </div>
                </div>
                <div id="main-mid">
                	<h2><?php echo $title;?> <span style="font-weight:600;">(HIỆN TẠI)</span></h2>
                	<div>
                    	<div class="status">
                        	<form>
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>STT</span></th>
                                        <th style="width:35%;"><span>Họ và Tên</span></th>
                                        <th style="width:15%;"><span>Mã số</span></th>
                                        <th style="width:20%;"><span>Ngày sinh</span></th>
                                        <th style="width:15%;"><span id="count-tam">Tạm: 0</span></th>
                                    </tr>
                                    <?php
										$dem=0;$tam=0;
										$result=get_all_hs_ca_hientai($caID);
										while($data=mysqli_fetch_assoc($result)) {
											$check=check_hs_in_ca_codinh($caID,$data["ID_HS"]);
											if(!$check) {
												echo"<tr style='background:#ffffa5'>";
												$tam++;
											} else {
												if($dem%2!=0) {
													echo"<tr style='background:#D1DBBD'>";
												} else {
													echo"<tr>";
												}
											}
									?> 
                                    	<td><span><?php echo ($dem+1);?></span></td>
                                        <td><span><?php echo $data["fullname"]; ?></span></td>
                                        <td><span><?php echo $data["cmt"]; ?></span></td>
                                        <td><span><?php echo format_dateup($data["birth"]);?></span></td>
                                        <td>
                                            <?php
												if(!$check) {
                                            		echo"<a href='javascript:void(0)'><i class='fa fa-trash' data-hsID='$data[ID_HS]' title='Xóa khỏi ca này'></i></a>";
												}
											?>
                                      	</td>
                                    </tr>
									<?php 
											$dem++;
										}
									?>
                                </table>
                                <input type="hidden" id="echo-tam" value="<?php echo $tam;?>"/>
                            </form>
                        </div>
                    </div>
               	</div>
                
                <div id="main-right">
                	<div>
                        <h3>Các ca học (hiện tại)</h3>
                        <ul>
                        <?php
                            if($lmID==0) {
                                $result1 = get_cum_kt($monID);
                            } else {
                                $result1 = get_all_cum($lmID,$monID);
                            }
							while($data1=mysqli_fetch_assoc($result1)) {
								echo"<li><a href='javascript:void(0)'># Cụm $data1[name]</a>
									<ul class='sub-menu'>";
										$result4=get_cahoc_cum_lop($data1["ID_CUM"],$lmID,$monID);
										while($data4=mysqli_fetch_assoc($result4)) {
											echo"<ol><a href='http://localhost/www/TDUONG/admin/ca-hien-tai/$data4[ID_CA]/' ";if($data4["ID_CA"]==$caID){echo"style='font-weight:600'";}echo"><i class='fa fa-caret-right'></i>".$thu_string[$data4["thu"]-1].", $data4[gio]</a></ol>";
										}
									echo"</ul>
								</li>";
							}
						?>
                        </ul>
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
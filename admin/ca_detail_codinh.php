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
                $("#MAIN #main-mid .table tr td").delegate("a i", "click", function() {
                    "fa fa-check-square-o" == $(this).attr("class") ? $(this).attr("class", "fa fa-square-o") : $(this).attr("class", "fa fa-check-square-o")
                }), $("#search-ok").click(function() {
                    return caID = $("#ca-change").val(), hsID = [], i = 0, $("#MAIN #main-mid .table tr").each(function(a, t) {
                        $(this).find("td a i.fa-check-square-o").attr("data-hsID") && (hsID[i] = $(this).find("td a i.fa-check-square-o").attr("data-hsID"), i++)
                    }), hsID_array = JSON.stringify(hsID), 0 != caID && hsID.length > 0 ? $.ajax({
                        async: true,
                        data: "hsID_array=" + hsID_array + "&caID1=" + caID,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-ca/",
                        success: function(a) {
                            location.reload()
                        }
                    }) : alert("Vui lòng chọn ca chuyển đến và học sinh cần chuyển!"), !1
                }), $("#MAIN #main-mid .table tr td a i.fa-trash").click(function() {
                    confirm("Bạn có chắc chắn xóa học sinh này khỏi ca này, cả cố định và hiện tại?") && (hsID = $(this).attr("data-hsID"), del_tr = $(this).closest("tr"), $.ajax({
                        async: true,
                        data: "hsID2=" + hsID + "&caID4=" + <?php echo $caID; ?>,
                        type: "post",
                        url: "http://localhost/www/TDUONG/admin/xuly-ca/",
                        success: function(t) {
                            del_tr.fadeOut("fast")
                        }
                    }))
                })
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
                        	<li class="action"><a href="http://localhost/www/TDUONG/admin/ca-hien-tai/<?php echo $caID;?>/"><i class="fa fa-angle-double-right"></i>Xem danh sách hiện tại</a></li>
                        </ul>
                    </div>
                </div>
                <div id="main-mid">
                	<h2><?php echo $title;?> <span style="font-weight:600;">(CỐ ĐỊNH)</span></h2>
                	<div>
                    	<div class="status">
                        	<div class="table-action">
                            	<select class="input" style="height:auto;width:55%;" id="ca-change">
                                	<option value="0">Chọn ca chuyển đến</option>
                                <?php
                                	$result5=get_all_cahoc_lop($lmID,$monID);
									while($data5=mysqli_fetch_assoc($result5)) {
										if($data5["ID_CA"]!=$caID && $data5["cum"]==$data0["cum"]) {
											echo"<option value='$data5[ID_CA]' data-cum='$data5[cum]'>".$thu_string[$data5["thu"]-1].", $data5[gio]</option>";
										}
									}
								?>
                                </select>
                                <button name="search-ok" class="submit" id="search-ok">Chuyển ca</button>
                            </div>
                        	<form>
                            	<table class="table">
                                	<tr style="background:#3E606F;">
                                        <th style="width:15%;"><span>STT</span></th>
                                        <th style="width:35%;"><span>Họ và Tên</span></th>
                                        <th style="width:15%;"><span>Mã số</span></th>
                                        <th style="width:20%;"><span>Ngày sinh</span></th>
                                        <th style="width:15%;"><span></span></th>
                                    </tr>
                                    <?php
										$dem=0;
										$result=get_all_hs_ca_codinh($caID);
										while($data=mysqli_fetch_assoc($result)) {
											if($dem%2!=0) {
												echo"<tr style='background:#D1DBBD'>";
											} else {
												echo"<tr>";
											}
									?> 
                                    	<td><span><?php echo ($dem+1);?></span></td>
                                        <td><span><?php echo $data["fullname"]; ?></span></td>
                                        <td><span><?php echo $data["cmt"]; ?></span></td>
                                        <td><span><?php echo format_dateup($data["birth"]);?></span></td>
                                        <td>
                                            <a href="javascript:void(0)"><i class="fa fa-square-o" data-hsID="<?php echo $data["ID_HS"];?>" title="Check"></i></a>
                                            <a href="javascript:void(0)" style="margin-left: 5px;"><i class="fa fa-trash" data-hsID="<?php echo $data["ID_HS"];?>" title="Delete"></i></a>
                                      	</td>
                                    </tr>
									<?php 
											$dem++;
										}
									?>
                                </table>
                            </form>
                        </div>
                    </div>
               	</div>
                
                <div id="main-right">
                	<div>
                        <h3>Các ca học (cố định)<br />Lớp <?php echo $mon_lop_name; ?></h3>
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
											echo"<ol><a href='http://localhost/www/TDUONG/admin/ca-co-dinh/$data4[ID_CA]/' ";if($data4["ID_CA"]==$caID){echo"style='font-weight:600'";}echo"><i class='fa fa-caret-right'></i>".$thu_string[$data4["thu"]-1].", $data4[gio]</a></ol>";
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
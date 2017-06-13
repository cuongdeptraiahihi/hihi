<div class="popup animated bounceIn" id="baoloi">
    <div>
        <p class="title"></p>
        <div>
            <button class="submit2 btn_ok"><i class="fa fa-check"></i></button>
        </div>
    </div>
</div>

<!--<div class="popup animated bounceIn" id="bao-error" style="top:15%;">-->
<!--	<form action="." method="post">-->
<!--    	<div>-->
<!--            <p class="title">Thông báo lỗi</p>-->
<!--            <div>-->
<!--            	<textarea class="input" id="sms-con" name="sms-con" style="height:80px;resize:vertical;width:96%;background:#000;padding:2%;" placeholder="Nội dung"></textarea>-->
<!--                <button class="submit2" type="submit" id="sms-ok" name="sms-ok" style="margin-top:10px;">Gửi</button>-->
<!--                <button class="submit2 btn_ok" style="margin-top:10px;">Hủy</button>-->
<!--           	</div>-->
<!--        </div>-->
<!--    </form>-->
<!--</div>-->

<?php
$resultb=get_thong_bao_big($_SESSION["ID_HS"],$_SESSION["lmID"]);
if(mysqli_num_rows($resultb)!=0 && stripos($_SERVER['REQUEST_URI'],"/thach-dau/")===false) {
    $datab=mysqli_fetch_assoc($resultb);
    if($datab["danhmuc"]=="thach-dau") {
        echo "<div class='popup animated bounceIn' id='thongbao-big' style='height: 250px;display:block;background-image: url(https://localhost/www/TDUONG/images/shield.jpg);background-position: center center;background-size: cover;background-repeat: no-repeat;'>
            <div style='margin-top: 50px;'>";
    } else {
        echo"<div class='popup animated bounceIn' id='thongbao-big' style='display: block;'>
            <div>";
    }
    if($datab["danhmuc"]=="thach-dau"){
        //echo"<div><img src='https://localhost/www/TDUONG/images/swords.png' style='width:20%;margin-bottom:10px;' /></div>";
        echo"<p class='title' style='background:#FFF;padding: 2.5%;'>$datab[content]</p>";
    } else if($datab["danhmuc"]=="len-level") {
        echo"<div><img src='https://localhost/www/TDUONG/images/level_up.png' style='width:45%;margin-bottom:10px;' /></div>
            <p class='title'>$datab[content]</p>";
        //echo"<div><i class='fa fa-level-up' style='font-size:32px;color:red;'></i><span style='font-size:32px;font-weight: 600;margin-left: 5px;'>$datab[object]</span></div>";
    } else if($datab["danhmuc"]=="nhay-de") {
        if ($datab["object"] == 1) {
            echo "<div><i class='fa fa-level-up' style='font-size:32px;color:red;'></i><span style='font-size:32px;font-weight: 600;margin-left: 5px;'>Lên đề</span></div>";
        } else {
            echo "<div><i class='fa fa-level-down' style='font-size:32px;color:red;'></i><span style='font-size:32px;font-weight: 600;margin-left: 5px;'>Xuống đề</span></div>";
        }
        echo"<p class='title'>$datab[content]</p>";
    }
    ?>
    <div>
        <?php
        if($datab["danhmuc"]=="thach-dau"){
            $resultt=callback_thachdau($datab["object"]);
            $datat=mysqli_fetch_assoc($resultt);
            if($datat["status"]=="pending" && $datat["ID_HS2"]==$_SESSION["ID_HS"]) {
                echo"<button class='submit2' id='tb-accept' data-tbID='$datab[ID_TB]' data-tdID='".base64_encode($datab["object"])."'><i class='fa fa-check'></i></button>
					<button class='submit2' id='tb-cancle' data-tbID='$datab[ID_TB]' data-tdID='".base64_encode($datab["object"])."'><i class='fa fa-ban'></i></button>";
                //<button class='submit2 btn_tb' data-tbID='$datab[ID_TB]'><i class='fa fa-trash'></i></button>
            } else {
                echo"<button class='submit2 btn_tb' data-tbID='$datab[ID_TB]'>Chiến luôn</button>";
            }
        } else {
            echo"<button class='submit2 btn_tb' data-tbID='$datab[ID_TB]'><i class='fa fa-check'></i></button>";
        }
        ?>
    </div>
    </div>
    </div>
<?php } ?>

<!--<div class="fixed-action-btn click-to-toggle animated bounceIn" style="bottom: 0;padding-bottom:45px; right: 0; padding-right:25px; width:150px;">-->
<!--    <ul>-->
<!--    --><?php
//        $icon=array("fa-edit","fa-list-alt","fa-trophy","fa-exchange","fa-random","fa-dollar","fa-facebook-official");
//        $title=array("Trắc nghiệm","Bảng xếp hạng","Thách đấu","Đổi ca học","Đổi ca thi","Tài khoản","Group lớp");
//        $link=array("http://localhost/www/TDUONG/luyenthi/dang-nhap-xa/".encode_data($_SESSION["ID_HS"]."|".$_SESSION["cmt"]."|".$_SESSION["lmID"],md5("1241996"))."/","http://localhost/www/TDUONG/mobile/bang-xep-hang/","http://localhost/www/TDUONG/mobile/thach-dau/","http://localhost/www/TDUONG/mobile/lich-hoc/","http://localhost/www/TDUONG/mobile/lich-thi/","http://localhost/www/TDUONG/mobile/tai-khoan/","http://localhost/www/TDUONG/mobile/dang-ky-facebook/");
//        $color=array("#2C84BD","#69b42e","cyan","brown","sliver","#ffa85a","orange","#365899");
//        for($i=1;$i<=7;$i++) {
//            echo"<li>
//				<a href='".$link[$i-1]."' class='btn-floating'>
//                    <p>".$title[$i-1]."</p>
//                    <div class='my-btn' style='background:".$color[$i-1]."'><i class='fa ".$icon[$i-1]."'></i></div>
//                </a>
//			</li>";
//        }
//    ?>
<!--    </ul>-->
<!--    <a class="btn-floating btn-large" style="float:right;">-->
<!--    <div class="my-btn my-btn-big" style="background:red;"><i class="large fa fa-th-list"></i></div>-->
<!--    </a>-->
<!--</div>-->
            
<div id="NAVBAR">
	<ul>
        <li id="open-sidebar" style='background:#FFF;width: 25%;'><a href='javascript:void(0)' title='Menu'><i class='fa fa-align-justify' style='font-size:14px;'></i></a></li>
        <li style='background:#FFF;width: 50%;'><a href='http://localhost/www/TDUONG/mobile/tong-quan/' title='Bgo Education'>Bgo Education</a></li>
        <?php
            $tb_num=count_thong_bao_hs($hsID,$lmID);
            if($tb_num>=1) {
                echo"<li style='position:relative;background:#FFF;width: 25%;'>
                        <a href='http://localhost/www/TDUONG/mobile/thong-bao/' style='color:yellow' title='Thông báo'><i class='fa fa-bell' style='font-size:14px;'></i></a>
                        <p style='position:absolute;right:35%;color:#FFF;background:red;border-radius:1000px;width:20px;height:20px;font-size:12px;line-height:22px;top:0;'>$tb_num</p>
                    </li>";
            } else {
                echo"<li style='position:relative;background:#FFF;opacity:0.15;width: 25%;'><a href='http://localhost/www/TDUONG/mobile/thong-bao/' title='Thông báo'><i class='fa fa-bell' style='font-size:14px;'></i></a></li>";
            }
        ?>
    </ul>
</div>

<div id="DOCK">
    <ul>

    </ul>
</div>

<!--<div id="SMS">-->
<!--    <a href='http://m.me/Bgo.edu.vn' target="_blank" class='sms-new'><i class='fa fa-commenting'></i><span>Hỗ trợ</span></a>-->
<!--</div>-->
<!--<div id="SMS">-->
<?php
	/*if(check_times_mail($_SESSION["ID_HS"])) {
		if(isset($_SESSION["bao-loi"]) && $_SESSION["bao-loi"]==1) {
			echo"<a href='javascript:void(0)' class='sms-new'><i class='fa fa-commenting'></i><span>Gửi thành công!</span></a>";
			$_SESSION["bao-loi"]=0;
		} else {
			echo"<a href='javascript:void(0)' class='sms-new'><i class='fa fa-commenting'></i><span>Góp ý</span></a>";
		}*/
    /*echo"<div id='sms-sub'>
    	<form action='.' method='post'>
            <table>
                <tr><td colspan='2'></td></tr>
                <tr><td colspan='2'><textarea class='input2' id='sms-con' name='sms-con' style='height:80px;resize:vertical;' placeholder='Nội dung'></textarea></td></tr>
                <tr><td colspan='2'></td></tr>
                <tr>
                	<td></td>
                    <td><input type='submit' class='submit' value='Gửi' id='sms-ok' name='sms-ok' style='float:right;' /></td>
                </tr>
            </table>
        </form>
    </div>";*/
	/*} else {
		echo"<a href='javasript:void(0)'><i class='fa fa-commenting'></i><span>Đã đủ 5 lần/ngày</span></a>";
	}*/
?>
<!--</div>-->

<?php 
	if($_SESSION["test"]==1) {
		echo"<div id='testing'><p>ĐÂY LÀ TÀI KHOẢN CỦA PHỤ HUYNH, BẠN CHỈ CÓ THỂ XEM.</p></div>";
	}
	$resultn=get_hs_short_detail($_SESSION["ID_HS"],$_SESSION["lmID"]);
	$datan=mysqli_fetch_assoc($resultn);
?>
</div>

<div id="SIDEBAR" class="back">
    <ul>
        <li>
            <div id="main-top" style="padding: 0;">
                <div id="main-avata" style="display: block;">
                    <a href="http://localhost/www/TDUONG/mobile/ho-so/" title="Hồ sơ cá nhân"><img src="https://localhost/www/TDUONG/hocsinh/avata/<?php echo $datan["avata"]; ?>" /></a>
                </div>
                <div id="main-person" style="width: 70%;">
                    <h1 style="line-height: 26px;text-align: left;">
                        <a href="javascript:void(0)" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-dollar"></i> <?php echo format_price($datan["taikhoan"]); ?></a>
                        <a href="javascript:void(0)" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-calendar"></i> <?php echo get_hs_lich_hoc($hsID, $lmID, $monID); ?></a>
                        <a href="javascript:void(0)" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-calendar-plus-o"></i> <?php echo get_hs_lich_thi2($hsID, $monID); ?></a>
                        <a href="http://localhost/www/TDUONG/mobile/level/" style="color: #FFF;font-size:12px;display: block;"><i class="fa fa-circle" style="font-size: 6px;"></i> <i class="fa fa-trophy"></i> Level <?php echo $datan["level"]; ?> (<?php echo $datan["de"]; ?>)</a>
                    </h1>
                </div>
                <div class="clear"></div>
            </div>
        </li>
        <li><a href="javascript:void(0)" class="line" style="background: #FFF;"></a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/lich-hoc/">Đổi ca học</a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/lich-thi/">Đổi ca thi</a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/tai-khoan/">Tài khoản</a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/bang-xep-hang/">Bảng xếp hạng</a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/thach-dau/">Thách đấu</a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/nhom-phat-ve/">Phát vé</a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/shop/">Sách tham khảo</a></li>
        <li><a href="http://localhost/www/TDUONG/luyenthi/dang-nhap-xa/<?php echo encode_data($_SESSION["ID_HS"]."|".$_SESSION["cmt"]."|".$_SESSION["lmID"],md5("1241996"))."/"; ?>">Trắc nghiệm</a></li>
        <li><a href="javascript:void(0)" class="line" style="background: #FFF;"></a></li>
        <?php
        $queryx="SELECT l.ID_LM,l.name,m.ID_STT,n.ID_N FROM lop_mon AS l
            LEFT JOIN hocsinh_mon AS m ON m.ID_HS='$hsID' AND m.ID_LM=l.ID_LM
            LEFT JOIN hocsinh_nghi AS n ON n.ID_HS='$hsID' AND n.ID_LM=l.ID_LM ORDER BY l.ID_MON ASC,l.ID_LM ASC";
        $resultx=mysqli_query($db,$queryx);
        while($datax=mysqli_fetch_assoc($resultx)) {
            if(isset($datax["ID_STT"])) {
                if(isset($datax["ID_N"])) {
                    echo"<li";if($lmID!=$datax["ID_LM"]){echo" style='opacity: 0.35;'";} echo"><a href='javascript:void(0)' class='nghi_mon close-sidebar' title='Môn $datax[name]'>Môn $datax[name]</a></li>";
                } else {
                    echo"<li";if($lmID!=$datax["ID_LM"]){echo" style='opacity: 0.35;'";} echo"><a href='http://localhost/www/TDUONG/mobile/chon-mon/$datax[ID_LM]/' title='Môn $datax[name]'>Môn $datax[name]</a></li>";
                }
            } else {
                echo"<li";if($lmID!=$datax["ID_LM"]){echo" style='opacity: 0.35;'";} echo"><a href='javascript:void(0)' class='access_mon close-sidebar' title='Môn $datax[name]'>Môn $datax[name]</a></li>";
            }
        }
        ?>
        <li><a href="javascript:void(0)" class="line" style="background: #FFF;"></a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/info/">Những điều cần biết</a></li>
        <li><a href="http://localhost/www/TDUONG/mobile/dang-xuat/"><i class="fa fa-sign-out"></i> Đăng xuất</a></li>
        <li></li>
    </ul>
</div>

<div id="myback"></div>
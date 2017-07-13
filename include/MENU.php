<div class="popup animated bounceIn" id="baoloi">
    <div>
        <p class="title"></p>
        <div>
            <button class="submit2 btn_ok"><i class="fa fa-check"></i></button>
        </div>
    </div>
</div>

<!--<div class="popup animated bounceIn" id="bao-error">-->
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
            <div style='margin-top: 70px;'>";
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

<div class="fixed-action-btn click-to-toggle animated bounceIn" style="bottom: 0;padding-bottom:45px; right: 0; padding-right:25px; width:150px;">
    <ul>
    <?php
        $icon=array("fa-shopping-bag","fa-list-alt","fa-trophy","fa-exchange","fa-dollar");
        $title=array("Sách tham khảo","Bảng xếp hạng","Thách đấu","Đổi ca","Tài khoản");
        $link=array("https://localhost/www/TDUONG/shop/","https://localhost/www/TDUONG/bang-xep-hang/","https://localhost/www/TDUONG/thach-dau/","https://localhost/www/TDUONG/lich-hoc/","https://localhost/www/TDUONG/tai-khoan/");
        $color=array("blue","brown","green","#365899","orange","#2C84BD","#69b42e","yellow","cyan");
        for($i=1;$i<=5;$i++) {
            echo"<li>";
//			if($i==3) {
//				echo"<a href='".$link[$i-1]."' class='btn-floating'>";
//				echo"<p>".$title[$i-1]."</p>
//                    <div class='my-btn' style='background:".$color[$i-1].";margin-top:0;'><i class='fa ".$icon[$i-1]."'></i></div>
//                </a>";
//			} else {
				echo"<a href='".$link[$i-1]."' class='btn-floating'>";
				echo"<p>".$title[$i-1]."</p>
                    <div class='my-btn' style='background:".$color[$i-1]."'><i class='fa ".$icon[$i-1]."'></i></div>
                </a>";
//			}
            echo"</li>";
        }
    ?>
    </ul>
    <a class="btn-floating btn-large" style="float:right;">
      <div class="my-btn my-btn-big" style="background:red;"><i class="large fa fa-th-list"></i></div>
    </a>
</div>

<div id="NAVBAR">
	<ul>
        <li id="li-temp" style="background:#FFF;">
        	<div id="thongbao-menu">
                <ul>
                    <li id="menu-load">
                        <ol class="tb-con" style="float: none;text-align: center;width: 100%;margin: 0;">
                            <p>Đang tải...</p>
                        </ol>
                    </li>
                <?php
                    global $count_time;
                    if($count_time) {
                    echo"<li style='padding:0;padding-top:5px;'><a href='javascript:void(0)' style='color:green;'>
                            <ol style='width:100%;''>
                            <p style='margin-left:10px;color:#000;font-size:14px;font-weight:600;'>Đếm ngược thi đại học</p>
                            <div id='DateCountdown' style='padding:10px 0 5px 0;'></div>
                            </ol>
                            <div class='clear'></div>
                        </a></li>";
                    }
                    echo"<a href='https://localhost/www/TDUONG/thong-bao/' style='float:right;display:block;font-size:14px;margin-top:3px;color:#FFF;'>Xem tất cả</a>";
                ?>
                </ul>
            </div>
       	</li>
        <li style="background:#FFF;"><a href="https://localhost/www/TDUONG/info/" target="_blank" title="Những điều cần biết"><i class="fa fa-book" style="font-size:18px;"></i></a></li>
        <li style="background:#FFF;" class="li-con"><a href="https://localhost/www/TDUONG/dang-xuat/" title="Đăng xuất"><i class="fa fa-sign-out" style="font-size:18px;"></i></a></li>
    </ul>
</div>

<?php
	/*$tb_num=count_thong_bao_hs($_SESSION["ID_HS"],$_SESSION["mon"]);
	if($tb_num>=1) {
		echo"<div id='THONGBAO'>
			<div id='tb-icon' class='noti'><i class='fa fa-bell-o'></i></div>
			<div class='clear'></div>
			<a href='javascript:void(0)' style='position:absolute;right:25px;top:10px;display:block;font-size:12px;border-radius:10px;width:20px;text-align:center;height:20px;color:#FFF;background:red;line-height:22px;font-weight:600;'>$tb_num</a>";
	} else {
		echo"<div id='THONGBAO'>
			<div id='tb-icon'><i class='fa fa-bell-o'></i></div>
			<div class='clear'></div>";
	}*/
?>


<?php 
	if($_SESSION["test"]==1) {
		echo"<div id='testing'><p>ĐÂY LÀ TÀI KHOẢN CỦA PHỤ HUYNH, BẠN CHỈ CÓ THỂ XEM.</p></div>";
	}
?>

<div id="myback"></div>

<!--<script>-->
<!--    window.fbAsyncInit = function() {-->
<!--        FB.init({-->
<!--            appId      : '967757889982912',-->
<!--            cookie     : true,-->
<!--            xfbml      : true,-->
<!--            version    : 'v2.8'-->
<!--        });-->
<!--        FB.AppEvents.logPageView();-->
<!--    };-->
<!---->
<!--    (function(d, s, id){-->
<!--        var js, fjs = d.getElementsByTagName(s)[0];-->
<!--        if (d.getElementById(id)) {return;}-->
<!--        js = d.createElement(s); js.id = id;-->
<!--        js.src = "//connect.facebook.net/en_US/sdk.js";-->
<!--        fjs.parentNode.insertBefore(js, fjs);-->
<!--    }(document, 'script', 'facebook-jssdk'));-->
<!--</script>-->
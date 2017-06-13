<div class="popup animated bounceIn" id="baoloi">
    <div>
        <p class="title"></p>
        <div>
            <button class="submit2 btn_ok"><i class="fa fa-check"></i></button>
        </div>
    </div>
</div>

<div class="fixed-action-btn click-to-toggle animated bounceIn" style="bottom: 0;padding-bottom:45px; right: 0; padding-right:25px; width:150px;">
    <ul>
    <?php
        $icon=array("fa-search","fa-exchange","fa-money","fa-calendar-check-o","fa-user");
        $title=array("Tra cứu","Đổi ca","Quản lý tiền","Điểm danh","Học sinh");
        $link=array("#","http://localhost/www/TDUONG/trogiang/lich-lam/","http://localhost/www/TDUONG/trogiang/quan-ly-tien/","http://localhost/www/TDUONG/trogiang/diem-danh-hoc-sinh/","http://localhost/www/TDUONG/trogiang/hoc-sinh-chi-tiet/");
        $color=array("green","#365899","orange","#2C84BD","#69b42e");
        for($i=1;$i<=5;$i++) {
            echo"<li>";
			if($i==3) {
				echo"<a href='".$link[$i-1]."' class='btn-floating'>";
				echo"<p>".$title[$i-1]."</p>
                    <div class='my-btn' style='background:".$color[$i-1].";margin-top:0;'><i class='fa ".$icon[$i-1]."'></i></div>
                </a>";
			} else {
				echo"<a href='".$link[$i-1]."' class='btn-floating'>";
				echo"<p>".$title[$i-1]."</p>
                    <div class='my-btn' style='background:".$color[$i-1]."'><i class='fa ".$icon[$i-1]."'></i></div>
                </a>";
			}
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
        <li style="background:#FFF;" class="li-con"><a href="http://localhost/www/TDUONG/trogiang/tong-quan/" title="Trang chủ"><i class="fa fa-home" style="font-size:18px;"></i></a></li>
        <li style="background:#FFF;position: relative;" class="li-con" id="tb-icon"><a href='javascript:void(0)' title="Thông báo"><i class="fa fa-bell" style="font-size:18px;"></i></a>
            <div id="thongbao-menu">
                <ul>
                    <li id="menu-load">
                        <ol class="tb-con" style="float: none;text-align: center;width: 100%;margin: 0;">
                            <p>Đang tải...</p>
                        </ol>
                    </li>
                    <a href='http://localhost/www/TDUONG/trogiang/thong-bao/' style='float:right;display:block;font-size:14px;margin-top:3px;color:#FFF;'>Xem tất cả</a>
                </ul>
            </div>
        </li>
        <li style="background:#FFF;" class="li-con"><a href="http://localhost/www/TDUONG/trogiang/dang-xuat/" title="Đăng xuất"><i class="fa fa-sign-out" style="font-size:18px;"></i></a></li>
    </ul>
</div>




<div id="myback"></div>
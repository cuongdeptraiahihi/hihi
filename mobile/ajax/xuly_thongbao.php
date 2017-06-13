<?php
	ob_start();
	//session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	session_start();
	require_once("../access_hocsinh.php");
    $hsID=$_SESSION["ID_HS"];
    $monID=$_SESSION["mon"];
    $lmID=$_SESSION["lmID"];
	
	if (isset($_POST["tbID"])) {
		$tbID=$_POST["tbID"];
		xem_thong_bao($tbID);
	}
	
	if (isset($_POST["tbID0"])) {
		$tbID=$_POST["tbID0"];
		chuaxem_thong_bao($tbID);
	}
	
	if (isset($_POST["tbID1"])) {
		$tbID=$_POST["tbID1"];
		freezee_thong_bao($tbID);
	}

	if(isset($_POST["action"])) {
	    $action=$_POST["action"];
	    if($action=="get") {
            $dem_tb=0;
            turn_off_freezee($hsID,$lmID);
            $resultn=get_thong_bao_hs($hsID,$lmID);
            while($datan=mysqli_fetch_assoc($resultn)) {
                $action="";
                $ava=$_SESSION["ava"];
                if($datan["danhmuc"]=="thach-dau") {
                    $content=$datan["content"];
                    $link="thach-dau";
                } else if($datan["danhmuc"]=="ngoi-sao") {
                    $content=$datan["content"];
                    $link="ngoi-sao-hy-vong";
                } else if($datan["danhmuc"]=="diem-thi") {
                    $content=$datan["content"];
                    $ava=get_avata_thay($monID);
                    $link="tong-quan";
                } else if($datan["danhmuc"]=="bang-xep-hang") {
                    $content=$datan["content"];
                    $ava=get_avata_thay($monID);
                    $link="bang-xep-hang";
                } else if($datan["danhmuc"]=="nhay-de") {
                    $content=$datan["content"];
                    $link="thong-bao";
                } else if($datan["danhmuc"]=="doi-ca") {
                    $content=$datan["content"];
                    $link="lich-hoc";
                } else if($datan["danhmuc"]=="phat-nong") {
                    $content=$datan["content"];
                    $ava=get_avata_thay($monID);
                    $link="tai-khoan-phat";
                } else if($datan["danhmuc"]=="nghi-hoc") {
                    $content=$datan["content"];
                    $ava=get_avata_thay($monID);
                    $link="thong-bao";
                } else if($datan["danhmuc"]=="nap-rut") {
                    $content = $datan["content"];
                    $link = "tai-khoan";
                } else if($datan["danhmuc"]=="len-level") {
                    $content = $datan["content"];
                    $link = "thach-dau";
                } else if($datan["danhmuc"]=="push-noti") {
                    $content = $datan["content"];
                    $link = "thong-bao";
                } else if($datan["danhmuc"]=="mua-sach") {
                    $content = $datan["content"];
                    $link = "thong-bao";
                } else {
                    $content="";
                    $link="#";
                }
                $tbID0=$datan["ID_TB"];
                //if($datan["status"]=="freezee") {$tbID0=0;}
                echo"<li><a href='https://localhost/www/TDUONG/$link/' data-tbID='$tbID0'>
                    <ol class='tb-img'><img src='https://localhost/www/TDUONG/hocsinh/avata/$ava' /></ol>
                    <ol class='tb-con'>
                        <p>$content</p>
                        <p class='tb-time'><i class='fa ".get_icon_danhmuc($datan["danhmuc"])."'></i>".get_past_time($datan["datetime"])."</p>
                    </ol>
                    <ol class='tb-action'>
                        <i class='fa fa-bell-slash' title='Tắt'></i>
                        $action
                    </ol>
                    <div class='clear'></div>
                </a></li>";
                $dem_tb++;
            }
            if($_SESSION["show_tien"]==1) {
                $tien=get_tien_hs($hsID);
                if($tien<0) {
                    echo"<li style='padding:5px 0 5px 0;text-align:center;background:red;border:2px solid red;'>";
                } else {
                    echo"<li style='padding:5px 0 5px 0;'>";
                }
                echo"<ol style='width:100%;'>
                    <a href='https://localhost/www/TDUONG/tai-khoan/' style='margin-left:10px;float:left;color:#000;font-size:12px;font-weight:600;'>Tài khoản: ".format_price($tien)."</a>
                    <a href='https://localhost/www/TDUONG/level/' style='margin-right:10px;float:right;color:#000;font-size:12px;font-weight:600;'>LVL: ".get_level($hsID,$lmID)."</a>
                </ol>
                <div class='clear'></div>
                </a></li>";
            }
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
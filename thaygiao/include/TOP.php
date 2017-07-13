<?php
	$current= "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	$_SESSION["last_page"]=$current;
	if(!isset($_SESSION["hs_in"])) {
		$_SESSION["hs_in"]=array();
	}
//	if($_SESSION["mobile"]==1) {
//        echo "<div id='FIX'>
//		<nav><select class='input' style='width:33%;float: left;' id='CHANGE_MON'>";
//        $result = get_all_lop_mon();
//        while($data=mysqli_fetch_assoc($result)) {
//            echo"<option value='$data[ID_LM]' ";if($_SESSION["lmID"]==$data["ID_LM"]){echo"selected='selected'";}echo" >$data[name]</option>";
//        }
//        echo "</select></nav>
//		<nav style='text-align:center;'><input type='submit' class='submit' style='width:33% !important;float: left;' id='CHANGE_OK' value='OK' /></nav>
//	</div>";
//    } else {
//        echo "<div id='FIX'>
//		<nav><select class='input' style='width:100%;' id='CHANGE_MON'>";
//        $result = get_all_lop_mon();
//        while($data=mysqli_fetch_assoc($result)) {
//            echo"<option value='$data[ID_LM]' ";if($_SESSION["lmID"]==$data["ID_LM"]){echo"selected='selected'";}echo" >$data[name]</option>";
//        }
//        echo "</select></nav>
//		<nav style='text-align:center;'><input type='submit' class='submit' style='width: 100%;' id='CHANGE_OK' value='OK' /></nav>
//	</div>";
//    }
?>

<div id="TOP">
    <div class="top-i">
<!--        <div id="top-logo"><a href="http://localhost/www/TDUONG/thaygiao/home/"><img src="http://localhost/www/TDUONG/thaygiao/images/logo.jpg" /></a></div>-->
        <ul>
            <li style="background:#3E606F;width:60px;height:35px;position:relative;">
                <a href='http://localhost/www/TDUONG/thaygiao/home/' style="color:#FFF;padding-top:5px;"><span class='fa fa-home' style='font-size:26px !important;'></span></a>
                <?php require_once("include/LEFT.php"); ?>
            </li>
            <li style="background:#FFF;"><a style="color:#3E606F;" href="http://localhost/www/TDUONG/thaygiao/hoc-sinh-chi-tiet/">Học sinh</a></li>
            <li><a href="http://localhost/www/TDUONG/thaygiao/diem-danh/">Điểm danh</a></li>
            <?php
            if($_SESSION["mobile"]==0) {
                echo"<li><a href='http://localhost/www/TDUONG/thaygiao/pre-hoc-sinh-thong-ke/'>Kiểm tra</a></li>
                <li><a href = 'http://localhost/www/TDUONG/thaygiao/bang-luong/' id='bangluong'> Bảng lương </a ></li >
                <li><a href = 'http://localhost/www/TDUONG/thaygiao/cai-dat/' > Cài đặt </a ></li >";

                echo "<li>";
            } else {
                echo "<li style='margin-left: 64px;'><a href='http://localhost/www/TDUONG/thaygiao/tra-cuu/'>Tra cứu</a></li>
                <li style='border:none;'>";
            }
                echo"<nav><select class='input' style='border-radius:6px;width:100%;height:100%;text-align-last:center;font-size: 12px;text-transform: uppercase;color:#FFF;background:#3E606F;border:1px solid #3E606F;border-bottom:2px solid #3E606F;font-weight: 600;' id='CHANGE_MON'>";
                $result = get_all_lop_mon2($_SESSION["mon"]);
                while($data=mysqli_fetch_assoc($result)) {
                    echo"<option value='$data[ID_LM]' ";if($_SESSION["lmID"]==$data["ID_LM"]){echo"selected='selected'";}echo" >$data[name]</option>";
                }
                echo "</select></nav>
            </li>
            <li><nav>";
            $lmIDxxx=$_SESSION["lmID"];
            $monIDxxx=$_SESSION["mon"];
                echo"<select class='input' style='border-radius:6px;width:100%;height:100%;text-align-last:center;font-size: 12px;text-transform: uppercase;color:#FFF;background:#3E606F;border:1px solid #3E606F;border-bottom:2px solid #3E606F;font-weight: 600;' id='CHANGE_BIEUDO'>
                    <option value=''>Biểu đồ</option>
                    <option value='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-tin/$lmIDxxx/$monIDxxx/'>Thông tin học sinh</option>
                    <option value='http://localhost/www/TDUONG/thaygiao/hoc-sinh-thong-ke/$lmIDxxx/0/'>Thống kê điểm</option>
                    <option value='http://localhost/www/TDUONG/thaygiao/cai-dat-ca/$lmIDxxx/$monIDxxx/'>Ca học - Ca thi</option>
                    <option value='http://localhost/www/TDUONG/thaygiao/thach-dau/'>Thách đấu</option>
                    <option value='http://localhost/www/TDUONG/thaygiao/hoc-sinh-nghi-nhieu/$lmIDxxx/$monIDxxx/'>Học sinh nghỉ nhiều</option>
                    <option value='http://localhost/www/TDUONG/thaygiao/hoc-sinh-vao-ra/'>Thống kê vào ra</option>
                </select>
            </nav></li>";
			?>
        </ul>
        <div class="clear"></div>
    </div>
</div>
        
<div class="popup" id="popup-note" style="width:30%;left:35%;">
    <div class="popup-close"><i class="fa fa-close"></i></div>
    <p style="text-transform:uppercase;">Thêm note</p>
    <div style="width:90%;margin:15px auto 15px auto;">
        <input type="text" id="title-add" class="input" style="margin-bottom:10px;" placeholder="Tiêu đề" />
        <textarea id="text-add" class="input" rows="5" placeholder="Nội dung"></textarea>
<!--        <input type="text" id="hs-add" class="input" style="margin-top:10px;" placeholder="Mã học sinh nếu cần, VD: 99-0025, 99-0345, ..." />-->
        <input type="text" id="sign-add" class="input" style="margin-top:10px;" placeholder="Kí tên" />
    </div>
    <div>
<!--        <button class="submit" id="gim-ok">Thêm và Gim</button>-->
        <button class="submit" id="note-ok" data-type="">Thêm note</button>
    </div>
</div>

<div class="popup" id="popup-chi" style="width:30%;left:35%;">
    <div class="popup-close"><i class="fa fa-close"></i></div>
    <p style="text-transform:uppercase;">Thêm khoản chi</p>
    <div style="width:90%;margin:15px auto 15px auto;">
        <input type="text" id="tien-chi-add" class="input" style="margin-bottom:10px;" placeholder="Số tiền chi" />
        <textarea id="text-chi-add" class="input" rows="3" placeholder="Nội dung"></textarea>
    </div>
    <div>
        <button class="submit" id="chi-ok" data-type="">Thêm</button>
    </div>
</div>

<div class="popup" id="popup-tien-chi" style="width:45%;left:27.5%;top:20%;">
    <div class="popup-close"><i class="fa fa-close"></i></div>
    <p style="text-transform:uppercase;">Danh sách tiền đã chi</p>
    <div style="width:90%;margin:15px auto 15px auto;">
        <table style="width:100%;">

        </table>
    </div>
</div>

<div class="popup" id="popup-list" style="width:45%;left:2.5%;top:20%;">
	<div class="popup-close"><i class="fa fa-close"></i></div>
    <p style="text-transform:uppercase;">Danh sách note</p>
    <div style="width:90%;margin:15px auto 15px auto;">
    	<table style="width:100%;">
        	
        </table>
    </div>
</div>

<div class="popup" id="popup-idea" style="width:45%;left:50%;top:20%;">
    <div class="popup-close"><i class="fa fa-close"></i></div>
    <p style="text-transform:uppercase;">Danh sách ý tưởng</p>
    <div style="width:90%;margin:15px auto 15px auto;">
        <table style="width:100%;">

        </table>
    </div>
</div>

<?php
//    $result=get_gim_note(3);
//    if(mysqli_num_rows($result) != 0) {
//        echo"<div id='REMIND'>
//            <ul>";
//            while($data=mysqli_fetch_assoc($result)) {
//                echo"<li>
//                    <p>$data[content]</p>
//                    <span>$data[name] - ".format_datetime($data["datetime"])."</span>
//                    <div class='clear'></div>
//                </li>";
//            }
//        echo"</ul>
//        </div>";
//    }
    echo"<div id='REMIND' style='display: none;'>
        <ul>
            <li><</li>
        </ul>
    </div>";
//?>

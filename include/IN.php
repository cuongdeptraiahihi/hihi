<!--<div id="NAVBAR">
	<ul>
    	<!--<li><a href="https://localhost/www/TDUONG/" title="Trang chủ"><i class="fa fa-home"></i></a></li>-->
        <?php
			/*$resultx=get_all_mon();
			while($datax=mysqli_fetch_assoc($resultx)) {
				$check=check_access_mon($_SESSION["ID_HS"],$datax["ID_MON"]);
				if($check) {
					if(check_hs_nghi($_SESSION["ID_HS"],$datax["ID_MON"])) {
						echo"<li";if($_SESSION["mon"]!=$datax["ID_MON"]){echo" class='li-con'";} echo"><a href='javascript:void(0)' class='nghi_mon' title='Môn $datax[name]'>$datax[name]</a></li>";
					} else {
						echo"<li";if($_SESSION["mon"]!=$datax["ID_MON"]){echo" class='li-con'";} echo"><a href='https://localhost/www/TDUONG/chon-mon/$datax[ID_MON]/' title='Môn $datax[name]'>$datax[name]</a></li>";
					}
				} else {
					echo"<li";if($_SESSION["mon"]!=$datax["ID_MON"]){echo" class='li-con'";} echo"><a href='javascript:void(0)' class='access_mon' title='Môn $datax[name]'>$datax[name]</a></li>";
				}
			}*/
		?>
       <!-- <li class="li-con"><a href="https://localhost/www/TDUONG/dang-xuat/"><i class="fa fa-sign-out"></i></a><p>Đăng xuất</p></li>
    </ul>
</div>-->



<div id="SMS">
    <a href='https://www.facebook.com/hotroloptoan' target="_blank" class='sms-new'><i class='fa fa-commenting'></i><span>Hỗ trợ</span></a>
</div>
<?php
	/*if(check_times_mail($_SESSION["ID_HS"])) {
		if(isset($_SESSION["bao-loi"]) && $_SESSION["bao-loi"]==1) {
			echo"<a href='javascript:void(0)' class='sms-new'><i class='fa fa-commenting'></i><span>Bạn đã gửi lỗi thành công!</span></a>";
			$_SESSION["bao-loi"]=0;
		} else {
			echo"<a href='javascript:void(0)' class='sms-new'><i class='fa fa-commenting'></i><span>Nhắn tin báo lỗi</span></a>";
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
		echo"<a href='javasript:void(0)'><i class='fa fa-commenting'></i><span>Bạn đã gửi đủ 5 lần/ngày</span></a>";
	}*/
?>
<!--</div>-->
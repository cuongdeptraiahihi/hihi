<div id="main-left">
    <div>
        <ul>
            <li class="action">
                <?php
                    $dem=$count=0;
                    $result=get_all_lop_mon();
                    while($data=mysqli_fetch_assoc($result)) {
                        if($data["ID_MON"]==$_SESSION["mon"]) {
                            continue;
                        }
                        $temp=count_hs_mon_lop($data["ID_LM"]);
                        echo"<span>Sĩ số $data[name]: $temp em<br /></span>";
                        $dem++;
                        $count+=$temp;
                    }
                ?>
            </li>
            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/doi-mat-khau/"><i class="fa fa-lock"></i>Đổi mật khẩu</a></li>
            <li class="action"><a href="http://m.me/Bgo.edu.vn" target="_blank"><i class="fa fa-bug"></i>Báo lỗi - Trợ giúp</a></li>
            <li class="action"><a href="http://localhost/www/TDUONG/thaygiao/dang-xuat/"><i class="fa fa-sign-out"></i>Đăng xuất</a></li>
        </ul>
    </div>
</div>
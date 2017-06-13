<div id="main-left">
    <div>
        <ul>
            <li class="action"><a href="http://localhost/www/TDUONG/admin/tai-khoan/"><i class="fa fa-money"></i>Tài khoản</a>
                <?php
                $nap=get_nap_ad();
                $rut=get_rut_ad();
                $thuong_con=get_thuong_con_ad();
                $phat_con=get_phat_con_ad();
                ?>
                <ul>
                    <ol><span>Đã thu: <b><?php echo format_money_vnd($nap);?></b></span></ol>
                    <ol><a href="http://localhost/www/TDUONG/admin/tai-khoan/<?php echo $_SESSION["mon"]."/".$_SESSION["lop"]; ?>/1/">Cần thu: <b><?php if($phat_con>$nap){echo format_money_vnd($phat_con-$nap);}else{echo 0;} ?></b></a></ol>
                    <ol><span>Tổng thu: <b><?php echo format_money_vnd($phat_con); ?></b></span></ol>
                </ul>
                <ul>
                    <ol><span>Đã chi: <b><?php echo format_money_vnd(get_da_chi() + $rut);?></b></span></ol>
                    <ol><a href="javascript:void(0)" id="da-chi">Cần chi: <b><?php echo format_money_vnd($thuong_con - $rut); ?></b></a></ol>
                    <ol><span>Tổng chi: <b><?php echo format_money_vnd($thuong_con); ?></b></span></ol>
                </ul>
                <a href="javascript:void(0)"><b>Lợi nhuận: <?php echo format_money_vnd($phat_con-$thuong_con); ?></b></a>
            </li>
            <li class="action">
                <?php
                    $dem=$count=0;
                    $result=get_all_lop_mon();
                    while($data=mysqli_fetch_assoc($result)) {
                        $temp=count_hs_mon_lop($data["ID_LM"]);
                        echo"<span>Sĩ số $data[name]: $temp em<br /></span>";
                        $dem++;
                        $count+=$temp;
                    }
                ?>
            </li>
            <li class="action"><a href="http://localhost/www/TDUONG/admin/doi-mat-khau/"><i class="fa fa-lock"></i>Đổi mật khẩu</a></li>
            <li class="action"><a href="http://m.me/Bgo.edu.vn" target="_blank"><i class="fa fa-bug"></i>Báo lỗi - Trợ giúp</a></li>
            <li class="action"><a href="http://localhost/www/TDUONG/admin/dang-xuat/"><i class="fa fa-sign-out"></i>Đăng xuất</a></li>
        </ul>
    </div>
</div>
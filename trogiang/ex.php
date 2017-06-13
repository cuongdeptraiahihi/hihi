<div class="main-div animated bounceInUp">
    <div id="main-info">
        <div class="main-1-left back" style="margin-right:2%;max-height:none;">
            <div>
                <p class="main-title">Lịch làm cố định trong tuần</p>
                <p id="tkb-show"></p>
            </div>
            <table class="table-tkb" style="border-spacing:0 3px;">
                <?php $tkb=array();
                $query5="SELECT DISTINCT thu FROM trogiang_lich WHERE ID='$id'";
                $result5 = mysqli_query($db, $query5);
                while ($data5 = mysqli_fetch_assoc($result5)) {
                    $tkb[]="Thứ ".$data5["thu"];
                }
                ?>
                <?php
                $buoi_arr = array();
                $buoi_arr[] = array(
                    "buoi" => "S",
                    "text" => "Sáng (8h - 12h)"
                );
                $buoi_arr[] = array(
                    "buoi" => "C",
                    "text" => "Chiều (3h45 - 6h45)"
                );
                $buoi_arr[] = array(
                    "buoi" => "T",
                    "text" => "Tối (6h45 - 9h45)"
                );
                $count=count($buoi_arr);
                for($i=2;$i<=8;$i++) {
                    echo"
                                <table class='table-tkb' style = 'border-spacing:0 3px;'>
                                    <tr>
                                        <td colspan = '2' style = 'text-transform:uppercase;'>";
                    if($i==8) {
                        echo "<span>Chủ Nhật</span></td>";
                    } else {
                        echo "<span>Thứ $i</span></td>";
                    }
                    echo "</tr>
                                    <tr data-id='$id'>
                                        <td style = 'text-align: left;padding-top: 0;padding-bottom: 0;'>
                                            <ul class='ul-ca'>";
                    for($j=0;$j<$count;$j++) {
                        $query5 = "SELECT k.name FROM trogiang_lich AS l INNER JOIN trogiang_info AS k ON k.ID_TG=l.ID WHERE l.buoi='" . $buoi_arr[$j]["buoi"] . "' AND l.thu='$i'";
                        $result5 = mysqli_query($db, $query5);
                        $a = "";
                        while ($data5 = mysqli_fetch_assoc($result5)) {
                            $a .= ",". $data5["name"];
                        }
                        $query6 = "SELECT ID_STT FROM trogiang_lich WHERE buoi='" . $buoi_arr[$j]["buoi"] . "' AND thu='$i' AND ID='$id'";
                        $result6 = mysqli_query($db, $query6);
                        if(mysqli_num_rows($result6)==0) {
                            $class="<i class='fa fa-square-o id-" . $id . "'></i>";
                            $show="on";
                        } else {
                            $class="<i class='fa fa-check-square-o id-" . $id . "'></i>";
                            $show="off";
                        }
                        if($a==NULL) {
                            echo "<li class='$show' data-buoi='".$buoi_arr[$j]["buoi"]."' data-thu='$i'><span>$class</span><span>".$buoi_arr[$j]["text"]."</span></li>";
                        } else {
                            echo "<li class='$show' data-buoi='".$buoi_arr[$j]["buoi"]."' data-thu='$i'><span>$class</span><span>" . $buoi_arr[$j]["text"] . " (" . substr("$a", 1) . ")</span></li>";
                        }
                    }
                    echo "</ul>
                                        </td>
                                    </tr
                                </table>";
                }
                ?>
            </table>
            <input type="hidden" value="<?php echo implode(" - ",$tkb);?>" id="tkb-hin" />
        </div>
        <div class="main-1-left back">
            <div>
                <p class="main-title">Tổng số buổi đi làm</p>
                <p class="sum" data-id="<?php echo $id; ?>"</p>
            </div>
            <table class="table-tkb">

            </table>
            <input type="hidden" value="<?php echo $kt; ?>" id="kt-hin" />
        </div>
    </div>
</div>

<div class="clear"></div>
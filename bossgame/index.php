<?php
    ob_start();
    require_once("../model/open_db.php");
    include("../model/model.php");
?>
<body>
    <table border="1" style="min-width: 600px;width: 100%;">
        <?php
        $sl_arr = array();
        $sum = 0;
        $query = "SELECT g.ID_N,COUNT(l.ID_STT) AS sl FROM game_group AS g
            INNER JOIN list_group AS l ON l.ID_N=g.ID_N
            GROUP BY g.ID_N";
        $result = mysqli_query($db, $query);
        while($data=mysqli_fetch_assoc($result)) {
            $sl_arr[$data["ID_N"]]=$data["sl"];
            $sum += $data["sl"];
        }
        echo"<tr>
            <td style='text-align: center;' colspan='4'><span>Tổng học sinh: $sum</span></td>
            <td style='text-align: center;' colspan='50'><span>Chặng</span></td>
        </tr>";
        ?>
        <tr>
            <th class="hidden" style="width:5%;"><span>STT</span></th>
            <th style="width:10%;"><span>Tên đội</span></th>
            <th style="width:10%;"><span>SĐT</span></th>
            <th style="width:5%;"><span>SL</span></th>
            <?php
            $vong_arr = array();
            $query = "SELECT ID_STT,level,anh,mota,domain FROM game_level ORDER BY level ASC";
            $result = mysqli_query($db, $query);
            while($data=mysqli_fetch_assoc($result)) {
                $vong_arr[] = array(
                    "level" => $data["level"],
                    "anh" => $data["anh"],
                    "mota" => $data["mota"],
                    "domain" => $data["domain"]
                );
                if($data["level"] != 0) {
                    echo "<th style='width:90px;'><span>" . $data["level"] . "</span></th>";
                }
            }
            $n = count($vong_arr);
            ?>
        </tr>
        <?php
        $count_arr = array();
        $query = "SELECT ID_N,level,datetime FROM game_unlock ORDER BY ID_N ASC";
        $result = mysqli_query($db, $query);
        while($data=mysqli_fetch_assoc($result)) {
            $count_arr[$data["ID_N"]."-".$data["level"]] = 1;
        }
        $stt=1;
        $query = "SELECT g.ID_N,g.name,h.sdt,COUNT(u.ID_STT) AS dem FROM game_group AS g
            INNER JOIN hocsinh AS h ON h.ID_HS=g.ID_HS
            LEFT JOIN game_unlock AS u ON u.ID_N=g.ID_N
            GROUP BY g.ID_N
            ORDER BY dem DESC,g.name ASC";
        $result = mysqli_query($db, $query);
        while($data=mysqli_fetch_assoc($result)) {
            echo"<tr>
                <td style='text-align: center;' class='hidden'><span>$stt</span></td>
                <td><span>$data[name]</span></td>
                <td style='text-align: center;'><span>".format_phone($data["sdt"])."</span></td>
                <td style='text-align: center;'><span>".$sl_arr[$data["ID_N"]]."</span></td>";
                for($i = 0; $i < $n; $i++) {
                    if($vong_arr[$i]["level"] != 0) {
                        if (isset($count_arr[$data["ID_N"] . "-" . $vong_arr[$i]["level"]])) {
                            echo "<td style='text-align: center;background: #69b42e;'><span></span></td>";
                        } else {
                            echo "<td><span></span></td>";
                        }
                    }
                }
            echo"</tr>";
            $stt++;
        }
        ?>
    </table>
</body>
<?php
    ob_end_flush();
    require_once("../model/close_db.php");
?>
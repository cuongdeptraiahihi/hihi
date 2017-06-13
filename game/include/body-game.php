<div class="main" style="text-align:center; padding: 10px;">
    <ul>
        <?php
            $url_arr = array();
            $query0 = "SELECT g.level,g.domain,u.ID_STT,i.ID_STT AS inn FROM game_level AS g
            LEFT JOIN game_unlock AS u ON u.ID_N='$nID' AND u.level=g.level
            LEFT JOIN game_in AS i ON i.ID_N='$nID' AND i.level=g.level
            WHERE g.status='1' AND g.level!='0'
            ORDER BY g.level ASC";
            $result0 = mysqli_query($db,$query0);
            while($data0 = mysqli_fetch_assoc($result0)) {
                if($vong != 0) {
                    if(isset($data0["ID_STT"])) {
                        echo"<li><button style='background: #69b42e;' onclick=\"location.href = 'http://localhost/www/TDUONG/game/$data0[domain]/'\"><a href='javascript:void(0)'>$data0[level]</a></button></li>";
                        $url_arr[] = $data0["domain"];
                    } else if(isset($data0["inn"])) {
                        echo"<li><button onclick=\"location.href = 'http://localhost/www/TDUONG/game/$data0[domain]/'\"><a href='javascript:void(0)'>$data0[level]</a></button></li>";
                        $url_arr[] = $data0["domain"];
                        break;
                    } else if (stripos($url, $data0["domain"] . "/") != false) {
                        $query = "INSERT INTO game_in(ID_N,ID_HS,level,datetime) SELECT * FROM (SELECT '$nID' AS nID,'$hsID' AS id,'$data0[level]' AS level,now() AS now) AS tmp WHERE NOT EXISTS (SELECT ID_STT FROM game_in WHERE ID_N='$nID' AND level='$data0[level]') LIMIT 1";
                        mysqli_query($db, $query);
                        echo"<li><button onclick=\"location.href = 'http://localhost/www/TDUONG/game/$data0[domain]/'\"><a href='javascript:void(0)'>$data0[level]</a></button></li>";
                        $url_arr[] = $data0["domain"];
                        break;
                    }
//                    echo "<li><button ";
//                    if ($data0["ID_STT"]) {
//                        echo "style='background:#69b42e;'";
//                    }
//                    echo " onclick=\"location.href='http://localhost/www/TDUONG/game/$data0[domain]/'\"><a href='javascript:void(0)'>$data0[level]</a></button></li>";
//                    if(stripos($url,"/".$data0["domain"]."/") != false) {
//                        $check_url = true;
//                    }
//                    if (!isset($data0["ID_STT"])) {
//                        if(!$check_url) {
//                            header("location:http://localhost/www/TDUONG/game/");
//                            exit();
//                        }
//                        break;
//                    }
                }
            }
            $check_url = false;
            $n = count($url_arr);
            for($i = 0; $i < $n; $i++) {
                if(stripos($url, $url_arr[$i]."/") != false) {
                    $check_url = true;
                    break;
                }
            }
            if(!$check_url) {
                header("location:http://localhost/www/TDUONG/game/");
                exit();
            }
        ?>
    </ul>
</div>
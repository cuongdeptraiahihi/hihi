<?php
    ob_start();
    ini_set('max_execution_time', 600);
    require_once("/home/nginx/bgo.edu.vn/public_html/model/open_db.php");
    require_once("/home/nginx/bgo.edu.vn/public_html/model/model.php");

    $ngay = date("Y-m-d");
    $date=getdate(date("U"));
    $thu=$date["wday"]+1;
    if($thu==1) {
        $thu=8;
    }
    $query = "SELECT COUNT(ID_STT) AS dem FROM trogiang_diemdanh WHERE ngay='$ngay' AND trang_thai='-1'";
    $result = mysqli_query($db, $query);
    $data = mysqli_fetch_array($result);
    if($data['dem']==0) {
        $content="";
        $dd=array();
        $query1 = "SELECT ID,COUNT(ID_STT) AS dem2 FROM trogiang_lich WHERE thu ='$thu' GROUP BY ID";
        $result1 = mysqli_query($db, $query1);
        while ($data1=mysqli_fetch_assoc($result1)) {
            for($i=0;$i<$data1['dem2'];$i++) {
                $content.=",('$data1[ID]','$ngay','".get_buoi_diem_danh_trogiang($data1["ID"],$ngay,$i)."','-1')";
            }
            $dd[$data1["ID"]]=$data1["dem2"];
        }
        $query1 = "SELECT ID,COUNT(ID_STT) AS dem2 FROM lich_bu WHERE ngay='$ngay' GROUP BY ID";
        $result1 = mysqli_query($db, $query1);
        while ($data1=mysqli_fetch_assoc($result1)) {
            for($i=0;$i<$data1['dem2'];$i++) {
                if(isset($dd[$data1["ID"]])) {
                    $content.=",('$data1[ID]','$ngay','".get_buoi_diem_danh_trogiang($data1["ID"],$ngay,$dd[$data1["ID"]])."','-1')";
                } else {
                    $content.=",('$data1[ID]','$ngay','".get_buoi_diem_danh_trogiang($data1["ID"],$ngay,0)."','-1')";
                }
            }
        }
        if($content!="") {
            $content=substr($content,1);
            $query = "INSERT INTO trogiang_diemdanh(ID,ngay,buoi,trang_thai) VALUES $content";
            mysqli_query($db, $query);
        }
    }

    ob_end_flush();
    require_once("/home/nginx/bgo.edu.vn/public_html/model/close_db.php");
?>
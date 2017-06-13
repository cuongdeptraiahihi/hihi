<?php
ob_start();
session_start();
ini_set('max_execution_time', 900);
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");

if(isset($_GET["buoiID"]) && isset($_GET["lmID"])) {

    if(isset($_GET["how"])) {
        $how=$_GET["how"];
        $auto=false;
    } else {
        $how="bgo-luyenthi";
        $auto=true;
    }

    $buoiID=$_GET["buoiID"];
    $lmID=$_GET["lmID"];
    $monID=get_mon_of_lop($lmID);
    $ngay=get_ngay_buoikt($buoiID);
    $buoi=format_dateup($ngay);

//    if($how=="bgo-luyenthi" && !check_done_options($buoiID, "cap-nhat-diem-1", $lmID, $monID)) {
    if($how=="bgo-luyenthi") {
        $query0="SELECT n.ID_N,l.name,l.ID_D FROM nhom_de AS n 
        INNER JOIN de_thi AS d ON d.nhom=n.ID_N AND d.main='1' AND d.ID_LM='$lmID'
        INNER JOIN loai_de AS l ON l.ID_D=d.loai
        WHERE n.ID_LM='$lmID' AND n.object='$buoiID' AND n.type='kiem-tra'";
        $result0=mysqli_query($db, $query0);
        while($data0=mysqli_fetch_assoc($result0)) {
            $dem=0;
            $content=$con_cau="";
            $query = "SELECT d.ID_HS,d.made,d.diem,d.loai,d.de,i.ID_DE,l.ID_STT FROM diemkt AS d
            INNER JOIN de_thi AS i ON i.maso=d.made AND i.nhom='$data0[ID_N]' AND i.loai='$data0[ID_D]'
            LEFT JOIN hoc_sinh_luyen_de AS l ON l.ID_DE=i.ID_DE AND l.ID_HS=d.ID_HS AND l.ID_LM='$lmID'
            WHERE d.ID_BUOI='$buoiID' AND d.de='$data0[name]' AND d.ID_LM='$lmID' AND (d.loai='0' OR d.loai='3')";
            $result = mysqli_query($db, $query);
            while ($data = mysqli_fetch_assoc($result)) {

                if (isset($data["ID_STT"]) && is_numeric($data["ID_STT"])) {
                    continue;
                }

                $deID = $data["ID_DE"];
                $query2 = "SELECT u.ID_C,d.note,a.ID_DA FROM chuyende_diem AS d
                INNER JOIN de_noi_dung AS u ON u.ID_DE='$deID' AND u.sort=d.cau
                INNER JOIN de_cau_dap_an AS a ON a.ID_DE='$deID' AND a.sort=d.y
                INNER JOIN dap_an_ngan AS n ON n.ID_DA=a.ID_DA AND n.ID_C=u.ID_C
                WHERE d.ID_BUOI='$buoiID' AND d.ID_HS='$data[ID_HS]' AND d.ID_LM='$lmID'
                ORDER BY d.cau ASC";
                $result2 = mysqli_query($db, $query2);
                while ($data2 = mysqli_fetch_assoc($result2)) {
                    $con_cau .= ",('$data[ID_HS]','$data2[ID_C]','$data2[ID_DA]','0','0','$data2[note]','$deID')";
//                $query3="UPDATE hoc_sinh_cau SET ID_C='$data2[ID_C]' WHERE ID_HS='$data[ID_HS]' AND ID_DE='$deID' AND ID_C='d.cau'";
//                mysqli_query($db,$query3);
                }
                $content .= ",('$deID','$data[ID_HS]','lam-cuoi-tuan','$data[diem]','0',now(),'$lmID')";
                $dem++;
                if($dem == 300) {
                    $content = substr($content, 1);
                    $con_cau = substr($con_cau, 1);
                    $query2 = "INSERT INTO hoc_sinh_luyen_de(ID_DE,ID_HS,type_de,diem,time,datetime,ID_LM) VALUES $content";
                    mysqli_query($db, $query2);
                    $query2 = "INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,note,ID_DE) VALUES $con_cau";
                    mysqli_query($db, $query2);
                    $dem=0;
                }
            }
            if($dem != 0) {
                $content = substr($content, 1);
                $con_cau = substr($con_cau, 1);
                $query2 = "INSERT INTO hoc_sinh_luyen_de(ID_DE,ID_HS,type_de,diem,time,datetime,ID_LM) VALUES $content";
                mysqli_query($db, $query2);
                $query2 = "INSERT INTO hoc_sinh_cau(ID_HS,ID_C,ID_DA,num,time,note,ID_DE) VALUES $con_cau";
                mysqli_query($db, $query2);
            }

            $content="";
            $query="SELECT d.ID_HS FROM diemdanh_nghi AS d INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.de='$data0[name]' AND m.ID_LM=d.ID_LM WHERE d.ngay='$ngay' AND d.ID_LM='$lmID' AND d.ID_MON='$monID'";
            $result = mysqli_query($db, $query);
            while ($data = mysqli_fetch_assoc($result)) {
                $content .= ",('$data[ID_HS]','$data0[ID_N]')";
            }
            if($content != "") {
                $content = substr($content, 1);
                $query = "INSERT INTO hoc_sinh_special(ID_HS,ID_N) VALUES $content";
                mysqli_query($db, $query);
            }
        }
        add_options($buoiID,"cap-nhat-diem-1",$lmID,$monID);
    }

//    if($how=="luyenthi-bgo" && !check_done_options($buoiID, "cap-nhat-diem-2", $lmID, $monID)) {
    if($how=="luyenthi-bgo") {
//        $lop_mon_name = get_lop_mon_name($lmID);
//        $is_phat = get_is_phat($monID);
//        $count=insert_diem_count($buoiID,$lmID);

        $diemarr = array();
        $query0="SELECT ID_HS FROM diemkt WHERE ID_BUOI='$buoiID' AND ID_LM='$lmID'";
        $result0=mysqli_query($db, $query0);
        while($data0=mysqli_fetch_assoc($result0)) {
            $diemarr[$data0["ID_HS"]] = 1;
        }

        $content = $content2 = "";
        $query0="SELECT ID_N FROM nhom_de WHERE ID_LM='$lmID' AND object='$buoiID' AND type='kiem-tra'";
        $result0=mysqli_query($db, $query0);
        while($data0=mysqli_fetch_assoc($result0)) {
            $cau_arr = $form_arr = array();
            $num = 0;
            $result = get_cau_hoi_by_de_main($data0["ID_N"], $lmID);
            while ($data = mysqli_fetch_assoc($result)) {
                $cau_arr[$data["ID_C"]] = 0;
                $form_arr[$data["ID_C"]] = array(
                    "cdID" => $data["ID_CD"],
                    "daID" => $data["ID_DA"]
                );
                $num++;
            }

            $count = 0;
            $query = "SELECT l.ID_HS,l.ID_DE,l.diem,m.de,d.maso FROM hoc_sinh_luyen_de AS l
            INNER JOIN hocsinh_mon AS m ON m.ID_HS=l.ID_HS AND m.ID_LM='$lmID'
            INNER JOIN de_thi AS d ON d.ID_DE=l.ID_DE AND d.nhom='$data0[ID_N]'
            WHERE l.time!='0' AND l.ID_LM='$lmID'";
            $result = mysqli_query($db, $query);
            while ($data = mysqli_fetch_assoc($result)) {

                if (isset($diemarr[$data["ID_HS"]])) {
//                $query2="UPDATE diemkt SET diem='$data[diem]' WHERE ID_BUOI='$buoiID' AND ID_HS='$data[ID_HS]' AND ID_LM='$lmID'";
//                mysqli_query($db, $query2);
                    continue;
                }

                $socau = array();
                $cau_arr = array_fill_keys(array_keys($cau_arr), 0);
                $query2 = "SELECT d.ID_C,i.ID_DA,d.sort AS csort,n.sort AS dsort,i.note FROM de_noi_dung AS d
                INNER JOIN hoc_sinh_cau AS i ON i.ID_C=d.ID_C AND i.ID_DE='$data[ID_DE]' AND i.ID_HS='$data[ID_HS]'
                INNER JOIN de_cau_dap_an AS n ON n.ID_DA=i.ID_DA AND n.ID_DE='$data[ID_DE]'
                WHERE d.ID_DE='$data[ID_DE]'";
                $result2 = mysqli_query($db, $query2);
                echo "$data[ID_HS] - $num - " . mysqli_num_rows($result2) . "<br />";
                $diemtp = format_diem(10 / $num);
                while ($data2 = mysqli_fetch_assoc($result2)) {
                    $cau_arr[$data2["ID_C"]] = 1;
                    if(isset($data2["ID_DA"])) {
                        if ($data2["ID_DA"] == $form_arr[$data2["ID_C"]]["daID"]) {
                            $diem = $diemtp . "/" . $diemtp;
                        } else {
                            $diem = "0/" . $diemtp;
                        }
                        if(isset($form_arr[$data2["ID_C"]]["cdID"])) {
                            $content .= ",('$buoiID','".$form_arr[$data2["ID_C"]]["cdID"]."','$data[ID_HS]','$diem','$data2[csort]','$data2[dsort]','$data2[note]','$lmID')";
                        } else {
                            $content .= ",('$buoiID','0','$data[ID_HS]','$diem','$data2[csort]','$data2[dsort]','$data2[note]','$lmID')";
                        }
                    } else {
                        $diem = "0/" . $diemtp;
                        $content .= ",('$buoiID','0','$data[ID_HS]','$diem','$data2[csort]','0','','$lmID')";
                    }
                }
                $content2 .= ",('$buoiID','$data[ID_HS]','$data[diem]','1','$data[de]','0','$data[maso]','$lmID','0')";

                $count++;
                if($count == 200) {
                    $content = substr($content, 1);
                    $query2 = "INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,note,ID_LM) VALUES $content";
//                    mysqli_query($db, $query2);
                    $content2 = substr($content2, 1);
                    $query2 = "INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,made,ID_LM,more) VALUES $content2";
//                    mysqli_query($db, $query2);
                    $content = $content2 = "";
                    $count=0;
                }

            }
            if($count != 0) {
                $content = substr($content, 1);
                $query2 = "INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y,note,ID_LM) VALUES $content";
//                mysqli_query($db, $query2);
                $content2 = substr($content2, 1);
                $query2 = "INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,made,ID_LM,more) VALUES $content2";
//                mysqli_query($db, $query2);
            }
        }
        add_options($buoiID,"cap-nhat-diem-2",$lmID,$monID);
    }

//    if($auto) {
//        header("location:http://localhost/www/TDUONG/admin/cap-nhap-diem/$buoiID/$lmID/luyenthi-bgo/");
//        exit();
//    } else {
//        header("location:http://localhost/www/TDUONG/admin/nhap-diem2/");
//        exit();
//    }
}

ob_end_flush();
require_once("../model/close_db.php");
?>
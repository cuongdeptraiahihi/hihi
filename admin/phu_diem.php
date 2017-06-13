<?php
ob_start();
session_start();
ini_set('max_execution_time', 300);
require_once("../model/open_db.php");
require_once("../model/model.php");
require_once("access_admin.php");

if(isset($_GET["buoiID"]) && isset($_GET["lmID"])) {
    $buoiID=$_GET["buoiID"];
    $lmID=$_GET["lmID"];
    $monID=get_mon_of_lop($lmID);
    $is_phat=get_is_phat($monID);
    $mon_lop_name=get_lop_mon_name($lmID);
    $buoi=get_ngay_buoikt($buoiID);
    $ngay=date_create($buoi);
    if(check_isset_diem($buoiID,$lmID) && !check_done_options($buoiID,"phu-diem",$lmID,$monID)) {
        $content="";
        $query="SELECT m.ID_HS,m.de,m.date_in,d.diem,d.loai,d.note,n.ID_N,t.ID_STT,v.ID_VAO,r.ID_RA,u.ID_STT AS td FROM hocsinh_mon AS m 
			LEFT JOIN diemkt AS d ON d.ID_HS=m.ID_HS AND d.ID_BUOI='$buoiID' AND d.ID_LM='$lmID' 
			LEFT JOIN nghi_temp AS t ON t.ID_HS=m.ID_HS AND ((t.start<='$buoi' AND t.end>='$buoi') OR (t.start<='$buoi' AND t.end='0000-00-00')) AND t.ID_LM='$lmID'
			LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=m.ID_HS AND n.ID_LM='$lmID'
			LEFT JOIN tien_vao AS v ON v.ID_HS=m.ID_HS AND v.string='kiemtra_$lmID' AND v.object='$buoiID'
			LEFT JOIN tien_ra AS r ON r.ID_HS=m.ID_HS AND r.string='kiemtra_$lmID' AND r.object='$buoiID'
			LEFT JOIN thachdau AS u ON (u.ID_HS=m.ID_HS OR u.ID_HS2=m.ID_HS) AND u.buoi='$buoi' AND u.status='accept' AND u.ID_LM='$lmID'
			WHERE m.ID_LM='$lmID'";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            $date=date_create($data["date_in"]);
            if(!isset($data["diem"]) && $ngay>$date) {
                if(!isset($data["ID_N"])) {
                    if(isset($data["ID_STT"]) && is_numeric($data["ID_STT"])) {
//                        insert_diem_hs2($data["ID_HS"], $buoiID, 0, $data["de"], 4, 0, $lmID);
                        $content.=",('$buoiID','$data[ID_HS]','0','4','$data[de]','0','','$lmID','0')";
                    } else {
//                        insert_diem_hs2($data["ID_HS"], $buoiID, 0, $data["de"], 5, 0, $lmID);
                        $content.=",('$buoiID','$data[ID_HS]','0','5','$data[de]','0','','$lmID','0')";
                        if($is_phat && !isset($data["ID_VAO"]) && !isset($data["ID_RA"])) {
                            if (!check_binh_voi($data["ID_HS"], $buoiID, $lmID)) {
                                if (0 < 5.25 && isset($data["td"]) && is_numeric($data["td"])) {

                                } else {
                                    get_phat_diemkt($data["ID_HS"], 0, $data["de"], 5, 0, $lmID, $mon_lop_name, $buoiID, $buoi, true);
                                }
                            }
                        }
                    }
                } else {
//                    insert_diem_hs2($data["ID_HS"], $buoiID, 0, $data["de"], 2, 0, $lmID);
                    $content.=",('$buoiID','$data[ID_HS]','0','2','$data[de]','0','','$lmID','0')";
                }
            } else if(isset($data["diem"])) {
                if($data["loai"]==0 && ($data["de"] == "Y" || $data["de"] == "B")) {
                    if($data["diem"] >= 9 && $data["diem"] < 10) {
                        add_log($data["ID_HS"],"Thưởng thẻ miễn phạt được điểm $data[diem] đề $data[de]","the-mien-phat");
                    } else if($data["diem"] == 10) {
                        if($data["de"] = "Y") {
                            $new_de = "B";
                        } else {
                            $new_de = "G";
                        }
                        update_de_hs($data["ID_HS"], $new_de, $lmID);
                        add_log($data["ID_HS"],"Thưởng thẻ miễn phạt được điểm $data[diem] đề $data[de] và lên đề","the-mien-phat");
                        add_log($data["ID_HS"],"Thưởng thẻ miễn phạt được điểm $data[diem] đề $data[de] và lên đề","the-mien-phat");
                        add_log($data["ID_HS"],"Thưởng thẻ miễn phạt được điểm $data[diem] đề $data[de] và lên đề","the-mien-phat");
                        add_log($data["ID_HS"],"Thưởng thẻ miễn phạt được điểm $data[diem] đề $data[de] và lên đề","the-mien-phat");
                        add_log($data["ID_HS"],"Thưởng thẻ miễn phạt được điểm $data[diem] đề $data[de] và lên đề","the-mien-phat");
                        add_options3(date("Y-m"),"len-de-mid",$data["ID_HS"],$lmID);
                        add_thong_bao_hs($data["ID_HS"],1,"Bạn đã được lên đề $new_de và thưởng 5 thẻ miễn phạt vì được điểm $data[diem] đề $data[de]","nhay-de",$lmID);
                    }
                }
                if($is_phat && !isset($data["ID_VAO"]) && !isset($data["ID_RA"])) {
                    if (!check_binh_voi($data["ID_HS"], $buoiID, $lmID)) {
                        if ($data["diem"] < 5.25 && isset($data["td"]) && is_numeric($data["td"])) {

                        } else {
                            get_phat_diemkt($data["ID_HS"], $data["diem"], $data["de"], $data["loai"], $data["note"], $lmID, $mon_lop_name, $buoiID, $buoi, true);
                        }
                    }
                }
            }
        }
        $content=substr($content,1);
        $query="INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note,made,ID_LM,more) VALUES $content";
        mysqli_query($db, $query);
        add_options($buoiID,"phu-diem",$lmID,$monID);
    }
    header("location:http://localhost/www/TDUONG/admin/nhap-diem2/");
    exit();
}

ob_end_flush();
require_once("../model/close_db.php");
?>
<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	
	if(isset($_GET["buoiID"]) && isset($_GET["lmID"])) {
		$buoiID=$_GET["buoiID"];
		$lmID=$_GET["lmID"];
        $monID=get_mon_of_lop($lmID);
		if(check_done_options($buoiID, "phu-diem",$lmID,$monID)) {
		    $de_arr=array("Y","B","G");
		    for($i=0;$i<count($de_arr);$i++) {
                $query2 = "SELECT AVG(d.diem) AS diemtb FROM diemkt AS d 
			    INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID' 
			    WHERE d.ID_BUOI='$buoiID' AND d.de='".$de_arr[$i]."' AND d.loai IN ('0','1')";
                $result2 = mysqli_query($db, $query2);
                $data2 = mysqli_fetch_assoc($result2);
                $diemtb = number_format((float)$data2["diemtb"], 2, '.', '');

                $query3 = "UPDATE diemkt_tb SET diemtb='$diemtb' WHERE ID_BUOI='$buoiID' AND detb='".$de_arr[$i]."' AND ID_LM='$lmID'";
                $result3 = mysqli_query($db, $query3);
                if(mysqli_affected_rows($db) == 0) {
                    $query3 = "INSERT INTO diemkt_tb(ID_BUOI,diemtb,detb,ID_LM) SELECT * FROM (SELECT '$buoiID' AS buoi,'$diemtb' AS diem,'".$de_arr[$i]."' AS de,'$lmID' AS lm) AS tmp WHERE NOT EXISTS (SELECT ID_BUOI,detb FROM diemkt_tb WHERE ID_BUOI='$buoiID' AND detb='".$de_arr[$i]."' AND ID_LM='$lmID') LIMIT 1";
                    mysqli_query($db, $query3);
                }
            }
			header("location:http://localhost/www/TDUONG/thaygiao/nhap-diem2/");
			exit();
		}
	}
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>
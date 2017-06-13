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
			$query2="SELECT AVG(d.diem) AS diemtb FROM diemkt AS d 
			INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID' 
			WHERE d.ID_BUOI='$buoiID' AND d.de='B' AND d.loai IN ('0','1')";
			$result2=mysqli_query($db,$query2);
			$data2=mysqli_fetch_assoc($result2);
			$diemtbB=number_format((float)$data2["diemtb"], 2, '.', '');
			$query2="SELECT AVG(d.diem) AS diemtb FROM diemkt AS d 
			INNER JOIN hocsinh_mon AS m ON m.ID_HS=d.ID_HS AND m.ID_LM='$lmID' 
			WHERE d.ID_BUOI='$buoiID' AND d.de='G' AND d.loai IN ('0','1')";
			$result2=mysqli_query($db,$query2);
			$data2=mysqli_fetch_assoc($result2);
			$diemtbG=number_format((float)$data2["diemtb"], 2, '.', '');
			
			if(!check_isset_diemtb($buoiID,$lmID)) {
				$query3="INSERT INTO diemkt_tb(ID_BUOI,diemtb,detb,ID_LM) SELECT * FROM (SELECT '$buoiID' AS buoi,'$diemtbB' AS diem,'B' AS de,'$lmID' AS lm) AS tmp WHERE NOT EXISTS (SELECT ID_BUOI,detb FROM diemkt_tb WHERE ID_BUOI='$buoiID' AND detb='B' AND ID_LM='$lmID') LIMIT 1";
				mysqli_query($db,$query3);
				$query4="INSERT INTO diemkt_tb(ID_BUOI,diemtb,detb,ID_LM) SELECT * FROM (SELECT '$buoiID' AS buoi,'$diemtbG' AS diem,'G' AS de,'$lmID' AS lm) AS tmp WHERE NOT EXISTS (SELECT ID_BUOI,detb FROM diemkt_tb WHERE ID_BUOI='$buoiID' AND detb='G' AND ID_LM='$lmID') LIMIT 1";
				mysqli_query($db,$query4);
			} else {
				$query3="UPDATE diemkt_tb SET diemtb='$diemtbB' WHERE ID_BUOI='$buoiID' AND detb='B' AND ID_LM='$lmID'";
				mysqli_query($db,$query3);
				$query4="UPDATE diemkt_tb SET diemtb='$diemtbG' WHERE ID_BUOI='$buoiID' AND detb='G' AND ID_LM='$lmID'";
				mysqli_query($db,$query4);
			}
			header("location:http://localhost/www/TDUONG/thaygiao/nhap-diem/");
			exit();
		}
	}
	
	ob_end_flush();
	require_once("../model/close_db.php");
?>
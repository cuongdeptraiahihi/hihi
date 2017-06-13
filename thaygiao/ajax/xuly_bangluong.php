<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	$monID=$_SESSION["mon"];
	
	if(isset($_POST["data"]) && isset($_POST["note"])) {
		$data=base64_decode($_POST["data"]);
		$data=json_decode($data, true);
		$note=$_POST["note"];
		
		$n=count($data)-1;
		$monID2=$data[$n]["monID"];
		if($monID2!=$monID) {
			echo"Dữ liệu không chính xác!";
		} else {
			for($i=0;$i<$n;$i++) {
				$sttID=$data[$i]["sttID"];
				if($sttID>=0 && is_numeric($sttID)) {
					$datetime=$data[$i]["datetime"];
					$tien=str_replace( ".", "",$data[$i]["tien"]);
					$tien=str_replace( "đ", "",$tien);
                    $tien=str_replace( "d", "",$tien);
					if($datetime=="X" || $tien=="X" || $datetime=="x" || $tien=="x") {
						delete_thanhtoan($sttID);
					} else {
					    if(is_numeric($tien) && $tien>0) {
                            if ($sttID == 0) {
                                add_thanhtoan($tien, format_date_o($datetime), get_admin_id_mon($monID), $monID, $note);
                            } else {
                                update_thanhtoan($sttID, $tien, format_date_o($datetime));
                            }
                        }
					}
				}
			}
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
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

	if(isset($_POST["ajax_data"])) {
	    $ajax=json_decode($_POST["ajax_data"],true);
        $n = count($ajax) - 1;
        $lmID = $ajax[$n]["lmID"];
        $monID = $ajax[$n]["monID"];
        $date = $ajax[$n]["date"];
        $result_arr = array();
        for($i = 0; $i < $n; $i++) {
            $query="SELECT content FROM options WHERE content!='0' AND content!='0k' AND note2='".$ajax[$i]["hsID"]."' AND type='edit-tien-hoc-$lmID' AND note='$date'";
            $result=mysqli_query($db, $query);
            if(mysqli_num_rows($result) == 0) {
                $me = check_chua_dong_hoc($ajax[$i]["hsID"], $ajax[$i]["date_in"], $lmID, $monID, $date, $ajax[$i]["old"]);
                if (!is_numeric($me)) {
                    $string = $me;
                } else {
                    $string = format_price($me);
                }
            } else {
                $data = mysqli_fetch_assoc($result);
                $string = format_price($data["content"]*1000) . " <strong>(bắt buộc)</strong>";
            }
            $result_arr[$ajax[$i]["index"]] = array(
                "tien" => $string
            );
        }
        if(count($result_arr) != 0) {
            echo json_encode($result_arr);
        } else {
            echo "none";
        }
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
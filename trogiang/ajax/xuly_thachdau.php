<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
	
	if (isset($_POST["tdID0"])) {
		$tdID=$_POST["tdID0"];
		$result=callback_thachdau($tdID);
		$data=mysqli_fetch_assoc($result);
        $buoiID=get_id_buoikt($data["buoi"],get_mon_of_lop($lmID));
		$temp=get_diem_hs3($data["ID_HS"],$buoiID,$lmID);
		$temp2=get_diem_hs3($data["ID_HS2"],$buoiID,$lmID);
		echo json_encode(
			array(
				"diem1" => $temp[0]." (".get_cmt_diem_loai2($temp[1]).")",
				"diem2" => $temp2[0]." (".get_cmt_diem_loai2($temp2[1]).")"
			)
		);
	}
	
	if (isset($_POST["tdID1"])) {
		$tdID=$_POST["tdID1"];
		del_thach_dau($tdID,$lmID);
	}
	
	if (isset($_POST["hsID2"])) {
		$hsID=$_POST["hsID2"];
		$dem=0;
		$result=get_thachdau_hs($hsID,$lmID);


		while($data=mysqli_fetch_assoc($result)) {
			$result1=get_hs_short_detail2($data["ID_HS"]);
			$data1=mysqli_fetch_assoc($result1);
			$result2=get_hs_short_detail2($data["ID_HS2"]);
			$data2=mysqli_fetch_assoc($result2);
			if($data["ketqua"]==$data["ID_HS"]) {
				$kq1="<p class='see-kq win' style='right:10px;'>WIN</p>";
				$kq2="<p class='see-kq lose' style='left:10px;'>LOSE</p>";
			} else if($data["ketqua"]==$data["ID_HS2"]){
				$kq1="<p class='see-kq lose' style='right:10px;'>LOSE</p>";
				$kq2="<p class='see-kq win' style='left:10px;'>WIN</p>";
			} else if($data["ketqua"]=="X"){
				$kq1="<p class='see-kq draw' style='right:10px;'>DRAW</p>";
				$kq2="<p class='see-kq draw' style='left:10px;'>DRAW</p>";
			} else {
				$kq1="";
				$kq2="";
			}
			if($dem%2!=0) {
				echo"<tr style='background:#D1DBBD'>";
			} else {
				echo"<tr>";
			}
			echo"<td><span>".($dem+1)."</span></td>
                <td><span>".format_dateup($data["buoi"])."</span></td>";
           		echo"<td><span>".$data1["fullname"]."<br />".$data1["cmt"]."<br /><span class='ketqua kq-1'></span></span>$kq1</td>";
                echo"<td><span>".$data2["fullname"]."<br />".$data2["cmt"]."<br /><span class='ketqua kq-2'></span></span>$kq2</td>
               	<td><span>".$data["chap"]." điểm</span></td>
				<td><span>";
				if($data["status"]=="pending") {
					echo"Đang chờ chấp nhận";
				} else if($data["status"]=="accept") {
					echo"Đang chờ kết quả";
				} else {
					echo"Hoàn thành";
				}
				echo"</span></td>
              	<td>";
					if($data["status"]=="done") {
                      	echo"<input class='submit view' data-tdID='$data[ID_STT]' type='submit' value='Xem kết quả' />";
                	}
					/*if($data["ketqua"]=="Z" && $data["status"]=="pending") {
						echo"<a href='javascript:void(0)' class='delete-pen' data-hsID='".$data["ID_HS"]."' data-tdID='".$data["ID_STT"]."'><i class='fa fa-trash' title='Xóa'></i></a><br /><span>Đang chờ</span>";
					} else if($data["ketqua"]=="Z" && $data["status"]=="accept") {
						echo"<a href='javascript:void(0)' class='delete-acc' data-tdID='".$data["ID_STT"]."'><i class='fa fa-trash' title='Xóa'></i></a><br /><span>Đã chấp nhận</span>";
					} else {
                 		echo"<a href='javascript:void(0)' class='view' data-tdID='".$data["ID_STT"]."'><i class='fa fa-eye' title='Xem kết quả'></i></a>";
					}*/
				echo"</td>
        	</tr>";
			$dem++;
		}
	}
	
	if (isset($_POST["tdID2"])) {
		$tdID=$_POST["tdID2"];
		$result=callback_thachdau($tdID);
		$data=mysqli_fetch_assoc($result);
		$diem1=get_diem_hs2($data["ID_HS"],$data["buoi"],$lmID);
		$diem2=get_diem_hs2($data["ID_HS2"],$data["buoi"],$lmID);
		echo json_encode(
			array(
				"diem1" => $diem1,
				"diem2" => $diem2
			)
		);
	}

	if(isset($_POST["sttID"])) {
	    $sttID=$_POST["sttID"];
        $query="SELECT * FROM log WHERE ID_STT='$sttID'";
        $result=mysqli_query($db,$query);
        $data=mysqli_fetch_assoc($result);
        if(stripos($data["content"],"(Đã nhận)")===false) {
            $string=$data["content"]." (Đã nhận)";
            $query2="UPDATE log SET content='$string' WHERE ID_STT='$sttID'";
            mysqli_query($db,$query2);
        }
    }

    if(isset($_POST["tdID3"])) {
        $tdID=$_POST["tdID3"];
        again_thach_dau($tdID,$lmID);
    }
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
    require("../../model/model.php");
    require("../access_admin.php");
    $lmID=$_SESSION["lmID"];
	
	if (isset($_POST["nsID"]) && isset($_POST["hsID"])) {
		$nsID=$_POST["nsID"];
		$hsID=$_POST["hsID"];
		delete_ngoisao_ad($hsID, $nsID);
	}
	
	if (isset($_POST["nsID0"])) {
		$nsID=$_POST["nsID0"];
		$result=callback_ngoisao($nsID);
		$data=mysqli_fetch_assoc($result);
		$diem=get_diem_hs2($data["ID_HS"],$data["buoi"],$lmID);
		echo $diem;
	}
	
	if (isset($_POST["hsID2"])) {
		$hsID=$_POST["hsID2"];
		$dem=0;
		$result=get_ngoisao_hs($hsID, $lmID);
		while($data=mysqli_fetch_assoc($result)) {
			$result1=get_hs_short_detail2($data["ID_HS"]);
			$data1=mysqli_fetch_assoc($result1);
			if($dem%2!=0) {
				echo"<tr style='background:#D1DBBD'>";
			} else {
				echo"<tr>";
			}
			echo"<td><span>".($dem+1)."</span></td>
            	<td><span>#".$data["ID_STT"]."</span></td>
                <td><span>".format_dateup($data["buoi"])."</span></td>
				<td><span>".$data1["fullname"]."<br />".$data1["cmt"]."<br /><span class='ketqua'></span></span></td>
               	<td><span>";
				if($data["ketqua"]==1) {
					echo"Thắng";
				} else if($data["ketqua"]==0){
					echo"Thua";
				} else {
					echo"Đang chờ";
				}
				echo"</span></td>
              	<td>";
					if($data["ketqua"]=="Z" && $data["status"]=="pending") {
						echo"<a href='javascript:void(0)' class='delete' data-hsID='".$data["ID_HS"]."' data-nsID='".$data["ID_STT"]."'><i class='fa fa-trash' title='Xóa'></i></a>";
					} else {
                 		echo"<a href='javascript:void(0)' class='view' data-nsID='".$data["ID_STT"]."'><i class='fa fa-eye' title='Xem kết quả'></i></a>";
					}
				echo"</td>
        	</tr>";
			$dem++;
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
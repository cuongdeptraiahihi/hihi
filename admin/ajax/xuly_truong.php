<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
require("../../model/model.php");
require("../access_admin.php");
	
	if (isset($_POST["quanID"]) && isset($_POST["quan"])) {
		$quanID=$_POST["quanID"];
		$quan=$_POST["quan"];
		edit_quanhuyen($quanID, $quan);
		echo $quan;
	}
	
	if (isset($_POST["truongID"]) && isset($_POST["truong"])) {
		$truongID=$_POST["truongID"];
		$truong=$_POST["truong"];
		edit_truong($truongID, $truong);
		echo $truong;
	}
	
	if(isset($_POST["tpID2"])) {
		$tpID=$_POST["tpID2"];
		$result=get_quan($tpID);
		while($data=mysqli_fetch_assoc($result)) {
			echo"<option value='$data[ID_QH]'>$data[quanhuyen]</option>";
		}
	}
	
	if(isset($_POST["quanID2"])) {
		$quanID=$_POST["quanID2"];
		$result=get_truong($quanID);
		while($data=mysqli_fetch_assoc($result)) {
			echo"<option value='$data[ID_T]'>$data[name]</option>";
		}
	}
	
	if(isset($_POST["truongID2"])) {
		$truongID=$_POST["truongID2"];
		$quanID=get_quan_truong($truongID);
		$tpID=get_tp_quan($quanID);
		echo"<select class='input' style='height:auto;width:100%;margin-bottom:10px;' id='add-quan'>
     		<option value='0'>Chọn Quận/Huyện</option>";
			$result0=get_quan($tpID);
			while($data0=mysqli_fetch_assoc($result0)) {
				echo"<option value='$data0[ID_QH]' ";if($quanID==$data0["ID_QH"]){echo"selected='selected'";} echo">$data0[quanhuyen]</option>";
			}
		echo"</select>
		<select class='input' style='height:auto;width:100%;' id='add-truong'>
          	<option value='0'>Chọn Trường</option>";
			$result=get_truong($quanID);
			while($data=mysqli_fetch_assoc($result)) {
				echo"<option value='$data[ID_T]' ";if($truongID==$data["ID_T"]){echo"selected='selected'";} echo">$data[name]</option>";
			}
		echo"</select>";
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
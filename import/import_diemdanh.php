
<?php 
	ini_set('max_execution_time', 600);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	
	$file=NULL;
	
	$ca_arr=array();
	$query0="SELECT DISTINCT c.cum,c.thu,c.ID_CA FROM cahoc AS c INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.lop='1' AND g.ID_MON='1' ORDER BY thu ASC";
	$result0=mysqli_query($db,$query0);
	while($data0=mysqli_fetch_assoc($result0)) {
		$ca_arr[$data0["thu"]]=array(
			"cum" => $data0["cum"],
			"caID" => $data0["ID_CA"]
		);
		//echo $data0["thu"]."-".$data0["cum"]."<br />";
	}
	
	$hs_ca=array();
	$query1="SELECT h.ID_HS FROM hocsinh_mon AS h ORDER BY h.ID_HS ASC";
	$result1=mysqli_query($db,$query1);
	while($data1=mysqli_fetch_assoc($result1)) {
		$hs_ca[$data1["ID_HS"]]=array();
		$query2="SELECT d.ID_STT,d.ID_CUM FROM diemdanh_buoi AS d INNER JOIN ca_hientai AS t ON t.ID_CA=d.ID_CA AND t.ID_HS='$data1[ID_HS]' AND t.cum=d.cum ORDER BY d.date ASC";
		$result2=mysqli_query($db,$query2);
		while($data2=mysqli_fetch_assoc($result2)) {
			$hs_ca[$data1["ID_HS"]][$data2["ID_CUM"]]=$data2["ID_STT"];
			//echo $data1["ID_HS"]."-".$hs_ca[$data1["ID_HS"]][$data2["ID_CUM"]]."<br />";
		}
	}

	if(isset($_POST["ok"])) {
		if($_FILES["data"]["error"]>0) {
			// N/A
		} else {
			$file=$_FILES["data"]["name"];
		}
		
		if($file) {
			
			move_uploaded_file($_FILES["data"]["tmp_name"],"../import/".$_FILES["data"]["name"]);

			include ("../admin/include/PHPExcel/IOFactory.php");
			$html="<table border='1'>";
			 $objPHPExcel = PHPExcel_IOFactory::load("".$file);  

			$cumID = 25;
			$num = 12;
			$rows = 242;
			
			$cumID2_temp=$cumID;
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {
				 $highestRow = $worksheet->getHighestRow();
				 
				 $html .= "<tr><td></td>";
				 for ($i = 1;$i <= $num; $i++) {
				 	 $ngay = $worksheet->getCellByColumnAndRow($i, 1)->getValue();
					 if(substr_count($ngay,"/")==1) {
						 $temp = explode("/",$ngay);
						 $temp2 = explode("-",$temp[0]);
						 
						 $thang1=format_month_db($temp[1]);
						 $ngay1=format_month_db($temp2[0]);
						 $thang2=format_month_db($temp[1]);
						 $ngay2=format_month_db($temp2[1]);
					 } else {
					 	$temp = explode("-",$ngay);
						$temp2 = explode("/",$temp[0]);
						$temp3 = explode("/",$temp[1]);
						
						$thang1=format_month_db($temp2[1]);
						 $ngay1=format_month_db($temp2[0]);
						 $thang2=format_month_db($temp3[1]);
						 $ngay2=format_month_db($temp3[0]);
					 }
					 $thu1=date("w", strtotime("2016-$thang1-$ngay1"))+1;
					 $thu2=date("w", strtotime("2016-$thang2-$ngay2"))+1;
					 $html .= "<td>2016-$thang1-$ngay1 ($thu1-".$ca_arr[$thu1]["cum"].")<br />2016-$thang2-$ngay2 ($thu2-".$ca_arr[$thu2]["cum"].")</td>";
					 $query2 = "INSERT INTO diemdanh_buoi(ID_CUM,ID_CD,ID_CA,cum,date,ID_LOP,ID_MON)
													value('$cumID','0','".$ca_arr[$thu1]["caID"]."','".$ca_arr[$thu1]["cum"]."','2016-$thang1-$ngay1','1','1')";

					//mysqli_query($db,$query2);
					$query2 = "INSERT INTO diemdanh_buoi(ID_CUM,ID_CD,ID_CA,cum,date,ID_LOP,ID_MON)
													value('$cumID','0','".$ca_arr[$thu2]["caID"]."','".$ca_arr[$thu2]["cum"]."','2016-$thang2-$ngay2','1','1')";

					//mysqli_query($db,$query2);
					$cumID++;
				 }
				 $html .= "</tr>";
					 
				 for ($row = 2; $row <= $rows; $row++) {

					 $html .= "<tr>";

					 $hsID = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					 $html .= "<td>$hsID</td>";
						
					$cumID2=$cumID2_temp;	
					 for ($i = 1; $i <= $num; $i++) {
						 $loai = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
						 if (is_numeric($loai)) {
							 switch($loai) {
								case 0:
									$is_hoc=0;
									$is_tinh=0;
									break;
								case 1:
									$is_hoc=1;
									$is_tinh=0;
									break;
								case 2:
									$is_hoc=1;
									$is_tinh=1;
									break;
							 }
							 if(isset($hs_ca[$hsID][$cumID2])) {
								 $query = "INSERT INTO diemdanh_toan(ID_DD,ID_HS,ca_check,is_hoc,is_tinh)
																value('".$hs_ca[$hsID][$cumID2]."','$hsID','1','$is_hoc','$is_tinh')";
									
									
								 mysqli_query($db,$query);
								 $echo = $hs_ca[$hsID][$cumID2];
							 } else {
							 	$echo="";
								$query = "INSERT INTO diemdanh_toan(ID_DD,ID_HS,ca_check,is_hoc,is_tinh)
																value('".$hs_ca[1][$cumID2]."','$hsID','1','$is_hoc','$is_tinh')";
									
									
								 mysqli_query($db,$query);
							 }
						 } 
						 $html .= "<td>$loai</td>";
						 $cumID2++;
					 }

					 $html .= "</tr>";

				 }
			 }  
			 $html .= '</table>';  
			 echo $html;  
			 echo '<br />Data Inserted '.$highestRow;  
		}
	}
	require_once("../model/close_db.php");
?>

<form action="import_diemdanh.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

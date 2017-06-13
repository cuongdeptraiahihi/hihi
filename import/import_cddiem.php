
<?php 
	ini_set('max_execution_time', 500);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	
	$cau_me=array();
	$cau_y = array("a","b","c","d");
	for($i=0;$i<count($cau_y);$i++) {
		$cau_me[$cau_y[$i]]=$i+1;
	}
	
	$file=NULL;
	$lido=array();
	$result3=get_all_lido2();
	while($data3=mysqli_fetch_assoc($result3)) {
		$lido["$data3[string]"]=$data3["ID_LD"];
	}

	$chuyende=array();
	$result1=get_all_chuyende(1,1);
	while($data1=mysqli_fetch_assoc($result1)) {
		$chuyende[$data1["maso"]]=$data1["ID_CD"];
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

			$buoiID = 44;
			$num = 118;
			$rows = 13;

			$dem=1;
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {
				 if($dem==2) {
					 $highestRow = $worksheet->getHighestRow();
					 $html .= "<tr>";
					 $html .= "<td></td><td></td><td></td>";
					 $total = array();
					 for ($i = 3; $i <= $num; $i++) {
						 $id = $worksheet->getCellByColumnAndRow($i, 3)->getValue();
						 $html .= '<td>' . $id . '</td>';
						 $total[$id]=0;
					 }

					 $html .= "</tr>";
					 for ($row = 4; $row <= $rows; $row++) {

						 $html .= "<tr>";

						 $cdID = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						 $cdID = $chuyende[$cdID];

						 $diem_tong = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						 $cau = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
						 $y = 0;
						 if (strpos($cau, "-", 0) === false) {

						 } else {
							 $temp = explode("-", $cau);
							 $cau = $temp[0];
							 $y = $cau_me["$temp[1]"];
						 }

						 $html .= '<td>' . $cdID . '</td>';
						 $html .= '<td>' . $diem_tong . '</td>';
						 $html .= '<td>' . $cau . '-' . $y . '</td>';

						 for ($i = 3; $i <= $num; $i++) {
							 $hsID = $worksheet->getCellByColumnAndRow($i, 3)->getValue();
							 $diem = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
							 if (is_numeric($diem)) {
								 $total[$hsID]+=$diem;

								 $diem = $diem . "/" . $diem_tong;
								 $html .= '<td>' . $diem . '</td>';

								 $query = "INSERT INTO chuyende_diem(ID_BUOI,ID_CD,ID_HS,diem,cau,y)
							   								value('$buoiID','$cdID','$hsID','$diem','$cau','$y')";

								 mysqli_query($db,$query);
							 } else {
								 $html .= '<td></td>';
							 }
						 }

						 $html .= "</tr>";
					 }

					 $html.= '<tr><td></td><td></td><td></td>';
					 foreach ($total as $diem_tong) {
						 $html.= '<td>'.$diem_tong.'</td>';
					 }
					 $html.= '</tr>';

				 }
				$dem++;
			 }  
			 $html .= '</table>';  
			 echo $html;  
			 echo '<br />Data Inserted '.$highestRow;  
		}
	}
	require_once("../model/close_db.php");
?>

<form action="import_cddiem.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

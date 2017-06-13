
<?php 
	ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	
	$file=NULL;
	$lido=array();
	$result3=get_all_lido2();
	while($data3=mysqli_fetch_assoc($result3)) {
		$lido["$data3[string]"]=$data3["ID_LD"];
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
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {  
				  $highestRow = $worksheet->getHighestRow(); 
				  
				  /*for($i=1;$i<=39;$i++) {
					  $html.="<tr>"; 
				  	$ngay = $worksheet->getCellByColumnAndRow($i, 1)->getValue();
					if(PHPExcel_Shared_Date::isDateTime($worksheet->getCellByColumnAndRow($i, 1))) {
							 $ngay = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($ngay)); 
						}
					$html.="<td>$ngay</td>"; 
					$html.="</tr>";
					
					$query4="INSERT INTO buoikt(ngay)
											value('$ngay')";
					mysqli_query($db,$query4); 
				  }*/
				   
				  for ($row=2; $row<=23; $row++)
				  {  
					   $html.="<tr>";
					   $maso = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
					   $id=get_hs_id($maso);
					   $de=$worksheet->getCellByColumnAndRow(6, $row)->getValue();
update_de_hs($id,$de,2);
					   //$de =  $worksheet->getCellByColumnAndRow(11, $row)->getValue();  
					   $html.= '<td>'.$id.'</td>';  
					   
					   $buoiID=48;$dem=0;$total=0;
					   for($i=2;$i<=5;$i++) {
						   $note=0;
					   		$diem=$worksheet->getCellByColumnAndRow($i, $row)->getValue();
							if(is_numeric($diem)) {
								//$html .= '<td>'.$diem.'</td>'; 
								if($diem<0) {
									$diem=-$diem;
									$loai=1;
								} else {
									$diem=$diem;
									$loai=0;
								}
							} else {
								$string=unicode_convert($diem);
								//$html .= '<td>'.$string.'</td>'; 
								if($string=="nghi-hoc") {
									$diem=0;
									$loai=2;
								} else if($string=="khong-di-thi") {
									$diem=0;
									$loai=5;
								} else if($string=="x" || $string=="nghi-co-phep" || $string=="chua-hoc" || $string=="mat-bai") {
									$diem="X";
									$loai=4;
								} else {
									$diem=0;
									$loai=3;
									//$note=$lido[$string];
								}
							}
							
							if(is_numeric($diem)) {
								$total+=$diem;
								$dem++;
							}
						   $diem=format_diem($diem);
							$query="INSERT INTO diemkt_anh(ID_BUOI,ID_HS,diem,loai,de,note)
														value('$buoiID','$id','$diem','$loai','$de','$note')";
							//mysqli_query($db,$query);
							
							$html .= '<td>'.$diem.'</td>'; 
							$buoiID++;
					   }
						
					    $html .= '<td>'.$de.'</td>';
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

<form action="import_diem.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>


<?php 
	ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	$file=NULL;
	$now = date("Y-m-d");
	if(isset($_POST["ok"])) {
		if($_FILES["data"]["error"]>0) {
			// N/A
		} else {
			$file=$_FILES["data"]["name"];
		}
		
		if($file) {
			
			/*$fieldseparator = ","; 
			$lineseparator = "\n";*/
			move_uploaded_file($_FILES["data"]["tmp_name"],"../import/".$_FILES["data"]["name"]);	
			
			/*$query2="SELECT ID_HS FROM hocsinh ORDER BY ID_HS DESC LIMIT 1";
			$result2=mysqli_query($db,$query2);
			if(mysqli_num_rows($result2)==0) {
				$stt=1;
			} else {
				$data2=mysqli_fetch_assoc($result2);
				$stt=$data2["ID_HS"]+1;
			}*/
			
			include ("../admin/include/PHPExcel/IOFactory.php");  
			 $html="<table border='1'>";  
			 $objPHPExcel = PHPExcel_IOFactory::load("".$file);  
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {  
				  $highestRow = $worksheet->getHighestRow();  
				  for ($row=2; $row<=232; $row++)  
				  {  
					   $html.="<tr>";  
					   $hsID=$worksheet->getCellByColumnAndRow(0, $row)->getValue(); 
					   $fullname = $worksheet->getCellByColumnAndRow(1, $row)->getValue();  
					   $birth = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); 
					   if(PHPExcel_Shared_Date::isDateTime($worksheet->getCellByColumnAndRow(3, $row))) {
							 $birth = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birth)); 
							 $birth = str_ireplace("1995","1999",$birth);
						}
					   $facebook = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
					   $truong = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
					   $sdt = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					   $sdt_bo = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					   $sdt_me = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					   $gender = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					   $de = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					   if($birth==$now) {
					   	$birth="0000-00-00";
					   }  
					   $query="INSERT INTO hocsinh(ID_HS,cmt,password,fullname,namestring,avata,birth,gender,facebook,truong,sdt,sdt_bo,sdt_me,taikhoan,ID_MAU,lop) value ('$hsID','','".md5("123456")."','$fullname','".unicode_convert($fullname)."','avata.jpg','$birth','$gender','$facebook','$truong','$sdt','$sdt_bo','$sdt_me','0','1','1')";  
					   //mysqli_query($db,$query);
					   $query3="INSERT INTO hocsinh_mon(ID_HS,de,ID_MON)
													value('$hsID','$de','1')";
					   //mysqli_query($db,$query3);
					   
					   $query4="INSERT INTO bang_xep_hang(ID_HS,diemtb,ID_MON)
													value('$hsID','0','1')";
					   //mysqli_query($db,$query4);
					   
					   $html.= '<td>'.$hsID.'</td>';  
					   $html.= '<td>'.$fullname.'</td>';  
					   $html .= '<td>'.$birth.'</td>'; 
					   $html .= '<td>'.$gender.'</td>'; 
					   $html .= '<td>'.$facebook.'</td>';
					   $html .= '<td>'.$truong.'</td>';
					   $html .= '<td>'.$sdt.'</td>'; 
					   $html .= '<td>'.$sdt_bo.'</td>'; 
					   $html .= '<td>'.$sdt_me.'</td>';  
					   $html .= '<td>'.$de.'</td>';    
					   $html .= "</tr>";  
					   //$stt++;
				  }  
			 }  
			 $html .= '</table>';  
			 echo $html;  
			 echo '<br />Data Inserted '.$highestRow;  
		}
	}
	require_once("../model/close_db.php");
?>

<form action="import_hocsinh.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

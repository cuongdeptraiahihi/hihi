
<?php 
	ini_set('max_execution_time', 500);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	$file=NULL;
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
				  for ($row=2; $row<=232; $row++)  
				  {  
					   $html.="<tr>";  
					   	$hsID=$worksheet->getCellByColumnAndRow(0, $row)->getValue();
						$html .= '<td>'.$hsID.'</td>';
						for($i=1;$i<=8;$i++) {
							$date=$worksheet->getCellByColumnAndRow($i, 1)->getValue()."-01";
							$sl=$worksheet->getCellByColumnAndRow($i, $row)->getValue();
							$query="INSERT INTO lichsu_nghi(ID_HS,soluong,date)
												value('$hsID','$sl','$date')";
												
							mysqli_query($db,$query);
							$html .= '<td>'.$date.'</td>';  
							$html .= '<td>'.$sl.'</td>';  
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

<form action="import_nghi.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>


<?php 
	
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
			 $n=255;
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {  
				  $highestRow = $worksheet->getHighestRow();  
				  for ($row=2; $row<=$n; $row++)  
				  {  
					   $html.="<tr>"; 
					   $hsID = $worksheet->getCellByColumnAndRow(0, $row)->getCalculatedValue();  
					   $tien = $worksheet->getCellByColumnAndRow(1, $row)->getCalculatedValue(); 
					   
					   if($tien!=0) {
							cong_tien_hs($hsID,$tien*1000,"Bạn đã nạp ".format_price($tien*1000),"nap-tien",0);
					   } 
					   
					   $html.= '<td>'.$hsID.'</td>'; 
					   $html.= '<td>'.format_price($tien*1000).'</td>';      
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

<form action="import_tien.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

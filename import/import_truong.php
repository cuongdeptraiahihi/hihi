
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
			
			include ("../include/PHPExcel/IOFactory.php");  
			 $html="<table border='1'>";  
			 $objPHPExcel = PHPExcel_IOFactory::load("".$file);  
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {  
				  $highestRow = $worksheet->getHighestRow();  
				  for ($row=2; $row<=$highestRow; $row++)  
				  {  
					   $html.="<tr>"; 
					   $quan = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); 
					   $ma = $worksheet->getCellByColumnAndRow(2, $row)->getValue();  
					   $name = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); 
					   
					   $query="INSERT INTO truong(ID_T,name,quan)
					   						value('$ma','$name','$quan');";
											
						mysqli_query($db,$query);
					   
					   $html.= '<td>'.$quan.'</td>'; 
					   $html.= '<td>'.$ma.'</td>';  
					   $html .= '<td>'.$name.'</td>';    
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

<form action="import_truong.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

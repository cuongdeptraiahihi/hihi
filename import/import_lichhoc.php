
<?php 
	ini_set('max_execution_time', 900);
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
				   
				  for ($row=2; $row<=251; $row++)  
				  {  
					   $html.="<tr>";  
					   $id = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
					   //$de =  $worksheet->getCellByColumnAndRow(11, $row)->getValue();  
					   $html.= '<td>'.$id.'</td>';  
					   
					   $lich=$worksheet->getCellByColumnAndRow(11, $row)->getValue();
					   
					   $string="";
					   if($lich!="") {
						   $temp=explode("-",$lich);
						   for($i=0;$i<3;$i++) {
								$buoi=substr($temp[$i],0,1);
								$thu=substr($temp[$i],1,1);
								$thutu=substr($temp[$i],2,1);
								$string.="/ $buoi,$thu,$thutu /";
								$query="SELECT c.ID_CA,c.cum FROM cahoc AS c INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.buoi='$buoi' AND g.thutu='$thutu' AND g.lop='1' AND g.ID_MON='1' WHERE c.thu='$thu'";
								$result=mysqli_query($db,$query);
								$data=mysqli_fetch_assoc($result);
								$html .= '<td>'.$data["ID_CA"].'</td>'; 
								add_hs_to_ca($data["ID_CA"],$id,$data["cum"],"ca_hientai","ca_codinh");
						   }
					   }
						 $html .= '<td>'.$lich.'</td>';   
					    $html .= '<td>'.$string.'</td>';   
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

<form action="import_lichhoc.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

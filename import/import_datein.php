
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
				  
				  $buoi=array();
				  $query2="SELECT * FROM buoikt ORDER BY ID_BUOI ASC";
				  $result2=mysqli_query($db,$query2);
				  while($data2=mysqli_fetch_assoc($result2)) {
				  		$buoi[]=$data2["ngay"];
				  }
				   
				  for ($row=2; $row<=250; $row++)  
				  {  
					   $html.="<tr>";  
					   $id = $worksheet->getCellByColumnAndRow(0, $row)->getValue(); 
					   $html.= '<td>'.$id.'</td>';  
					   $date_in="";
					   for($i=1;$i<=39;$i++) {
					   		$diem=$worksheet->getCellByColumnAndRow($i, $row)->getValue();
							
							if(!is_numeric($diem)) {
								$string=unicode_convert($diem);
								if($string=="chua-hoc") {
									$date_in=$buoi[$i-1];
								}
							} else {
								break;
							}
					   }
					   
					   if($date_in!="") {
						   $date=date_create($date_in);
						   date_add($date,date_interval_create_from_date_string("1 day"));
						   $date_in=date_format($date,"Y-m-d");
					   } else {
					   		$date_in="2015-07-11";
					   }
					   
					   $query="UPDATE hocsinh_mon SET date_in='$date_in' WHERE ID_HS='$id'";
						mysqli_query($db,$query);
					   
					   $html .= '<td>'.$date_in.'</td>'; 
						 
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

<form action="import_datein.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

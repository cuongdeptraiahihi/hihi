
<?php
ini_set('max_execution_time', 600);
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
				  for ($row=2; $row<=255; $row++)
				  {  
					   $html.="<tr>"; 
					   $hsID = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                      $html.= '<td>'.$hsID.'</td>';
                      $giamgia = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                      $html.= '<td>'.$giamgia.'</td>';
                      if($giamgia!=0 && is_numeric($giamgia) && $giamgia!="") {
                           $query="INSERT INTO giam_gia(ID_HS,discount,ID_MON)
                                                    value('$hsID','$giamgia','1')";
                           mysqli_query($db,$query);
                       }
					   
                       for($i=2;$i<=13;$i++) {
                           $thang = $worksheet->getCellByColumnAndRow($i, 1)->getValue();
                           if($thang>=7) {
                               $nam="2015";
                           } else {
                               $nam="2016";
                           }
                           $tien = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
                           $temp=check_dong_tien_hoc($hsID,1,"$nam-".format_month_db($thang));
                           if(count($temp)==0) {
                               if(is_numeric($tien) && $tien<0) {
                                   $query2 = "INSERT INTO tien_hoc(ID_HS,money,ID_MON,date_nhap,date_dong,date_dong2,note,who)
								                    value('$hsID','" . (abs($tien) * 1000) . "','1',now(),'$nam-" . format_month_db($thang) . "',now(),'Import từ Number','126')";
                                   $result2=mysqli_query($db,$query2);
                                   $html .= '<td>' . $tien . '</td>';
                               } else {
                                   $html .= '<td style="background: yellow">' . $tien . '</td>';
                               }
                           } else {
                               $html.= '<td style="background:cyan">'.$tien.'</td>';
                           }
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

<form action="import_tienhoc.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

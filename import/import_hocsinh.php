
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
			
			$truong_array=array();
			$result2=get_all_truong();
			while($data2=mysqli_fetch_assoc($result2)) {
				$truong_array[$data2["string"]]=$data2["ID_T"];
			}
			
			include ("../admin/include/PHPExcel/IOFactory.php");  
			 $html="<table border='1'>";  
			 $objPHPExcel = PHPExcel_IOFactory::load("".$file);  
			 $count=0;
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {  
				  $highestRow = $worksheet->getHighestRow();  
				  for ($row=2; $row<=251; $row++)  
				  {  
					   $html.="<tr>";  
					   
					   // Xử lý thời điểm nghỉ học
					   $is_nghi=false;
					   $date_nghi=$worksheet->getCellByColumnAndRow(0, $row)->getValue();
					   if(PHPExcel_Shared_Date::isDateTime($worksheet->getCellByColumnAndRow(0, $row))) {
							 $date_nghi = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($date_nghi)); 
						}
					   if($date_nghi!="") {
					   		$is_nghi=true;
					   }
					   
					   // Xử lý mã học sinh
					   $hsID=$worksheet->getCellByColumnAndRow(1, $row)->getValue(); 
					   
					   // Xử lý tên đầy đủ
					   $fullname = $worksheet->getCellByColumnAndRow(2, $row)->getValue();  
					   
					   // Xử lý CMTND
					   $cmt = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); 
					   if($cmt=="X" || $cmt=="") {
					   		if($hsID<10) {
								$cmt="10000000".$hsID;
							} else if($hsID<100) {
								$cmt="1000000".$hsID;
							} else {
								$cmt="100000".$hsID;
							}
					   }
					   
					   // Xử lý link facebook
					   $facebook = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
					   
					   // Xử lý ngày sinh
					   $birth = $worksheet->getCellByColumnAndRow(5, $row)->getValue(); 
					   if(PHPExcel_Shared_Date::isDateTime($worksheet->getCellByColumnAndRow(5, $row))) {
							 $birth = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($birth)); 
							 //$birth = str_ireplace("1995","1999",$birth);
						}
					   if($birth==$now) {
					   	$birth="0000-00-00";
					   }
					   
					   // Xử lý giới tính
					   $gender = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
					   if($gender=="nữ") {
					   	$gender=0;
					   } else if($gender=="Nam") {
					   	$gender=1;
					   } else {
					   	$gender="X";
					   }
					   
					   // Xử lý trường
					   $truong = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
					   if($truong=="X" || $truong=="") {
					   	$truong="0";
					   } else {
					   	$truong=unicode_convert("THPT ".$truong);
							if(!isset($truong_array[$truong])) {
								$truong=0;
								$count++;
						   } else {
						   		$truong=$truong_array[$truong];
						   }
					   }
					   
					   // Xử lý điện thoại
					   $sdt = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
					   $sdt_bo = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
					   $sdt_me = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
					   
					   $de = "B";
					    
					   /*$query="INSERT INTO hocsinh(ID_HS,cmt,password,fullname,namestring,avata,birth,gender,facebook,truong,sdt,sdt_bo,sdt_me,taikhoan,code,lop) value ('$hsID','$cmt','".md5("123456")."','$fullname','".unicode_convert($fullname)."','avata.jpg','$birth','$gender','$facebook','$truong','$sdt','$sdt_bo','$sdt_me','0','','1')";  
					   	mysqli_query($db,$query);
					   $query3="INSERT INTO hocsinh_mon(ID_HS,maso,de,ID_MON)
													value('$hsID','T99A-$hsID','$de','1')";
					  	mysqli_query($db,$query3);
					   
					   $query4="INSERT INTO bang_xep_hang(ID_HS,diemtb,ID_MON)
													value('$hsID','0','1')";
					   mysqli_query($db,$query4);
					   
					   if($is_nghi) {
							$query5="INSERT INTO hocsinh_nghi(ID_HS,ID_MON,date)
														value('$hsID','1','$date_nghi')";
							mysqli_query($db,$query5);
					   }*/
					   
					   $query="UPDATE hocsinh SET truong='$truong' WHERE ID_HS='$hsID'";
					   mysqli_query($db,$query);
					   
					   $html.= '<td>'.$date_nghi.'</td>';  
					   $html.= '<td>'.$hsID.'</td>';  
					   $html.= '<td>'.$fullname.'</td>';  
					   $html.= '<td>'.$cmt.'</td>';  
					   $html .= '<td>'.$facebook.'</td>';
					   $html .= '<td>'.$birth.'</td>'; 
					   $html .= '<td>'.$gender.'</td>'; 
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
			 echo '<br />Data Inserted '.$highestRow."<br />$count";  
		}
	}
	require_once("../model/close_db.php");
?>

<form action="import_hocsinh.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>


<?php 
	ini_set('max_execution_time', 600);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	
	$file=NULL;
	
	$ca_arr=array();
	$query0="SELECT DISTINCT c.cum,c.thu,c.ID_CA FROM cahoc AS c INNER JOIN cagio AS g ON g.ID_GIO=c.ID_GIO AND g.lop='1' AND g.ID_MON='1' ORDER BY c.thu ASC";
	$result0=mysqli_query($db,$query0);
	while($data0=mysqli_fetch_assoc($result0)) {
		$ca_arr[$data0["thu"]]=array(
			"cum" => $data0["cum"],
			"caID" => $data0["ID_CA"]
		);
		//echo $data0["thu"]."-".$data0["cum"]."-".$data0["ID_CA"]."<br />";
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

			$cumID = 36;
			$num = 19;
			$rows = 255;
			
			$cumID2_temp=$cumID;
			 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
			 {
				 $highestRow = $worksheet->getHighestRow();
				 
				 $ngay_arr=array();
				 
				 $html .= "<tr><td></td>";
				 for ($i = 12;$i <= $num; $i++) {
				 	 $ngay = $worksheet->getCellByColumnAndRow($i, 1)->getValue();
					 if(substr_count($ngay,"/")==1) {
						 $temp = explode("/",$ngay);
						 $temp2 = explode("-",$temp[0]);
						 
						 $thang1=format_month_db($temp[1]);
						 $ngay1=format_month_db($temp2[0]);
						 $thang2=format_month_db($temp[1]);
						 $ngay2=format_month_db($temp2[1]);
					 } else {
					 	$temp = explode("-",$ngay);
						$temp2 = explode("/",$temp[0]);
						$temp3 = explode("/",$temp[1]);
						
						$thang1=format_month_db($temp2[1]);
						 $ngay1=format_month_db($temp2[0]);
						 $thang2=format_month_db($temp3[1]);
						 $ngay2=format_month_db($temp3[0]);
					 }
					 $thu1=date("w", strtotime("2016-$thang1-$ngay1"))+1;
					 $thu2=date("w", strtotime("2016-$thang2-$ngay2"))+1;
					 $html .= "<td>2016-$thang1-$ngay1 ($thu1-".$ca_arr[$thu1]["cum"].")<br />2016-$thang2-$ngay2 ($thu2-".$ca_arr[$thu2]["cum"].")</td>";
					 $query2 = "INSERT INTO diemdanh_buoi(ID_CUM,ID_CD,ID_CA,cum,date,ID_LOP,ID_MON)
													value('$cumID','0','".$ca_arr[$thu1]["caID"]."','".$ca_arr[$thu1]["cum"]."','2016-$thang1-$ngay1','1','1')";

					//mysqli_query($db,$query2);
					$query2 = "INSERT INTO diemdanh_buoi(ID_CUM,ID_CD,ID_CA,cum,date,ID_LOP,ID_MON)
													value('$cumID','0','".$ca_arr[$thu2]["caID"]."','".$ca_arr[$thu2]["cum"]."','2016-$thang2-$ngay2','1','1')";

					//mysqli_query($db,$query2);
					$cumID++;
					$ngay_arr[$i]=array(
						"day_1" => "2016-$thang1-$ngay1",
						"day_2" => "2016-$thang2-$ngay2"
					);
				 }
				 $html .= "</tr>";
					 
				 for ($row = 2; $row <= $rows; $row++) {

					 $html .= "<tr>";

					 $hsID = $worksheet->getCellByColumnAndRow(0, $row)->getCalculatedValue();
					 $html .= "<td>$hsID</td>";
						
					$cumID2=$cumID2_temp;	
					 for ($i = 12; $i <= $num; $i++) {
						 
						 $me=rand(1,2);
						 $query="SELECT ID_STT,ID_CUM,ID_CA,cum FROM diemdanh_buoi WHERE date='".$ngay_arr[$i]["day_$me"]."' AND ID_LOP='1' AND ID_MON='1'";
						 $result=mysqli_query($db,$query);
						 $data=mysqli_fetch_assoc($result);
						 
						 $loai = $worksheet->getCellByColumnAndRow($i, $row)->getCalculatedValue();
						 $is_kt=1;
						 if (is_numeric($loai)) {
							 switch($loai) {
								case 0:
									$is_hoc=0;
									$is_tinh=0;
									break;
								case 1:
									$is_hoc=1;
									$is_tinh=0;
									break;
								case 2:
									$is_hoc=1;
									$is_tinh=1;
									break;
								case 3: 
									$is_kt=0;
									$is_hoc=0;
									$is_tinh=0;
									break;
							 }
							 //insert_diemdanh($data["ID_STT"],$hsID,$is_tinh,$is_hoc,$is_kt,1,"diemdanh_toan");
						 } else {
						 	if($loai=="P") {
								insert_diemdanh_nghi($data["ID_CUM"],$hsID,1,1,1);
							} else if($loai=="X") {
								insert_diemdanh_nghi($data["ID_CUM"],$hsID,0,1,1);
							} else if($loai=="") {
								insert_diemdanh_nghi($data["ID_CUM"],$hsID,1,1,1);
							} else {
							}
						 }
						 $html .= "<td>$loai</td>";
						 $cumID2++;
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

<form action="import_diemdanh2.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

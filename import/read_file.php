
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
			$highestRow=0;
			foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
				$highestRow = $worksheet->getHighestRow();
				$row=23;
//				for ($row=1; $row<=$highestRow; $row++) {
					if($row!=23) {
						continue;
					}
					$html.="<tr>";
					$col=0;
					$content = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
					$html.= '<td>'.$content.'</td>';
//					for($col=0;$col<=50;$col++) {
//						$content = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
//						$html.= '<td>'.$content.'</td>';
//					}

					$html .= "</tr>";
//				}
			}
			$html .= '</table>';
			echo $html;
			echo '<br />Data Inserted '.$highestRow;
		}
	}
	require_once("../model/close_db.php");
?>

<form action="read_file.php" method="post" enctype="multipart/form-data">
	<input type="file" name="data" />
    <input type="submit" name="ok" value="OK" />
</form>

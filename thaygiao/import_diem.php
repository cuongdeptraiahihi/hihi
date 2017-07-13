<?php
	ob_start();
	session_start();
	ini_set('max_execution_time', 900);
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	require_once("access_admin.php");
	$lido=array();
	$result3=get_all_lido2();
	while($data3=mysqli_fetch_assoc($result3)) {
		$lido["$data3[string]"]=$data3["ID_LD"];
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>       
        
        <title>IMPORT DỮ LIỆU</title>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--[if lt IE 9]>
    	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js">
        </script><![endif]-->
               
        <link rel="stylesheet" type="text/css" href="http://localhost/www/TDUONG/thaygiao/css/bocuc.css"/>
        <link href="http://localhost/www/TDUONG/thaygiao/images/favicon.ico" rel="shortcut icon" type="iamge/x-icon"/>
        <link rel="stylesheet" href="http://localhost/www/TDUONG/thaygiao/css/font-awesome.min.css">
        
        <style>
			#MAIN > #main-mid {width:100%;}#MAIN > #main-mid > div .status .table-action {width:100%;margin-bottom:50px;}#MAIN > #main-mid > div .status .table-action select {float:right;margin-top:3px;}#MAIN > #main-mid > div .status .table-action input.input {width:50%;}#MAIN > #main-mid > div .status #search-box {width:80%;}.check {width:20px;height:20px;margin-right:10px;}
			#MAIN > #main-mid > div .status .table-2 {display:inline-table;}#MAIN > #main-mid > div .status .table-2 tr td {text-align:left;padding-left:10px;padding-right:10px;}#MAIN > #main-mid > div .status .table-2 tr td span i {font-size:1.5em;}#MAIN > #main-mid > div .status table tr td > a {font-size:22px;color:#3E606F;text-decoration:underline;}#MAIN > #main-mid > div .status table tr td .td-p {display:inline-block;font-size:22px;color:#3E606F;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td #ca-result {color:#FFF;padding:5px 10px 5px 10px;margin-left:20px;}#MAIN > #main-mid > div .status .table tr td .ca-check {color:#FFF;padding:5px 10px 5px 10px;font-weight:600;}#MAIN > #main-mid > div .status .table-2 tr td > div p {display:inline-block;font-size:14px;color:#3E606F;background:#ffffa5;padding:7px 10px 7px 10px;border:1px solid #dfe0e4;border-bottom:2px solid #3E606F;}#MAIN > #main-mid > div .status .table-2 tr td > div input.check {display:inline-block;margin-left:10px;}.mon-lich {display:none;}
        </style>
        
        <?php require_once("include/SCRIPT.php"); ?>
        <script>
		$(document).ready(function() {
			
		});
		</script>
       
	</head>

    <body>
    
    	<div class="popup" id="popup-loading">
      		<p><img src="http://localhost/www/TDUONG/thaygiao/images/ajax-loader.gif" /></p>
      	</div>
        
        <?php
			$error="";
			$monID=$date=$start=$sum=$file=$hang=NULL;
			if(isset($_POST["ok"])) {
				if(isset($_POST["monID"])) {
					$monID=$_POST["monID"];
				}
				if(isset($_POST["add-date"])) {
					$date=$_POST["add-date"];
				}
				if(isset($_POST["add-cot"])) {
					$start=$_POST["add-cot"];
				}
				if(isset($_POST["sum-cot"])) {
					$sum=$_POST["sum-cot"];
				}
				if(isset($_POST["add-hang"])) {
					$hang=$_POST["add-hang"]+1;
				}
				if($_FILES["add-excel"]["error"]>0) {
				} else {
					$file=$_FILES["add-excel"]["name"];
				}
				if($monID && $date && $start && $sum && $file && $hang) {
					move_uploaded_file($_FILES["add-excel"]["tmp_name"],"../import/".$_FILES["add-excel"]["name"]);
					include ("include/PHPExcel/IOFactory.php");
					$html="<table border='1'>";  
					 $objPHPExcel = PHPExcel_IOFactory::load("../import/".$file);  
					 foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
					 {  
						  $highestRow = $worksheet->getHighestRow(); 
						   
						  for ($row=2; $row<=$hang; $row++)  
						  {  
							   $html.="<tr>";  
							   $id = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							   //$de=get_de_hs($id,$monID);
							   
							   $query2="SELECT de FROM diemkt WHERE ID_HS='$id' AND ID_BUOI='7'";
							   $result2=mysqli_query($db,$query2);
							   $data2=mysqli_fetch_assoc($result2);
							   $de=$data2["de"];
							   
							   //$de =  $worksheet->getCellByColumnAndRow(11, $row)->getValue();  
							   $html.= '<td>'.$id.'</td>';  
							   
							   $buoiID=$start;$dem=0;$total=0;
							   for($i=$start;$i<=($start+$sum-1);$i++) {
								   $note=0;$string="none";
									$diem=$worksheet->getCellByColumnAndRow($i, $row)->getValue();
									if(is_numeric($diem)) {
										//$html .= '<td>'.$diem.'</td>'; 
										if($diem<0) {
											$diem=-$diem;
											$loai=1;
										} else {
											$diem=$diem;
											$loai=0;
										}
									} else {
										$string=unicode_convert($diem);
										//$html .= '<td>'.$string.'</td>'; 
										if($string=="nghi-hoc") {
											$diem=0;
											$loai=2;
										} else if($string=="khong-di-thi") {
											$diem=0;
											$loai=5;
										} else if($string=="x" || $string=="nghi-co-phep" || $string=="chua-hoc") {
											$diem="X";
											$loai=4;
										} else {
											$diem=0;
											$loai=3;
											$note=$lido[$string];
										}
									}
									
									if(is_numeric($diem)) {
										$total+=$diem;
										$dem++;
									}
									$query="INSERT INTO diemkt(ID_BUOI,ID_HS,diem,loai,de,note)
														value('$buoiID','$id','$diem','$loai','$de','$note')";
														
									mysqli_query($db,$query);
									
									$html .= '<td>'.$diem.'</td>'; 
									$buoiID++;
							   }
							   
							   if($dem!=0) {
									$diemtb=$total/$dem;
									$diemtb=number_format((float)$diemtb, 2, '.', '');
							   } else {
									$diemtb=0;
							   }
								
								$html .= '<td>'.$de.'</td>';  
								$html .= '<td>'.$diemtb.'</td>'; 
								$html .= '<td>'.$string.'</td>';  
							   $html .= "</tr>";  
						  }  
					 }  
					 $html .= '</table>';  
					echo $html; 
					 $time=$date;
					$cn=$sum;
					$query="SELECT ID_HS,de,ID_MON FROM hocsinh_mon WHERE ID_MON='$monID' ORDER BY ID_HS ASC";
					$result=mysqli_query($db,$query);
					while($data=mysqli_fetch_assoc($result)) {
						$diemtb=tinh_diemtb_month($data["ID_HS"],$time,"diemkt");
						echo"$data[ID_HS] - $diemtb<br />";
						if($diemtb!=NULL) {
							insert_diemtb_thang($data["ID_HS"], $diemtb, $data["de"], 1, $data["ID_MON"],$time);
						}
					}
					$time=$date;
					clean_new_nhayde();
					$query="SELECT * FROM hocsinh_mon WHERE ID_MON='$monID' ORDER BY ID_MON ASC, ID_HS ASC";
					$result=mysqli_query($db,$query);
					while($data=mysqli_fetch_assoc($result)) {
						$diemtb=get_diemtb_month($data["ID_HS"], $data["ID_MON"], $time);
						if($diemtb>=8) {
							$new_de="G";
						} else if($diemtb<5){
							$new_de="B";
						} else {
							$new_de=$data["de"];
						}
						if($data["de"]!=$new_de) {
							//update_de_hs($data["ID_HS"], $new_de, $data["ID_MON"]);
							//insert_new_nhayde($data["ID_HS"], $new_de, $diemtb, $data["ID_MON"]);
							if($new_de=="G") {
								echo"$data[ID_HS] - $diemtb - $new_de (lên đề)<br />";
								//add_thong_bao_hs2($data["ID_HS"],1,"Chúc mừng bạn đã chuyến sang đề G từ ngày 1/$month","nhay-de",$data["ID_MON"]);
							} else {
								echo"$data[ID_HS] - $diemtb - $new_de (xuống đề)<br />";
								//add_thong_bao_hs2($data["ID_HS"],0,"Rất tiếc bạn phải chuyển xuống làm đề B từ ngày 1/$month","nhay-de",$data["ID_MON"]);
							}
						}
					}
					echo $start+$sum; 
				}
			}
		?>
                             
      	<div id="BODY">
        
        	<?php require_once("include/TOP.php"); ?>
            
            <div id="MAIN">
            
            	
                
                <div id="main-mid">
                	<h2>Import dữ liệu</h2>
                	<div>
                    	<div class="status">
                        	<form action="http://localhost/www/TDUONG/thaygiao/import/" method="post" enctype="multipart/form-data">
                        	<table class="table table-2" style="width:39%;" id="info-hs">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Thông tin Chung</span></th></tr>
                                <tr>
                                	<td style="width:35%;"><span>Môn học</span></td>
                                	<td style="width:65%;">
                                    	<select class="input" style="height:auto;width:100%;" name="monID">
                                                <option value="0">Chọn môn</option>
                                            <?php
                                                $result=get_all_mon_admin();
                                                for($i=0;$i<count($result);$i++) {
                                                    echo"<option value='".$result[$i]["monID"]."'>Môn ".$result[$i]["name"]."</option>";
                                                }
                                            ?>
                                       	</select>
                                    </td>
                              	</tr>
                                <tr>
                                	<td><span>Thời điêm</span></td>
                                    <td><input class="input" name="add-date" placeholder="2015-06" type="text" /></td>
                              	</tr>
                                <tr>
                                	<td><span>Cột bắt đầu</span></td>
                                    <td><input class="input" name="add-cot" placeholder="1, 2, 33,..." type="text" /></td>
                              	</tr>
                                <tr>
                                	<td><span>Tổng số cột</span></td>
                                    <td><input class="input" name="sum-cot" placeholder="1, 2,..." type="text" /></td>
                              	</tr>
                                <tr>
                                	<td><span>Hàng dữ liệu cuối</span></td>
                                    <td><input class="input" name="add-hang" value="250" placeholder="1, 2,..." type="text" /></td>
                              	</tr>
                                <tr>
                                	<td colspan="2"><span>Hàng dữ liệu đầu luôn là 2</span></td>
                              	</tr>
                            </table>
                            <table class="table table-2" style="width:59%;" id="info-mon">
                            	<tr style="background:#3E606F;"><th colspan="2"><span>Dữ liệu</span></th></tr>
                                <tr>
                                	<td style="width:40%;"><span>Chọn file Excel</span></td>
                                    <td style="width:60%;"><input class="input" name="add-excel" type="file" /></td>
                                </tr>
                            </table>
                            <table class="table" style="margin-top:25px;">
                            	<tr>
                            		<td><input type="submit" style="width:50%;font-size:1.375em;" class="submit" value="Nhập" name="ok" /></td>
                              	</tr>
                          	</table>
                       	</form>
                    </div>
               	</div>
            </div>
            </div>
        
        </div>
        
    </body>
</html>

<?php
	ob_end_flush();
	require_once("../model/close_db.php");
?>
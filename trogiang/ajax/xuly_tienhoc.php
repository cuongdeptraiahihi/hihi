<?php
	ob_start();
	session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");

    if(isset($_POST["cmt6"]) && isset($_POST["thang6"]) && isset($_POST["lmID6"])) {
        $cmt=$_POST{"cmt6"};
        $thang=$_POST["thang6"];
        $lmID=$_POST["lmID6"];
        $query="SELECT content FROM options WHERE type='edit-tien-hoc-$lmID' AND note='$thang' AND note2='".get_hs_id($cmt)."'";
        $result=mysqli_query($db,$query);
        if(mysqli_num_rows($result) != 0) {
            $data = mysqli_fetch_assoc($result);
            echo $data["content"];
        } else {
            echo "none";
        }
    }

    if(isset($_POST["hsID"]) && isset($_POST["lmID"]) && isset($_POST["thang"]) && isset($_POST["price"])) {
        $hsID=$_POST["hsID"];
        $lmID=$_POST["lmID"];
        $thang=$_POST["thang"];
        $price=$_POST["price"];
        add_options2($price,"edit-tien-hoc-$lmID",$thang,$hsID);
    }
	
	if (isset($_POST["cmt"])) {
		$cmt=$_POST["cmt"];
		$query="SELECT hocsinh.ID_HS,hocsinh.fullname,hocsinh.birth,truong.name FROM hocsinh INNER JOIN truong ON truong.ID_T=hocsinh.truong WHERE hocsinh.cmt='$cmt'";
		$result=mysqli_query($db,$query);
		$data=mysqli_fetch_assoc($result);
		echo json_encode(
			array(
				"ID_HS" => $data["ID_HS"],
				"fullname" => $data["fullname"],
				"birth" => format_dateup($data["birth"]),
				"truong" => $data["name"]
			)
		);
	}
	
	if(isset($_POST["ajax_data"])) {
		$data=$_POST["ajax_data"];
		$data=json_decode($data,true);
		$n=count($data)-1;
		$cmt=$data[$n]["cmt"];
		$oID=base64_decode($data[$n]["oID"]);
		$code=md5($data[$n]["code"]);
		$hsID=check_exited_hocsinh2($cmt);
		if($hsID!=0 && is_numeric($hsID)) {
			if(check_code_trogiang($oID,$code)) {
				$check=true;
				for($i=0;$i<$n;$i++) {
					if(is_numeric($data[$i]["seri"]) && $data[$i]["seri"]>=0 && is_numeric($data[$i]["money"]) && $data[$i]["date_dong"]!="" && $data[$i]["date_dong2"]!="" && $data[$i]["lmID"]!=0 && is_numeric($data[$i]["lmID"]) && is_numeric($data[$i]["sttID"])) {
					    //if(!check_same_seri($data[$i]["seri"])) {
                            add_tienhoc($hsID, $data[$i]["lmID"], $oID, $data[$i]["money"] * 1000, $data[$i]["seri"], $data[$i]["date_dong"], format_date_o($data[$i]["date_dong2"]), $data[$i]["note"], $data[$i]["sttID"]);
                        //} else {
                            //$check=false;
                        //}
                    } else {
						$check=false;
					}
				}
				if($check) {
					echo"ok";
				} else {
					echo"Dữ liệu đã được ghi nhưng có lỗi xảy ra! Vui lòng kiểm tra lại!";
				}
			} else {
				echo"Không tồn tại trợ giảng này!";
			}
		} else {
			echo"Không tồn tại học sinh này!";
		}
	}

	// Đã check
	if((isset($_POST["code0"]) && isset($_POST["ngay"])) || (isset($_GET["code0"]) && isset($_GET["ngay"]))) {
		if(isset($_GET["code0"]) && isset($_GET["ngay"])) {
			$code=md5($_GET["code0"]);
			$ngay=$_GET["ngay"];
			
			$xuat=true;
			include ("../include/PHPExcel/IOFactory.php");
			$objPHPExcel = new PHPExcel(); 
			$objPHPExcel->setActiveSheetIndex(0); 
		} else {
			$code=md5(base64_decode($_POST["code0"]));
			$ngay=$_POST["ngay"];
			$xuat=false;
		}
		$query="SELECT ID_O,note FROM options WHERE content='$code' AND type='tro-giang-code'";
		$result=mysqli_query($db,$query);
		if(mysqli_num_rows($result)!=0) {
			$data=mysqli_fetch_assoc($result);
			
			if($xuat) {
				$rowCount = 1; 
				$col = 'A';
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Trợ giảng");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data["note"]);$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_dateup($ngay));$col++;
				
				$rowCount=2;
				$col = 'A';
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "STT");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Họ và Tên");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã số");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Nội dung");$col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Mã hóa đơn");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tiền");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Ngày đóng");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Loại");$col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Môn");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Ghi chú");$col++;
				
				$rowCount=3;
			}
			
			$content="";
			$stt=0;
			$total=0;
            $tong_hoc=$tien_vao=$tien_ra=0;
			$query2="SELECT t.*,h.cmt,h.fullname,m.name FROM tien_hoc AS t INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS INNER JOIN lop_mon AS m ON m.ID_LM=t.ID_LM WHERE t.date_nhap='$ngay' AND t.who='$data[ID_O]' ORDER BY t.ID_STT DESC";
			$result2=mysqli_query($db,$query2);
			while($data2=mysqli_fetch_assoc($result2)) {
				
				$total+=$data2["money"];
                $tong_hoc+=$data2["money"];
				
				$content.="<tr>
					<td><span>".($stt+1)."</span></td>
					<td><span>$data2[fullname]</span></td>
					<td><span>$data2[cmt]</span></td>
					<td><span>($data2[code]) Ngày ".format_dateup($data2["date_dong2"])." đóng tiền học tháng ".format_month($data2["date_dong"])." môn $data2[name]</span></td>
					<td style='position:relative;' class='tien-total'><span>".format_price($data2["money"])."</span><span style='font-size:10px;position:absolute;bottom:0;z-index:9;display:none;right:0;' class='tien-show'>".format_price($total)."</span></td>
					<td><span>Tiền học</span></td>
					<td><span>$data2[note]</span></td>
				</tr>";
				$stt++;
				
				if($xuat){
					$col="A";
					
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $stt);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["fullname"]);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["cmt"]);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Đóng tiền học tháng ".format_month($data2["date_dong"]));$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["code"]);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_price($data2["money"]));$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_dateup($data2["date_dong2"]));$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tiền học");$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Môn $data2[name]");$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["note"]);$col++;
					
					$rowCount++;
				}
				
			}
            $content.="<tr>
                <td colspan='4'><span>Tổng tiền học</span></td>
                <td><span>".format_price($tong_hoc)."</span></td>
                <td><span></span></td>
                <td><span></span></td>
            </tr>";
			$query2="SELECT t.*,h.cmt,h.fullname,o.ID_O,o.note AS name FROM tien_vao AS t INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS INNER JOIN options AS o ON o.ID_O=t.object AND o.type='tro-giang-code' WHERE t.date='$ngay' AND t.string='rut-tien' ORDER BY t.ID_VAO DESC";
			$result2=mysqli_query($db,$query2);
			while($data2=mysqli_fetch_assoc($result2)) {
				
				$total+=$data2["price"];
                $tien_vao+=$data2["price"];

                if($data["ID_O"]!=$data2["ID_O"]) {
				    $content.="<tr style='opacity: 0.3;'>";
                } else {
                    $content.="<tr>";
                }
					$content.="<td><span>".($stt+1)."</span></td>
					<td><span>$data2[fullname]</span></td>
					<td><span>$data2[cmt]</span></td>
					<td><span>Ngày ".format_dateup($data2["date_dong"]).": $data2[note]</span></td>
					<td style='position:relative;' class='tien-total'><span>".format_price($data2["price"])."</span><span style='font-size:10px;position:absolute;bottom:0;z-index:9;display:none;right:0;' class='tien-show'>".format_price($total)."</span></td>
					<td><span>Rút tiền</span></td>
					<td><span>$data2[name]</span></td>
				</tr>";
				$stt++;
				
				if($xuat){
					$col="A";
					
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $stt);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["fullname"]);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["cmt"]);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["note"]);$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_price($data2["price"]));$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_dateup($data2["date_dong"]));$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Rút tiền");$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["name"]);$col++;
					
					$rowCount++;
				}
				
			}
            $content.="<tr>
                <td colspan='4'><span>Tổng rút tiền</span></td>
                <td><span>".format_price($tien_vao)."</span></td>
                <td><span></span></td>
                <td><span></span></td>
            </tr>";
			$query2="SELECT t.*,h.cmt,h.fullname,o.ID_O,o.note AS name FROM tien_ra AS t INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS INNER JOIN options AS o ON o.ID_O=t.object AND o.type='tro-giang-code' WHERE t.date='$ngay' AND t.string='nap-tien' ORDER BY t.ID_RA DESC";
			$result2=mysqli_query($db,$query2);
			while($data2=mysqli_fetch_assoc($result2)) {
				
				$total+=$data2["price"];
                $tien_ra+=$data2["price"];

                if($data["ID_O"]!=$data2["ID_O"]) {
                    $content.="<tr style='opacity: 0.3;'>";
                } else {
                    $content.="<tr>";
                }
					$content.="<td><span>".($stt+1)."</span></td>
					<td><span>$data2[fullname]</span></td>
					<td><span>$data2[cmt]</span></td>
					<td><span>($data2[code]) Ngày ".format_dateup($data2["date_dong"]).": $data2[note]</span></td>
					<td style='position:relative;' class='tien-total'><span>".format_price($data2["price"])."</span><span style='font-size:10px;position:absolute;bottom:0;z-index:9;display:none;right:0;' class='tien-show'>".format_price($total)."</span></td>
					<td><span>Nạp tiền</span></td>
					<td><span>$data2[name]</span></td>
				</tr>";
				$stt++;
				
				if($xuat){
					$col="A";
					
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $stt);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["fullname"]);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["cmt"]);$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["note"]);$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_price($data2["price"]));$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_dateup($data2["date_dong"]));$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Nạp tiền");$col++;
					$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
                    $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, $data2["name"]);$col++;
					
					$rowCount++;
				}
				
			}
            $content.="<tr>
                <td colspan='4'><span>Tổng nạp tiền</span></td>
                <td><span>".format_price($tien_ra)."</span></td>
                <td><span></span></td>
                <td><span></span></td>
            </tr>";
			
			$content.="<tr>
				<td colspan='4'><span>Tổng cộng</span></td>
				<td><span>".format_price($total)."</span></td>
				<td colspan='2'><span></span></td>
			</tr>";
			
			echo json_encode(
				array(
					"ID_O" => base64_encode($data["ID_O"]),
					"name" => $data["note"],
					"content" => $content
				)
			);
			
			if($xuat) {
				
				$col = 'A';
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "Tổng cộng");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, format_price($total));$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
				$objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
                $objPHPExcel->getActiveSheet()->SetCellValue("$col".$rowCount, "");$col++;
				
				ob_end_clean();
				// Instantiate a Writer to create an OfficeOpenXML Excel .xlsx file
				header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename="du-lieu-tien.xlsx"');
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
				// Write the Excel file to filename some_excel_file.xlsx in the current directory
				$objWriter->save('php://output');
			}
		} else {
			echo"none";
		}
	}
	
	if(isset($_POST["open"]) && isset($_POST["monID"])) {
		$open=$_POST["open"];
		$monID=$_POST["monID"];
		if(open_thang_tienhoc($open,$monID)) {
			echo"none";
		} else {
			echo"ok";
		}
	}
	
	if(isset($_POST["close"]) && isset($_POST["monID"])) {
		$close=$_POST["close"];
		$monID=$_POST["monID"];
		close_thang_tienhoc($close,$monID);
	}
	
	if(isset($_POST["tien1"]) && isset($_POST["seri"]) && isset($_POST["date_dong"]) && isset($_POST["ngay4"]) && isset($_POST["cmt0"]) && isset($_POST["ma"]) && isset($_POST["oID"])) {
		$tien=$_POST["tien1"];
        $seri=$_POST["seri"];
		$date_dong=$_POST["date_dong"];
		$date=$_POST["ngay4"];
		$cmt=$_POST["cmt0"];
		$code=md5($_POST["ma"]);
		$oID=base64_decode($_POST["oID"]);
		$hsID=check_exited_hocsinh2($cmt);
		if($hsID!=0 && is_numeric($hsID) && $tien>0 && is_numeric($tien)) {
			if(check_code_trogiang($oID,$code)) {
				cong_tien_hs2($hsID,$tien,"Bạn đã nạp ".format_price($tien),"nap-tien",$oID,$seri,$date,format_date_o($date_dong));
				//add_thong_bao_hs($hsID,0,"Bạn đã nạp ".format_price($tien),"nap-rut",3);
				echo"ok";
			} else {
				echo"Không tồn tại trợ giảng này!";
			}
		} else {
			echo"Dữ liệu không chính xác!";
		}
	}
	
	if(isset($_POST["tien2"]) && isset($_POST["date_dong"]) && isset($_POST["ngay4"]) && isset($_POST["cmt0"]) && isset($_POST["ma"]) && isset($_POST["oID"])) {
		$tien=$_POST["tien2"];
		$date_dong=$_POST["date_dong"];
		$date=$_POST["ngay4"];
		$cmt=$_POST["cmt0"];
		$code=md5($_POST["ma"]);
		$oID=base64_decode($_POST["oID"]);
		$hsID=check_exited_hocsinh2($cmt);
		if($hsID!=0 && is_numeric($hsID) && $tien>0 && is_numeric($tien)) {
			if(check_code_trogiang($oID,$code)) {
				if(get_tien_hs($hsID)>=$tien) {
					tru_tien_hs2($hsID,$tien,"Bạn đã rút ".format_price($tien),"rut-tien",$oID,$date,format_date_o($date_dong));
					//add_thong_bao_hs($hsID,0,"Bạn đã rút ".format_price($tien),"nap-rut",3);
					echo"ok";
				} else {
					echo"Tiền của bạn không đủ, bạn không thể rút!";
				}
			} else {
				echo"Không tồn tại trợ giảng này!";
			}
		} else {
			echo"Dữ liệu không chính xác!";
		}
	}
	
	if(isset($_POST["cmt2"]) && isset($_POST["ngay2"]) && isset($_POST["oID2"])) {
		$cmt=$_POST["cmt2"];
		$ngay=$_POST["ngay2"];
		$oID=base64_decode($_POST["oID2"]);
		$hsID=check_exited_hocsinh2($cmt);
		if($hsID!=0 && is_numeric($hsID)) {
			$query="SELECT * FROM tien_ra WHERE ID_HS='$hsID' AND string='nap-tien' AND object='$oID' AND date='$ngay' ORDER BY ID_RA DESC";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
				echo"<tr>
					<td><span>Số tiền nạp</span></td>
					<td><input type='number' class='input tien' min='0' placeholder='10000, 20000, ..' value='$data[price]' /></td>
					<td><input type='text' class='input seri' value='$data[code]' placeholder='Seri hóa đơn' /></td>
					<td><span>Ngày đóng</span></td>
					<td><input type='text' class='input date_dong' placeholder='12/4/2016' value='".format_dateup($data["date_dong"])."' /></td>
					<td><input type='submit' class='submit sua-nap' data-idRA='$data[ID_RA]' value='Sửa' /><input type='submit' class='submit xoa-nap' data-idRA='$data[ID_RA]' value='Xóa' /></td>
				</tr>";
			}
		}
	}
	
	if(isset($_POST["cmt3"]) && isset($_POST["ngay3"]) && isset($_POST["oID3"])) {
		$cmt=$_POST["cmt3"];
		$ngay=$_POST["ngay3"];
		$oID=base64_decode($_POST["oID3"]);
		$hsID=check_exited_hocsinh2($cmt);
		if($hsID!=0 && is_numeric($hsID)) {
			$query="SELECT * FROM tien_vao WHERE ID_HS='$hsID' AND string='rut-tien' AND object='$oID' AND date='$ngay' ORDER BY ID_VAO DESC";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
				echo"<tr>
					<td><span>Số tiền rút</span></td>
					<td><input type='number' class='input tien' min='0' placeholder='10000, 20000, ..' value='$data[price]' /></td>
					<td><span>Ngày rút</span></td>
					<td><input type='text' class='input date_dong' placeholder='12/4/2016' value='".format_dateup($data["date_dong"])."' /></td>
					<td><input type='submit' class='submit sua-rut' data-idVAO='$data[ID_VAO]' value='Sửa' /><input type='submit' class='submit xoa-rut' data-idVAO='$data[ID_VAO]' value='Xóa' /></td>
				</tr>";
			}
		}
	}

	// Đã check
	if(isset($_POST["cmt1"]) && isset($_POST["ngay1"]) && isset($_POST["oID"])) {
		$cmt=$_POST["cmt1"];
		$ngay=$_POST["ngay1"];
		$oID=base64_decode($_POST["oID"]);
        $hsID=check_exited_hocsinh2($cmt);
        if(date("j")<=20) {
            $pre_date=date("Y-m");
        } else {
            $pre_date=get_next_time(date("Y"),date("m"));
        }
		if($hsID!=0 && is_numeric($hsID)) {
			$query="SELECT h.ID_LM,h.date_in,m.name,m.date_in AS date FROM hocsinh_mon AS h INNER JOIN lop_mon AS m ON m.ID_LM=h.ID_LM WHERE h.ID_HS='$hsID' ORDER BY h.ID_LM ASC";
			$result=mysqli_query($db,$query);
			while($data=mysqli_fetch_assoc($result)) {
                $temp0=explode("-",$data["date"]);
				$temp=explode("-",$data["date_in"]);
				$temp_nam=$temp[0];
				$temp_thang=$temp[1];
				echo"<tr>
					<th colspan='9'><span>Môn $data[name]</span></th>
				</tr>";
				$query2="SELECT t.*,o.note AS name FROM tien_hoc AS t INNER JOIN options AS o ON o.ID_O=t.who WHERE t.ID_HS='$hsID' AND t.ID_LM='$data[ID_LM]' ORDER BY t.date_dong ASC";
				$result2=mysqli_query($db,$query2);
				while($data2=mysqli_fetch_assoc($result2)) {
					$nam=$temp0[0];
					$thang=$temp0[1];
					if($data2["date_nhap"]==$ngay && $data2["who"]==$oID) {
						$dis="";
						$opa="opacity:1;";
						$class="active-tr";
					} else {
						$dis="disabled='disabled'";
						$opa="opacity:0.3;";
						$class="";
					}
					echo"<tr class='$class' data-monID='$data[ID_LM]' data-sttID='$data2[ID_STT]'>
						<td style='width:10%;'>
							<select class='input' style='width:100%;height:auto;$opa' $dis>";
								for($i=0;$i<24;$i++) {
									if($nam>$temp_nam || ($nam==$temp_nam && $thang>=$temp_thang) || ($nam<$temp_nam && $thang==12)) {
										echo"<option value='$nam-$thang'"; if($data2["date_dong"]=="$nam-$thang"){echo"selected='selected'";}echo">$thang/$nam</option>";
									}
									$temp2=explode("-",get_next_time($nam,$thang));
									$nam=$temp2[0];
									$thang=$temp2[1];
								}
							echo"</select>
						</td>
						<td style='width:10%;'><span>Số tiền</span></td>
						<td style='width:7%;'><input type='number' min='100' max='9999' value='".($data2["money"]/1000)."' class='input money' style='$opa' $dis /></td>
						<td style='width:10%;'><input type='text' value='".$data2["code"]."' placeholder='Seri hóa đơn' class='input code' style='$opa' $dis /></td>
						<td style='width:10%;'><span>Ngày đóng</span></td>
						<td style='width:10%;'><input type='text' value='".format_dateup($data2["date_dong2"])."' class='input dong' style='$opa' $dis /></td>
						<td><input type='text' class='input note' placeholder='Ghi chú' value='$data2[note]' style='$opa' $dis /></td>
						<td style='width:15%;'><span>Ngày nhập: ".format_dateup($data2["date_nhap"])."</span></td>
						<td style='width:10%;'>";
						if($dis=="") {
							echo"<input type='submit' class='submit del-thang' data-sttID='$data2[ID_STT]' value='Xóa' />";
                        } else {
                            echo"<span>$data2[name]</span>";
                        }
						echo"</td>
					</tr>";
				}
				echo"<tr><td colspan='9'><input type='submit' class='submit add-thang' data-monID='$data[ID_LM]' value='+' /></td></tr>";
			}
			echo"<tr><th style='border: none;' colspan='9'><input type='submit' class='submit' value='Nhập/Sửa tiền học' id='ok-nhap' style='width:50%;font-size:1.375em;' /></th></tr>";
            $nam = $temp0[0];
            $thang = $temp0[1];
			$pre="<tr class='active-tr' data-monID='' data-sttID='0'><td style='width:10%;'><select class='input select-thang' style='width:100%;height:auto;'>";
                for($i=0;$i<24;$i++) {
                    if($pre_date=="$nam-$thang") {
                        $pre.="<option value='$nam-$thang' selected='selected'>$thang/$nam</option>";
                    } else {
                        $pre.="<option value='$nam-$thang'>$thang/$nam</option>";
                    }
                    $temp2=explode("-",get_next_time($nam,$thang));
                    $nam=$temp2[0];
                    $thang=$temp2[1];
                }
			$pre.="</select></td><td style='width:10%;'><span>Số tiền</span></td><td style='width:7%;'><input type='number' min='100' max='9999' class='input money' placeholder='1000' /></td><td style='width:10%;'><input type='text' value='000' placeholder='Seri hóa đơn' class='input code' /></td><td style='width:10%;'><span>Ngày đóng</span></td><td style='width:10%;'><input type='text' class='input dong' value='".date("d/m/Y")."' placeholder='12/04/2016' /></td><td><input type='text' class='input note' placeholder='Ghi chú' /></td><td style='width:15%;'><span>Ngày nhập: ".format_dateup(date("Y-m-d"))."</span></td><td style='width:10%;'><input type='submit' class='submit kill-thang' value='Xóa' /></td></tr>";
			echo"<input type='hidden' value='".base64_encode($pre)."' id='pre-tr' />";
		} else {
			echo"none";
		}
	}
	
	if(isset($_POST["sttID1"]) && isset($_POST["code1"]) && isset($_POST["oID1"])) {
		$sttID=$_POST["sttID1"];
		$code=md5($_POST["code1"]);
		$oID=base64_decode($_POST["oID1"]);
		if(check_code_trogiang($oID,$code) && is_numeric($sttID) && $sttID!=0) {
			delete_tienhoc($sttID,$oID);
			echo"ok";
		} else {
			echo"Không tồn tại trợ giảng này! Hoặc dữ liệu không chính xác!";
		}
	}
	
//	if(isset($_POST["search_ngay"])) {
//		$ngay=$_POST["search_ngay"];
//		$stt=0;
//		echo"<tr style='background:#3E606F;'><th colspan='5'><span>Kết quả</span></th></tr>
//        <tr>
//            <td><span>STT</span></td>
//            <td><span>Mã số</span></td>
//            <td><span>Nội dung</span></td>
//            <td><span>Ngày nhập</span></td>
//            <td><span>Trợ giảng</span></td>
//        </tr>";
//		$query="SELECT t.*,h.cmt,o.note,m.name FROM tien_hoc AS t INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS INNER JOIN options AS o ON o.ID_O=t.who INNER JOIN lop_mon AS m ON m.ID_LM=t.ID_LM WHERE t.date_dong2='$ngay' ORDER BY t.ID_STT DESC";
//		$result=mysqli_query($db,$query);
//		while($data=mysqli_fetch_assoc($result)) {
//			echo"<tr>
//				<td><span>".($stt+1)."</span></td>
//				<td><span>$data[cmt]</span></td>
//				<td><span>Đóng tiền học tháng ".format_month($data["date_dong"])." môn $data[name]: ".format_price($data["money"])."</span></td>
//				<td><span>".format_dateup($data["date_nhap"])."</span></td>
//				<td><span>$data[note]</span></td>
//			</tr>";
//			$stt++;
//		}
//		$query="SELECT t.*,h.cmt,o.note AS note2 FROM tien_ra AS t INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS INNER JOIN options AS o ON o.ID_O=t.object WHERE t.string='nap-tien' AND t.date_dong='$ngay' ORDER BY t.date DESC";
//		$result=mysqli_query($db,$query);
//		while($data=mysqli_fetch_assoc($result)) {
//			echo"<tr>
//				<td><span>".($stt+1)."</span></td>
//				<td><span>$data[cmt]</span></td>
//				<td><span>$data[note]: ".format_price($data["price"])."</span></td>
//				<td><span>".format_dateup($data["date"])."</span></td>
//				<td><span>$data[note2]</span></td>
//			</tr>";
//			$stt++;
//		}
//		$query="SELECT t.*,h.cmt,o.note AS note2 FROM tien_vao AS t INNER JOIN hocsinh AS h ON h.ID_HS=t.ID_HS INNER JOIN options AS o ON o.ID_O=t.object WHERE t.string='rut-tien' AND t.date_dong='$ngay' ORDER BY t.date DESC";
//		$result=mysqli_query($db,$query);
//		while($data=mysqli_fetch_assoc($result)) {
//			echo"<tr>
//				<td><span>".($stt+1)."</span></td>
//				<td><span>$data[cmt]</span></td>
//				<td><span>$data[note]: ".format_price($data["price"])."</span></td>
//				<td><span>".format_dateup($data["date"])."</span></td>
//				<td><span>$data[note2]</span></td>
//			</tr>";
//			$stt++;
//		}
//		if($stt==0) {
//			echo"<tr><td colspan='5' style='text-align:center'><span>Không có dữ liệu</span></td></tr>";
//		}
//	}
	
	if(isset($_POST["tien4"]) && isset($_POST["date_dong4"]) && isset($_POST["ngay4"]) && isset($_POST["idVAO4"]) && isset($_POST["cmt4"]) && isset($_POST["oID4"])) {
		$cmt=$_POST["cmt4"];
		$tien=$_POST["tien4"];
		$date=$_POST["ngay4"];
		$date_dong=$_POST["date_dong4"];
		$idVAO=$_POST["idVAO4"];
		$oID=base64_decode($_POST["oID4"]);
		$hsID=check_exited_hocsinh2($cmt);
		if($hsID!=0 && is_numeric($hsID)) {
			if($idVAO!=0 && is_numeric($idVAO) && $tien>0 && is_numeric($tien) && $date_dong!="" && is_numeric($oID) && $oID!=0) {
				delete_phat($idVAO);
				tru_tien_hs2($hsID,$tien,"Bạn đã rút ".format_price($tien),"rut-tien",$oID,$date,format_date_o($date_dong));
				echo"ok";
			} else {
				echo"Lỗi dữ liệu!";
			}
		} else {
			echo"Không có học sinh này!";
		}
	}
	
	if(isset($_POST["idVAO7"])) {
		$idVAO=$_POST["idVAO7"];
		if($idVAO!=0 && is_numeric($idVAO)) {
			delete_phat($idVAO);
			echo"ok";
		} else {
			echo"Lỗi dữ liệu!";
		}
	}
	
	if(isset($_POST["tien5"]) && isset($_POST["seri"]) && isset($_POST["date_dong5"]) && isset($_POST["ngay5"]) && isset($_POST["idRA5"]) && isset($_POST["cmt5"]) && isset($_POST["oID5"])) {
		$cmt=$_POST["cmt5"];
        $seri=$_POST["seri"];
		$tien=$_POST["tien5"];
		$date=$_POST["ngay5"];
		$date_dong=$_POST["date_dong5"];
		$idRA=$_POST["idRA5"];
		$oID=base64_decode($_POST["oID5"]);
		$hsID=check_exited_hocsinh2($cmt);
		if($hsID!=0 && is_numeric($hsID)) {
			if($idRA!=0 && is_numeric($idRA) && $tien>0 && is_numeric($tien) && $date_dong!="" && is_numeric($oID) && $oID!=0) {
				delete_thuong($idRA);
				cong_tien_hs2($hsID,$tien,"Bạn đã nạp ".format_price($tien),"nap-tien",$oID,$seri,$date,format_date_o($date_dong));
				echo"ok";
			} else {
				echo"Lỗi dữ liệu!";
			}
		} else {
			echo"Không có học sinh này!";
		}
	}

	if(isset($_POST["idRA6"])) {
		$idRA=$_POST["idRA6"];
		if($idRA!=0 && is_numeric($idRA)) {
			delete_thuong($idRA);
			echo"ok";
		} else {
			echo"Lỗi dữ liệu!";
		}
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
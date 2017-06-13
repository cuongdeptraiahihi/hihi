<?php
	ob_start();
	//session_start();
	require("../../model/open_db.php");
	require("../../model/model.php");
	session_start();
	require_once("../access_hocsinh.php");
	$monID=$_SESSION["mon"];
	$lopID=$_SESSION["lop"];
    $lmID=$_SESSION["lmID"];

    if(isset($_POST["maso0"])) {
        $maso=base64_decode($_POST["maso0"]);
        $hsID=get_hs_id($maso);
        if($hsID!=0 && $hsID!=$_SESSION["ID_HS"]) {
            if(check_exited_nghihoc($hsID,$lmID)) {
                echo"Học sinh này đã nghỉ học!";
            } else {
                if($_SESSION["de"]==get_de_hs($hsID, $lmID)) {
                    $me_tb=tinh_diemtb_month2($_SESSION["ID_HS"],$lmID);
                    $ban_tb=tinh_diemtb_month2($hsID,$lmID);
                    if($me_tb!=0 && $ban_tb!=0) {
                        $chap = abs($me_tb - $ban_tb);
                        if ($chap <= 1) {
                            $chap3 = 0;
                        } else {
                            $chap2 = $chap - 1;
                            $chap3 = floor($chap2) + get_diem_near($chap2 - floor($chap2));
                        }

                        if($chap3>2) {
                            $chap3=2;
                        }

                        if($me_tb<=$ban_tb) {
                            echo -$chap3;
                        } else {
                            echo $chap3;
                        }
                    } else {
                        echo"Điểm TB của bạn bị lỗi!";
                    }
                } else {
                    echo"Phải cùng đề thì bạn mới có thể thách đấu!";
                }
            }
        } else {
            echo"Không tồn tại học sinh này!";
        }
    }
	
	if (isset($_POST["maso"]) && isset($_POST["chap"])) {
		if($_POST["maso"]!="" && is_numeric($_POST["chap"]))  {
			$cmt=base64_decode($_POST["maso"]);
			$chap=$_POST["chap"];
			$hsID=get_hs_id($cmt);
			if($chap>=-4 && $chap<=4 && $hsID!=0 && $hsID!=$_SESSION["ID_HS"]) {
				$check=check_exited_nghihoc($hsID, $lmID);
				if($check) {
					echo"Học sinh này đã nghỉ học!";
				} else {
					if($_SESSION["de"]==get_de_hs($hsID,$lmID)) {
						$tien=get_muctien("thach-dau");
						$taikhoan=get_tien_hs($_SESSION["ID_HS"]);
						if($taikhoan>=$tien) {
							$taikhoan2=get_tien_hs($hsID);
							if($taikhoan2>=$tien) { 
								$next_CN=get_next_CN();
								if(check_exited_thachdau($_SESSION["ID_HS"], $hsID, $next_CN, $lmID)) {
									echo"Bạn đã thách đấu bạn này cho bài kiểm tra tuần tới ".format_dateup($next_CN)."!";
								} else {
									$sttID=check_exited_thachdau2($_SESSION["ID_HS"], $hsID, $next_CN, $lmID);
									if($sttID!=0) {
										/*accept_thachdau2($_SESSION["ID_HS"], $sttID);
										add_thong_bao_hs($hsID,$sttID,"Lời thách đấu của bạn cho ".$_SESSION["fullname"]." (".tinh_diemtb_month2($_SESSION["ID_HS"],date("Y-m"),$diem_string).") vào ngày ".format_dateup($next_CN)." và bạn chấp $chap điểm đã được chấp nhận!","thach-dau",$monID);
										tru_tien_hs($_SESSION["ID_HS"], $tien, "Trừ tiền nhận thách đấu cho ngày thi ".format_dateup($next_CN),"","");
										echo"Bạn đã thách đấu thành công!";*/
										echo"Bạn này đang gửi lời thách đấu đến bạn!";
									} else {
                                        $me_tb=tinh_diemtb_month2($_SESSION["ID_HS"],$lmID);
                                        $ban_tb=tinh_diemtb_month2($hsID,$lmID);
                                        if($me_tb!=0 && $ban_tb!=0) {
                                            $chap0 = abs($me_tb - $ban_tb);
                                            if ($chap0 <= 1) {
                                                $chap3 = 0;
                                            } else {
                                                $chap2 = $chap0 - 1;
                                                $chap3 = floor($chap2) + get_diem_near($chap2 - floor($chap2));
                                            }
                                            if($chap3>2) {
                                                $chap3=2;
                                            }
                                            if($me_tb<$ban_tb) {
                                                $chap=-$chap3;
                                            } else {
                                                $chap=$chap3;
                                            }
                                            add_thachdau($_SESSION["ID_HS"], $hsID, $next_CN, $chap, $tien, $lmID);
                                            $id = mysqli_insert_id($db);
                                            tru_tien_hs($_SESSION["ID_HS"], $tien, "Trừ tiền đi thách đấu cho ngày thi " . format_dateup($next_CN),"thach-dau", "");
                                            if($ban_tb>=9 && $me_tb<=8) {
                                                accept_thachdau2($hsID,$id);
                                                tru_tien_hs($hsID, $tien, "Trừ tiền nhận thách đấu tự động cho ngày thi ".format_dateup($next_CN),"thach-dau","");
                                                add_thong_bao_hs($_SESSION["ID_HS"],$id,"Lời thách đấu của bạn cho mã số $cmt ($ban_tb) vào ngày ".format_dateup($next_CN)." và bạn được chấp ".abs($chap)." điểm đã được chấp nhận tự động!","thach-dau",$lmID);
                                                add_thong_bao_hs($hsID, $id, "Bạn nhận được lời thách đấu mới (tự động chấp nhận) của bạn XXX ($me_tb) vào ngày " . format_dateup($next_CN) . " và bạn sẽ chấp ".abs($chap)." điểm", "thach-dau", $lmID);
                                            } else {
                                                if($chap<0) {
                                                    add_thong_bao_hs2($hsID, $id, "Bạn nhận được lời thách đấu mới của bạn XXX ($me_tb) vào ngày " . format_dateup($next_CN) . " và bạn được đề nghị chấp bạn XXX ".abs($chap)." điểm", "thach-dau", $lmID);
                                                } else {
                                                    add_thong_bao_hs2($hsID, $id, "Bạn nhận được lời thách đấu mới của bạn XXX ($me_tb) vào ngày " . format_dateup($next_CN) . " và bạn được chấp ".abs($chap)." điểm", "thach-dau", $lmID);
                                                }
                                            }
                                            echo "Bạn đã thách đấu thành công!";
                                        } else {
                                            echo"Điểm TB của bạn bị lỗi!";
                                        }
									}
								}
							} else {
								echo"Tài khoản của bạn mà bạn thách đấu có ít hơn ".format_price($tien).", không đủ để thách đấu bạn ấy!";
							}
						} else {
							echo"Tài khoản bạn có ít hơn ".format_price($tien).", không đủ để thách đấu!";
						}	
					} else {
						echo"Phải cùng đề thì bạn mới có thể thách đấu!";
					}
				}
			} else {
				echo"Số điểm chấp không hợp lệ, phải >=-4 và <=4 hoặc mã số bị sai!";
			}
		} else {
			echo"Vui lòng cung cấp đầy đủ thông tin và chính xác!";
		}
	}
	
	/*if(isset($_POST["tdID"])) {
		$tdID=base64_decode($_POST["tdID"],false);
		if(is_numeric($tdID)) {
			delete_thachdau($_SESSION["ID_HS"], $tdID);
			echo"";
		} else {
			echo"Oops";
		}
	}*/
	
	if(isset($_POST["tdID0"])) {
		$tdID=base64_decode($_POST["tdID0"]);
		if(is_numeric($tdID)) {
			$result=cancle_thachdau($_SESSION["ID_HS"], $tdID);
			$data=mysqli_fetch_assoc($result);
			add_thong_bao_hs($data["ID_HS"],$tdID,"Lời thách đấu của bạn vào ngày ".format_dateup($data["buoi"])." cho ".$_SESSION["fullname"]." đã bị từ chối!","thach-dau",$lmID);
			cong_tien_hs($data["ID_HS"], $data["tien"], "Hoàn tiền do bị từ chối thách đấu cho ngày thi ".format_dateup($data["buoi"]),"thach-dau","");
			echo"";
		} else {
			echo"Oops";
		}
	}
	
	if(isset($_POST["tdID1"])) {
		$tdID=base64_decode($_POST["tdID1"]);
		if(is_numeric($tdID)) {
            $result=accept_thachdau($_SESSION["ID_HS"], $tdID);
            $data=mysqli_fetch_assoc($result);
            add_thong_bao_hs($data["ID_HS"],$tdID,"Lời thách đấu của bạn cho ".$_SESSION["fullname"]." (".tinh_diemtb_month2($_SESSION["ID_HS"],$lmID).") vào ngày ".format_dateup($data["buoi"])." đã được chấp nhận!","thach-dau",$lmID);
            tru_tien_hs($_SESSION["ID_HS"], $data["tien"], "Trừ tiền nhận thách đấu cho ngày thi ".format_dateup($data["buoi"]),"thach-dau","");
            echo"";
		} else {
			echo"Oops";
		}
	}
	
	if (isset($_POST["tdID2"])) {
		$tdID=base64_decode($_POST["tdID2"]);
		$result=callback_thachdau($tdID);
		$data=mysqli_fetch_assoc($result);
		$diem1=get_diem_hs2($data["ID_HS"],$data["buoi"],$lmID);
		$diem2=get_diem_hs2($data["ID_HS2"],$data["buoi"],$lmID);
		echo json_encode(
			array(
				"diem1" => $diem1,
				"diem2" => $diem2
			)
		);
	}
	
	require("../../model/close_db.php");
	ob_end_flush();
?>
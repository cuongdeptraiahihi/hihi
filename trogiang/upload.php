<?php
	ob_start();
	//session_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
	session_start();
	$hsID=$_SESSION["ID_HS"];
	$lopID=$_SESSION["lop"];
	$monID=$_SESSION["mon"];
    $id=2;
	
	$valid_exts = array('jpeg', 'jpg', 'png');
	$max_file_size = 1000 * 1024; #200kb
	$nw = $nh = 300; # image with # height
	$error=1;
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		if ( isset($_FILES['avata']) ) {
			if (! $_FILES['avata']['error'] && $_FILES['avata']['size'] < $max_file_size) {
				$ext = strtolower(pathinfo($_FILES['avata']['name'], PATHINFO_EXTENSION));
				if (in_array($ext, $valid_exts)) {
						
					$name = uniqid();	
					update_avata_trogiang($id,$name.".".$ext);
					
					$path = 'trogiang/avata/' . $name . '.' . $ext;
					$size = getimagesize($_FILES['avata']['tmp_name']);

					$x = (int) $_POST['x'];
					$y = (int) $_POST['y'];
					$w = (int) $_POST['w'] ? $_POST['w'] : $size[0];
					$h = (int) $_POST['h'] ? $_POST['h'] : $size[1];

					$data = file_get_contents($_FILES['avata']['tmp_name']);
					$vImg = imagecreatefromstring($data);
					$dstImg = imagecreatetruecolor($nw, $nh);
					imagecopyresampled($dstImg, $vImg, 0, 0, $x, $y, $nw, $nh, $w, $h);
					imagejpeg($dstImg, $path);
					imagedestroy($dstImg);
					// Thành công
					$error=1;
					
				} else {
					// Có lỗi xảy ra!
					$error=2;
				} 
			} else {
				// Ảnh quá to hoặc quá nhỏ!
				$error=3;
			}
		} else {
			// Chưa chọn ảnh!
			$error=4;
		}
	} else {
		//Lỗi dữ liệu!
		$error=5;
	}
	
	header("location:http://localhost/www/TDUONG/trogiang/ho-so/$error/");
	exit();
	
	ob_end_flush();
	require_once("../model/close_db.php");

?>
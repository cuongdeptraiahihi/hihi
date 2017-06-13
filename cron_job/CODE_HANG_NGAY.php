<?php
	ob_start();
	ini_set('max_execution_time', 600);
	require_once("/home/nginx/bgo.edu.vn/public_html/model/open_db.php");
	require_once("/home/nginx/bgo.edu.vn/public_html/model/model.php");
	
	// Code này reset danh sách học sinh có trong trừ tiền đổi ca 1 lần/ngày
	// Code chạy vào đêm hàng ngày 
	
	$query="DELETE FROM doica_history";
	mysqli_query($db,$query);
	
	// Code này thay đổi ảnh nền hằng ngày
	
	$query="UPDATE options SET note='none' WHERE type='background' AND note='active'";
	mysqli_query($db,$query);
    $query2="UPDATE options SET note='active' WHERE type='background' ORDER BY rand() LIMIT 1";
    mysqli_query($db,$query2);

    // Code này tự động unlock tháng đóng tiền học
    // Chạy vào mùng 20 hàng tháng
    $day = date("j");
    if($day == 20) {
        $next = get_next_time(date("Y"),date("m"));
        $result=get_all_mon();
        while($data=mysqli_fetch_assoc($result)) {
            open_thang_tienhoc($next,$data["ID_MON"]);
        }
    }

    // Code này kiểm tra số lượng câu trắc nghiệm đã làm
    $monID=1;
    $lopID=1;
    $date=getdate(date("U"));
    $current=$date["wday"]+1;
    if($current == 1) {
        $dem=0;
        $num=count_cau_hoi_tuan($lopID,$monID,date("Y-m-d"));
        $query="SELECT h.ID_HS FROM hocsinh AS h INNER JOIN hocsinh_mon AS m ON m.ID_HS=h.ID_HS AND m.ID_MON='$monID' WHERE h.lop='$lopID'";
        $result=mysqli_query($db,$query);
        while($data=mysqli_fetch_assoc($result)) {
            if (count_cau_hoi_hs_tuan($data["ID_HS"],$monID, date("Y-m-d")) < $num) {
                $dem++;
                add_options("", "khong-lam-du-cau", $data["ID_HS"], $monID);
            }
        }
        add_sync("Đã khóa trắc nghiệm của $dem em do không làm đủ $num câu");
    }
	
	// Code này kiểm tra thời hạn xem của học sinh (1 tuần)
	// Chạy vào 23h00 hàng ngày
	
	/*$today=date("Y-m-d");
	$today_date=date_create($today);
	$dem=0;
	$query="SELECT * FROM video_xem";
	$result=mysqli_query($db,$query);
	while($data=mysqli_fetch_assoc($result)) {
		$date=date_create($data["date"]);
		$diff=date_diff($date,$today_date);
		$kc=$diff->format("%a");
		if($kc>7) {
			$query2="DELETE FROM video_xem WHERE date='$data[date]'";
			mysqli_query($db,$query2);
			$dem++;
		}
	}*/
	
	ob_end_flush();
	require_once("/home/nginx/bgo.edu.vn/public_html/model/close_db.php");
?>
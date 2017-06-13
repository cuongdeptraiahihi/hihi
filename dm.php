<?php
    $mang = array(-5,3,7,-1,0,-5,6,10,-8);
    $mang = countingSort($mang);

    $length = count($mang);
    for($i = 0; $i < $length; $i++) {
        echo $mang[$i]." ";
    }

    /**
    " Hàm sắp xếp mảng theo thuật toán Counting Sort
     * @pramas	Nhận vào 1 mảng chưa sắp xếp
     * @return	Trả về 1 mảng đã sắp xếp
     **/
    function countingSort($mang) {
        // TODO
        // Viet code sap xep mang theo thu tu tang dan
        // Bước 1: Khởi tạo các biến đếm = 0 và đếm số lần xuất hiện của các phần tử trong mảng
        $length = count($mang);
        $dem_arr = array();
        for($i = 0; $i < $length; $i++) {
            if(!isset($dem_arr[$mang[$i]])) {
                // Khởi tạo biến đêm nếu chưa có
                $dem_arr[$mang[$i]] = 0;
            }
            $dem_arr[$mang[$i]]++;  // Tăng biến đếm lên
        }

        // Khởi tạo mảng mới
        $mang_new = array();

        // Bước 2: Tìm phần tử min max khi không biết khoảng giá trị
        $min = min($mang);  // Tìm phần tử nhỏ nhất
        $max = max($mang);  // Tìm phần tử max

        // Bước 3: In ra mảng và đã sắp xếp
        // Vì đã có phần tử min và max nên tất cả các giá trị còn lại sẽ nằm giữa min và max
        for($i = $min; $i <= $max; $i++) {
            if(isset($dem_arr[$i])) {  // Nếu và tồn tại biến đếm cho giá trị đó
                for($j = 0; $j < $dem_arr[$i]; $j++) {  // Dựa vào số lần xuất hiện để in ra các giá trị
                    $mang_new[] = $i;   // Thêm giá trị đó vào mảng mới
                }
            }
        }

        return $mang_new;   // Trả về mảng mới đã sắp xếp
    }
?>
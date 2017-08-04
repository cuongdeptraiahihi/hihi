<?php
	ob_start();
	require_once("../model/open_db.php");
	require_once("../model/model.php");
    header("Content-type: application/json");

    if(isset($_GET["code"]) && isset($_GET["userid"]) && isset($_GET["accesscode"])) {
        $ketqua = array(
            "Hocsinh" => array(
                "ID" => null,
                "Username" => null,
                "Content" => "EMPTY",
                "Avata" => "placeholder.jpg",
                "Name" => "None",
                "Birth" => null,
                "Gender" => 0,
                "Facebook" => null,
                "School" => null,
                "Phone" => null,
                "Account" => 0,
                "DadPhone" => null,
                "MomPhone" => null
            ),
            "MonInfo" => array(),
            "TienHoc" => array(),
            "DiemDanh" => array()
        );

        $count_mon = 0;

        $code = addslashes($_GET["code"]);
        $id = addslashes($_GET["userid"]);
        $access = addslashes($_GET["accesscode"]);
        $result0 = get_admin($id);
        if(mysqli_num_rows($result0) != 0) {
            $data0 = mysqli_fetch_assoc($result0);
            if (md5($id . "|" . $data0["password"]) == $access) {
                $query = "SELECT h.ID_HS,h.cmt,h.fullname,h.avata,h.birth,h.gender,h.facebook,t.name,h.sdt,h.sdt_bo,h.sdt_me,h.taikhoan 
                  FROM hocsinh AS h 
                  LEFT JOIN truong AS t ON t.ID_T=h.truong
                  WHERE h.cmt='$code'";
                $result = mysqli_query($db,$query);
                if(mysqli_num_rows($result) != 0) {
                    $data = mysqli_fetch_assoc($result);
                    $ketqua["Hocsinh"]["ID"] = $data["ID_HS"];
                    $ketqua["Hocsinh"]["Username"] = $data["cmt"];
                    $ketqua["Hocsinh"]["Content"] = "OK";
                    $ketqua["Hocsinh"]["Avata"] = "https://bgo.edu.vn/hocsinh/avata/" . $data["avata"];
                    $ketqua["Hocsinh"]["Name"] = $data["fullname"];
                    $ketqua["Hocsinh"]["Birth"] = format_dateup($data["birth"]);
                    $ketqua["Hocsinh"]["Gender"] = $data["gender"];
                    $ketqua["Hocsinh"]["Facebook"] = formatFacebook($data["facebook"]);
                    $ketqua["Hocsinh"]["School"] = $data["name"];
                    $ketqua["Hocsinh"]["Phone"] = $data["sdt"];
                    $ketqua["Hocsinh"]["Account"] = $data["taikhoan"];
                    $ketqua["Hocsinh"]["DadPhone"] = $data["sdt_bo"];
                    $ketqua["Hocsinh"]["MomPhone"] = $data["sdt_me"];

                    $query1 = "SELECT m.ID_LM,m.de,m.date_in,n.ID_N,l.name,l.ID_MON FROM hocsinh_mon AS m 
                      INNER JOIN lop_mon AS l ON l.ID_LM=m.ID_LM
                      LEFT JOIN hocsinh_nghi AS n ON n.ID_HS=m.ID_HS AND n.ID_LM=m.ID_LM
                      WHERE m.ID_HS='$data[ID_HS]' ORDER BY m.ID_STT ASC";
                    $result1 = mysqli_query($db,$query1);
                    while($data1 = mysqli_fetch_assoc($result1)) {
                        if(isset($data1["ID_N"]))
                            continue;

                        $ketqua["MonInfo"][$count_mon] = array(
                            "Id" => $data1["ID_LM"],
                            "Name" => $data1["name"],
                            "MonID" => $data1["ID_MON"]
                        );

                        $ketqua["TienHoc"][$count_mon] = array(
                            "Title" => "Tiền học",
                            "I1" => null,
                            "I2" => null,
                            "I3" => null,
                            "I4" => null,
                            "I5" => null,
                            "I1Data" => null,
                            "I2Data" => null,
                            "I3Data" => null,
                            "I4Data" => null,
                            "I5Data" => null
                        );

                        $temp = explode("-", $data1["date_in"]);
                        $year_in = $temp[0];
                        $month_in = $temp[1];

                        $now = date("Y-m");
                        $temp = explode("-", $now);
                        $year = $temp[0];
                        $month = $temp[1];

                        $n = 1;
                        $mon_arr = array();
                        for($i = -2; $i <= 2; $i++) {
                            if($month + $i < 1) {
                                $year_cur = $year - 1;
                                $month_cur = 12 + ($month + $i);
                            } else if($month + $i > 12) {
                                $year_cur = $year + 1;
                                $month_cur = ($month + $i - 12);
                            } else {
                                $year_cur = $year;
                                $month_cur = $month + $i;
                            }

                            if($year_cur < $year_in || ($year_cur == $year_in && $month_cur < $month_in)) {
                                continue;
                            }

                            $mon_need = $year_cur . "-" . format_month_db($month_cur);

                            $mon_arr[] = "'" . $mon_need . "'";

                            $ketqua["TienHoc"][$count_mon]["I" . $n] = format_month2($mon_need);
                            $ketqua["TienHoc"][$count_mon]["I" . $n . "Data"] = "?";
                            $n++;
                        }

                        $mon_str = implode(",", $mon_arr);

                        $query2 = "SELECT money FROM tien_hoc WHERE ID_HS='$data[ID_HS]' AND ID_LM='$data1[ID_LM]' AND date_dong IN ($mon_str) ORDER BY date_dong ASC LIMIT 5";
                        $result2 = mysqli_query($db,$query2);
                        $n = 1;
                        while ($data2 = mysqli_fetch_assoc($result2)) {
                            $ketqua["TienHoc"][$count_mon]["I" . $n . "Data"] = format_price_sms($data2["money"]);
                            $n++;
                        }

                        $ketqua["DiemDanh"][$count_mon] = array(
                            "Title" => "Điểm danh",
                            "I1" => null,
                            "I2" => null,
                            "I3" => null,
                            "I4" => null,
                            "I5" => null,
                            "I1Data" => null,
                            "I2Data" => null,
                            "I3Data" => null,
                            "I4Data" => null,
                            "I5Data" => null
                        );

//                        $query2 = "SELECT d.ID_STT,b.date FROM diemdanh_buoi AS b
//                                  LEFT JOIN diemdanh AS d ON d.ID_HS='$data[ID_HS]' AND d.ID_DD=b.ID_CUM
//                                  WHERE b.ID_LM='$data1[ID_LM]' AND b.ID_MON='$data1[ID_MON]'
//                                  ORDER BY b.ID_CUM DESC
//                                  LIMIT 5";
//                        $result2 = mysqli_query($db,$query2);
//                        $n = mysqli_num_rows($result2);
//                        while ($data2 = mysqli_fetch_assoc($result2)) {
//                            $temp = explode("-", $data2["date"]);
//                            if($temp[0] < $year_in || ($temp[0] == $year_in && $temp[1] < $month_in)) {
//                                continue;
//                            }
//
//                            $ketqua["DiemDanh"][$count_mon]["I" . $n] = format_date($data2["date"]);
//
//                            if(isset($data2["ID_STT"]) && is_numeric($data2["ID_STT"])) {
//                                $ketqua["DiemDanh"][$count_mon]["I" . $n . "Data"] = "x";
//                            } else {
//                                $ketqua["DiemDanh"][$count_mon]["I" . $n . "Data"] = "?";
//                            }
//
//                            $n--;
//                        }

                        $count_mon++;
                    }
                } else {
                    $ketqua["Content"] = "CODE_FAIL";
                }
            } else {
                $ketqua["Content"] = "ACCESS_FAIL";
            }
        } else {
            $ketqua["Content"] = "LOGIN_FAIL";
        }

        echo json_encode($ketqua);
    }

    if(isset($_GET["search"]) && isset($_GET["limit"]) && isset($_GET["userid"]) && isset($_GET["accesscode"])) {
        $search = addslashes($_GET["search"]);
        $limit = addslashes($_GET["limit"]);
        $id = addslashes($_GET["userid"]);
        $access = addslashes($_GET["accesscode"]);

        $ketqua = array();

        $result0 = get_admin($id);
        if(mysqli_num_rows($result0) != 0) {
            $data0 = mysqli_fetch_assoc($result0);
            if (md5($id . "|" . $data0["password"]) == $access) {
                $query = "SELECT ID_HS,cmt,fullname FROM hocsinh WHERE cmt LIKE '%$search%' OR namestring LIKE '%".unicode_convert($search)."%' ORDER BY cmt ASC LIMIT " . $limit;
                $result = mysqli_query($db,$query);
                while($data = mysqli_fetch_assoc($result)) {
                    $ketqua[] = array(
                        "ID" => $data["ID_HS"],
                        "Username" => $data["cmt"],
                        "Name" => $data["fullname"]
                    );
                }
            }
        }

        echo json_encode($ketqua);
    }

	ob_end_flush();
	require_once("../model/close_db.php");
?>
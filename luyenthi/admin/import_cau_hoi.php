
    <?php
        ini_set('max_execution_time', 900);
        include_once "../include/top.php";
    ?>

    <!-- Page header -->
    <div class="page-header">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Câu hỏi</span> - Import câu hỏi từ <strong>Excel</strong></h4>
            </div>
        </div>
    </div>
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "../include/sidebar.php"; ?>

            <?php
                $file = NULL;
                $error = false;
                $error2 = false;
                $error_msg = "";
                $error_msg2 = "";
                $check = 0;
                if(isset($_POST["import-ok"])) {
                    if($_FILES["submit-file-zip"]["error"]>0) {
                        $error2 = true;
                        $error_msg2 = "Vui lòng chọn một file!";
                    } else {
                        $file = addslashes($_FILES["submit-file-zip"]["name"]);
                        $target_file = basename($file);
                        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                        if ($imageFileType != "zip") {
                            $error2 = true;
                            $error_msg2 = "Chỉ chấp nhận file ZIP";
                        } else {
                            move_uploaded_file($_FILES["submit-file-zip"]["tmp_name"], "../upload/$lmID/" . $_FILES["submit-file-zip"]["name"]);
                            $error_msg2 = "Upload file ảnh thành công!";
                            $pre = $_FILES["submit-file-zip"]["name"];
                            $pre = explode(".",$pre);
                            $pre = $pre[0];

                            $excel_file = "dethi.xlsx";
                            $img_type = array();

                            $zip = new ZipArchive();
                            $x = $zip->open("../upload/$lmID/" . $_FILES["submit-file-zip"]["name"]);  // open the zip file to extract
                            if ($x === true) {
                                $zip->extractTo("../upload/$lmID/"); // place in the directory with same name
                                for($i = 0; $i < $zip->numFiles; $i++) {
                                    $filename = $zip->getNameIndex($i);
                                    $temp = explode(".",$filename);
                                    $file_type=end($temp);
                                    if($file_type == "xlsx") {
                                        $excel_file = $filename;
                                    } else {
                                        $img_type[$temp[0]] = end($temp);
                                    }
                                }
                                $zip->close();

                                unlink("../upload/$lmID/" . $_FILES["submit-file-zip"]["name"]);

                                if(isset($_POST["submit-check"]) && !empty($_POST["submit-check"])) {
                                    $check = 1;
                                }

                                include("../model/PHPExcel/IOFactory.php");
                                $objPHPExcel = PHPExcel_IOFactory::load("../upload/$lmID/$excel_file");
                                $da_arr["A"] = 1;
                                $da_arr["B"] = 2;
                                $da_arr["C"] = 3;
                                $da_arr["D"] = 4;
                                $da_arr["E"] = 5;
                                $da_arr["F"] = 6;
                                $da_arr["G"] = 7;
                                $da_arr["H"] = 8;
                                $html = "";
                                foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                                    $highestRow = $worksheet->getHighestRow();
                                    $cID = 0;
                                    $da = array();
                                    $level = 0;
                                    $de = $note = $num = $maso = "";
                                    $dapan = $de_img = "none";
                                    $de_start = true;
                                    $da_start = false;
                                    $count = 1;
                                    for ($i = 1; $i <= $highestRow; $i++) {
                                        $db = new Cau_Hoi();
                                        $cau = trim($worksheet->getCellByColumnAndRow(0, $i)->getValue());
                                        $content = $worksheet->getCellByColumnAndRow(1, $i)->getValue();
                                        $extra1 = strtolower($worksheet->getCellByColumnAndRow(2, $i)->getValue());
                                        if(isset($img_type[$extra1])) {
                                            $extra1 = $extra1.".".$img_type[$extra1];
                                        }
                                        if($extra1 != "") {
                                            $extra = $pre."-".$extra1;
                                        } else {
                                            $extra = $extra1;
                                        }

                                        $cau_temp = substr($cau,0,1);
                                        //echo"$cau_temp<br />";
                                        switch ($cau_temp) {
                                            case 1:
                                                $de_start = true;
                                                $maso = substr($cau,2);
                                                if($extra != "") {
                                                    $de_img = $extra;
                                                    rename("../upload/$lmID/$extra1","../upload/$lmID/$extra");
                                                } else {
                                                    if($content != "") {
                                                        $de = $content;
                                                    }
                                                }
                                                break;
                                            case 2:
                                                $de_start = false;
                                                if($extra != "") {
                                                    $da[] = array(
                                                        "content" => $extra,
                                                        "type" => "image"
                                                    );
                                                    rename("../upload/$lmID/$extra1","../upload/$lmID/$extra");
                                                } else {
                                                    $da[] = array(
                                                        "content" => $content,
                                                        "type" => "text"
                                                    );
                                                }
                                                break;
                                            case 3:
                                                $num = $da_arr[$content];
                                                break;
                                            case 4:
                                                $da_start = true;
                                                if($extra != "") {
                                                    $dapan = "|<|" . $extra . "|>|";
                                                    rename("../upload/$lmID/$extra1","../upload/$lmID/$extra");
                                                } else {
                                                    if($content != "") {
                                                        $dapan = $content;
                                                    }
                                                }
                                                break;
                                            case 5:
                                                $da_start = false;
                                                $level = $content + 1;
                                                break;
                                            case 6:
                                                $note = $content;

                                                $maso = $pre."-".$maso;
                                                $cID_old = $db->checkIssetCauHoi($maso);
                                                if($cID_old == 0) {
                                                    $temp = $db->addCauHoi($maso, $de, $de_img, "trac-nghiem", 0, $level, 1, 0, $note, $check, $lmID);
                                                    $cID = $temp[1];
                                                } else {
                                                    $db->editCauHoi($cID_old, $maso, $de, $de_img, "trac-nghiem", 0, $level, 1, 0, $note, $check, $lmID);
                                                }

                                                $html.="<br />Câu $count<br />Đề bài: $de<br />";

                                                if($cID_old == 0) {
                                                    $db->addDapAnDai($dapan, "none", $cID);
                                                } else {
                                                    $db->editDapAnDai($cID_old,$dapan,"none",true);
                                                }

                                                $html.="<br />Đáp án: $dapan<br /><br />";

                                                if($cID_old == 0) {
                                                    for ($j = 0; $j < count($da); $j++) {
                                                        if ($j == $num - 1) {
                                                            $db->addDapAnNgan($da[$j]["content"], $da[$j]["type"], 1, $cID, 1);
                                                            $html.="+) (Đúng) ".$da[$j]["content"]."<br />";
                                                        } else {
                                                            $db->addDapAnNgan($da[$j]["content"], $da[$j]["type"], 0, $cID, 1);
                                                            $html.="+) ".$da[$j]["content"]."<br />";
                                                        }
                                                    }
                                                    $db->addDapAnNgan("Em không làm được", "text", 0, $cID, 0);
                                                    $db->addDapAnNgan("Đáp án khác...................", "text", 0, $cID, 0);
                                                    $html.="+) Em không làm được<br />";
                                                    $html.="+) Đáp án khác...................<br />";
                                                } else {
                                                    $j = 0;
                                                    $result = $db->getDapAnNgan($cID_old,false);
                                                    while($data = $result->fetch_assoc()) {
                                                        if ($j == $num - 1) {
                                                            $db->editDapAnNgan($data["ID_DA"], $da[$j]["content"], $da[$j]["type"], 1);
                                                        } else {
                                                            $db->editDapAnNgan($data["ID_DA"], $da[$j]["content"], $da[$j]["type"], 0);
                                                        }
                                                        $j++;
                                                    }
                                                }

                                                $html.="<br />Level: $level<br />";
                                                $html.="Ghi chú: $note<br />";
                                                $html.="--------------------------------------------------------------------------------------------------------------------------";

                                                $cID = 0;
                                                $da = array();
                                                $level = 0;
                                                $de = $note = $num = $maso = "";
                                                $dapan = $de_img = "none";
                                                $de_start = true;
                                                $da_start = false;

                                                $count++;

                                                break;
                                            default:
                                                if($de_start) {
                                                    if($extra != "") {
                                                        $de_img = $extra;
                                                        rename("../upload/$lmID/$extra1","../upload/$lmID/$extra");
                                                    } else {
                                                        if($content != "") {
                                                            $de .= "<br />" . $content;
                                                        }
                                                    }
                                                }
                                                if($da_start) {
                                                    if($extra != "") {
                                                        $dapan .= "<br />|<|" . $extra . "|>|";
                                                        rename("../upload/$lmID/$extra1","../upload/$lmID/$extra");
                                                    } else {
                                                        if($content != "") {
                                                            $dapan .= "<br />" . $content;
                                                        }
                                                    }
                                                }
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                    }

//                    if(!$error || !$error2) {
//                        header("location:http://localhost/www/TDUONG/luyenthi/admin/danh-sach-cau-hoi/");
//                        exit();
//                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/import_cau_hoi.php" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                <div class="form-cau-hoi">
                    <div class="col-lg-1">
                    </div>
                    <div class="col-lg-8">
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">Up file Excel</h5>
                            </div>
                            <div class="panel-body">
                                <?php if($error_msg2 != "") { ?>
                                    <div class="alert alert-danger no-border">
                                        <span class="text-semibold">Kết quả: </span>
                                        <?php echo $error_msg2; ?>
                                    </div>
                                <?php } ?>
                                <p class="content-group">File *.zip được mô tả như sau:<br />+ * là tên file zip<br />+ Trong file zip gồm 1 file Excel tên <strong>dethi.xlsx</strong> và các ảnh có trong đề<br />+ Ảnh có định dạng <strong>*.png</strong></p>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <label class="control-label">Chọn file *.zip</label>
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="file" name="submit-file-zip" class="file-input" data-show-caption="false" data-show-upload="false">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12" style="text-align: right;">
                                            <button type="submit" name="import-ok" class="btn btn-primary btn-sm bg-blue-400 import-cau-hoi">Import</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <?php if(isset($html) && $html != "") { ?>
                                    <div class="alert alert-danger no-border">
                                        <span class="text-semibold">Đầu ra: </span>
                                        <?php echo $html; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">Mô tả các cột file Excel</h5>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="text-center">Câu</th>
                                    <th class="text-center">Đề bài</th>
                                    <th class="text-center">Ảnh (nếu có)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">1."Mã đề"</td>
                                    <td class="text-center">"Nội dung đề bài"</td>
                                    <td class="text-center">"Tên ảnh" (không cần đuôi)</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2.A</td>
                                    <td class="text-center">"Đáp án A"</td>
                                    <td class="text-center">"Tên ảnh"</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2.B</td>
                                    <td class="text-center">"Đáp án B"</td>
                                    <td class="text-center">"Tên ảnh"</td>
                                </tr>
                                <tr>
                                    <td class="text-center">2.C</td>
                                    <td class="text-center">"Đáp án C"</td>
                                    <td class="text-center">"Tên ảnh"</td>
                                </tr>
                                <tr>
                                    <td class="text-center">3.Đáp số</td>
                                    <td class="text-center">"A"</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-center">4.Đáp án</td>
                                    <td class="text-center">"Nội dung đáp án"</td>
                                    <td class="text-center">"Tên ảnh"</td>
                                </tr>
                                <tr>
                                    <td class="text-center">5.Level</td>
                                    <td class="text-center">"2"</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="text-center">6.Ghi chú</td>
                                    <td class="text-center">"Nội dung ghi chú không xuống dòng"</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-lg-3">

                        <!-- Sales stats -->
                        <div class="panel panel-flat">
                            <div class="panel-heading">
                                <h5 class="panel-title">Tùy chọn</h5>
                            </div>
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" name="submit-check">
                                                    Các câu hỏi đều đã được check
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- /main form -->
                </form>
            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

        <?php include_once "../include/bottom.php"; ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("body").addClass("sidebar-xs");

                $(".import-cau-hoi").click(function () {
                    if(confirm("Bạn có chắc chắn?")) {
                        return true;
                    } else {
                        return false;
                    }
                });
            });
        </script>

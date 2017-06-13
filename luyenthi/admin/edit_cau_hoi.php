
    <?php include_once "../include/top.php"; ?>
    <?php
        if(isset($_GET["cID"]) && is_numeric($_GET["cID"])) {
            $cID = $_GET["cID"];
        } else {
            $cID = 0;
        }
        $db = new Cau_Hoi();
        $db2 = new Nhom_Cau_Hoi();
        $result0 = $db->getCauHoiById($cID);
        $data0 = $result0->fetch_assoc();
        $result1 = $db->getDapAnDai($cID);
        $data1 = $result1->fetch_assoc();
        $sum_da = 6;
        $nhom = 0;
        $monID = $data0["ID_MON"];
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Câu hỏi</span> - Sửa câu hỏi #--><?php //echo $cID; ?><!--</h4>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <!-- /page header -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <?php include_once "../include/sidebar.php"; ?>

            <?php
                $error_msg = "";
                $error = false;
                if(isset($_POST["submit-del-ok"])) {
                    if($db->xoaCauHoi($cID)) {
                        header("location:http://localhost/www/TDUONG/luyenthi/admin/danh-sach-cau-hoi/");
                        exit();
                    } else {
                        $error = true;
                        $error_msg = "Câu hỏi này đã được sử dụng ở các đề thi!";
                    }
                }
                $da_arr = $da_sua = array();
                $content = $anh_title = $da_chi_tiet = $anh_chi_tiet = $note = "none";
                $cdID=$loai=$do_kho=$da_dien_tu_id=$nhom=$lop=0;
                $check_de_anh = $check_da_anh = true;
                $type_da = "trac-nghiem";
                $maso = $da_dien_tu = $nhom_name = "";
                if(isset($_POST["submit-add-ok"])) {
                    if($_FILES["submit-anh-title"]["error"]>0) {
                        if(isset($_POST["check-de-anh"]) && !empty($_POST["check-de-anh"])) {
                            $check_de_anh = true;
                        } else {
                            $check_de_anh = false;
                        }
                    } else {
                        $anh_title = addslashes($_FILES["submit-anh-title"]["name"]);
                    }
                    if(isset($_POST["submit-maso"]) && !empty($_POST["submit-maso"])) {
                        $maso = $_POST["submit-maso"];
                    }
                    if(isset($_POST["submit-content"]) && !empty($_POST["submit-content"])) {
                        $content = $_POST["submit-content"];
                    }
                    if(isset($_POST["submit-cd"]) && !empty($_POST["submit-cd"])) {
                        $cdID = $_POST["submit-cd"];
                    }
                    if(isset($_POST["submit-loai"]) && !empty($_POST["submit-loai"])) {
                        $loai = $_POST["submit-loai"];
                    }
                    if(isset($_POST["submit-type-da"]) && !empty($_POST["submit-type-da"])) {
                        $type_da = $_POST["submit-type-da"];
                    }
                    if(isset($_POST["submit-do-kho"]) && !empty($_POST["submit-do-kho"])) {
                        $do_kho = $_POST["submit-do-kho"];
                    }
                    if(isset($_POST["submit-da-chi-tiet"]) && !empty($_POST["submit-da-chi-tiet"])) {
                        $da_chi_tiet = $_POST["submit-da-chi-tiet"];
                    }
                    if($_FILES["submit-da-anh-chi-tiet"]["error"]>0) {
                        if(isset($_POST["check-da-anh"]) && !empty($_POST["check-da-anh"])) {
                            $check_da_anh = true;
                        } else {
                            $check_da_anh = false;
                        }
                    } else {
                        $anh_chi_tiet = addslashes($_FILES["submit-da-anh-chi-tiet"]["name"]);
                    }
                    if(isset($_POST["submit-note"]) && !empty($_POST["submit-note"])) {
                        $note = $_POST["submit-note"];
                    }
                    if(isset($_POST["submit-nhom-name"]) && !empty($_POST["submit-nhom-name"])) {
                        $nhom_name = $_POST["submit-nhom-name"];
                    } else if(isset($_POST["submit-nhom"])) {
                        $nhom = $_POST["submit-nhom"];
                    }
                    if(isset($_POST["submit-dien-tu"]) && !empty($_POST["submit-dien-tu"])) {
                        $type_da = "dien-tu";
                        $da_dien_tu = $_POST["submit-dien-tu"];
                        $result2 = $db->getDapAnNgan($cID,false);
                        $data2 = $result2->fetch_assoc();
                        $da_dien_tu_id = $data2["ID_DA"];
                    } else {
                        if(isset($_POST["submit-check-da"]) && !empty($_POST["submit-check-da"])) {
                            $dap_an_dung = $_POST["submit-check-da"];
                        } else {
                            $dap_an_dung = 0;
                        }
                        $has_dung = false;
                        $type_da = "trac-nghiem";
                        $dem = 1;
                        $result2 = $db->getDapAnNgan($cID,false);
                        while ($data2 = $result2->fetch_assoc()) {
                            $is_skip = false;
                            if ($data2["ID_DA"] == $dap_an_dung && !$has_dung) {
                                $is_dung = 1;
                            } else {
                                $is_dung = 0;
                            }
                            $da_content = $da_type = "none";
                            if (isset($_POST["submit-da-content-$data2[ID_DA]"]) && !empty($_POST["submit-da-content-$data2[ID_DA]"])) {
                                $da_content = $_POST["submit-da-content-$data2[ID_DA]"];
                                $da_type = "text";
                                if($is_dung == 1) {$has_dung = true;}
                            } else {
                                if ($_FILES["submit-da-anh-$data2[ID_DA]"]["error"] > 0) {
                                    if (isset($_POST["submit-use-anh-$data2[ID_DA]"]) && !empty($_POST["submit-use-anh-$data2[ID_DA]"])) {
                                        $da_content = "none";
                                        if($is_dung == 1) {$has_dung = true;}
                                    } else {
                                        $is_skip = true;
                                    }
                                } else {
                                    $da_content = addslashes($_FILES["submit-da-anh-$data2[ID_DA]"]["name"]);
                                    if($is_dung == 1) {$has_dung = true;}
                                }
                                $da_type = "image";
                            }
                            $da_sua[] = array(
                                "stt" => $data2["ID_DA"],
                                "is_dung" => $is_dung,
                                "da_content" => $da_content,
                                "da_type" => $da_type,
                                "is_del" => $is_skip
                            );
                            $dem++;
                        }
                        for ($i = 1; $i <= $sum_da - $dem + 1; $i++) {
                            $is_skip = false;
                            if ($i == $dap_an_dung && !$has_dung) {
                                $is_dung = 1;
                            } else {
                                $is_dung = 0;
                            }
                            $da_content = $da_type = "none";
                            if (isset($_POST["submit-da-content-$i"]) && !empty($_POST["submit-da-content-$i"])) {
                                $da_content = $_POST["submit-da-content-$i"];
                                $da_type = "text";
                                if($is_dung == 1) {$has_dung = true;}
                            } else {
                                if ($_FILES["submit-da-anh-$i"]["error"] > 0) {
                                    $is_skip = true;
                                } else {
                                    $da_content = addslashes($_FILES["submit-da-anh-$i"]["name"]);
                                    $da_type = "image";
                                    if($is_dung == 1) {$has_dung = true;}
                                }
                            }
                            if (!$is_skip) {
                                $da_arr[] = array(
                                    "stt" => $i,
                                    "is_dung" => $is_dung,
                                    "da_content" => $da_content,
                                    "da_type" => $da_type
                                );
                            }
                        }
                        if(!$has_dung) {
                            $da_arr = $da_sua = array();
                        }
                    }
                    if(($anh_title!="none" || $content!="none")) {
//                        if($nhom_name != "") {
//                            $db = new Nhom_Cau_Hoi();
//                            $nhom = $db->addNhom($nhom_name,$lmID);
//                        }
                        $main = 0;
                        $temp3 = explode("-",$maso);
                        if(isset($temp3[2]) && stripos($temp3[2],"a") !== false) {
                            $main = 1;
                        }
                        $db->editCauHoi($cID,$maso,$content,$anh_title,$type_da,$cdID,$do_kho,$loai,$nhom,$note,$check_de_anh,$monID,$main);
                        if(!is_dir("http://localhost/www/TDUONG/luyenthi/upload/$monID")){
                            mkdir("http://localhost/www/TDUONG/luyenthi/upload/$monID");
                        }
                        if(!is_dir("http://localhost/www/TDUONG/luyenthi/upload/$monID/dapan")){
                            mkdir("http://localhost/www/TDUONG/luyenthi/upload/$monID/dapan");
                        }
                        move_uploaded_file($_FILES["submit-anh-title"]["tmp_name"],"http://localhost/www/TDUONG/luyenthi/".$db->getUrlDe($monID,$_FILES["submit-anh-title"]["name"]));
                        $db->editDapAnDai($cID,$da_chi_tiet,$anh_chi_tiet,$check_da_anh);
                        move_uploaded_file($_FILES["submit-da-anh-chi-tiet"]["tmp_name"],"http://localhost/www/TDUONG/luyenthi/".$db->getUrlDapAn($monID,$_FILES["submit-da-anh-chi-tiet"]["name"]));
                        if($type_da == "trac-nghiem") {
                            for ($i = 0; $i < count($da_sua); $i++) {
                                if ($da_sua[$i]["is_del"]) {
//                                    $db->delDapAnNgan($da_sua[$i]["stt"]);
                                } else {
                                    $db->editDapAnNgan($da_sua[$i]["stt"], $da_sua[$i]["da_content"], $da_sua[$i]["da_type"], $da_sua[$i]["is_dung"]);
                                    if ($da_sua[$i]["da_type"] == "image") {
                                        move_uploaded_file($_FILES["submit-da-anh-" . $da_sua[$i]["stt"]]["tmp_name"], "http://localhost/www/TDUONG/luyenthi/".$db->getUrlDapAn($monID, $_FILES["submit-da-anh-" . $da_sua[$i]["stt"]]["name"]));
                                    }
                                }
                            }
                            for ($i = 0; $i < count($da_arr); $i++) {
                                $db->addDapAnNgan($da_arr[$i]["da_content"], $da_arr[$i]["da_type"], $da_arr[$i]["is_dung"], $cID, 1);
                                if ($da_arr[$i]["da_type"] == "image") {
                                    move_uploaded_file($_FILES["submit-da-anh-" . $da_arr[$i]["stt"]]["tmp_name"], "http://localhost/www/TDUONG/luyenthi/".$db->getUrlDapAn($monID, $_FILES["submit-da-anh-" . $da_arr[$i]["stt"]]["name"]));
                                }
                            }
                        } else if($type_da == "dien-tu") {
                            $db->editDapAnNgan($da_dien_tu_id,$da_dien_tu, "text", 1);
                        } else {

                        }
                        $error = false;
                        $error_msg .= " Bạn đã sửa câu hỏi thành công!";
                    } else {
                        $error = true;
                        $error_msg = " Vui lòng nhập đầy đủ thông tin!";
                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/<?php echo $cID; ?>/" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                <div class="form-cau-hoi">
                    <div class="col-lg-8">
                        <div class="panel-heading">
                            <h5 class="panel-title"><?php echo $data0["maso"]; ?></h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <?php if($error_msg != "") { ?>
                                    <div class="alert alert-primary no-border">
                                        <span class="text-semibold">Kết quả: </span> <?php echo $error_msg; ?>
                                    </div>
                                <?php } ?>
                                <p class="content-group">Nội dung câu hỏi và đáp án <strong>không được bao gồm</strong> các từ đặc biệt như <code>'</code>, <code>></code>, <code><</code>, <code>\[</code>, <code>\)</code>, <code>mml</code>, ...</p>
<!--                                <form class="form-horizontal" action="#">-->
                                    <fieldset class="content-group">
                                        <div class="form-group">
                                            <div class="div-has-img">
                                                <label class="control-label col-sm-3">Đề bài + Ảnh (nếu có)</label>
                                                <?php if($data0["anh"] != "none") { ?>
                                                    <input type="checkbox" name="check-de-anh" checked="checked" class="control-danger check-use-anh check-de-anh"/>
                                                    <img src="http://localhost/www/TDUONG/luyenthi/upload/<?php echo $monID."/".$data0["anh"]; ?>" class="img-thumbnail img-responsive" />
                                                <?php } ?>
                                            </div>
                                            <div class="col-sm-9" style="text-align: right;">
                                                <input type="file" name="submit-anh-title" class="file-input chon-dang-anh" data-show-caption="false" data-show-upload="false">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?php if($is_ct == 1) { ?>
                                            <div class="col-lg-6 panel-heading" id="de-show" style="padding-bottom: 0;"><?php echo imageToImg($monID,$data0["content"],250); ?></div>
                                            <div class="col-lg-6">
                                                <textarea name="submit-content" style="resize:none;box-sizing: border-box;overflow: hidden;" class="form-control chon-dang-text" placeholder="Nội dung câu hỏi"><?php echo str_replace("<br />","\n",$data0["content"]); ?></textarea>
                                                <button type="button" class="btn btn-primary btn-sm bg-slate-400 sua-cauhoi" style='float:right;margin-top:10px;'>Sửa đề</button>
                                            </div>
                                            <?php } else { ?>
                                            <div class="col-lg-12">
                                                <textarea name="submit-content" style="resize:none;box-sizing: border-box;overflow: hidden;" class="form-control chon-dang-text" placeholder="Nội dung câu hỏi"><?php echo str_replace("<br />","\n",$data0["content"]); ?></textarea>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <button type="button" class="btn btn-primary btn-sm bg-brown-400 add-dap-an-chi-tiet none-chi-tiet">Đáp án chi tiết (nếu có)</button>
                                            </div>
                                            <div class="col-sm-9" style="text-align: right;">
                                                <input type="file" name="submit-da-anh-chi-tiet" class="file-input chon-anh-chi-tiet" data-show-caption="false" data-show-upload="false">
                                            </div>
                                        </div>
                                        <div class="form-group" style="margin-bottom: 0;">
                                            <?php if($data1["anh"] != "none") { ?>
                                            <div class="col-sm-3 div-has-img" style="clear: both;">
                                                <input type="checkbox" name="check-da-anh" checked="checked" class="control-danger check-use-anh check-da-anh"/>
                                                <img src="http://localhost/www/TDUONG/luyenthi/upload/<?php echo $monID."/dapan/".$data1["anh"]; ?>" class="img-thumbnail img-responsive" />
                                            </div>
                                            <div style="clear: both;height: 6px;">
                                            </div>
                                            <?php }
                                            if($is_ct == 1) { ?>
                                            <div id="dapan-show" class="col-lg-6 panel-heading" style="padding-bottom: 0;"><?php echo imageToImg($monID,$data1["content"],300); ?></div>
                                            <div class="col-lg-6">
                                                <textarea name="submit-da-chi-tiet" style="resize:none;box-sizing: border-box;overflow: hidden;" class="form-control chon-content-chi-tiet" placeholder="Nội dung đáp án chi tiết"><?php echo str_replace("<br />","\n",$data1["content"]); ?></textarea>
                                                <button type="button" class="btn btn-primary btn-sm bg-slate-400 sua-dapan" style='float:right;margin-top:10px;'>Sửa đáp án</button>
                                            </div>
                                            <?php } else { ?>
                                            <div class="col-lg-12">
                                                <textarea name="submit-da-chi-tiet" style="resize:none;box-sizing: border-box;overflow: hidden;" class="form-control chon-content-chi-tiet" placeholder="Nội dung đáp án chi tiết"><?php echo str_replace("<br />","\n",$data1["content"]); ?></textarea>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-12"></label>
                                            <?php if($data0["type_da"] == "trac-nghiem") { ?>
                                            <div class="col-lg-12 list-dap-an">
                                                <?php
                                                    $dem=1;
                                                    $result2 = $db->getDapAnNgan($cID,false);
                                                    while($data2 = $result2->fetch_assoc()) { ?>
                                                        <div class="form-group">
                                                            <div class="radio col-xs-1">
                                                                <label>
                                                                    <input type="radio" name="submit-check-da" value="<?php echo $data2["ID_DA"]; ?>" <?php if($data2["main"]==1) {echo"checked='checked'";} ?> class="control-primary"/>
                                                                </label>
                                                            </div>
                                                            <!--<div class="radio col-xs-1">
                                                                <label>
                                                                    <input type="radio" name="radio-styled-color" class="control-primary" disabled="disabled">
                                                                </label>
                                                            </div>-->
                                                            <div class="col-xs-7"><input type="text" name="submit-da-content-<?php echo $data2["ID_DA"]; ?>" class="form-control dap-an-content" value="<?php if($data2["type"]=="text") {echo $data2["content"];} ?>" /></div>
                                                            <div class="col-xs-3"><input type="file" name="submit-da-anh-<?php echo $data2["ID_DA"]; ?>" class="file-input dap-an-anh" data-show-caption="false" data-show-upload="false"></div>
                                                            <div class="col-xs-1" style="padding-left: 4%;margin-top: 8px;">
                                                                <ul class='icons-list'>
                                                                    <li class='xoa-dap-an'><a href="javascript:void(0)" data-daID='<?php echo $data2["ID_DA"]; ?>'><i class='icon-cross3'></i></a></li>
                                                                </ul>
                                                            </div>
                                                            <?php if($data2["type"] == "image") { ?>
                                                                <div style="clear: both;"></div>
                                                                <div class="col-lg-6 div-has-img dap-an-div-img" style="padding-left: 0;padding-top: 10px;">
                                                                    <input type="checkbox" name="check-use-anh-<?php echo $data2["ID_DA"]; ?>" checked="checked" class="control-danger check-use-anh"/>
                                                                    <img src="http://localhost/www/TDUONG/luyenthi/upload/<?php echo $monID."/".$data0["ID_CD"]."/dapan/".$data2["content"]; ?>" style="max-width: 150px;" class="img-thumbnail img-responsive" />
                                                                </div>
                                                                <div class="col-lg-6 panel-heading dap-an-show" style="display: none;padding-bottom: 0;"></div>
                                                            <?php } else { ?>
                                                                <div class="col-lg-12 panel-heading dap-an-show" style="clear: both;padding-bottom: 0;"><?php echo imageToImgDapan($monID,$data2["content"],250); ?></div>
                                                            <?php } ?>
                                                        </div>
                                                <?php
                                                        $dem++;
                                                    }
                                                    for($i=1;$i<=$sum_da-$dem+1;$i++) {
                                                ?>
                                                    <div class="form-group form-hide">
                                                        <div class="radio col-xs-1">
                                                            <label>
                                                                <input type="radio" name="submit-check-da" value="<?php echo $i; ?>" class="control-primary"/>
                                                            </label>
                                                        </div>
                                                        <!--<div class="radio col-xs-1">
                                                            <label>
                                                                <input type="radio" name="radio-styled-color" class="control-primary" disabled="disabled">
                                                            </label>
                                                        </div>-->
                                                        <div class="col-xs-7"><input type="text" name="submit-da-content-<?php echo $i; ?>" class="form-control dap-an-content" /></div>
                                                        <div class="col-xs-3"><input type="file" name="submit-da-anh-<?php echo $i; ?>" class="file-input dap-an-anh" data-show-caption="false" data-show-upload="false"></div>
                                                        <div class="col-xs-1" style="padding-left: 4%;margin-top: 8px;">
                                                            <ul class='icons-list'>
                                                                <li><a><i class='icon-cross3'></i></a></li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-lg-12 panel-heading dap-an-show" style="clear: both;display: none;padding-bottom: 0;"></div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <?php } else if($data0["type_da"] == "dien-tu") {
                                                $result2 = $db->getDapAnNgan($cID,false);
                                                $data2 = $result2->fetch_assoc();
                                            ?>
                                            <div class="col-lg-12 list-dien-tu">
                                                <div class="form-group">
                                                    <div class="col-lg-12"><input type="text" name="submit-dien-tu" class="form-control dien-tu-content" placeholder="Đáp án đúng mà học sinh sẽ đánh vào" value="<?php echo $data2["content"]; ?>" /></div>
                                                </div>
                                            </div>
                                            <?php } else {} ?>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-primary btn-sm bg-brown-400 add-dap-an">Thêm đáp án</button>
                                                <label class="control-label add-dap-an-error" style="display: none;">Hỗ trợ tối đa 6 đáp án!</label>
                                            </div>
                                        </div>
                                    </fieldset>
<!--                                </form>-->
                            </div>
                        </div>
<!--                        <div class="panel panel-flat" style="display: none;">-->
<!--                            <div class="panel-heading">-->
<!--                                <h5 class="panel-title">Nhóm câu hỏi</h5>-->
<!--                            </div>-->
<!--                            <div class="panel-body">-->
<!--                                <fieldset class="content-group">-->
<!--                                    <div class="form-group">-->
<!--                                        <label class="control-label col-sm-3">Chọn các nhóm</label>-->
<!--                                        <div class="col-sm-9">-->
<!--                                            <select name="submit-nhom" class="form-control">-->
<!--                                                <option value="0" --><?php //if($data0["nhom"]==0){echo"selected='selected'";} ?><!--><!--</option>
<!--                                                --><?php
//                                                $db = new Nhom_Cau_Hoi();
//                                                $result = $db->getNhomByMon($lmID);
//                                                while($data=$result->fetch_assoc()) {
//                                                    echo"<option value='$data[ID_N]'"; if($data0["nhom"]==$data["ID_N"]){echo"selected='selected'";} echo">$data[name]</option>";
//                                                }
//                                                ?>
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="form-group">-->
<!--                                        <label class="control-label col-sm-3">Thêm nhóm mới</label>-->
<!--                                        <div class="col-sm-9">-->
<!--                                            <input type="text" name="submit-nhom-name" class="form-control" placeholder="Điền tên nhóm mới!" />-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </fieldset>-->
<!--                            </div>-->
<!--                        </div>-->
                    </div>

                    <div class="col-lg-4">
                        <div class="panel-heading">
                            <h5 class="panel-title">Định dạng</h5>
                        </div>
                        <!-- Sales stats -->
                        <div class="panel panel-flat">
                            <div class="panel-body">
<!--                                <form class="form-horizontal" action="#">-->
                                    <fieldset class="content-group">
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <input type="text" name="submit-maso" class="form-control" placeholder="Mã câu hỏi" value="<?php echo $data0["maso"]; ?>" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <select name="submit-cd" class="form-control">
                                                    <option value="0">Chọn chuyên đề</option>
                                                    <?php
                                                        $result = (new Chuyen_De())->getChuyenDeByMon($monID);
                                                        while($data=$result->fetch_assoc()) {
                                                            echo"<option value='$data[ID_CD]'"; if($data0["ID_CD"]==$data["ID_CD"]){echo"selected='selected'";}echo">$data[name]</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-12">
                                                <select name="submit-loai" class="form-control">
                                                    <option value="0">Chọn phân loại</option>
                                                    <?php
                                                        $result = (new Phan_Loai())->getPhanLoai();
                                                        while($data=$result->fetch_assoc()) {
                                                            echo"<option value='$data[ID_PL]'"; if($data0["ID_PL"]==$data["ID_PL"]){echo"selected='selected'";}echo">$data[name]</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </fieldset>
<!--                                </form>-->
                            </div>
                        </div>

                        <div class="panel-heading">
                            <h5 class="panel-title">Tùy chọn</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <select name="submit-type-da" class="form-control chon-dang-dap-an">
                                                <option value="trac-nghiem" <?php if($data0["type_da"]=="trac-nghiem"){echo"selected='selected'";}else{echo"disabled='disabled'";} ?>>Chọn 4 phương án A, B, C và D</option>
<!--                                                <option value="dien-tu" --><?php //if($data0["type_da"]=="dien-tu"){echo"selected='selected'";}else{echo"disabled='disabled'";} ?><!-->Điền vào chỗ trống</option>-->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <select name="submit-do-kho" class="form-control">
                                                <?php
                                                $result = (new Do_Kho())->getDoKho();
                                                while($data=$result->fetch_assoc()) {
                                                    echo"<option value='$data[ID_K]'"; if($data0["ID_K"]==$data["ID_K"]){echo"selected='selected'";}echo">Độ khó: $data[name]</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <textarea rows="4" cols="5" name="submit-note" style="resize: vertical;" class="form-control" placeholder="Ghi chú"><?php echo $data0["note"]; ?></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <fieldset class="content-group" style="margin-bottom: 0 !important;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-xs-12" style="text-align: right;">
                                            <button type="submit" name="submit-del-ok" class="btn btn-primary btn-sm bg-danger-400 del-cau-hoi">Xóa</button>
                                            <button type="submit" name="submit-add-ok" class="btn btn-primary btn-sm bg-blue-400 add-cau-hoi">Sửa toàn bộ</button>
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
                $("span.hidden-xs").remove();

                function format_content(me) {
                    me = me.replace(/\n/g,"<br />");
                    me = me.replace(/'/g,"1o1");
                    me = me.replace(/<\//g,"2o2");
                    me = me.replace(/</g,"3o3");
                    me = me.replace(/>/g,"4o4");
                    me = me.replace(/\+/g,"5o5");
                    me = me.replace(/&/g,"6o6");
                    return me;
                }

                $("textarea.chon-content-chi-tiet, textarea.chon-dang-text").typeWatch({
                    captureLength: 1,
                    callback: function (value) {
                        $(this).outerHeight($(this).height()).outerHeight(this.scrollHeight);
                    }
                });

                $("button.del-cau-hoi").click(function() {
                    if(confirm("Bạn có chắc chắn không?")) {
                        return true;
                    } else {
                        return false;
                    }
                });

                $("button.sua-cauhoi").click(function() {
                    var me = $(this);
                    var cID = <?php echo $data0["ID_C"]; ?>;
                    var content = format_content(me.closest("div").find("textarea").val().trim());
                    if(valid_id(cID) && content != "") {
                        me.html("Đang cập nhật");
                        $.ajax({
                            async: true,
                            data: "cID_edit=" + cID + "&content=" + content + "&monID=<?php echo $monID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                $("#de-show").html(result);
//                                me.closest("td").find("textarea.de-content").val(result);
                                me.html("Xong");
                                MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                            }
                        });
                    }
                });

                $("button.sua-dapan").click(function() {
                    var me = $(this);
                    var cID = <?php echo $data0["ID_C"]; ?>;
                    var content = format_content(me.closest("div").find("textarea").val().trim());
                    if(valid_id(cID) && content != "") {
                        me.html("Đang cập nhật");
                        $.ajax({
                            async: true,
                            data: "cID_edit=" + cID + "&dapan=" + content + "&monID=<?php echo $monID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                $("#dapan-show").html(result);
//                                me.closest("td").find("textarea.dapan-content").val(result);
                                me.html("Xong");
                                MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                            }
                        });
                    }
                });

                $("textarea.chon-content-chi-tiet, textarea.chon-dang-text").each(function(index,element) {
                    $(element).outerHeight(this.scrollHeight);
                });

                /*$(".chon-dang-anh").closest("div.file-input").show();
                $(".chon-dang-text").hide();
                $(".content-wrapper").delegate("div.form-cau-hoi .chon-dang","change",function() {
                    me = $(this).closest("div.form-group");
                    if($(this).val() == "image") {
                        me.find(".chon-dang-anh").closest("div.file-input").show();
                        me.find("> div.panel-heading").hide();
                        me.find("> div.dap-an-show").html("");
                        me.find(".chon-dang-text").hide();
                    } else {
                        me.find(".chon-dang-anh").closest("div.file-input").hide();
                        me.find(".chon-dang-text").show();
                    }
                });*/
                $("input.check-use-anh").closest("div.checker").css({"position":"absolute","z-index":"9","left":"4px","top":"14px","background":"#FFF"});
                $("input.check-de-anh").closest("div.checker").css({"top":"30px","left":"14px"});
                $("input.check-da-anh").closest("div.checker").css({"top":"4px","left":"14px"});
                $("input.check-use-anh").change(function () {
                    if($(this).is(":checked")) {
                        $(this).closest("div.div-has-img").find("> img").css("opacity", "1");
                        $(this).closest("div.form-group").find("> div input.dap-an-content").val("");
                    } else {
                        $(this).closest("div.div-has-img").find("> img").css("opacity", "0.3");
                    }
                });
                <?php if($data1["content"] == "none" && $data1["anh"] == "none") { ?>
                $(".chon-content-chi-tiet").hide();
                $(".chon-anh-chi-tiet").closest("div.file-input").hide();
                <?php } else { ?>
                $(this).removeClass("none-chi-tiet");
                <?php } ?>
                $(".add-dap-an-chi-tiet").click(function () {
                    if($(this).hasClass("none-chi-tiet")) {
                        $(this).closest("div.form-cau-hoi").find(".chon-content-chi-tiet").show();
                        $(this).closest("div.form-cau-hoi").find(".chon-anh-chi-tiet").closest("div.file-input").show();
                        $(this).removeClass("none-chi-tiet");
                    } else {
                        $(this).closest("div.form-cau-hoi").find(".chon-content-chi-tiet").hide();
                        $(this).closest("div.form-cau-hoi").find(".chon-anh-chi-tiet").closest("div.file-input").hide();
                        $(this).addClass("none-chi-tiet");
                    }
                });

                $(".form-hide").hide();
                $(".content-wrapper").delegate("div.form-cau-hoi .add-dap-an","click",function () {
                    me = $(this).closest("div.form-group").prev();
                    me.find(".form-hide").each(function(index, element) {
                        $(element).removeClass("form-hide").show();
                        return false;
                    });
                    if(me.find(".form-hide").length == 0) {
                        $(this).closest("div.form-group").find(".add-dap-an-error").show();
                    }
                });
                $(".content-wrapper").delegate("div.form-cau-hoi ul.icons-list > li > a > i.icon-cross3","click",function () {
                    $(this).closest("div.form-group").find("> div.checkbox span").removeClass("checked");
                    $(this).closest("div.form-group").find("> div.checkbox input").removeAttr("checked");
                    $(this).closest("div.form-group").find("> div input.dap-an-content").val("");
                    $(this).closest("div.form-group").addClass("form-hide").hide();
                    $(this).closest("div.list-dap-an").closest("div.form-group").next().find(".add-dap-an-error").hide();
                });
                $(".content-wrapper").delegate("div.form-cau-hoi li.form-close","click",function () {
                    if($("div.form-cau-hoi").length > 1) {
                        $(this).closest("div.form-cau-hoi").remove();
                    }
                });
                $(".content-wrapper").delegate("div.form-cau-hoi input.dap-an-anh","click",function() {
                    $(this).closest("div.form-group").find("> div input.dap-an-content").val("");
                    $(this).closest("div.form-group").find("> div.dap-an-show").html("");
                });
                $(".content-wrapper").delegate("div.form-cau-hoi .chon-dang-dap-an","change",function () {
                    me = $(this).closest("div.form-cau-hoi");
                    if($(this).val() == "dien-tu") {
                        me.find(".list-dap-an").hide();
                        me.find(".add-dap-an").hide();
                        me.find(".list-dien-tu").show();
                    } else {
                        me.find(".list-dien-tu").hide();
                        me.find(".list-dien-tu").find("input.dien-tu-content").val("");
                        me.find(".list-dap-an").show();
                        me.find(".add-dap-an").show();
                    }
                });
//                $(".content-wrapper").delegate("div.form-cau-hoi textarea.chon-dang-text, div.form-cau-hoi textarea.chon-content-chi-tiet, div.form-cau-hoi .list-dap-an div input.dap-an-content","click",function () {
//                    $(this).typeWatch({
//                        captureLength: 3,
//                        callback: function(me) {
//                            var dm = $(this);
//                            <?php //if($is_ct == 1) { ?>
//                            display_content(me, dm);
//                            <?php //} ?>
//                            show_anh(me, dm);
//                        }
//                    });
//                });
                $(".content-wrapper").delegate("div.form-cau-hoi button.copy-cau-hoi","click",function () {
                    var me = $(this).closest("div.form-cau-hoi").clone(true);
                    me.appendTo(".content-wrapper");
                });
                $(".content-wrapper").delegate("div.form-cau-hoi select","change",function () {
                    var val = $(this).val(); //get new value
                    $(this).find("option").each(function (index, element) {
                        if($(element).val() != val) {
                            $(element).removeAttr("selected");
                        } else {
                            $(element).attr("selected", "selected");
                        }
                    });
                });
                $(".xoa-dap-an").click(function() {
                    var del_tr = $(this).closest("div.form-group");
                    var id = $(this).find("> a").attr("data-daID");
                    if(confirm("Bạn có chắc chắn xóa đáp án này?")) {
                        if(valid_id(id)) {
                            $.ajax({
                                async: false,
                                data: "daID1=" + id,
                                type: "post",
                                url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                                success: function (result) {
                                    result = result.trim();
                                    if (result == "ok") {
                                        del_tr.remove();
                                        new PNotify({
                                            title: 'Đáp án',
                                            text: 'Đã xóa thành công!',
                                            icon: 'icon-menu6'
                                        });
                                    } else {
                                        new PNotify({
                                            title: 'Đáp án',
                                            text: 'Không thể xóa đáp án này!',
                                            icon: 'icon-menu6'
                                        });
                                        setTimeout(function() {
                                            location.reload();
                                        },1000);
                                    }
                                }
                            });
                        } else {
                            alert("Dữ liệu không chính xác!");
                        }
                    }
                });
                function display_content(me, dm) {
                    if (me != "") {
                        /*me = me.replace(/$\\/g,"\\(\\");
                         me = me.replace(/$/g,"\\)");
                         me = me.replace(/\[/g,"\(");
                         me = me.replace(/\]/g,"\)");*/
                        me = me.replace(/\n/g,"<br />");
                        me = me.replace(/'/g,"1o1");
                        me = me.replace(/<\//g,"2o2");
                        me = me.replace(/</g,"3o3");
                        me = me.replace(/>/g,"4o4");
                        me = me.replace(/\+/g,"5o5");
                        me = me.replace(/&/g,"6o6");
                        dm.closest("div.form-group").find("> div.dap-an-show").html("<iframe class='embed-responsive-item' style='height: 90px;width: 100%;border:0;' src='http://localhost/www/TDUONG/luyenthi/admin/ajax/formula.php?formula=" + me + "'></iframe>");
                        dm.closest("div.form-group").find("> div.panel-heading").show();
                    } else {
                        dm.closest("div.form-group").find("> div.panel-heading").hide();
                        dm.closest("div.form-group").find("> div.dap-an-show").html("");
                    }
                }
                function show_anh(me, dm) {
                    if (me != "") {
                        dm.closest("div.form-group").find("> div.dap-an-div-img span").removeClass("checked");
                        dm.closest("div.form-group").find("> div.dap-an-div-img input.check-use-anh").removeAttr("checked");
                        dm.closest("div.form-group").find("> div.dap-an-div-img > img").css("opacity", "0.3");
                    } else {
                        dm.closest("div.form-group").find("> div.dap-an-div-img span").addClass("checked");
                        dm.closest("div.form-group").find("> div.dap-an-div-img input.check-use-anh").attr("checked","checked");
                        dm.closest("div.form-group").find("> div.dap-an-div-img > img").css("opacity", "1");
                    }
                }
            });
        </script>
        <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML-full"></script>
        <script>
            MathJax.Hub.Config({
                showProcessingMessages: false,
                messageStyle: "none",
                tex2jax: {
                    inlineMath: [["$", "$"], ["\\(", "\\)"], ["\\[", "\\]"]],
                    processEscapes: false
                },
                showMathMenu: false,
                displayAlign: "left",
                jax: ["input/TeX","output/NativeMML"],
                "fast-preview": {disabled: true},
                NativeMML: { linebreaks: { automatic: true }, minScaleAdjust: 110, scale: 110},
                TeX: { noErrors: { disabled: true } },
            });
        </script>
        <?php
            if(!$error && $error_msg != "") {
                sleep(2);
                header("location:http://localhost/www/TDUONG/luyenthi/admin/sua-cau-hoi/$cID/");
                exit();
            }
        ?>


    <?php
        ini_set('max_execution_time', 900);
        include_once "../include/top.php";
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        if(isset($_GET["filename"]) && isset($_GET["check"]) && is_numeric($_GET["check"]) && ($_GET["check"]==0 || $_GET["check"]==1) && isset($_GET["notecd"]) && is_numeric($_GET["notecd"]) && ($_GET["notecd"]==0 || $_GET["notecd"]==1)) {
            $filedone = $_GET["filename"];
            $is_check = $_GET["check"];
            $is_notecd = $_GET["notecd"];
        } else {
            $filedone = NULL;
            $is_check = 0;
            $is_notecd = 0;
        }
        $monID = (new Mon_Hoc())->getMonOfLop($lmID);

        require_once '../bootstrap.php';
        use PhpOffice\PhpWord\Settings;
        date_default_timezone_set('UTC');
        error_reporting(E_ALL);
        define('CLI', (PHP_SAPI == 'cli') ? true : false);
        define('EOL', CLI ? PHP_EOL : '<br />');
        define('SCRIPT_FILENAME', basename("de-thi", '.php'));
        define('IS_INDEX', SCRIPT_FILENAME == 'index');
        Settings::loadConfig();

        // Set writers
        $writers = array('HTML' => 'html');
        // Turn output escaping on
        Settings::setOutputEscapingEnabled(true);

        // Return to the caller script when runs by CLI
        if (CLI) {
            return;
        }

        function write($phpWord, $filename, $writers, $monID)
        {
            // Write documents
            $name="";
            foreach ($writers as $format => $extension) {
                if (null !== $extension) {
                    $targetFile = "../upload/$monID/$filename.{$extension}";
                    $phpWord->save($targetFile, $format);
                    $name = "../upload/$monID/DeThi_".date("Y-m-d_H-i").".html";
                    rename("../upload/$monID/import_cau_hoi_ex.html", $name);
                }
                break;
            }
            return $name;
        }

        $da_arr = array("A","B","C","D","E","F","G","H","I","K");
    ?>

    <!-- Page header -->
<!--    <div class="page-header">-->
<!--        <div class="page-header-content">-->
<!--            <div class="page-title">-->
<!--                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Câu hỏi</span> - Import câu hỏi từ <strong>Word</strong></h4>-->
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
                $file = NULL;
                $error = false;
                $error_msg = "";
                $check = 0;
                $html = "";
                $file_arr = "";
                if(isset($_POST["import-ok"])) {
                    $total = count($_FILES["submit-file-word"]["name"]);
                    if($total > 100) {
                        $total = 100;
                    }
                    if($total <= 0) {
                        $error = true;
                        $error_msg = "Vui lòng chọn một file!";
                    } else {

                        if(isset($_POST["submit-check"])) {
                            $check = 1;
                        } else {
                            $check = 0;
                        }
                        if(isset($_POST["submit-note-cd"])) {
                            $notecd = 1;
                        } else {
                            $notecd = 0;
                        }

                        for($f = 0; $f < $total; $f++) {
                            $file = addslashes($_FILES["submit-file-word"]["name"][$f]);
                            $target_file = basename($file);
                            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                            if ($imageFileType != "docx") {
                                $error = true;
                                $error_msg = "Chỉ chấp nhận file DOCX";
                            } else {
                                move_uploaded_file($_FILES["submit-file-word"]["tmp_name"][$f], "../upload/$monID/" . $_FILES["submit-file-word"]["name"][$f]);
                                /*Create a new ZIP archive object*/
                                $pre = explode(".",$_FILES["submit-file-word"]["name"][$f]);
                                $pre = str_replace("(","",$pre[0]);
                                $pre = str_replace(")","",$pre);
                                $pre = explode("-",$pre);
                                $pre = $pre[0]."-".$pre[1]."-".$pre[2];

                                $file_arr .= "--".$pre;

                                $zip = new ZipArchive;

                                /*Open the received archive file*/
                                $source_file = "../upload/$monID/" . $_FILES["submit-file-word"]["name"][$f];
                                if (true === $zip->open("../upload/$monID/" . $_FILES["submit-file-word"]["name"][$f])) {
                                    $zip->extractTo("../upload/$monID/");
                                    /*Get the content of the specified index of ZIP archive*/
                                    for($i = 0; $i < $zip->numFiles; $i++) {
                                        $filename = $zip->getNameIndex($i);
                                        $temp = explode("/",$filename);
                                        $filename = end($temp);
                                        $temp = explode(".",$filename);
                                        $file_type=strtolower(end($temp));
                                        if($file_type == "png" || $file_type == "jpg" || $file_type == "jpeg") {
                                            rename("../upload/$monID/word/media/$filename", "../upload/$monID/$pre-$filename");
                                        }
                                    }

                                    $zip->close();

                                    $phpWord = \PhpOffice\PhpWord\IOFactory::load($source_file);
                                    $filename = write($phpWord, basename(__FILE__, '.php'), $writers, $monID);
                                    copy($filename, "../upload/$monID/$pre.html");
                                } else {
                                    $error = true;
                                    $error_msg .= "Không thể đọc file DOCX<br />";
                                }
                            }
                        }

                        rrmdir("../upload/$monID/_rels");
                        rrmdir("../upload/$monID/word");
                        rrmdir("../upload/$monID/docProps");
                        unlink("../upload/$monID/[Content_Types].xml");

                        header("location:http://localhost/www/TDUONG/luyenthi/admin/import-cau-hoi-ex/$file_arr/$check/$notecd/");
                        exit();
                    }
                }
            ?>

            <!-- Main content -->
            <div class="content-wrapper">
                <form action="http://localhost/www/TDUONG/luyenthi/admin/import_cau_hoi_ex.php" class="form-horizontal" method="post" enctype="multipart/form-data">
                <!-- Main form -->
                <div class="form-cau-hoi">
                    <div class="col-lg-12">
                        <div class="panel-heading">
                            <h5 class="panel-title">Import từ file Word</h5>
                        </div>
                        <div class="panel panel-flat">
                            <div class="panel-body">
                                <?php if($error_msg != "") { ?>
                                    <div class="alert alert-danger no-border">
                                        <span class="text-semibold">Kết quả: </span>
                                        <?php echo $error_msg; ?>
                                    </div>
                                <?php } ?>
                                <p class="content-group">File *.docx được mô tả như sau:
                                    <br />+ * là tên file Word, định dạng <strong>D01-04-(1)-100%.docx</strong>
                                    <br />+ Chỉ update tối đa <strong>100 file</strong> cùng một thời điểm
                                    <br />+ Các câu hỏi sẽ được tạo mã theo tên file Word D01-04-1a, D01-04-1b, D01-04-1c,...
                                    <br />+ Nội dung ở định dạng <strong>LaTex</strong>
                                    <br />+ Trang test convert: <a href="https://www.mathjax.org/" target="_blank">Mathjax</a>
                                    <br />+ File word mẫu: <a href="http://localhost/www/TDUONG/luyenthi/admin/download_file.php?filename=D01-04-(1)-100%.docx" target="_blank">Click để download</a>
                                </p>
                                <fieldset class="content-group">
                                    <div class="form-group">
                                        <div class="col-lg-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" checked="checked" name="submit-check">
                                                    Các câu hỏi đều đã được check
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="control-primary" name="submit-note-cd">
                                                    Ghi chú là mã chuyên đề?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-lg-12">
                                            <label class="control-label">Chọn file *.docx</label>
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="file" name="submit-file-word[]" multiple="multiple" class="file-input" data-show-caption="false" data-show-upload="false">
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <div class="col-lg-12" style="text-align: right;">
                                            <button type="submit" name="import-ok" class="btn btn-primary btn-sm bg-blue-400 import-cau-hoi">Import</button>
                                        </div>
                                    </div>
                                </fieldset>
                                <?php
                                    $javascript = false;
                                    if($filedone) {
                                        $temp = explode("--",$filedone);
                                        for($i = 1; $i < count($temp); $i++) {
                                            echo"<div class='filedone' style='display: none;' data-pre='$temp[$i]'>";
                                            if (is_file("../upload/$monID/$temp[$i].html")) {
                                                $javascript = true;
                                                $me = file_get_contents("../upload/$monID/$temp[$i].html");
                                                $me = str_replace(array("<p></p>","<p>&nbsp;</p>"), array("<p>8o8</p>"), $me);
//                                                $me = str_replace("</span></p>
//<p><span>", "", $me);
//                                                $me = str_replace(array("<p></p>","<p>&nbsp;</p>"), array("<p>8o8</p>",""), $me);
//                                                $me = str_replace("\\]</p>
//<p><span","\\]</p><p>8o8</p><p><span",$me);
//                                                $me = str_replace("</p>
//<p>", "<p>8o8", $me);
//                                                $me = str_replace("</p>
//
//<p>", "<p>8o8</p>", $me);
//                                                $me = str_replace("8o88o8", "8o8", $me);
                                                echo $me;
                                            } else {
                                                $html = "Không tồn tại file tạm cần lấy dữ liệu!";
                                            }
                                            echo"</div>";
                                        }
                                    }
                                ?>
                                <div class="alert alert-danger no-border" style="display: none;" id="error-form">
                                    <span class="text-semibold"></span>
                                </div>
                                <?php if(isset($html) && $html != "") { ?>
                                    <div class="alert alert-danger no-border">
                                        <span class="text-semibold">Đầu ra: </span>
                                        <?php echo $html; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($_SESSION["ketqua"]) && count($_SESSION["ketqua"]) > 0) { ?>
                        <div class="panel-heading">
                            <h5 class="panel-title">Kết quả Import</h5>
                        </div>
                        <div class="panel panel-flat">
                            <table class="table" id="list-bai-thi">
                                <tbody>
                                    <col style="width:20%;" />
                                    <col style="width:80%;" />
                                <?php
                                    $db = new Cau_Hoi();
                                    $dem = 1;
                                    $cau_arr = $_SESSION["ketqua"];
                                    $n = count($cau_arr);
                                    echo"<input type='hidden' id='notecd' value='".$cau_arr[$n-1]["notecd"]."' />";
                                    echo"<tr><td colspan='2'><p class='content-group' id='check-error'></p></td></tr>";
                                    echo"<tr><td colspan='2'>Số lượng câu: ".$cau_arr[$n-1]["num"]."</td></tr>";
                                    for($i = 0; $i < $n-1; $i++) {
                                        $result = $db->getContentCau($cau_arr[$i]["ID_C"]);
                                        $data = $result->fetch_assoc();
                                        echo"<tr class='de-bai-cau-big' data-dem='$dem'>
                                            <td colspan='2' style='line-height: 30px;'>
                                                <span class='label bg-brown-600' style='font-size:14px;margin-right: 5px;'>Câu ".$cau_arr[$i]["maso"].":</span> 
                                                    <strong>".$cau_arr[$i]["chuyende"]."</strong>";
                                                if($data["con"] != "none") {
                                                    echo"<br />";
                                                    echo"<span class='de-show' style='width: 47.5%;float:left;font-size:13px;margin-right: 5%;overflow: hidden;'>".imageToImg($cau_arr[$i]["monID"],$data["con"],250)."</span>";
                                                    echo"<textarea style='resize:none;box-sizing: border-box;overflow: hidden;width: 47.5%;float:left;' class='form-control de-content'>".str_replace("<br />","\n",$data["con"])."</textarea>";
                                                    echo"<button type='button' class='btn btn-primary btn-sm bg-blue-400 sua-de' data-cID='".$cau_arr[$i]["ID_C"]."' style='float:right;margin-top:10px;'>Sửa</button>";
                                                }
                                                if($cau_arr[$i]["anh"] != "none"){
                                                    echo"<span style='text-align: center;display: block;margin: 7px 0 7px 0;clear:both;'><img src='http://localhost/www/TDUONG/luyenthi/".$db->getUrlDe($cau_arr[$i]["monID"],$cau_arr[$i]["anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                                }
                                        echo"</td></tr>
                                        <tr>
                                            <td colspan='2' style='line-height: 30px;'>
                                                <strong>Đáp án chi tiết</strong>";
                                                if($data["da_con"] != "none") {
                                                    echo"<br />";
                                                    echo"<span class='dapan-show' style='width: 47.5%;float:left;font-size:13px;margin-right: 5%;overflow: hidden;'>".imageToImg($cau_arr[$i]["monID"],$data["da_con"],300)."</span>";
                                                    echo"<textarea style='resize:none;box-sizing: border-box;overflow: hidden;width: 47.5%;float:left;' class='form-control dapan-content'>".str_replace("<br />","\n",$data["da_con"])."</textarea>";
                                                    echo"<button type='button' class='btn btn-primary btn-sm bg-blue-400 sua-dapan' data-cID='".$cau_arr[$i]["ID_C"]."' style='float:right;margin-top:10px;'>Sửa</button>";
                                                }
                                                if($cau_arr[$i]["da_anh"] != "none"){
                                                    echo "<span style='text-align: center;display: block;margin: 7px 0 7px 0;clear:both;'><img src='http://localhost/www/TDUONG/luyenthi/".$db->getUrlDe($cau_arr[$i]["monID"],$cau_arr[$i]["da_anh"])."' style='max-height:250px;max-width:700px;' class='img-thumbnail img-responsive' /></span>";
                                                }
                                        echo"</td>
                                        </tr>";
                                        for($j = 0; $j < count($cau_arr[$i]["da_arr"]); $j++) {
                                            echo"<tr class='dap-an-cau-$dem'>
                                                <td>
                                                    <div class='radio' style='left: 25%;'>
                                                        <label>
                                                            <input type='radio' disabled='disabled' ";if($cau_arr[$i]["da_arr"][$j]["dapanMain"]==1){echo"checked='checked'";}echo" class='control-primary radio-dap-an' />
                                                            ".$da_arr[$j].".
                                                        </label>
                                                    </div></td>
                                                    <td>";
                                                if($cau_arr[$i]["da_arr"][$j]["dapanType"] == "text") {
                                                    echo"<span style='font-size: 13px;'>".imageToImgDapan($cau_arr[$i]["monID"],$cau_arr[$i]["da_arr"][$j]["dapan"],250)."</span>";
                                                } else {
                                                    echo"<img src='http://localhost/www/TDUONG/luyenthi/".$db->getUrlDapAn($cau_arr[$i]["monID"],$cau_arr[$i]["da_arr"][$j]["dapan"])."' style='max-height:250px;' class='img-thumbnail img-responsive' />";
                                                }
                                                echo"</td>
                                            </tr>";
                                        }
                                        echo"<tr class='tr-note'><td colspan='2'>Ghi chú: ".$cau_arr[$i]["note"]."</td></tr>";
                                        $dem++;
                                    }
                                    $dem--;
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <?php }
                            $_SESSION["ketqua"] = array();
                        ?>
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
                var error_msg = "";
                $("table#list-bai-thi tr.de-bai-cau-big").each(function(index, element) {
                    var dem = $(element).attr("data-dem");
                    if($("table#list-bai-thi tr.dap-an-cau-" + dem).length != 4) {
                        error_msg += "+ Câu " + (index+1) + " bị lỗi số lượng đáp án!<br />";
                        $(element).css("background", "red");
                    }
                    var check_da = false;
                    $("table#list-bai-thi tr.dap-an-cau-" + dem).each(function(index, element) {
                        if($(element).find("td input").attr("checked") == "checked") {
                            check_da = true;
                        }
                    });
                    if(!check_da) {
                        error_msg += "+ Câu " + (index+1) + " bị lỗi đáp án đúng!<br />";
                        $(element).css("background", "red");
                    }
                });
                var count_anh = 0;
                $("table#list-bai-thi tr td img").each(function(index, element) {
                    var img_width = $(element).width();
                    var img_height = $(element).height();
                    if((img_width < 60 || img_height < 60) && img_width > 10 && img_height > 10) {
                        $(element).closest("tr").css("background", "red");
                        count_anh++;
                    }
                });
                if(count_anh != 0) {
                    error_msg += "+ Có " + count_anh + " ảnh lỗi!<br />";
                }
                var check_note = false;
                $("table#list-bai-thi tr.tr-note").each(function (index, element) {
                    if($(element).find("td").text().length == 13) {
                        check_note = true;
                    } else if(check_note) {
                        $(element).css("background", "red");
                        error_msg += "+ Câu " + (index+1) + " bị lỗi note đáp án!<br />";
                    }
                });
                if(check_note && $("#notecd").val() == 0) {
                    error_msg += "+ Bạn chưa tích Ghi chú là Mã chuyên đề!<br />";
                }
                console.log(error_msg);
                $("#check-error").html(error_msg);

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

                $("textarea.de-content, textarea.dapan-content").typeWatch({
                    captureLength: 1,
                    callback: function (value) {
                        $(this).outerHeight($(this).height()).outerHeight(this.scrollHeight);
                    }
                });

                $("button.sua-de").click(function() {
                    var me = $(this);
                    var cID = me.attr("data-cID");
                    var content = format_content(me.closest("td").find("textarea.de-content").val().trim());
                    if(valid_id(cID) && content != "") {
                        me.html("Đang cập nhật");
                        $.ajax({
                            async: true,
                            data: "cID_edit=" + cID + "&content=" + content + "&monID=<?php echo $monID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                me.closest("td").find("span.de-show").html(result);
//                                me.closest("td").find("textarea.de-content").val(result);
                                me.html("Xong");
                                MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                            }
                        });
                    }
                });

                $("button.sua-dapan").click(function() {
                    var me = $(this);
                    var cID = me.attr("data-cID");
                    var content = format_content(me.closest("td").find("textarea.dapan-content").val().trim());
                    if(valid_id(cID) && content != "") {
                        me.html("Đang cập nhật");
                        $.ajax({
                            async: true,
                            data: "cID_edit=" + cID + "&dapan=" + content + "&monID=<?php echo $monID; ?>",
                            type: "post",
                            url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                            success: function (result) {
                                me.closest("td").find("span.dapan-show").html(result);
//                                me.closest("td").find("textarea.dapan-content").val(result);
                                me.html("Xong");
                                MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                            }
                        });
                    }
                });

                $("textarea.de-content, textarea.dapan-content").each(function(index,element) {
                    $(element).outerHeight(this.scrollHeight);
                });

                function dapan_length(me) {
//                    temp = me;
                    me = me.replace(/left/g,"").replace(/right/g,"").replace(/7o7begin/g,"").replace(/7o7end/g,"").replace(/gathered/g,"").replace(/7o7hfill/g,"").replace(/7o7sqrt/g,"").replace(/7o7notin/g,"");
                    me = me.replace(/7o7frac/g,"").replace(/7o7over/g,"").replace(/7o7leqslant/g,"").replace(/arrow/g,"").replace(/7o7Right/g,"").replace(/7o7Left/g,"").replace(/7o7approx/g,"").replace(/7o7mathop/g,"").replace(/7o7limits_/g,"");
                    me = me.replace(/7o7backslash/g,"").replace(/7o7geqslant/g,"").replace(/7o7Delta/g,"").replace(/7o7in/g,"").replace(/7o7cup/g,"").replace(/7o7max/g,"").replace(/7o7min/g,"").replace(/7o7mathbb/g,"").replace(/7o7forall/g,"");
                    me = me.replace(/7o7bot/g,"").replace(/7o7int/g,"").replace(/7o7to/g,"").replace(/7o7infty/g,"").replace(/{/g,"").replace(/}/g,"").replace(/ /g,"");
                    var len = me.length;
//                    console.log("ccc" + me + "-dai:" + len);
                    return len;
//                    if(len <= 30) {
//                        return 1;
//                    } else if(len <= 54) {
//                        return 2;
//                    } else {
//                        return 3;
//                    }
                }

                function check_first_letter(me) {
                    me = me.substr(0,1);
                    if(me == "B" || me == "C" || me == "D" || me == "Đ" || me == "E" || me == "G" || me == "H" || me == "K" || me == "L" || me == "M" || me == "X" || me == "S" || me == "R" || me == "Q" || me == "T" || me == "P" || me == "V" || me == "N") {
                        return true;
                    } else {
                        return false;
                    }
                }

                function format_ct(me) {
                    me = me.replace(/"/g,"0o0");
                    me = me.replace(/'/g,"1o1");
                    me = me.replace(/<\//g,"2o2");
                    me = me.replace(/</g,"3o3");
                    me = me.replace(/>/g,"4o4");
                    me = me.replace(/\+/g,"5o5");
                    me = me.replace(/ & /g,"");
                    me = me.replace(/&/g,"6o6");
                    me = me.replace(/\\/g,"7o7");
                    me = me.replace(/8o87o7/g,"7o7");
                    me = me.replace(/8o88o8/g,"8o8");
                    me = me.replace(/5o58o8/g,"5o5");
//                    me = me.trim();
                    return me;
                }

                <?php if($javascript) { ?>
                $("#error-form").fadeIn("fast").find("span").html("Đang tiến hành IMPORT!!!");
                $("#error-form").closest("div.panel-body").find("fieldset").remove();

                var ajax_data = "[";
                var cau = 0;
                $("div.filedone").each(function (index3,element3) {
                    var pre_ma = $(element3).attr("data-pre");

                    $(element3).find("table tr").each(function(index, element) {
                        var type = $(element).find("td:first-child p").text().trim().replace(/8o8/g,"");
                        var loai = parseInt(type.substring(0,1).trim());
                        if(loai == 1) {
                            cau++;
                            $(element).addClass("cau").attr("data-cau", cau);
                        } else if(type != "") {
                            $(element).addClass("info-cau-" + cau);
                        }
                        var pre_con = "";
                        var total_len = 0;
                        var check = false;
                        var is_ct = false;
                        var text_len = 0;
                        $(element).find("td:eq(1) p").each(function (index2, element2) {
                            var me = "";
                            var now_len = 0;
                            if($(element2).find("span").length) {
                                // Nếu chứa span => text
                                me = format_ct($(element2).find("span").text());
                                if(me.trim() == "") {
                                    $(element2).remove();
                                } else {
                                    now_len = dapan_length(me);
                                    check = check_first_letter(me);
                                    if ((check && now_len > 1 && is_ct) || (check && text_len > 70)) {
                                        pre_con += "8o8";
                                        text_len = 0;
                                        total_len = 0;
                                    } else if(check) {
                                        pre_con += " ";
                                    }

                                    total_len += now_len;
                                    text_len += now_len;
                                    is_ct = false;
                                    pre_con += me;
                                }
                            } else {
                                // Nếu không chứa span => có thể là text hoặc CT
                                // Kiểm tra text hay CT
                                me = format_ct($(element2).text());
                                if(me.trim() == "") {
                                    $(element2).remove();
                                } else {
                                    if ((me.match(/7o7/g) || []).length >= 2 || (me.match(/\$/g) || []).length >= 2) {
                                        // Là CT
                                        now_len = dapan_length(me);
                                        total_len += now_len;
                                        if (me.substr(0, 12) == "7o7[7o7left." || me.indexOf("7o7[7o7begin{gathered}") >= 0) {
                                            pre_con += "8o8";
                                            total_len = 0;
                                        } else if(is_ct && me.indexOf("7o7]") > 0) {
                                            pre_con += "8o8";
                                            total_len = 0;
                                        } else if (total_len > 54 && now_len > 20) {
                                            pre_con += "8o8";
                                            total_len = 0;
                                        }
                                        me.replace(/7o78o8/g,"7o7").replace(/8o87o7/g,"7o7").replace(/7o7to/g, "8o87o7to");
                                        pre_con += me;
                                        is_ct = true;
                                    } else {
                                        // Là text
                                        now_len = dapan_length(me);
                                        check = check_first_letter(me);
                                        if ((check && now_len > 1 && is_ct) || (check && text_len > 70)) {
                                            pre_con += "8o8";
                                            text_len = 0;
                                            total_len = 0;
                                        } else if(check) {
                                            pre_con += " ";
                                        }
                                        total_len += now_len;
                                        text_len += now_len;
                                        is_ct = false;
                                        pre_con += me;
                                    }
                                }
                            }
                        });
                        pre_con = pre_con.replace(/; /g,";8o8").replace(/8o8;/g,";").replace(/8o88o8/g,"8o8").replace(/  /g," ");
//                        console.log(pre_con);
                        $(element).find("td:eq(1)").html("<p>" + pre_con + "</p>");




//                        pre_con = "";
//                        is_ct = false;
//                        total_len = 0;
//                        is_enter = true;
//                        $(element).find("td:eq(1) p").each(function (index2, element2) {
//                            if($(element2).find("span").length) {
//                                var me = format_ct($(element2).find("span").text());
//                                if(me == "") {
//                                    $(element2).remove();
//                                } else {
//                                    if(!is_enter && check_first_letter(me)) {
//                                        pre_con += "8o8";
//                                    }
////                                    if(pre_con.substr(pre_con.length-1,1) == "." || pre_con.substr(pre_con.length-1,1) == ",") {
////                                        pre_con += " ";
////                                    }
////                                    pre_con += " " + me;
//                                    pre_con += me;
//                                    is_ct = false;
//                                    is_enter = true;
//                                    total_len = 0;
//                                }
//                            } else {
////                                if(is_enter) {
////                                    pre_con += "8o8";
////                                }
//                                var me = format_ct($(element2).text());
////                                if(pre_con.substr(pre_con.length-4,4) == "7o7]" && me.substr(0,4) == "7o7[") {
////                                    pre_con += "8o8";
////                                }
//                                if(me.substr(0,12)=="7o7[7o7left.") {
//                                    pre_con += "8o8";
//                                    is_enter = true;
//                                } else {
//                                    now_len = dapan_length(me);
//                                    total_len += now_len;
//                                    if (total_len > 54) {
//                                        if (!is_ct) {
//                                            pre_con += "8o8";
//                                            is_enter = true;
//                                        } else {
//                                            is_enter = false;
//                                        }
////                                    total_len = 0;
//                                        is_enter = false;
//                                    } else {
////                                    console.log(me);
//                                        is_enter = true;
//                                    }
//                                }
//                                if(me.search("7o7\\[") >= 0) {
//                                    is_ct = true;
//                                }
//                                if(me.search("7o7\\]") >= 0) {
//                                    is_ct = false;
//                                    is_enter = false;
//                                }
//                                if(me.search("7o7\\]") >= 0 && me.search("7o7to") >= 0) {
//                                    is_ct = true;
//                                }
//                                pre_con += me;
//                            }
//                        });
////                        pre_con = pre_con.replace(/;/g,";8o8");
//                        pre_con = pre_con.replace(/; /g,";8o8").replace(/8o8;/g,";").replace(/8o88o8/g,"").replace(/  /g," ");
////                        pre_con = pre_con.replace(/8o8;/g,";").replace(/;8o8/g,";");
//                        $(element).find("td:eq(1)").html("<p>" + pre_con + "</p>");
                    });

                    var count_anh = 1;
                    $(element3).find("table tr.cau").each(function(index, element) {
                        var type = $(element).find("td:first-child p").text().trim().replace(/8o8/g,"");
                        var content = $(element).find("td:eq(1) p").text();

                        var maDe = pre_ma + "" + type.substring(2);
                        var de = "";
                        var anhDe = "none";
                        var dapanIndex = [];
                        var dapan = [];
                        var dapanType = [];
                        var dapanMain = [];
                        var chiTiet = "none";
                        var chiTietAnh = "none";
                        var level = 0;
                        var note = "";

                        var stt_cau = $(element).attr("data-cau");

                        de = content;
                        var next_tr = $(element).next("tr");
                        var j = 0;
                        while(true) {
                            var temp_tr = next_tr;
                            var extra = temp_tr.find("td:first-child p").text().trim().replace(/8o8/g,"");
                            var extra_con = temp_tr.find("td:eq(1) p").text().replace(/8o8/g,"");
                            if(extra == "" && extra_con == "") {
                                de += "|3o3|" + pre_ma + "-image" + count_anh + "|4o4|";
                                anhDe = pre_ma + "-image" + count_anh;
                                count_anh++;
                            }
                            if(extra == "" && extra_con != "") {
                                de += temp_tr.find("td:eq(1) p").text();
                            }
                            next_tr = next_tr.next("tr");
                            if(extra != "") {
                                break;
                            }
                            j++;
                            if(j == 10)
                                break;
                        }
                        if(j == 1) {
                            de = de.replace("|3o3|" + anhDe + "|4o4|","");
                        } else {
                            anhDe = "none";
                        }
                        de = de.replace(/8o8/g," ");
//                        if(de.substr(0,3) == "8o8") {
//                            de = de.replace("8o8","");
//                       }
//                        de = de.replace(/8o87o7\[/g,"7o7[").replace(/7o7\]8o8/g,"7o7]").replace(/8o8\$/g,"$").replace(/\$8o8/g,"$");
                        console.log(maDe + " - " + de + " - " + anhDe + " - i=" + j);

                        var stt_dapan = 0;
                        $(element3).find("table tr.info-cau-" + stt_cau).each(function(index2, element2) {
                            var type_con = $(element2).find("td:first-child p").text().trim().replace(/8o8/g,"");
                            var content_con = $(element2).find("td:eq(1) p").text();

                            var loai = parseInt(type_con.substring(0,1).trim());
                            var explain = type_con.substring(2).trim();
                            if(loai == 2) {
                                dapanIndex.push(explain);
                                if(content_con == "") {
                                    dapan.push(pre_ma + "-image" + count_anh);
                                    count_anh++;
                                    dapanType.push("image")
                                } else {
                                    dapan.push(content_con);
                                    dapanType.push("text");
                                }
                                stt_dapan++;
                            } else if(loai == 3) {
                                for(i = 0; i < stt_dapan; i++) {
                                    if(dapanIndex[i] == content_con.trim().replace(/8o8/g,"")) {
                                        dapanMain.push(1);
                                    } else {
                                        dapanMain.push(0);
                                    }
                                }
                            } else if(loai == 4) {
                                if(content_con != "") {
                                    chiTiet = content_con;
                                }

                                var next_tr = $(element2).next("tr");
                                var j = 0;
                                while(true) {
                                    var temp_tr = next_tr;
                                    var extra = temp_tr.find("td:first-child p").text().trim().replace(/8o8/g,"");
                                    var extra_con = temp_tr.find("td:eq(1) p").text().replace(/8o8/g,"");
                                    if(extra == "" && extra_con == "") {
                                        chiTiet += "|3o3|" + pre_ma + "-image" + count_anh + "|4o4|";
                                        chiTietAnh = pre_ma + "-image" + count_anh;
                                        count_anh++;
                                    }
                                    if(extra == "" && extra_con != "") {
                                        chiTiet += temp_tr.find("td:eq(1) p").text();
                                    }
                                    next_tr = next_tr.next("tr");
                                    if(extra != "") {
                                        break;
                                    }
                                    j++;
                                    if(j == 10)
                                        break;
                                }
                                if(j == 1) {
                                    chiTiet = chiTiet.replace("|3o3|" + chiTietAnh + "|4o4|","");
                                } else {
                                    chiTietAnh = "none";
                                }
                            } else if(loai == 5) {
                                level = content_con;
                            } else if(loai == 6) {
                                <?php if($is_notecd == 1) { ?>
                                    note = content_con.replace(/ /g,"").replace(/8o8/g,"");
                                <?php } else { ?>
                                    note = content_con;
                                <?php } ?>
                            }
                        });
                        for(i = 0; i < stt_dapan; i++) {
                            console.log(dapanIndex[i] + " - " + dapan[i] + " - " + dapanType[i] + " - " + dapanMain[i]);
                        }
                        if(chiTiet.substr(0,3) == "8o8") {
                            chiTiet = chiTiet.replace("8o8","");
                        }
                        console.log(chiTiet + " - " + chiTietAnh);
//                        console.log(level);
//                        console.log(note);

                        ajax_data += '{"cau-' + stt_cau + '":[' +
                            '{"maDe":"' + maDe + '","de":"' + de + '","deAnh":"' + anhDe + '","chiTiet":"' + chiTiet + '","chiTietAnh":"' + chiTietAnh + '","level":"' + level + '","note":"' + note +'"},' +
                            '{"dapan":[';
                        for(i = 0; i < stt_dapan; i++) {
                            ajax_data += '{"stt":"' + i + '","dapan":"' + dapan[i] + '","dapanType":"' + dapanType[i] + '","dapanMain":"' + dapanMain[i] + '"},';
                        }
                        ajax_data += '{"num":"' + stt_dapan + '"}]}' +
                            ']},';
                    });
                });

                ajax_data += '{"pre":"<?php echo $filedone; ?>","check":"<?php echo $is_check; ?>","notecd":"<?php echo $is_notecd; ?>"}';
                ajax_data += "]";
//                console.log(ajax_data);
                if(valid_json(ajax_data)) {
                    console.log("Ok");
                    $.ajax({
                        async: true,
                        data: "ajax_data=" + ajax_data + "&monID=<?php echo $monID; ?>",
                        type: "post",
                        url: "http://localhost/www/TDUONG/luyenthi/admin/xuly-cau-hoi/",
                        success: function (result) {
//                            $("#error-form").html(result);
//                            console.log("Kết quả: " + result);
                            $("#error-form").html("IMPORT thành công!!!");
                            setTimeout(function() {
                                window.location.href='http://localhost/www/TDUONG/luyenthi/admin/import_cau_hoi_ex.php';
                            },500);
                        }
                    });
                } else {
                    console.log("No");
                    $("#error-form").find("span").html("Có lỗi đã xảy ra!!!");
                }
                <?php } ?>
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
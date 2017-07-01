<?php
    ob_start();
    session_start();
    ini_set('max_execution_time', 300);
    require_once("../model/model.php");
    require_once("access_admin.php");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if(isset($_GET["deID"]) && is_numeric($_GET["deID"])) {
        $deID = $_GET["deID"];
    } else {
        $deID = 0;
    }
    if(isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
        $nhom = $_GET["nhom"];
    } else {
        $nhom = 0;
    }
    if(isset($_GET["luuy"]) && is_numeric($_GET["luuy"]) && isset($_GET["goc"]) && is_numeric($_GET["goc"])) {
        $luuy = $_GET["luuy"];
        $goc = $_GET["goc"];
    } else {
        $luuy = 1;
        $goc = 1;
    }
    if(isset($_GET["start"]) && is_numeric($_GET["start"]) && isset($_GET["end"]) && is_numeric($_GET["end"])) {
        $start = $_GET["start"];
        $end = $_GET["end"];
    } else {
        $start = 0;
        $end = 0;
    }
    $db = new De_Thi();
    if($deID != 0) {
        $only = true;
    } else {
        $deID = $db->getDeThiMainByNhom($nhom);
        $only = false;
    }
    $result0 = $db->getDeThiById($deID);
    $data0 = $result0->fetch_assoc();
    $da_arr = array("A","B","C","D","E","F","G","H","I","K");

    $db2 = new Cau_Hoi();
    $db3 = new Loai_De();
    $result1 = $db3->getLoaiDeById($data0["loai"]);
    $data1 = $result1->fetch_assoc();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LUYỆN THI TRẮC NGHIỆM</title>
    <script src="http://localhost/www/TDUONG/luyenthi/assets/js/core/libraries/jquery.min.js"></script>
    <style type="text/css">
        @font-face {
            font-family: 'FontMe';
            src: url('http://localhost/www/TDUONG/luyenthi/admin/assets/fonts/Cambria.ttf');
        }
        @media print {
            #toggle-math, #toggle-scroll {display: none;}
            body, page {
                margin: 0;
                box-shadow: 0;
            }
            @page {
                margin: 0;
                size: A4;
            }
        }
        page[size="A4"] {
            width: 21cm;
            height: 29.6cm;
        }
        * {margin: 0;padding: 0;list-style-type: none;}
        page {background: #FFF;display: block;margin:auto;position: relative;overflow: hidden;}
        body {font-family: 'FontMe';background: rgb(204,204,204);}
        .MAIN {width: 100%;height: 100%;margin: auto;}
        .container {margin:20px 50px 0 50px;}
        .container #top nav {display:inline-table;text-align: center;}.container #top nav#top-left {width: 34%;}.container nav#top-right {width: 64%;}
        .container #top nav h2 {text-transform: uppercase;font-size: 15px;}
        .container #top nav h3 {margin: 3px auto 10px auto;font-size: 17px;}
        .container #top nav h4 {font-size: 17px;text-transform: uppercase;line-height: 24px;}
        .container #top nav#top-left span {border-top:1px solid #000;display: block;width: 50%;margin:5px auto 13px auto;}
        .container #top nav#top-right span {font-size: 15px;}
        .container .content {margin-top: 20px;}
        .container .content table {width: 100%;margin-top: 5px;}
        .container .content table tr th {text-align: left;font-weight: 400;line-height: 24px;0vertical-align: top;}
        .container .content table tr th p {font-size: 17px;}
        .container .content table tr td {padding: 2.5px 0 2.5px 0;vertical-align: text-top;}
        .container .content table tr td p {font-size: 17px;}
        .container .content table tr td p span.cau, .container .content table tr th p span.cau {font-weight: 600 !important;margin-right: 5px;}
        .container .con-main {text-align: center;margin-top: 110px;}
        .container .con-main h1 {font-size: 100px;}
        .container .con-remind {margin: 25px 0 0 0;}
        .container .con-remind > p {font-weight: 600;text-transform: uppercase;text-decoration: underline;font-size: 17px;}
        .container .con-remind ul {margin-top: 5px;padding-left: 10px;}
        .container .con-remind ul li {font-size: 17px;font-style: italic;line-height: 24px;}
        .container .con-remind ul li span {font-size: 9px;color: #000;margin-right: 5px;}
        .header {text-align: center;}
        .header nav {background: #000;}
        .header nav.pre-left {position: absolute;left:-110px;top:-40px;z-index:9;-ms-transform: rotate(-45deg);-webkit-transform: rotate(-45deg);transform: rotate(-45deg);height: 100px;width: 250px;text-align: center;}
        .header nav.pre-left p {font-size: 55px;-ms-transform: rotate(90deg);-webkit-transform: rotate(90deg);transform: rotate(90deg);margin-top: 40px;font-weight: 600;}
        .header nav.pre-mid {width:150px;margin: auto;padding: 3px 0 3px 0;}
        .header nav.pre-mid p {font-size: 15px;font-weight: 600;}
        .header nav.pre-right {position: absolute;right:-110px;top:-40px;z-index:9;-ms-transform: rotate(45deg);-webkit-transform: rotate(45deg);transform: rotate(45deg);height: 100px;width: 250px;text-align: center;}
        .header nav.pre-right p {font-size: 29px;font-weight: 600;bottom: 5px;width: 100%;position: absolute;}
        .header nav p {color: #FFF;text-transform: uppercase;}
        .footer {width: 100%;position: absolute;z-index: 9;bottom: 0;left: 0;background: #FFF;}
        .footer ul {margin: 0 50px 0 50px;border-top: 1px solid #000;}
        .footer ul li {float: left;padding: 5px  0 5px 2%;}
        #toggle-math {position: fixed;z-index: 99;top: 0;left: 0;}
        #toggle-scroll {position: fixed;z-index: 99;top: 0;right: 0;}
        .mjx-chtml {padding: 0 !important;font-size: 100% !important;display: inline !important;}
        .mjx-mtd {text-align: left !important;}
        .MJXc-space2, .MJXc-space3 {margin-left: 2px !important;}
    </style>
</head>
<div>
    <?php
        $mon_name = (new Mon_Hoc())->getNameMonLop($data0["ID_LM"]);
        $temp = explode(" ",$mon_name);
        $lop = end($temp);
        if(!is_numeric($lop)) {
            $lop = "";
        }
        $mota = explode("+",$db3->getMotaDe($data0["loai"], $data0["ID_LM"]));
    echo"<button id='toggle-math'>Căn chỉnh</button><button id='toggle-scroll'>Cuộn xuống</button>";
    $nextCN = formatDateUp(getNextCN());
    $dem = 0;
    if($only) {
        $result = $db->getDeThiById($deID);
    } else {
        $result = $db->getDeThiByNhomKhoang($nhom, $start, $end);
    }
    while($data = $result->fetch_assoc()) {
//        if($dem == 10) {break;}
        echo"<div class='de-big'>";
        if($luuy == 1) {
            echo "<page size='A4'>
                <div class='MAIN'>
                    <div class='header'>
                        <nav class='pre-mid' style='width: 100%;padding: 10px 0 10px 0;'><p>" . $nextCN . "</p></nav>
                    </div>
                    <div class='container'>
                        <div class='con-main'>
                            <h1>".mb_strtoupper($temp[0])."<br />$lop - $data1[name]<br /><span style='font-size: 29px;font-weight: 400;'>Mã đề: $data[maso]</span></h1>
                        </div>
                        <div class='con-remind'>
                            <p>Mô tả:</p>
                            <ul>
                                <li><span>&#9899;</span>Đề thi gồm <strong class='num-trang'></strong>.</li>
                                <li><span>&#9899;</span>Đề thi gồm <strong class='num-cau'></strong>.</li>
                                <li><span>&#9899;</span>Mỗi câu đúng được tính <strong class='num-diem'></strong>.</li>
                            </ul>
                        </div>
                        <div class='con-remind'>
                            <p>Lưu ý:</p>
                            <ul>";
                                for($i = 0; $i < count($mota); $i++) {
                                    if($mota[$i] != "" && $mota[$i] != " ") {
                                        echo"<li><span>&#9899;</span>".trim(formatPara($mota[$i]))."</li>";
                                    }
                                }
                            echo"</ul>
                        </div>
                    </div>
                    <div class='footer'>
                        <ul>
                            <li style='width: 28%;background: #000;'><span style='color: #FFF;'><strong>Thầy Dương (KTQD)</strong></span></li>
                            <li style='width: 28%;'><span><strong>Tel</strong>: 09.765.82.764</span></li>
                            <li style='width: 37%;border-left: 1px solid #000;padding-top: 0;padding-bottom: 0;margin-top: 5px;margin-bottom: 5px;'><span><strong>Facebook.com</strong>/diengia.phanduong</span></li>
                        </ul>
                    </div>
                </div>
            </page>";
        }
    ?>
    <page size="A4">
        <div class="MAIN">
            <div class="header">
                <nav class="pre-left"><p><?php echo substr($data1["name"],0,1); ?></p></nav>
                <nav class="pre-mid"><p><?php echo $nextCN; ?></p></nav>
                <nav class="pre-right"><p><?php echo $lop; ?></p></nav>
            </div>
            <div class="container">
                <div id="top">
                    <nav id=top-left>
                        <h2>Lớp Toán thầy Phan Dương</h2>
                        <span></span>
                        <h4><?php echo $data1["mota"]; ?><br />(<?php echo $data1["name"]; ?> - <?php echo $data["maso"]; ?>)</h4>
                    </nav>
                    <nav id="top-right">
                        <h2>Kì thi trung học phổ thông quốc gia năm 2017</h2>
                        <h3>Môn thi: <?php echo mb_strtoupper($temp[0],"UTF-8"); ?></h3>
                        <span>Thời gian làm bài: <?php echo $data["time"]; ?> phút, không kể thời gian phát đề</span>
                    </nav>
                </div>
                <div class="content">
                    <?php
//                        if($dem == 0) {
                            $dapan_all = array();
                            $result2 = $db->getDapAnNganByDeAllUse($data["ID_DE"], false);
                            while ($data2 = $result2->fetch_assoc()) {
                                $dapan_all[$data2["ID_C"]][] = array(
                                    "type" => $data2["type"],
                                    "content" => $data2["content"]
                                );
                            }
//                        }

                        $cau = 1;
                        $result2 = $db->getCauHoiByDe($data["ID_DE"],false);
                        while($data2 = $result2->fetch_assoc()) {
                            echo"<table class='cau-hoi'>";
                            $warp = false;
                            if($data2["anh"] != "none") {
                                if($data2["content"] != "none") {
                                    $size = getimagesize("http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data2["ID_MON"],$data2["anh"]));
                                    $width = $size[0];
                                    $height = $size[1];
                                    if($width <= 300 || abs($height-$width) <= 200) {
//                                    if(($height >= $width || $height*1.3 > $width) || $width <= 300) {
                                        echo "<tr style='height: 0px;' class='de-has-anh' data-cau='$cau'>
                                            <th colspan='3' style='width: 60%;'><p><span class='cau'>Câu $cau.</span>" . imageToImg($data2["ID_MON"], $data2["content"], 200) . "</p></th>
                                            <th style='width: 40%;' class='de-anh'><p><span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data2["ID_MON"],$data2["anh"])."' style='max-height:250px;width:100%;' /></span></p></th>
                                        </tr>";
                                        $warp = true;
                                    } else {
                                        echo "<tr style='height: 0px;'>
                                            <th colspan='4'><p><span class='cau'>Câu $cau.</span>" . imageToImg($data2["ID_MON"],$data2["content"],200) . "</p></th>
                                        </tr>
                                        <tr style='height: 0px;'>
                                            <th colspan='4'><p><span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data2["ID_MON"],$data2["anh"])."' style='max-height:250px;max-width: 70%;' /></span></p></th>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr style='height: 0px;'>
                                        <th colspan='4'><p><span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data2["ID_MON"],$data2["anh"])."' style='max-height:250px;max-width: 70%;' /></span></p></th>
                                    </tr>";
                                }
                            } else {
                                if($data2["content"] != "none") {
                                    echo "<tr style='height: 0px;'>
                                        <th colspan='4'><p><span class='cau'>Câu $cau.</span>" . imageToImg($data2["ID_MON"],$data2["content"],200) . "</p></th>
                                    </tr>";
                                }
                            }

                            $form = $max = 0;
                            $n = count($dapan_all[$data2["ID_C"]]);
                            if($n > 4) {
                                $max = 2;
                            }
                            for($i = 0; $i < $n; $i++) {
                                if($dapan_all[$data2["ID_C"]][$i]["type"] == "text") {
                                    $form = formatDapAn($dapan_all[$data2["ID_C"]][$i]["content"]);
                                } else {
                                    $form = 2;
                                }
                                $max = $form > $max ? $form : $max;
                            }

                            $form = $max;

                            if($warp && $form == 1) {
                                $form = 2;
                            }

                            if($form == 1) {
                                echo"<tr class='dap-an dap-an-$cau'>";
                            }
                            $dap = 1;
                            for($i = 0; $i < $n; $i++) {
                                if($form == 1) {
                                    echo"<td style='width: 25%;'>
                                        <p><span class='cau'>".$da_arr[$dap-1].".</span>".imageToImgDapan($data2["ID_MON"],$dapan_all[$data2["ID_C"]][$i]["content"],200)."</p>
                                    </td>";
                                } else if($form == 2) {
                                    if($dap % 2 != 0) {
                                        echo"<tr class='dap-an dap-an-$cau'>";
                                    }

                                    if($warp) {
//                                        if($dap % 2 != 0) {
//                                            $me = "";
//                                        } else {
//                                            $me = "";
//                                        }
                                        if ($dapan_all[$data2["ID_C"]][$i]["type"] == "text") {
                                            echo "<td style='width: 30%;'>
                                                <p><span class='cau'>" . $da_arr[$dap - 1] . ".</span>" . imageToImgDapan($data2["ID_MON"], $dapan_all[$data2["ID_C"]][$i]["content"], 200) . "</p>
                                            </td>";
                                        } else {
                                            echo "<td style='width: 30%;'>
                                                <p><span class='cau'>" . $da_arr[$dap - 1] . ".</span><span style='text-align: left;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data2["ID_MON"],$dapan_all[$data2["ID_C"]][$i]["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' /></span></p>
                                            </td>";
                                        }
                                    } else {
                                        if ($dapan_all[$data2["ID_C"]][$i]["type"] == "text") {
                                            echo "<td colspan='2' style='width: 50%;'>
                                                <p><span class='cau'>" . $da_arr[$dap - 1] . ".</span>" . imageToImgDapan($data2["ID_MON"], $dapan_all[$data2["ID_C"]][$i]["content"], 200) . "</p>
                                            </td>";
                                        } else {
                                            echo "<td colspan='2' style='width: 50%;'>
                                                <p><span class='cau'>" . $da_arr[$dap - 1] . ".</span><span style='text-align: left;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/".$db2->getUrlDe($data2["ID_MON"],$dapan_all[$data2["ID_C"]][$i]["content"])."' style='max-height:250px;' class='img-thumbnail img-responsive' /></span></p>
                                            </td>";
                                        }
                                    }

                                    if($dap % 2 == 0) {
                                        echo"</tr>";
                                    }
                                } else {
                                    if($warp) {
                                        $me = "colspan='3'";
                                    } else {
                                        $me = "";
                                    }
                                    echo"<tr class='dap-an dap-an-$cau'><td $me>
                                        <p><span class='cau'>".$da_arr[$dap-1].".</span>".imageToImgDapan($data2["ID_MON"],$dapan_all[$data2["ID_C"]][$i]["content"],200)."</p>
                                    </td></tr>";
                                }

                                $dap ++;
                            }
                            if($form == 1) {
                                echo"</tr>";
                            }

                            $cau++;
                            echo"</table>";
                        }
                        $cau--;
                    ?>
                    <table class="cau-hoi">
                        <tr>
                            <td colspan="4" style="text-align: center;"><p style="font-size: 13px;">---------------------------------- <strong>HẾT</strong> ----------------------------------</p></td>
                        </tr>
                    </table>
                </div>
            </div>
<!--            <div class="footer" style="display: none;">-->
<!--                <ul>-->
<!--                    <li style="width: 28%;background: #000;"><span style="color: #FFF;"><strong>Thầy Dương (KTQD)</strong></span></li>-->
<!--                    <li style="width: 28%;"><span><strong>Tel</strong>: 09.765.82.764</span></li>-->
<!--                    <li style="width: 37%;border-left: 1px solid #000;padding-top: 0;padding-bottom: 0;margin-top: 5px;margin-bottom: 5px;"><span><strong>Facebook.com</strong>/diengia.phanduong</span></li>-->
<!--                </ul>-->
<!--            </div>-->
        </div>
    </page>
    </div>
    <?php
        $dem++;
        if($goc==1) {break;}
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $("table.cau-hoi tr.de-has-anh").each(function (index,element) {
                var cau = $(element).attr("data-cau");
                $(element).find("th.de-anh").attr("rowspan",$("table tr.dap-an-" + cau).length + 1);
                $("table.cau-hoi tr.dap-an-" + cau).removeAttr("style").css("height","0px");
                $("table.cau-hoi tr.dap-an-" + cau + ":last-child").css({"height":"auto","vertical-align":"top"});
                $("table.cau-hoi tr.dap-an-" + cau).find("td").css("vertical-align","inherit");
            });

            var page_height = $("page").height();
            console.log(page_height);
            var cur_height = 0;
            $("#toggle-math").click(function() {
                $(this).hide();
                $("div.de-big").each(function (index2, element2) {
                    cur_height = 0;
                    var cur_page = 2;
                    var first_page = true;
                    $(element2).find("table.cau-hoi").each(function (index, element) {
                        if(first_page) {
                            limit_page = 200;
                        } else {
                            limit_page = 100;
                        }
                        cur_height += $(element).height();
                        if (cur_height > page_height - limit_page) {
                            console.log((index2 + 1) + " - " + (index + 1) + " - " + cur_height + " - Overflow! Limit: " + limit_page);
                            $(element2).find("page").removeClass("page-new");
                            $(element2).append("<page size='A4' class='page-new'><div class='MAIN'><div class='header'><nav class='pre-left'><p><?php echo $data1["name"]; ?></p></nav><nav class='pre-mid'><p><?php echo $nextCN; ?></p></nav> <nav class='pre-right'><p><?php echo $lop; ?></p></nav></div><div class='container'><div class='content'></div></div></div></page>");
                            cur_page++;
                            cur_height = $(element).height();
                            first_page = false;
                        } else {
                            console.log((index + 1) + " - " + cur_height);
                        }
                        if ($(element2).find("page").hasClass("page-new")) {
                            $(element2).find("page.page-new .MAIN .container .content").append("<table class='cau-hoi'>" + $(element).html() + "</table>");
                            $(element).closest("table.cau-hoi").remove();
                        }
                    });
                    if (cur_page % 2 != 0) {
                        $(element2).append("<page size='A4'><div class='MAIN'></div></page>");
                        cur_page++;
                    }
                    if(cur_page == 6 || cur_page == 10) {
                        $(element2).append("<page size='A4'><div class='MAIN'></div></page><page size='A4'><div class='MAIN'></div></page>");
                        cur_page+=2;
                    }
                    if ($(element2).find("table.cau-hoi").length > 1) {
                        $(element2).find(".num-trang").html(formatZero($(element2).find("page").length) + " trang");
                        $(element2).find(".num-cau").html(formatZero($(element2).find("table.cau-hoi").length - 1) + " câu hỏi");
                        $(element2).find(".num-diem").html(formatNumber(10 / ($(element2).find("table.cau-hoi").length - 1)) + " điểm");
//                        $(element2).find(".num-tru").html(formatNumber(5 / ($(element2).find("table.cau-hoi").length - 1)) + " điểm");
                    }
                });

            });

            function formatZero(number) {
                if(number < 10) {
                    return "0" + number;
                } else {
                    return number;
                }
            }

            function formatNumber(number) {
                var number = parseFloat((number * 100) / 100).toFixed(3);
                if(parseInt(number.substring(5,4)) == "0") {
                    return number.substring(0,4);
                } else {
                    return number;
                }
            }

            console.log($("body, html").height());
            $("#toggle-scroll").click(function () {
                if($(this).hasClass("active")) {
                    $("body, html").scrollTop(0);
                    $(this).removeClass("active").html("Cuộn xuống");
                } else {
                    $("body, html").scrollTop($("body, html").height());
                    $(this).addClass("active").html("Cuộn lên");
                }
            });
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
            NativeMML: { linebreaks: { automatic: true }, minScaleAdjust: 90, scale: 90},
            TeX: { noErrors: { disabled: true } },
        });
    </script>
</body>
</html>
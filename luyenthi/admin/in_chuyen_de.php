<?php
    ob_start();
    session_start();
    require_once("../model/model.php");
    require_once("access_admin.php");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

    if(isset($_GET["nhom"]) && is_numeric($_GET["nhom"])) {
        $nhom = $_GET["nhom"];
    } else {
        $nhom = 0;
    }
    $db = new De_Thi();
    if(isset($_GET["maso"])) {
        $nhom = $_GET["maso"];
    }
    $result4 = $db->getNhomDeById($nhom);
    $data4 = $result4->fetch_assoc();
    $deID = $db->getDeThiMainByNhom($nhom);
    $result0 = $db->getDeThiById($deID);
    $data0 = $result0->fetch_assoc();
    $da_arr = array("A","B","C","D","E","F","G","H","I","K");
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
            #toggle-math {display: none;}
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
        .container .content table tr th {text-align: left;font-weight: 400;line-height: 24px;vertical-align: top;}
        .container .content table tr th p {font-size: 17px;}
        .container .content table tr td {padding: 2.5px 0 2.5px 0;vertical-align: text-top;}
        .container .content table tr td p {font-size: 17px;}
        .container .content table tr td p span.cau, .container .content table tr th p span.cau {font-weight: 600 !important;margin-right: 5px;}
        .container .con-main {text-align: center;margin-top: 130px;}
        .container .con-main h1 {font-size: 100px;}
        .container .con-remind {margin: 25px 0 0 0;}
        .container .con-remind > p {font-weight: 600;text-transform: uppercase;text-decoration: underline;font-size: 17px;}
        .container .con-remind ul {margin-top: 5px;padding-left: 10px;}
        .container .con-remind ul li {font-size: 17px;font-style: italic;line-height: 24px;}
        .container .con-remind ul li span {font-size: 9px;color: #000;margin-right: 5px;}
        .header {text-align: center;}
        .header nav {margin: 20px 70px 0 70px;}
        .header nav.pre-mid {padding: 3px 40px 3px 40px;background: #000;float: right;}
        .header nav.pre-mid p {font-size: 15px;font-weight: 600;color: #FFF;}
        .header nav.pre-main {border-top: 2px solid #000;border-bottom: 2px solid #000;padding: 10px 0 10px 0;}
        .header nav.pre-main p {font-size:37px;}
        .header nav.pre-main p span {font-weight: 600;}
        .header nav p {text-transform: uppercase;}
        .footer {width: 100%;margin-top: 30px;position: absolute;z-index: 9;bottom: 20px;left: 0;background: #FFF;}
        .footer ul {margin: 0 50px 0 50px;border-top: 1px solid #000;}
        .footer ul li {float: left;padding: 5px  0 5px 2%;}
        #toggle-math {position: fixed;z-index: 99;top: 0;left: 0;}
        .mjx-chtml {padding: 0 !important;font-size: 100% !important;display: inline !important;}
        .mjx-mtd {text-align: left !important;}
        .MJXc-space2, .MJXc-space3 {margin-left: 2px !important;}
    </style>
</head>
<body>
    <button id='toggle-math'>Căn chỉnh</button>
    <div class='de-big'>
    <page size="A4">
        <div class="MAIN">
            <div class="header">
                <nav class="pre-mid"><p>Mã chuyên đề: <?php echo $data4["code"]; ?></p></nav>
                <nav class="pre-main" style="clear: both;"><p><span>Chuyên đề:</span> <?php echo $data0["mota"]; ?></p></nav>
            </div>
            <div class="container">
                <div class="content">
                    <?php
                        $dapan_all = array();
                        $result2 = $db->getDapAnNganByDeAllUse($deID, false);
                        while ($data2 = $result2->fetch_assoc()) {
                            $dapan_all[$data2["ID_C"]][] = array(
                                "type" => $data2["type"],
                                "content" => $data2["content"]
                            );
                        }

                        $cau = 1;
                        $db2 = new Cau_Hoi();
                        $result2 = $db->getCauHoiByDe($deID,false);
                        while($data2 = $result2->fetch_assoc()) {
                            echo"<table class='cau-hoi'>";
                            $warp = false;
                            if($data2["anh"] != "none") {
                                if($data2["content"] != "none") {
                                    $size = getimagesize("http://localhost/www/TDUONG/luyenthi/upload/$data2[ID_MON]/$data2[anh]");
                                    $width = $size[0];
                                    $height = $size[1];
                                    if($width <= 300 || abs($height-$width) <= 200) {
//                                    if(($height >= $width || $height*1.3 > $width) || $width <= 300) {
                                        echo "<tr style='height: 0px;' class='de-has-anh' data-cau='$cau'>
                                            <th colspan='3' style='width: 60%;'><p><span class='cau'>Câu $cau.</span>" . imageToImg($data2["ID_MON"], $data2["content"], 200) . "</p></th>
                                            <th style='width: 40%;' class='de-anh'><p><span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/upload/$data2[ID_MON]/$data2[anh]' style='max-height:250px;width:100%;' /></span></p></th>
                                        </tr>";
                                        $warp = true;
                                    } else {
                                        echo "<tr style='height: 0px;'>
                                            <th colspan='4'><p><span class='cau'>Câu $cau.</span>" . imageToImg($data2["ID_MON"],$data2["content"],200) . "</p></th>
                                        </tr>
                                        <tr style='height: 0px;'>
                                            <th colspan='4'><p><span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/upload/$data2[ID_MON]/$data2[anh]' style='max-height:250px;' /></span></p></th>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr style='height: 0px;'>
                                        <th colspan='4'><p><span style='text-align: center;display: block;'><img src='http://localhost/www/TDUONG/luyenthi/upload/$data2[ID_MON]/$data2[anh]' style='max-height:250px;' /></span></p></th>
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
//                                            $me = "colspan='2'";
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
                    <table class="cau-hoi">
                        <tr><th colspan="4"><p style="font-weight: 600;text-transform: uppercase;text-decoration: underline;font-size: 17px;">HƯỚNG DẪN LÀM BÀI TẬP TRẮC NGHIỆM BẮT BUỘC</p></th></tr>
                    <?php
                        $mota = explode("+",(new Loai_De())->getMotaDe($data0["loai"], $data0["ID_LM"]));
                        for($i = 0; $i < count($mota); $i++) {
                            if($mota[$i] != "" && $mota[$i] != " ") {
                                $mota[$i] = str_replace("{ma-so}",$data4["code"],$mota[$i]);
				                $mota[$i] = str_replace("{ten-chuyen-de}",$data0["mota"],$mota[$i]);
                                echo"<tr><th colspan='4'><p><span class='cau' style='font-size:9px;'>&#9899;</span>".formatPara($mota[$i])."</p></th></tr>";
                            }
                        }
                    ?>
                    </table>
                </div>
            </div>
            <div class="footer">
                <ul>
                    <li style="width: 28%;background: #000;"><span style="color: #FFF;"><strong>Thầy Dương (KTQD)</strong></span></li>
                    <li style="width: 28%;"><span><strong>Tel</strong>: 09.765.82.764</span></li>
                    <li style="width: 37%;border-left: 1px solid #000;padding-top: 0;padding-bottom: 0;margin-top: 5px;margin-bottom: 5px;"><span><strong>Facebook.com</strong>/diengia.phanduong</span></li>
                </ul>
            </div>
        </div>
    </page>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $("table.cau-hoi tr.de-has-anh").each(function (index,element) {
                var cau = $(element).attr("data-cau");
                $(element).find("th.de-anh").attr("rowspan",$("table tr td.dap-an-" + cau).length + 1);
                $("table tr td.dap-an-" + cau).closest("tr").not(':last').css("height","0px");
            });

            var page_height = $("page").height();
            console.log(page_height);
            var cur_height = 0;
            $("#toggle-math").click(function() {
                $(this).hide();
                $("div.de-big").each(function (index2, element2) {
                    cur_height = 0;
                    var cur_page = 0;
                    var first_page = true;
                    $(element2).find("table.cau-hoi").each(function (index, element) {
                        if(first_page) {
                            limit_page = 200;
                        } else {
                            limit_page = 100;
                        }
                        cur_height += $(element).height();
                        if (cur_height > page_height - limit_page) {
                            console.log((index2 + 1) + " - " + (index + 1) + " - " + cur_height + " - Overflow!");
                            $(element2).find("page").removeClass("page-new");
                            $(element2).append("<page size='A4' class='page-new'><div class='MAIN'><div class='container'><div class='content'></div></div></div></page>");
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
                return parseFloat(Math.round(number * 100) / 100).toFixed(2);
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
            NativeMML: { linebreaks: { automatic: true }, minScaleAdjust: 90, scale: 90},
            TeX: { noErrors: { disabled: true } },
        });
    </script>
</body>
</html>
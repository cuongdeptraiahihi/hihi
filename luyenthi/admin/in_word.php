<?php
session_start();
require_once("../model/model.php");
require_once("access_admin.php");
include_once 'Sample_Header.php';

if(isset($_GET["deID"]) && is_numeric($_GET["deID"]) && isset($_GET["isda"]) && is_numeric($_GET["isda"]) && isset($_GET["isdetail"]) && is_numeric($_GET["isdetail"])) {
    $deID = addslashes($_GET["deID"]);
    $isda = addslashes($_GET["isda"]);
    $isdetail = addslashes($_GET["isdetail"]);
} else {
    $deID = $isda = $isdetail = 0;
}

// Khởi tạo file Word
echo date('H:i:s') , ' Khởi tạo đối tượng PHPWord' , EOL;
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Header
$top_size = 12;

$phpWord->addFontStyle('hStyle', array('bold' => true, 'italic' => false, 'size' => $top_size, 'allCaps' => true, 'name' => 'Times New Roman'));
$phpWord->addFontStyle('rStyle', array('bold' => true, 'italic' => false, 'size' => $top_size, 'name' => 'Times New Roman'));
$phpWord->addFontStyle('rStyleMain', array('bold' => true, 'italic' => false, 'size' => 16, 'name' => 'Times New Roman', 'color' => 'ff0000'));
$phpWord->addFontStyle('iStyle', array('bold' => false, 'italic' => true, 'size' => $top_size, 'name' => 'Times New Roman'));
$phpWord->addFontStyle('nStyle', array('bold' => false, 'italic' => false, 'size' => $top_size, 'name' => 'Times New Roman'));
$phpWord->addParagraphStyle('pStyle', array('align' => 'center'));
$phpWord->addParagraphStyle('pStyle2', array('spacing' => 100));

$tableCellStyle = array();

$db2 = new Cau_Hoi();
$da_arr = array("A","B","C","D","E","F","G","H","I","K");

$db = new De_Thi();

$cellColSpanOne = array('valign' => 'center');

$cellCol = array('gridSpan' => 2, 'vMerge' => 'restart', 'valign' => 'top');
$cellColSpan = array('gridSpan' => 2, 'valign' => 'top');
$cellColSpanContinue = array('vMerge' => 'continue', 'gridSpan' => 2, 'valign' => 'top');

$cellColSpanImg = array('gridSpan' => 3, 'valign' => 'top');
$cellColSpanX = array('gridSpan' => 4, 'valign' => 'top');

$result = $db->getDeThiById($deID);
$data = $result->fetch_assoc();

$section = $phpWord->addSection(array("marginLeft" => 1000, "marginRight" => 1000, "marginTop" => 1000));

$table = $section->addTable();

//$table->addRow();
//$top_left = $table->addCell(4000, $tableCellStyle);
//$top_left->addText("Lớp toán thầy Phan Dương", 'hStyle', 'pStyle');
//$top_left->addText("------------------------", 'nStyle', 'pStyle');
//$top_right = $table->addCell(6000, $tableCellStyle);
//$top_right->addText("Kì thi trung học phổ thông quốc gia 2017", 'hStyle', 'pStyle');
//$top_right->addText("Môn thi: TOÁN", 'rStyle', 'pStyle');
//
//$table->addRow();
//$table->addCell(4000, $tableCellStyle)->addText("Mã đề $data[maso]", 'hStyle', 'pStyle');
//$bot_right = $table->addCell(6000, $tableCellStyle);
//$bot_right->addText("Thời gian làm bài: $data[time] phút, không kể thời gian phát đề", 'iStyle', 'pStyle');
//$section->addTextBreak(1);

$table->addRow();
$center = $table->addCell(10000, $tableCellStyle);
$center->addText($data["mota"], 'hStyle', 'pStyle');

$cau = 1;
$result2 = $db->getCauHoiByDeWithTimeLimit($data["ID_DE"]);
while($data2 = $result2->fetch_assoc()) {
    //$table = $section->addTable(array('width' => 100 * 50, 'unit' => 'pct', 'align' => 'center'));
    $section->addTextBreak(1, array("space" => array("line" => 0)));
    $table = $section->addTable();
    $table->addRow();
    $warp = false;
    if($data2["anh"] != "none") {
        if($data2["content"] != "none") {
            $size = getimagesize("../".$db2->getUrlDe($data2["ID_MON"],$data2["anh"]));
            $width = $size[0];
            $height = $size[1];
            if($height >= $width || $height*1.3 > $width) {
                $textrun = $table->addCell(6000, $cellColSpan)->addTextRun();
                $textrun->addText("Câu $cau. ", 'rStyle');
                if($data2["content"] != "none") {
                    $temp = explode("newline",$db2->formatCTOut($data2["content"]));
                    for($i = 0; $i < count($temp); $i++) {
                        if(stripos($temp[$i],"|<|") === false && stripos($temp[$i],"|>|") === false) {
                            if($i != 0) {
                                $textrun->addTextBreak();
                            }
                            $textrun->addText($temp[$i], 'nStyle', 'pStyle2');
                        } else {
                            $image = str_replace("|<|","",$temp[$i]);
                            $image = str_replace("|>|","",$image);
                            $textrun->addTextBreak();
                            $textrun->addImage("../".$db2->getUrlDe($data2["ID_MON"], $image), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                        }
                    }
                }
                $table->addCell(4000, $cellCol)->addImage("../".$db2->getUrlDe($data2["ID_MON"], $data2["anh"]), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                $warp = true;
            } else {
                $textrun = $table->addCell(10000, $cellColSpanX)->addTextRun();
                $textrun->addText("Câu $cau. ", 'rStyle');
                if($data2["content"] != "none") {
                    $temp = explode("newline",$db2->formatCTOut($data2["content"]));
                    for($i = 0; $i < count($temp); $i++) {
                        if(stripos($temp[$i],"|<|") === false && stripos($temp[$i],"|>|") === false) {
                            if($i != 0) {
                                $textrun->addTextBreak();
                            }
                            $textrun->addText($temp[$i], 'nStyle', 'pStyle2');
                        } else {
                            $image = str_replace("|<|","",$temp[$i]);
                            $image = str_replace("|>|","",$image);
                            $textrun->addTextBreak();
                            $textrun->addImage("../".$db2->getUrlDe($data2["ID_MON"],$image), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                        }
                    }
                }
                $table->addRow();
                $table->addCell(10000, $cellColSpanX)->addImage("../".$db2->getUrlDe($data2["ID_MON"],$data2["anh"]), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
            }
        } else {
            $table->addCell(10000, $cellColSpanX)->addImage("../".$db2->getUrlDe($data2["ID_MON"],$data2["anh"]), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
        }
    } else {
        $textrun = $table->addCell(10000, $cellColSpanX)->addTextRun();
        $textrun->addText("Câu $cau. ", 'rStyle');
        if($data2["content"] != "none") {
            $temp = explode("newline",$db2->formatCTOut($data2["content"]));
            for($i = 0; $i < count($temp); $i++) {
                if(stripos($temp[$i],"|<|") === false && stripos($temp[$i],"|>|") === false) {
                    if($i != 0) {
                        $textrun->addTextBreak();
                    }
                    $textrun->addText($temp[$i], 'nStyle', 'pStyle2');
                } else {
                    $image = str_replace("|<|","",$temp[$i]);
                    $image = str_replace("|>|","",$image);
                    $textrun->addTextBreak();
                    $textrun->addImage("../".$db2->getUrlDe($data2["ID_MON"],$image), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                }
            }
        }
    }

    $form = $max = 1;
    $result3 = $db->getDapAnNganByDe($data2["ID_C"],$data["ID_DE"],false);
    if($result3->num_rows > 4) {
        $max = 2;
    }
    $temp_arr = array();
    while ($data3 = $result3->fetch_assoc()) {
        if($data3["type"] == "text") {
            $form = formatDapAn($data3["content"]);
        } else {
            $form = 2;
        }
        $max = $form > $max ? $form : $max;
        $temp_arr[] = $data3;
    }
    $form = $max;

    if($warp && $form == 1) {
        $form = 2;
    }

    $dap = 1;
//    $result3 = $db->getDapAnNganByDe($data2["ID_C"],$data["ID_DE"],false);
//    while($data3 = $result3->fetch_assoc()) {
    $i = 0;
    while(isset($temp_arr[$i])) {
        $data3 = $temp_arr[$i];
        $style = 'rStyle';
        if($data3["main"]==1 && $isda) {
            $style = 'rStyleMain';
        }
        if($form == 1) {
            if ($dap == 1) {
                $table->addRow();
            }
            $textrun2 = $table->addCell(2500, $cellColSpanOne)->addTextRun();
            $textrun2->addText($da_arr[$dap - 1] . ". ", $style);
            $textrun2->addText($db2->formatCTOut($data3["content"]), "nStyle", '');
        } else if($form == 2) {
            if($dap % 2 != 0) {
                $table->addRow();
            }
            if($warp) {
                if($dap % 2 != 0) {
                    if($data3["type"] == "text") {
                        $textrun2 = $table->addCell(3300, $cellColSpanOne)->addTextRun();
                        $textrun2->addText($da_arr[$dap-1].". ", $style);
                        $textrun2->addText($db2->formatCTOut($data3["content"]), "nStyle", '');
                    } else {
                        $table->addCell(3300, $cellColSpanOne)->addImage("../".$db2->getUrlDapAn($data2["ID_MON"],$data3["content"]), array('height' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT));
                    }
                } else {
                    if($data3["type"] == "text") {
                        $textrun2 = $table->addCell(3000, $cellColSpanOne)->addTextRun();
                        $textrun2->addText($da_arr[$dap-1].". ", $style);
                        $textrun2->addText($db2->formatCTOut($data3["content"]), "nStyle", '');
                    } else {
                        $table->addCell(3000, $cellColSpanOne)->addImage("../".$db2->getUrlDapAn($data2["ID_MON"],$data3["content"]), array('height' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT));
                    }
                    $table->addCell(null, $cellColSpanContinue);
                }
            } else {
                if($data3["type"] == "text") {
                    $textrun2 = $table->addCell(5000, array_merge($cellColSpan, array("valign" => "center")))->addTextRun();
                    $textrun2->addText($da_arr[$dap-1].". ", $style);
                    $textrun2->addText($db2->formatCTOut($data3["content"]), "nStyle", '');
                } else {
                    $table->addCell(5000, $cellColSpan)->addImage("../".$db2->getUrlDapAn($data2["ID_MON"],$data3["content"]), array('height' => 100, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT));
                }
            }
        } else {
            $table->addRow();
            if($warp) {
                $textrun2 = $table->addCell(3000, $cellColSpan)->addTextRun();
                    $table->addCell(null, $cellColSpanContinue);
            } else {
                $textrun2 = $table->addCell(10000, $cellColSpanX)->addTextRun();
            }
            $textrun2->addText($da_arr[$dap-1].". ", $style);
            $textrun2->addText($db2->formatCTOut($data3["content"]), "nStyle", '');
        }

        $dap++;
        $i++;
    }

    if($isdetail) {
        $table->addRow();
        $textrun = $table->addCell(10000, $cellColSpanX)->addTextRun();
        $textrun->addText("Đáp án chi tiết. ", 'rStyle');
        if ($data2["da_con"] != "none") {
            $temp = explode("newline", $db2->formatCTOut($data2["da_con"]));
            for ($i = 0; $i < count($temp); $i++) {
                if (stripos($temp[$i], "|<|") === false && stripos($temp[$i], "|>|") === false) {
                    if ($i != 0) {
                        $textrun->addTextBreak();
                    }
                    $textrun->addText($temp[$i], 'nStyle', 'pStyle2');
                } else {
                    $image = str_replace("|<|", "", $temp[$i]);
                    $image = str_replace("|>|", "", $image);
                    $textrun->addTextBreak();
                    $textrun->addImage("../" . $db2->getUrlDe($data2["ID_MON"], $image), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
                }
            }
        }
        if ($data2["da_anh"] != "none") {
            $table->addRow();
            $table->addCell(10000, $cellColSpanX)->addImage("../" . $db2->getUrlDe($data2["ID_MON"], $data2["da_anh"]), array('height' => 200, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER));
        }
    }

    $cau++;
}

// Save file
//echo write($phpWord, basename("De-thi (".formatDateTime($data0["datetime"]).")", ".php"), $writers);
$filename = "De-thi (".formatDateTime($data["ngay"]).")";
$filename = str_replace("/","-",$filename);
$filename = str_replace(":","-",$filename);
echo write($phpWord, $filename, $writers);
if (!CLI) {
    include_once 'Sample_Footer.php';
}

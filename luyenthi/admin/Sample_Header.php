<?php
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
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');

// Set PDF renderer
if (null === Settings::getPdfRendererPath()) {
    $writers['PDF'] = null;
}

// Turn output escaping on
Settings::setOutputEscapingEnabled(true);

// Return to the caller script when runs by CLI
if (CLI) {
    return;
}

// Set titles and names
//$pageHeading = str_replace('_', ' ', SCRIPT_FILENAME);
$pageHeading = "Xuất bản đề thi...";
$pageTitle = IS_INDEX ? 'Hi ' : "{$pageHeading} - ";
$pageTitle .= 'PHPWord';
$pageHeading = IS_INDEX ? '' : "<h1>{$pageHeading}</h1>";

// Populate samples
//$files = '';
//if ($handle = opendir('.')) {
//    while (false !== ($file = readdir($handle))) {
//        if (preg_match('/^Sample_\d+_/', $file)) {
//            $name = str_replace('_', ' ', preg_replace('/(Sample_|\.php)/', '', $file));
//            $files .= "<li><a href='{$file}'>{$name}</a></li>";
//        }
//    }
//    closedir($handle);
//}

/**
 * Write documents
 *
 * @param \PhpOffice\PhpWord\PhpWord $phpWord
 * @param string $filename
 * @param array $writers
 *
 * @return string
 */
function write($phpWord, $filename, $writers)
{
    $result = '';

    // Write documents
    foreach ($writers as $format => $extension) {
        $result .= date('H:i:s') . " Ghi ra định dạng {$format}";
        if (null !== $extension) {
            $targetFile = "../upload/$filename.{$extension}";
            $phpWord->save($targetFile, $format);
        } else {
            $result .= ' ... NOT DONE!';
        }
        $result .= EOL;
        break;
    }

    $result .= getEndingNotes($filename, $writers);

    return $result;
}

/**
 * Get ending notes
 *
 * @param array $writers
 *
 * @return string
 */
function getEndingNotes($filename, $writers)
{
    $result = '';

    // Do not show execution time for index
    if (!IS_INDEX) {
        $result .= date('H:i:s') . " Ghi xong! " . EOL;
        $result .= date('H:i:s') . " Bộ nhớ sử dụng: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB" . EOL;
    }

    // Return
    if (CLI) {
        $result .= 'The results are stored in the "results" subdirectory.' . EOL;
    } else {
        if (!IS_INDEX) {
            $types = array_values($writers);
            $result .= '<p>&nbsp;</p>';
            $result .= '<p>Kết quả: ';
            foreach ($types as $type) {
                if (!is_null($type)) {
                    $resultFile = "upload/$filename." . $type;
//                    if (file_exists($resultFile)) {
                        $result .= "<a href='http://localhost/www/TDUONG/luyenthi/{$resultFile}' class='btn btn-primary'>{$type}</a> ";
//                    }
                }
                break;
            }
            $result .= '</p>';
        }
    }

    return $result;
}
?>
<title><?php echo $pageTitle; ?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/font-awesome.min.css" />
<link rel="stylesheet" href="http://localhost/www/TDUONG/luyenthi/admin/assets/css/phpword.css" />
</head>
<body>
<div class="container">
<div class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./">In ấn - Xuất bản</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
<!--                <li class="dropdown active">-->
<!--                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-code fa-lg"></i>&nbsp;Samples<strong class="caret"></strong></a>-->
<!--                    <ul class="dropdown-menu">--><?php //echo $files; ?><!--</ul>-->
<!--                </li>-->
            </ul>
            <ul class="nav navbar-nav navbar-right">
<!--                <li><a href="https://github.com/PHPOffice/PHPWord"><i class="fa fa-github fa-lg" title="GitHub"></i>&nbsp;</a></li>-->
<!--                <li><a href="http://phpword.readthedocs.org/"><i class="fa fa-book fa-lg" title="Docs"></i>&nbsp;</a></li>-->
<!--                <li><a href="http://twitter.com/PHPWord"><i class="fa fa-twitter fa-lg" title="Twitter"></i>&nbsp;</a></li>-->
            </ul>
        </div>
    </div>
</div>
<?php echo $pageHeading; ?>

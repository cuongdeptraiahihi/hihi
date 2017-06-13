<?php
    if(isset($_GET["filename"])) {
        $filename=$_GET["filename"];

        $file = fopen("resources/".$filename, "rb");

        header("Content-Type: application/octet-stream");
        header("content-Disposition: attachment; filename=".$filename);

        fpassthru($file);

        fclose($file);
    }
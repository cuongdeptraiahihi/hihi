
</div>
<!-- /page container -->
<?php
    if(isset($start_count)) {
        $end_count = microtime(true) - $start_count;
        echo "<input type='hidden' value='$end_count' />";
    }
?>

</body>
</html>

<?php
    $db = new Log();
    if(isset($result0)) {
        $db->cleanResult($result0);
    }
    if(isset($result)) {
        $db->cleanResult($result);
    }
    if(isset($result1)) {
        $db->cleanResult($result1);
    }
    if(isset($result2)) {
        $db->cleanResult($result2);
    }
    if(isset($result3)) {
        $db->cleanResult($result3);
    }
    if(isset($result4)) {
        $db->cleanResult($result4);
    }
    if(isset($result5)) {
        $db->cleanResult($result5);
    }
    ob_end_flush();
//    clearstatcache();
?>
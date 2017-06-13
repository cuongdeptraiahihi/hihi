<?php
    ob_start();
    require_once("../../model/model.php");
    $ct = "";
    if(isset($_GET["formula"])) {
        $ct = $_GET["formula"];
        $db = new Cau_Hoi();
        $ct = $db->formatCT($ct);
        $ct = str_replace("1o1","'",$ct);
        $ct = str_replace("2o2","</",$ct);
        $ct = str_replace("3o3","<",$ct);
        $ct = str_replace("4o4",">",$ct);
        $ct = str_replace("5o5","+",$ct);
        $ct = str_replace("6o6","&",$ct);
        $ct = str_replace("7o7","\\",$ct);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>MathJax TeX Test Page</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML-full"></script>
</head>
<body>
    <p style="font-size: 13px;font-family: Helvetica, Arial, Tahoma;"><?php echo $ct; ?></p>
</body>
</html>
<?php
    ob_end_flush();
?>


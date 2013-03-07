<?php
    $formatted = "";
    $output = array();
    $command = "";
    if (!empty($_GET['filepath'])) {
        $result = -1;
        $highlight_lines = $_GET['lineno'];
        $command = "cat " . htmlspecialchars_decode($_GET['filepath']);
        $return_code = -1;
        $result = exec($command, $output, $return_code);
        $highlight = "";
        if (!empty($highlight_lines)) {
            $highlight = "; highlight: " . $highlight_lines;
        }
        $formatted = "<pre class=\"brush: php" . $highlight . "\">";
        foreach($output as $line) {
            $formatted .= htmlspecialchars($line) . "\n";
        }
        $formatted .= "</pre>";
    }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <title>PHP cat: <?php echo $_GET['filepath']; ?></title>
        <script type="text/javascript" src="xregexp-min.js"></script>
        <script type="text/javascript" src="shCore.js"></script>
        <script type="text/javascript" src="shBrushPhp.js"></script>
        <link href="shCore.css" rel="stylesheet" type="text/css" />
        <link href="shThemeDefault.css" rel="stylesheet" type="text/css" />
    </head>
    <body id="" onload="">
<?php if (!empty($command)) { ?>
        Output: <br>
        <?php echo $formatted; ?>
<?php } ?>
        <script type="text/javascript">
             SyntaxHighlighter.all()
        </script>
    </body>
</html>
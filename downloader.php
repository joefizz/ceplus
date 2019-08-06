<html>
<head>
<script src="jquery-3.3.1.min.js"></script>
<script src="checkall.js"></script>
</head>
<P>
<body>
<H3>CE Plus Test Files for end user device checks
</H3>
<p>

<?php
        $files = scandir("files");
        foreach ($files as $file) {
                if ($file != "." AND $file != ".."){
                        echo "<a href='files/$file'>$file<br>";
                }
        }
?>
<br>

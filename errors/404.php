<?php require_once 'header.php';?>
<body>
<?php require_once 'nav.php';?>
<div class="container">
    <h1>404 not found</h1>
<br>
<?php if (file_exists('debug')) : ?>
Error: <br><pre>
<?php
echo 'line:'. $e->getLine()."<br>";
echo 'File:'. $e->getFile() . "<br>";
echo $e->getMessage()."<br>";
echo $e->getTraceAsString();

endif;
echo '</div>';
require_once 'footer.php';?>



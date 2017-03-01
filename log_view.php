<?php

// выводим бэкаплог

$start = microtime(true);
include __DIR__ . '/views/header.php';
include __DIR__ . '/views/header_log.php';

$dbid = (integer)$_GET[dbid];
$log  = $_GET[log];
$file = './mail/' . $dbid . '/' . $log; 


if (file_exists($file)) { 
    if (filesize($file) !==0){
        $log = fopen($file, 'r');
        $view = fread($log, filesize($file));
        fclose($log);
        echo $view;        
    } else echo '<p align="center"><font color="red">лог порожній або пошкоджений</font><p>';
} else {
    echo '<p align="center"><font color="red">такого логу не існує</font><p>';
}

?>
<hr><p align="center"><input type="button" onclick="history.back();" value="Назад"/></p></pre>
<?php
include __DIR__ . '/views/footer.php';
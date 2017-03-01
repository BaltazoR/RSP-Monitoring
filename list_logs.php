<?php

// выводим список бэкаплогов согласно DBID

$start = microtime(true);
include __DIR__ . '/views/header.php';
include __DIR__ . '/views/header_log.php';
require './function/common.php';

$dbid = (integer)$_GET[dbid];

$dir    = './mail/' . $dbid;
$files = scandir($dir);
$files = array_slice($files, 3);
$files = array_reverse($files);

echo '<table border="1" cellpadding="5" align="center">';
echo '<tr align="center">';
echo '<th>№<br>п/п</>';
echo '<th>Дата</>';
echo '<th>Лог</>';
echo '</tr>';

$i = 1;

foreach ($files as $key => $value) {
    $date = explode('_', $value);

    echo '<tr align="center" '. color($date[1], 1) . '><td>'. $i . '</td><td>' . time_format($date[0]) . '</td>';
    echo '<td><a class="in_a" href="./log_view.php?dbid=' . $dbid .'&log='. $value .'&name='. urlencode($name) . '">'  . 'переглянути лог</a></td></tr>';
    ++$i;
}

echo '</table>';
?>
<hr><p align="center"><input type="button" onclick="history.back();" value="Назад"/></p></pre>
<?php
include __DIR__ . '/views/footer.php';
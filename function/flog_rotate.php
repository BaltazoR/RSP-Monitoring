<?php

// Функции для задания крона: удаление устаревших бэкаплогов

require_once 'common.php';

define('CRLF', "\r\n");

// удаляет из массива папки и файл .htaccess
function array_clean($var)
{
    if (!($var == '..' or $var == '.' or $var == '.htaccess')) return $var;
}

// возвращает список бэкаплогов, которые лежат в соответсвующей папке (согласно ID)
function get_log($dbid = '')
{
    $dir = ROOT_DIR . '/mail/' . $dbid;
    $files = scandir($dir);
    $files = array_filter($files, 'array_clean');
    return $files;
}

// пишет данные выполнения задания крона
function save_log($result)
{
    if (CREATE_LOG == 0) return;
    $save_log = fopen(ROOT_DIR . '/cron/log_rotate.log', 'a');
    $log = time_format(time()) . ' ' . $result . ' видалений' . CRLF;
    fwrite($save_log, $log);
    fclose($save_log);
}
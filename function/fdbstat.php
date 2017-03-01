<?php

// Функции для работы с сайтом донором

// сортирует массив полученных данных относительно ключа $sort_key
// по умолчанию - дата отставания данных
function dbstat_sort($array_sort, $sort_key)
{
    foreach ($array_sort as $key => $row) {
        $sort[$key] = $row[$sort_key];
    }
    array_multisort($sort, SORT_STRING, SORT_ASC, $array_sort);
    return $array_sort;
}

// получает данные с сайта донора
function dbstat_download($dbstat_request)
{
    $file = @file_get_contents('http://' . DBSTAT_USER . ':' . DBSTAT_PASS . '@' . DBSTAT . '/' . $dbstat_request . DBSTAT_URN);
    if ($file) {
        if ($file) file_put_contents(CACHE_DBSTAT . $dbstat_request, $file);
        return $file;
    } else {
        return false;
    }
}
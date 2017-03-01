<?php

require ROOT_DIR . '/function/fdbstat.php';

// получает данные с сайта донора
function Dbstat_getALL($dbstat_request, $dbstat_sort = 3)
{
    // проверяет есть ли уже у нас файл кэша и не просрочен ли он (отдаем данные с файлкэша)
    if ((!is_file(CACHE_DBSTAT . $dbstat_request)) or (time() > filemtime(CACHE_DBSTAT . $dbstat_request) + CACHE_TIME)) {
        $file = dbstat_download($dbstat_request);
        if ($file == false) {
            // если файлкэша нет, запрашиваем данные с сайта донора
            $file = @file_get_contents(CACHE_DBSTAT . $dbstat_request);
            if ($file == false) return false;
        }
    } else {
        // если файлкэш просрочен, запрашиваем данные с сайта донора
        $file = @file_get_contents(CACHE_DBSTAT . $dbstat_request);
        if ($file == false) return false;
    }

    // данные с донора приходят в json формате, декодируем
    $json = (json_decode($file, true));
    
    // дата последнего запроса в ЦБД
    $times_stat = $json[caption];
    
    // выборка по всем судам
    foreach ($json[rows] as $key => $value) {
        $courts [$value[id]] = $value[cell];
    }
    
    // сортируем массив
    $courts = dbstat_sort($courts, $dbstat_sort);

    return $result = ['times_stat' => $times_stat,
        'db_stat' => $courts,
    ];
}
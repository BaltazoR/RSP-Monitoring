<?php

// отправная точка

// засекаем время выполнения скрипта
$start = microtime(true);
define('ROOT_DIR', dirname(__FILE__));

require ROOT_DIR . '/config/conf.php';
require ROOT_DIR . '/models/dbstat.php';
require_once ROOT_DIR . '/function/common.php';

// пишем куку
if (!($_POST[region] == false)) {
    $_COOKIE[region] = cookie_set('region', $_POST[region]);
    $_COOKIE[region] = base64_encode(serialize($_POST[region]));
}

// получаем данные с сайта донора
$dbstat_request = Dbstat_getALL(DBSTAT_EXPORT, 3);

include ROOT_DIR . '/views/header.php';
include ROOT_DIR . '/views/index.php';
include ROOT_DIR . '/views/footer.php';
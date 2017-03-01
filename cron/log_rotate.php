<?php

// задание для крона: удаление устаревших логов

define('ROOT_DIR', dirname(__DIR__));

include_once ROOT_DIR . '/config/conf.php';
include_once ROOT_DIR . '/models/log_rotate.php';


log_rotate(LOG_ROTATE_DAYS);
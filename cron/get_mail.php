<?php

// задание для крона: сбор почты

define('ROOT_DIR', dirname(__DIR__));

include_once ROOT_DIR . '/config/conf.php';
include_once ROOT_DIR . '/models/backup.php';

Backup_getALL();
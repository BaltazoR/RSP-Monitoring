<?php

// адресс сайта статистики (донор)
define(DBSTAT, 'урл без http');
// логин от донора
define(DBSTAT_USER, 'логин');
// пароль от донора
define(DBSTAT_PASS, 'пароль');

// константы для парсинга донора (не менять)
define(DBSTAT_EXPORT, 'result');
define(DBSTAT_IMPORT, 'result_out');
define(DBSTAT_URN, '.php?_search=true&nd=1331894875937&rows=100&page2&sidx=dbid&sort=asc&totalrows=1000');

// папка для кэша информации с донора
define(CACHE_DBSTAT, __DIR__ . '/../cache/');
// время жизни кэша в секундах (по умолчанию 300 секунд)
define(CACHE_TIME, 300);

// адресс почтового сервера, куда собираются логи
define(BACKUP_MAIL_SERVER, 'урл или айпи');
// имя почтового ящика
define(BACKUP_LOGIN, 'логин');
// пароль от почтового ящика
define(BACKUP_PASS, 'пароль');

// количество дней, через которое логи удаляются (по умолчанию 30 дней)
define(LOG_ROTATE_DAYS, 30);



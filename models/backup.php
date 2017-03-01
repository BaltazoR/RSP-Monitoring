<?php

require ROOT_DIR . '/function/fbackup.php';

// забирает и обрабатывает почту
function Backup_getALL()
{
    //подключаемся к серверу
    $mail_stream = @fsockopen(BACKUP_MAIL_SERVER, 110, $errno, $errstr, 10);
    if (!$mail_stream) return backup_save_log('Помилка: не вдається отримати пошту (' . trim(iconv("windows-1251", "utf-8", "$errstr")) . ')');

    //читаем статус ответа, он может быть либо +OK, либо -ERR
    $mail_read = fgets($mail_stream);
    if (strpos($mail_read, '+OK') !== 0) return backup_save_log('помилка підключення');

    //авторизируемся, для этого надо отправить имя пользователя
    fwrite($mail_stream, 'USER ' . BACKUP_LOGIN . CRLF);

    //отправляем пароль
    fwrite($mail_stream, 'PASS ' . BACKUP_PASS . CRLF);

    $mail_read = fgets($mail_stream);

    // если авторизация успешна, то сервер выбросит сколько писем в ящике, иначе будет ошибка авторизации
    $mail_read = fgets($mail_stream);
    if (strpos($mail_read, '+OK') !== 0) return backup_save_log('помилка авторизації');

    // считаем количество писем
    preg_match("/\+OK\s\d*/", $mail_read, $out);
    $mail_count = $out[0];
    $mail_count = str_replace("+OK ", "", $mail_count);
    $mail_count = (int)$mail_count;

    if ($mail_count !== 0) backup_save_log('є нова пошта - ' . $mail_count . ' шт.' . CRLF . '*************************START*****************************');
    else die; //return backup_save_log ('****** нових повідомлень немає ***********');

    $i = $mail_count;

    while ($i >= 1) {
        fwrite($mail_stream, "RETR $i" . CRLF);
        //забираем письмо
        $mail_read = backup_getData($mail_stream);
        // забираем только СПД письма
        $spd = strpos($mail_read[1], 'spd');

        if ($spd === false) {
            // удаляем не СПД-шные письма
            backup_del_mail($mail_stream, $i);
            backup_save_log('видалено листа від ' . $mail_read[1]);
        } else {
            // обрабатываем СПД-шные письма

            // получаем DBID  
            preg_match("/spd\K([^@spd.ics.gov.ua]+)/", $mail_read[1], $idDB);
            $idDB = $idDB[0];
            $dir = ROOT_DIR . "/mail/$idDB";
            // создаем папку с полученным DBID, если она не существует и кладем туда файлик .htaccess, которые запрещает доступ к этой папки извне
            if (!file_exists($dir)) {
                mkdir($dir);
                $htaccess = fopen("$dir/.htaccess", 'w');
                fwrite($htaccess, '<FilesMatch "\.([Pp][Hh][Pp]|[Cc][Gg][Ii]|[Pp][Ll]|[Ph][Hh][Tt][Mm][Ll])\.?.*">' . CRLF);
                fwrite($htaccess, 'Order allow,deny' . CRLF);
                fwrite($htaccess, 'Deny from all' . CRLF);
                fwrite($htaccess, '</FilesMatch>' . CRLF);
                fclose($htaccess);
            }

            // анализируем бэкаплог, и в случае "успеха выполнения" дописываем к имени файла 1, иначе - 0
            // сохраняем бэкаплог
            if (strpos($mail_read[2], 'Завдання виконано успішно') !== false) {
                $mail_name = $mail_read[0] . '_' . '1';
                $file = $dir . '/' . $mail_name;
                backup_savefile($file, $mail_read[2], $mail_read[3]);
                backup_del_mail($mail_stream, $i);
            } else {
                $mail_name = $mail_read[0] . '_' . '0';
                $file = $dir . '/' . $mail_name;
                backup_savefile($file, $mail_read[2], $mail_read[3]);
                backup_del_mail($mail_stream, $i);
            }
            backup_save_log('лист від ' . $mail_read[1] . ' оброблено');
        }
        --$i;
    }

    // отключаемся от почтового сервера
    fputs($mail_stream, 'QUIT' . CRLF);

    // пишем результат выполнения в лог
    $result = 'пошта оброблена успішно ' . $mail_count . ' шт.' . CRLF . '**************************END******************************';
    return backup_save_log($result);
}
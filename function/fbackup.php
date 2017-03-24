<?php

// Ф-и для работы со сбором логов Бэкапа (бэкаплог)

require_once 'common.php';

// константа "перевода" строки
define('CRLF', "\r\n");

// читает письмо и разбирает его на шапку и тело
function backup_getData($fp)
{
    // тело письма
    $msg = '';
    // шапка письма
    $head = '';

    // в цикле отделяем шапку письма от тела
    while (!feof($fp)) {
        $line = (fgets($fp));
        if (($line) == '.' . CRLF) break;

        if ($line == CRLF && !$msg) $msg = ' ';

        if (!$msg) $head .= $line;
        else $msg .= $line;
    }

    $msg = iconv("windows-1251", "utf-8", trim($msg));

    // перекодируем с quoted-printable в utf-8
    $head = quoted_printable_decode(preg_replace("/_/", " ", $head));
    $head = preg_replace("/=\?windows-1251\?Q\?/", "", $head);
    $head = preg_replace("/\?\s/", "", $head);
    $head = preg_replace("/\?=/", "", $head);
    $head = preg_replace("/\?Old/", CRLF . 'Old', $head);
    $head = iconv("windows-1251", "utf-8", $head);

    // получаем тему письма
    preg_match("/Subject: \K([^\n]+)/", $head, $tema);
    $tema = $tema[0];
    $clean = trim(mb_substr($tema, -2));
    if ($clean == '>') {
        $tema = explode('?', $tema);
        $tema = $tema[0];
    }

    // получаем дату и время письма
    preg_match("/Date: \K([^\n]+)/", $head, $date);
    $date = strtotime("{$date[0]}");

    // получаем адресс, от кого письмо
    preg_match("/Return-Path: <\K([^>\n]+)/", $head, $from);
    $from = $from[0];

    return [$date, $from, $msg, trim($tema)];
}

// сохраняет бэкаплог (обработанное письмо) на диск
function backup_savefile($file, $msg, $tema)
{
    if (!file_exists($file)) {
        $mail_save = fopen($file, 'w');
        fwrite($mail_save, $tema . CRLF);
        fwrite($mail_save, $msg);
        fclose($mail_save);
    }
}

// возвращает список папок(ID), в которых лежат бэкаплоги
function backup_ListFromMailDbid()
{
    $dir = './mail/';
    $files = scandir($dir);
    $files = array_slice($files, 3);
    return $files;
}

// возвращает разницу в днях между нынешней датой и датой создания бэкаплога(та что в письме)
function backup_diffdate($date)
{
    $date = new DateTime(time_format($date));
    $now = new DateTime();
    return $diff = $date->diff($now)->format("%a");
}

// пишет данные выполнения задания крона
function backup_save_log($result)
{
    if (CREATE_LOG == 0) return;
    $save_log = fopen(ROOT_DIR . '/cron/backup.log', 'a');
    $log = time_format(time()) . ' ' . $result . CRLF;
    fwrite($save_log, $log);
    fclose($save_log);
}

// удаляет письмо
function backup_del_mail($mail_stream, $i)
{
    if (DEL_MAIL == 0) return;
    fwrite($mail_stream, 'DELE ' . $i . CRLF);
}

// читает и возвращает данные с бэкаплога
function backup_read_log($file)
{
    $log = fopen($file, 'r');
    $view = @fread($log, filesize($file));
    fclose($log);
    return $view;
}

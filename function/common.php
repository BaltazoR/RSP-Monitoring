<?php

// Универсальные функции 


// форматирует дату согласно шаблону 'd.m.Y H:i'
function time_format($sorce)
{
    return $newformat = date('d.m.Y H:i', $sorce);
}

// красит ячейку таблицы, в зависимости от результата
function color($value, $backup = 0)
{
    if ($backup == 1) {
        if ($value == $backup) {
            $color = 'bgcolor="grean"';
        } else {
            if ($value == 3) {
                $color = 'bgcolor="yellow"';
            } else {
                $color = 'bgcolor="red"';
            }
        }
        return $color;
    } else {
        if ($value == $backup) {
            $color = 'bgcolor="grean"';
        } else {
            $color = 'bgcolor="red"';
        }
        return $color;
    }
}

// устанавливает куку на срок до 2038 года
function cookie_set($name, $cookie)
{
    $cookie = base64_encode(serialize($cookie));
    setcookie($name, $cookie, time() + (10 * 365 * 24 * 60 * 60));

}

// читает куку
function cookie_read($name)
{
    return $name = unserialize(base64_decode($_COOKIE[$name]));
}
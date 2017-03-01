<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Статистика</title>
    <style type="text/css">
        body {
            background-color: #F0F0F0; /* Цвет фона веб-страницы */
        }

        .in_a {
            color: black; /* Цвет текста */
        }

        .in_a:hover {
            color: blue;
        }

        .tooltip {
            border-bottom: 1px dotted #000000;
            color: #000000;
            outline: none;
            color: black;
            position: relative;
        }

        .tooltip span {
            margin-left: -999em;
            position: absolute;
        }

        .tooltip:hover span {
            border-radius: 5px 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.1);
            -webkit-box-shadow: 5px 5px rgba(0, 0, 0, 0.1);
            -moz-box-shadow: 5px 5px rgba(0, 0, 0, 0.1);
            font-family: Calibri, Tahoma, Geneva, sans-serif;
            position: absolute;
            left: 1em;
            top: 2em;
            z-index: 99;
            margin-left: -100px;
            width: 250px;
        }

        .classic {
            padding: 0.8em 1em;
        }

        * html a:hover {
            background: transparent;
        }

        .classic {
            background: #FFFFAA;
            border: 1px solid #272822;
        }
    </style>
</head>
<body>

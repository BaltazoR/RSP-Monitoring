<form action="<?php __FILE__ ?>" method="post">
    <p align="center"><input type="submit" value="Оновити сторінку"></p>
    <table border="1" cellpadding="5" align="center">
        <tr align="center">
            <td colspan="10">
                <?php
                // выводим в select-е список областей и выбираем из куки
                include_once ROOT_DIR . '/config/regions.php';
                $region = cookie_read('region');
                echo '<select name="region">';
                foreach ($regions as $key => $value) {
                    if ($value == $region) $selected = 'selected ';
                    echo '<option ' . $selected . 'value="' . $value . '">' . $value . '</option>' . "\r\n";
                    unset($selected);
                }
                echo '</select>';
                ?>
                <input type="submit" value="Вибрати">
            </td>
        </tr>
        <tr align="center">
            <th colspan="7" align="center">Експорт станом на <?php echo $dbstat_request['times_stat'] ?></th>
            <th colspan="3">Резервування БД<br><?php echo $backup_request ?></th>
        </tr>

        <tr align="center">
            <th>№<br>п/п</th>
            <th>DBid</th>
            <th>Назва суду</th>
            <th colspan="2">Відставання даних</th>
            <th colspan="2">Відставання реплікацій</th>
            <th>Відставання по днях</th>
            <th>Лог резервування БД</th>
            <th>Всі логи</th>
        </tr>
        <?php
        // выбираем, группируем и выводим данные
        require_once ROOT_DIR . '/function/fbackup.php';
        if ($dbstat_request == 0) {
            echo '<tr>';
            echo '<td colspan="10" align="center">немає даних для відображення, зверніться до розробника ПО</td>';
            echo '</tr>';
            echo '</table>';
            include ROOT_DIR . '/views/footer.php';
            die;
        }

        $i = 0;
        
        foreach ($dbstat_request['db_stat'] as $key => $value) {
            if (!(is_null($region))) {
                if ($region == false) $region = 'Всі регіони';
                if (!($region == 'Всі регіони')) {
                    if (!($value[2] == $region)) continue;
                }
            }

            echo '<tr>';
            echo '<th align="center">' . ++$i . "</th>";
            echo '<td align="center">' . $value[0] . "</td>";
            echo "<td>" . $value[1] . "</td>";
            echo '<td align="center" ' . color($value[5]) . '>' . $value[5] . "</td>";
            echo '<td align="center">' . time_format(strtotime("{$value[3]}")) . "</td>";
            echo '<td align="center" ' . color($value[6]) . '>' . $value[6] . "</td>";
            echo '<td align="center">' . time_format(strtotime("{$value[4]}")) . "</td>";


            if (in_array($value[0], backup_ListFromMailDbid())) {
                $dir = "./mail/{$value[0]}";
                $files = scandir($dir, SCANDIR_SORT_DESCENDING);
                $file = $files[0];

                $backup_title = backup_read_log($dir . '/' . $file);
                $backup_title = explode(CRLF, $backup_title);
                $backup_title = $backup_title[0];
                $backup_title = str_replace('Звіт про резервування: ', '', $backup_title);
                $backup_title = str_replace(' Завдання виконано із помилками.', '', $backup_title);


                $files = explode('_', $file);
                $diffdate = backup_diffdate($files[0]);

                echo '<td align="center" ' . color($diffdate) . '>' . $diffdate . '</td>';
                echo '<td align="center" ' . color($files[1], 1) . '><a class="tooltip" href="./log_view.php?dbid=' . $value[0] . '&log=' . $file . '&name=' . urlencode($value[1]) . '">' . time_format($files[0]) . '<span class="classic">' . $backup_title . '</span></a></td>';
                echo '<td align="center"><a class="in_a" href="./list_logs.php?dbid=' . $value[0] . '&name=' . urlencode($value[1]) . '"> Переглянути </a></td>';

            } else {
                echo '<td align="center" bgcolor="grey" colspan="3">Дані відсутні</td>';
            }


        }
        ?>

    </table>
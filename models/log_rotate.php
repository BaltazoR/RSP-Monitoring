<?php

require ROOT_DIR . '/function/flog_rotate.php';
require ROOT_DIR . '/function/fbackup.php';

// удаляет устаревшие бэкаплоги
function log_rotate($days)
{
    $all_log = get_log();
    foreach ($all_log as $key => $dbid) {
        $list = get_log($dbid);
        foreach ($list as $key => $log) {
            $res = substr($log, 0, -2);
            if (backup_diffdate($res) >= $days) {
                $del_file = ROOT_DIR . '/mail/' . $dbid . '/' . $log;
                if (unlink($del_file)) save_log($dbid . ' => ' . $log);

            }
        }
    }
}
<?php
function getTeamFromTid($p){
    if (!isset($p['tid'])){
        trigger_error("No tid for getTeamFromTid", E_USER_ERROR);
    }
    $sql = 'SELECT *  FROM teams WHERE tid = :tid
         LIMIT 1';
    $data= array(
        'tid' => $p['tid']
    );
    $team = sqlReturnObjectOne($sql, $data);
    $out['content'] = $team;
    return $out;
}
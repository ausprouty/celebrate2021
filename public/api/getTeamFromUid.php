<?php
function getTeamFromUid($p){
    if (!isset($p['uid'])){
        trigger_error("No uid for getTeamFromUid", E_USER_ERROR);
    }
    $sql = 'SELECT tid FROM members WHERE uid = :uid
      ORDER BY mid LIMIT 1';
    $data= array(
        'uid' => $p['uid']
    );
    $team = sqlReturnObjectOne($sql, $data);
    $sql = 'SELECT *  FROM teams WHERE tid = :tid
         LIMIT 1';
    $data= array(
        'tid' => $team->tid
    );
    $team = sqlReturnObjectOne($sql, $data);

    $out['content'] = $team;
    return $out;
}
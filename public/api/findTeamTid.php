<?php
function findTeamTid($p){
    // is this a valid team key
    $sql = "SELECT tid FROM teams WHERE teamkey = :teamkey LIMIT 1";
    $data = array(
        'teamkey' => $p['teamkey']
    );
    $team = sqlReturnObjectOne($sql, $data);
    if ($team->tid){
        return $team->tid;
    }
    return null;

}
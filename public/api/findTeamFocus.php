<?php
function findTeamFocus($p){
    if (is_array($p)){
      $tid=$p['tid'];
    }
    else{
     $tid=$p;
    }
    // is this a valid team key
    $sql = "SELECT focus FROM teams WHERE tid = :tid LIMIT 1";
    $data = array(
        'tid' =>$tid
    );
    $team = sqlReturnObjectOne($sql, $data);
    if ($team->focus){
        return $team->focus;
    }
    return null;

}
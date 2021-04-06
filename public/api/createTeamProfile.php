<?php
function createTeamProfile($p){
    $out = array();
    $out['debug'] = 'createTeamProfile' ."\n";
    $sql = "INSERT INTO teams (strategy, focus, state, name) 
        VALUES (:strategy, :focus, :state, :name)";
    $data = array(
        'strategy' =>  $p['strategy'], 
        'focus' => $p['focus'], 
        'state' => $p['state'],
        'name' => $p['name'],
    );
    sqlSafe($sql, $data);
    $sql = "SELECT * FROM teams  
        WHERE strategy= :strategy 
        AND focus = :focus
        AND state= :state
        AND name = :name
        ORDER BY tid DESC
        LIMIT 1 ";
     $team = sqlReturnObjectOne($sql, $data);
     $out['content'] = $team;
    return $out;
}

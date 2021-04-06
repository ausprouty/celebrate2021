<?php
function updateTeamProfile($p){
    $out = array();
    $out['debug'] = 'updateTeamProfile' ."\n";
    $sql = "UPDATE teams SET  strategy= :strategy, focus = :focus, state= :state, name = :name
    WHERE tid = :tid LIMIT 1";
    $data = array(
        'strategy' =>  $p['strategy'], 
        'focus' => $p['focus'], 
        'state' => $p['state'],
        'name' => $p['name'],
        'tid'=>  $p['tid']
    );
    sqlSafe($sql, $data);
    return $out;
}

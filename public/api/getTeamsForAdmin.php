<?php


function getTeamsForAdmin($params){
    $out = [];
    $out['debug'] = "in get GetTeamsForAdmin\n";

    $sql = 'SELECT *
           FROM  teams
           ORDER BY name';
    $data= array();
    $teams = sqlReturnArrayMany($sql, $data);

    $out['content'] = $teams;
    return $out;
}
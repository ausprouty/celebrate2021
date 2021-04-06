<?php


function getTeams($params){
	
    $sql = 'SELECT * FROM teams ORDER BY name ';
    $data = [];
    $teams = sqlReturnObjectMany($sql, $data);
    $out['content'] = $teams;
    return $out;
}
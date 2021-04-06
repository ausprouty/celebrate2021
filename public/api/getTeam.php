<?php

require_once ('getImageTeam.php');

function getTeam($params){
	if (!isset($params['tid'])){
        if (isset($params['route'])){
            $route = json_decode($params['route']);
            if (isset($route->tid)){
                $tid = $route->tid;
            }
            else{
                $out['debug'] = 'tid not set in getTeam';
                return $out;
            }
        }
        else{
            $out['debug'] = 'tid not set in getTeam';
		    return $out;
        }
    }
    else{
        $tid = $params['tid'];
    }
    $sql = 'SELECT * FROM teams WHERE tid = :tid LIMIT 1';
    $data= array(
        'tid' => $tid
    );
    $team = sqlReturnObjectOne($sql, $data);
    $res = getImageTeam($team);
    if ($res['content']){
        $team->image = $res['content'];
    }
    $out['content'] = $team;
    return $out;
}
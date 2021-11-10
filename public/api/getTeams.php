<?php


function getTeams($params){
    $out = [];
    $out['debug'] = "in get Teams\n";
	if (!isset($params['my_uid'])){
        if (isset($params['route'])){
            $route = json_decode($params['route']);
            if (isset($route->uid)){
                $uid = $route->uid;
            }
            else{
                $out['debug'] = 'uid not set in getTeams';
                return $out;
            }
        }
        else{
            $out['debug'] = 'my_uid not set in getTeams';
		    return $out;
        }
    }
    else{
        $uid = $params['my_uid'];
    }
    $sql = 'SELECT t.tid, t.name FROM members AS m
           INNER JOIN teams AS t
            ON  m.tid = t.tid
            WHERE m.uid = :uid';
    $out['debug'] .=$sql . "\n";

    $data= array(
        'uid' => $uid
    );
    $teams = sqlReturnArrayMany($sql, $data);

    $out['content'] = $teams;
    return $out;
}
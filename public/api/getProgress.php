<?php

function getProgress($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in getProgress' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
    $route = json_decode($params['route']);
	$sql = 'SELECT * FROM progress WHERE uid = :uid  AND year = :year ORDER BY item, month';
	$data = [   
      'uid'=> $route->uid,
      'year'=> $route->year
	];
	$out['content'] = sqlReturnObjectMany($sql, $data);
    return $out;
}
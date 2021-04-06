<?php

function getDisciples($params){
	$out['debug'] = [];
	if (!isset($params['route'])){
		 $out['debug'] = 'Route not set in getMembers';
	}
   //leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$route = json_decode($params['route']) ;
	$sql = 'SELECT * FROM disciples WHERE 
		uid = :uid AND
		tid = :tid 
		ORDER BY firstname';
	$data= array(
		'uid'=> $route->uid,
		'tid'=> $route->tid
	);
    $disciples = sqlReturnObjectMany($sql, $data);
    $out['content'] = $disciples;
    return $out;
}
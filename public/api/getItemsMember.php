<?php

function getItemsMember($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in getItemsMember' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$route = json_decode($params['route']);
	$sql = "SELECT * FROM items WHERE	
	       celebration_set = 'cru' OR
		   tid = :tid OR
		   uid = :uid
		   ORDER BY id";
	$data = array(
		'tid' => $route->tid,
		'uid' => $route->uid,
	);
	$result = sqlReturnObjectMany($sql, $data);

	$out['content'] = json_encode($result);
    return $out;
}
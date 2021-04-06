<?php

function getItem($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['id'])){
		 $out['debug'] .= 'id not set in getItem' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$sql = 'SELECT * FROM items WHERE id = :id LIMIT 1';
	$data = [   
	  'id'=> $params['id']
	];

	$out['content'] = sqlReturnObjectOne($sql, $data);
    return $out;
}
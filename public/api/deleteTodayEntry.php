<?php

function deleteTodayEntry($params){
	$out['debug'] = null;

	if (!isset($params['id'])){
		 $out['debug'] .= 'id not set in deleteTodayEntry' . "\n";
	}
	
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$sql = 'DELETE FROM today WHERE todayid = :id LIMIT 1';
	$data = [   
	  'id'=> $params['id']
	];
	$out = sqlSafe($sql, $data);
    return $out;
}
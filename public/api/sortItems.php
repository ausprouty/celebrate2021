<?php

function sortItems($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['items'])){
		 $out['debug'] .= 'items  not set in sortItems' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	//
	$out['debug'] = $params['items'] . "\n";
	$items = json_decode($params['items']);
	$sequence = 1;
	foreach ($items as $item){
		$out['debug'] .= $item->id . "\n";
		$sql = "UPDATE items set sequence = :sequence	
		  WHERE id = :id 
		  LIMIT 1";
		$data = array(
			'sequence' => $sequence,
			'id'=> $item->id
		);
		$result = sqlSafe($sql, $data);
		$sequence++;
	}
	
    return $out;
}
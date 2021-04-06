<?php

function getItemsStandard($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in getItemsTeam' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$route = json_decode($params['route']);
	$sql = "SELECT * FROM items WHERE	
	       celebration_set = :celebration_set
		   ORDER BY id";
	$data = array(
		'celebration_set' => 'cru',
	);
	$result = sqlReturnObjectMany($sql, $data);

	$out['content'] = json_encode($result);
    return $out;
}
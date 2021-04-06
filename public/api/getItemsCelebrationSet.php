<?php

function getItemsCelebrationSet($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['celebration_set'])){
		 $out['debug'] .= 'celebration_set not set in getItemsCelebrationSet' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$sql = "SELECT * FROM items WHERE	
	       celebration_set = :celebration_set
		   ORDER BY sequence";
	$data = array(
		'celebration_set' => $params['celebration_set']
	);
	$result = sqlReturnObjectMany($sql, $data);

	$out['content'] = json_encode($result);
    return $out;
}
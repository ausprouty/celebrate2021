<?php

function getStandardItems($params){
	
	$items = [];
	$sql = 'SELECT * FROM items  WHERE celebration_set = :set  ORDER BY sequence';
	$data = array(
		$set => 'cru'
	);
    $result = sqlReturnObjectMany($sql, $data);
	foreach ($result as $item){
		$items[] = $item;
	}
    $out['content'] = $items;
    return $out;
}
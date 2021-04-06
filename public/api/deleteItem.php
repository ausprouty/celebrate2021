<?php

function deleteItem($params){
	$out['debug'] = null;
	$item = json_decode($params['item']) ;
	if (!isset($item->id)){
		 $out['debug'] .= 'id not set in deleteItem' . "\n";
	}
	
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$out['debug'] = 'These are my parameters' ."\n";
	foreach (get_object_vars($item) as $var => $val) {
		$out['debug']  .= $var . '->'. $val . "\n";
	}
	$sql = 'DELETE FROM items WHERE id = :id LIMIT 1';
	$data = [   
	  'id'=> $item->id
	];
	$pdo = new PDO (DSN, USER, PASS);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $pdo->prepare($sql);
	$stmt->execute($data);
	$count = $stmt->rowCount();		
    $out['debug'] = 'I finshed delete with count '. $count;
	//$out = sqlSafe($sql, $data);
    return $out;
}
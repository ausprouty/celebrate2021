<?php

function getSettingsToday($params){
	// check to make sure we have all the params we need
	$out['debug'] = null;
	$settings = [];
	if (!isset($params['route'])){
		$out['debug'] .= 'Route not set in getSettingsToday' . "\n";
	   }
	// decode
	$route = json_decode($params['route']);
	if (!isset($route->tid)){
		 $out['debug'] .= 'tid not set in getSettingsToday'. "\n";
	}
	if (!isset($route->uid)){
		 $out['debug'] .= 'uid not set in getSettingsToday'. "\n";
	}
	if ($out['debug'] != null){
		return $out;
	}
	// get all items for this person/team
	$sql = "SELECT * FROM items WHERE	
	       celebration_set = 'cru' OR
		   (tid = :tid AND uid IS NULL)
		   OR  uid = :uid
		   ORDER BY id";
	$data = array(
		'tid' => $route->tid,
		'uid' => $route->uid,
	);
	$result = sqlReturnObjectMany($sql, $data);
	if ($result){
		foreach ($result as $item){
			// does this require quick action?
			$sql = "SELECT qid FROM quick 
				WHERE uid = :uid AND
				tid = :tid AND
				item = :id ";
			$data = array(
				'uid' => $route->uid,
				'tid' => $route->tid,
				'id' => $item->id,
			);
			$response = sqlReturnObjectOne($sql, $data);
			$item->quick = isset($response->qid)? true : false;
			// is there a goal for this item?
			$sql = "SELECT numbers, text FROM goals 
				WHERE uid = :uid AND
				tid = :tid AND
				id = :id  AND
				year = :year";
			$data = array(
				'uid' => $route->uid,
				'tid' => $route->tid,
				'id' => $item->id,
				'year' =>$route->year
			);
			$response = sqlReturnObjectOne($sql, $data);
			$item->number = isset($response->numbers)? $response->numbers : null;
			// set value
			$settings[] = $item;
		}
	}
    $out['content'] = $settings;
    return $out;
}
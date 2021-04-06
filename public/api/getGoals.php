<?php

function getGoals($params){
	// check to make sure we have all the params we need
	$out['debug'] = null;
	$goals = [];
	if (!isset($params['route'])){
		$out['debug'] .= 'Route not set in getGoals' . "\n";
	   }
	// decode
	$route = json_decode ($params['route']);
	if (!isset($route->year)){
		$route->year = date("Y");
	}
	if (!isset($route->tid)){
		 $out['debug'] .= 'tid not set in getGoals'. "\n";
	}
	
	if ($out['debug'] != null){
		return $out;
	}
	if (isset($route->uid)){
		$out  = getGoalsIndividual($route);
	}
	else{
		$out  = getGoalsTeam($route);
	}
	return $out;
}
function getGoalsIndividual($route){
	// get all items for this person/team
	$sql = "SELECT * FROM items WHERE	
	       celebration_set = 'cru' OR
		   (tid = :tid AND uid IS NULL) OR
		   uid = :uid
		   ORDER BY id";
	$data = array(
		'tid' => $route->tid,
		'uid' => $route->uid,
	);
	$result = sqlReturnObjectMany($sql, $data);
	if ($result){
		// get goals for this year
		foreach ($result as $item){
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
			$item->text = isset($response->text)? $response->text : null;
			$goals[] = $item;
		}
	}
	$out['content'] = $goals;
	return $out;
}
function getGoalsTeam($route){
	// get all items for this person/team
	$sql = "SELECT * FROM items WHERE	
	       celebration_set = 'cru' OR
		   (tid = :tid AND uid IS NULL)
		   ORDER BY id";
	$data = array(
		'tid' => $route->tid,
	);
	$result = sqlReturnObjectMany($sql, $data);
	if ($result){
		// get goals for this year
		foreach ($result as $item){
			$sql = "SELECT numbers, text FROM goals 
				WHERE uid IS NULL AND
				tid = :tid AND
				id = :id  AND
				year = :year";
			$data = array(
				'tid' => $route->tid,
				'id' => $item->id,
				'year' =>$route->year
			);
			$response = sqlReturnObjectOne($sql, $data);
			$item->number = isset($response->numbers)? $response->numbers : null;
			$item->text = isset($response->text)? $response->text : null;
			$goals[] = $item;
		}
	}
	$out['content'] = $goals;
	return $out;
}
<?php
function getGoalsPage($params){
	// check to make sure we have all the params we need
	$out['debug'] = null;
	$goals = [];
	if (!isset($params['route'])){
		$out['debug'] .= 'Route not set in getGoalsPage' . "\n";
	   }
	// decode
	$route = json_decode ($params['route']);
	if (!isset($route->year)){
		 $out['debug'] .= 'Year not set in getGoalsPage' . "\n";
	}
	if (!isset($route->tid)){
		 $out['debug'] .= 'tid not set in getGoalsPage'. "\n";
	}
	if (!isset($route->uid)){
		 $out['debug'] .= 'uid not set in getGoalsPage'. "\n";
    }
    if (!isset($route->page)){
        $out['debug'] .= 'Page not set in getGoalsPage'. "\n";
   }
	if ($out['debug'] != null){
		return $out;
	}
	// get all items for this person/team
	$sql = "SELECT * FROM items WHERE	
	       (celebration_set = 'cru' OR
		   tid = :tid OR
		   uid = :uid)
           AND page = :page
		   ORDER BY id";
	$data = array(
		'tid' => $route->tid,
        'uid' => $route->uid,
        'page'=> $route->page
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
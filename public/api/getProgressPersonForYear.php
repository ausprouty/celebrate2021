
<?php

function getProgressPersonForYear($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in  getProgressPersonForYear' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
	$route = json_decode($params['route']);
	if (!isset($route->year)){
		$route->year = date("Y");
	}
	$last_year = $route->year -1;
	// get item information
	$sql = 'SELECT * FROM items WHERE  id = :item LIMIT 1';
	$data = [   
		'item'=> isset( $route->item)  ? $route->item : 1
	];
	$item = sqlReturnObjectMany($sql, $data);
	// get goal
	$sql = 'SELECT numbers FROM goals WHERE 
		id = :item AND
		uid = :uid AND
		tid = :tid 
		LIMIT 1';
	$data = [   
		'item'=> isset( $route->item)  ? $route->item : 1,
		'uid' =>  $route->uid,
		'tid' => $route->tid,
	];
	$goal = sqlReturnObjectOne($sql, $data);
	
	//get progress data
	$sql = 'SELECT p.month, p.year, p.entry  FROM progress AS p
        WHERE p.uid = :uid 
		AND p.tid = :tid  
		AND (p.year = :year  OR p.year = :last_year)
        AND p.item = :item';
	$out['debug'] .= $sql ."\n";
	$data = [   
	  'uid'=> $route->uid,
	  'tid'=> $route->tid,
	  'year'=> $route->year,
	  'last_year'=> $last_year,
      'item'=> isset( $route->item)  ? $route->item : 1
	];
	$records = sqlReturnObjectMany($sql, $data);
	
	//arrange into array for last year and this year
	$prev_year = [];
	for ($i = 0; $i < 12; $i++){
		$prev_year[$i] = 0;
	}
	$month = date("n");
	$this_year = [];
	for ($i = 0; $i < $month; $i++){
		$this_year[$i] = 0;

	}
	$total = 0;
	foreach ($records as $record){
		$out['debug'] .=$record->year . '-' . $record->month . '-' .$record->entry ."\n";
		if ($record->year == $last_year){
			$prev_year [$record->month -1] = $prev_year [$record->month -1] + $record->entry;
		}
		else{
			$this_year [$record->month -1] = $this_year [$record->month -1] + $record->entry;
			$total = $total + $record->entry;
		}

	}
	$out['content']['item'] = json_encode($item);
	$out['content']['goal'] = isset($goal->numbers) ?$goal->numbers:0 ;
	$out['content']['last_year'] = $last_year;
	$out['content']['this_year'] = $route->year;
	$out['content']['this_year_total'] = isset($total)?$total : 0 ;
	$out['content']['progress'] = array();
	$out['content']['progress'][] = $prev_year;
	$out['content']['progress'][] = $this_year; 
    return $out;
}
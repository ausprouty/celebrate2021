<?php

function getPrayersTeam($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in getProgress' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
	$route = json_decode($params['route']);
	$month = $route->month -1;
	if ($month > 0){
		
		$sql = 'SELECT p.pid, p.uid, p.prayer, p.month, p.year, u.firstname, u.lastname, u.image 
			FROM progress AS p
		    LEFT JOIN users AS u
			on p.uid = u.uid
			WHERE tid = :tid  
			AND prayer IS NOT NULL
			AND prayer != :blank
			AND year = :year 
			AND month > :month
			ORDER BY pid DESC';
		$data = [   
			'tid'=> $route->tid,
			'blank'=> '',
			'year'=> $route->year,
			'month'=> $month
		];
	}
	else{
		$last_year = $route->year -1;
		$month = $month + 12;
		$sql = 'SELECT p.pid, p.uid, p.prayer, p.month, p.year, u.firstname, u.lastname, u.image 
			FROM progress AS p
		    LEFT JOIN users AS u
			on p.uid = u.uid
			WHERE tid = :tid  
			AND prayer IS NOT NULL
			AND prayer != :blank
			AND year = :this_year 
			OR (year = :last_year AND month > :month) ORDER BY pid DESC';
		$data = [   
			'tid'=> $route->tid,
			'blank'=> '',
			'this_year'=> $route->year,
			'last_year' => $last_year,
			'month'=> $month
		];
	}
	$out['content'] = sqlReturnObjectMany($sql, $data);
    return $out;
}
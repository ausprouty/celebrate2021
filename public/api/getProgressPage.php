
<?php

function getProgressPage($params){
	$out = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in getProgressPage' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
    $route = json_decode($params['route']);
	$sql = 'SELECT * FROM progress AS p
        INNER JOIN items AS i
		ON  p.item = i.id
        WHERE p.uid = :uid  AND p.year = :year 
        AND i.page = :page
        ORDER BY item, month';
	$data = [   
      'uid'=> $route->uid,
      'year'=> $route->year,
      'page'=> isset($route->page)  ? $route->page : 0
	];
	$out['content'] = sqlReturnObjectMany($sql, $data);
    return $out;
}
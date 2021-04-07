<?php


function getProgressPageEntry($params){
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
	$sql = 'SELECT * FROM items AS i
       LEFT JOIN progress AS p
        ON i.id = p.item 
        WHERE `page` = :page
        AND (
            i.uid = :uid2
            OR i.tid = :tid
            OR i.celebration_set = :set
        )
        AND p.uid = :uid  
        AND `month` =:month
        AND `year` = :year 
        ORDER BY `item`';
	$data = [   
      'page'=> $route->page,
      'uid2'=> $route->uid,
      'tid'=> $route->tid,
      'set'=> 'cru',
      'uid'=> $route->uid,
      'month'=> $route->month,
      'year'=> $route->year
    ];
    //foreach ($data as $key=> $value){
    //    $out['debug'] .= $key . ' => '. $value . "\n";
    //}
	$out['content'] = sqlReturnObjectMany($sql, $data);
    return $out;
}

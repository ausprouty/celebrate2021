
<?php

function getProgressToday($params){
    $out = [];
    $out['content'] = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in  getProgressToday' . "\n";
	}
	
    $route = json_decode($params['route']);
    if (!isset($route->uid) || !isset($route->tid)){
        $out['debug'] .= 'uid or tid not set in  getProgressToday' . "\n";
    }
    //leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
    // find items for quick entry
	$sql = 'SELECT * FROM items AS i
         INNER JOIN quick AS q
         ON  i.id = q.item 
        WHERE  (
            q.uid = :uid
            AND q.tid = :tid
        )
        ORDER BY sequence';
	$data = [   
      'uid'=> $route->uid,
      'tid'=> $route->tid,
    ];
    $items = sqlReturnObjectMany($sql, $data);
    foreach ($items as $item){
        $out['debug'] .= 'I have an item: ' . $item->id .  "\n";
        $days = getToday($route, $item);
        $entered = [];
        foreach ($days as $day){
            $out['debug'] .= 'I have an day: ' .$day->todayid ."\n";
            $day->when = date('D m-d',$day->time);
            $entered[] = $day;
        }
        $item->entered = $entered;
        $out['content'][] = $item; 
    }
    return $out;
}

function getToday($route, $item){
        $sql = 'SELECT * FROM today
        WHERE `uid` = :uid 
        AND `tid` = :tid 
        AND `item` = :item
        AND `month`= :month
        AND `year` = :year
        ORDER BY time DESC LIMIT 5'
        ;
    $data = [   
        'uid'=> $route->uid,
        'tid'=> $route->tid,
        'item' => $item->id,
        'month' => $route->month,
        'year'=> $route->year,
        
    ];
    $today = sqlReturnObjectMany($sql, $data);
    return ($today);
}

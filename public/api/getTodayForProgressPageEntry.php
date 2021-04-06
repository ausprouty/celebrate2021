
<?php

function getTodayForProgressPageEntry($params){
    $out = [];
    $recorded = [];
    $out['content'] = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in  getTodayForProgressPageEntry' . "\n";
    }
    if (!isset($params['items'])){
        $out['debug'] .= 'route not set in  getTodayForProgressPageEntry' . "\n";
   }
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
    $route = json_decode($params['route']);
    $items = json_decode($params['items']);
      // find today entries for these items 
    foreach ($items as $item){
        $sql = 'SELECT *  FROM today WHERE 
            uid = :uid AND
            tid = :tid AND
            month= :month AND
            year = :year AND
            item = :item
            ORDER BY time DESC';
        $data = [   
            'uid'=> $route->uid,
            'tid'=> $route->tid,
            'month'=> $route->month,
            'year'=> $route->year,
            'item'=> $item->id
            ];
        $result = sqlReturnObjectMany($sql, $data);
        $today = [];
        foreach ($result as $data){
            if (isset($data->time)){
                $data->when = date('D m-d', $data->time);
            }
            $today[] = $data;
        }
        $recorded[$item->id] = $today;
    }
    $out['content'] = $recorded;
    return $out;
}

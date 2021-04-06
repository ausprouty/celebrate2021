<?php
function updateProgressToday($params){
	$out['debug']  = null;
	
	if (!isset($params['items'] )){
		 $out['debug']  .= 'Items not set in updateProgressToday' . "\n";
	}
	if (!isset($params['route'] )){
		 $out['debug']  .= 'Route not set in updateProgressToday'. "\n";
	}
	//leave if any errors
	if ($out['debug']  != null){
		return $out;
    }
    // decode parameters
    $items = json_decode($params['items']);
    $route = json_decode($params['route']);
    foreach ($items as $item){
        if (isset($item->entry)){
            if ($item->entry !== null){
                $sql = 'INSERT INTO today 
                (time, uid, tid, month , year, item, entry, comment, prayer)
                VALUES (:time, :uid, :tid, :month, :year, :item, :entry, :comment, :prayer)';
                $data =  $data = [   
                    'time' => time(),
                    'uid'=> $route->uid,
                    'tid'=> $route->tid,
                    'month'=> $route->month,
                    'year'=> $route->year,
                    'item' => $item->id,
                    'entry' => $item->entry,
                    'comment'=> isset($item->comment) ? $item->comment : null,
                    'prayer'=> isset($item->prayer) ? $item->prayer : null,
                ];
                $res = sqlSafe($sql, $data);
                if (isset($res['debug'])){
                    $out['debug'] .= $res['debug'];
                }
            }
           
        }
       
    }
    return $out;
}
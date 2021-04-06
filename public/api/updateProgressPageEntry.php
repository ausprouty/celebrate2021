<?php
function updateProgressPageEntry($params){
	$out['debug']  = null;
	
	if (!isset($params['items'] )){
		 $out['debug']  .= 'Items not set in updateProgressPageEntry' . "\n";
	}
	if (!isset($params['route'] )){
		 $out['debug']  .= 'Route not set in updateProgressPageEntry'. "\n";
	}
	//leave if any errors
	if ($out['debug']  != null){
		return $out;
    }
    // decode parameters
    $items = json_decode($params['items']);
    $route = json_decode($params['route']);
    if (!isset($route->year)){
        $out['debug']  .= 'Year not set in updateProgressPageEntry'. "\n";
    }
    if (!isset($route->month)){
        $out['debug']  .= 'Month not set in updateProgressPageEntry'. "\n";
    }
    //leave if any errors
	if ($out['debug']  != null){
		return $out;
    }
    foreach ($items as $item){
        // do we already have this item?
        $sql = 'SELECT pid FROM progress WHERE
            year = :year AND
            month = :month AND
            uid = :uid AND
            tid = :tid AND
            item = :item';
        $data =  $data = [   
            'uid'=> $route->uid,
            'tid'=> $route->tid,
            'month'=> $route->month,
            'year'=> $route->year,
            'item' => $item->id
        ];
        $progress = sqlReturnObjectOne($sql, $data);
        // update
        if ($progress){
            $sql = 'UPDATE progress 
                SET entry = :entry,
                comment = :comment,
                prayer = :prayer
                WHERE
                year = :year AND
                month = :month AND
                uid = :uid AND
                tid = :tid AND
                item = :item';
            $data =  $data = [   
                'entry' => $item->entry,
                'comment'=> $item->comment,
                'prayer'=> $item->prayer,
                'uid'=> $route->uid,
                'tid'=> $route->tid,
                'month'=> $route->month,
                'year'=> $route->year,
                'item' => $item->id
            ];
            $res = sqlSafe($sql, $data);
            if (isset($res['debug'])){
                $out['debug'] .= $res['debug'];
            }

        }
        // insert
        else{
            $sql = 'INSERT INTO progress 
                (year, month ,uid,tid,item,entry,comment,prayer)
                VALUES (:year, :month ,:uid,:tid,:item,:entry,:comment,:prayer)';
            $data =  $data = [   
                'year'=> $route->year,
                'month'=> $route->month,
                'uid'=> $route->uid,
                'tid'=> $route->tid,
                'item' => $item->id,
                'entry' => $item->entry,
                'comment'=> $item->comment,
                'prayer'=> $item->prayer 
            ];
            $res = sqlSafe($sql, $data);
            if (isset($res['debug'])){
                $out['debug'] .= $res['debug'];
            }

        }
    }
}
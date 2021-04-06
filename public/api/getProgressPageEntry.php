
<?php

function getProgressPageEntry($params){
    $out = [];
    $out['content'] = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in getProgressPage' . "\n";
	}
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
    $route = json_decode($params['route']);
    // page may not be set by vuejs
    if (!isset($route->page)){
        $route->page = 0;
    }
     // uid is not set by team
     if (!isset($route->uid)){
        $route->uid = NULL;
    }
    // find items for this page
	$sql = 'SELECT * FROM items AS i
        WHERE `page` = :page
        AND (
            i.uid = :uid2
            OR (i.tid = :tid AND i.uid IS NULL)
            OR i.celebration_set = :set
        )
        ORDER BY sequence';
	$data = [   
      'page'=> $route->page,
      'uid2'=> $route->uid,
      'tid'=> $route->tid,
      'set'=> 'cru',
    ];
    $items = sqlReturnObjectMany($sql, $data);
   
    foreach ($items as $item){
         // find progress for this month
        $current_month = getCurrentMonth($route, $item);
        $item->entry = isset($current_month->entry)?$current_month->entry:0 ;
        $item->comment = isset($current_month->comment)?$current_month->comment:null ;
        $item->prayer = isset($current_month->prayer)?$current_month->prayer:null ;
        // get previous month progress
        $previous_month = getPreviousMonth($route, $item);
        $item->previous_entry = isset($previous_month->entry)?$previous_month->entry:0 ;
        $item->previous_comment = isset($previous_month->comment)?$previous_month->comment:null ;
        $item->previous_prayer = isset($previous_month->prayer)?$previous_month->prayer:null ;
        // find cumulative for this item
        $calculated = getCummulativeOrAverage($route, $item);
        $item->calculated_entry = isset($calculated->sum)?$calculated->sum:0 ;
        // find goal for this item
        $goal = getGoal($route, $item);
        $item->goal_numbers = isset($goal->numbers)?$goal->numbers:null;
        $item->goal_text = isset($goal->text)?$goal->text:null ;
        // can this be edited?
        if ($item->uid == $route->uid){
            $item->edit_link = '/user/' . $route->uid . '/' . $route->tid . '/item/' . $item->id;
        }
        else{
            $item->edit_link = null;
        }
            
        
        // update array
        $out['content'][] = $item; 
    }
    return $out;
}

function getCurrentMonth($route, $item){
        $sql = 'SELECT * FROM progress
        WHERE `uid` = :uid 
        AND `tid` = :tid 
        AND `item` = :item
        AND `month`= :month
        AND `year` = :year'
        ;
    $data = [   
        'uid'=> $route->uid,
        'tid'=> $route->tid,
        'item' => $item->id,
        'month' => $route->month,
        'year'=> $route->year,
        
    ];
    $progress = sqlReturnObjectOne($sql, $data);
    return ($progress);
}

function getGoal($route, $item){
    $sql = 'SELECT * FROM goals
            WHERE `uid` = :uid 
            AND `tid` = :tid 
            AND `year` = :year 
            AND `id` = :item';
        $data = [   
            'uid'=> $route->uid,
            'tid'=> $route->tid,
            'year'=> $route->year,
            'item' => $item->id
        ];
        $goal = sqlReturnObjectOne($sql, $data);
        return ($goal);
}
function getPreviousMonth($route, $item){
    if ($route->month >1){
        $month = $route->month - 1;
        $year = $route->year;
    }
    else{
        $month = 12;
        $year = $route->year - 1;
    }
    $sql = 'SELECT * FROM progress
        WHERE `uid` = :uid 
        AND `tid` = :tid 
        AND `item` = :item
        AND `month`= :month
        AND `year` = :year'
        ;
    $data = [   
        'uid'=> $route->uid,
        'tid'=> $route->tid,
        'item' => $item->id,
        'month' => $month,
        'year'=> $year,
    ];
    $progress = sqlReturnObjectOne($sql, $data);
    return ($progress);
}
function getCummulativeOrAverage($route, $item){
    // get rid of those that are not recorded as numbers
    if ($item->numbers != 'Y'){
        return;
    }
    $sql = 'SELECT SUM(entry) AS sum FROM progress
        WHERE `uid` = :uid 
        AND `tid` = :tid 
        AND `item` = :item
        AND `year` = :year'
        ;
    $data = [   
        'uid'=> $route->uid,
        'tid'=> $route->tid,
        'item' => $item->id,
        'year'=> $route->year,
    ];
    $sum = sqlReturnObjectOne($sql, $data);

    if ($item->cumulative != 'Y'){
        $out = ($sum->sum/ $route->month) ;
    }
    else{
        $out = $sum;
    }
    return $out;
}

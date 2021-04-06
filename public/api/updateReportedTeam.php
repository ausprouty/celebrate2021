<?php
function updateReportedTeam($params){
	$out['debug']  = null;
	if (!isset($params['route'] )){
		 $out['debug']  .= 'Route not set in updateReportedTeam'. "\n";
	}
	//leave if any errors
	if ($out['debug']  != null){
		return $out;
    }
    // decode parameters
    $route = json_decode($params['route']);
    $route->uid = null;
    // does entry exist?
    $sql = 'SELECT rid FROM reported 
        WHERE uid = :uid AND  tid = :tid 
        AND  month =:month AND year = :year 
        LIMIT 1';
    $data = [   
        'uid' => $route->uid,
        'tid'=> $route->tid,
        'month'=> $route->month,
        'year'=> $route->year,
    ];
    $res = sqlReturnObjectOne($sql, $data);
    // if not, insert
    if (!isset($res->rid)){
        $sql = 'INSERT INTO reported 
            (uid, tid, month , year)
            VALUES (:uid, :tid, :month, :year)';
        $res = sqlSafe($sql, $data);
        if (isset($res['debug'])){
            $out['debug'] .= $res['debug'];
        }
    }
    return $out;
}
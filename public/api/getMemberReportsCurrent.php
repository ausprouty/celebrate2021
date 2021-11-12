<?php

include_once('findMemberActive.php');
include_once('verifyRoute.php');
function getMemberReportsCurrent($params){
    $out['debug']  = null;
	if (!isset($params['route'] )){
		 $out['debug']  .= 'Route not set in getMemberReportsCurrent'. "\n";
	}
	//leave if any errors
	if ($out['debug']  != null){
		return $out;
    }
    // decode parameters
    $required = array('tid','uid');
	$verify = verifyRoute($params['route'], $required, 'getMemberReportsCurrent');
	if (isset($verify['debug'])){
		$out['debug'] .=$verify['debug'];
		return  $out;
	}
    $route= $verify['route'];
    if (!isset($route->year)){
        $route->year = date('Y');
    }
    if (!isset($route->month)){
        $route->month = date('n') -1;
        if ($route->month == 0){
            $route->year = $route->year -1;
            $route->month = 12;
        }
    }
       // find reports
    $sql = 'SELECT * FROM reported
        WHERE uid = :uid AND  tid = :tid
        AND year = :year';
    $data = [
        'uid' => $route->uid,
        'tid'=> $route->tid,
        'year'=> $route->year,
    ];
    // set current to null
    $current = array();
    for ($i = 1; $i<= $route->month; $i++){
        $current[$i] = 'N';
    }
    $reports = sqlReturnObjectMany($sql, $data);
    foreach ($reports as $report){
        $current[$report->month] = 'Y';
    }
    $missing = [];
    foreach ($current as $month => $value){
        if ($value == 'N'){
            if (findMemberActive($route, $month)){
                 $missing[] = $month;
            }

        }
    }
    $out['content'] = $missing;
    return $out;
}
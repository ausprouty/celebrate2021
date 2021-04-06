
<?php

function getTodayEntry($params){
    $out = [];
    $out['content'] = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in  getTodayForProgressPageEntry' . "\n";
    }
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
    }
    $route = json_decode($params['route']);
      // find today entries for these items 
    $sql = 'SELECT *  FROM today WHERE 
        todayid = :todayid';
    $data = [   
        'todayid'=> $route->todayid,
        ];
    $result = sqlReturnObjectOne($sql, $data);
   
    $out['content'] = $result;
    return $out;
}

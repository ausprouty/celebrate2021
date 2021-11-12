<?php
include_once('findTeamFocus.php');
include_once('verifyRoute.php');

function getSettingsToday($params){
	$out=[];
	writeLog('getSettingsToday-6',"Entered");
	// check to make sure we have all the params we need
	$out['debug'] = null;
	$settings = [];
	/*if (!isset($params['route'])){
		writeLog('getSettingsToday-10',"Stopped abbroptly: Route not set getSettingsToday");
		$out['debug'] .= "Route not set getSettingsToday\n";
        return $out;
	}
    writeLog('getSettingsToday-15',"Entered");
    $required = array('year', 'tid','uid');
	$verify = verifyRoute($params['route'], $required, 'getSettingsToday');
	if ($verify['debug'] != null){
		return $verify['debug'];
	}
    $route= $verify['route'];
	*/
   // $route = json_decode($params['route']);
	$focus=findTeamFocus($route->tid);

	writeLog('getSettingsToday-27',$focus);
	$route = new stdClass();
     $route->tid=1;
     $route->uid= 1;
      $route->year= 2021;

	writeLog('getSettingsToday-32',$focus);
	// get all items for this person/team
	$sql = "SELECT * FROM items WHERE
	       celebration_set = :focus  OR
		   (tid = :tid AND uid IS NULL)
		   OR  uid = :uid
		   ORDER BY id";
	$data = array(
		'focus' =>$focus,
		'tid' => $route->tid,
		'uid' => $route->uid,
	);
	$result = sqlReturnObjectMany($sql, $data);
	if ($result){
		foreach ($result as $item){
			// does this require quick action?
			writeLog('getSettingsToday-48-'. $item->name ,$item->paraphrase);
			$sql = "SELECT qid FROM quick
				WHERE uid = :uid AND
				tid = :tid AND
				item = :id ";
			$data = array(
				'uid' => $route->uid,
				'tid' => $route->tid,
				'id' => $item->id,
			);
			$response = sqlReturnObjectOne($sql, $data);
			$item->quick = isset($response->qid)? true : false;
			// is there a goal for this item?
			$sql = "SELECT numbers, text FROM goals
				WHERE uid = :uid AND
				tid = :tid AND
				id = :id  AND
				year = :year";
			$data = array(
				'uid' => $route->uid,
				'tid' => $route->tid,
				'id' => $item->id,
				'year' =>$route->year
			);
			$response = sqlReturnObjectOne($sql, $data);
			$item->number = isset($response->numbers)? $response->numbers : null;
			// set value
			$settings[] = $item;
		}
	}
	writeLog('getSettingsToday-67',$out['debug']);
    $out['content'] = $settings;
    return $out;
}
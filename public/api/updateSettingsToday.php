<?php

function updateSettingsToday($params){
	$out['debug'] = null;
	if (!isset($params['year'])){
		 $out['debug'] .= 'Year not set in updateSettingsToday' . "\n";
	}
	if (!isset($params['tid'])){
		 $out['debug'] .= 'tid not set in updateSettingsToday'. "\n";
	}
	if (!isset($params['uid'])){
		 $out['debug'] .= 'uid not set in updateSettingsToday'. "\n";
	}
	
	if ($out['debug'] != null){
		return $out;
	}
	// get new values
	$plans = json_decode($params['plan']);
	
	foreach ($plans as $plan){
		if ($plan->quick == 'Y'){
			$res = updateQuick($params, $plan);
			$out['debug'] .= isset($res['debug']) ? $res['debug'] : null;
		}
		else{
			$res = deleteQuick($params, $plan);
			$out['debug'] .= isset($res['debug']) ? $res['debug'] : null;
		}
		
	}
    return $out;
}

function updateQuick($params,  $plan){
	$out = [];
	$out['debug'] = null;
	$sql = 'SELECT * FROM quick  WHERE
		    uid = :uid
			AND tid = :tid
			AND item =  :item 
			LIMIT 1';
	$data = array(
		'uid' => $params['uid'],
		'tid' => $params['tid'] ,
		'item' => $plan->id,
	);
	// get existing values
	$existing = sqlReturnObjectOne($sql, $data);
	$out['debug'] .="\n". 'checking ' . $plan->id . ' for uid: '. $params['uid'] . ' and  tid:  ' .$params['tid']  .  "\n";
	$out['debug'] .= isset($existing->uid)? $existing->uid : ' not set' ;
	$out['debug'] .= "\n";
	// if none exisitng insert record
	if (!isset($existing->uid)){
		$sql = 'INSERT INTO  quick (uid, tid, item) 
			VALUES ( :uid, :tid, :item)';
		$res = sqlSafe($sql, $data);
		$out['debug'] .= isset($res['debug'])? $res['debug'] :null ;
		$out['debug'] .= 'inserted '. $plan->id . "\n\n";
	}
	else{
		$out['debug'] .= 'skipped '. $plan->id . "\n\n";
	}
	// check to make sure not two
	$sql = 'SELECT COUNT(uid) as count FROM quick  WHERE
		    uid = :uid
			AND tid = :tid
			AND item =  :item';
	$check = sqlReturnObjectOne($sql, $data);
	if ($check->count != 1){
		$out['debug'] .= 'count is now  '.  $check->count . ' for ' . $plan->id . "\n";
		for ($i = 1; $i < $check->count; $i ++){
			$out['debug'] .= 'deleted one ' . $plan->id . "\n";
			$sql = 'DELETE FROM quick  WHERE
						uid = :uid
						AND tid = :tid
						AND item =  :item 
						LIMIT 1';		
			$res = sqlSafe($sql, $data);
		}
	}
	return $out;
}

function deleteQuick($params, $plan){
	$out = [];
	$sql = 'DELETE FROM quick  WHERE
		    uid = :uid
			AND tid = :tid
			AND item =  :item';
	$data = array(
		'uid' => $params['uid'],
		'tid' => $params['tid'] ,
		'item' => $plan->id,
	);
	$res = sqlSafe($sql, $data);
	$out['debug'] = isset($res['debug'])? $res['debug'] :null;
	$out['debug'] .= 'deleted '. $plan->id . "\n\n";;
	return $out;

}
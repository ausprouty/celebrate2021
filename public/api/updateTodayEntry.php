<?php

function updateTodayEntry($params){
	$out['debug']  = null;
	if (!isset($params['today'])){
		$out['debug']  .= 'Today not set in updateTodayEntry' . "\n";
		return $out;
	}
	$today = json_decode($params['today']) ;
	
	$sql = 'UPDATE today  
		SET  entry= :entry, comment= :comment, prayer = :prayer
		WHERE todayid = :todayid LIMIT 1';
	
	$data = [   
		'entry' => $today->entry,
		'comment'=> isset($today->comment) ? $today->comment : null,
		'prayer'=> isset($today->prayer) ? $today->prayer : null,
		'todayid' => $today->todayid
	];
	$out = sqlSafe($sql, $data);
    return $out;
}
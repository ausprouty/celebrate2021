<?php

function updateTodayEntry($params){
	$out['debug']  = null;
	if (!isset($params['today'])){
		$out['debug']  .= 'Today not set in updateTodayEntry' . "\n";
		return $out;
	}
	$today = json_decode($params['today']) ;
	$route = json_decode($params['route']) ;
    if (isset($today->todayid)){
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
	}
	else{
		$sql = 'INSERT INTO today (time, uid, tid, month, year, item, entry, comment, prayer)
			VALUES (:time, :uid, :tid, :month, :year, :item, :entry, :comment, :prayer)';

		$data = [
			'time' => time(),
			'uid' => $route->uid,
			'tid' => $route->tid,
			'month'=> $today->month,
			'year'=> $today->year,
			'item'=> $route->id,
			'entry' => $today->entry,
			'comment'=> isset($today->comment) ? $today->comment : null,
			'prayer'=> isset($today->prayer) ? $today->prayer : null,
		];
		$out = sqlSafe($sql, $data);

	}

    return $out;
}
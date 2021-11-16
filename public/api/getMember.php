<?php
function getMember($params){
	$out = [];
	if (!isset($params['uid'])){
		if (isset($params['route'])){
			$route = json_decode($params['route']);
			if (isset($route->uid)){
				$params['uid'] = $route->uid;
			}
		}
		if (!isset($params['uid'])){
			trigger_error("No uid for getMember", E_USER_ERROR);
			return $out;
		}
	}
    $sql = 'SELECT * FROM members
		WHERE uid = :uid
		AND tid =:tid
		LIMIT 1';
	$data = array(
		'uid' => $params['uid'],
		'tid' => $params['tid'],
	);
    $member = sqlReturnObjectOne($sql, $data);
	$out['content'] = $member;
    return $out;
}
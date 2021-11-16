<?php
function getUser($params){
	$out = [];
	if (!isset($params['uid'])){
		if (isset($params['route'])){
			$route = json_decode($params['route']);
			if (isset($route->uid)){
				$params['uid'] = $route->uid;
			}
		}
		if (!isset($params['uid'])){
			trigger_error("No uid for getUser", E_USER_ERROR);
			return $out;
		}
	}
    $sql = 'SELECT * FROM users WHERE uid = :uid';
	$data = array(
		'uid' => $params['uid']
	);
    $user = sqlReturnObjectOne($sql, $data);
	unset($user->email);
	unset($user->password);
	$out['content'] = $user;
    return $out;
}
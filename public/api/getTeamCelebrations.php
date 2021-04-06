<?php
// this is not functional

function getMembers($params){
	if (!isset($params['tid'])){
		 $out['debug'] = 'tid not set in getMembers';
		return $out;
	}
	$members = [];
	$out['debug'] = $params['tid'] . "\n";
	$sql = 'SELECT * FROM users 
    INNER JOIN members 
    ON users.uid = members.uid
    WHERE tid = :tid 
    ORDER BY firstname';
	$data = array(
		'tid' => $params['tid'] 
	);
    $result = sqlReturnObjectMany($sql, $data);
	foreach ($result as $member){
		unset($member->password);
		$members[] = $member;
	}
    $out['content'] = $members;
    return $out;
}
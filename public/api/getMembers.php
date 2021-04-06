<?php

function getMembers($params){
	if (!isset($params['tid'])){
		 $out['debug'] = 'tid not set in getMembers';
		return $out;
	}
	$members = [];
	$out['debug'] = $params['tid'] . "\n";
	$sql = 'SELECT * FROM users WHERE team = :tid ORDER BY firstname';
	$data= array(
		'tid'=> $params['tid']
	);
	$out['debug'] .= $sql . "\n";
    $result = sqlReturnObjectMany($sql, $data);
	foreach ($result as $member){
		unset($member->email);
		unset($member->password);
		$members[] = $member;
	}
    $out['content'] = $members;
    return $out;
}
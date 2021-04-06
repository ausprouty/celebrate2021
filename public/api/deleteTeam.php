<?php

function deleteTeam($params){
	$out['debug'] = null;

	if (!isset($params['tid'])){
		 $out['debug'] .= 'tid not set in deleteTeam' . "\n";
	}
	
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
	$sql = 'DELETE FROM teams WHERE tid= :tid LIMIT 1';
	$data = [   
	  'tid'=> $params['tid']
	];
	$out = sqlSafe($sql, $data);
    return $out;
}
<?php

function updateGoals($params){
	$out['debug'] = null;
	if (!isset($params['year'])){
		 $out['debug'] .= 'Year not set in updateGoals' . "\n";
	}
	if (!isset($params['tid'])){
		 $out['debug'] .= 'tid not set in updateGoals'. "\n";
	}
	
	if (!isset($params['goals'])){
		 $out['debug'] .= 'goals not set in updateGoals'. "\n";
	}
	if ($out['debug'] != null){
		return $out;
	}
	// get new values
	$goals = json_decode($params['goals']);

	// team goals are not assigned to one person
	if (!isset($params['uid'])){
		$params['uid'] = NULL;
		
   	}
	
	foreach ($goals as $goal){
		$numbers = isset($goal->number) ? $goal->number: 0;
		$text = isset($goal->text) ?  "'" .$goal->text ."'" : "";
		$sql = 'SELECT * FROM goals  WHERE id = :id 
			AND tid = :tid
			AND uid = :uid
			AND year =  :year 
			LIMIT 1';
		$data = array(
			'id' => $goal->id,
			'tid' => $params['tid'] ,
			'uid' => $params['uid'],
			'year' => $params['year']

		);
		// get existing values
		$existing = sqlReturnObjectOne($sql, $data);
		// if none exisitng insert record
		if (!isset($existing->gid)){
			$sql = "INSERT INTO  goals (uid, tid, id, year, numbers, text) 
				VALUES ( :uid, :tid, :id, :year, :numbers, :text)";
			$data = array(
				'id' => $goal->id,
				'tid' => $params['tid'] ,
				'uid' => $params['uid'],
				'year' => $params['year'],
				'numbers' => $numbers,
				'text' => $text
	
			);
			$res = sqlSafe($sql, $data);
			$out['debug'] .= isset($res['debug'])? $res['debug'] : null;
		}
		// if existing values then update
		else{
			$out['debug'] .= 'Numbers: '. $numbers . "\n";
			$out['debug'] .= 'Result Numbers: '. $existing->numbers . "\n";
			//if ($numbers){
				if ($numbers != $existing->numbers){
					$sql = 'UPDATE goals SET numbers = :numbers WHERE id = :id 
					AND tid = :tid
					AND uid = :uid
					AND year =  :year 
					LIMIT 1';
					$data = array(
						'numbers' => $numbers,
						'id' => $goal->id,
						'tid' => $params['tid'] ,
						'uid' => $params['uid'],
						'year' => $params['year'],
					);
					$res = sqlSafe($sql, $data);
					$out['debug'] .= isset($res['debug'])? $res['debug'] : null;
				}
			//}
			//if ($text){
				if ($text != $existing->text){
					$sql = 'UPDATE goals SET text = :text WHERE id = :id 
					AND tid = :tid
					AND uid = :uid
					AND year =  :year 
					LIMIT 1';
					$data = array(
						'text' => $text,
						'id' => $goal->id,
						'tid' => $params['tid'] ,
						'uid' => $params['uid'],
						'year' => $params['year'],
					);
					$res = sqlSafe($sql, $data);
					$out['debug'] .= isset($res['debug'])? $res['debug'] : null;
				}
			//}
		}
	}
    return $out;
}
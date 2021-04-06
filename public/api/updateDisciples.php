<?php

function updateDisciples($params){
	$out['debug']  = null;
	
	if (!isset($params['new_disciples'])){
		$out['debug']  .= 'New Disciples not set in updateDisciples' . "\n";
	}
	if (!isset($params['uid'])){
		$out['debug']  .= 'Uid not set in updateDisciples' . "\n";
	}
	if (!isset($params['tid'])){
		$out['debug']  .= 'Tid not set in updateDisciples' . "\n";
	}
	//leave if any errors
	if ($out['debug']  != null){
		return $out;
	}
	$new_disciples = json_decode($params['new_disciples']);
	foreach($new_disciples as $new_disciple) {
		if ($new_disciple->firstname != ''){
			$sql = 'INSERT INTO disciples (tid, uid, firstname, progress, group_name)
				VALUES (:tid, :uid, :firstname, :progress, :group_name)';
			$data = [
				'tid' => $params['tid'], 
				'uid'=> $params['uid'], 
				'firstname'=> $new_disciple->firstname,  
				'progress'=> $new_disciple->progress,  
				'group_name'=>$new_disciple->group_name  
			];
			$res  = sqlSafe($sql, $data);
			$out['debug']  .= isset($res['debug']) ? $res['debug'] :null;
			$out['debug']  .=  "\n";
		}
	}
	if (isset($params['disciples'])){
		$disciples = json_decode($params['disciples']);
		foreach($disciples as $disciple) {
		
			if ($disciple->progress != 'Delete'){
				$out['debug']  .=  "I am going to update an item for $disciple->progress \n";
				$sql = 'UPDATE disciples SET 
					firstname = :firstname, 
					progress = :progress, 
					group_name = :group_name
					WHERE discipleid = :discipleid
					AND uid = :uid
					LIMIT 1';
				$data = [
					'firstname'=> $disciple->firstname,  
					'progress'=>  $disciple->progress,  
					'group_name'=>  $disciple->group_name, 
					'discipleid' => $disciple->discipleid ,
					'uid'=> $params['uid'],  
				];
			}else{
				$out['debug']  .=  "I am going to delete an item for $disciple->progress \n";
				$sql = 'DELETE FROM  disciples 
					WHERE discipleid = :discipleid
					AND uid = :uid
					LIMIT 1';
				$data = [
					'discipleid' => $disciple->discipleid ,
					'uid'=> $params['uid'],  
				];
			}
			$res = sqlSafe($sql, $data);
			$out['debug'] .= isset($res['debug'])? $res['debug'] : null;
			$out['debug']  .= $sql . "\n";
		}
	}
    return $out;
}
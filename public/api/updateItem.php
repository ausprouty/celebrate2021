<?php

function updateItem($params){
	$out['debug']  = null;
	$item = json_decode($params['item']) ;
	if (!isset($item->celebration_set )){
		 $out['debug']  .= 'Celebration Set not set in updateItem' . "\n";
	}
	if (!isset($item->name )){
		 $out['debug']  .= 'Name not set in updateItem'. "\n";
	}
	if (!isset($item->paraphrase )){
		 $out['debug']  .= 'paraphrase not set in updateItem'. "\n";
	}
	if (!isset($item->numbers )){
		 $out['debug']  .= 'numbers not set in updateItem'. "\n";
	}//leave if any errors
	if ($out['debug']  != null){
		return $out;
	}
	foreach (get_object_vars($item) as $var => $val) {
		$out['debug']  .= $var . '->'. $val . "\n";
	}
	// insert new record
	if ($item->id  == null){
		$sql = 'INSERT INTO items (celebration_set, tid, uid, sequence, page, code, objective, name, definition, paraphrase, details, numbers, cumulative, image)
			VALUES (:celebration_set, :tid, :uid, :sequence, :page, :code, :objective, :name, :definition, :paraphrase, :details, :numbers, :cumulative, :image)';
		$data = [
		  'celebration_set' => $item->celebration_set ,
		  'tid' => isset($item->tid ) ? $item->tid  : null, 
		  'uid'=> isset($item->uid ) ? $item->uid  : null ,  
		  'sequence'=> isset($item->sequence ) ? $item->sequence  : null ,  
		  'page'=> isset($item->page ) ? $item->page  : 0 ,  
		  'code'=> isset($item->code ) ? $item->code  : null ,   
		  'objective'=> isset($item->objective ) ? $item->objective  : null ,   
		  'name'=> $item->name   ,  
		  'definition'=> isset($item->definition ) ? $item->definition  : null ,  
		  'paraphrase'=> $item->paraphrase  ,   
		  'details'=> isset($item->details  ) ? $item->details  : null ,  
		  'numbers'=> $item->numbers,
		  'cumulative'=> isset($item->cumulative  ) ? $item->cumulative  : null ,	
		  'image'=> isset($item->image ) ? $item->image  : null ,		  
		];
		$out['debug']  .= $sql . "\n";
		$res  = sqlSafe($sql, $data);
		$out['debug']  .= isset($res['debug']) ? $res['debug'] :null;
		$out['debug']  .=  "\n";
		
	}
	else{
		$out['debug']  .=  "I am going to update an item \n";
		$sql = 'UPDATE items SET 
			celebration_set = :celebration_set, 
			tid = :tid, 
			uid = :uid, 
			sequence = :sequence,
			page = :page, 
			code = :code, 
			objective = :objective,
			name = :name, 
			definition = :definition, 
			paraphrase = :paraphrase, 
			details = :details, 
			numbers = :numbers,
			cumulative=:cumulative,
			image=:image
			WHERE id = :id';
		$data = array(
			'celebration_set' => $item->celebration_set ,
			'tid' => isset($item->tid ) ? $item->tid  : null , 
			'uid'=> isset($item->uid ) ? $item->uid  : null ,  
			'sequence'=> isset($item->sequence ) ? $item->sequence  : null ,  
			'page'=> isset($item->page ) ? $item->page  : 0 ,  
			'code'=> isset($item->code ) ? $item->code  : null ,  
			'objective'=> isset($item->objective ) ? $item->objective  : null ,    
			'name'=> $item->name,  
			'definition'=> isset($item->definition ) ? $item->definition  : null ,  
			'paraphrase'=> $item->paraphrase,   
			'details'=> isset($item->details  ) ? $item->details  : null ,  
			'numbers'=> $item->numbers,
			'cumulative'=> isset($item->cumulative  ) ? $item->cumulative  : null ,	
			'image'=> isset($item->image ) ? $item->image  : null ,
			'id' => $item->id
		  
		);
		
		
		foreach ($data as $var => $val) {
			$out['debug']  .= $var . '->'. $val . "\n";
		}
		
		$res = sqlSafe($sql, $data);
		$out['debug'] .= isset($res['debug'])? $res['debug'] : null;
		
		
		$out['debug']  .= $sql . "\n";
		
		$sql = 'SELECT * FROM items WHERE id = :id LIMIT 1';
		$data = array( 'id'=> $item->id);
		$out['debug'] .= $sql . "\n";
		$check = sqlReturnObjectOne($sql, $data);
		if ($check->name != $item->name){
			$out['content'] = 'Not changeD from '. $check->name . ' to '. $item->name;
		}
		else {
			$out['content'] = 'changed to ' . $check->name;
		}
	}
    return $out;
}
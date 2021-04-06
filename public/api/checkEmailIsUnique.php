<?php

// will return 1  if Email is not presently in system

// as array
function checkEmailIsUnique($p){
    $out = array();
    $out['debug'] = null;
	if (!isset($p['email'])){
		 $out['debug'] .= 'No email in checkEmailIsUnique' . "\n";
    }
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
    $out['debug'] = 'checkEmailIsUnique' ."\n";
    
    $sql = "SELECT uid FROM users WHERE email = :email LIMIT 1";
    $data = array(
        'email' =>$p['email'], 
    );
    $result = sqlReturnObjectOne($sql, $data);
    if (isset($result->uid)) {
            $out['content'] = null;
    }
    else{
        $out['content'] = 1;
    }
    return $out;
}
// as integer
function emailIsUnique($email){
    $sql = "SELECT uid FROM users WHERE email = :email LIMIT 1";
    $data = array(
        'email' =>$email, 
    );
    $result = sqlReturnObjectOne($sql, $data);
    if (isset($result->uid)) {
            return null;
    }
    else{
        return  1;
    }
}

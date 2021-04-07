<?php
require_once ('checkEmailIsUnique.php');

function createTeamMembers($p){
    $out = array();
    $out['debug'] = null;
	if (!isset($params['members'])){
		 $out['debug'] .= 'No members in createTeamMembers' . "\n";
    }
    if (!isset($params['tid'])){
        $out['debug'] .= 'No tid in createTeamMembers' . "\n";
   }
	//leave if any errors
	if ($out['debug'] != null){
		return $out;
	}
    $out['debug'] = 'createTeamMembers' ."\n";
    $tid = $params['tid'];
    $lines = explode("\n", $params['members']);
    foreach ($lines as $line){
        $items = explode ("\t", $line);
        if (count($items > 3)){
            $firstname = $items[1];
            $lastname = $items[2];
            $email = $items[3];
            $password = uniqid();
            $hash = password_hash($password, PASSWORD_DEFAULT);
        }
        // if email is unique enter user
        $unique = checkEmailIsUnique($email);
        if ($unique == 1){
            $sql = "INSERT INTO users ( firstname, lastname, email, password, date_started) VALUES
                ( :team, :firstname, :lastname, :email, :password, :date_started)";
            $data = array(
                'firstname' =>  $firstname, 
                'lastname' => $lastname, 
                'email' =>$email, 
                'password' => $hash , 
                'date_started' => time()
            );
            sqlSafe($sql, $data);
            // add user to team
            $uid = getUidFromEmail($email);
            $sql = "INSERT INTO members ( uid, tid, date_started)
                VALUES (:uid, :tid, :date_started)";
            $data= array(
                'uid' => $uid,
                'tid' => $tid,
                'date_started' => time()
            );
            sqlSafe($sql, $data);
        }
        if ($unique != 1){
            $out['debug'] .=  $email . ' is not unique ' . "\n";
        }
    }
    return $out;
}

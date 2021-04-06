<?php
require_once ('checkEmailIsUnique.php');
function updateUserProfile($p){
    $out = array();
    $out['debug'] = 'updateUserProfile' ."\n";
    // if existing user
    if (isset($p['uid'])){
        $out['debug'] .=  'I am updating an exiting person' ."\n";
        $sql = "UPDATE users SET firstname = :firstname, lastname = :lastname, phone = :phone
        WHERE uid = :uid LIMIT 1";
        $data = array(
            'firstname' =>  $p['firstname'], 
            'lastname' => $p['lastname'], 
            'phone' => $p['phone'], 
            'uid'=>  $p['uid']
        );
        sqlSafe($sql, $data);
        // was password changed?
        if (isset($p['password'])){
            $hash = password_hash($p['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET  password = :password
            WHERE uid = :uid LIMIT 1";
            $data = array(
                'password' => $hash , 
                'uid'=>  $p['uid']
            );
            sqlSafe($sql, $data);
        }
        // was email changed?
        if (isset($p['email'])){
            $sql = "UPDATE users SET email = :email
            WHERE uid = :uid LIMIT 1";
            $data = array(
                'email' => $p['email'] , 
                'uid'=>  $p['uid']
            );
            sqlSafe($sql, $data);
        }
        return $out;
    }
    else{
        $out['debug'] .=  'I am entering a new person and the check of thier new email said' ."\n";
        $out['debug'] .= emailIsUnique($p['email']) ."\n";
        if (emailIsUnique($p['email']) == 1){
            $out['debug'] .=  'Their email is unique' ."\n";
            $hash = password_hash($p['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users ( firstname, lastname, email, password, date_started) VALUES
                ( :firstname, :lastname, :email, :password, :date_started)";
            $data = array(
                'firstname' =>  $p['firstname'], 
                'lastname' => $p['lastname'], 
                'email' =>$p['email'], 
                'password' => $hash , 
                'date_started' => time()
            );
            sqlSafe($sql, $data);
            // add user to team
            $uid = getUidFromEmail($p['email']);
            $sql = "INSERT INTO members ( uid, tid, date_started)
                VALUES (:uid, :tid, :date_started)";
            $data= array(
                'uid' => $uid,
                'tid' => $p['tid'],
                'date_started' => time()
            );
            sqlSafe($sql, $data);
        }
        return $out;

    }
}

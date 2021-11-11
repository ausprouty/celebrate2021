<?php
function findUserUid($p){
    $out= [];
    $sql = "SELECT uid FROM users WHERE email = :email LIMIT 1";
    $data = array(
        'email' => $p['email']
    );
    $user = sqlReturnObjectOne($sql, $data);
    if ($user){
        return $user->uid;
    }
    return null;

}
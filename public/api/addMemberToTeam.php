<?php
function AddMemberToTeam($uid, $tid, $scope= null){
    $out = [];
    $data = array(
        'uid' =>  $uid,
        'tid' => $tid,
    );
      // are they already in this team?
    $sql = "SELECT mid FROM members WHERE uid = :uid AND tid = :tid LIMIT 1";
    $res =  sqlReturnObjectOne($sql, $data);
    if (!isset($res->mid)){
        $data = array(
            'uid' =>  $uid,
            'tid' => $tid,
            'scope' =>  $scope,
            'date_started' => time()

        );
        $sql = "INSERT INTO members (uid, tid, scope, date_started)
             VALUES( :uid, :tid, :scope, :date_started)";
        sqlSafe($sql, $data);}

    return $out;
}
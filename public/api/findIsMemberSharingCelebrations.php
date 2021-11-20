<?php


function  findIsMemberSharingCelebrations($member, $route){
// $route->month; $route->year; $route->tid
// $member->date_started; $member->date_stopped
    $out=[];
    $out['debug']='';
    if (!isset($route->year)){
        $route->year = (int)date('Y');
    }
    if (!isset($route->month)){
        $route->month = (int)date('n') -1;
        if ($route->month == 0){
            $route->year = $route->year -1;
            $route->month = 12;
        }
    }
    $starting_month= date('n', $member->date_started);
    $starting_year = date('Y', $member->date_started);
    if ($starting_year < $route->year){
      $starting_month= 1;
    }
    $expected_shared = (int)$route->month - (int)$starting_month + 1;
    $out['debug'] .="Expected Share:  $expected_shared\n";
    writeLog('MemberSharing-' . $member->uid, $out['debug']);
    $sql = 'SELECT count(id) AS count FROM shared
        WHERE uid = :uid AND  tid = :tid
        AND year = :year';
    $data = [
        'uid' =>$member->uid,
        'tid'=> $route->tid,
        'year'=> $route->year,
    ];
    $shared = sqlReturnObjectOne($sql, $data);
     $sharing = 'Y';
    if ($shared->count != $expected_shared){
        $sharing ='N';
    }
    writeLog('sharing' . $member->uid , $out['debug']);
    return $sharing;
}

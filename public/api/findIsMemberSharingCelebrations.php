<?php


function  findIsMemberSharingCelebrations($member, $route){
// $route->month; $route->year; $route->tid
// $member->date_started; $member->date_stopped
    if (!isset($route->year)){
        $route->year = date('Y');
    }
    if (!isset($route->month)){
        $route->month = date('n') -1;
        if ($route->month == 0){
            $route->year = $route->year -1;
            $route->month = 12;
        }
    }
    $starting_month= date('M', $member->date_started);
    $starting_year= date('Y', $member->date_started);
    if ($starting_year < $route->year){
      $starting_month= 1;
    }
    $expected_shared = $route->month - $starting_month + 1;
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
    return $sharing;
}

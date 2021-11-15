<?php

function  findMemberSharingMissing($member, $route){
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
    $sql = 'SELECT month FROM shared
        WHERE uid = :uid AND  tid = :tid
        AND year = :year
        AND month >= :month';
    $data = [
        'uid' =>$member->uid,
        'tid'=> $route->tid,
        'year'=> $route->year,
        'month' =>$starting_month,
    ];
    $shared = sqlReturnObjectMany($sql, $data);
    $found = [];
    foreach ($shared as $share){
        $found[$share->month] ='Y';
    }
    $missing = [];
    for ($month = $starting_month; $month<= $route->month; $month++ ){
        if (!isset($found[$month])){
            $missing[] = $month;
        }
    }
    return  $missing;

}

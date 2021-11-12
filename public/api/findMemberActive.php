<?php

function findMemberActive($route, $month){
    if ($month < 10){
       $month ='0'. $month;
    }
    $consider =  strtotime( $route->year . '-'. $month . '-01');
    $active= false;
    // assumes uid and tid and year
    $sql = 'SELECT * FROM members
        WHERE uid = :uid AND  tid = :tid
        LIMIT 1';
    $data = [
        'uid' => $route->uid,
        'tid'=> $route->tid,
    ];
   $member = sqlReturnObjectOne($sql, $data);
   if ($member->date_started < $consider){
        $active = true;
    if ($member->date_stopped){
      if ($member->date_stopped < $consider){
        $active = false;
      }
    }
   }
   return $active;
}
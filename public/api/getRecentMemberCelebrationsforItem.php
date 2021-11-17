<?php
include_once ('verifyRoute.php');
function  getRecentMemberCelebrationsforItem($p){
    $out=[];
    $required= array('uid', 'tid', 'id');
    $res = verifyRoute($p['route'], $required , $source =  'getRecentMemberCelebrationsforItem');
    $route= $res['route'];
    $sql = 'SELECT * FROM today
        WHERE uid =:uid AND
        tid = :tid AND item =:id
        ORDER BY  time DESC
        LIMIT 5';
    $data = [
      'uid'=> $route->uid,
      'tid'=> $route->tid,
      'id'=> $route->id,
    ];
    $daily = sqlReturnObjectMany($sql, $data);
    $out['content']= $daily;
    return $out;
}
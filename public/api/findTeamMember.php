<?php
function findTeamMember($route){

    $sql = 'SELECT u.uid, u.firstname, u.lastname, u.image,
    m.scope, m.date_started, m.date_stopped
    FROM users AS u INNER JOIN members AS m
        ON u.uid = m.uid
        WHERE tid = :tid
        LIMIT 1';
    $data= array(
        'tid'=>  $route->tid
    );
    $member = sqlReturnObjectOne($sql, $data);
    return $member;
}
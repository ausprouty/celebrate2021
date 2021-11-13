<?php
function findTeamMembersCurrent($route){

    $sql = 'SELECT u.uid, u.firstname, u.lastname, u.image,
    m.scope, m.date_started, m.date_stopped
    FROM users AS u INNER JOIN members AS m
        ON u.uid = m.uid
        WHERE tid = :tid AND
        m.date_stopped IS NULL
        ORDER BY firstname';
    $data= array(
        'tid'=>  $route->tid
    );
    $result = sqlReturnObjectMany($sql, $data);
    return $result;
}
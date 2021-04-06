<?php
function getTeamMembersReported($params){
    $out['debug']  = null;
	if (!isset($params['route'] )){
		 $out['debug']  .= 'Route not set in getTeamMembersReported'. "\n";
	}
	//leave if any errors
	if ($out['debug']  != null){
		return $out;
    }
    // decode parameters
    $route = json_decode($params['route']);
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
    // get Team Members?
   $members = [];
   $sql = 'SELECT * FROM users 
    INNER JOIN members 
    ON users.uid = members.uid
    WHERE tid = :tid 
    ORDER BY firstname';
   $data= array(
       'tid'=>  $route->tid
   );
   $out['debug'] .= $sql . "\n";
   $result = sqlReturnObjectMany($sql, $data);
   foreach ($result as $member){
       // remove sensitive information
       unset($member->password);
       // find reports
       $sql = 'SELECT count(rid) AS count FROM reported 
            WHERE uid = :uid AND  tid = :tid 
            AND year = :year';
        $data = [   
            'uid' => $member->uid,
            'tid'=> $route->tid,
            'year'=> $route->year,
        ];
        $reports = sqlReturnObjectOne($sql, $data);
        $member->reports = $reports->count;
        $member->month = $route->month;
        if ($reports->count == $route->month){
            $member->current = 'Y';
        }
        else{
            $member->current = 'N';
        }
       // add to array
       $members[] = $member;
   }
   $out['content'] = $members;
   return $out;
}
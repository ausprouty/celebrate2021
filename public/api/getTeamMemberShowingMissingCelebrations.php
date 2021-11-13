<?php
include_once('findTeamMember.php');
include_once ('findMemberSharingMissing.php');

function getTeamMemberShowingMissingCelebrations($params){
    $out= [];
    $missing = [];
    $out['debug']  = null;
	if (!isset($params['route'] )){
		 $out['debug']  .= 'Route not set in getTeamMembersReported'. "\n";
         return $out;
	}
    $route = json_decode($params['route']);
    $member = findTeamMember($route);
    $missing = findMemberSharingMissing($member, $route);
    writeLog('getitrite', $missing);
    $out['content'] = $missing;
    return $out;
}
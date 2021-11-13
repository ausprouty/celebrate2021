<?php
include_once('findTeamMembersCurrent.php');
include_once ('findIsMemberSharingCelebrations.php');

function getTeamMembersShowingCurrentCelebrations($params){
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

    // get Team Members?
   $members = [];
   $result = findTeamMembersCurrent($route);
   foreach ($result as $member){
       $member->current = findIsMemberSharingCelebrations($member, $route);
       $members[] = $member;
   }
   $out['content'] = $members;
   return $out;
}
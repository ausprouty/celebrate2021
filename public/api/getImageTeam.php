<?php
require_once ('dirList.php');

function getImageTeam($team){
    $out = [];
    $out['debug'] = null;
	if (!isset($team->tid)){
		 $out['debug'] = 'dir not set in getImageTeam';
		return $out;
    }

    // get random picture for team
    // dir is set in team.code
    $dir = '/images/team' . $team->tid . '/team/';
    $pictures = dirList($dir);
    if (!$pictures){
        $dir = '/images/teamDefault/team/';
        $pictures = dirList($dir);
    }
    $count = count($pictures);
    $selected = rand(0, $count-1);
    $out['content'] = $dir . $pictures[$selected];
    
    return $out;

}
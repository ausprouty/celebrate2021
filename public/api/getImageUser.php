<?php

function getImageUser($params){
    $out = [];
    $out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] = 'route not set in getImageUser';
		return $out;
    }
    // decode route
    $route = json_decode($params['route']);

    // get random picture for page
    $dir = 'images/pages/'. $route->page . '/';
    $pictures = dirList($dir);
    if ($pictures){
        foreach ($pictures as $picture){
            $out['debug'] .= $picture  . " is picture\n";
        }
        $count = count($pictures);
        //$out['debug'] .= $count  . " is count\n";
        $selected = rand(0, $count-1);
        //$out['debug'] .= $selected  . " is selected\n";
        $out['content'] = $pictures[$selected];
        //$out['debug'] .= $picture . "\n";
    }
    else{
        $out['debug'] .= 'No pictures' . "\n";  
    }
    return $out;

}
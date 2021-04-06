<?php

require_once ('dirList.php');
function getImagePage($params){
    $out = [];
    $out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] = 'route not set in getProgress';
		return $out;
    }
    // decode route
    $route = json_decode($params['route']);

    if (!isset($route->page)){
        $route->page = 0;
    }

    // get random picture for page
    $pictures = dirList('/images/pages/'. $route->page);
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
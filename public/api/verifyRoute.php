<?php

function verifyRoute($route_encoded = null, $required = null, $source = null){
 // $required = array('year', 'tid','uid', 'yid');
 // $source = 'getSettingsToday';
 // $route_encoded ='{"uid":1,"tid":1,"year":2021}';
    $out= [];
    $missing = null;
    if (!isset($route_encoded)){
        $out['debug'] = "No encoded route in verifyRoute sent by  $source\n";
        _writeLog('verifyRoute-7-' . $source, 'no route_encoded');
        return $out;
    }
    $route = json_decode($route_encoded);
    foreach ($required as $r){
       _writeLog('verifyRoute-20-' . $r, $r);
         if (!isset($route->$r)){
           $missing .= "$r  ," ;
         }
    }
    if ($missing) {
         $out['debug'] = "Missing  $missing in $source\n";
          _writeLog('verifyRoute-27-' . $source,  $out['debug']);
         return $out;
    }
    $out['route']=$route;
    return $out;

}
function _writeLog($filename, $content){
	if (!is_array($content)){
		$text = $content;
	}
	else{
		$text = '';
		foreach ($content as $key=> $value){
			$text .= $key . ' => '. $value . "\n";
		}
	}

		$root_log = 'c:/xampp/htdocs/log/';

	if (!file_exists($root_log)){
		mkdir($root_log);
	}
	$fh = fopen($root_log . $filename . '.txt', 'w');
	fwrite($fh, $text);
    fclose($fh);
}

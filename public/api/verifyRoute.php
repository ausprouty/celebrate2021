<?php
function verifyRoute($route_encoded, $required, $source){
    $out= [];
    $missing = null;
    if (!isset($route_encoded)){
        $out['debug'] = "No encoded route in verifyRoute sent by  $source\n";
        writeLog('verifyRoute-7-' . $source, 'no route_encoded');
        return $out;
    }
    $route = json_decode($route_encoded);
    foreach ($required as $r){
       writeLog('verifyRoute-27-' . $r, $r);
         if (!isset($route->$r)){
           $missing .= "$r  ," ;
         }
    }
    if ($missing) {
         $out['debug'] = "Missing  $missing in $source\n";
          writeLog('verifyRoute-18-' . $source,  $out['debug']);
         return $out;
    }
    $out['route']=$route;
    return $out;

}
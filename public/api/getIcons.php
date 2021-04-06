<?php
require_once ('dirList.php');

function getIcons($params){
    $out = [];
    $package = [];
	if (!isset($params['icons'])){
		$params['icons']= 'Cru';
    }
    if (!isset($params['icon_size'])){
        $params['icon_size'] =  '48x48';
    }
    $params['icon_size'] .= '.png';
    $dir = '/images/icons/' . $params['icons'] .'/';
    $icons = dirListMask($dir, $params['icon_size'] );
    if ($icons){
        $package['icons'] = [];
        sort($icons);
        foreach ($icons as $icon){
            $item = new stdClass();
            $item->title= str_ireplace('_' . $params['icon_size'], '', $icon);
            $item->image = $icon;
            $package['icons'][] = $item;
        }
        $package['dir'] = $dir;
        $out['content'] = $package;
    }
    else{
        $out['debug'] = 'No icons' . "\n";  
    }
    return $out;

}
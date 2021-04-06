<?php

function dirlist ($dir){
    $results = [];
    $directory = ROOT_CONTENT . $dir;
	if (file_exists($directory)){
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				$results[] =  $mfile;
			}
		}
		closedir ($handler);
	}
    return $results;
}

function dirlistMask ($dir, $mask){
    $results = [];
    $directory = ROOT_CONTENT . $dir;
	if (file_exists($directory)){
		$handler = opendir ($directory);
		while ($mfile = readdir ($handler)){
			if ($mfile != '.' && $mfile != '..' ){
				if ( strpos($mfile, $mask) !== false){
					$results[] =  $mfile;
				}
			}
		}
		closedir ($handler);
	}
    return $results;
}

<?php
include_once('verifyRoute.php');
include_once('findTeamFocus.php');

function getItemsToday($params){
    $out = [];
	$out['debug'] = null;
	if (!isset($params['route'])){
		 $out['debug'] .= 'route not set in  getItemsToday' . "\n";
         return $out;
	}

    // decode
	$required = array('tid','uid');
	$verify = verifyRoute($params['route'], $required, 'getItemsToday');
	if ($verify['debug'] != null){
		return $verify['debug'];
	}
    $route = $verify['route'];
    $focus =findTeamFocus($route->tid);

    // find items for quick entry
	$sql = 'SELECT i.*, q.uid FROM items AS i
         LEFT JOIN quick AS q
         ON  i.id = q.item
        WHERE
            q.uid = :uid  OR
            i.tid = :tid  OR
            i.celebration_set= :focus
       ORDER BY q.uid DESC, name ASC
        ';
	$data = [
      'uid'=> $route->uid,
      'tid'=> $route->tid,
      'focus'=> $focus,
    ];
    $items = sqlReturnObjectMany($sql, $data);
    $out['content'] = $items;
    return $out;
}

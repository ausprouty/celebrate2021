
<?php
include_once('verifyRoute.php');
include_once('findTeamFocus.php');

function getItemsToday($params){
    $out = [];

	$out['debug'] = null;
    if (isset($params['uid'])){
         $uid= $params['uid'];
         $tid = $params['tid'];
    }
	else{
        // decode
        $required = array('tid','uid');
        $verify = verifyRoute($params['route'], $required, 'getItemsToday');
        if ($verify['debug'] != null){
            return $verify['debug'];
        }
        $route = $verify['route'];
        $uid = $route->uid;
        $tid = $route->tid;
    }
    $focus =findTeamFocus($tid);

   // find items for quick entry
/*	$sql = 'SELECT i.*, q.uid FROM items AS i
         INNER JOIN quick AS q
         ON  i.id = q.item
         AND q.uid = :uid
        WHERE
            q.uid = :uid  OR
            i.tid = :tid  OR
            i.celebration_set= :focus
       ORDER BY q.uid DESC, name ASC
        ';
	$data = [
      'uid'=> $uid,
      'tid'=> $tid,
      'focus'=> $focus,
    ];
    $items = sqlReturnObjectMany($sql, $data);
    $out['content'] = $items;

    $sql = 'SELECT * FROM items
        WHERE
            uid = :uid  OR
            tid = :tid  OR
            celebration_set= :focus
       ORDER BY name ASC
        ';
	$data = [
      'uid'=> $uid,
      'tid'=> $tid,
      'focus'=> $focus,
    ];
    $items = sqlReturnObjectMany($sql, $data);
    $today = [];


    foreach ($items as $item){
        $sql='SELECT qid FROM quick
            WHERE item = :item AND uid = :uid
            LIMIT 1';
        $data = [
            'uid' => $uid,
            'item' => $item->id
        ];
        $quick = sqlReturnObjectOne($sql, $data);
        if (isset($quick->qid)){
            $item->quick= true;
        }
        else{
           $item->quick = false;
        }
        $today[] = $item;
    }
    */
    // first find all that are in the quick menu
     $sql = 'SELECT i.*, q.qid FROM items AS i
         INNER JOIN quick AS q
         ON  i.id = q.item
        WHERE q.uid = :uid
         ORDER BY q.uid DESC, name ASC
        ';
	$data = [
      'uid'=> $uid,
    ];
    $items = sqlReturnObjectMany($sql, $data);
    $found=[];
    $today =[];
    foreach ($items as $item){
        if (!in_array ( $item->id, $found) ){
            $found[] =$item->id;
            $today[]=$item;
        }
    }
    // now add all those which are not in quick menu
    $sql = 'SELECT i.* FROM items AS i
        WHERE
            i.uid = :uid  OR
            i.tid = :tid  OR
            i.celebration_set= :focus
       ORDER BY name ASC
        ';
	$data = [
      'uid'=> $uid,
      'tid'=> $tid,
      'focus'=> $focus,
    ];
    $items = sqlReturnObjectMany($sql, $data);
    foreach ($items as $item){
        if (!in_array ( $item->id, $found) ){
           $found[] =$item->id;
           $today[] = $item;
        }
    }
    $out['content'] = $today;

    return $out;
}

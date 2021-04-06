<?php
function seedProgress(){
    $out =[];
    $out['content'] = 'seeded Progress';
    $sql = 'SELECT * FROM users';
    $data = [];
    $users = sqlReturnObjectMany($sql, $data);
    foreach ($users as $user){
        $sql = 'SELECT * FROM items WHERE
        celebration_set = "Cru" 
        OR  tid = :tid
        or uid = :uid';
        $data = [   
        'uid'=> $user->uid,
        'tid'=> $user->team
        ];
        $items = sqlReturnObjectMany($sql, $data);
        foreach ($items as $item){
            $year = 2019;
            for ($i = 1; $i<13; $i++){
                $selected = rand(0, 10);
                $sql = 'INSERT INTO progress 
                        (year, month ,uid,tid,item,entry,comment,prayer)
                        VALUES (:year, :month ,:uid,:tid,:item,:entry,:comment,:prayer)';
                $data =  $data = [   
                    'year'=> $year,
                    'month'=> $i,
                    'uid'=> $user->uid,
                    'tid'=> $user->team,
                    'item' => $item->id,
                    'entry' => $selected,
                    'comment'=> null,
                    'prayer'=> null 
                ];
                $res = sqlSafe($sql, $data);
            }
            $year = 2020;
            for ($i = 1; $i<3; $i++){
                $selected = rand(0, 10);
                $sql = 'INSERT INTO progress 
                        (year, month ,uid,tid,item,entry,comment,prayer)
                        VALUES (:year, :month ,:uid,:tid,:item,:entry,:comment,:prayer)';
                $data =  $data = [   
                    'year'=> $year,
                    'month'=> $i,
                    'uid'=> $user->uid,
                    'tid'=> $user->team,
                    'item' => $item->id,
                    'entry' => $selected,
                    'comment'=> null,
                    'prayer'=> null 
                ];
                $res = sqlSafe($sql, $data);
            }
        }
    }
    return $out;
}
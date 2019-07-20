<?php

function read_new($agency, $new_id){
        $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            array('id' => $new_id, 'agency'=>$agency),
            array('$inc' => array('read_times' => 1))
        );
        $manager->executeBulkWrite('db.news', $bulk);
}

function getSavedCategories(){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $cmd = new MongoDB\Driver\Command([
        // build the 'distinct' command
       'distinct' => 'news', // specify the collection name
       'key' => 'categories' // specify the field for which we want to get the distinct values
      ]);
      $cursor = $manager->executeCommand('db', $cmd); // retrieve the results
      return $cursor->toArray()[0]->values;
}

function get_new($agency, $new_id){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(array(
        'agency'=>$agency,
        'id'=>$new_id
    ));
    $cursor = $manager->executeQuery('db.news', $query);
    $data = $cursor->toArray()[0];
    if($data->visible){
        if(isset($data)){
            return array("result"=>$data, "status"=>true, "desc"=>"From db");
        }
    }
    return array("result"=>null, "status"=>false, "desc"=>"not found in db");
}

function get_agency_news($agency){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(array(
        'agency'=>$agency
    ));
    $cursor = $manager->executeQuery('db.news', $query);
    $data = $cursor->toArray();
    if(count($data)>=1){
        return array("result"=>$data, "status"=>true, "desc"=>"From db");
    }
    return array("result"=>null, "status"=>false, "desc"=>"not found in db");
}

function getIconDB($id){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(array(
        'id'=>$id,
    ));
    $cursor = $manager->executeQuery('db.icons', $query);
    $data = @$cursor->toArray()[0];
    if(isset($data)){
            return $data;
    }
    return false;
}

function catNews($filter){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(
            array(
                'categories'=>$filter
            ),
            array(
                'sort'=>
                    array('date_u'=> -1),
                'projection'=>array(
                    "id"=>1,
                    "agency"=>1, 
                    "agency_title"=>1, 
                    "categories"=>1,
                    "date"=>1,
                    "title"=>1, 
                    "spot"=>1, 
                    "image"=>1, 
                    "url"=>1,
                    "seo_link"=>1
                    )
            )
    );
    $cursor = $manager->executeQuery('db.news', $query);
    $data = $cursor->toArray();
    if(isset($data)){
        return $data;
    }
    return null;
}

function checkNew($agency, $new_id){
        $status=false;
        $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
        $query = new MongoDB\Driver\Query(
        array(
            'agency'=>$agency,
            'id'=>$new_id
            ),
            // get just id field
        array(
            'sort'=>
                array('date_u'=> -1),
            'projection'=>
                array("id"=>1)
            )
        );
        $cursor = $manager->executeQuery('db.news', $query);
        $data=count((array)$cursor->toArray());
        if($data>=1){
            $status=true;
            return $status;
        }
    return $status;
}

function checkSearch($arg){
    $status=false;
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(
    array(
        'keyword'=>$arg
        ),
        // get just id field
    array(
        'projection'=>
            array("id"=>1),
        )
    );
    $cursor = $manager->executeQuery('db.search', $query);
    $data=count((array)$cursor->toArray());
    if($data>=1){
        $status=true;
        return $status;
    }
return $status;
}

function getSearchResult($arg, $limit=40){
    $status=false;
    if($limit===0){
        $limit=40;
    }
    if(strlen($arg)<3){
        return false;
    }
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(
    array(
        'visible'=>true,
        '$text'=>array('$search'=>$arg)
        ),
        // get just id field
    array(
        'limit'=>$limit,
        'projection'=>array(
            "id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1
        ),
        'sort'=>array("date_u"=>-1)
        )
    );
    $cursor = $manager->executeQuery('db.news', $query);
    saveSearch($arg);
    return $cursor->toArray();
}

function saveDatabase($agency, $data){
    global $new_id;
    if(strlen($data['text'])>=10 && strlen($data['title'])>=7 && isset($data['image'])){
        if(!checkNew($agency, $new_id)){
                $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->insert($data);
                $manager->executeBulkWrite('db.news', $bulk);
                return true;
        }
    }
    return false;
}

function saveComment($agency, $id, $text, $name, $ip){
        $time=time();
        $data=array(
            "id"=>Sanitizer::integer($id),
            "agency"=>Sanitizer::alfabetico($agency),
            "text"=>Sanitizer::alfanumerico($text, true, true),
            "name"=>Sanitizer::alfanumerico($name, true, true),
            "ip"=>$ip,
            "date"=>date('d.m.Y - H:i:s ', $time),
            "visible"=>true,
            "date_u"=>$time
        );
        if(strlen($name)>=3 && strlen($text)>4){
            $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($data);
            $manager->executeBulkWrite('db.comments', $bulk);
            return true;
        }else{
            return false;
        }
    return false;
}

function get_comment($agency, $new_id){
    $new_id=(int)$new_id;
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(
        array(
            'agency'=>$agency,
            'id'=>$new_id,
            'visible'=>true
        ),
        array(
            'sort'=>array("_id"=>-1)
        )
    );
    $cursor = $manager->executeQuery('db.comments', $query);
    $data = $cursor->toArray();
    if(count($data)>0){
        return array("result"=>$data, "status"=>true, "desc"=>"From db");
    }
    return array("result"=>null, "status"=>false, "desc"=>"not found in db");
}

function saveSearch($data){
    if(strlen($data)>=3){
        $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
        $bulk = new MongoDB\Driver\BulkWrite;
        if(!checkSearch($data)){
            $data=array("keyword"=>$data);
            $bulk->insert($data);
        }else{
            $bulk->update(
                array("keyword"=>$data),
                array('$inc' => array('search_times' => 1))
            );
        }
        return $manager->executeBulkWrite('db.search', $bulk);
    }
    return false;
}

function mostRead($arg, $limit=10){
    if($limit>100){
        $limit=100;
    }
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    // most read by last 7 days
    switch($arg){
        case "week":
            $week=getLastWeekDates();
            $time=$week[1];
            $maxTime=$week[0];
            $query = new MongoDB\Driver\Query(
                array(
                    'visible'=>true,
                    'date_u'=>
                        array('$gte'=>$maxTime, '$lt'=>$time)
                ),
                array(
                    'sort'=>
                        array('read_times'=> -1),
                    'limit'=>$limit,
                    'projection'=>
                                array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1),
                    'sort'=>array("date_u"=>-1)
                )
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
        break;
        case "month":
            $range=rangeMonthThis();
            $query = new MongoDB\Driver\Query(
            array(
                'visible'=>true,
                'date_u'=>
                    array('$gte'=>$range['start'], '$lt'=>$range['end'])
            ),
            array(
                'sort'=>
                    array('read_times'=> -1),
                'limit'=>$limit,
                'projection'=>
                        array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1),
                'sort'=>array("date_u"=>-1)
                )
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
        break;
        case "today":
            $today=strtotime(date("d.m.Y"));
            $query = new MongoDB\Driver\Query(
            array(
                'visible'=>true,
                "date_u"=>array('$gt'=>$today)
            ),
            array(
                'sort'=>
                    array('read_times'=> -1),
                'limit'=>$limit,
                'projection'=>
                    array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1),
                'sort'=>array("date_u"=>-1)
                )
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
        break;
        default:
            $query = new MongoDB\Driver\Query(
                array(
                    'visible'=>true
                ),
                array(
                    'limit'=>$limit,
                    'sort'=>
                        array('read_times'=> -1),
                    'projection'=>
                        array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1),
                    'sort'=>
                        array("date_u"=>-1)
                    )
    
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
    }
}

function getAgencyReadTimes() {
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $command = new MongoDB\Driver\Command([
        'aggregate' => 'news',
        'pipeline' => [
            ['$group' => ['_id' => '$agency', 'total' => ['$sum' => ['$add'=> '$read_times']]]],
        ],
        'cursor' => new stdClass,
    ]);
    $cursor = $manager->executeCommand('db', $command);
    
    /* The aggregate command can optionally return its results in a cursor instead
     * of a single result document. In this case, we can iterate on the cursor
     * directly to access those results. */
    $arr=[];
    foreach ($cursor as $key=>$document) {
        $arr[]=$document;
    }

    if(count($arr)>0){
        return $arr;
    }
    return null;
}

function getAgencyNewsCount() {
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $command = new MongoDB\Driver\Command([
        'aggregate' => 'news',
        'pipeline' => [
            ['$group' => ['_id' => '$agency', 'total' => ['$sum' => 1]]],
        ],
        'cursor' => new stdClass,
    ]);
    $cursor = $manager->executeCommand('db', $command);
    
    /* The aggregate command can optionally return its results in a cursor instead
     * of a single result document. In this case, we can iterate on the cursor
     * directly to access those results. */
    $arr=[];
    foreach ($cursor as $key=>$document) {
        $arr[]=$document;
    }

    if(count($arr)>0){
        return $arr;
    }
    return null;
}

function get_db_stats(){
    $db_stats=[];
    $db_status=curl_function("http://ulak.news:27017");
    if($db_status['status']){
        $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
        $query = new MongoDB\Driver\Command(
            ["count" => "news"]
    );
    $cursor = $manager->executeCommand('db', $query);
    $count_news = current($cursor->toArray())->n;
    $read_times=getAgencyReadTimes();
    $read_times_total=0;
    
    foreach($read_times as $raw){
        $read_times_total=$read_times_total+$raw->total;
    }

    $agency_news=getAgencyNewsCount();
    $agency_news_count=0;
    foreach($agency_news as $raw){
        $agency_news_count=$agency_news_count+$raw->total;
    }
    $db_stats=array("news_count_total"=>$agency_news_count, "news_count"=>$agency_news, "read_times"=>$read_times, "read_times_total"=>$read_times_total);
    }
    return array(
        "db_status"=>$db_status['status'],
        "db_stats"=>$db_stats
    );
}


function get_last_search($limit=10){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(
        array(
        ),
        array(
            'sort'=>
                array('_id'=> -1),
            'limit'=> $limit,
        )
    );
    $cursor = $manager->executeQuery('db.search', $query);
    return (array)$cursor->toArray();
}
?>
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

function catNews($filter){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(
            array(
                'categories'=>$filter
            ),
            array(
                'projection'=>array(
                    "id"=>1,
                    "agency"=>1, 
                    "agency_title"=>1, 
                    "categories"=>1, 
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
            array("id"=>1)
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

function getSearchResult($arg){
    $status=false;
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $query = new MongoDB\Driver\Query(
    array(
        'visible'=>true,
        'text'=>
        array('$regex'=>$arg, '$options'=>'i')
        ),
        // get just id field
    array(
        'limit'=>20,
        'projection'=>
        array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1)
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

function saveSearch($data){
    if(strlen($data)>=3){
        if(!checkSearch($data)){
            $data=array("keyword"=>$data);
            $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($data);
            $manager->executeBulkWrite('db.search', $bulk);
            return true;
        }
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
            $time=time();
            $maxTime=$time-7*24*60*60;
            $query = new MongoDB\Driver\Query(
            array(
                'date_u'=>
                    array('$gte'=>$maxTime, '$lt'=>$time)
            ),
            array(
                'sort'=>
                    array('read_times'=> -1),
                'limit'=>$limit,
                'projection'=>
                            array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1)
                )
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
        break;
        case "month":
            $range=rangeMonthThis();
            $query = new MongoDB\Driver\Query(
            array(
                'date_u'=>
                    array('$gte'=>$range['start'], '$lt'=>$range['end'])
            ),
            array(
                'sort'=>
                    array('read_times'=> -1),
                'limit'=>$limit,
                'projection'=>
                        array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1)
                )
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
        break;
        case "today":
            $today=strtotime(date("d.m.Y"));
            $query = new MongoDB\Driver\Query(
            array(
                "date_u"=>array('$gt'=>$today)
            ),
            array(
                'sort'=>
                    array('read_times'=> -1),
                'limit'=>$limit,
                'projection'=>
                    array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1)
                )
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
        break;
        default:
            $query = new MongoDB\Driver\Query(
                array(),
                array(
                    'limit'=>$limit,
                    'sort'=>
                        array('read_times'=> -1),
                        'projection'=>
                            array("id"=>1, "title"=>1, "seo_link"=>1, "image"=>1, "read_times"=>1, "seo_url"=>1, "agency_title"=>1, "agency"=>1, "date_u"=>1, "date"=>1, "spot"=>1)
                        )
    
            );
            $cursor = $manager->executeQuery('db.news', $query);
            return (array)$cursor->toArray();
    }
}

?>
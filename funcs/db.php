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

function getSavedCategory(){
    $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
    $cmd = new MongoDB\Driver\Command([
        // build the 'distinct' command
       'distinct' => 'news', // specify the collection name
       'key' => 'category' // specify the field for which we want to get the distinct values
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
    if(isset($data)){
        return array("result"=>$data, "status"=>true, "desc"=>"From db");
    }
    return array("result"=>null, "status"=>false, "desc"=>"not found in db");
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
            '$project'=>
                array("id"=>1)
            )
        );
        $cursor = $manager->executeQuery('db.news', $query);
        $data=count((array)$cursor->toArray()[0]);
        if($data>=1){
            $status=true;
            return $status;
        }
    return $status;
}

function saveDatabase($agency, $data){
    global $new_id;
    if(!checkNew($agency, $new_id)){
            $manager = new MongoDB\Driver\Manager($_ENV["mongo_conn"]);
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($data);
            $manager->executeBulkWrite('db.news', $bulk);
            return true;
    }
    return false;

}

?>
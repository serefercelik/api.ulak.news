<?php
    set_time_limit(300);

    header('Content-Type: application/json; charset=utf-8');
    include("env.php");
    if(isset($_GET['key']) && $_GET['key']===$_ENV['cron_key']){

		///// CACHE //////////
			require_once "sCache.php";
			$options = array(
				'time'   => 1200, // 60 saniye
				'dir'    => 'cache/', // sCache2 klasörü oluşturup buraya yazılsın.
				'load'   => false,  // sayfamızın sonunda load değerimiz görünsün.
				'extension' => ".json", // standart değer .html olarak ayarlanmıştır cache dosyalarınızın uzantısını temsil etmektedir.
				);
			
			$sCache = new sCache($options); // ayarları sınıfımıza gönderip sınıfı çalıştıralım.
		///// CACHE BITIS /////
	
    $opts = [
        "http" => [
            "method" => "GET",
            "header" =>
                'X-Site: '.$_ENV["curl-auth-web"]."\r\n".
                'X-Site-Token: '.$_ENV["curl-auth-token"]."\r\n"
        ]
    ];
    $context = stream_context_create($opts);

    function api_conn($url){
        global $context;
        return json_decode(file_get_contents($url, false, $context), true);
    }

    function get_news($agency, $limit=1000, $start=0){
        if($agency==="list"){
            $agency="all";
        }
        $curl=api_conn("https://api.ulak.news/?agency=$agency&limit=$limit&start=$start");
        if($curl['status']){
                return $curl['result'];
        }
        return false;
    }

    function get_new($agency, $id){
        $curl=api_conn("https://api.ulak.news/?agency=$agency&id=$id");
        if($curl['status']){
                return $curl;
        }
        return false;
    }
    $all_news=get_news("all");
    $true=0;
    $false=0;
    $added=0;
    foreach($all_news as $key=>$new){
       $go=get_new($new['agency'], $new['id']);
       if($go['status']){
            if($go['result']['read_times']===1){
                $true=$true+1;
            }
       }else{
            $false=$false+1;
       }
    };
    $result=array(
        "time"=>time(),
        "cached_time"=>time()+$options['time'],
        "news"=>count($all_news),
        "true"=>$true,
        "false"=>$false
    );
    echo json_encode($result);
}
?>
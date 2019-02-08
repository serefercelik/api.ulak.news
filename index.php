<?php
header("Content-type:application/json");


include("funcs.php");
$cache_seconds=120;
$apikey_result=true;
// agency and new globals
$agency_process=false;
$agency=null;
$new_id=null;
$agency_new=false;
$saved_new=false;
$islem=null;
if(isset($_GET['agency'])){
    $agency_process=true;
    $agency=Sanitizer::alfabetico($_GET['agency']);
    if(isset($_GET['id'])){
        $agency_new=true;
        $new_id=Sanitizer::numerico($_GET['id']);
        $saved_new=checkNew($agency, $new_id);
        if($saved_new){
            read_new($agency, $new_id);
            $islem=get_new($agency, $new_id);
        }
    }
}

//get process
$get_process=false;
$process=null;
if(isset($_GET['process'])){
    $get_process=true;
    $process=Sanitizer::alfabetico($_GET['process']);
}


/// CACHE
$host=Sanitizer::url($_SERVER['HTTP_HOST']);
if($host!="127.0.0.1:3001" && $host!="127.0.0.1"){
    require_once "sCache.php";
    $options = array(
        'time'   => $cache_seconds, // 120 saniye yani 2 dakika
        'dir'    => 'cache/news', // sCache2 klasörü oluşturup buraya yazılsın.
        'buffer' => true, // html sayfalarımızın sıkıştırılmasını aktif edelim.
        'load'   => false,  // sayfamızın sonunda load değerimiz görünsün.
        'extension' => ".json", // standart değer .html olarak ayarlanmıştır cache dosyalarınızın uzantısını temsil etmektedir.
        );
    
    $sCache = new sCache($options); // ayarları sınıfımıza gönderip sınıfı çalıştıralım.
}
// CACHE FINISH

// Globals
$status=false;
$desc="İşlem Yok";
$time=time();
$result=null;


// Page agency
if($agency_process){
    // API KEY CHECK
    if($apikey_result){
        switch ($agency) {
            case "haberturk":
                    if($agency_new){
                            if(!$saved_new){
                                $islem=get_haberturk_new($new_id);
                            }
                            $status=$islem['status'];
                            $result=$islem['result'];
                            $desc=$islem['desc'];
                    }else{
                        $islem=get_haberturk();
                        $status=$islem['status'];
                        $desc=$islem['desc'];
                        $result=$islem['result'];
                    }
            break;
            case "odatv":
                if($agency_new){
                        if(!$saved_new){
                            $islem=get_odatv_new($new_id);
                        }
                        $status=$islem['status'];
                        $result=$islem['result'];
                        $desc=$islem['desc'];
                }else{
                        $islem=get_odatv();
                    $status=$islem['status'];
                    $desc=$islem['desc'];
                    $result=$islem['result'];
                }
            break;
            case "sputnik":
                if($agency_new){
                        if(!$saved_new){
                            $islem=get_sputnik_new($new_id);
                        }
                        $status=$islem['status'];
                        $result=$islem['result'];
                        $desc=$islem['desc'];
                }else{
                    $islem=get_sputnik();
                    $status=$islem['status'];
                    $desc=$islem['desc'];
                    $result=$islem['result'];
                }
            break;
            case "sozcu":
                if($agency_new){
                        if(!$saved_new){
                            $islem=get_sozcu_new($new_id);
                        }
                        $status=$islem['status'];
                        $result=$islem['result'];
                        $desc=$islem['desc'];
                }else{
                    $islem=get_sozcu();
                    $status=$islem['status'];
                    $desc=$islem['desc'];
                    $result=$islem['result'];
                }
            break;
            case "all":
                    $islem_haberturk=get_haberturk();
                    $islem_odatv=get_odatv();
                    $islem_sputnik=get_sputnik();
                    $islem_sozcu=get_sozcu();
                    $all=array_merge($islem_haberturk['result'], $islem_odatv['result'], $islem_sputnik['result'], $islem_sozcu['result']);
                    if($islem_haberturk['status']===true && $islem_odatv['status']===true && $islem_sozcu['status']===true && $islem_sputnik['status']===true){
                        shuffle($all);
                        $status=true;
                        $desc="Okey";
                        $result=$all;
                    }else{
                        $desc="Ajans ile bağlantı kurulamadı.";
                    }
    
            break;
            case "list":
                $status=true;
                $desc= "Listelendi.";
                $result=array(
                    "haberturk"=>array(
                        "image"=>getImage("haberturk"), 
                        "about"=>"Habertürk, Ciner Yayın Holding bünyesinde 1 Mart 2009 tarihinde yayın hayatına başlayan günlük gazeteydi. Son sayısı 5 Temmuz 2018'de çıktı. "),
                    "odatv"=>array(
                        "image"=>getImage("odatv"), 
                        "about"=>"Odatv.com, Odatv ya da odaᵀⱽ, 2007 yılında haber portalı olarak yayın yaşamına başlayan Web sitesi. İmtiyaz sahibi kişisi Soner Yalçın'dır. "),
                    "sputnik"=>array(
                        "image"=>getImage("sputnik"), 
                        "about"=>"Sputnik, 10 Kasım 2014'te Rossiya Segodnya tarafından kurulan Moskova merkezli uluslararası medya kuruluşu. Dünyanın farklı bölgelerinde ofisleri bulunmaktadır. Sputnik, yayınlarını 34 ülkeyi kapsayan 130 şehirde, günde toplam 800 saatin üzerinde internet sitesinden ve radyo istasyonlarından yapar. "),
                    "sozcu"=>array(
                        "image"=>getImage("sozcu"), 
                        "about"=>"Sözcü, 27 Haziran 2007 yılında merkezi İstanbul olmak üzere kurulmuş gazete.")
                );
                if(isset($_GET['filter'])){
                    if(isset($result[$_GET['filter']])){
                        $result=$result[$_GET['filter']];
                    }else{
                        $status=false;
                        $desc="Sonuç bulunamadı.";
                        $result=null;
                    }
                }
            break;
            default:
                $desc="Eksik veya hatalı işlem";
        }
    }
    // API KEY CHECK FINISH
}else
// Page agency
if($get_process){
    switch ($process) {
        case "getCategories":
            $status=true;
            $desc="Listed Categories";
            $result=getSavedCategories();
            break;
        default:
            $desc="Eksik veya hatalı işlem";
    }
}else{
    $desc="Eksik veya hatalı işlem";
}
// result
$resultPage=array(
    "status"=>$status,
    "desc"=>$desc,
    "cached_time"=>$time,
    "availability"=>$time+$cache_seconds,
    "result"=>$result,
    "get"=>$_GET
);
echo json_encode($resultPage);
?>
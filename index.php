<?php
ignore_user_abort(true);
set_time_limit(0);


header("Content-type:application/json");

require_once 'S3.php';
use UlakNews\S3;

$s3 = new S3('ulaknews-images');

include("funcs.php");

$apikey_result= $is_local ? true : checkToken();

$cache_seconds=120;
// agency and new globals
$agency_process=false;
$agency=null;
$new_id=null;
$agency_new=false;
$saved_new=false;
$islem=null;
$limit=0; // 0 unlimited
$start=0; // start from
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

if(isset($_GET['limit'])){
    $limit=Sanitizer::numerico($_GET['limit']);
}
if(isset($_GET['start'])){
    $start=Sanitizer::numerico($_GET['start']);
}
 
//get process
$get_process=false;
$process=null;
if(isset($_GET['process'])){
    $get_process=true;
    $process=Sanitizer::alfabetico($_GET['process']);
}

if(isset($_GET['process'])){
    if($_GET['process']==="getComments" || $_GET['process']==="saveComment"){
        $is_local=true;
    }
}

/// CACHE
if(!$is_local){
    require_once "sCache.php";
    $options = array(
        'time'   => $cache_seconds, // 120 saniye yani 2 dakika
        'dir'    => 'cache/news', // sCache2 klasörü oluşturup buraya yazılsın.
        'buffer' => false, // html sayfalarımızın sıkıştırılmasını aktif edelim.
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


if($apikey_result){
    // Page agency
    if($agency_process){
        // API KEY CHECK
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
                            if($limit>0){
                                $result=array_splice($result, $start, $limit);
                            }
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
                        if($limit>0){
                            $result=array_splice($result, $start, $limit);
                        }
                    }
                break;
                // case "sputnik":
                //     if($agency_new){
                //             if(!$saved_new){
                //                 $islem=get_sputnik_new($new_id);
                //             }
                //             $status=$islem['status'];
                //             $result=$islem['result'];
                //             $desc=$islem['desc'];
                //     }else{
                //         $islem=get_sputnik();
                //         $status=$islem['status'];
                //         $desc=$islem['desc'];
                //         $result=$islem['result'];
                //         if($limit>0){
                //             $result=array_splice($result, $start, $limit);
                //         }
                //     }
                // break;
                // case "cumhuriyet":
                // if($agency_new){
                //         if(!$saved_new){
                //             $islem=get_cumhuriyet_new($new_id);
                //         }
                //         $status=$islem['status'];
                //         $result=$islem['result'];
                //         $desc=$islem['desc'];
                // }else{
                //     $islem=get_cumhuriyet();
                //     $status=$islem['status'];
                //     $desc=$islem['desc'];
                //     $result=$islem['result'];
                //     if($limit>0){
                //         $result=array_splice($result, $start, $limit);
                //     }
                // }
                // break;
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
                        if($limit>0){
                            $result=array_splice($result, $start, $limit);
                        }
                    }
                break;
                // case "hackpress":
                //     if($agency_new){
                //             if(!$saved_new){
                //                 $islem=get_hackpress_new($new_id);
                //             }
                //             $status=$islem['status'];
                //             $result=$islem['result'];
                //             $desc=$islem['desc'];
                //     }else{
                //         $islem=get_hackpress();
                //         $status=$islem['status'];
                //         $desc=$islem['desc'];
                //         $result=$islem['result'];
                //         if($limit>0){
                //             $result=array_splice($result, $start, $limit);
                //         }
                //     }
                // break;
                case "diken":
                    if($agency_new){
                        if(!$saved_new){
                            $islem=get_diken_new($new_id);
                        }
                        $status=$islem['status'];
                        $result=$islem['result'];
                        $desc=$islem['desc'];
                    }else{
                        $islem=get_diken();
                        $status=$islem['status'];
                        $desc=$islem['desc'];
                        $result=$islem['result'];
                        if($limit>0){
                            $result=array_splice($result, $start, $limit);
                        }
                    }
                break;
                case "halkweb":
                    if($agency_new){
                        if(!$saved_new){
                            $islem=get_halkweb_new($new_id);
                        }
                        $status=$islem['status'];
                        $result=$islem['result'];
                        $desc=$islem['desc'];
                    }else{
                        $islem=get_halkweb();
                        $status=$islem['status'];
                        $desc=$islem['desc'];
                        $result=$islem['result'];
                        if($limit>0){
                            $result=array_splice($result, $start, $limit);
                        }
                    }
                break;
                case "all":
                    $all=[];
                        $islem_haberturk=get_haberturk();
                        if($islem_haberturk['status']){
                            foreach($islem_haberturk['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        $islem_odatv=get_odatv();
                        if($islem_odatv['status']){
                            foreach($islem_odatv['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        $islem_halkweb=get_odatv();
                        if($islem_halkweb['status']){
                            foreach($islem_halkweb['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        // $islem_sputnik=get_sputnik();
                        // if($islem_sputnik['status']){
                        //     foreach($islem_sputnik['result'] as $raw){
                        //         $all[]=$raw;
                        //     }
                        // }
                        $islem_sozcu=get_sozcu();
                        if($islem_sozcu['status']){
                            foreach($islem_sozcu['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        $islem_halkweb=get_halkweb();
                        if($islem_halkweb['status']){
                            foreach($islem_halkweb['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        $islem_diken=get_diken();
                        if($islem_diken['status']){
                            foreach($islem_diken['result'] as $index =>$raw){
                                $all[]=$raw;
                                if($index === 1){
                                break;
                                }
                            }
                        }
                            $sortArray = array();
                            foreach($all as $person){ 
                                foreach($person as $key=>$value){ 
                                    if(!isset($sortArray[$key])){ 
                                        $sortArray[$key] = array(); 
                                    } 
                                    $sortArray[$key][] = $value; 
                                } 
                            }
                            $orderby = "date_u"; //change this to whatever key you want from the array 
                            array_multisort($sortArray[$orderby],SORT_DESC,$all); 
                            $status=true;
                            $desc="Haberler listelendi.";
                            $result=$all;
                            if($limit>0){
                                $result=array_splice($result, $start, $limit);
                            }
                            if($result===null){
                                $desc="null";
                            }
        
                break;
                case "allKron":
                    $all=[];
                        $islem_haberturk=get_haberturk();
                        if($islem_haberturk['status']){
                            foreach($islem_haberturk['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        $islem_odatv=get_odatv();
                        if($islem_odatv['status']){
                            foreach($islem_odatv['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        // $islem_sputnik=get_sputnik();
                        // if($islem_sputnik['status']){
                        //     foreach($islem_sputnik['result'] as $raw){
                        //         $all[]=$raw;
                        //     }
                        // }
                        $islem_sozcu=get_sozcu();
                        if($islem_sozcu['status']){
                            foreach($islem_sozcu['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        $islem_halkweb=get_halkweb();
                        if($islem_halkweb['status']){
                            foreach($islem_halkweb['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                        // $islem_cumhuriyet=get_cumhuriyet();
                        // if($islem_cumhuriyet['status']){
                        //     foreach($islem_cumhuriyet['result'] as $raw){
                        //         $all[]=$raw;
                        //     }
                        // }
                        $islem_diken=get_diken();
                        if($islem_diken['status']){
                            foreach($islem_diken['result'] as $raw){
                                $all[]=$raw;
                            }
                        }
                            $sortArray = array();
                            foreach($all as $person){ 
                                foreach($person as $key=>$value){ 
                                    if(!isset($sortArray[$key])){ 
                                        $sortArray[$key] = array(); 
                                    } 
                                    $sortArray[$key][] = $value; 
                                } 
                            } 
                            $orderby = "date_u"; //change this to whatever key you want from the array 
                            array_multisort($sortArray[$orderby],SORT_DESC,$all); 
                            $status=true;
                            $desc="Haberler listelendi.";
                            $result=$all;
                            if($limit>0){
                                $result=array_splice($result, $start, $limit);
                            }
                            if($result===null){
                                $desc="null";
                            }
        
                break;
                case "list":
                    $status=true;
                    $desc= "Listelendi.";
                    $result=array(
                        "sozcu"=>array(
                            "title"=>"Sözcü",
                            "image"=>getImage("sozcu"),
                            "seo_link"=>'kaynak_sozcu.html',
                            "about"=>"Sözcü, 27 Haziran 2007 yılında merkezi İstanbul olmak üzere kurulmuş gazete."),
                        "diken"=>array(
                            "title"=>"Diken",
                            "image"=>getImage("diken"),
                            "seo_link"=>'kaynak_diken.html',
                            "about"=>"Diken’in misyonu net ve kısa: Ülkemizde gül bahçesine dönüştürülmek istenen medyanın dikeni olup, köklerinden sallanmaya başlayan demokrasimizi, temel özgürlüklerimizi ve laikliği savunmak. Bu misyonu yerine getirirken de gazetecilik mesleğine hak ettiği itibar ve onuru yeniden kazandırmak."),
                        // "cumhuriyet"=>array(
                        //     "title"=>"Cumhuriyet",
                        //     "image"=>getImage("cumhuriyet"),
                        //     "seo_link"=>'kaynak_cumhuriyet.html',
                        //     "about"=>"Cumhuriyet Gazetesi, \"amacını toplum yaşamına katıldığı 7 Mayıs 1924'te yayınladığı ilk sayısında kurucusu Yunus Nadi'nin kalemiyle belirlemiştir. Cumhuriyet, ne hükümet ne de parti gazetesidir. Cumhuriyet yalnız Cumhuriyet'in, bilimsel ve yaygın anlatımıyla demokrasinin savunucusudur. "),
                        "odatv"=>array(
                            "title"=>"Odatv",
                            "image"=>getImage("odatv"),
                            "seo_link"=>'kaynak_odatv.html',
                            "about"=>"Odatv.com, Odatv ya da odaᵀⱽ, 2007 yılında haber portalı olarak yayın yaşamına başlayan Web sitesi. İmtiyaz sahibi kişisi Soner Yalçın'dır. "),
                        "haberturk"=>array(
                            "title"=>"Haber Türk",
                            "image"=>getImage("haberturk"),
                            "seo_link"=>'kaynak_haberturk.html',
                            "about"=>"Habertürk, Ciner Yayın Holding bünyesinde 1 Mart 2009 tarihinde yayın hayatına başlayan günlük gazeteydi. Son sayısı 5 Temmuz 2018'de çıktı. "),
                        "haberturk"=>array(
                            "title"=>"HalkWeb",
                            "image"=>getImage("halkweb"),
                            "seo_link"=>'kaynak_halkweb.html',
                            "about"=>"Halkweb, halkweb.com.tr Genel Yayın Yönetmeni Orhan ŞAHİN"),
                        // "sputnik"=>array(
                        //     "title"=>"Sputnik",
                        //     "image"=>getImage("sputnik"),
                        //     "seo_link"=>'kaynak_sputnik.html',
                        //     "about"=>"Sputnik, 10 Kasım 2014'te Rossiya Segodnya tarafından kurulan Moskova merkezli uluslararası medya kuruluşu. Dünyanın farklı bölgelerinde ofisleri bulunmaktadır. Sputnik, yayınlarını 34 ülkeyi kapsayan 130 şehirde, günde toplam 800 saatin üzerinde internet sitesinden ve radyo istasyonlarından yapar. "),
                        // "hackpress"=>array(
                        //     "title"=>"Hack Press",
                        //     "image"=>getImage("hackpress"),
                        //     "seo_link"=>'kaynak_hackpress.html',
                        //     "about"=>"Hack Press, siber güvenlik dünyasındaki gelişmelerden kullanıcıları haberdar etmek, siber dünyanın tehlikelerinden nasıl korunulması gerektiğini; açık ve anlaşılır bir üslupla içerikler yayınlayarak kullanıcılara aktarmak ve toplumda \"siber güvenlik\" kavramının yer tutmasını sağlamak amaçlarını, ilke edinen bir haber platformudur."),
                    );
                    if(isset($_GET['filter'])){
                        if(isset($result[$_GET['filter']])){
                            $result=$result[Sanitizer::alfabetico($_GET['filter'])];
                        }else{
                            $desc="Sonuç bulunamadı.";
                            $result=null;
                        }
                    }
                break;
                default:
                    $desc="Eksik veya hatalı işlem";
            }
        // API KEY CHECK FINISH
    }else
    // Page agency
    if($get_process){
        switch ($process) {
            case "cats":
                $status=true;
                $desc="Listed Categories";
                $result=getSavedCategories();
                $cats=null;
                foreach($result as $cat){
                    $cats[]=array("cat"=>$cat, "seo_link"=>seolinkCat($cat), "cat_icon"=>caticon($cat));
                }
                if($limit>0){
                    $cats=array_splice($cats, $start, $limit);
                }
                $result=$cats;
                break;
            case "search":
                $filter=Sanitizer::alfanumerico($_GET['filter'], true, true);
                $status=true;
                $desc="Search result listed";
                $result=getSearchResult($filter, $limit);
                if(!$result){
                    $desc="Lütfen aradığınız kelimeyi kontrol edin.";
                }else{
                    $sortArray = array();
                    foreach($result as $person){ 
                        foreach($person as $key=>$value){ 
                            if(!isset($sortArray[$key])){ 
                                $sortArray[$key] = array(); 
                            } 
                            $sortArray[$key][] = $value; 
                        } 
                    }
                    $orderby = "date_u"; //change this to whatever key you want from the array 
                    array_multisort($sortArray[$orderby], SORT_DESC, $result);
                }
                break;
            case "mostRead":
                if(isset($_GET['filter'])){
                    $filter=Sanitizer::alfabetico($_GET['filter']);
                    $status=true;
                    if($limit>0){
                        $result=mostRead($filter, $limit);
                    }else{
                        $result=mostRead($filter);
                    }
                    $desc="most readed news filtered by $filter";
                }else{
                    $status=true;
                    $desc="most readed news filtered by all";
                    $result=mostRead("all");
                }
                break;
            case "catNews":
                if(isset($_GET['filter'])){
                    $filter=Sanitizer::toCat($_GET['filter'], true, true);
                    $status=true;
                    $result=catNews($filter);
                    $sortArray = array();
                    foreach($result as $person){ 
                        foreach($person as $key=>$value){ 
                            if(!isset($sortArray[$key])){ 
                                $sortArray[$key] = array(); 
                            } 
                            $sortArray[$key][] = $value; 
                        } 
                    }
                    $orderby = "date_u"; //change this to whatever key you want from the array 
                    array_multisort($sortArray[$orderby], SORT_DESC, $result); 
                    if($limit>0){
                        $result=array_splice($result, $start, $limit);
                    }
                    $desc="news filtered by $filter";
                }else{
                    $desc="no filter query";
                }
                break;
            case "saveComment":
                if(isset($_POST['toAgency']) && isset($_POST['toId']) && isset($_POST['text']) && isset($_POST['name']) && isset($_POST['ip'])){
                    $process=saveComment($_POST['toAgency'], $_POST['toId'], $_POST['text'], $_POST['name'], $_POST['ip']);
                    if($process){
                        $status=true;
                        $desc="Yorum Kaydedildi.";
                    }else{
                        $desc="İşlem başarısız.";
                    }
                }else{
                    $desc="Eksik işlem";
                }
                break;

            case "getComments":
                if(isset($_GET['toAgency']) && isset($_GET['toId'])){
                    $result=get_comment($_GET['toAgency'], $_GET['toId']);
                    if($result['status']){
                        $status=true;
                        $desc="Yorumlar";
                        $result=$result['result'];
                    }else{
                        $result=[];
                        $desc="İşlem başarısız.";
                    }
                }else{
                    $desc="Eksik işlem";
                }
                break;
            case "lastSearch":
                $result=get_last_search();
                $status=true;
                $desc="Last Search listed.";
                break;
            case "stats":
                $result=get_db_stats($limit);
                $status=true;
                $desc="Server status loaded.";
                break;
            default:
                $desc="Eksik veya hatalı işlem";
        }
    }else{
        $desc="Eksik veya hatalı işlem";
    }
}else{
    $desc="Please send token";
}

// result
$resultPage=array(
    "status"=> $status,
    "desc"=> $desc,
    "cached_time"=> $time,
    "availability"=> $time+$cache_seconds,
    "result"=> $result,
    "get"=> $_GET,
    "post"=> $_POST
);
echo json_encode($resultPage);
?>
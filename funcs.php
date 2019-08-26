<?php
date_default_timezone_set('Europe/Istanbul');

$is_local=false;
// env file
include("env.php");

/**
 * haber içeriklerinde izin verilen tagler
 */
$allowed_tags="<strong><p><h2><h3><h4><h5><span><br><br/><img><style><center><blockquote>";

/**
 * host bilgisi alıyoruz.
 */
$host=Sanitizer::url($_SERVER['HTTP_HOST']);

/**
 * eğer projemiz localde çalışıyor ise local database işlem yapmak için.
 */
if($host===$_ENV['local1'] || $host===$_ENV['local2']){
    $is_local=true;
    $_ENV["mongo_conn"]="mongodb://localhost";
}


/**
 * ajanslardan gelen çoklu gereksiz br etiketlerine karşı :)
 */
function multiBrClear($text){
    return preg_replace('#(<\s*br[^/>]*/?\s*>\s*){2,}#is',"<br />", nl2br($text));
}

/**
 * haberler için seo link oluşturucu title, agency ve id ye göre
 */
function seolink($s, $agency, $id){
    $s  = html_entity_decode($s);
    $tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',':',',', "'", "!",'’','#',"'",'&039;','"','“','.','…','?','‘','”');
    $eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','','','','','','','','','','','','','','');
    $s = str_replace($tr,$eng,$s);
    $s = strtolower($s);
    $s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
    $s = preg_replace('/\s+/', '-', $s);
    $s = preg_replace('|-+|', '-', $s);
    $s = preg_replace('/#/', '', $s);
    $s = str_replace('.', '', $s);
    $s = trim($s, '-');
    $s = substr($s, 0, 32);
    return "haber_".$s."_".$agency."_".$id.".html";
}

/**
 * kategoriler için seo link oluşturucu.
 */
function seolinkCat($s){
    $s=Sanitizer::alfabetico($s, true, true);
    $s=base64_encode($s);
    return "kategori.html?kategori=".$s;
}

/**
 * kategoriler için icon listeleme.
 */
function caticon($s){
    $s=Sanitizer::alfabetico($s, true, true);
    $s=base64_encode($s);
    $db=getIconDB($s);
    if($db){
        return $db['icon'];
    }
    return "https://api.ulak.news/images/web/noCat.png";
}

/**
 * keyword oluşturucu
 */
function keywords($s){
    return str_replace(array(' ', '!', '.', '”','“',',,','’','\n',"'"), array(', ', '','','','','','','',''), strtolower(strip_tags($s)));
}

/**
 * token kontrol.
 */
function checkToken(){
    global $apikey_result;
        if(isset($_SERVER['HTTP_X_SITE']) && isset($_SERVER['HTTP_X_SITE_TOKEN'])){
            if(isset($_SERVER['HTTP_X_SITE_BYPASS']) && $_SERVER['HTTP_X_SITE_BYPASS']==$_ENV['bypass-token']){
                return true;
            }
            $site=Sanitizer::url($_SERVER['HTTP_X_SITE']);
            $token=Sanitizer::alfanumerico($_SERVER['HTTP_X_SITE_TOKEN']);
            if($_ENV['site']===$site && $_ENV['token']===$token){
                return true;
            }
        }
    return false;
}

/**
 * bu ayın ilk gününün tarihini ve son gününün tarihini verir.
 */
function rangeMonthThis (){
    return array (
      "start" => (int)strtotime(date("Ym01")),
      "end" => (int)strtotime(date("Ymt"))
    );
}

/**
 * son haftanın başlangıç ve bitiş tarihlerini verir.
 */
function getLastWeekDates(){
    $lastWeek = array();
    $prevMon = abs(strtotime("previous monday"));
    $currentDate = abs(strtotime("today"));
    $seconds = 86400; //86400 seconds in a day
 
    $dayDiff = ceil( ($currentDate-$prevMon)/$seconds ); 
 
    if( $dayDiff < 7 )
    {
        $dayDiff += 1; //if it's monday the difference will be 0, thus add 1 to it
        $prevMon = strtotime( "previous monday", strtotime("-$dayDiff day") );
    }
 
    $prevMon = date("Y-m-d",$prevMon);
 
    // create the dates from Monday to Sunday
    for($i=0; $i<7; $i++)
    {
        // edited => haftanın ilk ve sadece son timestamp i ni array içine gönderiyoruz.
        if($i===0 || $i===6){
            $d = date(strtotime( $prevMon." + $i day") );
            $lastWeek[]=(int)$d;
        }
    }
 
    return $lastWeek;
}

/**
 * haftanın ilk ve son tarihlerini verir.
 */
function rangeWeek ($datestr) {
    date_default_timezone_set (date_default_timezone_get());
    $dt = strtotime ($datestr);
    return array (
      "start" => date ('N', $dt) == 1 ? $dt :strtotime ('last monday', $dt),
      "end" => date('N', $dt) == 7 ? $dt :strtotime ('next sunday', $dt)
    );
}

/**
 * tarihden unix time a çevirir.
 */
function getUnixTime ($dateStr){
    $date=new DateTime($dateStr, new DateTimeZone("Europe/Istanbul"));
    return (int)$date->format('U');
}

class Sanitizer {
    /**
    * @param str => $email = email a ser sanitizado
    */
    public static function email($email){
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    * @param bol => $allow_accents = permitir acentos
    * @param bol => $allow_spaces = permitir espaços
    */
    public static function alfabetico($valor, bool $allow_accents = true, bool $allow_spaces = false){
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        if(!$allow_accents && !$allow_spaces){
            return preg_replace('#[^A-Za-z]#', '', $valor);
        }
        if($allow_accents && !$allow_spaces){
            return preg_replace('#[^A-Za-zà-źÀ-Ź]#', '', $valor);
        }
        if(!$allow_accents && $allow_spaces){
            return preg_replace('#[^A-Za-z ]#', '', $valor);
        }
        if($allow_accents && $allow_spaces){
            return preg_replace('#[^A-Za-zà-źÀ-Ź ]#', '', $valor);
        }                
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function toCat($valor){
        return preg_replace('#[^A-Za-zà-źÀ-Ź ]#', ' ', $valor);
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function alfanumerico($valor, bool $allow_accents = true, bool $allow_spaces = false){
        if(is_array($valor) || is_object($valor)){
            return false;
        }
        $valor = str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor));
        if(!$allow_accents && !$allow_spaces){
            return preg_replace('#[^A-Za-z0-9]#', '', $valor);
        }
        if($allow_accents && !$allow_spaces){
            return preg_replace('#[^A-Za-zà-źÀ-Ź0-9]#', '', $valor);
        }
        if(!$allow_accents && $allow_spaces){
            return preg_replace('#[^A-Za-z0-9 ]#', '', $valor);
        }
        if($allow_accents && $allow_spaces){
            return preg_replace('#[^A-Za-zà-źÀ-Ź0-9 ]#', '', $valor);
        }
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function numerico($valor){
        if(is_array($valor) || is_object($valor)){
            return false;
        }
        return (int)preg_replace('/\D/', '', $valor);
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function integer($valor){
        if(is_array($valor) || is_object($valor)){
            return false;
        }
        return (int)$valor;
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function float($valor){
        if(is_array($valor) || is_object($valor)){
            return false;
        }
        return (float)$valor;
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function money($valor){
        if(is_array($valor) || is_object($valor)){
            return false;
        }
        $valor = preg_replace('/\D/', '', $valor);
        if(strlen($valor) < 3){
            $valor = substr($valor, 0, strlen($valor)).'.00';
            return (float)$valor; 
        }
        if(strlen($valor) > 2){
            $valor = substr($valor, 0, (strlen($valor)-2)).'.'.substr($valor, (strlen($valor)-2));
            return (float)$valor; 
        }
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function url($valor){
        if(is_array($valor) || is_object($valor)){
            return false;
        }
        $valor = strip_tags(str_replace(array('"', "'", '`', '´', '¨'), '', trim($valor)));
        return filter_var($valor, FILTER_SANITIZE_URL);
    }
}

// Functions
include("funcs/db.php");

/**
 * yapım aşamasında olan resim kaydetme.
 */
function saveImage($link){
    // save to images/web/$agency/$id.jpg
}

/**
 * haber kaynaklarının verdiği kategori id lerinin hangi kategori olduğunun sonucunu verir.
 */
function getCategorie($agency, $id){
    $cat=null;
    $result=null;
    if($agency==="odatv"){
        $cat='[{ "id": "1","ismi": "Siyaset" }, { "id": "2","ismi": "Analiz" }, { "id": "3","ismi": "Ekonomi" }, { "id": "4","ismi": "Medya" }, { "id": "5","ismi": "Spor" }, { "id": "6","ismi": "Magazin" }, { "id": "7","ismi": "Kültür Sanat" }, { "id": "8","ismi": "Güncel" }, { "id": "9","ismi": "Odatv Yazılar" }, { "id": "10","ismi": "Tüm Manşetler" }, { "id": "11","ismi": "En Çok Okunanlar" }]';
        foreach(json_decode($cat, true) as $line){
            if($id==$line['id']){
                $result=$line['ismi'];
            }
        }
        if(!isset($result)){
            return "Odatv Kategorisiz";
        }
    }elseif($agency==="sputnik"){
        $cat='[{"ismi":"T\u00fcrkiye","id":111102},{"ismi":"D\u00fcnya","id":111103},{"ismi":"Rusya","id":111114},{"ismi":"Avrupa","id":111115},{"ismi":"Do\u011fu Akdeniz","id":111116},{"ismi":"Ortado\u011fu","id":111117},{"ismi":"ABD","id":111118},{"ismi":"G\u00fcney Amerika","id":111119},{"ismi":"Asya & Pasifik","id":111120},{"ismi":"Afrika","id":111121},{"ismi":"Politika","id":111104},{"ismi":"Ekonomi","id":111105},{"ismi":"Savunma","id":111106},{"ismi":"Ya\u015fam","id":111107},{"ismi":"Bilim","id":111122},{"ismi":"\u00c7evre","id":111123},{"ismi":"K\u00fclt\u00fcr & Sanat","id":111124},{"ismi":"Spor","id":111125},{"ismi":"Analiz","id":111112},{"ismi":"R\u00f6portajlar","id":111128},{"ismi":"G\u00f6r\u00fc\u015f","id":111129},{"ismi":"Rus medyas\u0131","id":111126}]';
        foreach(json_decode($cat, true) as $line){
            if($id==$line['id']){
                $result=$line['ismi'];
            }
        }
        if(!isset($result)){
            return null;
        }
    }elseif($agency==="cumhuriyet"){
        return explode(', ', $id);
    }elseif($agency==="diken"){
        $cat='[{"id":21,"ismi":"9 Soruda"},{"id":6353,"ismi":"Advertorial"},{"id":12,"ismi":"Agora"},{"id":16,"ismi":"Ak\u015fam bask\u0131s\u0131"},{"id":14,"ismi":"Akt\u00fcel"},{"id":17,"ismi":"An itibar\u0131yla"},{"id":9,"ismi":"Analiz"},{"id":6356,"ismi":"Anket"},{"id":20,"ismi":"Astroloji"},{"id":827,"ismi":"Ba\u015f yaz\u0131"},{"id":287,"ismi":"Bir \u0130nsan"},{"id":18,"ismi":"Bir rakam bir insan"},{"id":288,"ismi":"Bir Say\u0131"},{"id":828,"ismi":"Bir s\u00f6z"},{"id":4349,"ismi":"Brasil2014"},{"id":6352,"ismi":"Brexit"},{"id":6354,"ismi":"Destek k\u00f6\u015fesi"},{"id":11,"ismi":"Diken \u00f6zel"},{"id":3962,"ismi":"Diken TV"},{"id":1292,"ismi":"Diken de bu hafta"},{"id":321,"ismi":"Dikene tak\u0131lanlar"},{"id":6347,"ismi":"Dikenle 2015"},{"id":6351,"ismi":"Doktor K\u00f6\u015fesi"},{"id":13,"ismi":"D\u00fcnya"},{"id":1320,"ismi":"English"},{"id":15,"ismi":"G\u00fcn\u00fcn 11i"},{"id":7158,"ismi":"G\u00fcn\u00fcn eseri"},{"id":1521,"ismi":"G\u00fcn\u00fcn karesi"},{"id":6348,"ismi":"HAFTANIN SUYU"},{"id":19,"ismi":"Keyif"},{"id":216,"ismi":"Lorem ipsum"},{"id":366,"ismi":"Man\u015fet"},{"id":8,"ismi":"Medya"},{"id":6349,"ismi":"Panama Belgeleri"},{"id":6346,"ismi":"Perker"},{"id":7147,"ismi":"Sanat"},{"id":6350,"ismi":"Sarraf davas\u0131"},{"id":5676,"ismi":"Snowden"},{"id":6357,"ismi":"Spor"},{"id":1,"ismi":"Uncategorized"},{"id":6344,"ismi":"Vefat"},{"id":1111,"ismi":"Diken Vitrin"},{"id":6355,"ismi":"VPN Haber"}]';
        foreach(json_decode($cat, true) as $line){
            if($id==$line['id']){
                $result=$line['ismi'];
            }
        }
        if(!isset($result)){
            return null;
        }
        /**
         * diken ise direk result çıkarıyoruz...
         */
        return $result;
    }
    return Sanitizer::toCat($result, true, true);
}

/**
 * ilgili ajansa göre resmini getirir.
 */
function getImage($agency){
    $result;
    switch ($agency) {
        // HABERTURK
        case "odatv":
            return "https://api.ulak.news/images/web/odatv.png";
            break;
        case "haberturk":
            return "https://api.ulak.news/images/web/haberturk.png";
            break;
        case "sputnik":
            return "https://api.ulak.news/images/web/sputnik.png";
            break;
        case "sozcu":
            return "https://api.ulak.news/images/web/sozcu.png";
            break;
        case "cumhuriyet":
            return "https://api.ulak.news/images/web/cumhuriyet.png";
            break;
        case "hackpress":
            return "https://api.ulak.news/images/web/hackpress.svg";
            break;
        case "diken":
            return "https://api.ulak.news/images/web/diken.png";
            break;
        default:
            return "https://api.ulak.news/images/web/404.png";
    }
}

include("funcs/curls.php"); // curls
include("funcs/cumhuriyet.php"); // cumhuriyet funcs..
include("funcs/haberturk.php"); // haberturk funcs..
include("funcs/sozcu.php"); // sozcu funcs...
include("funcs/odatv.php"); // odatv funcs..
include("funcs/sputnik.php"); // sputnik funcs...
include("funcs/hackpress.php"); // hackpress funcs...
include("funcs/diken.php"); // diken funcs...


?>
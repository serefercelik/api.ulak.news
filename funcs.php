<?php
date_default_timezone_set('Europe/Istanbul');
// env file
include("env.php");

$allowed_tags="<strong><p><h2><h3><h4><h5><span><br><br/><img><style><center><blockquote>";
$host=Sanitizer::url($_SERVER['HTTP_HOST']);
if($host===$_ENV['local1'] || $host===$_ENV['local2']){
    $is_local=true;
    $_ENV["mongo_conn"]="mongodb://localhost";
}

function seolink($s, $agency, $id){
    $s  = html_entity_decode($s);
    $tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',':',',', "'", "!",'’','#',"'",'&039;','"','“','.','…','?','‘');
    $eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','','','','','','','','','','','','','');
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

function seolinkCat($s){
    $s=Sanitizer::alfabetico($s, true, true);
    $s=base64_encode($s);
    // $s  = html_entity_decode($s);
    // $tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',':',',', "'", "!",'’','#',"'",'&039;','"','“','.','…','?');
    // $eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','','','','','','','','','','','','');
    // $s = str_replace($tr,$eng,$s);
    // $s = strtolower($s);
    // $s = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $s);
    // $s = preg_replace('/\s+/', '-', $s);
    // $s = preg_replace('|-+|', '-', $s);
    // $s = preg_replace('/#/', '', $s);
    // $s = str_replace('.', '', $s);
    // $s = trim($s, '-');
    // $s = substr($s, 0, 32);
    return "kategori.html?kategori=".$s;
}

function keywords($s){
    return str_replace(array(' ', '!', '.', '”','“',',,','’','\n',"'"), array(', ', '','','','','','','',''), strtolower($s));
}


function checkToken(){
    global $apikey_result;
    $result=false;
        if(isset($_SERVER['HTTP_X_SITE']) && isset($_SERVER['HTTP_X_SITE_TOKEN'])){
            $site=Sanitizer::url($_SERVER['HTTP_X_SITE']);
            $token=Sanitizer::alfanumerico($_SERVER['HTTP_X_SITE_TOKEN']);
            if($_ENV['site']===$site && $_ENV['token']===$token){
                $result=true;
            }
        }
    return $result;
}

function rangeMonthThis (){
    date_default_timezone_set (date_default_timezone_get());
    $dt = time();
    return array (
      "start" => strtotime ('first day of this month', $dt),
      "end" => strtotime ('last day of this month', $dt)
    );
}

function rangeWeek ($datestr) {
    date_default_timezone_set (date_default_timezone_get());
    $dt = strtotime ($datestr);
    return array (
      "start" => date ('N', $dt) == 1 ? $dt :strtotime ('last monday', $dt),
      "end" => date('N', $dt) == 7 ? $dt :strtotime ('next sunday', $dt)
    );
}

  
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

function saveImage($link){
    // save to images/web/$agency/$id.jpg
}

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
            return "Sputnik Kategorisiz";
        }
    }
    return Sanitizer::toCat($result, true, true);
}

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
        default:
            return "https://api.ulak.news/images/web/404.png";
    }
}


include("funcs/curls.php"); // curls
include("funcs/haberturk.php"); // haberturk funcs..
include("funcs/sozcu.php"); // sozcu funcs...
include("funcs/odatv.php"); // odatv funcs..
include("funcs/sputnik.php"); // sputnik funcs...
                   


?>
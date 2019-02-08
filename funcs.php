<?php
// env file
include("env.php");
date_default_timezone_set('Europe/Istanbul');

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
    public static function alfanumerico($valor, bool $allow_accents = true, bool $allow_spaces = false){
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
        return (int)preg_replace('/\D/', '', $valor);
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function integer($valor){
        return (int)$valor;
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function float($valor){
        return (float)$valor;
    }

    /**
    * @param str => $valor = valor a ser sanitizado
    */
    public static function money($valor){
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
        $cat='[{ "id": "1","ismi": "Siyaset" }, { "id": "2","ismi": "Analiz" }, { "id": "3","ismi": "Ekonomi" }, { "id": "4","ismi": "Medya" }, { "id": "5","ismi": "Spor" }, { "id": "6","ismi": "Magazin" }, { "id": "7","ismi": "Kültür Sanat" }, { "id": "8","ismi": "Güncel" }, { "id": "9","ismi": "Yazarlar" }, { "id": "10","ismi": "Tüm Manşetler" }, { "id": "11","ismi": "En Çok Okunanlar" }]';
        foreach(json_decode($cat, true) as $line){
            if($id==$line['id']){
                $result=$line['ismi'];
            }
        }
    }elseif($agency==="sputnik"){
        $cat='[{"ismi":"T\u00fcrkiye","id":111102},{"ismi":"D\u00fcnya","id":111103},{"ismi":"Rusya","id":111114},{"ismi":"Avrupa","id":111115},{"ismi":"Do\u011fu Akdeniz","id":111116},{"ismi":"Ortado\u011fu","id":111117},{"ismi":"ABD","id":111118},{"ismi":"G\u00fcney Amerika","id":111119},{"ismi":"Asya & Pasifik","id":111120},{"ismi":"Afrika","id":111121},{"ismi":"Politika","id":111104},{"ismi":"Ekonomi","id":111105},{"ismi":"Savunma","id":111106},{"ismi":"Ya\u015fam","id":111107},{"ismi":"Bilim","id":111122},{"ismi":"\u00c7evre","id":111123},{"ismi":"K\u00fclt\u00fcr & Sanat","id":111124},{"ismi":"Spor","id":111125},{"ismi":"Analiz","id":111112},{"ismi":"R\u00f6portajlar","id":111128},{"ismi":"G\u00f6r\u00fc\u015f","id":111129},{"ismi":"Rus medyas\u0131","id":111126}]';
        foreach(json_decode($cat, true) as $line){
            if($id==$line['id']){
                $result=$line['ismi'];
            }
        }
    }
    return $result;
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
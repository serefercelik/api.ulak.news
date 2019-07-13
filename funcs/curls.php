<?php
function curl_function($url){
    $error=null;
    $ch = curl_init();
    $ip="127.0.0.1";
    $headers = array();
    $headers[] = "Authority: $url";
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
    $headers[] = 'Dnt: 1';
    $headers[] = "X-Forwarded-For: $ip";
    $headers[] = "Client-IP: $ip";
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com.tr');
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
    // -curl_setopt($ch,CURLOPT_HEADER, false); 
    $data=curl_exec($ch);
    $output=json_decode($data, true);
    $info = curl_getinfo($ch);
    if($info===false){
        $error=curl_error($ch);
    }
    curl_close($ch);
    if($info['http_code']!==200){
        return array("status"=>false, "result"=>$error);
    }
    return array("status"=>true, "result"=>$output);
}

function curl_function_odatv($url){
    $error=null;
    $ch = curl_init();
    $ip="127.0.0.1";
    $headers = array();
    $headers[] = "Authority: $url";
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
    $headers[] = 'Dnt: 1';
    $headers[] = "X-Forwarded-For: $ip";
    $headers[] = "Client-IP: $ip";
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com.tr');
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
    // curl_setopt($ch,CURLOPT_HEADER, false); 
    $data=curl_exec($ch);
    $output=json_decode($data, true);
    $info = curl_getinfo($ch);
    if($info===false){
        $error=curl_error($ch);
    }
    curl_close($ch);
    if($info['http_code']!==200){
        return array("status"=>false, "result"=>$error);
    }
    return array("status"=>true, "result"=>$output);
}

function curl_function_sozcu(){
    $url=$_ENV["get_sozcu"];
    $error=null;
    $ch = curl_init();
    $ip="127.0.0.1";
    $headers = array();
    $headers[] = "Authority: $url";
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
    $headers[] = 'Dnt: 1';
    $headers[] = "X-Forwarded-For: $ip";
    $headers[] = "Client-IP: $ip";
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
            "{$_ENV["get_sozcu_field"]}");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com.tr');
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
    // curl_setopt($ch,CURLOPT_HEADER, false); 
    $data=curl_exec($ch);
    $output=json_decode($data, true);
    $info = curl_getinfo($ch);
    if($info===false){
        $error=curl_error($ch);
    }
    curl_close($ch);
    if($info['http_code']!==200){
        return array("status"=>false, "result"=>$error);
    }
    return array("status"=>true, "result"=>$output);
}

function curl_function_sozcu_new($new_id){
    $url=$_ENV['get_sozcu_new'];
    $error=null;
    $ch = curl_init();
    $ip="127.0.0.1";
    $headers = array();
    $headers[] = "Authority: $url";
    $headers[] = 'Pragma: no-cache';
    $headers[] = 'Cache-Control: no-cache';
    $headers[] = 'Upgrade-Insecure-Requests: 1';
    $headers[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
    $headers[] = 'Dnt: 1';
    $headers[] = "X-Forwarded-For: $ip";
    $headers[] = "Client-IP: $ip";
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
            $_ENV['get_sozcu_new_field']."&post_id=$new_id");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_REFERER, 'https://www.google.com.tr');
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 5000);
    // curl_setopt($ch,CURLOPT_HEADER, false); 
    $data=curl_exec($ch);
    $output=json_decode($data, true);
    $info = curl_getinfo($ch);
    if($info===false){
        $error=curl_error($ch);
    }
    curl_close($ch);
    if($info['http_code']!==200){
        return array("status"=>false, "result"=>$error);
    }
    return array("status"=>true, "result"=>$output);
}
?>
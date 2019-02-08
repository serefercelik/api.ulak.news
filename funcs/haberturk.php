<?php


        /// HABERTURK ///
        function get_haberturk(){
            $desc="İstediğiniz artık yok veya hatalı işlem.";
            $catNews=null;
            $status=false;
            $file=curl_function("{$_ENV["get_haberturk"]}");
            if($file['status']){
                $desc="İşlem sanırım tamam :)";
                $status=true;
                foreach($file['result']['items'] as $raw){
                    if(isset($raw['items'])){
                        foreach($raw['items'] as $itemRaw){
                            if(explode('/',$itemRaw['url'])[2]==="news"){
                                $catNews[]=array(
                                    "agency_title"=>"Haber Türk",
                                    "agency"=>"haberturk",
                                    "category"=>array($itemRaw['kategori_adi']),
                                    "id"=>(int)$itemRaw['haber_id'],
                                    "date_u"=>strtotime($itemRaw['giris_zamani']),
                                    "date"=>$itemRaw['giris_zamani'],
                                    "title"=>$itemRaw['haber_baslik'],
                                    "spot"=>$itemRaw['haber_spot'],
                                    "image"=>$itemRaw['mansetPhoto'],
                                    "url"=>$itemRaw['httpurl']
                                );
                            }
                        }
                    }
                }
            }else{
                $desc="Habertürk ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
            }
            return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
        }
        
        
        function get_haberturk_new($new_id){
            global $agency;
            $status=false;
            $result=null;
            $desc="İstediğiniz artık yok veya hatalı işlem.";
            $file=curl_function("{$_ENV["get_haberturk_new"]}?news_id={$new_id}");
            if($file['status']){
                $desc="İşlem sanırım tamam :)";
                $status=true;
                $news=$file['result'];
                $news_text=$news['haber_metin'];
                $text=null;
                $news_title;
                $news_date;
                $news_spot;
                $news_image;
                $news_cat=$news['kategori_adi'];
                foreach($news_text as $textRaw){
                    if($textRaw['type']==="text"){
                        $text.=$textRaw['text']." ";
                    }else
                    if($textRaw['type']==="mainimage"){
                        $news_image=$textRaw['imageUrl']." ";
                    }else
                    if($textRaw['type']==="spot"){
                        $news_spot=$textRaw['text']." ";
                    }else
                    if($textRaw['type']==="title"){
                        $news_title=$textRaw['text']." ";
                    }else
                    if($textRaw['type']==="share"){
                        $news_date=$textRaw['createTime']." ";
                    }
                }
                    //image check
                    if(isset($news_image)){
                        $news_image=$news_image;
                    }else{
                        $news_image="https://api.ulak.news/images/web/404.png";

                    }
                    $result=array(
                        "agency"=>"haberturk",
                        "agency_title"=>"Haber Türk",
                        "text"=>$text,
                        "category"=>array($news_cat),
                        "id"=>(int)$news['haber_id'],
                        "date_u"=>strtotime($news_date),
                        "date"=>$news_date,
                        "title"=>$news_title,
                        "spot"=>$news_spot,
                        "image"=>$news_image,
                        "url"=>$news['url'],
                        "read_times"=>0
                    );
                    saveDatabase($agency, $result);
            }else{
                $desc="Habertürk ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
            }
            return array("status"=>$status, "result"=>$result, "desc"=>$desc);
        }
                    /// HABERTÜRK BİTİŞ ///

?>
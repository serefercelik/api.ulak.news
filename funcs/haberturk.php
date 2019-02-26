<?php


        /// HABERTURK ///
        function get_haberturk(){
            $desc="İstediğiniz artık yok veya hatalı işlem.";
            $catNews=null;
            $status=false;
            $file=curl_function("{$_ENV["get_haberturk"]}");
            if($file['status']){
                $desc="from agency";
                $status=true;
                foreach($file['result']['items'] as $raw){
                    if(isset($raw['items'])){
                        foreach($raw['items'] as $itemRaw){
                            if(explode('/',$itemRaw['url'])[2]==="news"){
                                $rawDate=explode(' ',$itemRaw['giris_zamani']);
                                $rawDatee=explode('-', $rawDate[0]);
                                $news_date=$rawDatee[2].".".$rawDatee[1].".".$rawDatee[0]." ".$rawDate[1];
                                if($itemRaw['kategori_id']==="0"){
                                    $itemRaw['kategori_adi']="HT Yazı";
                                }
                                if($itemRaw['haber_spot']===""){
                                    $itemRaw['haber_spot']=$itemRaw['haber_baslik'];
                                }
                                $news_title=$itemRaw['haber_baslik'];
                                $new_id=(int)$itemRaw['haber_id'];
                                $catNews[]=array(
                                    "agency_title"=>"Haber Türk",
                                    "agency"=>"haberturk",
                                    "categories"=>array($itemRaw['kategori_adi']),
                                    "id"=>(int)$new_id,
                                    "date_u"=>getUnixTime($news_date),
                                    "date"=>$news_date,
                                    "title"=>$news_title,
                                    "seo_link"=>seolink($news_title, "haberturk", $new_id),
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
            global $agency, $allowed_tags;
            $status=false;
            $result=null;
            $desc="İstediğiniz artık yok veya hatalı işlem.";
            $file=curl_function("{$_ENV["get_haberturk_new"]}?news_id={$new_id}");
            if($file['status']){
                $desc="from agency";
                $status=true;
                $news=$file['result'];
                $news_text=$news['haber_metin'];
                $text=null;
                $news_title;
                $news_date;
                $news_spot;
                $news_image;
                $news_cat=Sanitizer::toCat($news['kategori_adi'], true, true);
                foreach($news_text as $textRaw){
                    if($textRaw['type']==="text"){
                        if(isset($textRaw['font_size'])){
                            $text.="<p style='font-size:".$textRaw['font_size']."px;text-align:center;font-weight:bold'>".str_replace('\n','<br>', strip_tags($textRaw['text'], '<strong><p><h2><h3><h4><h5><span><br><br/><img><center>'))."</p>";
                        }
                        $text.=$textRaw['text'];
                    }else
                    if($textRaw['type']==="innerImage"){
                        $text.='<img width="'.$textRaw['width'].'" height="'.$textRaw['height'].'" src="'.$textRaw['src'].'" />';
                    }else
                    if($textRaw['type']==="mainimage"){
                        $news_image=$textRaw['imageUrl'];
                    }else
                    if($textRaw['type']==="title"){
                        $news_title=$textRaw['text'];
                    }else
                    if($textRaw['type']==="spot"){
                        $news_spot=$textRaw['text'];
                        if($news_spot===""){
                            $news_spot=$news_title;
                        }
                    }else
                    if($textRaw['type']==="share"){
                        $rawDate=explode(' ',$textRaw['createTime']);
                        $rawDatee=explode('-', $rawDate[0]);
                        $news_date=$rawDatee[2].".".$rawDatee[1].".".$rawDatee[0]." ".$rawDate[1];
                    }
                }
                    //image check
                    if(isset($news_image)){
                        $news_image=$news_image;
                    }else{
                        $news_image=null;

                    }
                    if($news['kategori_adi']===""){
                        $news_cat="HaberTürk Yazılar";
                    }
                    $text=str_replace(array('<a', '\n'), array('<a target="_blank"','<br>'), $text);
                    $text=strip_tags($text, $allowed_tags);
                    if(strlen($news_title)<=8 || strlen($text)<=8 ){
                        $status=false;
                    }
                    $result=array(
                        "visible"=>true,
                        "agency"=>"haberturk",
                        "agency_title"=>"Haber Türk",
                        "seo_link"=>seolink($news_title, "haberturk", $new_id),
                        "text"=>$text,
                        "categories"=>array($news_cat),
                        "id"=>$new_id,
                        "date_u"=>getUnixTime($news_date),
                        "date"=>$news_date,
                        "title"=>$news_title,
                        "spot"=>$news_spot,
                        "keywords"=>keywords($news_spot),
                        "saved_date"=>time(),
                        "image"=>$news_image,
                        "url"=>$news['url'],
                        "read_times"=>1
                    );
                    saveDatabase($agency, $result);
            }else{
                $desc="Habertürk ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
            }
            return array("status"=>$status, "result"=>$result, "desc"=>$desc);
        }
                    /// HABERTÜRK BİTİŞ ///

?>
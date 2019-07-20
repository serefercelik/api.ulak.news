<?php
        // SPUTNİKNEWS ///
        function get_sputnik(){
            $desc="İstediğiniz artık yok veya hatalı işlem.";
            $catNews=null;
            $status=false;
            $file=curl_function("{$_ENV["get_sputnik"]}"); // Tüm manşetler
            if($file['status']){
                $news=$file['result']['articles'];
                if($news!=null){
                    foreach($news as $news){
                        if($news['type']==="text"){
                            $desc="from agency";
                            $status=true;
                            $cat=$news['categories'];
                            $cats=[];
                            foreach($cat as $subcat){
                                $resCat=getCategorie("sputnik", $subcat);
                                if($resCat!==null){
                                    $cats[]=$resCat;
                                }
                            }
                            if(count($cats)<1){
                                $cats=array("Dünya");
                            }
                            $imageid=null;
                            if(!isset($news['enclosures'][0]['id'])){
                                $imageid=null;
                            }else{
                                $imageid="https://cdnmfd.img.ria.ru/enclosures/{$news['enclosures'][0]['id']}.jpg";
                            }
                            $news_title=$news['title'];
                            $new_id=$news['id'];
                            $catNews[]=array(
                                "agency"=>"sputnik",
                                "agency_title"=>"Sputnik",
                                "categories"=>$cats,
                                "id"=>$new_id,
                                "date"=>gmdate("d.m.Y H:i:s", $news['pub_date_ut']),
                                "date_u"=>(int)explode('.',$news['pub_date_ut'])[0],
                                "title"=>$news['title'],
                                "seo_link"=>seolink($news_title, "sputnik", $new_id),
                                "spot"=>$news['lead'],
                                "image"=>"https://images.ulak.news/?src=".$imageid,
                                "url"=>$news['issuer_article_uri']
                            );
                        }
                    }
                }
        
            }else{
                $desc="Sputnik ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
            }
            return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
        }
        function get_sputnik_new($new_id){
            global $agency, $allowed_tags;
            $status=false;
            $result=null;
            $desc="İstediğiniz artık yok veya hatalı işlem.";
            $file=curl_function("{$_ENV["get_sputnik_new"]}&id={$new_id}");
            if($file['status']){
                $news=$file['result']['articles'][0];
                if($news!=null){
                    if($news['type']==="text"){
                        $desc="from agency";
                        $status=true;
                        $cat=$news['categories'];
                        $cats=[];
                        foreach($cat as $subcat){
                            $resCat=getCategorie($agency, $subcat);
                            if($resCat!=null){
                                $cats[]=$resCat;
                            }
                        }
                        if(count($cats)<0){
                            $cats=array("Dünya");
                        }
                        $news_title=$news['title'];
                        $news_image=null;
                        if(!array_key_exists('enclosures', $news)){
                            $news_image=null;
                        }else{
                            $news_image="http://cdnmfd.img.ria.ru/enclosures/{$news['enclosures'][0]['id']}.jpg?w=840&h=840&crop=1&q=50.png";
                        }
                        $news_spot=$news['lead'];
                        if($news_spot===""){
                            $news_spot=$news_title;
                        }
                        $text=strip_tags(str_replace(array('<a', 'src="', "src='"), array('<a target="_blank"', 'src="https://images.ulak.news/?src=', "src='https://images.ulak.news/?src="), $news['body']), $allowed_tags);
                        if(strlen($news_title)<=8 || strlen($text)<=8 ){
                            $status=false;
                        }
                        $result=array(
                            "visible"=>true,
                            "agency"=>"sputnik",
                            "agency_title"=>"Sputnik",
                            "text"=>$text,
                            "categories"=>$cats,
                            "id"=>$new_id,
                            "date"=>gmdate("d-m-Y H:i:s", $news['pub_date_ut']),
                            "date_u"=>(int)explode('.',$news['pub_date_ut'])[0],
                            "title"=> $news_title,
                            "seo_link"=>seolink($news_title, "sputnik", $new_id),
                            "spot"=>$news_spot,
                            "keywords"=>keywords($news_spot),
                            "saved_date"=>time(),
                            "image"=>"https://images.ulak.news/?src=".$news_image,
                            "url"=>$news['issuer_article_uri'],
                            "read_times"=>1
                        );
                        if($cats!=null && isset($news_image)){
                            saveDatabase($agency, $result);
                        }
                    }
                }
        
            }else{
                $desc="Sputnik ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
            }
            return array("status"=>$status, "result"=>$result, "desc"=>$desc);
        }
?>
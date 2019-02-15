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
                                if($resCat!=null){
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

                                $imageid="http://cdnmfd.img.ria.ru/enclosures/{$news['enclosures'][0]['id']}.jpg?w=840&h=840&crop=1&q=50";
                            }
                            $news_title=$news['title'];
                            $catNews[]=array(
                                "agency"=>"sputnik",
                                "agency_title"=>"Sputnik",
                                "categories"=>$cats,
                                "id"=>$news['id'],
                                "date"=>gmdate("d.m.Y H:i:s", $news['pub_date_ut']),
                                "date_u"=>(int)explode('.',$news['pub_date_ut'])[0],
                                "title"=>$news['title'],
                                "seo_link"=>seolink($news_title),
                                "spot"=>$news['lead'],
                                "image"=>$imageid,
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
            global $agency;
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
                        $news_image="http://cdnmfd.img.ria.ru/enclosures/{$news['enclosures'][0]['id']}.jpg?w=840&h=840&crop=1&q=50";
                        if(!isset($news['enclosures'][0]['id'])){
                            $news_image="https://api.ulak.news/images/web/404.png";
                        }
                        $result=array(
                            "agency"=>"sputnik",
                            "agency_title"=>"Sputnik",
                            "text"=>strip_tags($news['body']),
                            "categories"=>$cats,
                            "id"=>(int)$new_id,
                            "date"=>gmdate("d-m-Y H:i:s", $news['pub_date_ut']),
                            "date_u"=>(int)explode('.',$news['pub_date_ut'])[0],
                            "title"=> $news_title,
                            "seo_link"=>seolink($news_title),
                            "spot"=>$news['lead'],
                            "image"=>$news_image,
                            "url"=>$news['issuer_article_uri'],
                            "read_times"=>1
                        );
                        if($cats!=null){
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
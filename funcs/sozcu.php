<?php

            /// SÖZCÜ BAŞLANGIÇ ////
            function get_sozcu(){
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $catNews=null;
                $status=false;
                $file=curl_function_sozcu(); // Tüm manşetler
                if($file['status']){
                    if($file['result']['success']){
                        $desc="from agency";
                        $status=true;
                        foreach($file['result']['data'] as $raw){
                            $date=gmdate("d.m.Y H:i:s", $raw['date']);
                            $news_title=$raw['title'];
                            $new_id=(int)$raw['post_id'];
                            $catNews[]=array(
                                "agency"=>"sozcu",
                                "agency_title"=>"Sözcü",
                                "categories"=>array($raw['category']),
                                "id"=>$new_id,
                                "date"=>$date,
                                "date_u"=>getUnixTime($date),
                                "title"=>$news_title,
                                "seo_link"=>seolink($news_title, "sozcu", $new_id),
                                "spot"=>$news_title,
                                "image"=>"https://images.ulak.news/?src=".$raw['image'],
                                "url"=>null
                            );
                        }
                    };
                }else{
                    $desc="Sözcü ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
            }
            function get_sozcu_new($new_id){
                global $agency, $allowed_tags;
                $status=false;
                $result=null;
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $file=curl_function_sozcu_new($new_id);
                if($file['status']){
                    $news=$file['result']['data'];
                    if($news[0]['post_id']!=null){
                        $desc="from agency";
                        $status=true;
                        $date=gmdate("d.m.Y H:i:s", $news[0]['date']);
                        $news_title=html_entity_decode($news[0]['title']);
                        //image check
                        $news_image=$news[0]['image'];
                        if(!isset($news[0]['image'])){
                            $news_image=null;
                        }
                        if($news[0]['category']===null){
                            $news[0]['category']="Sözcü Diğer";
                        }
                        $cat=Sanitizer::toCat($news[0]['category'], true, true);
                        $text=strip_tags(htmlspecialchars_decode(str_replace(array('<a', 'src="', "src='"), array('<a target="_blank"', 'src="https://images.ulak.news/?src=', "src='https://images.ulak.news/?src="), $news[0]['content'])), $allowed_tags);
                        if(strlen($news_title)<=8 || strlen($text)<=8 ){
                            $status=false;
                            $desc="from agency not saved text or title so short ";
                        }
                        $result=array(
                            "visible"=>true,
                            "agency"=>"sozcu",
                            "agency_title"=>"Sözcü",
                            "title"=>$news_title,
                            "text"=>html_entity_decode($text),
                            "categories"=>array($cat),
                            "id"=>$new_id,
                            "date"=>$date,
                            "date_u"=>getUnixTime($date),
                            "seo_link"=>seolink($news_title, "sozcu", $new_id),
                            "spot"=>$news_title,
                            "keywords"=>keywords($news_title),
                            "saved_date"=>time(),
                            "image"=>"https://images.ulak.news/?src=".$news_image,
                            "url"=>$news[0]['permalink'],
                            "read_times"=>1
                        );
                        if(isset($news_image)){
                            saveDatabase($agency, $result);
                        }
                    }
                }else{
                    $desc="Sözcü ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }

            ?>
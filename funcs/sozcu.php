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
                                "date_u"=>strtotime($date),
                                "title"=>$news_title,
                                "seo_link"=>seolink($news_title, "sozcu", $new_id),
                                "spot"=>$news_title,
                                "image"=>$raw['image'],
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
                global $agency;
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
                        $news_title=$news[0]['title'];
                        //image check
                        $news_image=$news[0]['image'];
                        if(!isset($news[0]['image'])){
                            $news_image="https://api.ulak.news/images/web/404.png";
                        }
                        $result=array(
                            "agency"=>"sozcu",
                            "agency_title"=>"Sözcü",
                            "text"=>strip_tags(html_entity_decode($news[0]['content']), '<strong><p><h2><h3><h4><h5><span><br><br/><img><center>'),
                            "categories"=>array($news[0]['category']),
                            "id"=>$new_id,
                            "date"=>$date,
                            "date_u"=>strtotime($date),
                            "title"=>$news_title,
                            "seo_link"=>seolink($news_title, "sozcu", $new_id),
                            "spot"=>$news[0]['title'],
                            "image"=>$news_image,
                            "url"=>$news[0]['permalink'],
                            "read_times"=>1
                        );
                        saveDatabase($agency, $result);
                    }
                }else{
                    $desc="Sözcü ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }

            ?>
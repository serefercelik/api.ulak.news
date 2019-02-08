<?php

            /// SÖZCÜ BAŞLANGIÇ ////
            function get_sozcu(){
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $catNews=null;
                $status=false;
                $file=curl_function_sozcu(); // Tüm manşetler
                if($file['status']){
                    if($file['result']['success']){
                        $desc="İşlem sanırım tamam :)";
                        $status=true;
                        foreach($file['result']['data'] as $raw){
                            $date=gmdate("d.m.Y H:i:s", $raw['date']);
                            $catNews[]=array(
                                "agency"=>"sozcu",
                                "agency_title"=>"Sözcü",
                                "category"=>array($raw['category']),
                                "id"=>(int)$raw['post_id'],
                                "date"=>$date,
                                "date_u"=>strtotime($date),
                                "title"=>$raw['title'],
                                "spot"=>$raw['title'],
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
                        $desc="İşlem sanırım tamam :)";
                        $status=true;
                        $date=gmdate("d.m.Y H:i:s", $news[0]['date']);
                        //image check
                        $news_image="https://api.ulak.news/images/web/404.png";
                        if(isset($news[0]['image'])){
                            $news_image=$news[0]['image'];
                        }
                        $result=array(
                            "agency"=>"sozcu",
                            "agency_title"=>"Sözcü",
                            "text"=>strip_tags(html_entity_decode($news[0]['content'])),
                            "category"=>array($news[0]['category']),
                            "id"=>(int)$news[0]['post_id'],
                            "date"=>$date,
                            "date_u"=>strtotime($date),
                            "title"=>$news[0]['title'],
                            "spot"=>$news[0]['title'],
                            "image"=>$news_image,
                            "url"=>$news[0]['permalink'],
                            "read_times"=>0
                        );
                        saveDatabase($agency, $result);
                    }
                }else{
                    $desc="Sözcü ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }

            ?>
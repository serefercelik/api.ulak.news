<?php

            /// Cumhuriyet BAŞLANGIÇ ////
            function get_cumhuriyet(){
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $catNews=null;
                $status=false;
                $file=curl_function($_ENV["get_cumhuriyet"]); // Tüm manşetler
                if($file['status']){
                        $desc="from agency";
                        $status=true;
                        foreach($file['result'] as $raw){
                            // cumhuriyet unix date paylaşmadığı için biz de kendimiz üretiyoruz :)
                            $unix=intval(preg_replace('/\D/', '', parse_url($raw['coverUrl'])['query'])/1000);
                            $date=gmdate("d.m.Y H:i:s", $unix);
                            $news_title=$raw['title'];
                            $news_summary=$raw['summary'];
                            $new_id=(int)$raw['id'];
                            $catNews[]=array(
                                "agency"=>"cumhuriyet",
                                "agency_title"=>"Cumhuriyet",
                                "categories"=>getCategorie("cumhuriyet", $raw['categoryName']),
                                "id"=>$new_id,
                                "date"=>$date,
                                "date_u"=>$unix,
                                "title"=>$news_title,
                                "seo_link"=>seolink($news_title, "cumhuriyet", $new_id),
                                "spot"=>$news_summary,
                                "image"=>"https://images.ulak.news/?src="."http://cumhuriyet.com.tr".$raw['coverUrl'],
                                "url"=>"http://cumhuriyet.com.tr".$raw['newsUrl']
                            );
                    };
                }else{
                    $desc="Cumhuriyet ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
            }

            function get_cumhuriyet_new($new_id){
                global $agency, $allowed_tags;
                $status=false;
                $result=null;
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $file=curl_function($_ENV["get_cumhuriyet_new"]."?newsId=".$new_id);
                if($file['status']){
                    $news=$file['result'];
                    if($news[0]['id']!=null){
                        $desc="from agency";
                        $status=true;
                        $unix=intval(preg_replace('/\D/', '', parse_url($news[0]['coverUrl'])['query'])/1000);
                        $date=gmdate("d.m.Y H:i:s", $unix);
                        $news_title=html_entity_decode($news[0]['title']);
                        //image check
                        $news_image="http://cumhuriyet.com.tr".$news[0]['coverUrl'];
                        if(!isset($news[0]['coverUrl'])){
                            $news_image=null;
                        }
                        $text=strip_tags(htmlspecialchars_decode(str_replace(array('<a', 'src="', "src='"), array('<a target="_blank"', 'src="https://images.ulak.news/?src=', "src='https://images.ulak.news/?src="), $news[0]['content'])), $allowed_tags);
                        if(strlen($news_title)<=8 || strlen($text)<=8 ){
                            $status=false;
                            $desc="from agency not saved text or title so short ";
                        }
                        $result=array(
                            "visible"=>true,
                            "agency"=>"cumhuriyet",
                            "agency_title"=>"Cumhuriyet",
                            "title"=>$news_title,
                            "text"=>html_entity_decode($text),
                            "categories"=>array("Cumhuriyet Kategorisiz"),
                            "id"=>$new_id,
                            "date"=>$date,
                            "date_u"=>getUnixTime($date),
                            "seo_link"=>seolink($news_title, "sozcu", $new_id),
                            "spot"=>$news_title,
                            "keywords"=>keywords($news_title),
                            "saved_date"=>time(),
                            "image"=>"https://images.ulak.news/?src=".$news_image,
                            "url"=>"http://cumhuriyet.com.tr".$news[0]['newsUrl'],
                            "read_times"=>1
                        );
                        if(isset($news_image)){
                            saveDatabase($agency, $result);
                        }
                    }
                }else{
                    $desc="Cumhuriyet ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }

            ?>
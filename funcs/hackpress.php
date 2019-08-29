<?php

            /// Hack Press BAŞLANGIÇ ////
            function get_hackpress(){
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $catNews=null;
                $status=false;
                $file=curl_function($_ENV["get_hackpress"]); // Tüm manşetler
                if($file['status']){
                        $desc="from agency";
                        $status=true;
                        foreach($file['result'] as $raw){
                            // hackpress unix date paylaşmadığı için biz de kendimiz üretiyoruz :)
                            $date=str_replace(array(',', '/', '-'), array('', '.', ''), preg_replace('/[^0-9, :,.,\/]+/i', '', $raw['date']));
                            $news_title=$raw['title'];
                            $news_summary=$raw['summary'];
                            $new_id=(int)$raw['id'];
                            $catNews[]=array(
                                "agency"=>"hackpress",
                                "agency_title"=>"Hack Press",
                                "categories"=>$raw['category'],
                                "id"=>$new_id,
                                "date"=>$date,
                                "date_u"=>getUnixTime($date),
                                "title"=>$news_title,
                                "seo_link"=>seolink($news_title, "hackpress", $new_id),
                                "spot"=>$news_summary,
                                "image"=>"https://images.ulak.news/index2.php?src="."http://hackpress.org".$raw['image'],
                                "url"=>$raw['url']
                            );
                    };
                }else{
                    $desc="Hack Press ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
            }

            function get_hackpress_new($new_id){
                global $agency, $allowed_tags;
                $status=false;
                $result=null;
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $file=curl_function($_ENV["get_hackpress_new"]."?id=".$new_id);
                if($file['status']){
                    $news=$file['result'];
                    if($news[0]['id']!=null){
                        $desc="from agency";
                        $status=true;
                        $date=str_replace(array(',', '/', '-'), array('', '.', ''), preg_replace('/[^0-9, :,.,\/]+/i', '', $news[0]['date']));
                        $news_title=html_entity_decode($news[0]['title']);
                        //image check
                        $news_image="http://hackpress.org".$news[0]['image'];
                        if(!isset($news[0]['image'])){
                            $news_image=null;
                        }
                        $text=strip_tags(htmlspecialchars_decode(str_replace(array('<a', 'src="', "src='"), array('<a target="_blank"', 'src="https://images.ulak.news/index2.php?src=', "src='https://images.ulak.news/index2.php?src="), $news[0]['content'])), $allowed_tags);
                        if(strlen($news_title)<=8 || strlen($text)<=8 ){
                            $status=false;
                            $desc="from agency not saved text or title so short ";
                        }
                        $result=array(
                            "visible"=>true,
                            "agency"=>"hackpress",
                            "agency_title"=>"Hack Press",
                            "title"=>$news_title,
                            "text"=>html_entity_decode($text),
                            "categories"=>array($news[0]['category']),
                            "id"=>$new_id,
                            "date"=>$date,
                            "date_u"=>getUnixTime($date),
                            "seo_link"=>seolink($news_title, "hackpress", $new_id),
                            "spot"=>$news_title,
                            "keywords"=>keywords($news_title),
                            "saved_date"=>time(),
                            "image"=>"https://images.ulak.news/index2.php?src=".$news_image,
                            "url"=>$news[0]['url'],
                            "read_times"=>1
                        );
                        if(isset($news_image)){
                            saveDatabase($agency, $result);
                        }
                    }
                }else{
                    $desc="Hack Press ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }

            ?>
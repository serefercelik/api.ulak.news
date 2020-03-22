<?php

            /// SÖZCÜ ///
            function get_sozcu(){
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $catNews=null;
                $status=false;
                $file=curl_function("{$_ENV["get_sozcu"]}?status=acf-disabled&time=".time()); // Tüm manşetler
                if($file['status']){
                    $desc="from agency";
                    $status=true;
                    foreach($file['result'] as $raw){
                                $news_title=$raw['title']['rendered'];
                                $new_id=(int)$raw['id'];
                                
                                // $news_html = get_meta_tags($raw['link']);

                                // if($news_html['twitter:image'] === null){
                                //     $news_image = "https://api.ulak.news/images/web/sozcu.png";
                                // }else{
                                //     $news_image = $news_html['twitter:image'];
                                // }

                                $catNews[]=array(
                                    "agency"=>"sozcu",
                                    "agency_title"=>"Sözcü",
                                    // "categories"=>[explode(',', $news_html['sgm:pagecat'])[0]],
                                    "id"=>$new_id,
                                    "date"=>date('d.m.Y H:i:s', getUnixTime(str_replace('T', ' ', $raw['date']))),
                                    "date_u"=>getUnixTime($raw['date']),
                                    "title"=>$news_title,
                                    "seo_link"=>seolink($news_title, "sozcu", $new_id),
                                    "spot"=>trim(preg_replace('/\s\s+/', ' ',$raw['excerpt']['rendered'])),
                                    // "image"=>"https://images.ulak.news/index2.php?src=".$news_image,
                                    "url"=>$raw['link']
                                );
                    }
                }else{
                    $desc="sozcu ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
            }
            
            function get_sozcu_new($new_id){
                global $agency, $allowed_tags;
                $status=false;
                $result=null;
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $file=curl_function("{$_ENV['get_sozcu']}/{$new_id}?_embed&time=".time());
                if($file['status']){
                    $news=$file['result'];
                    if($news!=null){
                        $desc="from agency";
                        $status=true;
                        $news_image="";
                        $news_title=$news['title']['rendered'];

                        $news_html = get_meta_tags($news['link']);

                        if($news_html['twitter:image'] === null){
                            $news_image = "https://api.ulak.news/images/web/sozcu.png";
                        }else{
                            $news_image = $news_html['twitter:image'];
                        }

                        $news_spot=$news['excerpt']['rendered'];
                        if($news['excerpt']['rendered']===""){
                            $news_spot=$news_title;
                        }
                        $text=strip_tags(str_replace(array('<a', 'src="', "src='", 'srcset=', 'aip(\'pageStructure\', {\"pageUrl\":\"https:\\/\\/www.sozcu.com.tr\\/wp-json\\/wp\\/v2\\/posts\\/'.$new_id.'\",\"pageCanonical\":\"https:\\/\\/www.sozcu.com.tr\\/wp-json\\/wp\\/v2\\/posts\\/'.$new_id.'\",\"pageType\":\"diger\",\"pageIdentifier\":\"\",\"pageCategory1\":\"sozcu\",\"pageCategory2\":\"\",\"pageCategory3\":\"\",\"pageCategory4\":\"\",\"pageCategory5\":\"\",\"pageTitle\":\" - S\\u00f6zc\\u00fc Gazetesi\"});'), array('<a target="_blank"', 'src="https://images.ulak.news/index2.php?src=', "src='https://images.ulak.news/index2.php?src=", '', ''), $news['content']['rendered']), $allowed_tags);
                        if(strlen($news_title)<=8 || strlen($text)<=8 ){
                            $status=false;
                        }

                        $result=array(
                            "visible"=>true,
                            "agency"=>"sozcu",
                            "agency_title"=>"Sözcü",
                            "text"=>$text,
                            "categories"=>[explode(',', $news_html['sgm:pagecat'])[0]],
                            "id"=>(int)$news['id'],
                            "date"=>date('d.m.Y H:i:s', getUnixTime(str_replace('T', ' ', $news['date']))),
                            "date_u"=>getUnixTime($news['date']),
                            "seo_link"=>seolink($news_title, "sozcu", $new_id),
                            "title"=>$news_title,
                            "spot"=>$news_spot,
                            "keywords"=>keywords($news_spot),
                            "saved_date"=>time(),
                            "image"=>"https://images.ulak.news/index2.php?src=".$news_image,
                            "url"=>$news['link'],
                            "read_times"=>1
                        );
                            if($news_image !== "https://api.ulak.news/images/web/sozcu.png"){
                                saveDatabase($agency, $result);
                            }
                    }
                }else{
                    $desc="sozcu ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }
                    // SÖZCÜ BİTİŞ ///
?>
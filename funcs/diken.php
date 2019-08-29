<?php

            /// DİKEN ///
            function get_diken(){
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $catNews=null;
                $status=false;
                $file=curl_function("{$_ENV["get_diken"]}?per_page=25&_embed"); // Tüm manşetler
                if($file['status']){
                    $desc="from agency";
                    $status=true;
                    foreach($file['result'] as $raw){
                                $news_title=$raw['title']['rendered'];
                                $new_id=(int)$raw['id'];
                                $cats=[];
                                foreach($raw['categories'] as $subcat){
                                    $resCat=getCategorie("diken", $subcat);
                                    if($resCat!==null){
                                        $cats[]=$resCat;
                                    }
                                }
                                if(array_key_exists('wp:featuredmedia', $raw['_embedded'])){
                                    $news_image=$raw['_embedded']['wp:featuredmedia'][0]['source_url'];
                                }else{
                                    $news_image="https://api.ulak.news/images/web/diken_manset.png";
                                }
                                $catNews[]=array(
                                    "agency"=>"diken",
                                    "agency_title"=>"Diken",
                                    "categories"=>$cats,
                                    "id"=>$new_id,
                                    "date"=>date('d.m.Y H:i:s', getUnixTime(str_replace('T', ' ', $raw['date']))),
                                    "date_u"=>getUnixTime($raw['date']),
                                    "title"=>$news_title,
                                    "seo_link"=>seolink($news_title, "diken", $new_id),
                                    "spot"=>$raw['excerpt']['rendered'],
                                    "image"=>"https://images.ulak.news/index2.php?src=".$news_image,
                                    "url"=>$raw['link']
                                );
                    }
                }else{
                    $desc="Diken ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
            }
            
            function get_diken_new($new_id){
                global $agency, $allowed_tags;
                $status=false;
                $result=null;
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $file=curl_function("{$_ENV['get_diken']}/{$new_id}?_embed");
                if($file['status']){
                    $news=$file['result'];
                    if($news!=null){
                        $desc="from agency";
                        $status=true;
                        $news_image="";
                        $news_title=$news['title']['rendered'];
                        //image check

                        if(array_key_exists('wp:featuredmedia', $news['_embedded'])){
                            $news_image=$news['_embedded']['wp:featuredmedia'][0]['source_url'];
                        }else{
                            $news_image="https://api.ulak.news/images/web/diken_manset.png";
                        }

                        $news_spot=$news['excerpt']['rendered'];
                        if($news['excerpt']['rendered']===""){
                            $news_spot=$news_title;
                        }
                        $text=strip_tags(str_replace(array('<a', 'src="', "src='", 'srcset='), array('<a target="_blank"', 'src="https://images.ulak.news/index2.php?src=', "src='https://images.ulak.news/index2.php?src=", ''), $news['content']['rendered']), $allowed_tags);
                        if(strlen($news_title)<=8 || strlen($text)<=8 ){
                            $status=false;
                        }
                        $cats=[];
                        foreach($news['categories'] as $subcat){
                            $resCat=getCategorie("diken", $subcat);
                            if($resCat!==null){
                                $cats[]=$resCat;
                            }
                        }
                        $result=array(
                            "visible"=>true,
                            "agency"=>"diken",
                            "agency_title"=>"Diken",
                            "text"=>$text,
                            "categories"=>$cats,
                            "id"=>(int)$news['id'],
                            "date"=>date('d.m.Y H:i:s', getUnixTime(str_replace('T', ' ', $news['date']))),
                            "date_u"=>getUnixTime($news['date']),
                            "seo_link"=>seolink($news_title, "diken", $new_id),
                            "title"=>$news_title,
                            "spot"=>$news_spot,
                            "keywords"=>keywords($news_spot),
                            "saved_date"=>time(),
                            "image"=>"https://images.ulak.news/index2.php?src=".$news_image,
                            "url"=>$news['link'],
                            "read_times"=>1
                        );
                           saveDatabase($agency, $result);
                    }
                }else{
                    $desc="Diken ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }
                    // DİKEN BİTİŞ ///
?>
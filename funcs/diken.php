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
                                $catNews[]=array(
                                    "agency"=>"diken",
                                    "agency_title"=>"Diken",
                                    "categories"=>$cats,
                                    "id"=>$new_id,
                                    "date"=>str_replace('T', ' ', $raw['date']),
                                    "date_u"=>getUnixTime($raw['date']),
                                    "title"=>$news_title,
                                    "seo_link"=>seolink($news_title, "diken", $new_id),
                                    "spot"=>$raw['excerpt']['rendered'],
                                    "image"=>"https://images.ulak.news/?src=".$raw['_embedded']['wp:featuredmedia'][0]['source_url'],
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
                $file=curl_function("{$_ENV['get_diken_new']}/id={$new_id}?_embed");
                if($file['status']){
                    $news=$file['result'];
                    if($news!=null){
                        $desc="from agency";
                        $status=true;
                        $news_title=$news[0]['haber_baslik'];
                        //image check
                        $news_image=$news[0]['resim'];
                        $news_spot=$news[0]['haber_spot'];
                        if($news[0]['haber_spot']===""){
                            $news_spot=$news_title;
                        }
                        $text=strip_tags(str_replace(array('<a', 'src="', "src='"), array('<a target="_blank"', 'src="https://images.ulak.news/?src=', "src='https://images.ulak.news/?src="), $news[0]['haber_metin']), $allowed_tags);
                        if(strlen($news_title)<=8 || strlen($text)<=8 ){
                            $status=false;
                        }
                        $result=array(
                            "visible"=>true,
                            "agency"=>"diken",
                            "agency_title"=>"Diken",
                            "text"=>$text,
                            "categories"=>array(getCategorie($agency, $news[0]['kategori_id'])),
                            "id"=>(int)$news[0]['id'],
                            "date"=>$news[0]['haber_zaman'],
                            "date_u"=>getUnixTime($news[0]['haber_zaman']),
                            "seo_link"=>seolink($news_title, "diken", $new_id),
                            "title"=>$news_title,
                            "spot"=>$news_spot,
                            "keywords"=>keywords($news_spot),
                            "saved_date"=>time(),
                            "image"=>"https://images.ulak.news/?src=".$news_image,
                            "url"=>$news[0]['haber_url'],
                            "read_times"=>1
                        );
                        if(isset($news_image)){
                           // saveDatabase($agency, $result);
                        }
                    }
                }else{
                    $desc="Diken ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }
                    // DİKEN BİTİŞ ///
?>
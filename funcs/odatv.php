<?php
            /// ODA TV ///
            function get_odatv(){
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $catNews=null;
                $status=false;
                $file=curl_function("{$_ENV["get_odatv"]}?catid=10"); // Tüm manşetler
                if($file['status']){
                    $desc="from agency";
                    $status=true;
                    foreach($file['result']['haberler'] as $raw){
                                $news_title=$raw['haber_baslik'];
                                $new_id=(int)$raw['id'];
                                $catNews[]=array(
                                    "agency"=>"odatv",
                                    "agency_title"=>"Odatv",
                                    "categories"=>array(getCategorie("odatv", $raw['kategori_id'])),
                                    "id"=>$new_id,
                                    "date"=>$raw['haber_zaman'],
                                    "date_u"=>strtotime($raw['haber_zaman']),
                                    "title"=>$news_title,
                                    "seo_link"=>seolink($news_title, "odatv", $new_id),
                                    "spot"=>$raw['haber_spot'],
                                    "image"=>$raw['resim'],
                                    "url"=>$raw['haber_url']
                                );
                    }
                }else{
                    $desc="Odatv ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$catNews, "desc"=>$desc);
            }
            
            function get_odatv_new($new_id){
                global $agency;
                $status=false;
                $result=null;
                $desc="İstediğiniz artık yok veya hatalı işlem.";
                $file=curl_function("{$_ENV['get_odatv_new']}?id={$new_id}");
                if($file['status']){
                    $news=$file['result']['haberler'];
                    if($news!=null){
                        $desc="from agency";
                        $status=true;
                        $news_title=$news[0]['haber_baslik'];
                        //image check
                        $news_image=$news[0]['resim'];
                        if(!isset($news[0]['resim'])){
                            $news_image="https://api.ulak.news/images/web/404.png";
                        }
                        $result=array(
                            "agency"=>"odatv",
                            "agency_title"=>"Odatv",
                            "text"=>strip_tags($news[0]['haber_metin']),
                            "categories"=>array(getCategorie($agency, $news[0]['kategori_id'])),
                            "id"=>(int)$news[0]['id'],
                            "date"=>$news[0]['haber_zaman'],
                            "date_u"=>strtotime($news[0]['haber_zaman']),
                            "seo_link"=>seolink($news_title, "odatv", $new_id),
                            "title"=>$news_title,
                            "spot"=>$news[0]['haber_spot'],
                            "image"=>$news_image,
                            "url"=>$news[0]['haber_url'],
                            "read_times"=>1
                        );
                        saveDatabase($agency, $result);
                    }
                }else{
                    $desc="Odatv ile bağlantı kurulamadı. api@orhanaydogdu.com.tr";
                }
                return array("status"=>$status, "result"=>$result, "desc"=>$desc);
            }
                    // ODA TV BİTİŞ ///
?>
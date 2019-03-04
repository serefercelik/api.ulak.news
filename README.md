###Özellikler

- Son dakika haberleri listemeleme
- Haber ajanslarını listeleme
- Ajanslara göre haberleri listeleme
- Haberleri filtreleme
- Kategorilere göre haberleri listeleme
- En çok okunan haberleri listeleme
- Günlük, haftalık, aylık ve tüm zamanlara göre en çok okunanları listeleme
- Haberlerin içeriğinde arama yapabilme
-- Geliştirmeye devam ediyorum :)


####En son haberler sonuç
https://api.ulak.news/?agency=all

```json
{
  "status": true,
  "desc": "Okey",
  "cached_time": 1551685880,
  "availability": 1551686000,
  "result": [
    {
      "agency_title": "Haber Türk",
      "agency": "haberturk",
      "categories": [
        "Gündem"
      ],
      "id": 2391781,
      "date_u": 1551685663,
      "date": "04.03.2019 10:47:43",
      "title": "19 ilde FETÖ operasyonu: 25 gözaltı",
      "seo_link": "haber_19-ilde-feto-operasyonu-25-gozal_haberturk_2391781.html",
      "spot": "Bursa Cumhuriyet Başsavcılığınca yürütülen soruşturma kapsamında, ardışık olarak arandıkları tespit edilen ve haklarında yakalama kararı verilen 50 zanlıdan 25'i gözaltına alındı",
      "image": "https:\/\/im.haberturk.com\/2019\/03\/04\/ver1551685663\/2391781_htmansetyeni.jpg",
      "url": "https:\/\/www.haberturk.com\/19-ilde-feto-operasyonu-25-gozalti-2391781"
    },
    {
      "agency": "odatv",
      "agency_title": "Odatv",
      "categories": [
        "Tüm Manşetler"
      ],
      "id": 168932,
      "date": "04.03.2019 10:47:00",
      "date_u": 1551685620,
      "title": "Erdoğan bize ne demek istiyor",
      "seo_link": "haber_erdogan-bize-ne-demek-istiyor_odatv_168932.html",
      "spot": "Seçim loto zamanı yine geldi ama...\r\n",
      "image": "http:\/\/www.odatv.com\/images\/2019_03\/2019_03_04\/erdogan-bize-ne-demek-istiyor-04031951_m2.jpg",
      "url": "http:\/\/www.odatv.com\/n.php?n=erdogan-bize-ne-demek-istiyor-04031951"
    }
]}
```
####Ajans listesi cevap
https://api.ulak.news/?agency=list

```json
{
  "status": true,
  "desc": "Listelendi.",
  "cached_time": 1551691307,
  "availability": 1551691427,
  "result": {
    "haberturk": {
      "title": "Haber Türk",
      "image": "https:\/\/api.ulak.news\/images\/web\/haberturk.png",
      "seo_link": "kaynak_haberturk.html",
      "about": "Habertürk, Ciner Yayın Holding bünyesinde 1 Mart 2009 tarihinde yayın hayatına başlayan günlük gazeteydi. Son sayısı 5 Temmuz 2018'de çıktı. "
    },
    "odatv": {
      "title": "Odatv",
      "image": "https:\/\/api.ulak.news\/images\/web\/odatv.png",
      "seo_link": "kaynak_odatv.html",
      "about": "Odatv.com, Odatv ya da odaᵀⱽ, 2007 yılında haber portalı olarak yayın yaşamına başlayan Web sitesi. İmtiyaz sahibi kişisi Soner Yalçın'dır. "
    },
    "sputnik": {
      "title": "Sputnik",
      "image": "https:\/\/api.ulak.news\/images\/web\/sputnik.png",
      "seo_link": "kaynak_sputnik.html",
      "about": "Sputnik, 10 Kasım 2014'te Rossiya Segodnya tarafından kurulan Moskova merkezli uluslararası medya kuruluşu. Dünyanın farklı bölgelerinde ofisleri bulunmaktadır. Sputnik, yayınlarını 34 ülkeyi kapsayan 130 şehirde, günde toplam 800 saatin üzerinde internet sitesinden ve radyo istasyonlarından yapar. "
    },
    "sozcu": {
      "title": "Sözcü",
      "image": "https:\/\/api.ulak.news\/images\/web\/sozcu.png",
      "seo_link": "kaynak_sozcu.html",
      "about": "Sözcü, 27 Haziran 2007 yılında merkezi İstanbul olmak üzere kurulmuş gazete."
    }
  },
  "get": {
    "agency": "list"
  }
}
```

ve daha fazlası...
api@orhanaydogdu.com.tr

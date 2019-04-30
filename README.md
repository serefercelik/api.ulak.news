
  
### Özellikler

- Son dakika haberleri listemeleme
- Haber ajanslarını listeleme
- Ajanslara göre haberleri listeleme
- Haberleri filtreleme
- Kategorilere göre haberleri listeleme
- En çok okunan haberleri listeleme
- Günlük, haftalık, aylık ve tüm zamanlara göre en çok okunanları listeleme
- Haberlerin içeriğinde arama yapabilme
- Haber listesinin başlangıç ve bitiş olarak listeleme (&start=0 veya herhangi başlangıç, &limit=10 veya daha fazlası)
- Haber yorumlarını listeleme ve filtreleme
- Cache'ler her bir client isteğine özel olarak oluşmakta çünkü her bir client a göre isteğin cevabı farklı olabilir bundan dolayı cachler isteğe özeldir.

-- Geliştirmeye devam ediyorum :)


#### En son haberler sonuç
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
#### Ajans listesi cevap
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

#### haber istek cevabı:
https://api.ulak.news/?agency=sputnik&id=7308570

```json
{
  "status": true,
  "desc": "From db",
  "cached_time": 1551691870,
  "availability": 1551691990,
  "result": {
    "_id": {
      "$oid": "5c7cf02dbcc5af32ae612002"
    },
    "visible": true,
    "agency": "sputnik",
    "agency_title": "Sputnik",
    "text": "<p>DHA'nın aktardığı habere g&ouml;re, Beyoğlu'nun Hask&ouml;y mahallesinde &ccedil;oğunluğu gecekondudan oluşan mahalle sakinlerinin g&uuml;ndemi kapılarının arasından gece bırakılan para dolu zarflar ve bunu kimin yaptığı.<\/p>\n<p>Habere g&ouml;re, bazı kişiler sabaha karşı mahalleye giriyor ve &ouml;nceden belirledikleri maddi durumu k&ouml;t&uuml; ailelerin kapıları arasından i&ccedil;eriye zarf bırakıyor. Bazı eve tek, bazılarına ise 2 ya da 3 zarf bırakılıyor. Zarfların her birinden 1000 TL &ccedil;ıkıyor. Aynı zarfların başka evlere de bırakıldığını &ouml;ğrenenler, daha sonra bunun bir yardım zarfı olduğunu anlıyor ve 'Hızır' adını verdikleri hayırseveri merak ediyor.<\/p>\n\n<p>DHA'ya konuşan mahalle sakinlerinden Paşa Ali Bilgin, \"Sabah namaz vakti kalktım evde tespih &ccedil;ekiyorum. Abdest aldım. Bir ses oldu dışarı &ccedil;ıktım. Baktım bacımla konuşuyorlar. Ne oluyor, dedim. 'Amca bir zarf bıraktım pazar parası yaparsınız' dedi. Dedim ki 'Burada bir hane yok. Bacım da var' dedim. Yukarılarda ona da vermişler. Dağıtarak gelmişler. Sonra gittiler. Zarfta ya 100 ya da 200 lira var sandım. Baktım 1000 TL var. 3 zarf aldık. 'Nereden geldi bu para?' dedim. 'Amca karıştırma bu s&ouml;ylenmez. Patronumuz sağ olsun' dediler\" ifadesini kullandı.<\/p>\n<p><strong>BİLGİN: B&Ouml;YLE İNSANLARA İHTİYA&Ccedil; VAR<\/strong><\/p>\n<p>Bir başka mahalle sakini Mustafa Bilgin de, \"Buradan ge&ccedil;erken ışığı g&ouml;r&uuml;yorlar. Burada duruyorlar. Abimi g&ouml;r&uuml;yorlar. Zarf veriyorlar 2-3 tane. Bin lira varmış zarfta. Sonra da gidiyorlar\" dedi.<\/p>\n<p>Metin Canbolat ise, \"Ger&ccedil;ekten durumu iyi olmayan evleri g&ouml;rerek kapı altlarından zarfı attıklarını s&ouml;yl&uuml;yorlar. Alanlar da 'aldık' diyorlar. Ama kim olduğunu, ne i&ccedil;in yapıldığını bilmiyorum. 10-15 kişiden duyduk para alan. Ama mahallede &ccedil;ok fazla alan var. Daha &ouml;nce bir sefer daha s&ouml;ylenmişti. Şimdi yakınımızdaki insanlar alınca biliyoruz. Allah razı olsun bu insandan. Bize gelen olmadı ama bu kadar evi neşelendirdi, şenlendirdi. B&ouml;yle insanlara ihtiya&ccedil; var\" diye konuştu.<\/p>\n<p><strong>BİLGİN: 40 SENEDİR BURADAYIM, B&Ouml;YLE BİR ŞEY G&Ouml;RMEDİM<\/strong><\/p>\n<p>Bir diğer mahalle sakini Yaşar Bilgin de, \"Ge&ccedil;en hafta ve ondan &ouml;nceki hafta oldu. 2 defa oldu bu ay i&ccedil;erisinde. Birinde sabah 06.30'da diğerini de gece 23.30'dan sonra dağıtmışlar. Babam almış, zarflardan bin lira &ccedil;ıktı. 40 senedir bu mahalledeyim b&ouml;yle bir şey g&ouml;rmedim. Allah razı olsun. Diğer iş adamlarına da &ouml;rnek olsun. Herkes bir mutlu oldu. Babam da ihtiyacı olan akrabalarına da dağıttı. Duyduğumuz 15, 20 ev var. Ama daha fazlası var. Kimse s&ouml;ylemiyor\" dedi.<\/p>\n\n<p><strong>ERZURUMLU: ZENGİN OLUP BURAYI UNUTMAYAN BİRİ OLABİLİR<\/strong><\/p>\n<p>Bir başka mahalle sakini Oğuzhan Erzurumlu da, \"Bize gelmedi ama mahallede ihtiyacı olan evlere vermişler. Benim tahminim burada eskiden oturan, daha sonra zengin olup burayı unutmayan biri olabilir parayı dağıtan kişi. &Ccedil;&uuml;nk&uuml; bu kadar ihtiyacı olan evleri bilmesi imkansız\" ifadesini kullandı.<\/p>",
    "categories": [
      "Sputnik Kategorisiz",
      "Dünya",
      "Yaşam",
      "Sputnik Kategorisiz",
      "Sputnik Kategorisiz",
      "Türkiye",
      "Sputnik Kategorisiz"
    ],
    "id": 7308570,
    "date": "04-03-2019 09:22:00",
    "date_u": 1551691320,
    "title": "Hasköy'de evlere para dolu zarf bırakıyorlar: 'Nereden geldi bu para?' dedim, 'Amca karıştırma söylenmez' dediler",
    "seo_link": "haber_haskoyde-evlere-para-dolu-zarf-b_sputnik_7308570.html",
    "spot": "İstanbul Hasköy'de gecekonduların çoğunlukta olduğu bir mahallede 1 ay içerisinde birçok kişi kapılarından içeri atılmış, içinde 1000 TL olan zarflarla karşılaştı. Mahalle sakinlerinden Paşa Ali Bilgin, zarf bırakanlara \"Nereden geldi bu para?\" diye sorduğunu, \"'Amca karıştırma bu söylenmez. Patronumuz sağ olsun' dediler\" yanıtını aldığını söyledi.",
    "keywords": "İstanbul, hasköyde, gecekonduların, çoğunlukta, olduğu, bir, mahallede, 1, ay, içerisinde, birçok, kişi, kapılarından, içeri, atılmış içinde, 1000, tl, olan, zarflarla, karşılaştı, mahalle, sakinlerinden, paşa, ali, bilgin zarf, bırakanlara, \"nereden, geldi, bu, para?\", diye, sorduğunu \"amca, karıştırma, bu, söylenmez, patronumuz, sağ, olsun, dediler\", yanıtını, aldığını, söyledi",
    "saved_date": 1551691821,
    "image": "http:\/\/cdnmfd.img.ria.ru\/enclosures\/13586329.jpg?w=840&h=840&crop=1&q=50.png",
    "url": "https:\/\/tr.sputniknews.com\/yasam\/201903041037988326-istanbul-haskoy-gecekondu-para-dolu-zarf\/",
    "read_times": 6
  },
  "get": {
    "agency": "sputnik",
    "id": "7308570"
  }
}
```

ve daha fazlası...
api@orhanaydogdu.com.tr

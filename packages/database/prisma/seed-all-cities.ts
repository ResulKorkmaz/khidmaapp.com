import { PrismaClient } from '@prisma/client';

const prisma = new PrismaClient();

// Tüm Türkiye şehirleri ve ilçeleri
const allCitiesData = [
  {
    name: "Adana", plateCode: "01", region: "Akdeniz",
    districts: ["Seyhan", "Yüreğir", "Çukurova", "Sarıçam", "Aladağ", "Ceyhan", "Feke", "İmamoğlu", "Karaisalı", "Karataş", "Kozan", "Pozantı", "Saimbeyli", "Tufanbeyli", "Yumurtalık"]
  },
  {
    name: "Adıyaman", plateCode: "02", region: "Güneydoğu Anadolu",
    districts: ["Merkez", "Besni", "Çelikhan", "Gerger", "Gölbaşı", "Kahta", "Samsat", "Sincik", "Tut"]
  },
  {
    name: "Afyonkarahisar", plateCode: "03", region: "Ege",
    districts: ["Merkez", "Başmakçı", "Bayat", "Bolvadin", "Çay", "Çobanlar", "Dazkırı", "Dinar", "Emirdağ", "Evciler", "Hocalar", "İhsaniye", "İscehisar", "Kızılören", "Sandıklı", "Sinanpaşa", "Sultandağı", "Şuhut"]
  },
  {
    name: "Ağrı", plateCode: "04", region: "Doğu Anadolu",
    districts: ["Merkez", "Diyadin", "Doğubayazıt", "Eleşkirt", "Hamur", "Patnos", "Taşlıçay", "Tutak"]
  },
  {
    name: "Amasya", plateCode: "05", region: "Karadeniz",
    districts: ["Merkez", "Göynücek", "Gümüşhacıköy", "Hamamözü", "Merzifon", "Suluova", "Taşova"]
  },
  {
    name: "Ankara", plateCode: "06", region: "İç Anadolu",
    districts: ["Altındağ", "Ayaş", "Bala", "Beypazarı", "Çamlıdere", "Çankaya", "Çubuk", "Elmadağ", "Etimesgut", "Evren", "Gölbaşı", "Güdül", "Haymana", "Kalecik", "Kazan", "Keçiören", "Kızılcahamam", "Mamak", "Nallıhan", "Polatlı", "Pursaklar", "Sincan", "Şereflikoçhisar", "Yenimahalle", "Akyurt"]
  },
  {
    name: "Antalya", plateCode: "07", region: "Akdeniz",
    districts: ["Aksu", "Alanya", "Demre", "Döşemealtı", "Elmalı", "Finike", "Gazipaşa", "Gündoğmuş", "İbradı", "Kaş", "Kemer", "Kepez", "Konyaaltı", "Korkuteli", "Kumluca", "Manavgat", "Muratpaşa", "Serik"]
  },
  {
    name: "Artvin", plateCode: "08", region: "Karadeniz",
    districts: ["Merkez", "Ardanuç", "Arhavi", "Borçka", "Hopa", "Murgul", "Şavşat", "Yusufeli"]
  },
  {
    name: "Aydın", plateCode: "09", region: "Ege",
    districts: ["Merkez", "Bozdoğan", "Buharkent", "Çine", "Didim", "Germencik", "İncirliova", "Karacasu", "Karpuzlu", "Koçarlı", "Köşk", "Kuşadası", "Kuyucak", "Nazilli", "Söke", "Sultanhisar", "Yenipazar"]
  },
  {
    name: "Balıkesir", plateCode: "10", region: "Marmara",
    districts: ["Altıeylül", "Karesi", "Ayvalık", "Balya", "Bandırma", "Bigadiç", "Burhaniye", "Dursunbey", "Edremit", "Erdek", "Gömeç", "Gönen", "Havran", "İvrindi", "Kepsut", "Manyas", "Marmara", "Savaştepe", "Sındırgı", "Susurluk"]
  },
  {
    name: "Bilecik", plateCode: "11", region: "Marmara",
    districts: ["Merkez", "Bozüyük", "Gölpazarı", "İnhisar", "Osmaneli", "Pazaryeri", "Söğüt", "Yenipazar"]
  },
  {
    name: "Bingöl", plateCode: "12", region: "Doğu Anadolu",
    districts: ["Merkez", "Adaklı", "Genç", "Karlıova", "Kiğı", "Solhan", "Yayladere", "Yedisu"]
  },
  {
    name: "Bitlis", plateCode: "13", region: "Doğu Anadolu",
    districts: ["Merkez", "Adilcevaz", "Ahlat", "Güroymak", "Hizan", "Mutki", "Tatvan"]
  },
  {
    name: "Bolu", plateCode: "14", region: "Karadeniz",
    districts: ["Merkez", "Dörtdivan", "Gerede", "Göynük", "Kıbrıscık", "Mengen", "Mudurnu", "Seben", "Yeniçağa"]
  },
  {
    name: "Burdur", plateCode: "15", region: "Akdeniz",
    districts: ["Merkez", "Ağlasun", "Altınyayla", "Bucak", "Çavdır", "Çeltikçi", "Gölhisar", "Karamanlı", "Kemer", "Tefenni", "Yeşilova"]
  },
  {
    name: "Bursa", plateCode: "16", region: "Marmara",
    districts: ["Osmangazi", "Nilüfer", "Yıldırım", "Büyükorhan", "Gemlik", "Gürsu", "Harmancık", "İnegöl", "İznik", "Karacabey", "Keles", "Kestel", "Mudanya", "Mustafakemalpaşa", "Orhaneli", "Orhangazi", "Yenişehir"]
  },
  {
    name: "Çanakkale", plateCode: "17", region: "Marmara",
    districts: ["Merkez", "Ayvacık", "Bayramiç", "Biga", "Bozcaada", "Çan", "Eceabat", "Ezine", "Gelibolu", "Gökçeada", "Lapseki", "Yenice"]
  },
  {
    name: "Çankırı", plateCode: "18", region: "İç Anadolu",
    districts: ["Merkez", "Atkaracalar", "Bayramören", "Çerkeş", "Eldivan", "Ilgaz", "Kızılırmak", "Korgun", "Kurşunlu", "Orta", "Şabanözü", "Yapraklı"]
  },
  {
    name: "Çorum", plateCode: "19", region: "Karadeniz",
    districts: ["Merkez", "Alaca", "Bayat", "Boğazkale", "Dodurga", "İskilip", "Kargı", "Laçin", "Mecitözü", "Oğuzlar", "Ortaköy", "Osmancık", "Sungurlu", "Uğurludağ"]
  },
  {
    name: "Denizli", plateCode: "20", region: "Ege",
    districts: ["Merkezefendi", "Pamukkale", "Acıpayam", "Babadağ", "Baklan", "Bekilli", "Beyağaç", "Bozkurt", "Buldan", "Çal", "Çameli", "Çardak", "Çivril", "Güney", "Honaz", "Kale", "Sarayköy", "Serinhisar", "Tavas"]
  },
  {
    name: "Diyarbakır", plateCode: "21", region: "Güneydoğu Anadolu",
    districts: ["Bağlar", "Kayapınar", "Sur", "Yenişehir", "Bismil", "Çermik", "Çınar", "Çüngüş", "Dicle", "Eğil", "Ergani", "Hani", "Hazro", "Kulp", "Lice", "Silvan"]
  },
  {
    name: "Edirne", plateCode: "22", region: "Marmara",
    districts: ["Merkez", "Enez", "Havsa", "İpsala", "Keşan", "Lalapaşa", "Meriç", "Süloğlu", "Uzunköprü"]
  },
  {
    name: "Elazığ", plateCode: "23", region: "Doğu Anadolu",
    districts: ["Merkez", "Ağın", "Alacakaya", "Arıcak", "Baskil", "Karakoçan", "Keban", "Kovancılar", "Maden", "Palu", "Sivrice"]
  },
  {
    name: "Erzincan", plateCode: "24", region: "Doğu Anadolu",
    districts: ["Merkez", "Çayırlı", "İliç", "Kemah", "Kemaliye", "Otlukbeli", "Refahiye", "Tercan", "Üzümlü"]
  },
  {
    name: "Erzurum", plateCode: "25", region: "Doğu Anadolu",
    districts: ["Aziziye", "Palandöken", "Yakutiye", "Aşkale", "Çat", "Hınıs", "Horasan", "İspir", "Karaçoban", "Karayazı", "Köprüköy", "Narman", "Oltu", "Olur", "Pasinler", "Şenkaya", "Tekman", "Tortum", "Uzundere"]
  },
  {
    name: "Eskişehir", plateCode: "26", region: "İç Anadolu",
    districts: ["Odunpazarı", "Tepebaşı", "Alpu", "Beylikova", "Çifteler", "Günyüzü", "Han", "İnönü", "Mahmudiye", "Mihalgazi", "Mihalıççık", "Sarıcakaya", "Seyitgazi", "Sivrihisar"]
  },
  {
    name: "Gaziantep", plateCode: "27", region: "Güneydoğu Anadolu",
    districts: ["Şahinbey", "Şehitkamil", "Oğuzeli", "Araban", "İslahiye", "Karkamış", "Nizip", "Nurdağı", "Yavuzeli"]
  },
  {
    name: "Giresun", plateCode: "28", region: "Karadeniz",
    districts: ["Merkez", "Alucra", "Bulancak", "Çamoluk", "Çanakçı", "Dereli", "Doğankent", "Espiye", "Eynesil", "Görele", "Güce", "Keşap", "Piraziz", "Şebinkarahisar", "Tirebolu", "Yağlıdere"]
  },
  {
    name: "Gümüşhane", plateCode: "29", region: "Karadeniz",
    districts: ["Merkez", "Kelkit", "Köse", "Kürtün", "Şiran", "Torul"]
  },
  {
    name: "Hakkari", plateCode: "30", region: "Doğu Anadolu",
    districts: ["Merkez", "Çukurca", "Derecik", "Şemdinli", "Yüksekova"]
  },
  {
    name: "Hatay", plateCode: "31", region: "Akdeniz",
    districts: ["Antakya", "Defne", "Arsuz", "Altınözü", "Belen", "Dörtyol", "Erzin", "Hassa", "İskenderun", "Kırıkhan", "Kumlu", "Payas", "Reyhanlı", "Samandağ", "Yayladağı"]
  },
  {
    name: "Isparta", plateCode: "32", region: "Akdeniz",
    districts: ["Merkez", "Aksu", "Atabey", "Eğirdir", "Gelendost", "Gönen", "Keçiborlu", "Senirkent", "Sütçüler", "Şarkikaraağaç", "Uluborlu", "Yalvaç", "Yenişarbademli"]
  },
  {
    name: "Mersin", plateCode: "33", region: "Akdeniz",
    districts: ["Akdeniz", "Mezitli", "Toroslar", "Yenişehir", "Anamur", "Aydıncık", "Bozyazı", "Çamlıyayla", "Erdemli", "Gülnar", "Mut", "Silifke", "Tarsus"]
  },
  {
    name: "İstanbul", plateCode: "34", region: "Marmara",
    districts: ["Adalar", "Arnavutköy", "Ataşehir", "Avcılar", "Bağcılar", "Bahçelievler", "Bakırköy", "Başakşehir", "Bayrampaşa", "Beşiktaş", "Beykoz", "Beylikdüzü", "Beyoğlu", "Büyükçekmece", "Çatalca", "Çekmeköy", "Esenler", "Esenyurt", "Eyüpsultan", "Fatih", "Gaziosmanpaşa", "Güngören", "Kadıköy", "Kağıthane", "Kartal", "Küçükçekmece", "Maltepe", "Pendik", "Sancaktepe", "Sarıyer", "Silivri", "Sultanbeyli", "Sultangazi", "Şile", "Şişli", "Tuzla", "Ümraniye", "Üsküdar", "Zeytinburnu"]
  },
  {
    name: "İzmir", plateCode: "35", region: "Ege",
    districts: ["Aliağa", "Balçova", "Bayındır", "Bayraklı", "Bergama", "Beydağ", "Bornova", "Buca", "Çeşme", "Çiğli", "Dikili", "Foça", "Gaziemir", "Güzelbahçe", "Karabağlar", "Karaburun", "Karşıyaka", "Kemalpaşa", "Kınık", "Kiraz", "Konak", "Menderes", "Menemen", "Narlıdere", "Ödemiş", "Seferihisar", "Selçuk", "Tire", "Torbalı", "Urla"]
  },
  {
    name: "Kars", plateCode: "36", region: "Doğu Anadolu",
    districts: ["Merkez", "Akyaka", "Arpaçay", "Digor", "Kağızman", "Sarıkamış", "Selim", "Susuz"]
  },
  {
    name: "Kastamonu", plateCode: "37", region: "Karadeniz",
    districts: ["Merkez", "Abana", "Ağlı", "Araç", "Azdavay", "Bozkurt", "Cide", "Çatalzeytin", "Daday", "Devrekani", "Doğanyurt", "Hanönü", "İhsangazi", "İnebolu", "Küre", "Pınarbaşı", "Seydiler", "Şenpazar", "Taşköprü", "Tosya"]
  },
  {
    name: "Kayseri", plateCode: "38", region: "İç Anadolu",
    districts: ["Kocasinan", "Melikgazi", "Talas", "Hacılar", "Akkışla", "Bünyan", "Develi", "Felahiye", "İncesu", "Özvatan", "Pınarbaşı", "Sarıoğlan", "Sarız", "Tomarza", "Yahyalı", "Yeşilhisar"]
  },
  {
    name: "Kırklareli", plateCode: "39", region: "Marmara",
    districts: ["Merkez", "Babaeski", "Demirköy", "Kofçaz", "Lüleburgaz", "Pehlivanköy", "Pınarhisar", "Vize"]
  },
  {
    name: "Kırşehir", plateCode: "40", region: "İç Anadolu",
    districts: ["Merkez", "Akçakent", "Akpınar", "Boztepe", "Çiçekdağı", "Kaman", "Mucur"]
  },
  {
    name: "Kocaeli", plateCode: "41", region: "Marmara",
    districts: ["İzmit", "Başiskele", "Çayırova", "Darıca", "Derince", "Dilovası", "Gebze", "Gölcük", "Kandıra", "Karamürsel", "Kartepe", "Körfez"]
  },
  {
    name: "Konya", plateCode: "42", region: "İç Anadolu",
    districts: ["Karatay", "Meram", "Selçuklu", "Ahırlı", "Akören", "Akşehir", "Altınekin", "Beyşehir", "Bozkır", "Cihanbeyli", "Çeltik", "Çumra", "Derbent", "Derebucak", "Doğanhisar", "Emirgazi", "Ereğli", "Güneysinir", "Hadim", "Halkapınar", "Hüyük", "Ilgın", "Kadınhanı", "Karapınar", "Kulu", "Sarayönü", "Seydişehir", "Taşkent", "Tuzlukçu", "Yalıhüyük", "Yunak"]
  },
  {
    name: "Kütahya", plateCode: "43", region: "Ege",
    districts: ["Merkez", "Altıntaş", "Aslanapa", "Çavdarhisar", "Domaniç", "Dumlupınar", "Emet", "Gediz", "Hisarcık", "Pazarlar", "Simav", "Şaphane", "Tavşanlı"]
  },
  {
    name: "Malatya", plateCode: "44", region: "Doğu Anadolu",
    districts: ["Battalgazi", "Yeşilyurt", "Akçadağ", "Arapgir", "Arguvan", "Darende", "Doğanşehir", "Doğanyol", "Hekimhan", "Kale", "Kuluncak", "Pütürge", "Yazıhan"]
  },
  {
    name: "Manisa", plateCode: "45", region: "Ege",
    districts: ["Şehzadeler", "Yunusemre", "Akhisar", "Alaşehir", "Demirci", "Gölmarmara", "Gördes", "Kırkağaç", "Köprübaşı", "Kula", "Salihli", "Sarıgöl", "Saruhanlı", "Selendi", "Soma", "Turgutlu"]
  },
  {
    name: "Kahramanmaraş", plateCode: "46", region: "Akdeniz",
    districts: ["Dulkadiroğlu", "Onikişubat", "Afşin", "Andırın", "Çağlayancerit", "Ekinözü", "Elbistan", "Göksun", "Nurhak", "Pazarcık", "Türkoğlu"]
  },
  {
    name: "Mardin", plateCode: "47", region: "Güneydoğu Anadolu",
    districts: ["Artuklu", "Dargeçit", "Derik", "Kızıltepe", "Mazıdağı", "Midyat", "Nusaybin", "Ömerli", "Savur", "Yeşilli"]
  },
  {
    name: "Muğla", plateCode: "48", region: "Ege",
    districts: ["Menteşe", "Bodrum", "Dalaman", "Datça", "Fethiye", "Kavaklıdere", "Köyceğiz", "Marmaris", "Milas", "Ortaca", "Seydikemer", "Ula", "Yatağan"]
  },
  {
    name: "Muş", plateCode: "49", region: "Doğu Anadolu",
    districts: ["Merkez", "Bulanık", "Hasköy", "Korkut", "Malazgirt", "Varto"]
  },
  {
    name: "Nevşehir", plateCode: "50", region: "İç Anadolu",
    districts: ["Merkez", "Acıgöl", "Avanos", "Derinkuyu", "Gülşehir", "Hacıbektaş", "Kozaklı", "Ürgüp"]
  },
  {
    name: "Niğde", plateCode: "51", region: "İç Anadolu",
    districts: ["Merkez", "Altunhisar", "Bor", "Çamardı", "Çiftlik", "Ulukışla"]
  },
  {
    name: "Ordu", plateCode: "52", region: "Karadeniz",
    districts: ["Altınordu", "Akkuş", "Aybastı", "Çamaş", "Çatalpınar", "Çaybaşı", "Fatsa", "Gölköy", "Gülyalı", "Gürgentepe", "İkizce", "Kabadüz", "Kabataş", "Korgan", "Kumru", "Mesudiye", "Perşembe", "Piraziz", "Ulubey", "Ünye"]
  },
  {
    name: "Rize", plateCode: "53", region: "Karadeniz",
    districts: ["Merkez", "Ardeşen", "Çamlıhemşin", "Çayeli", "Derepazarı", "Fındıklı", "Güneysu", "Hemşin", "İkizdere", "İyidere", "Kalkandere", "Pazar"]
  },
  {
    name: "Sakarya", plateCode: "54", region: "Marmara",
    districts: ["Adapazarı", "Serdivan", "Akyazı", "Arifiye", "Erenler", "Ferizli", "Geyve", "Hendek", "Karapürçek", "Karasu", "Kaynarca", "Kocaali", "Pamukova", "Sapanca", "Söğütlü", "Taraklı"]
  },
  {
    name: "Samsun", plateCode: "55", region: "Karadeniz",
    districts: ["Atakum", "Canik", "İlkadım", "Tekkeköy", "19 Mayıs", "Alaçam", "Asarcık", "Ayvacık", "Bafra", "Çarşamba", "Havza", "Kavak", "Ladik", "Salıpazarı", "Terme", "Vezirköprü", "Yakakent"]
  },
  {
    name: "Siirt", plateCode: "56", region: "Güneydoğu Anadolu",
    districts: ["Merkez", "Baykan", "Eruh", "Kurtalan", "Pervari", "Şirvan", "Tillo"]
  },
  {
    name: "Sinop", plateCode: "57", region: "Karadeniz",
    districts: ["Merkez", "Ayancık", "Boyabat", "Dikmen", "Durağan", "Erfelek", "Gerze", "Saraydüzü", "Türkeli"]
  },
  {
    name: "Sivas", plateCode: "58", region: "İç Anadolu",
    districts: ["Merkez", "Akıncılar", "Altınyayla", "Divriği", "Doğanşar", "Gemerek", "Gölova", "Gürün", "Hafik", "İmranlı", "Kangal", "Koyulhisar", "Şarkışla", "Suşehri", "Ulaş", "Yıldızeli", "Zara"]
  },
  {
    name: "Tekirdağ", plateCode: "59", region: "Marmara",
    districts: ["Süleymanpaşa", "Çerkezköy", "Çorlu", "Ergene", "Hayrabolu", "Malkara", "Marmaraereğlisi", "Muratlı", "Saray", "Şarköy"]
  },
  {
    name: "Tokat", plateCode: "60", region: "Karadeniz",
    districts: ["Merkez", "Almus", "Artova", "Başçiftlik", "Erbaa", "Niksar", "Pazar", "Reşadiye", "Sulusaray", "Turhal", "Yeşilyurt", "Zile"]
  },
  {
    name: "Trabzon", plateCode: "61", region: "Karadeniz",
    districts: ["Ortahisar", "Akçaabat", "Araklı", "Arsin", "Beşikdüzü", "Çarşıbaşı", "Çaykara", "Dernekpazarı", "Düzköy", "Hayrat", "Köprübaşı", "Maçka", "Of", "Sürmene", "Şalpazarı", "Tonya", "Vakfıkebir", "Yomra"]
  },
  {
    name: "Tunceli", plateCode: "62", region: "Doğu Anadolu",
    districts: ["Merkez", "Çemişgezek", "Hozat", "Mazgirt", "Nazımiye", "Ovacık", "Pertek", "Pülümür"]
  },
  {
    name: "Şanlıurfa", plateCode: "63", region: "Güneydoğu Anadolu",
    districts: ["Eyyübiye", "Haliliye", "Karaköprü", "Akçakale", "Birecik", "Bozova", "Ceylanpınar", "Halfeti", "Harran", "Hilvan", "Siverek", "Suruç", "Viranşehir"]
  },
  {
    name: "Uşak", plateCode: "64", region: "Ege",
    districts: ["Merkez", "Banaz", "Eşme", "Karahallı", "Sivaslı", "Ulubey"]
  },
  {
    name: "Van", plateCode: "65", region: "Doğu Anadolu",
    districts: ["İpekyolu", "Tuşba", "Bahçesaray", "Başkale", "Çaldıran", "Çatak", "Edremit", "Erciş", "Gevaş", "Gürpınar", "Muradiye", "Özalp", "Saray"]
  },
  {
    name: "Yozgat", plateCode: "66", region: "İç Anadolu",
    districts: ["Merkez", "Akdağmadeni", "Aydıncık", "Boğazlıyan", "Çandır", "Çayıralan", "Çekerek", "Kadışehri", "Saraykent", "Sarıkaya", "Şefaatli", "Sorgun", "Yenifakılı", "Yerköy"]
  },
  {
    name: "Zonguldak", plateCode: "67", region: "Karadeniz",
    districts: ["Merkez", "Alaplı", "Çaycuma", "Devrek", "Gökçebey", "Kilimli", "Kozlu"]
  },
  {
    name: "Aksaray", plateCode: "68", region: "İç Anadolu",
    districts: ["Merkez", "Ağaçören", "Eskil", "Gülağaç", "Güzelyurt", "Ortaköy", "Sarıyahşi"]
  },
  {
    name: "Bayburt", plateCode: "69", region: "Karadeniz",
    districts: ["Merkez", "Aydıntepe", "Demirözü"]
  },
  {
    name: "Karaman", plateCode: "70", region: "İç Anadolu",
    districts: ["Merkez", "Ayrancı", "Başyayla", "Ermenek", "Kazımkarabekir", "Sarıveliler"]
  },
  {
    name: "Kırıkkale", plateCode: "71", region: "İç Anadolu",
    districts: ["Merkez", "Bahşili", "Balışeyh", "Çelebi", "Delice", "Karakeçili", "Keskin", "Sulakyurt", "Yahşihan"]
  },
  {
    name: "Batman", plateCode: "72", region: "Güneydoğu Anadolu",
    districts: ["Merkez", "Beşiri", "Gercüş", "Hasankeyf", "Kozluk", "Sason"]
  },
  {
    name: "Şırnak", plateCode: "73", region: "Güneydoğu Anadolu",
    districts: ["Merkez", "Beytüşşebap", "Cizre", "Güçlükonak", "İdil", "Silopi", "Uludere"]
  },
  {
    name: "Bartın", plateCode: "74", region: "Karadeniz",
    districts: ["Merkez", "Amasra", "Kurucaşile", "Ulus"]
  },
  {
    name: "Ardahan", plateCode: "75", region: "Doğu Anadolu",
    districts: ["Merkez", "Çıldır", "Damal", "Göle", "Hanak", "Posof"]
  },
  {
    name: "Iğdır", plateCode: "76", region: "Doğu Anadolu",
    districts: ["Merkez", "Aralık", "Karakoyunlu", "Tuzluca"]
  },
  {
    name: "Yalova", plateCode: "77", region: "Marmara",
    districts: ["Merkez", "Altınova", "Armutlu", "Çınarcık", "Çiftlikköy", "Termal"]
  },
  {
    name: "Karabük", plateCode: "78", region: "Karadeniz",
    districts: ["Merkez", "Eflani", "Eskipazar", "Ovacık", "Safranbolu", "Yenice"]
  },
  {
    name: "Kilis", plateCode: "79", region: "Güneydoğu Anadolu",
    districts: ["Merkez", "Elbeyli", "Musabeyli", "Polateli"]
  },
  {
    name: "Osmaniye", plateCode: "80", region: "Akdeniz",
    districts: ["Merkez", "Bahçe", "Düziçi", "Hasanbeyli", "Kadirli", "Sumbas", "Toprakkale"]
  },
  {
    name: "Düzce", plateCode: "81", region: "Karadeniz",
    districts: ["Merkez", "Akçakoca", "Cumayeri", "Çilimli", "Gölyaka", "Gümüşova", "Kaynaşlı", "Yığılca"]
  }
];

// Kategori verilerini de ekleyelim
const categoriesData = [
  {
    name: "Temizlik Hizmetleri",
    children: [
      "Ev Temizliği", "Ofis Temizliği", "İnşaat Sonrası Temizlik", "Cam Silme", 
      "Halı Temizliği", "Koltuk Temizliği", "Yer Silme", "Derin Temizlik"
    ]
  },
  {
    name: "Tadilat ve Dekorasyon",
    children: [
      "Boyacılık", "Elektrik İşleri", "Su Tesisatı", "Döşeme", "Duvar Kağıdı", 
      "Parke Döşeme", "Mutfak Tadilat", "Banyo Tadilat", "Kapı Pencere"
    ]
  },
  {
    name: "Bahçe ve Peyzaj",
    children: [
      "Bahçe Düzenleme", "Çim Biçme", "Ağaç Budama", "Sulama Sistemi", 
      "Peyzaj Tasarım", "Bahçe Bakım", "Çiçek Ekimi", "Meyve Ağacı Bakımı"
    ]
  },
  {
    name: "Nakliye ve Taşımacılık",
    children: [
      "Ev Taşıma", "Ofis Taşıma", "Eşya Taşıma", "Piyano Taşıma", 
      "Beyaz Eşya Taşıma", "Şehir İçi Nakliye", "Şehirler Arası Nakliye"
    ]
  },
  {
    name: "Teknoloji ve Bilgisayar",
    children: [
      "Bilgisayar Tamiri", "Laptop Tamiri", "Telefon Tamiri", "Tablet Tamiri",
      "Veri Kurtarma", "Yazılım Kurulumu", "Ağ Kurulumu", "Güvenlik Kamerası"
    ]
  },
  {
    name: "Otomotiv",
    children: [
      "Araç Tamiri", "Lastik Değişimi", "Yağ Değişimi", "Fren Tamiri",
      "Motor Tamiri", "Elektrik Tamiri", "Araç Boyama", "Araç Temizliği"
    ]
  },
  {
    name: "Sağlık ve Kişisel Bakım",
    children: [
      "Masaj", "Fizyoterapi", "Kuaförlük", "Güzellik Bakımı", "Makyaj",
      "Tırnak Bakımı", "Cilt Bakımı", "Epilasyon"
    ]
  },
  {
    name: "Eğitim ve Kurslar",
    children: [
      "Matematik Dersi", "İngilizce Dersi", "Müzik Dersi", "Spor Antrenörlüğü",
      "Sürücü Kursu", "Bilgisayar Kursu", "Dil Kursu", "Özel Ders"
    ]
  },
  {
    name: "Etkinlik ve Organizasyon",
    children: [
      "Düğün Organizasyonu", "Doğum Günü", "Kurumsal Etkinlik", "Catering",
      "Müzik ve DJ", "Fotoğraf Çekimi", "Video Çekimi", "Animasyon"
    ]
  },
  {
    name: "Hukuki Danışmanlık",
    children: [
      "Hukuki Danışmanlık", "Avukatlık", "Noter İşlemleri", "Mahkeme İşlemleri",
      "Sözleşme Hazırlama", "Boşanma Davası", "İş Hukuku", "Gayrimenkul Hukuku"
    ]
  },
  {
    name: "Güvenlik Hizmetleri",
    children: [
      "Güvenlik Görevlisi", "Alarm Sistemi", "Güvenlik Kamerası", "Çilingir",
      "Kasa Açma", "Kapı Kilidi", "Güvenlik Danışmanlığı", "İş Güvenliği"
    ]
  },
  {
    name: "Diğer Hizmetler",
    children: [
      "Tercümanlık", "Veteriner Hizmetleri", "Pet Bakımı", "Yaşlı Bakımı",
      "Bebek Bakımı", "Ev İşleri Yardımı", "Alışveriş Yardımı", "Temizlik Malzemesi"
    ]
  }
];

// Slug oluşturma fonksiyonu
function createSlug(text: string): string {
  return text
    .toLowerCase()
    .replace(/ğ/g, 'g')
    .replace(/ü/g, 'u')
    .replace(/ş/g, 's')
    .replace(/ı/g, 'i')
    .replace(/ö/g, 'o')
    .replace(/ç/g, 'c')
    .replace(/\s+/g, '-')
    .replace(/[^a-z0-9-]/g, '');
}

async function main() {
  console.log('🌍 Tüm Türkiye şehirleri ve ilçeleri ekleniyor...');

  // Önce mevcut verileri temizle
  await prisma.userCategory.deleteMany();
  await prisma.serviceCategory.deleteMany();
  await prisma.district.deleteMany();
  await prisma.city.deleteMany();

  console.log('✅ Mevcut veriler temizlendi');

  // Şehir ve ilçeleri ekle
  for (const cityData of allCitiesData) {
    console.log(`📍 ${cityData.name} şehri ekleniyor...`);
    
    const city = await prisma.city.create({
      data: {
        name: cityData.name,
        slug: createSlug(cityData.name),
        plateCode: cityData.plateCode,
        region: cityData.region,
        isActive: true
      }
    });

    // İlçeleri ekle
    for (const districtName of cityData.districts) {
      await prisma.district.create({
        data: {
          name: districtName,
          slug: createSlug(districtName),
          cityId: city.id,
          isActive: true
        }
      });
    }
  }

  console.log('✅ Tüm şehirler ve ilçeler eklendi');

  // Kategorileri ekle
  console.log('📂 Kategoriler ekleniyor...');
  
  for (const categoryData of categoriesData) {
    // Ana kategori oluştur
    const mainCategory = await prisma.serviceCategory.create({
      data: {
        name: categoryData.name,
        slug: createSlug(categoryData.name),
        description: `${categoryData.name} ile ilgili tüm hizmetler`,
        isActive: true,
        order: categoriesData.indexOf(categoryData)
      }
    });

    // Alt kategorileri oluştur
    for (const childName of categoryData.children) {
      const childSlug = createSlug(childName);
      const uniqueSlug = `${createSlug(categoryData.name)}-${childSlug}`;
      
      await prisma.serviceCategory.create({
        data: {
          name: childName,
          slug: uniqueSlug,
          description: `${childName} hizmeti`,
          parentId: mainCategory.id,
          isActive: true,
          order: categoryData.children.indexOf(childName)
        }
      });
    }
  }

  console.log('✅ Tüm kategoriler eklendi');

  // İstatistikleri göster
  const cityCount = await prisma.city.count();
  const districtCount = await prisma.district.count();
  const categoryCount = await prisma.serviceCategory.count();

  console.log('\n🎉 Veri ekleme tamamlandı!');
  console.log(`📊 İstatistikler:`);
  console.log(`   • Şehir sayısı: ${cityCount}`);
  console.log(`   • İlçe sayısı: ${districtCount}`);
  console.log(`   • Kategori sayısı: ${categoryCount}`);
}

main()
  .catch((e) => {
    console.error('❌ Hata:', e);
    process.exit(1);
  })
  .finally(async () => {
    await prisma.$disconnect();
  }); 
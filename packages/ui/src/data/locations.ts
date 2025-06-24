export const CITIES_DISTRICTS = {
  "İstanbul": [
    "Adalar", "Arnavutköy", "Ataşehir", "Avcılar", "Bağcılar", "Bahçelievler", 
    "Bakırköy", "Başakşehir", "Bayrampaşa", "Beşiktaş", "Beykoz", "Beylikdüzü", 
    "Beyoğlu", "Büyükçekmece", "Çatalca", "Çekmeköy", "Esenler", "Esenyurt", 
    "Etiler", "Eyüpsultan", "Fatih", "Gaziosmanpaşa", "Güngören", "Kadıköy", 
    "Kağıthane", "Kartal", "Küçükçekmece", "Maltepe", "Pendik", "Sancaktepe", 
    "Sarıyer", "Silivri", "Sultanbeyli", "Sultangazi", "Şile", "Şişli", 
    "Tuzla", "Ümraniye", "Üsküdar", "Zeytinburnu"
  ],
  "Ankara": [
    "Akyurt", "Altındağ", "Ayaş", "Bala", "Beypazarı", "Çamlıdere", "Çankaya", 
    "Çubuk", "Elmadağ", "Etimesgut", "Evren", "Gölbaşı", "Güdül", "Haymana", 
    "Kalecik", "Kazan", "Keçiören", "Kızılcahamam", "Mamak", "Nallıhan", 
    "Polatlı", "Pursaklar", "Sincan", "Şereflikoçhisar", "Yenimahalle"
  ],
  "İzmir": [
    "Aliağa", "Balçova", "Bayındır", "Bayraklı", "Bergama", "Beydağ", "Bornova", 
    "Buca", "Çeşme", "Çiğli", "Dikili", "Foça", "Gaziemir", "Güzelbahçe", 
    "Karabağlar", "Karaburun", "Karşıyaka", "Kemalpaşa", "Kınık", "Kiraz", 
    "Konak", "Menderes", "Menemen", "Narlıdere", "Ödemiş", "Seferihisar", 
    "Selçuk", "Tire", "Torbalı", "Urla"
  ],
  "Bursa": [
    "Büyükorhan", "Gemlik", "Gürsu", "Harmancık", "İnegöl", "İznik", "Karacabey", 
    "Keles", "Kestel", "Mudanya", "Mustafakemalpaşa", "Nilüfer", "Orhaneli", 
    "Orhangazi", "Osmangazi", "Yenişehir", "Yıldırım"
  ],
  "Antalya": [
    "Akseki", "Aksu", "Alanya", "Demre", "Döşemealtı", "Elmalı", "Finike", 
    "Gazipaşa", "Gündoğmuş", "İbradı", "Kaş", "Kemer", "Kepez", "Konyaaltı", 
    "Korkuteli", "Kumluca", "Manavgat", "Muratpaşa", "Serik"
  ],
  "Adana": [
    "Aladağ", "Ceyhan", "Çukurova", "Feke", "İmamoğlu", "Karaisalı", "Karataş", 
    "Kozan", "Pozantı", "Saimbeyli", "Sarıçam", "Seyhan", "Tufanbeyli", "Yumurtalık", "Yüreğir"
  ],
  "Adıyaman": [
    "Besni", "Çelikhan", "Gerger", "Gölbaşı", "Kahta", "Merkez", "Samsat", "Sincik", "Tut"
  ],
  "Afyonkarahisar": [
    "Başmakçı", "Bayat", "Bolvadin", "Çay", "Çobanlar", "Dazkırı", "Dinar", 
    "Emirdağ", "Evciler", "Hocalar", "İhsaniye", "İscehisar", "Kızılören", 
    "Merkez", "Sandıklı", "Sinanpaşa", "Sultandağı", "Şuhut"
  ]
} as const;

export const getCities = (): string[] => {
  return Object.keys(CITIES_DISTRICTS);
};

export const getDistricts = (city: string): readonly string[] => {
  return CITIES_DISTRICTS[city as keyof typeof CITIES_DISTRICTS] || [];
};

export type City = keyof typeof CITIES_DISTRICTS;
export type District<T extends City> = typeof CITIES_DISTRICTS[T][number]; 
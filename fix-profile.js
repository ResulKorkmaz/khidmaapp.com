// Browser console'da çalıştırılacak kod - localhost:3000 için
console.log('🔧 OnlineUsta.com.tr Profil Düzeltme Scripti');
console.log('📍 localhost:3000/hizmetveren-profil için hazırlanıyor...');

localStorage.setItem('isLoggedIn', 'true');
localStorage.setItem('currentUser', JSON.stringify({
  id: "cmc7xjs7z0005xx62dw4kzedz",
  email: "test@example.com",
  firstName: "Test",
  lastName: "Kullanıcı",
  role: "PROFESSIONAL",
  city: "İstanbul",
  district: "Kadıköy",
  phone: "05551234567",
  bio: "Deneyimli usta",
  experienceYears: 5,
  rating: 4.8,
  reviewCount: 25,
  completedJobsCount: 100,
  isVerified: true,
  categories: [
    {id: 1, name: "Tadilat ve Dekorasyon"},
    {id: 2, name: "Elektrik"}
  ]
}));

console.log('✅ Kullanıcı verisi localStorage\'a kaydedildi!');
console.log('🔄 Sayfayı yenileyin (F5)');
console.log('🎯 Toast bildirimlerini test etmek için profil bilgilerinizi düzenleyin!');

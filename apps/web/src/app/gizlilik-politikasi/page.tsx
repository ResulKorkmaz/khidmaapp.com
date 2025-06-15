import type { Metadata } from "next";
import Footer from "../../components/Footer";

export const metadata: Metadata = {
  title: "Gizlilik ve Kişisel Verilerin Korunması Politikası - OnlineUsta",
  description: "OnlineUsta gizlilik politikası ve kişisel verilerin korunması hakkında bilgiler. Finansal bilgi ve TC kimlik bilgisi talep etmiyoruz.",
};

export default function GizlilikPolitikasi() {
  return (
    <>
      <div className="min-h-screen bg-gray-50 py-12 pt-24">
        <div className="container mx-auto px-4 max-w-4xl">
          <div className="bg-white rounded-lg shadow-sm p-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-8">
              Gizlilik ve Kişisel Verilerin Korunması Politikası
            </h1>
            
            <div className="prose prose-lg max-w-none">
              <p className="text-gray-600 mb-6">
                Son güncelleme: {new Date().toLocaleDateString('tr-TR')}
              </p>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">1. Giriş</h2>
                <p className="text-gray-700 mb-4">
                  OnlineUsta olarak, kişisel verilerinizin korunması konusunda hassasiyetle 
                  hareket etmekteyiz. Bu politika, 6698 sayılı Kişisel Verilerin Korunması 
                  Kanunu (KVKK) kapsamında hazırlanmış olup, platformumuzun ücretsiz hizmet 
                  verdiği mevcut dönemde geçerlidir.
                </p>
                <div className="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                  <h3 className="text-lg font-semibold text-green-800 mb-2">🔒 Güvenlik Taahhüdümüz</h3>
                  <ul className="text-green-700 space-y-1">
                    <li>• <strong>TC Kimlik Numarası talep etmiyoruz</strong></li>
                    <li>• <strong>Finansal bilgi (banka hesabı, kredi kartı) istemiyoruz</strong></li>
                    <li>• <strong>Ödeme bilgisi saklamıyoruz</strong></li>
                    <li>• <strong>Şimdilik tamamen ücretsiz hizmet veriyoruz</strong></li>
                  </ul>
                </div>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">2. Toplanan Kişisel Veriler</h2>
                <p className="text-gray-700 mb-4">
                  OnlineUsta platformunda <strong>yalnızca aşağıdaki temel bilgiler</strong> toplanmaktadır:
                </p>
                
                <div className="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-4">
                  <h3 className="text-lg font-semibold text-blue-800 mb-3">✅ Topladığımız Bilgiler</h3>
                  <ul className="list-disc pl-6 text-blue-700 space-y-2">
                    <li><strong>Temel kimlik bilgileri:</strong> Ad, soyad</li>
                    <li><strong>İletişim bilgileri:</strong> Telefon numarası, e-posta adresi</li>
                    <li><strong>Hizmet bilgileri:</strong> Sunduğunuz/talep ettiğiniz hizmet türleri</li>
                    <li><strong>Hesap türü:</strong> Bireysel veya şirket hesabı bilgisi</li>
                    <li><strong>Şirket bilgileri:</strong> Şirket adı, şirket telefonu, şirket e-postası (sadece şirket hesapları için)</li>
                    <li><strong>Teknik veriler:</strong> IP adresi, tarayıcı bilgileri (güvenlik amaçlı)</li>
                  </ul>
                </div>

                <div className="bg-red-50 border border-red-200 rounded-lg p-6">
                  <h3 className="text-lg font-semibold text-red-800 mb-3">❌ Toplamadığımız Bilgiler</h3>
                  <ul className="list-disc pl-6 text-red-700 space-y-2">
                    <li><strong>TC Kimlik Numarası</strong> - Hiçbir şekilde talep etmiyoruz</li>
                    <li><strong>Finansal bilgiler</strong> - Banka hesabı, kredi kartı bilgileri</li>
                    <li><strong>Ödeme bilgileri</strong> - Herhangi bir ödeme bilgisi</li>
                    <li><strong>Hassas kişisel veriler</strong> - Sağlık, din, siyasi görüş vb.</li>
                    <li><strong>Adres bilgileri</strong> - Ev veya iş adresi</li>
                  </ul>
                </div>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">3. Verilerin İşlenme Amaçları</h2>
                <p className="text-gray-700 mb-4">Toplanan kişisel verileriniz <strong>yalnızca</strong> aşağıdaki amaçlarla işlenmektedir:</p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li><strong>Hesap oluşturma ve yönetimi</strong> - Platform kullanımı için</li>
                  <li><strong>Hizmet eşleştirme</strong> - Hizmet veren ve alan kişileri buluşturma</li>
                  <li><strong>İletişim sağlama</strong> - Kullanıcılar arası güvenli iletişim</li>
                  <li><strong>Platform güvenliği</strong> - Sahte hesap ve kötüye kullanım önleme</li>
                  <li><strong>Müşteri desteği</strong> - Teknik destek ve yardım</li>
                  <li><strong>Yasal yükümlülükler</strong> - Kanuni gerekliliklerin yerine getirilmesi</li>
                  <li><strong>Platform geliştirme</strong> - Hizmet kalitesini artırma (anonim verilerle)</li>
                </ul>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">4. Ücretsiz Hizmet Dönemi</h2>
                <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                  <h3 className="text-lg font-semibold text-yellow-800 mb-3">💰 Ödeme ve Finansal Bilgiler</h3>
                  <p className="text-yellow-700 mb-3">
                    OnlineUsta şu anda <strong>tamamen ücretsiz</strong> hizmet vermektedir. Bu nedenle:
                  </p>
                  <ul className="list-disc pl-6 text-yellow-700 space-y-2">
                    <li>Herhangi bir ödeme işlemi yapılmamaktadır</li>
                    <li>Kredi kartı veya banka bilgisi talep edilmemektedir</li>
                    <li>Finansal veri işlenmemektedir</li>
                    <li>Gelecekte ücretli hizmet sunulması durumunda, bu politika güncellenecek ve kullanıcılar bilgilendirilecektir</li>
                  </ul>
                </div>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">5. Veri Güvenliği</h2>
                <p className="text-gray-700 mb-4">
                  Kişisel verilerinizin güvenliği için aşağıdaki teknik ve idari tedbirler alınmaktadır:
                </p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li><strong>Şifreleme:</strong> Tüm veriler şifrelenmiş olarak saklanır</li>
                  <li><strong>Güvenli sunucular:</strong> Veriler güvenli veri merkezlerinde tutulur</li>
                  <li><strong>Erişim kontrolü:</strong> Verilere sadece yetkili personel erişebilir</li>
                  <li><strong>Düzenli yedekleme:</strong> Veri kaybına karşı düzenli yedekleme</li>
                  <li><strong>Güvenlik güncellemeleri:</strong> Sistem güvenliği sürekli güncellenir</li>
                </ul>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">6. Veri Saklama Süresi</h2>
                <p className="text-gray-700 mb-4">
                  Kişisel verileriniz aşağıdaki süreler boyunca saklanır:
                </p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li><strong>Aktif hesaplar:</strong> Hesap aktif olduğu sürece</li>
                  <li><strong>Pasif hesaplar:</strong> Son aktiviteden itibaren 2 yıl</li>
                  <li><strong>Silinen hesaplar:</strong> Silme talebinden sonra 30 gün içinde tamamen silinir</li>
                  <li><strong>Yasal gereklilikler:</strong> Kanuni saklama yükümlülükleri varsa o süre boyunca</li>
                </ul>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">7. Üçüncü Taraflarla Veri Paylaşımı</h2>
                <p className="text-gray-700 mb-4">
                  Kişisel verileriniz <strong>yalnızca aşağıdaki durumlarda</strong> üçüncü taraflarla paylaşılır:
                </p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li><strong>Yasal zorunluluk:</strong> Mahkeme kararı veya yasal talep durumunda</li>
                  <li><strong>Güvenlik:</strong> Platform güvenliği için gerekli durumlarda</li>
                  <li><strong>Hizmet sağlayıcılar:</strong> Teknik altyapı sağlayıcıları (veri işleme sözleşmesi ile)</li>
                  <li><strong>Açık rıza:</strong> Sizin açık onayınız ile</li>
                </ul>
                <p className="text-gray-700 mt-4">
                  <strong>Verileriniz hiçbir şekilde ticari amaçla satılmaz veya kiralanmaz.</strong>
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">8. KVKK Kapsamındaki Haklarınız</h2>
                <p className="text-gray-700 mb-4">6698 sayılı KVKK kapsamında aşağıdaki haklara sahipsiniz:</p>
                <div className="grid md:grid-cols-2 gap-4">
                  <div className="bg-gray-50 p-4 rounded-lg">
                    <h4 className="font-semibold text-gray-800 mb-2">Bilgi Alma Hakları</h4>
                    <ul className="text-sm text-gray-700 space-y-1">
                      <li>• Verilerinizin işlenip işlenmediğini öğrenme</li>
                      <li>• İşlenen verileriniz hakkında bilgi alma</li>
                      <li>• İşlenme amacını öğrenme</li>
                      <li>• Veri aktarılan üçüncü kişileri bilme</li>
                    </ul>
                  </div>
                  <div className="bg-gray-50 p-4 rounded-lg">
                    <h4 className="font-semibold text-gray-800 mb-2">Düzeltme ve Silme Hakları</h4>
                    <ul className="text-sm text-gray-700 space-y-1">
                      <li>• Yanlış verilerin düzeltilmesini isteme</li>
                      <li>• Verilerin silinmesini isteme</li>
                      <li>• Verilerin yok edilmesini isteme</li>
                      <li>• Zararın giderilmesini talep etme</li>
                    </ul>
                  </div>
                </div>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">9. Çerezler (Cookies)</h2>
                <p className="text-gray-700 mb-4">
                  Platformumuzda kullanıcı deneyimini iyileştirmek için çerezler kullanılmaktadır:
                </p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li><strong>Zorunlu çerezler:</strong> Platform işleyişi için gerekli</li>
                  <li><strong>Performans çerezleri:</strong> Site performansını ölçmek için</li>
                  <li><strong>Fonksiyonel çerezler:</strong> Kullanıcı tercihlerini hatırlamak için</li>
                </ul>
                <p className="text-gray-700 mt-4">
                  Tarayıcı ayarlarınızdan çerezleri yönetebilir veya silebilirsiniz.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">10. Politika Güncellemeleri</h2>
                <p className="text-gray-700 mb-4">
                  Bu gizlilik politikası gerektiğinde güncellenebilir. Önemli değişiklikler 
                  durumunda kullanıcılar e-posta veya platform üzerinden bilgilendirilecektir.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">11. İletişim ve Başvuru</h2>
                <div className="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                  <p className="text-indigo-700 mb-4">
                    Kişisel verilerinizle ilgili taleplerinizi aşağıdaki kanallardan bize iletebilirsiniz:
                  </p>
                  <div className="space-y-2 text-indigo-700">
                    <p>
                      <strong>E-posta:</strong> 
                      <a href="mailto:kvkk@onlineusta.com.tr" className="text-indigo-600 hover:text-indigo-800 ml-1 underline">
                        kvkk@onlineusta.com.tr
                      </a>
                    </p>
                    <p>
                      <strong>Konu:</strong> KVKK Başvurusu - [Talebinizin türü]
                    </p>
                    <p className="text-sm mt-4">
                      <strong>Yanıt süresi:</strong> Başvurularınız en geç 30 gün içinde yanıtlanacaktır.
                    </p>
                  </div>
                </div>
              </section>

              <div className="bg-green-50 border border-green-200 rounded-lg p-6 mt-8">
                <h3 className="text-lg font-semibold text-green-800 mb-2">📞 Sorularınız mı var?</h3>
                <p className="text-green-700">
                  Bu politika hakkında herhangi bir sorunuz varsa, lütfen bizimle iletişime geçin. 
                  Kişisel verilerinizin korunması bizim için önceliktir.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
} 
import type { Metadata } from "next";
import Footer from "../../components/Footer";

export const metadata: Metadata = {
  title: "Çerez Politikası",
  description: "OnlineUsta çerez politikası ve çerez kullanımı hakkında bilgiler",
};

export default function CerezPolitikasi() {
  return (
    <>
      <div className="min-h-screen bg-gray-50 py-12">
        <div className="container mx-auto px-4 max-w-4xl">
          <div className="bg-white rounded-lg shadow-sm p-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-8">Çerez Politikası</h1>
            
            <div className="prose prose-lg max-w-none">
              <p className="text-gray-600 mb-6">
                Son güncelleme: {new Date().toLocaleDateString('tr-TR')}
              </p>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">1. Çerez Nedir?</h2>
                <p className="text-gray-700 mb-4">
                  Çerezler, web sitelerini ziyaret ettiğinizde cihazınıza (bilgisayar, tablet, telefon) 
                  kaydedilen küçük metin dosyalarıdır. Bu dosyalar, web sitesinin daha iyi çalışmasını 
                  ve kullanıcı deneyiminin geliştirilmesini sağlar.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">2. Çerez Türleri</h2>
                <p className="text-gray-700 mb-4">OnlineUsta platformunda aşağıdaki çerez türlerini kullanmaktayız:</p>
                
                <div className="space-y-4">
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">Zorunlu Çerezler</h3>
                    <p className="text-gray-700">
                      Web sitesinin temel işlevlerini yerine getirmesi için gerekli olan çerezlerdir. 
                      Bu çerezler olmadan site düzgün çalışmaz.
                    </p>
                  </div>
                  
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">Performans Çerezleri</h3>
                    <p className="text-gray-700">
                      Web sitesinin performansını ölçmek ve iyileştirmek için kullanılır. 
                      Hangi sayfaların en çok ziyaret edildiği gibi bilgileri toplar.
                    </p>
                  </div>
                  
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">İşlevsellik Çerezleri</h3>
                    <p className="text-gray-700">
                      Tercihlerinizi hatırlamak ve kişiselleştirilmiş deneyim sunmak için kullanılır.
                    </p>
                  </div>
                  
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 mb-2">Pazarlama Çerezleri</h3>
                    <p className="text-gray-700">
                      Size daha alakalı reklamlar göstermek ve pazarlama kampanyalarının 
                      etkinliğini ölçmek için kullanılır.
                    </p>
                  </div>
                </div>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">3. Çerez Kullanım Amaçları</h2>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li>Oturum yönetimi ve güvenlik</li>
                  <li>Kullanıcı tercihlerini hatırlama</li>
                  <li>Site performansını analiz etme</li>
                  <li>Kullanıcı deneyimini kişiselleştirme</li>
                  <li>Pazarlama ve reklam optimizasyonu</li>
                  <li>Teknik sorunları tespit etme</li>
                </ul>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">4. Üçüncü Taraf Çerezleri</h2>
                <p className="text-gray-700 mb-4">
                  Platformumuzda aşağıdaki üçüncü taraf hizmetlerinin çerezleri kullanılabilir:
                </p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li>Google Analytics (web sitesi analizi)</li>
                  <li>Google Ads (reklam gösterimi)</li>
                  <li>Facebook Pixel (sosyal medya entegrasyonu)</li>
                  <li>Ödeme sağlayıcıları (güvenli ödeme işlemleri)</li>
                </ul>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">5. Çerez Yönetimi</h2>
                <p className="text-gray-700 mb-4">
                  Çerez tercihlerinizi aşağıdaki yollarla yönetebilirsiniz:
                </p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li>Tarayıcı ayarlarından çerezleri devre dışı bırakabilirsiniz</li>
                  <li>Mevcut çerezleri silebilirsiniz</li>
                  <li>Çerez bildirimleri alabilirsiniz</li>
                  <li>Belirli çerez türlerini engelleyebilirsiniz</li>
                </ul>
                <p className="text-gray-700 mt-4">
                  <strong>Not:</strong> Zorunlu çerezleri devre dışı bırakmanız durumunda 
                  web sitesinin bazı özellikleri düzgün çalışmayabilir.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">6. Çerez Saklama Süresi</h2>
                <p className="text-gray-700 mb-4">
                  Çerezler türlerine göre farklı sürelerde saklanır:
                </p>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li><strong>Oturum Çerezleri:</strong> Tarayıcı kapatıldığında silinir</li>
                  <li><strong>Kalıcı Çerezler:</strong> Belirli bir süre (genellikle 1-2 yıl) saklanır</li>
                  <li><strong>Güvenlik Çerezleri:</strong> Oturum süresince aktif kalır</li>
                </ul>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">7. İletişim</h2>
                <p className="text-gray-700">
                  Çerez politikamız hakkında sorularınız için:
                </p>
                <p className="text-gray-700 mt-2">
                  E-posta: 
                  <a href="mailto:info@onlineusta.com.tr" className="text-green-500 hover:text-green-600 ml-1">
                    info@onlineusta.com.tr
                  </a>
                </p>
              </section>
            </div>
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
} 
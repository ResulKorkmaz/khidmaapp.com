import type { Metadata } from "next";
import Footer from "../../components/Footer";

export const metadata: Metadata = {
  title: "Kullanıcı Sözleşmesi",
  description: "OnlineUsta kullanıcı sözleşmesi ve hizmet şartları",
};

export default function KullaniciSozlesmesi() {
  return (
    <>
      <div className="min-h-screen bg-gray-50 py-12">
        <div className="container mx-auto px-4 max-w-4xl">
          <div className="bg-white rounded-lg shadow-sm p-8">
            <h1 className="text-3xl font-bold text-gray-900 mb-8">Kullanıcı Sözleşmesi</h1>
            
            <div className="prose prose-lg max-w-none">
              <p className="text-gray-600 mb-6">
                Son güncelleme: {new Date().toLocaleDateString('tr-TR')}
              </p>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">1. Genel Hükümler</h2>
                <p className="text-gray-700 mb-4">
                  Bu sözleşme, OnlineUsta platformunu kullanan tüm kullanıcılar için geçerlidir. 
                  Platformumuzu kullanarak bu şartları kabul etmiş sayılırsınız.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">2. Hizmet Tanımı</h2>
                <p className="text-gray-700 mb-4">
                  OnlineUsta, hizmet arayan müşteriler ile hizmet veren profesyonelleri buluşturan 
                  bir dijital platformdur. Platform, aracılık hizmeti sunmakta olup, 
                  gerçekleştirilen işlerin kalitesinden sorumlu değildir.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">3. Kullanıcı Yükümlülükleri</h2>
                <ul className="list-disc pl-6 text-gray-700 space-y-2">
                  <li>Doğru ve güncel bilgiler sağlamak</li>
                  <li>Platform kurallarına uymak</li>
                  <li>Diğer kullanıcılara saygılı davranmak</li>
                  <li>Yasalara uygun hareket etmek</li>
                </ul>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">4. Ödeme ve Faturalandırma</h2>
                <p className="text-gray-700 mb-4">
                  Platform üzerinden gerçekleştirilen ödemeler güvenli ödeme sistemleri 
                  aracılığıyla yapılır. Komisyon oranları ve ödeme koşulları 
                  platform üzerinde belirtilmiştir.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">5. Sorumluluk Sınırlaması</h2>
                <p className="text-gray-700 mb-4">
                  OnlineUsta, platform üzerinde sunulan hizmetlerin kalitesi, 
                  güvenliği veya sonuçlarından sorumlu değildir. 
                  Kullanıcılar kendi sorumluluklarında hareket ederler.
                </p>
              </section>

              <section className="mb-8">
                <h2 className="text-2xl font-semibold text-gray-900 mb-4">6. İletişim</h2>
                <p className="text-gray-700">
                  Bu sözleşme ile ilgili sorularınız için: 
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
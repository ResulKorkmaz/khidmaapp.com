// Açıklama: Kategori landing page - SEO optimize edilmiş
import { Metadata } from 'next';
import { notFound } from 'next/navigation';
import Link from 'next/link';

interface CategoryPageProps {
  params: {
    slug: string;
  };
}

// Mock data - real data would come from API
const mockCategories = {
  'ev-temizligi': {
    id: 1,
    name: 'Ev Temizliği',
    slug: 'ev-temizligi',
    description: 'Profesyonel ev temizlik hizmetleri ile eviniz tertemiz olsun',
    icon: '🏠',
    image: '/images/categories/ev-temizligi.jpg',
    metaTitle: 'Ev Temizliği Hizmeti | OnlineUsta',
    metaDescription: 'Profesyonel ev temizliği hizmeti al. Güvenilir temizlik ustalarından hızlı teklif al.',
    children: [
      {
        id: 6,
        name: 'Genel Ev Temizliği',
        slug: 'genel-ev-temizligi',
        description: 'Komple ev temizlik hizmetleri',
        icon: '🧽',
      },
      {
        id: 7,
        name: 'Cam Temizliği',
        slug: 'cam-temizligi',
        description: 'Profesyonel cam temizlik hizmetleri',
        icon: '🪟',
      },
    ],
    benefits: [
      'Uzman temizlik elemanları',
      'Profesyonel temizlik malzemeleri',
      'Güvenilir ve sigortalı hizmet',
      'Esnek çalışma saatleri',
    ],
    faqs: [
      {
        question: 'Temizlik hizmeti ne kadar sürer?',
        answer: 'Evinizin büyüklüğüne göre 2-6 saat arasında değişmektedir.',
      },
      {
        question: 'Temizlik malzemeleri dahil mi?',
        answer: 'Evet, tüm temizlik malzemeleri hizmet kapsamındadır.',
      },
    ],
  },
  'elektrik': {
    id: 2,
    name: 'Elektrik',
    slug: 'elektrik',
    description: 'Elektrik tesisatı ve onarım hizmetleri',
    icon: '⚡',
    image: '/images/categories/elektrik.jpg',
    metaTitle: 'Elektrikçi Hizmeti | OnlineUsta',
    metaDescription: 'Güvenilir elektrikçilerden hızlı teklif al. Elektrik arıza, tesisat ve onarım hizmetleri.',
    children: [
      {
        id: 8,
        name: 'Elektrik Arızası',
        slug: 'elektrik-arizasi',
        description: 'Elektrik arıza tespit ve onarım',
        icon: '🔧',
      },
    ],
    benefits: [
      'Lisanslı elektrikçiler',
      '7/24 acil servis',
      'Garanti belgeli hizmet',
      'Güvenlik standartlarına uygun',
    ],
    faqs: [
      {
        question: 'Elektrik arızası ne kadar sürede çözülür?',
        answer: 'Arızanın türüne göre 1-4 saat arasında değişmektedir.',
      },
    ],
  },
};

export async function generateMetadata({
  params,
}: CategoryPageProps): Promise<Metadata> {
  const category = mockCategories[params.slug as keyof typeof mockCategories];
  
  if (!category) {
    return {
      title: 'Kategori Bulunamadı',
    };
  }

  return {
    title: category.metaTitle,
    description: category.metaDescription,
    openGraph: {
      title: category.metaTitle,
      description: category.metaDescription,
      images: [
        {
          url: category.image,
          width: 1200,
          height: 630,
          alt: category.name,
        },
      ],
    },
  };
}

export default function CategoryPage({ params }: CategoryPageProps) {
  const category = mockCategories[params.slug as keyof typeof mockCategories];

  if (!category) {
    notFound();
  }

  return (
    <div className="min-h-screen bg-white">
      {/* Hero Section */}
      <section className="bg-gradient-to-r from-brand-600 to-brand-700 text-white py-16">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <div className="text-6xl mb-4">{category.icon}</div>
            <h1 className="text-4xl font-bold mb-4">{category.name}</h1>
            <p className="text-xl mb-8 opacity-90">{category.description}</p>
            <Link href="/hizmet-talep" className="inline-block bg-white text-green-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg">
                Hemen Teklif Al
            </Link>
          </div>
        </div>
      </section>

      {/* Subcategories */}
      <section className="py-16">
        <div className="container mx-auto px-4">
          <div className="max-w-6xl mx-auto">
            <h2 className="text-3xl font-bold text-center mb-12">
              {category.name} Hizmet Türleri
            </h2>
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {category.children?.map((subcategory) => (
                <div
                  key={subcategory.id}
                  className="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow"
                >
                  <div className="text-4xl mb-4">{subcategory.icon}</div>
                  <h3 className="text-xl font-semibold mb-2">
                    {subcategory.name}
                  </h3>
                  <p className="text-gray-600 mb-4">{subcategory.description}</p>
                  <Link href={`/kategoriler/${subcategory.slug}`} className="inline-block border border-green-600 text-green-600 font-semibold py-2 px-4 rounded-lg hover:bg-green-600 hover:text-white transition-colors duration-300">
                      Detayları Gör
                  </Link>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Benefits */}
      <section className="py-16 bg-gray-50">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl font-bold text-center mb-12">
              Neden {category.name} Hizmeti?
            </h2>
            <div className="grid md:grid-cols-2 gap-6">
              {category.benefits?.map((benefit, index) => (
                <div key={index} className="flex items-center">
                  <div className="bg-brand-600 text-white rounded-full w-8 h-8 flex items-center justify-center mr-4">
                    ✓
                  </div>
                  <span className="text-lg">{benefit}</span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* FAQ */}
      <section className="py-16">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-3xl font-bold text-center mb-12">
              Sık Sorulan Sorular
            </h2>
            <div className="space-y-6">
              {category.faqs?.map((faq, index) => (
                <div key={index} className="bg-white rounded-lg shadow-md p-6">
                  <h3 className="text-lg font-semibold mb-2">{faq.question}</h3>
                  <p className="text-gray-600">{faq.answer}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* CTA */}
      <section className="py-16 bg-brand-600 text-white">
        <div className="container mx-auto px-4 text-center">
          <h2 className="text-3xl font-bold mb-4">
            Hemen {category.name} Talebi Oluşturun
          </h2>
          <p className="text-xl mb-8 opacity-90">
            Profesyonel hizmet sağlayıcılardan ücretsiz teklif alın
          </p>
          <Link href="/hizmet-talep" className="inline-block bg-white text-green-600 font-bold py-4 px-8 rounded-xl hover:bg-gray-100 transition-colors duration-300 shadow-lg">
              Talep Oluştur
          </Link>
        </div>
      </section>
    </div>
  );
} 
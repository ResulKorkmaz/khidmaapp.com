"use client";

// Açıklama: Hizmet talep formu sayfası
import { ServiceRequestWizard } from "@onlineusta/ui";

// Mock data - real data would come from API
const mockCategories = [
  {
    id: 1,
    name: "Ev Temizliği",
    slug: "ev-temizligi",
    icon: "🏠",
    children: [
      {
        id: 6,
        name: "Genel Ev Temizliği",
        slug: "genel-ev-temizligi",
        icon: "🧽",
      },
      {
        id: 7,
        name: "Cam Temizliği",
        slug: "cam-temizligi",
        icon: "🪟",
      },
    ],
  },
  {
    id: 2,
    name: "Elektrik",
    slug: "elektrik",
    icon: "⚡",
    children: [
      {
        id: 8,
        name: "Elektrik Arızası",
        slug: "elektrik-arizasi",
        icon: "🔧",
      },
    ],
  },
];

export default function ServiceRequestPage() {
  const handleSubmit = async (data: any) => {
    try {
      // API call will be implemented here
      console.log("Service request data:", data);
      
      // Redirect to success page or show success message
      alert("Hizmet talebiniz başarıyla oluşturuldu!");
    } catch (error) {
      console.error("Error creating service request:", error);
      alert("Bir hata oluştu. Lütfen tekrar deneyin.");
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 py-12">
      <div className="container mx-auto px-4">
        <div className="text-center mb-8">
          <h1 className="text-3xl font-bold text-gray-900 mb-4">
            Hizmet Talebinizi Oluşturun
          </h1>
          <p className="text-lg text-gray-600 max-w-2xl mx-auto">
            Sadece birkaç adımda hizmet talebinizi oluşturun ve 
            profesyonel hizmet sağlayıcılarından teklifler alın.
          </p>
        </div>
        
        <ServiceRequestWizard
          categories={mockCategories}
          onSubmit={handleSubmit}
          className="mb-12"
        />
      </div>
    </div>
  );
} 
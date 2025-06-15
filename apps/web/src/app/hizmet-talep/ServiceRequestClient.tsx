"use client";

// import { ServiceRequestWizard } from "@onlineusta/ui";

interface ServiceRequestClientProps {
  categories: any[];
}

export default function ServiceRequestClient({ categories }: ServiceRequestClientProps) {
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
    <div className="text-center p-8 bg-white rounded-lg shadow-sm">
      <p className="text-gray-500">Hizmet talep formu geliştiriliyor...</p>
    </div>
  );
} 
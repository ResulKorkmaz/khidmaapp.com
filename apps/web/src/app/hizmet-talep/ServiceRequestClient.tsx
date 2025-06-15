"use client";

import { ServiceRequestWizard } from "@onlineusta/ui";

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
    <ServiceRequestWizard
      categories={categories}
      onSubmit={handleSubmit}
      className="mb-12"
    />
  );
} 
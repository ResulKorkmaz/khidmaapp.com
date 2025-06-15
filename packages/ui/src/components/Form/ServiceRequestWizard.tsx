// Açıklama: Çok adımlı hizmet talep formu - wizard pattern ile
"use client";

import { useState } from "react";
import { Button } from "../Button/Button";
import { cn } from "../../utils/cn";

interface ServiceRequestWizardProps {
  categories: Array<{
    id: number;
    name: string;
    slug: string;
    icon?: string;
    children?: Array<{
      id: number;
      name: string;
      slug: string;
      icon?: string;
    }>;
  }>;
  onSubmit: (data: ServiceRequestFormData) => void;
  className?: string;
}

interface ServiceRequestFormData {
  categoryId: number;
  title: string;
  description: string;
  budget?: number;
  city: string;
  district: string;
  address?: string;
  preferredDate?: Date;
  isFlexible: boolean;
  images: string[];
}

const steps = [
  { id: 1, name: "Kategori", description: "Hizmet türünü seçin" },
  { id: 2, name: "Detaylar", description: "İş tanımını yapın" },
  { id: 3, name: "Konum", description: "Adres bilgilerini girin" },
  { id: 4, name: "Tarih", description: "Tercihinizi belirtin" },
  { id: 5, name: "Bütçe", description: "Bütçe aralığını seçin" },
];

export function ServiceRequestWizard({
  categories,
  onSubmit,
  className,
}: ServiceRequestWizardProps) {
  const [currentStep, setCurrentStep] = useState(1);
  const [formData, setFormData] = useState<Partial<ServiceRequestFormData>>({
    isFlexible: true,
    images: [],
  });

  const updateFormData = (data: Partial<ServiceRequestFormData>) => {
    setFormData((prev) => ({ ...prev, ...data }));
  };

  const nextStep = () => {
    if (currentStep < steps.length) {
      setCurrentStep(currentStep + 1);
    }
  };

  const prevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1);
    }
  };

  const handleSubmit = () => {
    if (isFormValid()) {
      onSubmit(formData as ServiceRequestFormData);
    }
  };

  const isFormValid = (): boolean => {
    return !!(
      formData.categoryId &&
      formData.title &&
      formData.description &&
      formData.city &&
      formData.district
    );
  };

  const isStepValid = (step: number): boolean => {
    switch (step) {
      case 1:
        return !!formData.categoryId;
      case 2:
        return !!(formData.title && formData.description);
      case 3:
        return !!(formData.city && formData.district);
      case 4:
        return true; // Optional step
      case 5:
        return true; // Optional step
      default:
        return false;
    }
  };

  return (
    <div className={cn("w-full max-w-4xl mx-auto", className)}>
      {/* Progress Steps */}
      <div className="mb-8">
        <div className="flex items-center justify-between">
          {steps.map((step, index) => (
            <div key={step.id} className="flex items-center">
              <div
                className={cn(
                  "flex h-10 w-10 items-center justify-center rounded-full border-2 text-sm font-medium",
                  currentStep >= step.id
                    ? "border-brand-600 bg-brand-600 text-white"
                    : "border-gray-300 bg-white text-gray-500"
                )}
              >
                {step.id}
              </div>
              <div className="ml-4 min-w-0 flex-1">
                <p
                  className={cn(
                    "text-sm font-medium",
                    currentStep >= step.id ? "text-brand-600" : "text-gray-500"
                  )}
                >
                  {step.name}
                </p>
                <p className="text-sm text-gray-500">{step.description}</p>
              </div>
              {index < steps.length - 1 && (
                <div
                  className={cn(
                    "h-0.5 w-16 mx-4",
                    currentStep > step.id ? "bg-brand-600" : "bg-gray-300"
                  )}
                />
              )}
            </div>
          ))}
        </div>
      </div>

      {/* Step Content */}
      <div className="bg-white rounded-lg shadow-md p-6 min-h-96">
        {currentStep === 1 && (
          <CategoryStep
            categories={categories}
            selectedCategoryId={formData.categoryId}
            onSelectCategory={(categoryId) =>
              updateFormData({ categoryId })
            }
          />
        )}
        {currentStep === 2 && (
          <DetailsStep
            title={formData.title || ""}
            description={formData.description || ""}
            onUpdate={(title, description) =>
              updateFormData({ title, description })
            }
          />
        )}
        {currentStep === 3 && (
          <LocationStep
            city={formData.city || ""}
            district={formData.district || ""}
            address={formData.address || ""}
            onUpdate={(city, district, address) =>
              updateFormData({ city, district, address })
            }
          />
        )}
        {currentStep === 4 && (
          <DateStep
            preferredDate={formData.preferredDate}
            isFlexible={formData.isFlexible || true}
            onUpdate={(preferredDate, isFlexible) =>
              updateFormData({ preferredDate, isFlexible })
            }
          />
        )}
        {currentStep === 5 && (
          <BudgetStep
            budget={formData.budget}
            onUpdate={(budget) => updateFormData({ budget })}
          />
        )}
      </div>

      {/* Navigation */}
      <div className="flex justify-between mt-6">
        <Button
          variant="outline"
          onClick={prevStep}
          disabled={currentStep === 1}
        >
          Geri
        </Button>
        <div className="flex gap-3">
          {currentStep < steps.length ? (
            <Button
              onClick={nextStep}
              disabled={!isStepValid(currentStep)}
            >
              İleri
            </Button>
          ) : (
            <Button
              onClick={handleSubmit}
              disabled={!isFormValid()}
            >
              Talep Oluştur
            </Button>
          )}
        </div>
      </div>
    </div>
  );
}

// Step Components would be implemented here
function CategoryStep({ categories, selectedCategoryId, onSelectCategory }: any) {
  return <div>Category Selection Component</div>;
}

function DetailsStep({ title, description, onUpdate }: any) {
  return <div>Details Input Component</div>;
}

function LocationStep({ city, district, address, onUpdate }: any) {
  return <div>Location Input Component</div>;
}

function DateStep({ preferredDate, isFlexible, onUpdate }: any) {
  return <div>Date Selection Component</div>;
}

function BudgetStep({ budget, onUpdate }: any) {
  return <div>Budget Selection Component</div>;
} 
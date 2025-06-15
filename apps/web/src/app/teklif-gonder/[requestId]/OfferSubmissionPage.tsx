"use client";

import React, { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
// import { OfferForm, OfferFormData } from '@onlineusta/ui';

interface OfferFormData {
  price: number;
  description: string;
  estimatedDuration?: number;
  availableFrom?: string;
}

interface ServiceRequest {
  id: string;
  title: string;
  description: string;
  category: {
    name: string;
    slug: string;
  };
  budget: number;
  city: string;
  district: string;
  customer: {
    firstName: string;
    lastName: string;
  };
  createdAt: string;
  status: string;
}

interface OfferSubmissionPageProps {
  requestId: string;
}

export function OfferSubmissionPage({ requestId }: OfferSubmissionPageProps) {
  const router = useRouter();
  const [serviceRequest, setServiceRequest] = useState<ServiceRequest | null>(null);
  const [loading, setLoading] = useState(true);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetchServiceRequest();
  }, [requestId]);

  const fetchServiceRequest = async () => {
    try {
      setLoading(true);
      const response = await fetch(`/api/service-requests/${requestId}`);
      
      if (!response.ok) {
        throw new Error('Hizmet talebi bulunamadı');
      }

      const data = await response.json();
      setServiceRequest(data.data);
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Bir hata oluştu');
    } finally {
      setLoading(false);
    }
  };

  const handleOfferSubmit = async (offerData: OfferFormData) => {
    try {
      setSubmitting(true);
      setError(null);

      const response = await fetch('http://localhost:3001/api/v1/offers', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'x-user-id': 'temp-professional-id', // TODO: Get from auth
        },
        body: JSON.stringify({
          ...offerData,
          serviceRequestId: requestId,
        }),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Teklif gönderilemedi');
      }

      const result = await response.json();
      
      // Success - redirect to offers page or show success message
      alert('Teklifiniz başarıyla gönderildi!');
      router.push(`/tekliflerim`);
      
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Teklif gönderilirken hata oluştu');
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="animate-spin rounded-full h-32 w-32 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
          <div className="text-center">
            <div className="text-red-600 text-6xl mb-4">⚠️</div>
            <h1 className="text-2xl font-bold text-gray-900 mb-2">Hata</h1>
            <p className="text-gray-600 mb-4">{error}</p>
            <button
              onClick={() => router.back()}
              className="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors"
            >
              Geri Dön
            </button>
          </div>
        </div>
      </div>
    );
  }

  if (!serviceRequest) {
    return null;
  }

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('tr-TR', {
      style: 'currency',
      currency: 'TRY',
    }).format(price);
  };

  const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('tr-TR', {
      day: 'numeric',
      month: 'long',
      year: 'numeric',
    }).format(new Date(dateString));
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <button
            onClick={() => router.back()}
            className="flex items-center text-gray-600 hover:text-gray-900 mb-4"
          >
            ← Geri Dön
          </button>
          <h1 className="text-3xl font-bold text-gray-900">Teklif Gönder</h1>
          <p className="text-gray-600 mt-2">
            Aşağıdaki hizmet talebine teklifinizi gönderin
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
          {/* Service Request Details */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-4">
              Hizmet Talebi Detayları
            </h2>
            
            <div className="space-y-4">
              <div>
                <h3 className="font-semibold text-lg text-gray-900">
                  {serviceRequest.title}
                </h3>
                <span className="inline-block bg-blue-100 text-blue-800 text-sm px-2 py-1 rounded mt-1">
                  {serviceRequest.category.name}
                </span>
              </div>

              <div>
                <h4 className="font-medium text-gray-700 mb-2">Açıklama</h4>
                <p className="text-gray-600 leading-relaxed">
                  {serviceRequest.description}
                </p>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <h4 className="font-medium text-gray-700 mb-1">Bütçe</h4>
                  <p className="text-green-600 font-semibold">
                    {formatPrice(serviceRequest.budget)}
                  </p>
                </div>
                <div>
                  <h4 className="font-medium text-gray-700 mb-1">Konum</h4>
                  <p className="text-gray-600">
                    {serviceRequest.city}, {serviceRequest.district}
                  </p>
                </div>
              </div>

              <div>
                <h4 className="font-medium text-gray-700 mb-1">Müşteri</h4>
                <p className="text-gray-600">
                  {serviceRequest.customer.firstName} {serviceRequest.customer.lastName}
                </p>
              </div>

              <div>
                <h4 className="font-medium text-gray-700 mb-1">Tarih</h4>
                <p className="text-gray-600">
                  {formatDate(serviceRequest.createdAt)}
                </p>
              </div>
            </div>
          </div>

          {/* Offer Form */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <h2 className="text-xl font-semibold text-gray-900 mb-6">
              Teklifinizi Hazırlayın
            </h2>
            
            {/* <OfferForm
              serviceRequestId={requestId}
              onSubmit={handleOfferSubmit}
              loading={submitting}
            /> */}
            <div className="text-center p-8">
              <p className="text-gray-500">Teklif formu geliştiriliyor...</p>
            </div>
          </div>
        </div>

        {/* Tips */}
        <div className="mt-8 bg-blue-50 rounded-lg p-6">
          <h3 className="font-semibold text-blue-900 mb-3">💡 Teklif Verme İpuçları</h3>
          <ul className="text-blue-800 space-y-2 text-sm">
            <li>• Deneyiminizi ve uzmanlık alanlarınızı belirtin</li>
            <li>• Kullanacağınız malzemeler hakkında bilgi verin</li>
            <li>• Gerçekçi bir fiyat ve süre belirleyin</li>
            <li>• Müşterinin sorularına hızlı yanıt vermeye hazır olun</li>
            <li>• Geçmiş çalışma örneklerinizi paylaşabilirsiniz</li>
          </ul>
        </div>
      </div>
    </div>
  );
} 
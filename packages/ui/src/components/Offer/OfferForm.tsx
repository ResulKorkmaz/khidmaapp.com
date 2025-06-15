"use client";

import React, { useState } from 'react';
import { Button } from '../Button';
import { Input } from '../Input';
import { Textarea } from '../Textarea';
import { cn } from '../../lib/utils';

interface OfferFormProps {
  serviceRequestId: string;
  onSubmit: (offerData: OfferFormData) => Promise<void>;
  className?: string;
  loading?: boolean;
}

export interface OfferFormData {
  price: number;
  description: string;
  estimatedDuration?: number;
  availableFrom?: string;
}

export function OfferForm({ 
  serviceRequestId, 
  onSubmit, 
  className,
  loading = false 
}: OfferFormProps) {
  const [formData, setFormData] = useState<OfferFormData>({
    price: 0,
    description: '',
  });
  const [errors, setErrors] = useState<Record<string, string>>({});

  const validateForm = (): boolean => {
    const newErrors: Record<string, string> = {};

    if (!formData.price || formData.price < 1) {
      newErrors.price = 'Fiyat en az 1 TL olmalıdır';
    }

    if (formData.price > 100000) {
      newErrors.price = 'Fiyat en fazla 100.000 TL olabilir';
    }

    if (!formData.description || formData.description.length < 20) {
      newErrors.description = 'Açıklama en az 20 karakter olmalıdır';
    }

    if (formData.description && formData.description.length > 1000) {
      newErrors.description = 'Açıklama en fazla 1000 karakter olabilir';
    }

    if (formData.estimatedDuration && formData.estimatedDuration < 1) {
      newErrors.estimatedDuration = 'Tahmini süre en az 1 saat olmalıdır';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }

    try {
      await onSubmit(formData);
      // Reset form on success
      setFormData({
        price: 0,
        description: '',
      });
      setErrors({});
    } catch (error) {
      console.error('Teklif gönderme hatası:', error);
    }
  };

  const handleChange = (field: keyof OfferFormData, value: any) => {
    setFormData(prev => ({
      ...prev,
      [field]: value,
    }));
    
    // Clear error when user starts typing
    if (errors[field]) {
      setErrors(prev => ({
        ...prev,
        [field]: '',
      }));
    }
  };

  return (
    <form 
      onSubmit={handleSubmit}
      className={cn("space-y-6", className)}
    >
      <div className="space-y-4">
        <div>
          <label htmlFor="price" className="block text-sm font-medium text-gray-700 mb-2">
            Teklif Fiyatı (TL) *
          </label>
          <Input
            id="price"
            type="number"
            min="1"
            max="100000"
            step="0.01"
            value={formData.price || ''}
            onChange={(e) => handleChange('price', parseFloat(e.target.value))}
            placeholder="Örn: 500"
            error={errors.price}
            required
          />
          {errors.price && (
            <p className="mt-1 text-sm text-red-600">{errors.price}</p>
          )}
        </div>

        <div>
          <label htmlFor="description" className="block text-sm font-medium text-gray-700 mb-2">
            Açıklama *
          </label>
          <Textarea
            id="description"
            value={formData.description}
            onChange={(e) => handleChange('description', e.target.value)}
            placeholder="Hizmetinizi detaylı olarak açıklayın. Deneyiminizi, kullanacağınız malzemeleri ve çalışma şeklinizi belirtin."
            rows={5}
            maxLength={1000}
            error={errors.description}
            required
          />
          <div className="flex justify-between items-center mt-1">
            {errors.description ? (
              <p className="text-sm text-red-600">{errors.description}</p>
            ) : (
              <p className="text-sm text-gray-500">En az 20 karakter</p>
            )}
            <p className="text-sm text-gray-400">
              {formData.description.length}/1000
            </p>
          </div>
        </div>

        <div>
          <label htmlFor="estimatedDuration" className="block text-sm font-medium text-gray-700 mb-2">
            Tahmini Süre (Saat)
          </label>
          <Input
            id="estimatedDuration"
            type="number"
            min="1"
            max="168"
            value={formData.estimatedDuration || ''}
            onChange={(e) => handleChange('estimatedDuration', e.target.value ? parseInt(e.target.value) : undefined)}
            placeholder="Örn: 4"
            error={errors.estimatedDuration}
          />
          {errors.estimatedDuration && (
            <p className="mt-1 text-sm text-red-600">{errors.estimatedDuration}</p>
          )}
        </div>

        <div>
          <label htmlFor="availableFrom" className="block text-sm font-medium text-gray-700 mb-2">
            En Erken Başlama Tarihi
          </label>
          <Input
            id="availableFrom"
            type="datetime-local"
            value={formData.availableFrom || ''}
            onChange={(e) => handleChange('availableFrom', e.target.value)}
            min={new Date().toISOString().slice(0, 16)}
          />
        </div>
      </div>

      <div className="flex justify-end space-x-3 pt-4 border-t">
        <Button
          type="submit"
          loading={loading}
          disabled={loading}
          className="min-w-32"
        >
          {loading ? 'Gönderiliyor...' : 'Teklif Gönder'}
        </Button>
      </div>
    </form>
  );
} 
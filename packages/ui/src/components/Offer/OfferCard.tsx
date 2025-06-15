"use client";

import React from 'react';
import { Button } from '../Button';
import { Badge } from '../Badge';
import { cn } from '../../lib/utils';

interface OfferCardProps {
  offer: {
    id: string;
    price: number;
    description: string;
    estimatedDuration?: number;
    availableFrom?: string;
    status: 'PENDING' | 'ACCEPTED' | 'REJECTED' | 'WITHDRAWN';
    createdAt: string;
    expiresAt: string;
    professional: {
      id: string;
      firstName: string;
      lastName: string;
      avatar?: string;
      rating?: number;
      reviewCount?: number;
      isVerified?: boolean;
      city?: string;
      district?: string;
    };
  };
  onAccept?: (offerId: string) => void;
  onReject?: (offerId: string) => void;
  onWithdraw?: (offerId: string) => void;
  showActions?: boolean;
  userRole?: 'customer' | 'professional';
  className?: string;
}

const statusConfig = {
  PENDING: { label: 'Bekliyor', color: 'yellow' as const },
  ACCEPTED: { label: 'Kabul Edildi', color: 'green' as const },
  REJECTED: { label: 'Reddedildi', color: 'red' as const },
  WITHDRAWN: { label: 'Geri Çekildi', color: 'gray' as const },
};

export function OfferCard({
  offer,
  onAccept,
  onReject,
  onWithdraw,
  showActions = true,
  userRole = 'customer',
  className,
}: OfferCardProps) {
  const isExpired = new Date(offer.expiresAt) < new Date();
  const canAccept = userRole === 'customer' && offer.status === 'PENDING' && !isExpired;
  const canReject = userRole === 'customer' && offer.status === 'PENDING' && !isExpired;
  const canWithdraw = userRole === 'professional' && offer.status === 'PENDING' && !isExpired;

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
      hour: '2-digit',
      minute: '2-digit',
    }).format(new Date(dateString));
  };

  const getTimeRemaining = () => {
    const now = new Date();
    const expiresAt = new Date(offer.expiresAt);
    const diffMs = expiresAt.getTime() - now.getTime();
    
    if (diffMs <= 0) return 'Süresi doldu';
    
    const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
    const diffDays = Math.floor(diffHours / 24);
    
    if (diffDays > 0) {
      return `${diffDays} gün kaldı`;
    } else {
      return `${diffHours} saat kaldı`;
    }
  };

  return (
    <div className={cn(
      "bg-white rounded-lg border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow",
      isExpired && "opacity-75",
      className
    )}>
      {/* Header */}
      <div className="flex items-start justify-between mb-4">
        <div className="flex items-center space-x-3">
          <div className="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
            {offer.professional.avatar ? (
              <img 
                src={offer.professional.avatar} 
                alt={`${offer.professional.firstName} ${offer.professional.lastName}`}
                className="w-12 h-12 rounded-full object-cover"
              />
            ) : (
              <span className="text-lg font-semibold text-gray-600">
                {offer.professional.firstName[0]}{offer.professional.lastName[0]}
              </span>
            )}
          </div>
          <div>
            <div className="flex items-center space-x-2">
              <h3 className="font-semibold text-gray-900">
                {offer.professional.firstName} {offer.professional.lastName}
              </h3>
              {offer.professional.isVerified && (
                <Badge variant="success" size="sm">Doğrulanmış</Badge>
              )}
            </div>
            <div className="flex items-center space-x-2 text-sm text-gray-500">
              {offer.professional.city && (
                <span>{offer.professional.city}, {offer.professional.district}</span>
              )}
              {offer.professional.rating && (
                <span>⭐ {offer.professional.rating.toFixed(1)} ({offer.professional.reviewCount} değerlendirme)</span>
              )}
            </div>
          </div>
        </div>
        <div className="flex items-center space-x-2">
          <Badge 
            variant={statusConfig[offer.status].color}
            size="sm"
          >
            {statusConfig[offer.status].label}
          </Badge>
        </div>
      </div>

      {/* Price */}
      <div className="mb-4">
        <div className="text-2xl font-bold text-green-600">
          {formatPrice(offer.price)}
        </div>
        {offer.estimatedDuration && (
          <div className="text-sm text-gray-500">
            Tahmini süre: {offer.estimatedDuration} saat
          </div>
        )}
      </div>

      {/* Description */}
      <div className="mb-4">
        <p className="text-gray-700 leading-relaxed">
          {offer.description}
        </p>
      </div>

      {/* Available From */}
      {offer.availableFrom && (
        <div className="mb-4 text-sm text-gray-600">
          <span className="font-medium">En erken başlama:</span> {formatDate(offer.availableFrom)}
        </div>
      )}

      {/* Footer */}
      <div className="flex items-center justify-between pt-4 border-t border-gray-100">
        <div className="text-sm text-gray-500">
          <div>Gönderildi: {formatDate(offer.createdAt)}</div>
          {offer.status === 'PENDING' && !isExpired && (
            <div className="text-orange-600 font-medium">
              {getTimeRemaining()}
            </div>
          )}
        </div>

        {/* Actions */}
        {showActions && (
          <div className="flex items-center space-x-2">
            {canAccept && onAccept && (
              <Button
                size="sm"
                onClick={() => onAccept(offer.id)}
                className="bg-green-600 hover:bg-green-700"
              >
                Kabul Et
              </Button>
            )}
            {canReject && onReject && (
              <Button
                size="sm"
                variant="outline"
                onClick={() => onReject(offer.id)}
                className="border-red-300 text-red-600 hover:bg-red-50"
              >
                Reddet
              </Button>
            )}
            {canWithdraw && onWithdraw && (
              <Button
                size="sm"
                variant="outline"
                onClick={() => onWithdraw(offer.id)}
                className="border-gray-300 text-gray-600 hover:bg-gray-50"
              >
                Geri Çek
              </Button>
            )}
          </div>
        )}
      </div>
    </div>
  );
} 
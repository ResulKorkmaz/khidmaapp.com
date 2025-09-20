<?php

namespace App\Filament\Resources\ProfileVerificationResource\Pages;

use App\Filament\Resources\ProfileVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewProfileVerification extends ViewRecord
{
    protected static string $resource = ProfileVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Kullanıcı Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Kullanıcı Adı'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('E-posta'),
                        Infolists\Components\TextEntry::make('user.phone')
                            ->label('Telefon'),
                        Infolists\Components\TextEntry::make('user.role')
                            ->label('Rol')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'individual_provider' => 'Bireysel Sağlayıcı',
                                'company_provider' => 'Şirket Sağlayıcı',
                                default => $state
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Doğrulama Detayları')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Durum')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Beklemede',
                                'approved' => 'Onaylandı',
                                'rejected' => 'Reddedildi',
                                'expired' => 'Süresi Doldu'
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                'expired' => 'gray'
                            }),

                        Infolists\Components\TextEntry::make('verification_type')
                            ->label('Doğrulama Türü')
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'basic' => 'Temel',
                                'premium' => 'Premium'
                            }),

                        Infolists\Components\IconEntry::make('is_trial')
                            ->label('Deneme Sürümü')
                            ->boolean(),

                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Aktif')
                            ->boolean(),

                        Infolists\Components\TextEntry::make('price')
                            ->label('Fiyat')
                            ->money('USD'),

                        Infolists\Components\TextEntry::make('days_until_expiry')
                            ->label('Kalan Gün')
                            ->getStateUsing(function ($record): string {
                                $days = $record->days_until_expiry;
                                if ($days <= 0) {
                                    return 'Süresi Doldu';
                                } else {
                                    return $days . ' gün';
                                }
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Tarih Bilgileri')
                    ->schema([
                        Infolists\Components\TextEntry::make('verified_at')
                            ->label('Doğrulama Tarihi')
                            ->dateTime('d/m/Y H:i'),

                        Infolists\Components\TextEntry::make('expires_at')
                            ->label('Bitiş Tarihi')
                            ->dateTime('d/m/Y H:i'),

                        Infolists\Components\TextEntry::make('trial_expires_at')
                            ->label('Deneme Süresi Bitiş')
                            ->dateTime('d/m/Y H:i'),

                        Infolists\Components\TextEntry::make('payment_received_at')
                            ->label('Ödeme Alınma Tarihi')
                            ->dateTime('d/m/Y H:i'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Oluşturulma Tarihi')
                            ->dateTime('d/m/Y H:i'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Güncellenme Tarihi')
                            ->dateTime('d/m/Y H:i'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Notlar ve Ödemeler')
                    ->schema([
                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label('Admin Notları'),

                        Infolists\Components\TextEntry::make('payment_reference')
                            ->label('Ödeme Referansı'),
                    ]),

                Infolists\Components\Section::make('Belgeler')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('documents')
                            ->label('Yüklenen Belgeler')
                            ->keyLabel('Belge Türü')
                            ->valueLabel('Dosya Yolu'),
                    ])
                    ->visible(fn ($record): bool => !empty($record->documents)),
            ]);
    }
}


<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Kategori düzenleme sayfası
 */
class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Sayfa başlığı
     */
    public function getTitle(): string
    {
        return 'Kategori Düzenle';
    }

    /**
     * Sayfa başlığındaki aksiyonlar
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Görüntüle')
                ->icon('heroicon-o-eye'),
            Actions\DeleteAction::make()
                ->label('Sil')
                ->icon('heroicon-o-trash'),
        ];
    }

    /**
     * Form submit button metni
     */
    protected function getSaveFormAction(): Actions\Action
    {
        return parent::getSaveFormAction()
            ->label('Değişiklikleri Kaydet');
    }

    /**
     * Cancel button metni
     */
    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('İptal');
    }
}
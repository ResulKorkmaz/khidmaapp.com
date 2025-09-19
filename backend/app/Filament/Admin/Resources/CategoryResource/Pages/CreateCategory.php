<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

/**
 * Kategori oluşturma sayfası
 */
class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Sayfa başlığı
     */
    public function getTitle(): string
    {
        return 'Yeni Kategori Oluştur';
    }

    /**
     * Form submit button metni
     */
    protected function getCreateFormAction(): Actions\Action
    {
        return parent::getCreateFormAction()
            ->label('Kategori Oluştur');
    }

    /**
     * Cancel button metni
     */
    protected function getCancelFormAction(): Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('İptal');
    }

    /**
     * Create & Create Another button metni
     */
    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Oluştur ve Yeni Ekle');
    }
}
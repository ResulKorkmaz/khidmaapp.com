<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

/**
 * Kategori listesi sayfası
 */
class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    /**
     * Sayfa başlığı
     */
    public function getTitle(): string
    {
        return 'Kategoriler';
    }

    /**
     * Sayfa başlığındaki aksiyonlar
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Yeni Kategori')
                ->icon('heroicon-o-plus'),
        ];
    }
}
<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * Kategori görüntüleme sayfası
 */
class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Sayfa başlığı
     */
    public function getTitle(): string
    {
        return 'Kategori Detayları';
    }

    /**
     * Sayfa başlığındaki aksiyonlar
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Düzenle')
                ->icon('heroicon-o-pencil'),
        ];
    }
}
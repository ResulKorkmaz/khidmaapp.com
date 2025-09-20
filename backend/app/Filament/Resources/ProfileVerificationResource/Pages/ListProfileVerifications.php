<?php

namespace App\Filament\Resources\ProfileVerificationResource\Pages;

use App\Filament\Resources\ProfileVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListProfileVerifications extends ListRecords
{
    protected static string $resource = ProfileVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Yeni Doğrulama'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Tümü'),
            
            'pending' => Tab::make('Beklemede')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending'))
                ->badge(fn (): ?string => $this->getModel()::where('status', 'pending')->count() ?: null)
                ->badgeColor('warning'),
            
            'approved' => Tab::make('Onaylandı')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
                ->badge(fn (): ?string => $this->getModel()::where('status', 'approved')->count() ?: null)
                ->badgeColor('success'),
            
            'trial' => Tab::make('Deneme Sürümü')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_trial', true)->where('is_active', true))
                ->badge(fn (): ?string => $this->getModel()::where('is_trial', true)->where('is_active', true)->count() ?: null)
                ->badgeColor('info'),
            
            'expiring' => Tab::make('Yakında Dolacak')
                ->modifyQueryUsing(fn (Builder $query) => $query->expiringInDays(30))
                ->badge(fn (): ?string => $this->getModel()::expiringInDays(30)->count() ?: null)
                ->badgeColor('danger'),
        ];
    }
}


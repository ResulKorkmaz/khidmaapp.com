<?php

namespace App\Filament\Resources\ProfileVerificationResource\Pages;

use App\Filament\Resources\ProfileVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProfileVerification extends CreateRecord
{
    protected static string $resource = ProfileVerificationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set default values
        if (empty($data['verified_at']) && $data['status'] === 'approved') {
            $data['verified_at'] = now();
        }

        if ($data['is_trial'] && empty($data['trial_expires_at'])) {
            $data['trial_expires_at'] = now()->addMonths(6);
        }

        if (!$data['is_trial'] && empty($data['expires_at']) && $data['status'] === 'approved') {
            $data['expires_at'] = now()->addYear();
        }

        return $data;
    }
}


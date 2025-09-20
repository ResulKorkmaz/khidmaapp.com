<?php

namespace App\Filament\Resources\ProfileVerificationResource\Pages;

use App\Filament\Resources\ProfileVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfileVerification extends EditRecord
{
    protected static string $resource = ProfileVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Auto-set verified_at when status changes to approved
        if ($data['status'] === 'approved' && empty($data['verified_at'])) {
            $data['verified_at'] = now();
        }

        // Auto-set expires_at for non-trial verifications
        if (!$data['is_trial'] && empty($data['expires_at']) && $data['status'] === 'approved') {
            $data['expires_at'] = now()->addYear();
        }

        // Auto-set trial_expires_at for trial verifications
        if ($data['is_trial'] && empty($data['trial_expires_at'])) {
            $data['trial_expires_at'] = now()->addMonths(6);
        }

        return $data;
    }
}


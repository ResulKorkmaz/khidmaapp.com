<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfileVerificationResource\Pages;
use App\Models\ProfileVerification;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfileVerificationResource extends Resource
{
    protected static ?string $model = ProfileVerification::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Profil Doğrulamaları';

    protected static ?string $modelLabel = 'Profil Doğrulaması';

    protected static ?string $pluralModelLabel = 'Profil Doğrulamaları';

    protected static ?string $navigationGroup = 'Kullanıcı Yönetimi';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Temel Bilgiler')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Kullanıcı')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Durum')
                            ->options([
                                'pending' => 'Beklemede',
                                'approved' => 'Onaylandı',
                                'rejected' => 'Reddedildi',
                                'expired' => 'Süresi Doldu'
                            ])
                            ->required()
                            ->default('pending'),

                        Forms\Components\Select::make('verification_type')
                            ->label('Doğrulama Türü')
                            ->options([
                                'basic' => 'Temel',
                                'premium' => 'Premium'
                            ])
                            ->required()
                            ->default('basic'),

                        Forms\Components\TextInput::make('price')
                            ->label('Fiyat ($)')
                            ->numeric()
                            ->default(50.00)
                            ->prefix('$'),
                    ])->columns(2),

                Forms\Components\Section::make('Tarih Bilgileri')
                    ->schema([
                        Forms\Components\DateTimePicker::make('verified_at')
                            ->label('Doğrulama Tarihi'),

                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Bitiş Tarihi'),

                        Forms\Components\DateTimePicker::make('trial_expires_at')
                            ->label('Deneme Süresi Bitiş'),

                        Forms\Components\DateTimePicker::make('payment_received_at')
                            ->label('Ödeme Alınma Tarihi'),
                    ])->columns(2),

                Forms\Components\Section::make('Durumlar')
                    ->schema([
                        Forms\Components\Toggle::make('is_trial')
                            ->label('Deneme Sürümü')
                            ->default(true),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(false),
                    ])->columns(2),

                Forms\Components\Section::make('Notlar ve Ödemeler')
                    ->schema([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notları')
                            ->rows(3),

                        Forms\Components\TextInput::make('payment_reference')
                            ->label('Ödeme Referansı')
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Belgeler')
                    ->schema([
                        Forms\Components\KeyValue::make('documents')
                            ->label('Yüklenen Belgeler')
                            ->keyLabel('Belge Türü')
                            ->valueLabel('Dosya Yolu'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Kullanıcı')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.role')
                    ->label('Rol')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'individual_provider' => 'Bireysel Sağlayıcı',
                        'company_provider' => 'Şirket Sağlayıcı',
                        default => $state
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'individual_provider' => 'success',
                        'company_provider' => 'info',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('status')
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

                Tables\Columns\IconColumn::make('is_trial')
                    ->label('Deneme')
                    ->boolean()
                    ->trueIcon('heroicon-o-clock')
                    ->falseIcon('heroicon-o-currency-dollar')
                    ->trueColor('warning')
                    ->falseColor('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('days_until_expiry')
                    ->label('Kalan Gün')
                    ->getStateUsing(function (ProfileVerification $record): string {
                        $days = $record->days_until_expiry;
                        if ($days <= 0) {
                            return 'Süresi Doldu';
                        } elseif ($days <= 7) {
                            return $days . ' gün (Kritik)';
                        } elseif ($days <= 30) {
                            return $days . ' gün (Yaklaşıyor)';
                        } else {
                            return $days . ' gün';
                        }
                    })
                    ->color(fn (ProfileVerification $record): string => 
                        $record->days_until_expiry <= 0 ? 'danger' :
                        ($record->days_until_expiry <= 7 ? 'danger' :
                        ($record->days_until_expiry <= 30 ? 'warning' : 'success'))
                    ),

                Tables\Columns\TextColumn::make('price')
                    ->label('Fiyat')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('verified_at')
                    ->label('Doğrulama Tarihi')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Beklemede',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                        'expired' => 'Süresi Doldu'
                    ]),

                Tables\Filters\TernaryFilter::make('is_trial')
                    ->label('Deneme Sürümü'),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),

                Tables\Filters\Filter::make('expiring_soon')
                    ->label('Yakında Süresi Dolacaklar')
                    ->query(fn (Builder $query): Builder => $query->expiringInDays(30)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ProfileVerification $record): bool => $record->status === 'pending')
                    ->action(function (ProfileVerification $record) {
                        $record->approve('Admin tarafından onaylandı');
                        
                        Notification::make()
                            ->success()
                            ->title('Doğrulama Onaylandı')
                            ->body('Profil doğrulaması başarıyla onaylandı.')
                            ->send();
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ProfileVerification $record): bool => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('reason')
                            ->label('Red Nedeni')
                            ->required()
                    ])
                    ->action(function (ProfileVerification $record, array $data) {
                        $record->reject($data['reason']);
                        
                        Notification::make()
                            ->warning()
                            ->title('Doğrulama Reddedildi')
                            ->body('Profil doğrulaması reddedildi.')
                            ->send();
                    }),

                Tables\Actions\Action::make('extend')
                    ->label('Süre Uzat')
                    ->icon('heroicon-o-plus-circle')
                    ->color('info')
                    ->visible(fn (ProfileVerification $record): bool => $record->is_active)
                    ->form([
                        Forms\Components\Select::make('months')
                            ->label('Uzatılacak Süre')
                            ->options([
                                1 => '1 Ay',
                                3 => '3 Ay', 
                                6 => '6 Ay',
                                12 => '12 Ay (1 Yıl)',
                                24 => '24 Ay (2 Yıl)',
                            ])
                            ->default(12)
                            ->required(),
                        
                        Forms\Components\TextInput::make('payment_reference')
                            ->label('Ödeme Referansı')
                    ])
                    ->action(function (ProfileVerification $record, array $data) {
                        $record->extendVerification($data['months']);
                        
                        if (!empty($data['payment_reference'])) {
                            $record->update([
                                'payment_reference' => $data['payment_reference'],
                                'payment_received_at' => now()
                            ]);
                        }

                        Notification::make()
                            ->success()
                            ->title('Süre Uzatıldı')
                            ->body("Doğrulama süresi {$data['months']} ay uzatıldı.")
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('approve_selected')
                        ->label('Seçilenleri Onayla')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $count = 0;
                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    $record->approve('Toplu onay');
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->success()
                                ->title('Toplu Onay Tamamlandı')
                                ->body("{$count} doğrulama onaylandı.")
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfileVerifications::route('/'),
            'create' => Pages\CreateProfileVerification::route('/create'),
            'view' => Pages\ViewProfileVerification::route('/{record}'),
            'edit' => Pages\EditProfileVerification::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pending = static::getModel()::where('status', 'pending')->count();
        return $pending > 0 ? 'warning' : null;
    }
}


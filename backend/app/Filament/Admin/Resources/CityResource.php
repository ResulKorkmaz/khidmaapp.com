<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CityResource\Pages;
use App\Filament\Admin\Resources\CityResource\RelationManagers;
use App\Models\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    
    protected static ?string $navigationGroup = 'İçerik Yönetimi';
    
    protected static ?string $navigationLabel = 'Şehirler';
    
    protected static ?string $modelLabel = 'Şehir';
    
    protected static ?string $pluralModelLabel = 'Şehirler';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Şehir Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label('Şehir Adı (Arapça)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->label('Şehir Adı (İngilizce)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug_ar')
                            ->label('URL (Arapça)')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('slug_en')
                            ->label('URL (İngilizce)')
                            ->maxLength(255),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Konum Bilgileri')
                    ->schema([
                        Forms\Components\TextInput::make('region_code')
                            ->label('Bölge Kodu')
                            ->required()
                            ->maxLength(10),
                        Forms\Components\TextInput::make('latitude')
                            ->label('Enlem')
                            ->numeric()
                            ->step(0.000001)
                            ->required(),
                        Forms\Components\TextInput::make('longitude')
                            ->label('Boylam')
                            ->numeric()
                            ->step(0.000001)
                            ->required(),
                    ])->columns(3),
                    
                Forms\Components\Section::make('Durum ve Öncelik')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                        Forms\Components\TextInput::make('priority')
                            ->label('Öncelik')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('services_count')
                            ->label('Hizmet Sayısı')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')
                    ->label('Şehir (Arapça)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label('Şehir (İngilizce)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('region_code')
                    ->label('Bölge')
                    ->searchable(),
                Tables\Columns\TextColumn::make('latitude')
                    ->label('Enlem')
                    ->numeric(decimalPlaces: 4),
                Tables\Columns\TextColumn::make('longitude')
                    ->label('Boylam')
                    ->numeric(decimalPlaces: 4),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Öncelik')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('services_count')
                    ->label('Hizmet Sayısı')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktif'),
                Tables\Filters\SelectFilter::make('region_code')
                    ->label('Bölge')
                    ->options([
                        'RI' => 'Riyad',
                        'MA' => 'Makkah',
                        'EP' => 'Doğu',
                        'MD' => 'Medine',
                        'QS' => 'Qassim',
                        'AS' => 'Asir',
                        'TB' => 'Tabuk',
                        'HA' => 'Hail',
                        'JZ' => 'Jazan',
                        'NJ' => 'Najran',
                        'BH' => 'Al Bahah',
                        'NB' => 'Kuzey Sınırları',
                        'JF' => 'Al Jawf',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Görüntüle'),
                Tables\Actions\EditAction::make()->label('Düzenle'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('Sil'),
                ]),
            ])
            ->defaultSort('priority', 'desc');
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'view' => Pages\ViewCity::route('/{record}'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
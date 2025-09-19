<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ServiceResource\Pages;
use App\Filament\Admin\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->label('Kategori')
                    ->options(function () {
                        return \App\Models\Category::all()->pluck('admin_display_name', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->helperText('Arapça - Türkçe format (sadece admin panelinde)'),
                Forms\Components\Select::make('city_id')
                    ->label('Şehir')
                    ->options(function () {
                        return \App\Models\City::all()->pluck('admin_display_name', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->required()
                    ->helperText('Arapça - Türkçe format (sadece admin panelinde)'),
                Forms\Components\TextInput::make('title_ar')
                    ->required(),
                Forms\Components\TextInput::make('title_en'),
                Forms\Components\Textarea::make('description_ar')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('slug_ar')
                    ->required(),
                Forms\Components\TextInput::make('slug_en'),
                Forms\Components\Textarea::make('images')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('budget_min')
                    ->numeric(),
                Forms\Components\TextInput::make('budget_max')
                    ->numeric(),
                Forms\Components\TextInput::make('budget_currency')
                    ->required(),
                Forms\Components\TextInput::make('urgency')
                    ->required(),
                Forms\Components\DatePicker::make('preferred_date'),
                Forms\Components\TextInput::make('preferred_time'),
                Forms\Components\TextInput::make('latitude')
                    ->numeric(),
                Forms\Components\TextInput::make('longitude')
                    ->numeric(),
                Forms\Components\TextInput::make('address_ar'),
                Forms\Components\TextInput::make('address_en'),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\DateTimePicker::make('expires_at'),
                Forms\Components\DateTimePicker::make('completed_at'),
                Forms\Components\TextInput::make('views_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('bids_count')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_featured')
                    ->required(),
                Forms\Components\Textarea::make('custom_fields')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('seo_meta')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name_ar')
                    ->label('Kategori')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->category ? $record->category->admin_display_name : '-';
                    })
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('city.name_ar')
                    ->label('Şehir')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->city ? $record->city->admin_display_name : '-';
                    })
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('title_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget_min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget_max')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget_currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('urgency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferred_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('preferred_time'),
                Tables\Columns\TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('address_ar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_en')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bids_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'view' => Pages\ViewService::route('/{record}'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}

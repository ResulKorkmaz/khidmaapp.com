<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Kategori yönetimi için Filament resource sınıfı
 * 
 * Bu sınıf kategori CRUD işlemlerini, form ve tablo 
 * yapılandırmalarını yönetir.
 */
class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    
    protected static ?string $navigationGroup = 'İçerik Yönetimi';
    
    protected static ?string $navigationLabel = 'Kategoriler';
    
    protected static ?string $modelLabel = 'Kategori';
    
    protected static ?string $pluralModelLabel = 'Kategoriler';
    
    protected static ?int $navigationSort = 2;

    /**
     * Kategori oluşturma/düzenleme formu
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Ana Bilgiler Bölümü
                Forms\Components\Section::make('Ana Bilgiler')
                    ->description('Kategorinin temel bilgilerini giriniz')
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->label('Ana Kategori')
                            ->options(function () {
                                return \App\Models\Category::all()->pluck('admin_display_name', 'id')->toArray();
                            })
                            ->placeholder('Ana kategori yok (kök kategori)')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Arapça - Türkçe format (sadece admin panelinde)'),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name_ar')
                                    ->label('Kategori Adı (Arapça)')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation !== 'create') {
                                            return;
                                        }
                                        // Arapça isimden slug oluştur
                                        $set('slug_ar', Str::slug($state));
                                    }),
                                    
                                Forms\Components\TextInput::make('name_en')
                                    ->label('Kategori Adı (İngilizce)')
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation !== 'create') {
                                            return;
                                        }
                                        // İngilizce isimden slug oluştur
                                        $set('slug_en', Str::slug($state));
                                    }),
                            ]),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('slug_ar')
                                    ->label('URL Adresi (Arapça)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->rules(['regex:/^[a-z0-9-]+$/']),
                                    
                                Forms\Components\TextInput::make('slug_en')
                                    ->label('URL Adresi (İngilizce)')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true)
                                    ->rules(['regex:/^[a-z0-9-]+$/']),
                            ]),
                    ])->columns(2),

                // Açıklama Bölümü
                Forms\Components\Section::make('Açıklama')
                    ->description('Kategori açıklamalarını giriniz')
                    ->schema([
                        Forms\Components\Textarea::make('description_ar')
                            ->label('Açıklama (Arapça)')
                            ->rows(4)
                            ->maxLength(1000),
                            
                        Forms\Components\Textarea::make('description_en')
                            ->label('Açıklama (İngilizce)')
                            ->rows(4)
                            ->maxLength(1000),
                    ])->columns(2),

                // Görünüm Ayarları
                Forms\Components\Section::make('Görünüm Ayarları')
                    ->description('Kategori görünüm ve düzen ayarları')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('icon')
                                    ->label('İkon')
                                    ->placeholder('heroicon-o-squares-2x2')
                                    ->helperText('Heroicon icon adı'),
                                    
                                Forms\Components\ColorPicker::make('color')
                                    ->label('Renk')
                                    ->default('#14b8a6'),
                                    
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Sıralama')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Küçük sayılar önce gösterilir'),
                            ]),
                    ]),

                // Durum ve İstatistikler
                Forms\Components\Section::make('Durum ve İstatistikler')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->helperText('Kategorinin web sitesinde görünür olup olmayacağı'),
                                    
                                Forms\Components\TextInput::make('services_count')
                                    ->label('Servis Sayısı')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->helperText('Otomatik hesaplanır'),
                            ]),
                    ]),

                // SEO ve Özel Alanlar
                Forms\Components\Section::make('SEO ve Özel Ayarlar')
                    ->description('SEO meta bilgileri ve özel alanlar')
                    ->collapsed()
                    ->schema([
                        Forms\Components\KeyValue::make('seo_meta')
                            ->label('SEO Meta Bilgileri')
                            ->keyLabel('Meta Alan')
                            ->valueLabel('Değer')
                            ->addActionLabel('Meta alan ekle')
                            ->helperText('title, description, keywords gibi meta alanları'),
                            
                        Forms\Components\KeyValue::make('custom_fields')
                            ->label('Özel Alanlar')
                            ->keyLabel('Alan Adı')
                            ->valueLabel('Değer')
                            ->addActionLabel('Özel alan ekle')
                            ->helperText('Kategori özelinde özel alanlar'),
                    ]),
            ]);
    }

    /**
     * Kategori listesi tablosu
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('name_ar')
                    ->label('Kategori Adı (AR)')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Category $record): string => $record->slug_ar ?? ''),
                    
                Tables\Columns\TextColumn::make('name_en')
                    ->label('Kategori Adı (EN)')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Category $record): string => $record->slug_en ?? '')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('parent.name_ar')
                    ->label('Ana Kategori')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->parent) return 'Kök kategori';
                        return $record->parent->admin_display_name;
                    })
                    ->searchable()
                    ->sortable()
                    ->placeholder('Kök kategori')
                    ->toggleable()
                    ->wrap(),
                    
                Tables\Columns\ColorColumn::make('color')
                    ->label('Renk')
                    ->toggleable(),
                    
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Sıra')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                    
                Tables\Columns\TextColumn::make('services_count')
                    ->label('Servis Sayısı')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Güncellenme')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->trueLabel('Sadece aktif')
                    ->falseLabel('Sadece pasif')
                    ->native(false),
                    
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Ana Kategori')
                    ->relationship('parent', 'name_ar')
                    ->placeholder('Tüm kategoriler')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Görüntüle'),
                Tables\Actions\EditAction::make()
                    ->label('Düzenle'),
                Tables\Actions\DeleteAction::make()
                    ->label('Sil'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Seçilenleri Sil'),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            // Alt kategoriler relation manager eklenebilir
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
    
    /**
     * Global search için kullanılacak alanlar
     */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['parent']);
    }
    
    /**
     * Global search sonuçlarında gösterilecek bilgiler
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Ana Kategori' => $record->parent?->name_ar ?? 'Kök kategori',
            'Durum' => $record->is_active ? 'Aktif' : 'Pasif',
        ];
    }
}
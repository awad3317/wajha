<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Filament\Resources\RegionResource\RelationManagers;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    
    protected static ?string $navigationIcon = 'heroicon-o-map'; 

    protected static ?string $navigationGroup = 'الاعدادات'; 

    protected static ?string $navigationLabel = 'المناطق';

    protected static ?string $modelLabel = 'منطقة'; 
    
    protected static ?string $pluralModelLabel = 'المناطق';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('أسم المنطقة'),

                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'name', fn (Builder $query) => $query->whereNull('parent_id'))
                    ->nullable()
                    ->searchable() 
                    ->placeholder('اختر محافظة رئيسية (اختياري)')
                    ->label('أسم المحافظة'), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('المنطقة'),

                Tables\Columns\TextColumn::make('parent.name') 
                    ->searchable()
                    ->sortable()
                    ->default('N/A') 
                    ->label('المحافظة'),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListRegions::route('/'),
            // 'create' => Pages\CreateRegion::route('/create'),
            // 'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->withoutGlobalScopes();
}
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'المستخدمين';
    protected static ?string $pluralModelLabel = 'المستخدمين';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->label('اسم المستخدم'),
                
            Tables\Columns\TextColumn::make('phone')
                ->searchable()
                ->label('رقم الجوال'),
            Tables\Columns\TextColumn::make('phone_verified_at')
                ->label('تاريخ التحقق')
                ->dateTime('d/m/Y H:i') 
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: false) // يمكن جعله مخفيًا افتراضيًا إذا أردت
                ->placeholder('لم يتم التحقق'),
            Tables\Columns\ToggleColumn::make('is_banned')
                ->label('الحظر')
                ->onColor('danger')
                ->offColor('success') 
                ->onIcon('heroicon-o-shield-exclamation')
                ->offIcon('heroicon-o-check-circle')
                ->disabled(fn (User $record): bool => $record->id == 1) 
                ->updateStateUsing(function ($record, $state) {
                    $record->update(['is_banned' => $state]);
                }),
            Tables\Columns\TextColumn::make('user_type')
                ->label('نوع المستخدم')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'admin' => 'primary',
                    'owner' => 'success',
                    'user' => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'admin' => 'ادمن',
                    'user' => 'مستخدم',
                    'owner' => 'مالك منشئة',
                }),
                
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->label('انشئ في')
                ->toggleable(isToggledHiddenByDefault: true),
            ])->deferLoading() 
            ->filters([
            Tables\Filters\SelectFilter::make('user_type')
                ->label('نوع المتسخدم')
                ->options([
                    'admin' => 'ادمن',
                    'user' => 'مستخدم',
                    'owner' => 'اونر',
                ]),
            Tables\Filters\Filter::make('is_banned')
                ->query(fn (Builder $query): Builder => $query->where('is_banned', true))
                ->label('المحظورين فقط'),
            Tables\Filters\Filter::make('is_not_banned')
                ->query(fn (Builder $query): Builder => $query->where('is_banned', false))
                ->label('الغير محظورين فقط '),
            ])
            ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\Action::make('changeType')
                ->label('تغيير نوع المستخدم')
                ->hidden(fn (User $record): bool => $record->id == 1)
                ->modalWidth('sm')
                ->form([
                    Forms\Components\Select::make('user_type')
                        ->options([
                            'admin' => 'ادمن',
                            'user' => 'مستخدم',
                            'owner' => 'مالك منشئة',
                        ])
                        ->required()
                ])
                ->action(function (User $record, array $data) {
                    $record->update(['user_type' => $data['user_type']]);
                    
                })
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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()->withoutGlobalScopes();
}
}

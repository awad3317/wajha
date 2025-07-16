<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;

class CustomLogin extends BaseLogin
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('phone')
                    ->label('رقم الهاتف')
                    ->required()
                    ->numeric(),
                TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->required(),
            ]);
    }
}
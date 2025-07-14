<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
class CustomLogin extends BaseLogin
{
    
     public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getLoginFormComponent(),
                $this->getPasswordFormComponent(),
            ]);
    }
    protected function getLoginFormComponent(): \Filament\Forms\Components\Component
    {
        return \Filament\Forms\Components\TextInput::make('phone')
            ->label(' رقم الجوال')
            ->required()
            ->autocomplete()
            ->placeholder('مثال: 9665XXXXXXXX')
            ->maxLength(15)
            ->autofocus();
    }
    protected function getCredentialsFromFormData(array $data): array
    { 
        return [
            'phone' => $data['phone'],
            'password' => $data['password'],
        ];
    }
    public function getHeading(): string
    {
        return "الدخول إلى وجهة"; 
    }

    public function authenticate(): null|\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException $e) {
            Notification::make()
                ->title('خطأ في تسجيل الدخول')
                ->body('رقم الجوال أو كلمة المرور غير صحيحة')
                ->danger()
                ->persistent()
                ->send();
        
            throw $e;
        }
    }

}

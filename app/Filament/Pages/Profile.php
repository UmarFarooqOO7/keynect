<?php

namespace App\Filament\Pages;

use App\Notifications\PasswordChangedNotification;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class Profile extends BaseEditProfile
{
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['password']) && filled($data['password'])) {
            auth()->user()->notify(new PasswordChangedNotification());
        }

        return $data;
    }
}

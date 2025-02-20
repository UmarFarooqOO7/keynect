<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Notifications\CustomVerifyEmail;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $rawPassword = Str::random(12);
        $this->record->password = bcrypt($rawPassword);
        $this->record->save();
        
        $this->record->notify(new CustomVerifyEmail($rawPassword));
    }
}

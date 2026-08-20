<?php

namespace App\Filament\Resources\PasscodeResource\Pages;

use App\Filament\Resources\PasscodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPasscode extends EditRecord
{
    protected static string $resource = PasscodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

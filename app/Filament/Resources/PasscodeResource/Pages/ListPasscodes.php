<?php

namespace App\Filament\Resources\PasscodeResource\Pages;

use App\Filament\Pages\BaseListRecords;
use App\Filament\Resources\PasscodeResource;
use Filament\Actions;

class ListPasscodes extends BaseListRecords
{
    protected static string $resource = PasscodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\PasscodeResource\Pages;

use App\Filament\Resources\PasscodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPasscodes extends ListRecords
{
    protected static string $resource = PasscodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Pages\BaseListRecords;
use App\Filament\Resources\EmployeeResource;
use Filament\Actions;

class ListEmployees extends BaseListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

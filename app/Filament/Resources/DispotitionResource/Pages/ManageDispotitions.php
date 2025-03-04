<?php

namespace App\Filament\Resources\DispotitionResource\Pages;

use App\Filament\Resources\DispotitionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDispotitions extends ManageRecords
{
    protected static string $resource = DispotitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


}

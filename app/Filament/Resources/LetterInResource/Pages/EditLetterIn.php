<?php

namespace App\Filament\Resources\LetterInResource\Pages;

use App\Filament\Resources\LetterInResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLetterIn extends EditRecord
{
    protected static string $resource = LetterInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

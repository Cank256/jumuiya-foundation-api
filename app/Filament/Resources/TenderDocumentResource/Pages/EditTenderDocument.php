<?php

namespace App\Filament\Resources\TenderDocumentResource\Pages;

use App\Filament\Resources\TenderDocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTenderDocument extends EditRecord
{
    protected static string $resource = TenderDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

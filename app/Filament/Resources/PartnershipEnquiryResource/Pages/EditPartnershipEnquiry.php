<?php

namespace App\Filament\Resources\PartnershipEnquiryResource\Pages;

use App\Filament\Resources\PartnershipEnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnershipEnquiry extends EditRecord
{
    protected static string $resource = PartnershipEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

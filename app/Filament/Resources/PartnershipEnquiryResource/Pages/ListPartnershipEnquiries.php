<?php

namespace App\Filament\Resources\PartnershipEnquiryResource\Pages;

use App\Filament\Resources\PartnershipEnquiryResource;
use Filament\Resources\Pages\ListRecords;

class ListPartnershipEnquiries extends ListRecords
{
    protected static string $resource = PartnershipEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

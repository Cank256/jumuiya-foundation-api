<?php

namespace App\Filament\Resources\PartnershipEnquiryResource\Pages;

use App\Filament\Resources\PartnershipEnquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPartnershipEnquiry extends ViewRecord
{
    protected static string $resource = PartnershipEnquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mark_replied')
                ->label('Mark as Replied')
                ->icon('heroicon-o-check-circle')
                ->action(fn () => $this->record->update(['status' => 'replied']))
                ->visible(fn () => $this->record->status !== 'replied'),

            Actions\Action::make('archive')
                ->label('Archive')
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                ->action(fn () => $this->record->update(['status' => 'archived'])),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->status === 'unread') {
            $this->record->update(['status' => 'read']);
        }

        return $data;
    }
}

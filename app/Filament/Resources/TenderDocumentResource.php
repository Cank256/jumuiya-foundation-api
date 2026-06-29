<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenderDocumentResource\Pages;
use App\Models\TenderDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * TenderDocuments are managed inline on the TenderResource via a Repeater.
 * This resource is hidden from the navigation but kept available for direct access.
 */
class TenderDocumentResource extends Resource
{
    protected static ?string $model = TenderDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tender_id')
                ->relationship('tender', 'title')
                ->required(),

            Forms\Components\TextInput::make('name')->maxLength(255),

            Forms\Components\Select::make('type')
                ->options([
                    'rfp'           => 'RFP',
                    'tor'           => 'ToR',
                    'specification' => 'Specification',
                    'other'         => 'Other',
                ])
                ->required(),

            Forms\Components\TextInput::make('path')->required()->maxLength(500),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tender.title')->limit(30),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('type')->badge(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenderDocuments::route('/'),
            'create' => Pages\CreateTenderDocument::route('/create'),
            'edit'   => Pages\EditTenderDocument::route('/{record}/edit'),
        ];
    }
}

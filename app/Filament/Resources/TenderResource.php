<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenderResource\Pages;
use App\Models\Tender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TenderResource extends Resource
{
    protected static ?string $model = Tender::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Tenders & Procurement';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Tender Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('reference_number')
                        ->unique(ignoreRecord: true)
                        ->placeholder('e.g. JDF/PROC/2025/004')
                        ->maxLength(100),

                    Forms\Components\Select::make('status')
                        ->options([
                            'open'    => 'Open',
                            'closed'  => 'Closed',
                            'awarded' => 'Awarded',
                        ])
                        ->required()
                        ->default('open'),

                    Forms\Components\DateTimePicker::make('deadline')
                        ->native(false),
                ]),

            Forms\Components\Section::make('Content')
                ->schema([
                    Forms\Components\RichEditor::make('description')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('requirements')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Documents')
                ->description('Upload structured documents (preferred) or use the legacy flat fields.')
                ->schema([
                    Forms\Components\Repeater::make('tenderDocuments')
                        ->label('Document Files')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Display Filename')
                                ->maxLength(255),

                            Forms\Components\Select::make('type')
                                ->options([
                                    'rfp'           => 'RFP',
                                    'tor'           => 'ToR',
                                    'specification' => 'Specification',
                                    'other'         => 'Other',
                                ])
                                ->required()
                                ->default('other'),

                            Forms\Components\FileUpload::make('path')
                                ->label('File')
                                ->directory('tenders')
                                ->disk('public')
                                ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                ->required()
                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                    if ($state) {
                                        $set('name', basename($state));
                                    }
                                }),
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Legacy Document Fields (optional)')
                ->collapsed()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('document_url')
                        ->label('Generic Document URL (fallback)')
                        ->url()
                        ->maxLength(500)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make('has_rfp_document')
                        ->label('Has RFP Document')
                        ->live(),

                    Forms\Components\FileUpload::make('rfp_path')
                        ->label('RFP File')
                        ->directory('tenders/rfp')
                        ->disk('public')
                        ->visible(fn (Forms\Get $get) => $get('has_rfp_document')),

                    Forms\Components\TextInput::make('rfp_document_name')
                        ->maxLength(255)
                        ->visible(fn (Forms\Get $get) => $get('has_rfp_document')),

                    Forms\Components\Toggle::make('has_tor_document')
                        ->label('Has ToR Document')
                        ->live(),

                    Forms\Components\FileUpload::make('tor_path')
                        ->label('ToR File')
                        ->directory('tenders/tor')
                        ->disk('public')
                        ->visible(fn (Forms\Get $get) => $get('has_tor_document')),

                    Forms\Components\TextInput::make('tor_document_name')
                        ->maxLength(255)
                        ->visible(fn (Forms\Get $get) => $get('has_tor_document')),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(45),

                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Ref #')
                    ->searchable()
                    ->fontFamily('mono'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'open',
                        'danger'  => 'closed',
                        'warning' => 'awarded',
                    ]),

                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->deadline?->isPast() ? 'danger' : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['open' => 'Open', 'closed' => 'Closed', 'awarded' => 'Awarded']),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenders::route('/'),
            'create' => Pages\CreateTender::route('/create'),
            'edit'   => Pages\EditTender::route('/{record}/edit'),
        ];
    }
}

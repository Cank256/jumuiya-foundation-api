<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnualReportResource\Pages;
use App\Models\AnnualReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AnnualReportResource extends Resource
{
    protected static ?string $model = AnnualReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Annual Reports';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Report Details')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->label('Display Label')
                        ->placeholder('e.g. 2024 / 2025 Annual Report')
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('title')
                        ->label('Title (fallback if label empty)')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('year')
                        ->label('Year (last fallback)')
                        ->placeholder('e.g. 2024')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Lower numbers appear first.'),
                ]),

            Forms\Components\Section::make('Report File')
                ->schema([
                    Forms\Components\FileUpload::make('file_path')
                        ->label('Upload PDF')
                        ->directory('reports')
                        ->disk('public')
                        ->acceptedFileTypes(['application/pdf'])
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state && is_string($state)) {
                                $set('file_size', filesize(storage_path('app/public/' . $state)));
                            }
                        }),

                    Forms\Components\TextInput::make('href')
                        ->label('External URL (fallback if no file uploaded)')
                        ->url()
                        ->maxLength(500),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('year')
                    ->sortable(),

                Tables\Columns\TextColumn::make('formatted_file_size')
                    ->label('File Size'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable()
                    ->label('Order'),

                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->reorderable('sort_order')
            ->filters([
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
            ->defaultSort('sort_order', 'asc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAnnualReports::route('/'),
            'create' => Pages\CreateAnnualReport::route('/create'),
            'edit'   => Pages\EditAnnualReport::route('/{record}/edit'),
        ];
    }
}

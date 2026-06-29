<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CareerResource\Pages;
use App\Models\Career;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CareerResource extends Resource
{
    protected static ?string $model = Career::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Jobs & Careers';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Job Overview')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'closed' => 'Closed',
                        ])
                        ->required()
                        ->default('active'),

                    Forms\Components\Select::make('employment_type')
                        ->options([
                            'full-time'    => 'Full-time',
                            'part-time'    => 'Part-time',
                            'contract'     => 'Contract',
                            'internship'   => 'Internship',
                            'consultancy'  => 'Consultancy',
                            'volunteer'    => 'Volunteer',
                        ]),

                    Forms\Components\TextInput::make('department')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('location')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('salary_range')
                        ->placeholder('e.g. UGX 3,500,000 – 4,500,000/month')
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('application_deadline')
                        ->native(false),

                    Forms\Components\TextInput::make('reports_to')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('supervises_who')
                        ->maxLength(255),
                ]),

            Forms\Components\Section::make('Role Details')
                ->schema([
                    Forms\Components\RichEditor::make('purpose_of_role')
                        ->label('Purpose of Role')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('description')
                        ->label('Full Description')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('responsibilities')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('requirements')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('core_competencies')
                        ->label('Core Competencies')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Application Information')
                ->schema([
                    Forms\Components\RichEditor::make('application_requirements')
                        ->label('Application Requirements')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('application_process')
                        ->label('Application Process')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('apply_here')
                        ->label('External Application URL')
                        ->url()
                        ->maxLength(500),

                    Forms\Components\RichEditor::make('disclaimer')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Job Description Document')
                ->columns(2)
                ->schema([
                    Forms\Components\Toggle::make('has_document')
                        ->label('Has Downloadable Document')
                        ->live()
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('document_path')
                        ->label('Upload Document (PDF)')
                        ->directory('careers/documents')
                        ->disk('public')
                        ->acceptedFileTypes(['application/pdf'])
                        ->visible(fn (Forms\Get $get) => $get('has_document'))
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $set('document_name', basename($state));
                            }
                        }),

                    Forms\Components\TextInput::make('document_name')
                        ->maxLength(255)
                        ->visible(fn (Forms\Get $get) => $get('has_document')),
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

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'danger'  => 'closed',
                    ]),

                Tables\Columns\TextColumn::make('employment_type')
                    ->badge()
                    ->label('Type'),

                Tables\Columns\TextColumn::make('department')
                    ->limit(25),

                Tables\Columns\TextColumn::make('location')
                    ->limit(25),

                Tables\Columns\TextColumn::make('application_deadline')
                    ->date('M d, Y')
                    ->sortable()
                    ->color(fn ($record) => $record->application_deadline?->isPast() ? 'danger' : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['active' => 'Active', 'closed' => 'Closed']),

                Tables\Filters\SelectFilter::make('employment_type')
                    ->options([
                        'full-time'   => 'Full-time',
                        'part-time'   => 'Part-time',
                        'contract'    => 'Contract',
                        'internship'  => 'Internship',
                        'consultancy' => 'Consultancy',
                        'volunteer'   => 'Volunteer',
                    ]),

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
            'index'  => Pages\ListCareers::route('/'),
            'create' => Pages\CreateCareer::route('/create'),
            'edit'   => Pages\EditCareer::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    protected static function viewPermission(): string   { return 'view content'; }
    protected static function createPermission(): string { return 'create content'; }
    protected static function editPermission(): string   { return 'edit content'; }
    protected static function deletePermission(): string { return 'delete content'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('category')
                        ->options([
                            'Forum'       => 'Forum',
                            'Workshop'    => 'Workshop',
                            'Training'    => 'Training',
                            'Fundraising' => 'Fundraising',
                            'Symposium'   => 'Symposium',
                            'Networking'  => 'Networking',
                            'Conference'  => 'Conference',
                        ])
                        ->searchable(),

                    Forms\Components\Select::make('status')
                        ->options([
                            'upcoming'  => 'Upcoming',
                            'ongoing'   => 'Ongoing',
                            'completed' => 'Completed',
                        ])
                        ->required()
                        ->default('upcoming'),

                    Forms\Components\TextInput::make('location')
                        ->maxLength(255),

                    Forms\Components\DateTimePicker::make('start_date')
                        ->native(false),

                    Forms\Components\DateTimePicker::make('end_date')
                        ->native(false)
                        ->after('start_date'),

                    Forms\Components\TextInput::make('time')
                        ->placeholder('e.g. 9:00 AM – 5:00 PM EAT')
                        ->maxLength(100),

                    Forms\Components\TextInput::make('seats')
                        ->placeholder('e.g. Limited seats available')
                        ->maxLength(100),
                ]),

            Forms\Components\Section::make('Media & Links')
                ->columns(2)
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->directory('events')
                        ->disk('public')
                        ->imagePreviewHeight('120')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('registration_url')
                        ->url()
                        ->maxLength(500),

                    Forms\Components\Toggle::make('featured')
                        ->label('Featured / Hero Event')
                        ->helperText('Pins this event to the hero position on the events page.'),
                ]),

            Forms\Components\Section::make('Description')
                ->schema([
                    Forms\Components\RichEditor::make('description')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'h2', 'h3', 'bulletList', 'orderedList',
                            'link', 'blockquote', 'undo', 'redo',
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->disk('public')
                    ->width(60)
                    ->height(40),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'upcoming',
                        'warning' => 'ongoing',
                        'gray'    => 'completed',
                    ]),

                Tables\Columns\TextColumn::make('category')
                    ->badge(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->label('Featured'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'upcoming'  => 'Upcoming',
                        'ongoing'   => 'Ongoing',
                        'completed' => 'Completed',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Forum'       => 'Forum',
                        'Workshop'    => 'Workshop',
                        'Training'    => 'Training',
                        'Fundraising' => 'Fundraising',
                        'Symposium'   => 'Symposium',
                        'Networking'  => 'Networking',
                        'Conference'  => 'Conference',
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
            ->defaultSort('start_date', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit'   => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}

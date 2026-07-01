<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactSubmissionResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = ContactSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Inbox';

    protected static ?string $navigationLabel = 'Contact Messages';

    protected static ?int $navigationSort = 1;

    protected static function viewPermission(): string   { return 'view messages'; }
    protected static function createPermission(): string { return 'manage messages'; }
    protected static function editPermission(): string   { return 'manage messages'; }
    protected static function deletePermission(): string { return 'manage messages'; }

    public static function getNavigationBadge(): ?string
    {
        return (string) ContactSubmission::where('status', 'unread')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Sender Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('first_name')->disabled(),
                    Forms\Components\TextInput::make('last_name')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('organisation')->disabled(),
                    Forms\Components\TextInput::make('subject')->disabled(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'unread'   => 'Unread',
                            'read'     => 'Read',
                            'replied'  => 'Replied',
                            'archived' => 'Archived',
                        ]),
                ]),

            Forms\Components\Section::make('Message')
                ->schema([
                    Forms\Components\Textarea::make('message')
                        ->disabled()
                        ->rows(6)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Meta')
                ->collapsed()
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('ip_address')->disabled(),
                    Forms\Components\Textarea::make('user_agent')->disabled()->rows(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'unread',
                        'success' => 'read',
                        'primary' => 'replied',
                        'gray'    => 'archived',
                    ]),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['first_name', 'last_name']),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->badge(),

                Tables\Columns\TextColumn::make('message')
                    ->limit(60),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'unread'   => 'Unread',
                        'read'     => 'Read',
                        'replied'  => 'Replied',
                        'archived' => 'Archived',
                    ]),

                Tables\Filters\SelectFilter::make('subject')
                    ->options([
                        'general'     => 'General',
                        'partnership' => 'Partnership',
                        'volunteer'   => 'Volunteer',
                        'donation'    => 'Donation',
                        'media'       => 'Media',
                        'other'       => 'Other',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_read')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check')
                    ->action(fn (ContactSubmission $record) => $record->update(['status' => 'read']))
                    ->visible(fn (ContactSubmission $record) => $record->status === 'unread'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_read')
                        ->label('Mark as Read')
                        ->action(fn ($records) => $records->each->update(['status' => 'read'])),
                    Tables\Actions\BulkAction::make('archive')
                        ->label('Archive')
                        ->action(fn ($records) => $records->each->update(['status' => 'archived'])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContactSubmissions::route('/'),
            'view'   => Pages\ViewContactSubmission::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

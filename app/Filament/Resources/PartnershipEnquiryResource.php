<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\PartnershipEnquiryResource\Pages;
use App\Models\PartnershipEnquiry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnershipEnquiryResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = PartnershipEnquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Inbox';

    protected static ?string $navigationLabel = 'Partnership Enquiries';

    protected static ?int $navigationSort = 2;

    protected static function viewPermission(): string   { return 'view messages'; }
    protected static function createPermission(): string { return 'manage messages'; }
    protected static function editPermission(): string   { return 'manage messages'; }
    protected static function deletePermission(): string { return 'manage messages'; }

    public static function getNavigationBadge(): ?string
    {
        return (string) PartnershipEnquiry::where('status', 'unread')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Enquiry Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')->disabled(),
                    Forms\Components\TextInput::make('organisation')->disabled(),
                    Forms\Components\TextInput::make('email')->disabled(),
                    Forms\Components\TextInput::make('partnership_type')->disabled(),
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

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('organisation')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('partnership_type')
                    ->badge(),

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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('mark_read')
                    ->label('Mark Read')
                    ->icon('heroicon-o-check')
                    ->action(fn (PartnershipEnquiry $record) => $record->update(['status' => 'read']))
                    ->visible(fn (PartnershipEnquiry $record) => $record->status === 'unread'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('mark_read')
                        ->label('Mark as Read')
                        ->action(fn ($records) => $records->each->update(['status' => 'read'])),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnershipEnquiries::route('/'),
            'view'  => Pages\ViewPartnershipEnquiry::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

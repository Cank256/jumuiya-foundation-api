<?php

namespace App\Filament\Pages;

use App\Models\AnalyticsError;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AnalyticsErrors extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?string $navigationLabel = 'JS Errors';

    protected static ?string $title = 'Frontend JS Errors';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.analytics-errors';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('view analytics') ?? false;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = AnalyticsError::where('occurred_at', '>=', now()->startOfDay())->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(AnalyticsError::query()->latest('occurred_at'))
            ->columns([
                TextColumn::make('message')
                    ->limit(80)
                    ->searchable()
                    ->wrap(),

                TextColumn::make('context')
                    ->label('Context')
                    ->formatStateUsing(fn ($state) => is_array($state)
                        ? collect($state)->map(fn ($v, $k) => "{$k}: {$v}")->implode(' | ')
                        : $state
                    )
                    ->limit(80)
                    ->wrap(),

                TextColumn::make('ip')->label('IP'),

                TextColumn::make('occurred_at')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('today')
                    ->label('Today only')
                    ->query(fn (Builder $query) => $query->where('occurred_at', '>=', now()->startOfDay())),

                Filter::make('this_week')
                    ->label('This week')
                    ->query(fn (Builder $query) => $query->where('occurred_at', '>=', now()->startOfWeek())),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->paginated([25, 50, 100]);
    }
}

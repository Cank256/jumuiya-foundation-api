<?php

namespace App\Filament\Widgets;

use App\Models\AnalyticsEvent;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Model;

class TopPagesWidget extends BaseWidget
{
    protected static ?string $heading = 'Top Pages (Last 7 Days)';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    /**
     * The grouped query has no meaningful primary key.
     * Use the path value itself as the unique row identifier.
     */
    public function getTableRecordKey(Model $record): string
    {
        return md5((string) $record->path);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AnalyticsEvent::query()
                    ->where('type', 'page_view')
                    ->where('occurred_at', '>=', now()->subDays(7))
                    ->selectRaw('MIN(id) as id, path, COUNT(*) as views')
                    ->groupBy('path')
                    ->orderByDesc('views')
                    ->limit(20)
            )
            ->columns([
                Tables\Columns\TextColumn::make('path')
                    ->label('Page Path'),

                Tables\Columns\TextColumn::make('views')
                    ->label('Views'),
            ])
            ->paginated(false);
    }
}

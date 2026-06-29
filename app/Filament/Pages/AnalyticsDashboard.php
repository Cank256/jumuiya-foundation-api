<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AnalyticsOverviewWidget;
use App\Filament\Widgets\PageViewsChartWidget;
use App\Filament\Widgets\TopPagesWidget;
use App\Models\AnalyticsError;
use App\Models\AnalyticsEvent;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AnalyticsDashboard extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Analytics';

    protected static ?string $navigationLabel = 'Analytics Dashboard';

    protected static ?string $title = 'Analytics Dashboard';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.analytics-dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            AnalyticsOverviewWidget::class,
            PageViewsChartWidget::class,
            TopPagesWidget::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AnalyticsEvent::query()->latest('occurred_at')
            )
            ->heading('Recent Analytics Events')
            ->columns([
                TextColumn::make('type')->badge()->colors([
                    'primary' => 'page_view',
                    'success' => 'form_submission',
                    'warning' => 'button_click',
                ]),
                TextColumn::make('path')->limit(50)->placeholder('—'),
                TextColumn::make('button_name')->label('Button')->placeholder('—'),
                TextColumn::make('form_name')->label('Form')->placeholder('—'),
                TextColumn::make('ip')->label('IP'),
                TextColumn::make('occurred_at')->dateTime('M d, Y H:i:s')->sortable(),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->paginated([25, 50, 100]);
    }
}

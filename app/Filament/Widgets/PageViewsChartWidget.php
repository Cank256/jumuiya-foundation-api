<?php

namespace App\Filament\Widgets;

use App\Models\AnalyticsEvent;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PageViewsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Page Views — Last 30 Days';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $start = now()->subDays(29)->startOfDay();
        $end   = now()->endOfDay();

        $views = AnalyticsEvent::query()
            ->where('type', 'page_view')
            ->whereBetween('occurred_at', [$start, $end])
            ->selectRaw('DATE(occurred_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $data   = [];

        for ($i = 29; $i >= 0; $i--) {
            $date     = now()->subDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('M d');
            $data[]   = $views[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Page Views',
                    'data'            => $data,
                    'fill'            => true,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor'     => 'rgba(59, 130, 246, 1)',
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

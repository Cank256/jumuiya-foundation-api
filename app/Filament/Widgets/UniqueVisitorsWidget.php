<?php

namespace App\Filament\Widgets;

use App\Models\AnalyticsEvent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UniqueVisitorsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        // Unique visitors = distinct session_ids (or IPs as fallback) for page_view events
        $uniqueToday = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '>=', now()->startOfDay())
            ->distinct('session_id')
            ->count('session_id');

        $uniqueYesterday = AnalyticsEvent::where('type', 'page_view')
            ->whereBetween('occurred_at', [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()])
            ->distinct('session_id')
            ->count('session_id');

        $unique7Days = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '>=', now()->subDays(7))
            ->distinct('session_id')
            ->count('session_id');

        $unique30Days = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '>=', now()->subDays(30))
            ->distinct('session_id')
            ->count('session_id');

        // Trend: compare today vs yesterday
        $todayTrend = $uniqueYesterday > 0
            ? round((($uniqueToday - $uniqueYesterday) / $uniqueYesterday) * 100, 1)
            : null;

        $todayDescription = $todayTrend !== null
            ? ($todayTrend >= 0 ? "+{$todayTrend}%" : "{$todayTrend}%") . ' vs yesterday'
            : 'No data for yesterday';

        $todayColor = match (true) {
            $todayTrend === null  => 'gray',
            $todayTrend >= 0      => 'success',
            default               => 'danger',
        };

        // New vs returning in last 30 days
        $allSessions30Days = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '>=', now()->subDays(30))
            ->whereNotNull('session_id')
            ->pluck('session_id')
            ->unique();

        $returningCount = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '<', now()->subDays(30))
            ->whereIn('session_id', $allSessions30Days)
            ->distinct('session_id')
            ->count('session_id');

        $newCount = $allSessions30Days->count() - $returningCount;

        return [
            Stat::make('Unique Visitors Today', number_format($uniqueToday))
                ->description($todayDescription)
                ->descriptionIcon($todayTrend !== null && $todayTrend >= 0
                    ? 'heroicon-m-arrow-trending-up'
                    : 'heroicon-m-arrow-trending-down'
                )
                ->color($todayColor),

            Stat::make('Unique Visitors (7 days)', number_format($unique7Days))
                ->description('Distinct sessions in the past week')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Unique Visitors (30 days)', number_format($unique30Days))
                ->description('Distinct sessions this month')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('New vs Returning (30 days)', number_format($newCount) . ' / ' . number_format($returningCount))
                ->description('New · Returning')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),
        ];
    }
}

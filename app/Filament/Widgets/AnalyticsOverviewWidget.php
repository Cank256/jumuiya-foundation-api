<?php

namespace App\Filament\Widgets;

use App\Models\AnalyticsEvent;
use App\Models\AnalyticsError;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\PartnershipEnquiry;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalyticsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();

        $pageViewsToday  = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '>=', $today)->count();
        $pageViews7Days  = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '>=', now()->subDays(7))->count();
        $pageViews30Days = AnalyticsEvent::where('type', 'page_view')
            ->where('occurred_at', '>=', now()->subDays(30))->count();

        $errorsToday = AnalyticsError::where('occurred_at', '>=', $today)->count();

        $unreadMessages  = ContactSubmission::where('status', 'unread')->count();
        $unreadEnquiries = PartnershipEnquiry::where('status', 'unread')->count();

        $activeSubscribers = NewsletterSubscriber::where('active', true)->count();

        return [
            Stat::make('Page Views Today', number_format($pageViewsToday))
                ->description('Last 7 days: ' . number_format($pageViews7Days))
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),

            Stat::make('Page Views (30 days)', number_format($pageViews30Days))
                ->description('All-time: ' . number_format(AnalyticsEvent::where('type', 'page_view')->count()))
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),

            Stat::make('JS Errors Today', $errorsToday)
                ->description('Total logged: ' . AnalyticsError::count())
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($errorsToday > 0 ? 'danger' : 'success'),

            Stat::make('Unread Messages', $unreadMessages + $unreadEnquiries)
                ->description($unreadMessages . ' contact · ' . $unreadEnquiries . ' partnership')
                ->descriptionIcon('heroicon-m-envelope')
                ->color($unreadMessages + $unreadEnquiries > 0 ? 'warning' : 'gray'),

            Stat::make('Newsletter Subscribers', number_format($activeSubscribers))
                ->description('Active subscribers')
                ->descriptionIcon('heroicon-m-at-symbol')
                ->color('success'),
        ];
    }
}

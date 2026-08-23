<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Passcode;
use App\Models\ScanHistory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ScanStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $scansToday = ScanHistory::whereDate('created_at', today());

        return [
            Stat::make('Total User', Employee::count())
                ->description(Employee::whereNull('passcode_id')->count().' belum di-assign passcode')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('primary')
                ->icon('heroicon-o-users'),

            Stat::make('Passcode Aktif', Passcode::where('is_active', true)->count())
                ->description(Passcode::count().' total passcode')
                ->descriptionIcon('heroicon-m-key')
                ->color('warning')
                ->icon('heroicon-o-key'),

            Stat::make('Scan Hari Ini', $scansToday->count())
                ->description($scansToday->clone()->where('status', 'success')->count().' sukses, '.$scansToday->clone()->where('status', 'failed')->count().' gagal')
                ->descriptionIcon('heroicon-m-qr-code')
                ->color('success')
                ->icon('heroicon-o-qr-code'),
        ];
    }
}

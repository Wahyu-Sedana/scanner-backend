<?php

namespace App\Filament\Widgets;

use App\Models\ScanHistory;
use Filament\Widgets\ChartWidget;

class ScanTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Scan (14 hari terakhir)';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $days = collect(range(13, 0))->map(fn (int $daysAgo) => today()->subDays($daysAgo));

        $counts = ScanHistory::query()
            ->selectRaw("DATE(created_at) as date, status, count(*) as total")
            ->where('created_at', '>=', today()->subDays(13))
            ->groupBy('date', 'status')
            ->get()
            ->groupBy('date');

        $success = $days->map(fn ($day) => (int) optional($counts->get($day->toDateString()))
            ->firstWhere('status', 'success')?->total ?? 0);

        $failed = $days->map(fn ($day) => (int) optional($counts->get($day->toDateString()))
            ->firstWhere('status', 'failed')?->total ?? 0);

        return [
            'datasets' => [
                [
                    'label' => 'Sukses',
                    'data' => $success->values(),
                    'borderColor' => '#22c55e',
                    'backgroundColor' => '#22c55e33',
                ],
                [
                    'label' => 'Gagal',
                    'data' => $failed->values(),
                    'borderColor' => '#ef4444',
                    'backgroundColor' => '#ef444433',
                ],
            ],
            'labels' => $days->map(fn ($day) => $day->translatedFormat('d M'))->values(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

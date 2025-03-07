<?php

namespace App\Filament\Resources\LetterInResource\Widgets;

use App\Models\LetterIn;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DailyLetterIn extends BaseWidget
{
    protected static string $view = 'filament.resources.letter-in-resource.widgets.daily-letter-in';

    protected function getCards(): array
    {
        $surat = LetterIn::whereHas('dispotition', function($query) {
            $query->where('departement_id', auth()->user()->departement_id);
        })->get();

        $today = LetterIn::whereDate('created_at', today())->count();
        $yesterdayCount = LetterIn::whereDate('created_at', today()->subDay())->count();
        $percentageChange = $yesterdayCount != 0
            ? round(($today - $yesterdayCount) / $yesterdayCount * 100, 2)
            : 0;
        return [
            Card::make('Total Surat Masuk', LetterIn::count())
                ->description('Jumlah semua surat yang masuk')
                ->color('primary'),

            Card::make('Surat Belum Disposisi', LetterIn::where('status_disposisi', 'belum_disposisi')->count())
                ->description('Surat yang belum diproses pimpinan')
                ->color('warning'),

            Card::make('Surat Sudah Disposisi', LetterIn::where('status_disposisi', 'sudah_disposisi')->count())
                ->description('Surat yang sudah diproses pimpinan')
                ->color('success'),

            Card::make('Surat Masuk Hari Ini', $today)
                ->description($percentageChange . '% dari kemarin')
                ->descriptionIcon($percentageChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($percentageChange >= 0 ? 'success' : 'danger'),
        ];
    }

    protected function getChart(): ?array
    {
        $data = LetterIn::selectRaw('DATE(created_at) as date, count(*) as count')
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('date')
            ->pluck('count', 'date');

        return [
            'datasets' => [
                [
                    'label' => 'Surat Masuk 7 Hari Terakhir',
                    'data' => $data->values(),
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->keys()->map(fn ($date) => \Carbon\Carbon::parse($date)->format('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // line, bar, atau pie
    }
}

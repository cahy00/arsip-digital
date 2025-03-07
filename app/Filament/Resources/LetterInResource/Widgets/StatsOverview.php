<?php

namespace App\Filament\Resources\LetterInResource\Widgets;

use App\Models\LetterIn;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $surat = LetterIn::whereHas('dispotition', function($query) {
            $query->where('departement_id', auth()->user()->departement_id);
        })->get();

        $today = LetterIn::whereDate('tanggal_masuk', today())->count();
        $yesterdayCount = LetterIn::whereDate('tanggal_masuk', today()->subDay())->count();
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




}

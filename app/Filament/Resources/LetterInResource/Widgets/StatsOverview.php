<?php

namespace App\Filament\Resources\LetterInResource\Widgets;

use App\Models\LetterIn;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Surat Masuk', LetterIn::count())
                ->description('Jumlah semua surat yang masuk')
                ->color('primary'),
            
            Stat::make('Surat Belum Disposisi', LetterIn::where('status_disposisi', 'Belum Disposisi')->count())
                ->description('Surat yang belum diproses pimpinan')
                ->color('warning'),

            Stat::make('Surat Sudah Disposisi', LetterIn::where('status_disposisi', 'Sudah Disposisi')->count())
                ->description('Surat yang sudah diproses pimpinan')
                ->color('success'),
        ];
    }
}

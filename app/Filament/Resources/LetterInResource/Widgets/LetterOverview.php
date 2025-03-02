<?php

namespace App\Filament\Resources\LetterInResource\Widgets;

use App\Models\LetterIn;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;


class LetterOverview extends BaseWidget
{
    protected static string $view = 'filament.resources.letter-in-resource.widgets.letter-overview';

    public function getCards(): array
    {
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
        ];
    }

    public function getStats(): array
    {
        return [
            Stat::make('Total Surat Masuk', LetterIn::count())
        ];
    }
}

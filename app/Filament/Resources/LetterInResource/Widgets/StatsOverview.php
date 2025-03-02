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

    // protected function getStats(): array
    // {
    //     return [
	// 				Stat::make('Total Pertanyaan', LetterIn::all()->count()),
	// 				Stat::make('Pertanyaan Sudah Dijawab', LetterIn::where('status_disposisi', 'belum_dijawab')->count())
	// 				->color('success'),
	// 				Stat::make('Pertanyaan Belum Dijawab', LetterIn::where('status_disposisi', 'sudah_dijawab')->count()),
    //     ];
    // }

//     public function getStats(): array
// {
//     return [
//         'total_surat' => LetterIn::whereBetween('tanggal_surat', [
//             now()->startOfMonth(),
//             now()->endOfMonth()
//         ])->count(),
//     ];
// }
}

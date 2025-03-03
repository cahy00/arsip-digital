<?php

namespace App\Filament\Pages;

use App\Models\LetterIn;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;


class PimpinanDashboard extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.pimpinan-dashboard';
    protected static bool $shouldRegisterNavigation = false;


    protected function getTableQuery(): Builder
    {
        return LetterIn::query()
            ->where('status_disposisi', 'belum_disposisi')
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('judul_surat')
                ->searchable()
                ->sortable(),

            TextColumn::make('nomor_surat'),

            TextColumn::make('tanggal_surat')
                ->date(),


        ];
    }

    public function getStats(): array
    {
        return [
            'total_belum_disposisi' => LetterIn::where('status_disposisi', 'belum_disposisi')->count(),
            'total_surat' => LetterIn::count(),
        ];
    }
}

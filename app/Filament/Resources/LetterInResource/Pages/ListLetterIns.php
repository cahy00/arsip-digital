<?php

namespace App\Filament\Resources\LetterInResource\Pages;

use App\Filament\Resources\LetterInResource;
use App\Models\LetterIn;
use Filament\Actions;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLetterIns extends ListRecords
{
    protected static string $resource = LetterInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Input Surat Masuk'),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            LetterInResource\Widgets\StatsOverview::class
        ];
    }

    public function getTabs(): array
    {
        return [
            'Semua Data' => ListRecords\Tab::make(),
            'Minggu Ini' => ListRecords\Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->whereDate('tanggal_masuk', '>=', now()->subWeek()))
            ->badge(LetterIn::query()->where('tanggal_masuk', '>=', now()->subWeek())->count()),
            'Bulan Ini' => ListRecords\Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->whereDate('tanggal_masuk', '>=', now()->subMonths()))
            ->badge(LetterIn::query()->where('tanggal_masuk', '>=', now()->subMonth())->count()),
            'Tahun Ini' => ListRecords\Tab::make()
            ->modifyQueryUsing(fn(Builder $query) => $query->whereDate('tanggal_masuk', '>=', now()->subYear()))
                ->badge(LetterIn::query()->where('tanggal_masuk', '>=', now()->subYear())->count()),
        ];
    }
}

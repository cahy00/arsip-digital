<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispotitionResource\Pages;
use App\Filament\Resources\DispotitionResource\RelationManagers;
use App\Models\Dispotition;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use function Laravel\Prompts\progress;

class DispotitionResource extends Resource
{
    protected static ?string $model = Dispotition::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';
    protected static ?string $navigationGroup = 'Manajemen Surat';
    protected static ?string $pluralModelLabel = 'Disposisi Pimpinan';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('letterIn.judul_surat')
                    ->label('Judul Surat')
                    ->sortable()
                    ->url(fn ($record) => asset('storage/' . $record->LetterIn->file))
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('letterIn.asal_surat')
                    ->label('Nomor Surat')
                    ->sortable(),
//                Tables\Columns\TextColumn::make('letterIn.sifat_surat')->label('Tanggal Masuk')->sortable(),
                Tables\Columns\TextColumn::make('departement.name')->label('Unit Kerja')->sortable(),

                Tables\Columns\TextColumn::make('ket')->label('Arahan Pimpinan'),
                BadgeColumn::make('progress.status_progress')
                    ->label('Status')
                    ->colors([
                        'warning' => 'belum_selesai',
                        'success' => 'selesai',
                        'info' => 'on_progress',

                    ])
                    ->getStateUsing(function ($record) {
                        return $record->progress()->latest()->first()?->status_progress;
                    })

            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('progress')
                    ->label('Progress')
                    ->icon('heroicon-o-plus-circle')
                    ->button()
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('status_progress')
                        ->label('Status')
                        ->options([
                            'on_progress' => 'On Progress',
                            'selesai' => 'Selesai',
                        ]),
                        Forms\Components\Textarea::make('ket'),
                        Forms\Components\FileUpload::make('document-progress')
                            ->label('Dokumen Penyelesaian')
                            ->required()
                            ->disk('public')
                            ->directory('document-progress')
                            ->visibility('private'),

                    ])

                    ->modalHeading('Beri Disposisi')
                    ->modalButton('Simpan')
                    ->action(function ($data, $record) {
                        $record->progress()->create([
                            'status_progress' => $data['status_progress'], // Pastikan field ada
                            'ket' => $data['ket'],
                            'document-progress' => $data['document-progress'],
                            'disposition_id' => $record->id
                        ]);
                    })
//                    ->action(fn ($data, $record) => $record->progress()
//                    ->Create(['dispotition_id' => $record->id], ['status_progress' => $data['status_progress']], ['ket' => $data['ket']]))
                    ->hidden(fn () => auth()->user()->hasRole(['admin', 'pimpinan'])),
//                Tables\Actions\Action::make('lihat-progress')
//                    ->label('Lihat Progress')
//                    ->icon('heroicon-o-eye')
//                    ->button()
//                    ->color('success')
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Progress')
                    ->button()
                    ->color('success')

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Progress')
                ->description('Progress Penyelesaian Surat')
                ->schema([
                    RepeatableEntry::make('progress')
                        ->label('Tahapan')
                        ->schema([
                            TextEntry::make('status_progress')
                            ->label('Status')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'belum_selesai' => 'warning',
                                    'selesai' => 'success',
                                    'on_progress' => 'info',
                                }),
                            TextEntry::make('created_at')
                            ->label('Tanggal Input'),
                            TextEntry::make('ket'),
                            TextEntry::make('document-progress')
                                ->url(fn ($state) => url("storage/{$state}"))
//                                ->columnSpan(1),

                        ])
                        ->columns(1)
                ])
                ]);

    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDispotitions::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (Auth::user()->hasRole(['admin', 'pimpinan'])) {
            return parent::getEloquentQuery();
        }else{
            return parent::getEloquentQuery()
                ->where('departement_id', auth()->user()->departement_id)
                ->with(['letterIn', 'departement']);
        }
    }

    public static function canCreate(): bool
    {
        return Auth::user()->hasRole('admin'); // Hanya user non-pimpinan yang bisa create
    }
}

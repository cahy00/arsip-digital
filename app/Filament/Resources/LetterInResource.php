<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\LetterIn;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LetterInResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LetterInResource\RelationManagers;
use App\Filament\Resources\LetterInResource\Widgets\StatsOverview;

class LetterInResource extends Resource
{
    protected static ?string $model = LetterIn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen Surat';
    protected static ?string $pluralModelLabel = 'Surat Masuk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    Wizard::make()->schema([
                        Wizard\Step::make('Data Surat')
                            ->schema([
                                Forms\Components\TextInput::make('judul_surat')
                                ->label('Perihal Surat')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('nomor_surat')
                                ->required()
                                ->maxLength(255),
                            
                            Forms\Components\DatePicker::make('tanggal_surat')
                                ->required(),
                                
                            
                            ]),
                        Wizard\Step::make('Data Masuk Kanreg')
                            ->schema([
                                Forms\Components\DatePicker::make('tanggal_masuk')
                                ->required(),
                            Forms\Components\TextInput::make('asal_surat')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('sifat_surat')
                            ->required()
                            ->options([
                                'biasa' => 'Biasa',
                                'segera' => 'Segera',
                                'penting' => 'Penting',
                                'rahasia' => 'Rahasia',
                            ]),
                        Forms\Components\Select::make('kategori_surat')
                            ->required()
                            ->options([
                                'draft' => 'Surat Pengantar',
                                'reviewing' => 'Nota Dinas',
                                'published' => 'Surat Permohonan',
                            ]),
                            ]),
                        Wizard\Step::make('Upload Dokumen')
                            ->schema([
                                Forms\Components\FileUpload::make('file')
                                ->required()
                                ->disk('public')
                                ->directory('surat-masuk')
                                ->visibility('private'),
                            ]),
                    ])
                ])
                
                        ]);
            
            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul_surat')
                    ->searchable()
                    ->url(fn ($record) => asset('storage/' . $record->file))
                ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('asal_surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sifat_surat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kategori_surat')
                    ->searchable(),
                // Tables\Columns\ImageColumn::make('file')
                //     ->disk('public')
                //     ->searchable(),
                BadgeColumn::make('status_disposisi')
                ->label('Status')
                ->colors([
                    'warning' => 'Belum Disposisi',
                    'success' => 'Sudah Disposisi',
                ])
                ->formatStateUsing(fn ($record) => $record->status_disposisi),
                // SelectColumn::make('disposisi')
                // ->label('Disposisi')
                // ->options([
                //     'Belum Disposisi' => 'Belum Disposisi',
                //     'Ke Bidang A' => 'Ke Bidang A',
                //     'Ke Bidang B' => 'Ke Bidang B',
                //     'Ke Pegawai X' => 'Ke Pegawai X',
                //     'Ke Pegawai Y' => 'Ke Pegawai Y',
                // ])
                // ->afterStateUpdated(fn ($record, $state) => $record->update(['disposisi' => $state]))
                // ->searchable()
                // ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('download')
                // ->label('unduh')
                // ->icon('heroicon-o-arrow-down-on-square')
                // ->url(fn ($record) => Storage::disk('public')->url($record->file))
                // ->url(fn ($record) => asset('storage/' . $record->file)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLetterIns::route('/'),
            'create' => Pages\CreateLetterIn::route('/create'),
            'edit' => Pages\EditLetterIn::route('/{record}/edit'),
        ];
    }

    public static function getTitle(): string
    {
    return 'Tambah Surat Masuk';
    }

    public static function getWidgets(): array
    {
        return [
            LetterInResource\Widgets\StatsOverview::class,
        ];
    }
}

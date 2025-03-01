<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Employee;
use App\Models\LetterIn;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Departement;
use App\Models\Dispotition;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Split;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LetterInResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LetterInResource\RelationManagers;
use App\Filament\Resources\LetterInResource\Widgets\StatsOverview;
use App\Filament\Resources\LetterInResource\RelationManagers\DispotitionRelationManager;

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
                        Section::make([
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
                                ]),
                        Section::make([
                            Repeater::make('dispotition')
                            ->relationship()
                            ->schema([
                                Select::make('departement_id')
                                ->relationship('departement', 'name')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn($state, callable $set)=>$set('employee_id', null))
                                ->label('Disposisi Surat - Unit'),
                                Select::make('employee_id')
                                ->relationship('employee', 'name')
                                ->required()
                                ->options(function(callable $get){
                                    $departementId = $get('departement_id');
                                    if(!$departementId){
                                        return[];
                                    }

                                    return Employee::where('departement_id', $departementId)
                                    ->pluck('name', 'id')->toArray();
                                })
                                ->label('Disposisi Surat - Pegawai'),
                                Textarea::make('ket')
                                ->autosize()
                                ])
                        ])
                        ->label('Disposisi Surat')
                        ->description('Tentukan disposisi surat ke unit kerja dan pegawai')
                        ->hidden(fn () => !auth()->user()->hasRole(['pimpinan']))
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
                    ->searchable()
                    ->hidden(fn () => !auth()->user()->hasRole(['admin', 'pimpinan'])),
                Tables\Columns\TextColumn::make('tanggal_surat')
                    ->searchable()
                    ->hidden(fn () => !auth()->user()->hasRole(['admin', 'pimpinan'])),
                Tables\Columns\TextColumn::make('asal_surat')
                    ->searchable()
                    ->hidden(fn () => !auth()->user()->hasRole(['admin', 'pimpinan'])),
                Tables\Columns\TextColumn::make('sifat_surat')
                    ->searchable()
                    ->hidden(fn () => !auth()->user()->hasRole(['admin', 'pimpinan'])),
                Tables\Columns\TextColumn::make('kategori_surat')
                    ->searchable()
                    ->hidden(fn () => !auth()->user()->hasRole(['admin', 'pimpinan'])),
                // Tables\Columns\ImageColumn::make('file')
                //     ->disk('public')
                //     ->searchable(),
                BadgeColumn::make('status_disposisi')
                ->label('Status')
                ->colors([
                    'warning' => 'belum_disposisi',
                    'success' => 'sudah_disposisi',
                ])
                ->formatStateUsing(fn ($record) => $record->status_disposisi),
                
            ])
            ->filters([
                // SelectFilter::make('dispotition')
                //     ->relationship(name: 'departements', titleAttribute: 'name')
                //     ->label('Kategori Pertanyaan'),
                // SelectFilter::make('status')
                // ->relationship(name: 'answer', titleAttribute: 'status')
                // ->label('Status Pertanyaan'),
                // SelectFilter::make('city_id')
                //     ->relationship(name: 'city', titleAttribute: 'name')
                //     ->label('Kab/Kota'),
                // Filter::make('created_at')
                //     ->form([
                //         DatePicker::make('created_from')
                //             ->label('Tanggal Awal'),
                //         DatePicker::make('created_until')
                //             ->label('Tanggal Akhir'),
                //     ])
                //     ->query(function (Builder $query, array $data): Builder {
                //         return $query
                //             ->when(
                //                 $data['created_from'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                //             )
                //             ->when(
                //                 $data['created_until'],
                //                 fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                //             );
                //     })
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\Action::make('download')
                // ->label('unduh')
                // ->icon('heroicon-o-arrow-down-on-square')
                // ->url(fn ($record) => Storage::disk('public')->url($record->file))
                // ->url(fn ($record) => asset('storage/' . $record->file)),
                // Action::make('beri_disposisi')
                // ->modalActions()

            ])
            ->bulkActions([
                    Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultGroup('status_disposisi');
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

    public static function canCreate(): bool
{
    return !Auth::user()->hasRole('pimpinan'); // Hanya user non-pimpinan yang bisa create
}

    
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispotitionResource\Pages;
use App\Filament\Resources\DispotitionResource\RelationManagers;
use App\Models\Dispotition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                Tables\Columns\TextColumn::make('letterIn.judul_surat')->label('Judul Surat')->sortable(),
                Tables\Columns\TextColumn::make('letterIn.nomor_surat')->label('Nomor Surat')->sortable(),
                Tables\Columns\TextColumn::make('letterIn.tanggal_masuk')->label('Tanggal Masuk')->sortable(),
                Tables\Columns\TextColumn::make('departement.name')->label('Unit Kerja')->sortable(),
                Tables\Columns\TextColumn::make('employee.name')->label('Pegawai')->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
//                Tables\Actions\DeleteAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDispotitions::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('departement_id', auth()->user()->departement_id)
            ->with(['letterIn', 'departement']);
    }
}

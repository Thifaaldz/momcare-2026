<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PatientResource\Pages;
use App\Filament\Admin\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('User'),
                Forms\Components\TextInput::make('usia_kehamilan_minggu')
                    ->required()
                    ->numeric()
                    ->label('Usia Kehamilan (Minggu)'),
                Forms\Components\TextInput::make('usia_ibu')
                    ->required()
                    ->numeric()
                    ->label('Usia Ibu'),
                Forms\Components\TextInput::make('berat_badan')
                    ->required()
                    ->numeric()
                    ->label('Berat Badan'),
                Forms\Components\TextInput::make('tinggi_badan')
                    ->required()
                    ->numeric()
                    ->label('Tinggi Badan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('usia_kehamilan_minggu')
                    ->label('Usia Kehamilan (Minggu)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('usia_ibu')
                    ->label('Usia Ibu')
                    ->sortable(),
                Tables\Columns\TextColumn::make('berat_badan')
                    ->label('Berat Badan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->label('Tinggi Badan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}

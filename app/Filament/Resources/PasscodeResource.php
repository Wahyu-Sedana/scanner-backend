<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasscodeResource\Pages;
use App\Filament\Resources\PasscodeResource\RelationManagers;
use App\Models\Passcode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PasscodeResource extends Resource
{
    protected static ?string $model = Passcode::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->maxLength(64)
                    ->unique(ignoreRecord: true)
                    ->helperText('Kode yang diketik user di layar passcode.'),
                Forms\Components\TextInput::make('label')
                    ->maxLength(64)
                    ->helperText('Opsional, misalnya nama staff pemegang kode ini.'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->helperText('Matikan untuk menonaktifkan kode ini tanpa menghapusnya.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),
                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Terakhir dipakai')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Belum pernah'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status aktif'),
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
            'index' => Pages\ListPasscodes::route('/'),
            'create' => Pages\CreatePasscode::route('/create'),
            'edit' => Pages\EditPasscode::route('/{record}/edit'),
        ];
    }
}

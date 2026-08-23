<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasscodeResource\Pages;
use App\Models\Passcode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PasscodeResource extends Resource
{
    protected static ?string $model = Passcode::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Akses';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Passcode')
                    ->icon('heroicon-o-key')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->prefixIcon('heroicon-o-hashtag')
                            ->required()
                            ->maxLength(64)
                            ->unique(ignoreRecord: true)
                            ->helperText('Kode yang diketik user di layar passcode.'),
                        Forms\Components\TextInput::make('label')
                            ->maxLength(64)
                            ->helperText('Opsional, misalnya nama staff pemegang kode ini.'),
                        Forms\Components\Toggle::make('is_active')
                            ->inline(false)
                            ->required()
                            ->default(true)
                            ->helperText('Matikan untuk menonaktifkan kode ini tanpa menghapusnya.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->badge()
                    ->fontFamily('mono')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('employee.name')
                    ->label('Dipegang oleh')
                    ->icon('heroicon-o-user')
                    ->placeholder('Belum di-assign'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),
                Tables\Columns\TextColumn::make('last_used_at')
                    ->label('Terakhir dipakai')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->placeholder('Belum pernah'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(fn ($query) => $query->with('employee'))
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

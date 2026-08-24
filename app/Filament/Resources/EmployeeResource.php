<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use App\Models\Passcode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $navigationGroup = 'Akses';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'User';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'personal_email', 'phone'];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data User')
                    ->description('Data karyawan yang akan login ke aplikasi scanner.')
                    ->icon('heroicon-o-identification')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->prefixIcon('heroicon-o-user')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('personal_email')
                            ->label('Personal email')
                            ->prefixIcon('heroicon-o-envelope')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('No HP')
                            ->prefixIcon('heroicon-o-device-phone-mobile')
                            ->tel()
                            ->required()
                            ->maxLength(32)
                            ->unique(ignoreRecord: true),
                    ]),
                Forms\Components\Section::make('Login Aplikasi')
                    ->description('Passcode ini dipakai bersama nomor HP di atas untuk login di aplikasi scanner.')
                    ->icon('heroicon-o-key')
                    ->schema([
                        Forms\Components\Select::make('passcode_id')
                            ->label('Passcode')
                            ->native(false)
                            ->prefixIcon('heroicon-o-key')
                            ->options(fn () => Passcode::query()->pluck('code', 'id'))
                            ->searchable()
                            ->unique(ignoreRecord: true)
                            ->helperText('Satu passcode hanya bisa dipakai oleh satu user.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->icon('heroicon-o-user')
                    ->weight('semibold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('personal_email')
                    ->label('Personal email')
                    ->icon('heroicon-o-envelope')
                    ->searchable()
                    ->copyable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('No HP')
                    ->icon('heroicon-o-device-phone-mobile')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('passcode.code')
                    ->label('Passcode')
                    ->badge()
                    ->fontFamily('mono')
                    ->color(fn (?string $state): string => $state ? 'success' : 'gray')
                    ->placeholder('Belum di-assign'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with('passcode'))
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('passcode_id')
                    ->label('Status passcode')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah di-assign')
                    ->falseLabel('Belum di-assign')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('passcode_id'),
                        false: fn ($query) => $query->whereNull('passcode_id'),
                    ),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

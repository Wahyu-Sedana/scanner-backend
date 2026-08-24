<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanHistoryResource\Pages;
use App\Models\ScanHistory;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ScanHistoryResource extends Resource
{
    protected static ?string $model = ScanHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Scan History';

    protected static ?string $navigationGroup = 'Monitoring';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'barcode';

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    // History datang dari API tiap kali app scan barcode — bukan dibuat/diedit manual dari admin.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Detail Scan')
                ->icon('heroicon-o-qr-code')
                ->columns(2)
                ->schema([
                    TextEntry::make('barcode')->fontFamily('mono'),
                    TextEntry::make('format')->placeholder('—'),
                    TextEntry::make('mode')->badge(),
                    TextEntry::make('status')->badge()
                        ->color(fn(string $state): string => $state === 'success' ? 'success' : 'danger'),
                    TextEntry::make('environment')
                        ->label('Environment')
                        ->badge()
                        ->color(fn(?string $state): string => $state === 'production' ? 'success' : 'warning')
                        ->placeholder('—'),
                    // TextEntry::make('reason')->placeholder('—')->columnSpanFull(),
                    // TextEntry::make('product_name')
                    //     ->label('Produk')
                    //     ->icon('heroicon-o-cube')
                    //     ->placeholder('—'),
                    TextEntry::make('customer_name')
                        ->label('Nama pelanggan')
                        ->icon('heroicon-o-identification')
                        ->placeholder('—'),
                    TextEntry::make('customer_phone')
                        ->label('No HP pelanggan')
                        ->icon('heroicon-o-device-phone-mobile')
                        ->placeholder('—'),
                    TextEntry::make('scanned_by')
                        ->label('Discan oleh')
                        ->icon('heroicon-o-user')
                        ->state(fn(ScanHistory $record): ?string => $record->passcode?->employee?->name)
                        ->placeholder('—'),
                    TextEntry::make('created_at')->label('Waktu scan')->dateTime('d M Y, H:i'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barcode')
                    ->searchable()
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('mode')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'member' => 'info',
                        'redeem' => 'warning',
                        'event-ticket' => 'primary',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => $state === 'success' ? 'success' : 'danger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('environment')
                    ->label('Environment')
                    ->badge()
                    ->color(fn(?string $state): string => $state === 'production' ? 'success' : 'warning')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Produk')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('customer_phone')
                    ->label('No HP pelanggan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('scanned_by')
                    ->label('Discan oleh')
                    ->state(fn(ScanHistory $record): ?string => $record->passcode?->employee?->name)
                    ->searchable(query: fn(Builder $query, string $search): Builder => $query
                        ->whereHas('passcode.employee', fn(Builder $query) => $query->where('name', 'like', "%{$search}%")))
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->with('passcode.employee'))
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('mode')
                    ->options([
                        'member' => 'Member',
                        'redeem' => 'Redeem',
                        'event-ticket' => 'Event Ticket',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('environment')
                    ->label('Environment')
                    ->options([
                        'staging' => 'Staging',
                        'production' => 'Production',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScanHistories::route('/'),
            'view' => Pages\ViewScanHistory::route('/{record}'),
        ];
    }
}

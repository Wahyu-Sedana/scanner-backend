<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScanHistoryResource\Pages;
use App\Models\ScanHistory;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ScanHistoryResource extends Resource
{
    protected static ?string $model = ScanHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Scan History';

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
            TextEntry::make('barcode')->fontFamily('mono'),
            TextEntry::make('format'),
            TextEntry::make('mode')->badge(),
            TextEntry::make('status')->badge()
                ->color(fn (string $state): string => $state === 'success' ? 'success' : 'danger'),
            TextEntry::make('reason')->placeholder('—'),
            TextEntry::make('passcode.label')->label('Discan oleh')->placeholder('—'),
            TextEntry::make('created_at')->label('Waktu scan')->dateTime(),
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'success' ? 'success' : 'danger')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(40)
                    ->placeholder('—')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('passcode.label')
                    ->label('Discan oleh')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime()
                    ->sortable(),
            ])
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

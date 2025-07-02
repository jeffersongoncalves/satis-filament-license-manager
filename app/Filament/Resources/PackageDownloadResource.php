<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageDownloadResource\Pages;
use App\Models\PackageDownload;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class PackageDownloadResource extends Resource
{
    protected static ?string $model = PackageDownload::class;

    protected static ?string $navigationIcon = 'tabler-download';

    protected static bool $isGloballySearchable = true;

    protected static ?string $recordTitleAttribute = 'version';

    protected static ?int $navigationSort = 2;

    public static function getGloballySearchableAttributes(): array
    {
        return ['version'];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return PackageDownloadResource::getUrl('view', ['record' => $record]);
    }

    public static function getModelLabel(): string
    {
        return __('package_downloads.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('package_downloads.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('package_downloads.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('package_downloads.navigation.group');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Cache::rememberForever('package_downloads_count', fn () => PackageDownload::query()->count());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('package.name')
                            ->label(__('package_downloads.infolist.package.name'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('version')
                            ->label(__('package_downloads.infolist.version'))
                            ->badge()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('downloads')
                            ->label(__('package_downloads.infolist.downloads'))
                            ->badge()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('package.name')
                    ->label(__('package_downloads.table.package.name'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->label(__('package_downloads.table.version'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('downloads')
                    ->label(__('package_downloads.table.downloads'))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('package_downloads.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('package_downloads.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPackageDownloads::route('/'),
            'view' => Pages\ViewPackageDownload::route('/{record}'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageReleaseResource\Pages;
use App\Models\PackageRelease;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class PackageReleaseResource extends Resource
{
    protected static ?string $model = PackageRelease::class;

    protected static ?string $navigationIcon = 'tabler-package-import';

    protected static bool $isGloballySearchable = true;

    protected static ?string $recordTitleAttribute = 'version';

    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['version'];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return PackageReleaseResource::getUrl('view', ['record' => $record]);
    }

    public static function getModelLabel(): string
    {
        return __('package_releases.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('package_releases.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('package_releases.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('package_releases.navigation.group');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string)Cache::rememberForever('package_releases_count', fn() => PackageRelease::query()->count());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('package.name')
                            ->label(__('package_releases.infolist.package.name'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('version')
                            ->label(__('package_releases.infolist.version'))
                            ->badge()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('time')
                            ->label(__('package_releases.infolist.time'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('type')
                            ->label(__('package_releases.infolist.type'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->label(__('package_releases.infolist.description'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('homepage')
                            ->label(__('package_releases.infolist.homepage'))
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\RepeatableEntry::make('dependencies')
                    ->label(__('package_releases.infolist.section.dependencies'))
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('dependencies.infolist.name')),
                        Infolists\Components\TextEntry::make('pivot.version')
                            ->label(__('package_releases.infolist.version')),
                    ])
                    ->columns()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('package.name')
                    ->label(__('package_releases.table.package.name'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->label(__('package_releases.table.version'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dependencies_count')
                    ->label(__('package_releases.table.dependencies_count'))
                    ->counts('dependencies'),
                Tables\Columns\TextColumn::make('time')
                    ->label(__('package_releases.table.time'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('package_releases.table.type'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('package_releases.table.description'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('homepage')
                    ->label(__('package_releases.table.homepage'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('package_releases.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('package_releases.table.updated_at'))
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
            'index' => Pages\ListPackageReleases::route('/'),
            'view' => Pages\ViewPackageRelease::route('/{record}'),
        ];
    }
}

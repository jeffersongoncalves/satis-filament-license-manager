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
        return __('Package Release');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Package Releases');
    }

    public static function getNavigationLabel(): string
    {
        return __('Package Releases');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Package');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Cache::rememberForever('package_releases_count', fn () => PackageRelease::query()->count());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('package.name')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('version')
                            ->badge()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('time')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('type')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('description')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('homepage')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make()
                    ->heading(__('Dependencies'))
                    ->schema([
                        Infolists\Components\TextEntry::make('dependencies.name')
                            ->hiddenLabel()
                            ->listWithLineBreaks()
                            ->bulleted(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('package.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dependencies_count')
                    ->counts('dependencies'),
                Tables\Columns\TextColumn::make('time')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('homepage')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DependencyResource\Pages;
use App\Filament\Resources\DependencyResource\RelationManagers;
use App\Models\Dependency;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class DependencyResource extends Resource
{
    protected static ?string $model = Dependency::class;

    protected static ?string $navigationIcon = 'tabler-brand-pnpm';

    protected static bool $isGloballySearchable = true;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return DependencyResource::getUrl('view', ['record' => $record]);
    }

    public static function getModelLabel(): string
    {
        return __('dependencies.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dependencies.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('dependencies.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('dependencies.navigation.group');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Cache::rememberForever('dependencies_count', fn () => Dependency::query()->count());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('dependencies.infolist.name'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('type')
                            ->label(__('dependencies.infolist.type'))
                            ->badge()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('versions')
                            ->label(__('dependencies.infolist.versions'))
                            ->badge()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('dependencies.table.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('dependencies.table.type'))
                    ->badge(),
                Tables\Columns\TextColumn::make('versions')
                    ->label(__('dependencies.table.versions'))
                    ->badge(),
                Tables\Columns\TextColumn::make('package_releases_count')
                    ->label(__('dependencies.table.package_releases_count'))
                    ->counts('packageReleases'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('dependencies.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('dependencies.table.updated_at'))
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
            RelationManagers\PackageReleasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDependencies::route('/'),
            'view' => Pages\ViewDependency::route('/{record}'),
        ];
    }
}

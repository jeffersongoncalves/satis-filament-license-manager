<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DependencyResource\Pages;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
        return __('Dependency');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Dependencies');
    }

    public static function getNavigationLabel(): string
    {
        return __('Dependencies');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Package');
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
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('version')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make()
                    ->heading(__('Package Releases'))
                    ->schema([
                        Infolists\Components\TextEntry::make('packageReleases.name')
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('version')
                    ->searchable(),
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
            'index' => Pages\ListDependencies::route('/'),
            'view' => Pages\ViewDependency::route('/{record}'),
        ];
    }
}

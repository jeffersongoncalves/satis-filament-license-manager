<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PackageReleasesRelationManager extends RelationManager
{
    protected static string $relationship = 'packageReleases';

    public function infolist(Infolist $infolist): Infolist
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
                            ->hiddenLabel(),
                        Infolists\Components\TextEntry::make('pivot.version')
                            ->hiddenLabel()
                            ->badge(),
                    ])
                    ->contained(false)
                    ->columns()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('package_releases.plural'))
            ->recordTitleAttribute('version')
            ->columns([
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
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalWidth(MaxWidth::Large),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('package_releases.plural');
    }
}

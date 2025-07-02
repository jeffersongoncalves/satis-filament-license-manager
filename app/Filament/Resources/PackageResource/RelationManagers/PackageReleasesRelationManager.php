<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Filament\Resources\PackageReleaseResource;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class PackageReleasesRelationManager extends RelationManager
{
    protected static string $relationship = 'packageReleases';

    public function infolist(Infolist $infolist): Infolist
    {
        return PackageReleaseResource::infolist($infolist);
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
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('package_releases.plural');
    }
}

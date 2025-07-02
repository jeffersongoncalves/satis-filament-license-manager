<?php

namespace App\Filament\Resources\PackageResource\RelationManagers;

use App\Filament\Resources\PackageDownloadResource;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PackageDownloadsRelationManager extends RelationManager
{
    protected static string $relationship = 'packageDownloads';

    public function infolist(Infolist $infolist): Infolist
    {
        return PackageDownloadResource::infolist($infolist);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version')
            ->columns([
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
}

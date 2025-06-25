<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Resources\PackageResource;
use App\Jobs\SyncTenantPackages;
use Filament\Resources\Pages\CreateRecord;

class CreatePackage extends CreateRecord
{
    protected static string $resource = PackageResource::class;

    protected function afterCreate(): void
    {
        SyncTenantPackages::dispatch()->delay(now()->addSeconds(5));
    }
}

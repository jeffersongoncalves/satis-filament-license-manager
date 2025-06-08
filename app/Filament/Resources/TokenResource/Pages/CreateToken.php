<?php

namespace App\Filament\Resources\TokenResource\Pages;

use App\Filament\Resources\TokenResource;
use App\Jobs\SyncTokenPackages;
use Filament\Resources\Pages\CreateRecord;

class CreateToken extends CreateRecord
{
    protected static string $resource = TokenResource::class;

    protected function afterCreate(): void
    {
        SyncTokenPackages::dispatch($this->record)->delay(now()->addSeconds(60));
    }
}

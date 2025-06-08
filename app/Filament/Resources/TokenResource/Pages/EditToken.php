<?php

namespace App\Filament\Resources\TokenResource\Pages;

use App\Filament\Resources\TokenResource;
use App\Jobs\SyncTokenPackages;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditToken extends EditRecord
{
    protected static string $resource = TokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        SyncTokenPackages::dispatch($this->record)->delay(now()->addSeconds(60));
    }
}

<?php

namespace App\Filament\Resources\DependencyResource\Pages;

use App\Filament\Resources\DependencyResource;
use Filament\Resources\Pages\ViewRecord;

class ViewDependency extends ViewRecord
{
    protected static string $resource = DependencyResource::class;

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}

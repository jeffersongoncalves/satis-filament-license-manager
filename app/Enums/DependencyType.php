<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum DependencyType: string implements HasColor, HasIcon, HasLabel
{
    case Public = 'public';
    case Private = 'private';

    public static function of(int|string|self $value): DependencyType
    {
        return self::tryFrom($value instanceof self ? $value->value : $value);
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Public => __('dependencies.dependency_type.public'),
            self::Private => __('dependencies.dependency_type.private'),
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Public => 'iconoir-package',
            self::Private => 'iconoir-package-lock',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Public => 'success',
            self::Private => 'danger',
        };
    }
}

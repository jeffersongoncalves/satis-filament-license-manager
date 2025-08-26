<?php

namespace App\Models;

use App\Enums\DependencyType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * @property int $id
 * @property string $name
 * @property DependencyType $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Packagist whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Packagist extends Model
{
    protected $fillable = [
        'name',
        'type',
    ];

    public static function getDependencyType(string $name): DependencyType
    {
        if ($packagist = Packagist::query()->where('name', $name)->first()) {
            return $packagist->type;
        }
        $packagist = Packagist::create([
            'name' => $name,
            'type' => self::getRepoByPackagist($name),
        ]);

        return $packagist->type;
    }

    private static function getRepoByPackagist(string $name): DependencyType
    {
        try {
            return Http::get("https://repo.packagist.org/p/{$name}.json")->successful() ? DependencyType::Public : DependencyType::Private;
        } catch (ConnectionException $e) {
            return DependencyType::Private;
        }
    }

    protected function casts(): array
    {
        return [
            'type' => DependencyType::class,
        ];
    }
}

<?php

namespace App\Models;

use App\Observers\DependencyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed>|null $versions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DependencyPackageRelease> $packageReleaseRequires
 * @property-read int|null $package_release_requires_count
 * @property-read \App\Models\DependencyPackageRelease|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PackageRelease> $packageReleases
 * @property-read int|null $package_releases_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereVersions($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(DependencyObserver::class)]
class Dependency extends Model
{
    protected $fillable = [
        'name',
        'versions',
    ];

    public function packageReleaseRequires(): HasMany
    {
        return $this->hasMany(DependencyPackageRelease::class);
    }

    public function packageReleases(): BelongsToMany
    {
        return $this->belongsToMany(PackageRelease::class)->using(DependencyPackageRelease::class)->withTimestamps();
    }

    public function casts(): array
    {
        return [
            'versions' => 'array',
        ];
    }
}

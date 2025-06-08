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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PackageReleaseRequire> $packageReleaseRequires
 * @property-read int|null $package_release_requires_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dependency whereVersion($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(DependencyObserver::class)]
class Dependency extends Model
{
    protected $fillable = [
        'name',
        'version',
    ];

    public function packageReleaseRequires(): HasMany
    {
        return $this->hasMany(PackageReleaseRequire::class);
    }

    public function packageReleases(): BelongsToMany
    {
        return $this->belongsToMany(PackageRelease::class)->using(PackageReleaseRequire::class)->withTimestamps();
    }
}

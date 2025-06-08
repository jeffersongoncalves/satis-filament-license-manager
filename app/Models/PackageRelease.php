<?php

namespace App\Models;

use App\Observers\PackageReleaseObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $package_id
 * @property string $version
 * @property string $time
 * @property string $type
 * @property string $description
 * @property string $homepage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DependencyPackageRelease|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dependency> $dependencies
 * @property-read int|null $dependencies_count
 * @property-read \App\Models\Package $package
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DependencyPackageRelease> $packageReleaseRequires
 * @property-read int|null $package_release_requires_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereHomepage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageRelease whereVersion($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(PackageReleaseObserver::class)]
class PackageRelease extends Model
{
    protected $fillable = [
        'package_id',
        'version',
        'time',
        'type',
        'description',
        'homepage',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function packageReleaseRequires(): HasMany
    {
        return $this->hasMany(DependencyPackageRelease::class);
    }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(Dependency::class)->using(DependencyPackageRelease::class)->withTimestamps();
    }
}

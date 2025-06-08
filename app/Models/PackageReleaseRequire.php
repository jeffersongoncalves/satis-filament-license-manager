<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $package_id
 * @property int $package_release_id
 * @property int $dependency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dependency $dependency
 * @property-read \App\Models\Package $package
 * @property-read \App\Models\PackageRelease $packageRelease
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire whereDependencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire wherePackageReleaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PackageReleaseRequire extends Pivot
{
    public $incrementing = true;

    protected $fillable = [
        'package_id',
        'package_release_id',
        'dependency_id',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function packageRelease(): BelongsTo
    {
        return $this->belongsTo(PackageRelease::class);
    }

    public function dependency(): BelongsTo
    {
        return $this->belongsTo(Dependency::class);
    }
}

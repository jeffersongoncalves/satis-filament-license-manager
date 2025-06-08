<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read \App\Models\Dependency|null $dependency
 * @property-read \App\Models\Package|null $package
 * @property-read \App\Models\PackageRelease|null $packageRelease
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageReleaseRequire query()
 *
 * @mixin \Eloquent
 */
class PackageReleaseRequire extends Model
{
    protected $table = 'package_release_require';

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

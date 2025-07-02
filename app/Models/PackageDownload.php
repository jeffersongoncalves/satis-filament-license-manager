<?php

namespace App\Models;

use App\Observers\PackageDownloadObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $package_id
 * @property string $version
 * @property int $downloads
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Package $package
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload whereDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageDownload whereVersion($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(PackageDownloadObserver::class)]
class PackageDownload extends Model
{
    protected $fillable = [
        'package_id',
        'version',
        'downloads',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    protected function casts(): array
    {
        return [
            'downloads' => 'integer',
        ];
    }
}

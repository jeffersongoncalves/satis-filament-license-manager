<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $package_id
 * @property int $token_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Package $package
 * @property-read \App\Models\Token $token
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken whereTokenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageToken whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class PackageToken extends Pivot
{
    public $incrementing = true;

    protected $fillable = [
        'package_id',
        'token_id',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }
}

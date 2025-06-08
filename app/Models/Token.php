<?php

namespace App\Models;

use App\Models\Concerns\GenerateCode;
use App\Observers\TokenObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PackageToken|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Package> $packages
 * @property-read int|null $packages_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Token whereUsername($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(TokenObserver::class)]
class Token extends Model
{
    use GenerateCode;

    protected $fillable = [
        'name',
        'username',
        'password',
        'token',
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    public static function getColumnCode(): array
    {
        return ['token'];
    }

    public static function getLengthCode(): array
    {
        return ['token' => 36];
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)->using(PackageToken::class)->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}

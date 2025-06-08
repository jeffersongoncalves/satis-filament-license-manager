<?php

namespace App\Models;

use App\Models\Concerns\GenerateCode;
use App\Observers\TokenObserver;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property-read string $composer_command
 * @property-read string $composer_repository
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
class Token extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use GenerateCode;

    protected $fillable = [
        'name',
        'email',
        'token',
    ];

    protected $hidden = [
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

    public function getAuthPasswordName(): string
    {
        return 'token';
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)->using(PackageToken::class)->withTimestamps();
    }

    protected function composerCommand(): Attribute
    {
        return Attribute::get(function (): string {
            $host = str(config('app.url'))->remove('http://', 'https://')->toString();

            return "composer global config http-basic.{$host} token {$this->token}";
        });
    }

    protected function composerRepository(): Attribute
    {
        return Attribute::get(function (): string {
            $host = config('app.url');

            return <<<TEXT
    "repositories": [
        {
            "type": "composer",
            "url": "$host"
        }
    ]
TEXT;
        });
    }
}

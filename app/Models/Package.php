<?php

namespace App\Models;

use App\Enums\PackageType;
use App\Observers\PackageObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property PackageType $type
 * @property string $url
 * @property string|null $username
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $folder
 * @property-read string $name_provider
 * @property-read \App\Models\PackageToken|null $pivot
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Token> $tokens
 * @property-read int|null $tokens_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereUsername($value)
 *
 * @mixin \Eloquent
 */
#[ObservedBy(PackageObserver::class)]
class Package extends Model
{
    protected $fillable = [
        'name',
        'type',
        'url',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function tokens(): BelongsToMany
    {
        return $this->belongsToMany(Token::class)->using(PackageToken::class)->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'type' => PackageType::class,
        ];
    }

    protected function folder(): Attribute
    {
        return Attribute::get(fn (): string => str($this->url)->replace('://', '---')->toString());
    }

    protected function nameProvider(): Attribute
    {
        return Attribute::get(fn (): string => str($this->name)->replace('/', '~')->toString());
    }
}

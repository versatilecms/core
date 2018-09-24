<?php

namespace Versatile\Core\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Versatile\Core\Contracts\UserInterface;
use Versatile\Core\Traits\HasRelationships;
use Versatile\Core\Traits\VersatileUser;
use Versatile\Searchable\Searchable;

class User extends Authenticatable implements UserInterface
{
    use VersatileUser;
    use HasRelationships;
    use Searchable;

    protected $guarded = [];

    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'users.name' => 10,
            'users.email' => 10
        ]
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            if (config('versatile.user.add_default_role_on_register') && is_null($model->role_id)) {
                $model->setRole(config('versatile.user.default_role'))
                    ->save();
            }
        });
    }

    public function getAvatarAttribute($value)
    {
        if (is_null($value)) {
            return config('versatile.user.default_avatar', 'users/default.png');
        }

        return $value;
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setLocaleAttribute($value)
    {
        $this->attributes['settings'] = collect($this->settings)->merge(['locale' => $value]);
    }

    public function getLocaleAttribute()
    {
        return $this->settings['locale'];
    }
}

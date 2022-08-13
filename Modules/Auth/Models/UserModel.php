<?php

namespace Modules\Auth\Models;


use Illuminate\Database\Eloquent\Model;
use Modules\Shared\Models\Aggregation;

class UserModel extends Aggregation
{

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
        'reset_token',
        'reset_at',
        'reset_expires',
        'password',
        'active',
        'active_token',
        'active_expires',
        'status',
        'status_message',
        'created_at',
        'updated_at',
        'deleted_at',
        'first_name',
        'last_name',
        'image',
        'gender',
        'birthday',
        'country',
        'city',
        'address',
        'title',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [

    ];
    public function groupUser()
    {
        return $this->hasOne(GroupUserModel::class);
    }
}

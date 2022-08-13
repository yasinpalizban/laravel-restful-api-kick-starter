<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUserModel extends Model
{

    protected $table = 'auth_groups_users';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'group_id',
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

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    public function group()
    {
        return $this->belongsTo(GroupModel::class);
    }

}

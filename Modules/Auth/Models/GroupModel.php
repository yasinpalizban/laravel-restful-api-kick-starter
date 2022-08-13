<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Shared\Models\Aggregation;

class GroupModel extends Aggregation
{

    protected $table = 'auth_groups';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
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

    public function getGroupsForUser($userId)
    {
       $found= $this->select('auth_groups.name', 'auth_groups.id')
            ->join('auth_groups_users', 'auth_groups_users.group_id' ,'=', 'auth_groups.id')
            ->where('user_id', $userId)
            ->get();



        return $found;
    }

    public function groupUser()
    {
        return $this->hasOne(GroupUserModel::class);
    }



}

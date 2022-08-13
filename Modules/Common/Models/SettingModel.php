<?php

namespace Modules\Common\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Shared\Models\Aggregation;

class SettingModel extends Aggregation
{

    protected $table = 'setting';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'description',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',


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

}

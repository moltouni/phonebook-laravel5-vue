<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Contact.
 *
 * @package namespace App\Entities;
 */
class Contact extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'favorite',
        'first_name',
        'last_name',
        'email',
        'avatar'
    ];

    public function user() {
        return $this->belongsTo('App\Entities\User');
    }

    public function phones() {
        return $this->hasMany('App\Entities\Phone');
    }

}

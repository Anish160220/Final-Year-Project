<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductsAttribute extends Model
{
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}

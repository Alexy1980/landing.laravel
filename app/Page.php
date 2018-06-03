<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    protected $fillable = ['name', 'text', 'alias', 'images'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}

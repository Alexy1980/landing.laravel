<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
    ];

    public function users() { // у одного пользователя м/б несколько ролей
        return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id'); // вторым параметром указываем имя
        // связующей таблицы, третьим и четвертым - поля в связующей таблице
    }
}

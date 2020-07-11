<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyalur extends Model
{
    protected $table ='penyalur';
    public function user(){
        return $this->belongsTo('App\User','id_user');
    }

    public function mustahik(){
        return $this->hasMany('App\Models\Mustahik','id_penyalur');
    }
}

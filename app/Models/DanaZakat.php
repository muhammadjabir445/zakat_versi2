<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanaZakat extends Model
{
    protected $table = 'dana_zakat';
    protected $appends = ['file_bukti'];
    public function penyalur() {
        return $this->belongsTo('App\Models\Penyalur','id_penyalur');
    }

    public function petugas() {
        return $this->belongsTo('App\User','id_petugas');
    }

    public function getFileBuktiAttribute()
    {
        return $this->attributes['file_bukti'] ? asset('storage/'.$this->attributes['file_bukti']) : '';
    }
}

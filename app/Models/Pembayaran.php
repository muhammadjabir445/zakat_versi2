<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran_zakat';

    public function jenis(){
        return $this->belongsTo('App\Models\MasterDataDetail','id_jenis');
    }
}

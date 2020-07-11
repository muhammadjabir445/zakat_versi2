<?php

namespace App\Http\Controllers;

use App\Models\MasterDataDetail;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index() {
        $data = Setting::findOrFail(2);
        return $data;
    }

    public function store(Request $request){
        $data = Setting::findOrFail(2);
        $data->harga_emas = $request->harga_emas;
        $data->harga_perak = $request->harga_perak;
        $data->harga_beras = $request->harga_beras;
        $data->save();
        return response()->json([
            'message' => 'Berhasil merubah data'
        ],200);
    }

    public function jenis_zakat(){
        $data = ['jenis_zakat' => MasterDataDetail::where('id_master_data',9)->get(),
                    'harga' => $this->index()];
        return $data;
    }
}

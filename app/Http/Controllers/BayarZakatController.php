<?php

namespace App\Http\Controllers;

use App\Http\Resources\Pembayaran\Pembayaran as PembayaranPembayaran;
use App\Http\Resources\Pembayaran\PembayaranCollection;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class BayarZakatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request) {
            $data = Pembayaran::with('jenis')->where('nama_muzaki','LIKE',"%{$request->keyword}%")
            ->orWhere('email','LIKE',"%{$request->keyword}%")
            ->orWhere('nohp','LIKE',"%{$request->keyword}%")
            ->orWhere('kode','LIKE',"%{$request->keyword}%")
            ->orWhereHas('jenis',function($q) use ($request){
                $q->where('description','LIKE',"%{$request->keyword}%");
            })
            ->orderBy('created_at','desc')
            ->paginate(15);
        }else{
            $data = Pembayaran::with('jenis')
                    ->paginate(15);
        }

        return new PembayaranCollection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tgl = \Carbon\Carbon::now();
        $kode = Pembayaran::whereYear('created_at',$tgl->format('Y'))->whereMonth('created_at',$tgl->format('m'))->orderBy('created_at','desc')
        ->first();
        $kode = $kode ? $kode->kode + 1 : $tgl->format('Ym') . '001';
        $data = new Pembayaran;
        $data->kode = $kode;
        $data->nama_muzaki = $request->nama;
        $data->email = $request->email;
        $data->nohp = $request->nohp;
        $data->id_jenis = $request->jenis_zakat;
        $data->total_pembayaran = $request->jumlah_transfer;
        if ($request->file('foto')) {
            $file = $request->file('foto')->store('foto_pembayaran','public');
            $data->bukti_pembayaran = $file;
        } else {
            $data->id_approval = \Auth::user()->id;
            $data->status = 1;
        }
        $data->save();
        return response()->json([
            'message' => 'Berhasil Tambah Data',
            'data' => new PembayaranPembayaran($data)
        ],200);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Pembayaran::findOrFail($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Pembayaran::findOrFail($id);
        if ($request->status) {
            $data->status = $request->status;
            $data->id_approval = \Auth::user()->id;
        } else {
            $data->nama = $request->nama;
            $data->email = $request->email;
            $data->nohp = $request->nohp;
        }

        $data->save();
        return response()->json([
            'message' => 'Berhasil Ubah Data'
        ],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Pembayaran::findOrFail($id);
        $data->delete();
        return response()->json([
            'message' => 'Berhasil Hapus Data'
        ],200);
    }

    public function cek_pembayaran(Request $request){
        $data = Pembayaran::where('kode',$request->kode)->first();
        if ($data) {
            return new PembayaranPembayaran($data);
        } else {
            return response()->json([
                'message' => 'Berhasil Ubah Data'
            ],400);
        }
    }
}

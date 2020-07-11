<?php

namespace App\Http\Controllers\Mustahik;

use App\Http\Controllers\Controller;
use App\Models\Mustahik;
use Illuminate\Http\Request;

class MustahikController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request) {
            $user = \Auth::user()->penyalur->mustahik(function($q) use ($request) {
                $q->where('nama','LIKE',"%{$request->keyword}%")
                ->orWhere('alamat','LIKE',"%{$request->keyword}%");
            })

                    ->orderBy('created_at','desc')
                    ->paginate(15);
        }else{
            $user =  \Auth::user()->penyalur()->mustahik()->paginate(15);
        }

        return $user;
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
        $validator = \Validator::make($request->all(), [
            'ktp' => 'unique:mustahik,ktp',
        ],[
            '*.unique' => 'Mustahik Sudah Terdaftar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ],400);
        }
        $mustahik = new Mustahik;
        $mustahik->nama = $request->nama;
        $mustahik->alamat = $request->alamat;
        $mustahik->ktp = $request->ktp;
        $mustahik->id_penyalur = \Auth::user()->penyalur->id;
        $mustahik->save();
        return response()->json([
            'message' => 'Berhasil Tambah Mustahik'
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
        $mustahik = Mustahik::findOrFail($id);
        return $mustahik;
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
        $validator = \Validator::make($request->all(), [
            'ktp' => 'unique:mustahik,ktp,' .$id,
        ],[
            '*.unique' => 'Mustahik Sudah Terdaftar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ],400);
        }
        $mustahik = Mustahik::findOrFail($id);
        $mustahik->nama = $request->nama;
        $mustahik->alamat = $request->alamat;
        $mustahik->ktp = $request->ktp;
        $mustahik->save();
        return response()->json([
            'message' => 'Berhasil Edit Mustahik'
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
        $penyalur = Mustahik::findOrFail($id);
        $penyalur->delete();
        return response()->json([
            'message' => 'Berhasil Hapus Mustahik'
        ],200);
    }
}

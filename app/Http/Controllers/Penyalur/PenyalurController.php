<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use App\Models\Penyalur;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PenyalurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request) {
            $user = Penyalur::with('user')->where('nama','LIKE',"%{$request->keyword}%")
                    ->orWhere('alamat','LIKE',"%{$request->keyword}%")
                    ->orWhere('email','LIKE',"%{$request->keyword}%")
                    ->orWhere('nohp','LIKE',"%{$request->keyword}%")
                    ->orderBy('created_at','desc')
                    ->paginate(15);
        }else{
            $user = Penyalur::with('user')->paginate(15);
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
            'email' => 'unique:users,email',
        ],[
            '*.unique' => 'Email Sudah Tersedia'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ],400);
        }
        $penyalur = new Penyalur;
        $penyalur->nama = $request->nama;
        $penyalur->alamat = $request->alamat;
        $penyalur->email = $request->email;
        $penyalur->nohp = $request->nohp;
        $penyalur->save();
        return response()->json([
            'message' => 'Berhasil Tambah Penyalur'
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
        $penyalur = Penyalur::findOrFail($id);
        return $penyalur;
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
        $penyalur = Penyalur::findOrFail($id);
        $validate_email = $penyalur->id_user ? 'unique:users,email,'.$penyalur->id_user : 'unique:users,email' ;
        $validator = \Validator::make($request->all(), [
            'email' => $validate_email,
        ],[
            '*.unique' => 'Email Sudah Tersedia'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ],400);
        }

        if ($request->status) {
            $penyalur->status = $request->status;
            if ($request->status == 1) {
                if (!$penyalur->id_user) {
                    $user = new User;
                    $user->name = $penyalur->nama;
                    $user->password = \Hash::make(123456);
                    $user->email =  $penyalur->email;
                    $user->id_role = 36;
                    $user->save();
                    $penyalur->id_user = $user->id;
                    $message = 'Berhasil Konfirmasi Penyalur Zakat';
                } else {
                    $message = 'Berhasil Merubah status Penyalur Zakat';
                }


            } else {
                $message = 'Berhasil Merubah status Penyalur Zakat';

            }
        }
        else {
            $penyalur->nama = $request->nama;
            $penyalur->email = $request->email;
            $penyalur->nohp = $request->nohp;
            $penyalur->alamat = $request->alamat;
            if ($penyalur->id_user) {
                $user = $penyalur->user();
                $user->email = $request->email;
                $user->save();
            }
            $message = 'Berhasil Tambah Penyalur Zakat';

        }

        $penyalur->save();
        return response()->json([
            'message' => $message
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
        $penyalur = Penyalur::findOrFail($id);
        $penyalur->delete();
        return response()->json([
            'message' => 'Berhasil Hapus Penyalur'
        ],200);
    }
}

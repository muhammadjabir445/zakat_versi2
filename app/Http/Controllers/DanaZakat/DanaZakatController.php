<?php

namespace App\Http\Controllers\DanaZakat;

use App\Http\Controllers\Controller;
use App\Models\DanaZakat;
use App\Models\MasterDataDetail;
use App\Models\Pembayaran;
use App\Models\Penyalur;
use App\Models\Setting;
use Illuminate\Http\Request;

class DanaZakatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = '' ;
        if (\Auth::user()->id_role == 36 ) {
            $filter = Penyalur::where('id_user',\Auth::user()->id)->first();
            $filter = $filter->id;
        }
        if ($request) {
            $user = DanaZakat::with(['penyalur','petugas'])
                    ->where(function($q) use($request,$filter) {
                        $q->where('total_uang','LIKE',"%{$request->keyword}%")
                        ->where('id_penyalur','!=',null);
                        if ($filter) {
                            $q->where('id_penyalur',$filter)
                            ->where(function($q) {
                                $q->where('status',1)->orWhere('status',4);
                            });
                        }
                    })
                    ->orWhere(function($q) use($request,$filter) {
                        $q->where('total_beras','LIKE',"%{$request->keyword}%")
                        ->where('id_penyalur','!=',null);
                        if ($filter) {
                            $q->where('id_penyalur',$filter)
                            ->where(function($q) {
                                $q->where('status',1)->orWhere('status',4);
                            });
                        }
                    })
                    ->orWhere(function($q) use($request,$filter) {
                        $q->where('id_penyalur','!=',null)
                        ->whereHas('penyalur',function($q) use ($request){
                            $q->where('nama','LIKE',"%{$request->keyword}%");
                        });
                        if ($filter) {
                            $q->where('id_penyalur',$filter)
                            ->where(function($q) {
                                $q->where('status',1)->orWhere('status',4);
                            });
                        }
                    })
                    ->orWhere(function($q) use($request,$filter) {
                        $q->where('id_penyalur','!=',null)
                        ->whereHas('petugas',function($q) use ($request){
                            $q->where('name','LIKE',"%{$request->keyword}%");
                        });
                        if ($filter) {
                            $q->where('id_penyalur',$filter)
                            ->where(function($q) {
                                $q->where('status',1)->orWhere('status',4);
                            });
                        }
                    })

                    ->orderBy('created_at','desc')
                    ->paginate(15);
            $data = DanaZakat::with(['penyalur','petugas'])
            ->where(function($q) use($request) {
                $q->where('total_uang','LIKE',"%{$request->keyworddua}%")
                ->where('id_penyalur',null);
            })
            ->orWhere(function($q) use($request) {
                $q->where('total_beras','LIKE',"%{$request->keyworddua}%")
                ->where('id_penyalur',null);
            })
            ->orWhere(function($q) use($request) {
                $q->where('id_penyalur',null)
                ->whereHas('penyalur',function($q) use ($request){
                    $q->where('nama','LIKE',"%{$request->keyworddua}%");
                });
            })
            ->orWhere(function($q) use($request) {
                $q->where('id_penyalur',null)
                ->whereHas('petugas',function($q) use ($request){
                    $q->where('name','LIKE',"%{$request->keyworddua}%");
                });
            })

            ->orderBy('created_at','desc')
            ->paginate(15);
            return [
                'pembagian_zakat' => $user,
                'pembelian_beras' => $data
            ];
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 0 pnding
        // 1 setuju
        // 2 cair
        // 3 antar
        // 4 terima
        // 5 tolak
        $penyalur = Penyalur::where('status',1)->get();
        $uang = $this->getuang();

        return $data = [
            'penyalur'=>$penyalur,
            'uang_tersedia'=>$uang['total_uang_bersih'],
            'uang_beras_tersedia' => $uang['total_uang_beras'],
            'sisa_beras' => $uang['sisa_beras']
        ];
    }

    protected function getuang(){
        $uang_masuk = Pembayaran::where('status',1)->where(function($q) {
            $q->where('id_jenis','!=',38)->orWhere('id_jenis','!=',44);
        })->where('total_pembayaran', '>',1000)->get();
        $uang_keluar = DanaZakat::where('status','!=',5)->get();
        $uang_beras = Pembayaran::where('status',1)->where(function($q) {
            $q->where('id_jenis',38)->orWhere('id_jenis',44);
        })->where('total_pembayaran', '>',1000)->get();
        $uang_beras_keluar = DanaZakat::where('status','!=',5)->where('id_penyalur',null)->get();
        $total_uang_bersih = $uang_masuk->sum('total_pembayaran') - $uang_keluar->sum('total_uang');
        $total_uang_beras = $uang_beras->sum('total_pembayaran') - $uang_beras_keluar->sum('total_uang');


        $pembayaran_beras= Pembayaran::where('status',1)->where('total_pembayaran', '<',1000)->get(); //beras masuk
        $pembelian_beras = DanaZakat::where('status',4)->where('id_penyalur',null)->get(); // beli beras
        $beras_dibagikan = DanaZakat::where('status','!=',5)->where('id_penyalur','!=',null)->get(); // v\beras dibagikan
        $sisa_beras = $pembayaran_beras->sum('total_pembayaran') + $pembelian_beras->sum('total_beras') - $beras_dibagikan->sum('total_beras');

        return [
            'total_uang_bersih' => $total_uang_bersih,
            'total_uang_beras' => $total_uang_beras,
            'sisa_beras' => $sisa_beras
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data =new DanaZakat;
        if ($request->nama_yayasan) {
            $data->id_penyalur = $request->nama_yayasan;
        }
        $data->total_uang = $request->uang;
        $data->total_beras = $request->beras;
        $data->id_petugas = \Auth::user()->id;
        $data->deskripsi = $request->deskripsi;
        $data->save();
        return response()->json([
            'message' => 'Berhasil mengajukan dana'
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
        $data = DanaZakat::findOrFail($id);
        $uang = $this->getuang();
        $penyalur = Penyalur::where('status',1)->get();
        return $datas = [
            'data'=>$data,
            'uang_tersedia'=>$data->id_penyalur ? $uang['total_uang_bersih'] + $data->total_uang : $uang['total_uang_bersih'],
            'uang_beras_tersedia' => $data->id_penyalur ? $uang['total_uang_beras'] : $uang['total_uang_beras']  + $data->total_uang,
            'sisa_beras' => $data->id_penyalur ?   $uang['sisa_beras']  + $data->total_beras : $uang['sisa_beras'],
            'penyalur' =>  $penyalur = Penyalur::where('status',1)->get()
        ];


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
        $data = DanaZakat::findOrFail($id);
        if ($request->status) {
            $data->status = $request->status;
            $message = 'Berhasil mengubah status';
        }
        $data->save();
        return response()->json([
            'message' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $penyalur = DanaZakat::findOrFail($id);
        $penyalur->delete();
        return response()->json([
            'message' => 'Berhasil Hapus Data'
        ],200);
    }
}

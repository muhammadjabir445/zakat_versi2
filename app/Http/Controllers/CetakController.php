<?php

namespace App\Http\Controllers;

use App\Models\DanaZakat;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use DB;
use PDF;

class CetakController extends Controller
{
    public function index(){
        $tahun_pembayaran = Pembayaran::select(DB::raw("DATE_FORMAT(created_at, '%Y') year"))->groupBy('year')->get();
        $tahun_pembagian = DanaZakat::select(DB::raw("DATE_FORMAT(created_at, '%Y') year"))->groupBy('year')->get();
        $datasatu = [];
        $datadua = [];
        foreach ($tahun_pembayaran as $item ) {
            $text = $item->year + 1;
            $data_array = [
                'value' => $item->year,
                'text' => "$item->year/$text"
            ];
            array_push($datasatu,$data_array);
        }

        foreach ($tahun_pembagian as $item ) {
            $text = $item->year + 1;
            $data_array = [
                'value' => $item->year,
                'text' => "$item->year/$text"
            ];
            array_push($datadua,$data_array);
        }
        return [
            'tahun_pembagian' => $datadua,
            'tahun_pembayaran' => $datasatu
        ];
    }

    public function pembayaran(Request $request){
        $data = Pembayaran::select('*',DB::raw("DATE_FORMAT(created_at, '%M') month"))
        ->with('jenis')
        ->where('status',1)
        ->orWhere(function($q) use($request) {
            $q->whereYear('created_at','>=',$request->tahun)
            ->whereMonth('created_at','>=',06);
        })
        ->orWhere(function($q) use($request) {
            $q->whereYear('created_at','<=',$request->tahun + 1)
            ->whereMonth('created_at','<=',06);
        })
        ->orderBy('created_at','desc')
        ->get()
        ->groupBy('month');
        // return $data;
        $data_pembelian = DanaZakat::select('*',DB::raw("DATE_FORMAT(created_at, '%M') month"))
        ->with(['penyalur','penyalur.mustahik'])
        ->where(function($q) use($request) {
            $q->where('status',1)
            ->where('id_penyalur',null);
        })
        ->orWhere(function($q) use($request) {
            $q->whereYear('created_at','>=',$request->tahun)
            ->whereMonth('created_at','>=',06)
            ->where('id_penyalur',null);
        })
        ->orWhere(function($q) use($request) {
            $q->whereYear('created_at','<=',$request->tahun + 1)
            ->whereMonth('created_at','<=',06)
            ->where('id_penyalur',null);
        })
        ->orderBy('created_at')
        ->get();

        $pdf = PDF::loadView('pembayaran', ['data'=>$data,'tahun'=>$request->tahun,'pembelian'=>$data_pembelian]);
        return $pdf->stream('invoice.pdf');
        // return $data;
    }

    public function pembagian(Request $request){
        $data_pemabagian = DanaZakat::select('*',DB::raw("DATE_FORMAT(created_at, '%M') month"))
        ->with(['penyalur','penyalur.mustahik'])
        ->where(function($q) use($request) {
            $q->where('status',1)
            ->where('id_penyalur','!=',null);
        })
        ->orWhere(function($q) use($request) {
            $q->whereYear('created_at','>=',$request->tahun)
            ->whereMonth('created_at','>=',06)
            ->where('id_penyalur','!=',null);
        })
        ->orWhere(function($q) use($request) {
            $q->whereYear('created_at','<=',$request->tahun + 1)
            ->whereMonth('created_at','<=',06)
            ->where('id_penyalur','!=',null);
        })
        ->orderBy('created_at')
        ->get()
        ->groupBy('month');



        // return $data_pemabagian;
        $pdf = PDF::loadView('pembagian', ['data'=>$data_pemabagian,'tahun'=>$request->tahun]);
        return $pdf->stream('invoice.pdf');
    }
}

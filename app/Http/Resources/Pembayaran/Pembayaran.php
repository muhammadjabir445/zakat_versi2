<?php

namespace App\Http\Resources\Pembayaran;

use Illuminate\Http\Resources\Json\JsonResource;

class Pembayaran extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['id'=>$this->id,
                'kode'=>$this->kode,
                'nama'=>$this->nama_muzaki,
                'email'=>$this->email,
                'nohp'=>$this->nohp,
                'status' => $this->status,
                'tgl' => $this->created_at->format('d-m-Y'),
                'jenis_zakat' => $this->jenis->description,
                'total_pembayaran' => $this->total_pembayaran,
                'foto' =>$this->bukti_pembayaran ? asset('storage/' . $this->bukti_pembayaran) : '' ];
    }
}

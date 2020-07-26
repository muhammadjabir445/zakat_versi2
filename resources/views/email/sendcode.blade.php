@component('mail::message')
# Kepada Bpk/Ibu {{$data['nama']}}
anda telah melakukan pembayaran zakat, kode pembayaran anda adalah # {{$data['kode']}}

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Terimakasih,<br>
Petugas BMT At-Taqwa
@endcomponent

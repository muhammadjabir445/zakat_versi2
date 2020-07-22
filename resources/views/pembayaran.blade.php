<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        td,th{
            padding: 10px;
        }
    </style>
</head>
<body>
    <div style="">
        <h2 style="text-align: center">Laporan Pembayaran Zakat Periode {{$tahun}}/{{$tahun+1}}</h2>
    </div>
    <br>
    @php
            $uang = 0;
            $beras = 0;
    @endphp
    @foreach ($data as $key => $value)

    <table style="margin-top: 10px; witdh:100%;" border="1">

        <thead>
            <tr>
                <th colspan="6" style="text-align: center">Pembayaran bulan {{$key}} Tahun {{$value->first()->created_at->format('Y')}}</th>
            </tr>
            <tr>
                <th>Nama Muzaki</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Jenis Zakat</th>
                <th>Total Pembayaran</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($value as $item)
            <tr>
                @php
                if ($item->total_pembayaran > 1000) {
                    $uang = $uang + $item->total_pembayaran;
                } else {
                    $beras = $beras + $item->total_pembayaran;
                }

                @endphp
                <td>{{$item->nama_muzaki}}</td>
                <td>{{$item->email}}</td>
                <td>{{$item->nohp}}</td>
                <td>{{$item->jenis->description}}</td>
                <td>{{$item->total_pembayaran > 1000 ? 'Rp' .$item->total_pembayaran : $item->total_pembayaran .'/Kg Beras' }}</td>
                <td>{{$item->created_at->format('d-m-Y')}}</td>
            </tr>
            @endforeach

           </tbody>
    </table>
    @endforeach


    <br>

    <table style="margin-top: 10px; witdh:100%;" border="1">

        <thead>
            <tr>
                <th colspan="3" style="text-align: center">Pembelian Beras</th>
            </tr>
            <tr>
                <th>Jumlah uang</th>
                <th>Beras yang dibeli</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pembelian as $item)
            <tr>

                <td>Rp {{$item->total_uang}}</td>
                <td>{{$item->total_beras}}/Kg </td>
                <td>{{$item->created_at->format('d-m-Y')}}</td>
            </tr>
            @endforeach

           </tbody>
    </table>

    <br>
    <table style="margin-top: 10px; witdh:100%;" border="1">

        <thead>
            <tr>
                <th colspan="4" style="text-align: center">Total Uang Masuk</th>
                <th colspan="2">Rp {{$uang}}</th>
            </tr>


            <tr>
                <th colspan="4" style="text-align: center">Total Beras Masuk</th>
                <th colspan="2">{{$beras}}/Kg</th>
            </tr>

        </thead>
    </table>
    <br>
    <h3>Total Keseluruhan</h3>
    <table style="margin-top: 10px; witdh:100%;" border="1">

        <thead>
            <tr>
                <th colspan="4" style="text-align: center">Total Uang</th>
                <th colspan="2">Rp {{$uang - $pembelian->sum('total_uang')}}</th>
            </tr>


            <tr>
                <th colspan="4" style="text-align: center">Total Beras</th>
                <th colspan="2">{{$beras + $pembelian->sum('total_beras')}}/Kg</th>
            </tr>

        </thead>
    </table>

</body>
</html>

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
        <h2 style="text-align: center">Laporan Pembagian Zakat Periode {{$tahun}}/{{$tahun+1}}</h2>
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
                <th colspan="5" style="text-align: center">Pembagian zakat bulan {{$key}} Tahun {{$value->first()->created_at->format('Y')}}</th>
            </tr>
            <tr>
                <th>Nama Lembaga</th>
                <th>Jumlah uang</th>
                <th>Jumlah Beras</th>
                <th>Total Mustahik</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($value as $item)
            <tr>
                @php

                    $uang = $uang + $item->total_uang;

                    $beras = $beras + $item->total_beras;


                @endphp
                <td>{{$item->penyalur->nama}}</td>
                <td>Rp {{$item->total_uang}}</td>
                <td>{{$item->total_beras}}/Kg </td>
                <td>{{count($item->penyalur->mustahik)}}</td>
                <td>{{$item->created_at->format('d-m-Y')}}</td>
            </tr>
            @endforeach

           </tbody>
    </table>
    @endforeach



    <br>
    <h3>Total Keseluruhan</h3>
    <table style="margin-top: 10px; witdh:100%;" border="1">

        <thead>
            <tr>
                <th colspan="4" style="text-align: center">Total Uang Keluar</th>
                <th colspan="2">Rp {{$uang }} </th>
            </tr>


            <tr>
                <th colspan="4" style="text-align: center">Total Beras Keluar</th>
                <th colspan="2">{{$beras}}/Kg</th>
            </tr>

        </thead>
    </table>
</body>
</html>

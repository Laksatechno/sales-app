<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Surat Penawaran</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>

        body{

            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;

            color:#333;

            text-align:left;

            font-size:12px;

            margin:0;

        }

        .container{

            margin:0 auto;

            margin-top:0px;

            padding:0px;

            width:700px;

            height:auto;

            background-color:#fff;

        }

        caption{

            font-size:15px;

            margin-bottom:5px;

            text-align:right;

        }

        table{

            border:1px solid #333;

            border-collapse:collapse;

            margin:0 auto;

            width:700px;

        }

        td, tr, th{

            padding:5px;

            border:1px solid #333;

            width:100px;

        }

        th{

            background-color: #fff;

        }

        h4, p{

            margin:0px;

        }

        

        .page_break { page-break-before: always; }



        header {

                position: fixed;

                top: -30px;

                left: 0px;

                right: 0px;

                height: 50px;

        }



        footer {

                position: fixed; 
                
                bottom: -40px; 
                height: 50px; 
                text-align: center;
                color: #974578;
                margin-left: 1cm;

        }

    </style>

</head>

<body>



    <div class="container">

        <header>

            <img align="left" src="{{ URL::asset('assets/images/logo_ptlmi2.webp')}}" width="100px" height="=100px"><br><br><br><br>

        </header><br><br><br><br><br><br><br><br>

        <div align="center" style="margin: 100px">

            <img src="{{ URL::asset('assets/img/icon.webp')}}" width="400px" height="=400px">

        </div>

        <div>

            <h1 align="center" style="color: #974578; text-transform:uppercase">{{$penawarans->perihal}}</h1>

            <h3 align="center">PT Laksa Medika Internusa</h3>

        </div><br><br><br><br><br><br><br><br>

        <div align="center" style="color: #974578">

            <h4>PT Laksa Medika Internusa</h4>

           <h4>Jl. Amarta No.50 RT01, RW.01, Pelem, Baturetno, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198</h4>

            <h4>Telepon : 0274 443 6047 Email : laksamedikainternusa@gmail.com</h4>

           </div>

        



    </div>



    <div class="page_break">

            <header>

                <img align="left" src="{{ URL::asset('assets/images/logo_ptlmi2.webp')}}" width="100px" height="=100px"><br><br><br><br>            

            </header><br><br><br><br><br><br>

            <p style="font-size: 14px;margin: 10px;">No. 0{{$penawarans->id}}/LMI-SLS/<?php

                $array_bulan = array(1=>"I","II","III", "IV", "V","VI","VII","VIII","IX","X", "XI","XII");

                $bulan = $array_bulan[date('n')];

            

                echo  "$bulan";

            ?>/{{$penawarans->created_at->format('Y') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Yogyakarta, {{ \Carbon\Carbon::parse( $penawarans->created_at)->locale('id_ID')->isoFormat('dddd, D MMM YYYY') }}</p>

                <br><br>

            <p style="font-size: 12px;margin: 10px;">

                Kepada Yth.

            </p>

            <b style="font-size: 14px;line-height: 20px;margin: 10px;">{{$penawarans->customer}}<b>

            <p style="font-size: 12px;margin: 10px;">{{$penawarans->address}}</p>

            <b style="font-size: 12px;margin: 10px;">Perihal : {{$penawarans->perihal}}<b>

                <br><br>

            <p style="font-size: 12px;line-height: 20px;margin: 10px;">Dengan Hormat.</p>

            <p style="font-size: 12px;margin: 10px;">Bersama ini kami <b>PT. Laksa Medika Internusa,</b> bermaksud mengajukan {{$penawarans->perihal}} di<b> {{$penawarans->customer}}</b>.<br> Daftar penawaran terlampir. <br>

            Dengan kondisi penawaran sebagai berikut :</p>

            <p>

            @forelse($kondisis as $e=>$row)

                <p style="font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

                    {{ $e+1 }}.

                    {{ $row->kondisi }}

                </p>

            @empty

                <p>

                    Empty Data

                </p>

            @endforelse

            </p><br>

            <h3 style="margin: 10px;">List Harga Produk</h3>

            <table style="margin: 10px;">

                <tr>

                    <th colspan="1">No</th>

                    <th colspan="2">Nama</th>

                    <th colspan="1">Qty</th>

                    <th colspan="1">Harga</th>

                </tr>

                @foreach ($hargapenawarans as $e=>$hargapenawaran)

                <tr>

                    <td colspan="1" align="center">{{ $e+1 }}</td>

                    <td colspan="2">{{ $hargapenawaran->product->name }}</td>

                    <td colspan="1" align="center">{{ $hargapenawaran->qty}}</td>

                    <td colspan="1" align="right">Rp {{ number_format($hargapenawaran->price) }}</td>

                </tr>

                @endforeach

            </table>

            <p style="font-size: 12px;margin: 10px;">

                Demikian penawaran harga kami, Apabila terdapat hal yang kurang jelas, Anda dapat menghubungi kami melalui nomor HP : {{$penawarans->no_hp}} atau ke No. Telephone (0274) 443 6047. Atas perhatian dan kerja samanya kami ucapkan terima kasih.

            </p><br><br><br>

            <p align="right" style="font-size: 12px;">

                Hormat Kami,

            </p><br>

            <p align="right">

                <img src="assets/img/ttd.webp" width="100px" height="=50px"><br>

                <b>Yandi Okta Wirawan</b><br>

                <b>PT. Laksa Medika Internusa</b>


            <footer >

                <b >Jl. Amarta No.50 RT01, RW.01, Pelem, Baturetno, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198</b> <br>

                <b >Telepon : 0274 443 6047 Email : laksamedikainternusa@gmail.com</b>

            </footer>

    </div>
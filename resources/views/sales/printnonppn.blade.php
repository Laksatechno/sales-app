<!DOCTYPE html>
<html>
<head>
    <title>INV- {{ $sale->invoice_number }}</title>
    <style>
        body { 
            font-family:'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif;
            color:#333;
            text-align:left;
            font-size:12px;
            margin:0
        }
        .page-break { 
            page-break-after: always; 
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
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        table th, table td { 
            border: 1px solid #131313; 
            padding: 5px; 
            text-align: left; 
        }
        h1, h2, h3 { 
            margin: 0 0 10px; 
        }
        p {
             margin: 5px 0; 
            }
            .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        .details p {
            margin: 8px 0;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>

    <!-- Invoice Section -->
    <div class="container">
        <table>
            <caption style="caption-side: top; text-align: center; border: none;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr style="border: none;">
                        <td style="text-align: left; border: none; padding: none;">
                            {{-- <img align="left" src="{{ public_path('assets/img/logo_ptlmi.webp') }}" width="150px" height="30px">
                            <img align="left" src="{{ public_path('assets/img/logo_ptlmi.webp') }}" width="150px" height="30px"> --}}

                        </td>
                        <td style="text-align: right; border: none; font-weight: bold;">FAKTUR</td>
                    </tr>
                </table>
            </caption>
            <thead>
                 <tr>
                    <td colspan="2" align="left">
                        <!--<h4>Dari</h4>-->
                        <p>PT Laksa Medika Internusa<br>
                           Pelem Lor No.50 Bantul Yogyakarta<br>
                        </p>
                    </td>
                    <td colspan="3">
                        <!--<h4>Untuk : </h4>-->
                        <p>{{ $customer->name ?? $sale->users->name }}<br>
                        {{ $customer->address ?? $sale->users->address }}<br>
                        {{ $customer->phone ?? $sale->users->no_hp }} <br>
                        {{ $customer->email ?? $sale->users->email }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <th colspan="1" align="left">Invoice <strong>#00{{ $sale->invoice_number}}</strong></th>
                    <th colspan="2" align="center">Jatuh Tempo : {{$sale->due_date}}</th>
                    <th colspan="1" align="right"> Tanggal : {{ $sale->created_at->format('d-m-Y') }}</th>
                    <th colspan="1">Marketing#{{$sale->user->name}}</th>
                </tr>
            </thead>
                    </table><br>
        <table>
            <tbody>
                <tr>
                    <th align ="center">Product</th>
                    <th align ="center">Price</th>
                    <th align ="center">Qty</th>
                    <th align ="center">Diskon</th>
                    <th align ="center">Subtotal</th>
                </tr>
                @foreach ($details as $detail )
                    <tr>
                        <td align="center" scope="row">{{ $detail->product->name }}</td>
                        <td align="center">Rp {{ number_format($detail->price, 2) }}</td>
                        <td align="center">{{ $detail->quantity }}</td>
                        <td align="center">Rp {{ number_format($detail->diskon, 2) }}</td>
                        <td align="center">Rp {{ number_format($detail->total, 2) }}</td>
                    </tr>
                @endforeach
                @php
                $productCount = count($details);
                $emptyRows = max(5 - $productCount, 0);
                @endphp
            
                @for ($i = 0; $i < $emptyRows; $i++)
                <tr>
                    <td style="height: {{ 8 / (5 + $productCount) }}%;"></td>
                    <td style="height: {{ 8 / (5 + $productCount) }}%;"></td>
                    <td style="height: {{ 8 / (5 + $productCount) }}%;"></td>
                    <td style="height: {{ 8 / (5 + $productCount) }}%;"></td>
                    <td style="height: {{ 8 / (5 + $productCount) }}%;"></td>
                </tr>
                @endfor
                
                <tr>
                    <th colspan="4" align="left">Subtotal</th>
                    <td align ="right">Rp{{ number_format($sale->total, 2)}} </td>
                </tr>
                <tr>
                    <th>Tax</th>
                    <td></td>
                    <td colspan="2">12%</td>
                    <td align ="right">Rp {{ number_format($sale->tax, 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" align="left">Total Price</th>
                    <td align ="right">Rp {{ number_format($sale->total + $sale->tax)}}</td>
                </tr>
                <tr>
                    <th colspan="2" align="center">Penerima</th>
                    <th colspan="3" align="center">Hormat Kami</th>
            </tr>
             <tr>
                    <td colspan="2" align="center"><br>(....................)</td>
                    <td colspan="3" align="center"><br>(Fatmawaty Aripin)</td>
            </tr>
            </tfoot>
        </table>
        <p align="right">
            Rekening BCA 037-479-6000<br>
            a.n PT LAKSA MEDIKA INTERNUSA
        </p>
    </div>


    <div class="page-break"></div>
        <Table>
            <thead>
                <tr>
                    <th colspan="5">

                    {{-- <img align="left" src="{{ public_path('assets/images/logo_ptlmi.webp')}}" width="185px" height="30px"><br> --}}
                    <p align="right">
                        Pelem Lor No. 50 Baturetno, Banguntapan<br>
                        Bantul, DI. Yogyakarta. Telp/Fax +622742842046
                    </p>
                    <H1 align="center">TANDA TERIMA FAKTUR</H1><br>
                    <h3 align="left">Nama Outlet : {{ $customer->name ?? $sale->users->name }}</h3>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th align ="center">No</th>
                    <th align ="center">No Faktur</th>
                    <th align ="center">Tanggal Faktur</th>
                    <th align ="center">Tanggal Jatuh Tempo</th>
                    <th align ="center">Total Tagihan</th>
                </tr>
                <?php $no=1;?>
                <tr>
                    <td align="center" scope="row">{{ $no }}</td>
                    <td align="center">{{ $sale->invoice_number }}</td>
                    <td align ="center">{{ $sale->created_at->format('d-m-Y') }}</td>
                    <td align ="center">{{ $sale->due_date }}</td>
                    <td align ="right">Rp {{ number_format($sale->total + $sale->tax - $sale->diskon) }}</td>
                </tr>
                <?php $no++ ;?>
                <tr>
                    <th colspan="4">Total Tagihan</th>
                    <td align ="right">Rp {{ number_format($sale->total + $sale->tax - $sale->diskon) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" align="center">Penerima</th>
                    <td colspan="3" align="center">Yogyakarta, </td>
            </tr>
             <tr>
                    <td colspan="2" align="center"><br><br>(....................)</td>
                    <td colspan="3" align="center"><br><br>(Fatmawaty Aripin)</td>
            </tr>
            </tfoot>
        </Table>
    <div class="page-break"></div>

    <!-- Kwitansi Section -->
    <table>
        <tr>
            <h1 align="center">KWITANSI</h1>
            <p align="left" style="font-size: 14px; margin:5px">No. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;<b>{{ $sale->invoice_number }}</b></p><hr align="right" width="70%" style="margin-left: 21%">
            <p align="left" style="font-size: 14px; margin:5px">Telah Terima Dari &nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;<b>{{ $customer->name ?? $sale->users->name }}</b></p><hr align="right" width="70%" style="margin-left: 21%">
            <p align="left" style="font-size: 14px; margin:5px;">Uang Sejumlah &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;<b>
                {{ terbilang($sale->total + $sale->tax - $sale->diskon) }}
                </b></p><hr align="right"  width="70%" style="margin-left:21%">
                <p align="left" style="font-size: 14px; margin:5px">Guna Membayar &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;Pembayaran Faktur No. {{ $sale->invoice_number }}&nbsp;&nbsp;&nbsp;Tanggal Faktur {{ \Carbon\Carbon::parse($sale->created_at)->locale('id_ID')->isoFormat('dddd, D MMM YYYY')  }}</p><hr align="right"  width="70%" style="margin-left:21%">
                <p align="left" style="font-size: 14px; margin:5px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp; 
                    @if ($sale->tenggat)
                    Tanggal Jatuh Tempo{{ \Carbon\Carbon::parse($invoice->due_date)->locale('id_ID')->isoFormat('dddd, D MMM YYYY') }}
                    @else
                        Status COD
                    @endif</p><hr align="right"  width="70%" style="margin-left:21%">
                <p style="font-size: 14px; margin:5px;">Terbilang &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;
                    <span><b>Rp {{ number_format(floor($sale->total)) }}</b></span>
                </p>
                <p align="right" style="font-size: 14px; margin:5px; height:5%">
                    Yogyakarta,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>PT. Laksa Medika Internusa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <!--{{ $sale->created_at->format('D, d M Y') }}-->
                </p><br><br><br><br><br>
                <p align="right" style="font-size: 14px; margin:5px;">
                    (Fatmawaty Aripin)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </p>
        </tr>
    </table>
    <div class="page-break"></div>

    <!-- Surat Jalan Section -->
    <table>
        <thead>
        <tr>
        <th colspan="3">
            <h1 align="center">SURAT JALAN BARANG</h1>
            <p align="left" style="font-size: 12px;">INVOICE #{{ $sale->invoice_number }}</p>
            <p align="left" style="font-size: 12px;">Telah Terima dari PT. Laksa Medika Internusa &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ \Carbon\Carbon::parse($sale->created_at)->locale('id_ID')->isoFormat('dddd, D MMM YYYY')  }}</h3> 
        </th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <th align ="center">No</th>
                <th align ="center">Nama Barang</th>
                <th align ="center">Jumlah</th>
            </tr>
            @foreach ($details as $detail)
            @php
                $no++
            @endphp
            <tr>
                <td align="center" scope="row">{{ $no }}</td>
                <td>{{ $detail->product->name }}</td>
                 <td align ="center">{{ $detail->quantity }}</td>
            </tr>
            @endforeach
            <tr>
            <td colspan="3">
                <p align="left" style="font-size: 12px;"><b>Untuk&nbsp;&nbsp;&nbsp;: </b>{{ $customer->name ?? $sale->users->name }} </h3> 
                <p align="left" style="font-size: 12px;"><b>Alamat : </b>{{ $customer->address ?? $sale->users->address }} </h3> 
            </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1"align="center">Penerima</td>
                <td colspan="2"align="center">PT.Laksa Medika Internusa<br>Admin Logistik</td>
                {{-- {{ date('d F Y') }} --}}
        </tr>
         <tr>
                <td colspan="1" align="center"><br><br>(....................)</td>
                <td colspan="2" align="center"><br><br>(Puspita Tara Wahyuningsih)</td>
        </tr>
        </tfoot>
        
    </table>
    <div class="page-break"></div>

    <!-- Surat Keluar Barang Gudang  Section -->
    <table>
        <thead>
        <tr>
        <th colspan="3">
            <h1 align="center">SURAT KELUAR BARANG GUDANG</h1>
            <p align="left" style="font-size: 12px;">INVOICE # {{ $sale->invoice_number }}</p>
            <p align="right" style="font-size: 12px;">{{ \Carbon\Carbon::parse($sale->created_at)->locale('id_ID')->isoFormat('dddd, D MMM YYYY')  }}</h3> 
        </th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <th align ="center">No</th>
                <th align ="center">Nama Barang</th>
                <th align ="center">Jumlah</th>
            </tr>
            @foreach ($details as $e => $row)
            <tr>
                <td align="center" scope="row">{{ $e+1 }}</td>
                <td>{{ $row->product->name }}</td>
                 <td align ="center">{{ $row->quantity }}</td>
            </tr>
            @endforeach
            <tr>
            <td colspan="3">
                <p align="left" style="font-size: 12px;"><b>Untuk&nbsp;&nbsp;&nbsp;: </b>{{ $customer->name ?? $sale->users->name }} </h3> 
                <p align="left" style="font-size: 12px;"><b>Alamat : </b>{{ $customer->address ?? $sale->users->address }} </h3> 
            </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="1"align="center">Penerima</td>
                <td colspan="2"align="center">Yang Menyerahkan, </td>
                {{-- {{ date('d F Y') }} --}}
        </tr>
         <tr>
                <td colspan="1" align="center"><br><br>( Drajad Dwi Haryoko )</td>
                <td colspan="2" align="center"><br><br>(Puspita Tara Wahyuningsih)</td>
        </tr>
        </tfoot>
        
    </table>
</body>
</html>

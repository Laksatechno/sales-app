@extends('layouts.app')
@section ('header')
@include ('layouts.appheader')
@endsection
@section('content')
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <!-- Balance -->
            <div class="balance">
                <div class="left">
                    <h1 class="total"> {{ auth()->user()->name }}</h1>
                </div>
            </div>
            <!-- * Balance -->
            
            <!-- Wallet Footer -->
            <div class="wallet-footer">
                @if (auth()->user()->role == 'superadmin')
                <div class="item">
                    <a href="{{ route('sales.create') }}">
                        <div class="icon-wrapper bg-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c7.2 0 9 1.8 9 9s-1.8 9-9 9s-9-1.8-9-9s1.8-9 9-9m3 9H9m3-3v6"/></svg>
                          
                        </div>
                        <strong>Order</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('products.index') }}">
                        <div class="icon-wrapper bg-purle">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="m6 10l-2 1l8 4l8-4l-2-1M4 15l8 4l8-4M12 4v7"/><path d="m15 8l-3 3l-3-3"/></g></svg>
                        </div>
                        <strong>Barang</strong>
                    </a>
                </div>

                <div class="item">
                    <a href="{{ route('customers.index') }}">
                        <div class="icon-wrapper bg-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 7v1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7m0 1a3 3 0 0 0 6 0V7H3l2-4h14l2 4M5 21V10.85M19 21V10.85M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4"/></svg>
                        </div>
                        <strong>Customer</strong>
                    </a>
                </div>

                {{-- <div class="item">
                    <a href="{{ route('reports.index') }}">
                        <div class="icon-wrapper bg-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17 2a3 3 0 0 1 3 3v16a1 1 0 0 1-1.555.832l-2.318-1.545l-1.42 1.42a1 1 0 0 1-1.32.083l-.094-.083L12 20.415l-1.293 1.292a1 1 0 0 1-1.32.083l-.094-.083l-1.421-1.42l-2.317 1.545l-.019.012l-.054.03l-.028.017l-.054.023l-.05.023l-.049.015l-.06.019l-.052.009l-.057.011l-.084.006l-.026.003H5l-.049-.003h-.039l-.013-.003h-.016l-.041-.008l-.038-.005l-.015-.005l-.018-.002l-.034-.011l-.04-.01l-.019-.007l-.015-.004l-.029-.013l-.04-.015l-.021-.011l-.013-.005l-.028-.016l-.036-.018l-.014-.01l-.018-.01l-.038-.027l-.022-.014l-.01-.009l-.02-.014l-.045-.041l-.012-.008l-.024-.024l-.035-.039l-.02-.02l-.007-.011l-.011-.012l-.032-.045l-.02-.025l-.012-.019l-.03-.054l-.017-.028l-.023-.054l-.023-.05a1 1 0 0 1-.034-.108l-.01-.057l-.01-.053L4 21V5a3 3 0 0 1 3-3zm-2 12h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2m0-4H9a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2m0-4H9a1 1 0 1 0 0 2h6a1 1 0 0 0 0-2"/></svg>

                        </div>
                        <strong>Laporan</strong>
                    </a>
                </div> --}}

                <div class="item">
                    <a href="{{ route('sales.index') }}">
                        <div class="icon-wrapper bg-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17 2a3 3 0 0 1 3 3v16a1 1 0 0 1-1.555.832l-2.318-1.545l-1.42 1.42a1 1 0 0 1-1.32.083l-.094-.083L12 20.415l-1.293 1.292a1 1 0 0 1-1.32.083l-.094-.083l-1.421-1.42l-2.317 1.545l-.019.012l-.054.03l-.028.017l-.054.023l-.05.023l-.049.015l-.06.019l-.052.009l-.057.011l-.084.006l-.026.003H5l-.049-.003h-.039l-.013-.003h-.016l-.041-.008l-.038-.005l-.015-.005l-.018-.002l-.034-.011l-.04-.01l-.019-.007l-.015-.004l-.029-.013l-.04-.015l-.021-.011l-.013-.005l-.028-.016l-.036-.018l-.014-.01l-.018-.01l-.038-.027l-.022-.014l-.01-.009l-.02-.014l-.045-.041l-.012-.008l-.024-.024l-.035-.039l-.02-.02l-.007-.011l-.011-.012l-.032-.045l-.02-.025l-.012-.019l-.03-.054l-.017-.028l-.023-.054l-.023-.05a1 1 0 0 1-.034-.108l-.01-.057l-.01-.053L4 21V5a3 3 0 0 1 3-3zm-2 12h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2m0-4H9a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2m0-4H9a1 1 0 1 0 0 2h6a1 1 0 0 0 0-2"/></svg>

                        </div>
                        <strong>Tagihan</strong>
                    </a>
                </div>
                @else 
                <div class="item">
                    <a href="{{ route('shop.index') }}">
                            <div class="icon-wrapper bg-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c7.2 0 9 1.8 9 9s-1.8 9-9 9s-9-1.8-9-9s1.8-9 9-9m3 9H9m3-3v6"/></svg>
                              
                            </div>
                            <strong>Order</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('shop.riwayat') }}">
                        <div class="icon-wrapper bg-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17 2a3 3 0 0 1 3 3v16a1 1 0 0 1-1.555.832l-2.318-1.545l-1.42 1.42a1 1 0 0 1-1.32.083l-.094-.083L12 20.415l-1.293 1.292a1 1 0 0 1-1.32.083l-.094-.083l-1.421-1.42l-2.317 1.545l-.019.012l-.054.03l-.028.017l-.054.023l-.05.023l-.049.015l-.06.019l-.052.009l-.057.011l-.084.006l-.026.003H5l-.049-.003h-.039l-.013-.003h-.016l-.041-.008l-.038-.005l-.015-.005l-.018-.002l-.034-.011l-.04-.01l-.019-.007l-.015-.004l-.029-.013l-.04-.015l-.021-.011l-.013-.005l-.028-.016l-.036-.018l-.014-.01l-.018-.01l-.038-.027l-.022-.014l-.01-.009l-.02-.014l-.045-.041l-.012-.008l-.024-.024l-.035-.039l-.02-.02l-.007-.011l-.011-.012l-.032-.045l-.02-.025l-.012-.019l-.03-.054l-.017-.028l-.023-.054l-.023-.05a1 1 0 0 1-.034-.108l-.01-.057l-.01-.053L4 21V5a3 3 0 0 1 3-3zm-2 12h-2a1 1 0 0 0 0 2h2a1 1 0 0 0 0-2m0-4H9a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2m0-4H9a1 1 0 1 0 0 2h6a1 1 0 0 0 0-2"/></svg>

                        </div>
                        <strong>Tagihan</strong>
                    </a>
                </div>
                @endif  
            </div>
            <div class="wallet-footer">
                @if (auth()->user()->role == 'superadmin')
                <div class="item">
                    <a href="{{ route('shipments.index') }}">
                        <div class="icon-wrapper bg-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 32 32"><path fill="currentColor" d="M0 6v2h19v15h-6.156c-.446-1.719-1.992-3-3.844-3s-3.398 1.281-3.844 3H4v-5H2v7h3.156c.446 1.719 1.992 3 3.844 3s3.398-1.281 3.844-3h8.312c.446 1.719 1.992 3 3.844 3s3.398-1.281 3.844-3H32v-8.156l-.063-.157l-2-6L29.72 10H21V6zm1 4v2h9v-2zm20 2h7.281L30 17.125V23h-1.156c-.446-1.719-1.992-3-3.844-3s-3.398 1.281-3.844 3H21zM2 14v2h6v-2zm7 8c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2m16 0c1.117 0 2 .883 2 2s-.883 2-2 2s-2-.883-2-2s.883-2 2-2"/></svg>
                        </div>
                        <strong>Pengiriman</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{ route('penawaran.index') }}">
                        <div class="icon-wrapper bg-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4.75 4a.75.75 0 0 0-1.5 0v14.5a2.25 2.25 0 0 0 2.25 2.25H20a.75.75 0 0 0 0-1.5H5.5a.75.75 0 0 1-.75-.75v-8.109l3.92 3.92a.75.75 0 0 0 1.06 0l3.422-3.423l3.78 3.78h-1.72a.75.75 0 0 0 0 1.5h3.535a.75.75 0 0 0 .75-.75v-3.535a.75.75 0 0 0-1.5 0v1.729l-4.314-4.315a.75.75 0 0 0-1.061 0L9.2 12.72L4.75 8.27z"/></svg>
                        </div>
                        <strong>Penawaran</strong>
                    </a>
                </div>
                {{-- brosur --}}
                <div class="item">
                    <a href="{{ route('brochures.index') }}">
                        <div class="icon-wrapper bg-black">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.2em" height="1.2em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 17V7c0-1.886 0-2.828-.586-3.414S16.386 3 14.5 3h-5c-1.886 0-2.828 0-3.414.586S5.5 5.114 5.5 7v10c0 1.886 0 2.828.586 3.414S7.614 21 9.5 21h5c1.886 0 2.828 0 3.414-.586S18.5 18.886 18.5 17m0-11h.5c1.414 0 2.121 0 2.56.44C22 6.878 22 7.585 22 9v7c0 1.414 0 2.121-.44 2.56c-.439.44-1.146.44-2.56.44h-.5M5.5 6H5c-1.414 0-2.121 0-2.56.44C2 6.878 2 7.585 2 9v7c0 1.414 0 2.121.44 2.56C2.878 19 3.585 19 5 19h.5m9-11h-5m5 4h-5m5 4h-5" color="currentColor"/></svg>
                        </div>
                        <strong>Brosur</strong>
                    </a>
                </div>
                @endif
            </div>
        </div>
            @if (auth()->user()->role == 'superadmin')
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box">
                        <div class="title" style="font-size: 12px;">Total Penjualan</div>
                        <div class="value text-success">Rp. {{number_format($totalsale)}}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box">
                        <div class="title">Total Faktur</div>
                        <div class="value text-danger">{{$jmlhfaktur}}</div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box">
                        <div class="title">Total Pengiriman</div>
                        <div class="value">{{$jumlahpengiriman}}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box">
                        <div class="title">Total Customer</div>
                        <div class="value">{{$jmlhcs}}</div>
                    </div>
                </div>
            </div>
            @endif

    </div>
    </div>


@endsection

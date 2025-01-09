@extends('layouts.app')
@section('header')
    @include('layouts.appheaderback')
@endsection
@section('content')
<div class="section mt-2">
    <div class="card">
        <div class="card-body">
            <h2>Create Shipment</h2>

            <form action="{{ route('shipments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="in_progress">In Progress</option>
                        <option value="delivered">Delivered</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="photo_proof" class="form-label">Photo Proof</label>
                    <input type="file" name="photo_proof" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>
@endsection

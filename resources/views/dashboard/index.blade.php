@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="mb-4 text-gray-100 card bg-primary">
            <div class="card-body">{{ $car }} Mobil</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="text-gray-900 small stretched-link" href="{{ route('car.index') }}">View Details</a>
                <div class="text-gray-900 small"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="mb-4 text-gray-100 card bg-warning">
            <div class="card-body">{{ $user }} Pelanggan</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="text-gray-900 small stretched-link" href="{{ route('user.index') }}">View Details</a>
                <div class="text-gray-900 small"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="mb-4 text-gray-100 card bg-success">
            <div class="card-body">{{ $transaction }} Transaksi</div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="text-gray-900 small stretched-link" href="{{ route('transaction.index') }}">View Details</a>
                <div class="text-gray-900 small"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>
@endsection
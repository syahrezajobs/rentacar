@extends('layouts.main')

@section('title', 'Detail Transaksi')
@section('content')
<div class="mt-3 justify-content-center">
    <div class="card">
        <div class="card-body">
            <p>Pelanggan : {{ $transaction->user->name }}</p>
            <p>Mobil : {{ $transaction->car->name }}</p>
            <p>Tanggal Pinjam : {{ $transaction->date_start }}</p>
            <p>Tanggal Kembali : {{ $transaction->date_end }}</p>
            <p>Status : {{ $transaction->status }}</p>
            <p>Total : {{ $transaction->total }}</p>
            <p>Note : {{ $transaction->note }}</p>
        </div>
    </div>
</div>
@endsection
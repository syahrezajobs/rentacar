@extends('layouts.main')

@section('title', 'Merek')


@section('content')
<div class="d-flex">
    <a href="{{ route('brand.create') }}" class="btn btn-primary">Tambah Merek</a>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">No</th>
            <th scope="col">Nama</th>
            <th scope="col">Total</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($brands as $brand)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $brand->name }}</td>
            <td>{{ count($brand->cars) }}</td>
            <td>
                <form class="d-inline" action="{{ route('brand.destroy', $brand->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger" onclick="return confirm('Hapus data mobil ini?')">Hapus</button>
                </form>
                <a href="{{ route('brand.edit', $brand->id) }}" class="btn btn-success">Edit</a>
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="7" class="text-center">
                Belum ada data
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection
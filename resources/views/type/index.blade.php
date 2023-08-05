@extends('layouts.main')

@section('title', 'Tipe')

@section('content')
<div class="d-flex">
    <a href="{{ route('type.create') }}" class="btn btn-primary">Tambah Tipe</a>
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
        @forelse($types as $type)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $type->name }}</td>
            <td>{{ count($type->cars) }}</td>
            <td>
                <form class="d-inline" action="{{ route('type.destroy', $type->id) }}" method="POST">
                    @csrf
                    @method('delete')
                    <button class="btn btn-danger" onclick="return confirm('Hapus data mobil ini?')">Hapus</button>
                </form>
                <a href="{{ route('type.edit', $type->id) }}" class="btn btn-success">Edit</a>
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
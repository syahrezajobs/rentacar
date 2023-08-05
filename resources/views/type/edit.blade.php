@extends('layouts.main')

@section('title', 'Edit Tipe')

@section('content')
<form action="{{ route('type.update', $type->id) }}" method="POST" enctype="multipart/form-data">
    @csrf()
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $type->name) }}">
        @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
@endsection
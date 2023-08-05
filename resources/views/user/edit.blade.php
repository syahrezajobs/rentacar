@extends('layouts.main')

@section('title', 'Edit Pelanggan')

@section('content')
<form action="{{ route('user.update', $user->id) }}" method="POST">
    @csrf()
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
        @error('name')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email', $user->email) }}">
        @error('email')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3">{{ old('address', optional($user->detail)->address) }}</textarea>
        @error('address')
        <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Telepon</label>
        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" value="{{ old('phone', optional($user->detail)->phone) }}">
        @error('phone')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="sim" class="form-label">Nomor SIM</label>
        <input type="text" class="form-control @error('sim') is-invalid @enderror" name="sim" id="sim" value="{{ old('sim', optional($user->detail)->sim) }}">
        @error('sim')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
        @enderror
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
@endsection
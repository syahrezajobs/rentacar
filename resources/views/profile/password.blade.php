@extends('layouts.main')

@section('title', 'Ubah Password')

@section('content')
@if(session()->has('error'))
<div class="alert alert-danger" role="alert">
    {{ session('error') }}
</div>
@endif
<form action="{{ route('change-password.change') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="old_password" class="form-label">Password Lama</label>
        <input type="password" required class="form-control @error('old_password') is-invalid @enderror" name="old_password" id="old_password">
        @error('old_password')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password Baru</label>
        <input type="password" required class="form-control @error('password') is-invalid @enderror" name="password" id="password">
        @error('password')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Password Confirmation</label>
        <input type="password" required class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation">
        @error('password_confirmation')
        <span class="invalid-feedback">
            {{ $message }}
        </span>
        @enderror
    </div>
    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
@endsection
@extends('layouts.app')
@section('title', 'Tambah Kelas')
@section('content')
    <div class="container mt-5">
        <h1>Tambah Kelas</h1>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form action="{{ route('kelas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_kelas" class="form-label">ID Kelas</label>
                <input type="number" name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" value="{{ old('id_kelas') }}" required>
                @error('id_kelas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="nama_kelas" class="form-label">Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas') }}" required>
                @error('nama_kelas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
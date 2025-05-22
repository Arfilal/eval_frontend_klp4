@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
    <div class="container mt-5">
        <h1>Edit Kelas</h1>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (isset($kelas) && is_array($kelas) && !empty($kelas) && isset($kelas['id_kelas']))
            <form action="{{ route('kelas.update', $kelas['id_kelas']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="id_kelas" class="form-label">ID Kelas</label>
                    <input type="text" name="id_kelas" class="form-control" value="{{ $kelas['id_kelas'] }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="nama_kelas" class="form-label">Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control" value="{{ $kelas['nama_kelas'] ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        @else
            <div class="alert alert-warning">Data kelas tidak ditemukan.</div>
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
        @endif
    </div>
@endsection
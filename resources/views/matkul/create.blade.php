@extends('layouts.app')
@section('title', 'Tambah Mata Kuliah')
@section('content')
    <h1>Tambah Mata Kuliah</h1>
    <form action="{{ route('matkul.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kode_matkul" class="form-label">Kode Matkul</label>
            <input type="text" name="kode_matkul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nama_matkul" class="form-label">Nama Matkul</label>
            <input type="text" name="nama_matkul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="sks" class="form-label">SKS</label>
            <input type="text" name="sks" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="semester" class="form-label">Semester</label>
            <input type="text" name="semester" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('matkul.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection

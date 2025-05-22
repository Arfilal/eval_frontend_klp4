@extends('layouts.app')
@section('title', 'Edit Mata Kuliah')
@section('content')
    <h1>Edit Mata Kuliah</h1>
    <form action="{{ route('matkul.update', $matkul['kode_matkul']) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="kode_matkul" class="form-label">Kode Matkul</label>
            <input type="text" name="kode_matkul" class="form-control" value="{{ $matkul['kode_matkul'] }}" readonly>
        </div>
        <div class="mb-3">
            <label for="nama_matkul" class="form-label">Nama Matkul</label>
            <input type="text" name="nama_matkul" class="form-control" value="{{ $matkul['nama_matkul'] }}" required>
        </div>
        <div class="mb-3">
            <label for="sks" class="form-label">SKS</label>
            <input type="text" name="sks" class="form-control" value="{{ $matkul['sks'] }}" required>
        </div>
        <div class="mb-3">
            <label for="semester" class="form-label">Semester</label>
            <input type="text" name="semester" class="form-control" value="{{ $matkul['semester'] }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('matkul.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
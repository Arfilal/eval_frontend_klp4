@extends('layouts.app')
@section('title', 'Tambah Mahasiswa')
@section('content')
    <h1>Tambah Mahasiswa</h1>
    <form action="{{ route('mahasiswa.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="npm" class="form-label">NPM</label>
            <input type="text" name="npm" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="nama_mahasiswa" class="form-label">Nama Mahasiswa</label>
            <input type="text" name="nama_mahasiswa" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="id_kelas" class="form-label">Kelas</label>
            <select name="id_kelas" class="form-control" required>
                <option value="">Pilih Kelas</option>
                @foreach ($kelas as $k)
                    <option value="{{ $k['id_kelas'] }}">{{ $k['nama_kelas'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="kode_prodi" class="form-label">Kode Prodi</label>
            <input type="text" name="kode_prodi" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
@endsection
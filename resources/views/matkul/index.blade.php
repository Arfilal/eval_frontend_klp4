@extends('layouts.app')
@section('title', 'Daftar Mata Kuliah')
@section('content')
    <h1>Daftar Mata Kuliah</h1>
    <a href="{{ route('matkul.create') }}" class="btn btn-primary mb-3">Tambah Mata Kuliah</a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Matkul</th>
                <th>Nama Matkul</th>
                <th>SKS</th>
                <th>Semester</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matkul as $m)
                <tr>
                    <td>{{ $m['kode_matkul'] }}</td>
                    <td>{{ $m['nama_matkul'] }}</td>
                    <td>{{ $m['sks'] }}</td>
                    <td>{{ $m['semester'] }}</td>
                    <td>
                        <a href="{{ route('matkul.show', $m['kode_matkul']) }}" class="btn btn-info btn-sm">Lihat</a>
                        <a href="{{ route('matkul.edit', $m['kode_matkul']) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('matkul.destroy', $m['kode_matkul']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
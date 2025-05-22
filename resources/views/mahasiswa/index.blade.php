@extends('layouts.app')
@section('title', 'Daftar Mahasiswa')
@section('content')
    <h1>Daftar Mahasiswa</h1>
    <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary mb-3">Tambah Mahasiswa</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>NPM</th>
                <th>Nama Mahasiswa</th>
                <th>ID Kelas</th>
                <th>Kode Prodi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mahasiswa as $m)
                <tr>
                    <td>{{ $m['npm'] }}</td>
                    <td>{{ $m['nama_mahasiswa'] }}</td>
                    <td>{{ $m['id_kelas'] }}</td>
                    <td>{{ $m['kode_prodi'] }}</td>
                    <td>
                        <a href="{{ route('mahasiswa.show', $m['npm']) }}" class="btn btn-info btn-sm">Lihat</a>
                        <a href="{{ route('mahasiswa.edit', $m['npm']) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('mahasiswa.destroy', $m['npm']) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus mahasiswa ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data mahasiswa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
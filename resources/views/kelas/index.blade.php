@extends('layouts.app')
@section('title', 'Daftar Kelas')
@section('content')
    <div class="container mt-5">
        <h1>Daftar Kelas</h1>
        <a href="{{ route('kelas.create') }}" class="btn btn-primary mb-3">Tambah Kelas</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Kelas</th>
                    <th>Nama Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelas as $k)
                    <tr>
                        <td>{{ $k['id_kelas'] }}</td>
                        <td>{{ $k['nama_kelas'] }}</td>
                        <td>
                            <a href="{{ route('kelas.show', $k['id_kelas']) }}" class="btn btn-info btn-sm">Lihat</a>
                            <a href="{{ route('kelas.edit', $k['id_kelas']) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('kelas.destroy', $k['id_kelas']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
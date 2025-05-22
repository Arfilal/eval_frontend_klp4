<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://localhost:8080/']);
    }

    // Menampilkan daftar kelas
    public function index()
    {
        try {
            $response = $this->client->get('kelas');
            $kelas = json_decode($response->getBody()->getContents(), true);
            return view('kelas.index', compact('kelas'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengambil data: ' . $e->getMessage());
        }
    }

    // Menampilkan form tambah kelas
    public function create()
    {
        return view('kelas.create');
    }

    // Menyimpan data kelas baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_kelas' => 'required|integer|unique:kelas,id_kelas',
            'nama_kelas' => 'required|string|max:10',
        ]);

        try {
            $response = $this->client->post('kelas', [
                'form_params' => $data
            ]);

            if ($response->getStatusCode() == 201) {
                return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan kelas.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
        }
    }

    // Menampilkan detail kelas untuk edit
   public function show($id_kelas)
{
    try {
        $response = $this->client->get("kelas/{$id_kelas}");
        $body = $response->getBody()->getContents();
        $kelas = json_decode($body, true);

        // Log untuk debugging
        \Log::info('Respons dari backend untuk id_kelas ' . $id_kelas . ':', ['body' => $body, 'decoded' => $kelas]);

        // Jika respons adalah array dengan satu elemen, ambil elemen pertama
        if (is_array($kelas) && isset($kelas[0])) {
            $kelas = $kelas[0];
        }

        // Pastikan $kelas adalah array dan memiliki kunci yang diperlukan
        if (!is_array($kelas) || !isset($kelas['id_kelas']) || !isset($kelas['nama_kelas'])) {
            \Log::warning('Data kelas tidak valid atau kosong untuk id_kelas: ' . $id_kelas);
            return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan.');
        }

        return view('kelas.edit', compact('kelas'));
    } catch (\Exception $e) {
        \Log::error('Gagal mengambil data kelas: ' . $e->getMessage());
        return redirect()->route('kelas.index')->with('error', 'Gagal mengambil data: ' . $e->getMessage());
    }
}

    // Menampilkan form edit kelas
    public function edit($id_kelas)
    {
        return $this->show($id_kelas); // Reuse show method for edit
    }

    // Memperbarui data kelas
    public function update(Request $request, $id_kelas)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string|max:10',
        ]);

        try {
            $response = $this->client->put("kelas/{$id_kelas}", [
                'form_params' => $data
            ]);

            if ($response->getStatusCode() == 200) {
                return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
            } else {
                return redirect()->back()->with('error', 'Gagal memperbarui kelas.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
        }
    }

    // Menghapus kelas
    public function destroy($id_kelas)
    {
        try {
            $response = $this->client->delete("kelas/{$id_kelas}");

            if ($response->getStatusCode() == 200) {
                return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
            } else {
                return redirect()->back()->with('error', 'Gagal menghapus kelas.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }
}
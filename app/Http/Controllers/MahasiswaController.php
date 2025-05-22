<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MahasiswaController extends Controller
{
    protected $client;
    protected $baseUrl = 'http://localhost:8080';

    public function __construct()
    {
        $this->client = new Client(['base_uri' => $this->baseUrl]);
    }

    public function index()
    {
        try {
            $response = $this->client->get('/mahasiswa');
            if ($response->getStatusCode() == 200) {
                $mahasiswa = json_decode($response->getBody(), true);
                return view('mahasiswa.index', compact('mahasiswa'));
            } else {
                return redirect()->back()->with('error', 'Gagal mengambil data mahasiswa: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->back()->with('error', 'Gagal mengambil data mahasiswa: ' . $errorMessage);
        }
    }

    public function create()
    {
        try {
            $response = $this->client->get('/kelas');
            if ($response->getStatusCode() == 200) {
                $kelas = json_decode($response->getBody(), true);
                if (empty($kelas)) {
                    return redirect()->route('mahasiswa.index')->with('error', 'Tidak ada data kelas tersedia. Tambahkan kelas terlebih dahulu.');
                }
                return view('mahasiswa.create', compact('kelas'));
            } else {
                return redirect()->route('mahasiswa.index')->with('error', 'Gagal mengambil data kelas: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->route('mahasiswa.index')->with('error', 'Gagal mengambil data kelas: ' . $errorMessage);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'npm' => 'required|string|max:30|unique:mahasiswa,npm', // Asumsi validasi unik di frontend
            'nama_mahasiswa' => 'required|string|max:50',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas', // Pastikan id_kelas ada
            'kode_prodi' => 'required|string|max:8',
        ]);

        try {
            $response = $this->client->post('/mahasiswa', [
                'form_params' => $data
            ]);
            if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
                return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil ditambahkan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan mahasiswa: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->back()->with('error', 'Gagal menambahkan mahasiswa: ' . $errorMessage);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->client->get("/mahasiswa/{$id}");
            if ($response->getStatusCode() == 200) {
                $mahasiswa = json_decode($response->getBody(), true);
                return view('mahasiswa.show', compact('mahasiswa'));
            } else {
                return redirect()->route('mahasiswa.index')->with('error', 'Gagal mengambil data mahasiswa: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->route('mahasiswa.index')->with('error', 'Gagal mengambil data mahasiswa: ' . $errorMessage);
        }
    }

    public function edit($id)
    {
        try {
            $responseMahasiswa = $this->client->get("/mahasiswa/{$id}");
            if ($responseMahasiswa->getStatusCode() != 200) {
                return redirect()->route('mahasiswa.index')->with('error', 'Gagal mengambil data mahasiswa: Status ' . $responseMahasiswa->getStatusCode());
            }
            $mahasiswa = json_decode($responseMahasiswa->getBody(), true);

            $responseKelas = $this->client->get('/kelas');
            if ($responseKelas->getStatusCode() != 200) {
                return redirect()->route('mahasiswa.index')->with('error', 'Gagal mengambil data kelas: Status ' . $responseKelas->getStatusCode());
            }
            $kelas = json_decode($responseKelas->getBody(), true);
            if (empty($kelas)) {
                return redirect()->route('mahasiswa.index')->with('error', 'Tidak ada data kelas tersedia. Tambahkan kelas terlebih dahulu.');
            }

            return view('mahasiswa.edit', compact('mahasiswa', 'kelas'));
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->route('mahasiswa.index')->with('error', 'Gagal mengambil data: ' . $errorMessage);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_mahasiswa' => 'required|string|max:50',
            'id_kelas' => 'required|integer|exists:kelas,id_kelas', // Pastikan id_kelas ada
            'kode_prodi' => 'required|string|max:8',
        ]);

        try {
            $response = $this->client->put("/mahasiswa/{$id}", [
                'form_params' => $data
            ]);
            if ($response->getStatusCode() == 200) {
                return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil diperbarui.');
            } else {
                return redirect()->back()->with('error', 'Gagal memperbarui mahasiswa: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->back()->with('error', 'Gagal memperbarui mahasiswa: ' . $errorMessage);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->client->delete("/mahasiswa/{$id}");
            if ($response->getStatusCode() == 200) {
                return redirect()->route('mahasiswa.index')->with('success', 'Mahasiswa berhasil dihapus.');
            } else {
                return redirect()->route('mahasiswa.index')->with('error', 'Gagal menghapus mahasiswa: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->route('mahasiswa.index')->with('error', 'Gagal menghapus mahasiswa: ' . $errorMessage);
        }
    }
}
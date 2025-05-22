<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MatkulController extends Controller
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
            $response = $this->client->get('/matkul');
            if ($response->getStatusCode() == 200) {
                $matkul = json_decode($response->getBody(), true);
                return view('matkul.index', compact('matkul'));
            } else {
                return redirect()->back()->with('error', 'Gagal mengambil data matkul: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->back()->with('error', 'Gagal mengambil data matkul: ' . $errorMessage);
        }
    }

    public function create()
    {
        try {
            return view('matkul.create');
        } catch (\Exception $e) {
            return redirect()->route('matkul.index')->with('error', 'Gagal memuat halaman tambah matkul: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_matkul' => 'required|string|max:5|unique:matkul,kode_matkul', // Asumsi validasi unik di frontend
            'nama_matkul' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:6', // SKS biasanya 1-6
            'semester' => 'required|integer|min:1|max:8', // Semester biasanya 1-8
        ]);

        try {
            $response = $this->client->post('/matkul', [
                'form_params' => $data
            ]);
            if ($response->getStatusCode() == 201 || $response->getStatusCode() == 200) {
                return redirect()->route('matkul.index')->with('success', 'Matkul berhasil ditambahkan.');
            } else {
                return redirect()->back()->with('error', 'Gagal menambahkan matkul: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->back()->with('error', 'Gagal menambahkan matkul: ' . $errorMessage);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->client->get("/matkul/{$id}");
            if ($response->getStatusCode() == 200) {
                $matkul = json_decode($response->getBody(), true);
                return view('matkul.show', compact('matkul'));
            } else {
                return redirect()->route('matkul.index')->with('error', 'Gagal mengambil data matkul: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->route('matkul.index')->with('error', 'Gagal mengambil data matkul: ' . $errorMessage);
        }
    }

    public function edit($id)
    {
        try {
            $response = $this->client->get("/matkul/{$id}");
            if ($response->getStatusCode() == 200) {
                $matkul = json_decode($response->getBody(), true);
                return view('matkul.edit', compact('matkul'));
            } else {
                return redirect()->route('matkul.index')->with('error', 'Gagal mengambil data matkul: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->route('matkul.index')->with('error', 'Gagal mengambil data matkul: ' . $errorMessage);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_matkul' => 'required|string|max:50',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        try {
            $response = $this->client->put("/matkul/{$id}", [
                'form_params' => $data
            ]);
            if ($response->getStatusCode() == 200) {
                return redirect()->route('matkul.index')->with('success', 'Matkul berhasil diperbarui.');
            } else {
                return redirect()->back()->with('error', 'Gagal memperbarui matkul: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->back()->with('error', 'Gagal memperbarui matkul: ' . $errorMessage);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->client->delete("/matkul/{$id}");
            if ($response->getStatusCode() == 200) {
                return redirect()->route('matkul.index')->with('success', 'Matkul berhasil dihapus.');
            } else {
                return redirect()->route('matkul.index')->with('error', 'Gagal menghapus matkul: Status ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            $errorMessage = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)['message'] ?? $e->getMessage()
                : $e->getMessage();
            return redirect()->route('matkul.index')->with('error', 'Gagal menghapus matkul: ' . $errorMessage);
        }
    }
}
# Panduan Evaluasi dan Pembuatan Aplikasi Kelas

## 1. Deskripsi Proyek
Proyek ini terdiri dari dua bagian utama:
- **Backend**: Dibangun menggunakan CodeIgniter 4, bertanggung jawab untuk menyediakan API RESTful untuk mengelola data kelas (CRUD operations).
- **Frontend**: Dibangun menggunakan Laravel, bertanggung jawab untuk menampilkan antarmuka pengguna dan berinteraksi dengan API backend.

Aplikasi ini memungkinkan pengguna untuk:
- Melihat daftar kelas.
- Menambah kelas baru.
- Mengedit kelas yang ada.
- Menghapus kelas.

## 2. Prasyarat
Pastikan Anda memiliki perangkat lunak berikut terinstal sebelum memulai:
- PHP (versi 8.x atau lebih baru)
- Composer (untuk mengelola dependensi PHP)
- MySQL (misalnya via XAMPP)
- Git (untuk meng-clone repository)
- Node.js dan npm (opsional, untuk Vite di Laravel)
- Akses internet untuk mengunduh dependensi

## 3. Langkah-Langkah Setup dan Evaluasi

### 3.1. Clone Repository
1. **Clone Frontend Repository**:
   ```bash
   git clone https://github.com/Arfilal/eval_frontend_klp4.git
   cd eval_frontend_klp4
   ```
2. **Clone Backend Repository (Asumsi)**:
   Asumsi repository backend bernama `eval_backend_klp4`:
   ```bash
   git clone https://github.com/Arfilal/eval_backend_klp4.git
   cd eval_backend_klp4
   ```
   Jika repository ini tidak ada, ikuti langkah pembuatan backend di bawah.

### 3.2. Setup Backend (CodeIgniter)
1. **Navigasi ke Direktori Backend**:
   ```bash
   cd eval_backend_klp4
   ```
2. **Instal Dependensi**:
   ```bash
   composer install
   ```
3. **Konfigurasi Database**:
   - Pastikan MySQL berjalan (misalnya via XAMPP).
   - Buat database `db_krs`:
     ```sql
     CREATE DATABASE db_krs;
     USE db_krs;
     CREATE TABLE kelas (
         id_kelas INT PRIMARY KEY,
         nama_kelas VARCHAR(10) NOT NULL
     );
     INSERT INTO kelas (id_kelas, nama_kelas) VALUES (1, '1A'), (2, '2A'), (3, '2B');
     ```
   - Salin file `.env.example` ke `.env` dan sesuaikan:
     ```
     database.default.hostname = localhost
     database.default.database = db_krs
     database.default.username = root
     database.default.password = 
     database.default.DBDriver = MySQLi
     ```
4. **Jalankan Server Backend**:
   ```bash
   php spark serve --port=8080
   ```
5. **Uji API Backend**:
   - Gunakan Postman untuk menguji endpoint:
     - `GET http://localhost:8080/kelas`
     - `PUT http://localhost:8080/kelas/1` (body: `nama_kelas=1B`, format `x-www-form-urlencoded`).

### 3.3. Setup Frontend (Laravel)
1. **Navigasi ke Direktori Frontend**:
   ```bash
   cd eval_frontend_klp4
   ```
2. **Instal Dependensi**:
   ```bash
   composer install
   npm install
   ```
3. **Konfigurasi Environment**:
   - Salin file `.env.example` ke `.env` dan sesuaikan:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=db_krs
     DB_USERNAME=root
     DB_PASSWORD=
     ```
4. **Hapus Cache dan Build Asset**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   npm run dev
   ```
5. **Jalankan Server Frontend**:
   ```bash
   php artisan serve --port=8000
   ```

### 3.4. Uji Aplikasi
1. **Buka Halaman Daftar Kelas**:
   - Akses `http://localhost:8000/kelas`.
   - Pastikan daftar kelas muncul.
2. **Tambah Kelas**:
   - Klik "Tambah Kelas", isi ID Kelas: `4`, Nama Kelas: `3A`, lalu klik "Simpan".
   - Verifikasi di database:
     ```sql
     SELECT * FROM kelas WHERE id_kelas = 4;
     ```
3. **Edit Kelas**:
   - Klik "Edit" pada kelas dengan ID 1.
   - Ubah Nama Kelas menjadi `1B`, lalu klik "Update".
   - Verifikasi di database:
     ```sql
     SELECT * FROM kelas WHERE id_kelas = 1;
     ```
4. **Hapus Kelas**:
   - Klik "Hapus" dan konfirmasi.
   - Pastikan data terhapus dari database.

## 4. Langkah-Langkah Pembuatan Aplikasi (Rinci)

### 4.1. Pembuatan Backend (CodeIgniter)
1. **Inisialisasi Proyek**:
   ```bash
   composer create-project codeigniter4/appstarter eval_backend_klp4
   cd eval_backend_klp4
   ```
2. **Konfigurasi Database**:
   - Buat database dan tabel seperti di atas.
   - Sesuaikan `.env`.
3. **Buat Model**:
   - File: `app/Models/KelasModel.php`
     ```php
     <?php
     namespace App\Models;

     use CodeIgniter\Model;

     class KelasModel extends Model
     {
         protected $table = 'kelas';
         protected $primaryKey = 'id_kelas';
         protected $useAutoIncrement = false;
         protected $returnType = 'array';
         protected $allowedFields = ['id_kelas', 'nama_kelas'];

         protected $validationRules = [
             'id_kelas' => 'required|is_natural|is_unique[kelas.id_kelas]',
             'nama_kelas' => 'required|regex_match[/^[1-4][A-D]$/]'
         ];

         protected $validationMessages = [
             'id_kelas' => [
                 'required' => 'ID Kelas harus diisi!',
                 'is_natural' => 'ID Kelas harus berupa angka positif!',
                 'is_unique' => 'ID Kelas sudah ada, gunakan ID lain!'
             ],
             'nama_kelas' => [
                 'required' => 'Kelas harus diisi!',
                 'regex_match' => 'Format kelas harus antara 1A - 4D'
             ]
         ];
     }
     ```
4. **Buat Controller**:
   - File: `app/Controllers/Kelas.php`
     ```php
     <?php
     namespace App\Controllers;

     use CodeIgniter\RESTful\ResourceController;

     class Kelas extends ResourceController
     {
         protected $modelName = 'App\Models\KelasModel';
         protected $format = 'json';

         public function index()
         {
             return $this->respond($this->model->findAll());
         }

         public function show($id = null)
         {
             $data = $this->model->find($id);
             if ($data) {
                 return $this->respond($data);
             }
             return $this->failNotFound("Data tidak ditemukan untuk id_kelas: $id");
         }

         public function create()
         {
             $data = $this->request->getPost();
             if ($this->model->insert($data)) {
                 return $this->respondCreated(['message' => 'Kelas berhasil ditambahkan']);
             }
             return $this->failValidationErrors($this->model->errors());
         }

         public function update($id = null)
         {
             $data = $this->request->getPost();
             if (!$data || !isset($data['nama_kelas'])) {
                 return $this->failValidationError('Data nama_kelas tidak ditemukan.');
             }
             if (!preg_match('/^[1-4][A-D]$/', $data['nama_kelas'])) {
                 return $this->failValidationError('Format kelas harus antara 1A - 4D.');
             }
             $kelas = $this->model->find($id);
             if ($kelas) {
                 if ($this->model->update($id, $data)) {
                     return $this->respond(['message' => 'Kelas berhasil diperbarui']);
                 }
                 return $this->failServerError('Gagal memperbarui kelas.');
             }
             return $this->failNotFound("Data tidak ditemukan untuk id_kelas: $id");
         }

         public function delete($id = null)
         {
             $kelas = $this->model->find($id);
             if ($kelas) {
                 $this->model->delete($id);
                 return $this->respondDeleted(['message' => 'Kelas berhasil dihapus']);
             }
             return $this->failNotFound("Data tidak ditemukan untuk id_kelas: $id");
         }
     }
     ```
5. **Konfigurasi Routing**:
   - File: `app/Config/Routes.php`
     ```php
     $routes->resource('kelas', ['controller' => 'Kelas']);
     ```

### 4.2. Pembuatan Frontend (Laravel)
1. **Inisialisasi Proyek**:
   ```bash
   composer create-project laravel/laravel eval_frontend_klp4
   cd eval_frontend_klp4
   ```
2. **Instal Guzzle**:
   ```bash
   composer require guzzlehttp/guzzle
   ```
3. **Buat Controller**:
   - File: `app/Http/Controllers/KelasController.php`
     ```php
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

         public function create()
         {
             return view('kelas.create');
         }

         public function store(Request $request)
         {
             $data = $request->validate([
                 'id_kelas' => 'required|integer|unique:kelas,id_kelas',
                 'nama_kelas' => 'required|string|regex:/^[1-4][A-D]$/',
             ]);

             try {
                 $response = $this->client->post('kelas', [
                     'form_params' => $data
                 ]);
                 if ($response->getStatusCode() == 201) {
                     return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
                 }
                 return redirect()->back()->with('error', 'Gagal menambahkan kelas.');
             } catch (\Exception $e) {
                 return redirect()->back()->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
             }
         }

         public function show($id_kelas)
         {
             try {
                 $response = $this->client->get("kelas/{$id_kelas}");
                 $kelas = json_decode($response->getBody()->getContents(), true);
                 if (is_array($kelas) && isset($kelas[0])) {
                     $kelas = $kelas[0];
                 }
                 if (!is_array($kelas) || !isset($kelas['id_kelas'])) {
                     return redirect()->route('kelas.index')->with('error', 'Data kelas tidak ditemukan.');
                 }
                 return view('kelas.edit', compact('kelas'));
             } catch (\Exception $e) {
                 return redirect()->route('kelas.index')->with('error', 'Gagal mengambil data: ' . $e->getMessage());
             }
         }

         public function edit($id_kelas)
         {
             return $this->show($id_kelas);
         }

         public function update(Request $request, $id_kelas)
         {
             $data = $request->validate([
                 'nama_kelas' => 'required|string|regex:/^[1-4][A-D]$/',
             ]);

             try {
                 $response = $this->client->put("kelas/{$id_kelas}", [
                     'form_params' => $data
                 ]);
                 $responseBody = $response->getBody()->getContents();
                 if ($response->getStatusCode() == 200) {
                     return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
                 }
                 $errorMessage = json_decode($responseBody, true)['message'] ?? 'Gagal memperbarui kelas.';
                 return redirect()->route('kelas.index')->with('error', $errorMessage);
             } catch (\Exception $e) {
                 return redirect()->route('kelas.index')->with('error', 'Gagal memperbarui kelas: ' . $e->getMessage());
             }
         }

         public function destroy($id_kelas)
         {
             try {
                 $response = $this->client->delete("kelas/{$id_kelas}");
                 if ($response->getStatusCode() == 200) {
                     return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
                 }
                 return redirect()->back()->with('error', 'Gagal menghapus kelas.');
             } catch (\Exception $e) {
                 return redirect()->back()->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
             }
         }
     }
     ```
4. **Konfigurasi Routing**:
   - File: `routes/web.php`
     ```php
     use App\Http\Controllers\KelasController;

     Route::resource('kelas', KelasController::class);
     ```
5. **Buat Tampilan (Views)**:
   - Folder: `resources/views/kelas/`
   - File: `index.blade.php`
     ```html
     @extends('layouts.app')

     @section('title', 'Daftar Kelas')

     @section('content')
         <div class="container mt-5">
             <h1>Daftar Kelas</h1>

             @if (session('success'))
                 <div class="alert alert-success">{{ session('success') }}</div>
             @endif
             @if (session('error'))
                 <div class="alert alert-danger">{{ session('error') }}</div>
             @endif

             <a href="{{ route('kelas.create') }}" class="btn btn-primary mb-3">Tambah Kelas</a>

             <table class="table table-bordered">
                 <thead class="table-dark">
                     <tr>
                         <th>ID Kelas</th>
                         <th>Nama Kelas</th>
                         <th>Aksi</th>
                     </tr>
                 </thead>
                 <tbody>
                     @forelse ($kelas as $item)
                         <tr>
                             <td>{{ $item['id_kelas'] }}</td>
                             <td>{{ $item['nama_kelas'] }}</td>
                             <td>
                                 <a href="{{ route('kelas.show', $item['id_kelas']) }}" class="btn btn-info btn-sm">Lihat</a>
                                 <a href="{{ route('kelas.edit', $item['id_kelas']) }}" class="btn btn-warning btn-sm">Edit</a>
                                 <form action="{{ route('kelas.destroy', $item['id_kelas']) }}" method="POST" style="display:inline;">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">Hapus</button>
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
     ```
   - File: `create.blade.php`
     ```html
     @extends('layouts.app')

     @section('title', 'Tambah Kelas')

     @section('content')
         <div class="container mt-5">
             <h1>Tambah Kelas</h1>
             @if (session('error'))
                 <div class="alert alert-danger">{{ session('error') }}</div>
             @endif
             <form action="{{ route('kelas.store') }}" method="POST">
                 @csrf
                 <div class="mb-3">
                     <label for="id_kelas" class="form-label">ID Kelas</label>
                     <input type="number" name="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" value="{{ old('id_kelas') }}">
                     @error('id_kelas')
                         <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                 </div>
                 <div class="mb-3">
                     <label for="nama_kelas" class="form-label">Nama Kelas</label>
                     <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas') }}">
                     @error('nama_kelas')
                         <div class="invalid-feedback">{{ $message }}</div>
                     @enderror
                 </div>
                 <button type="submit" class="btn btn-primary">Simpan</button>
                 <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
             </form>
         </div>
     @endsection
     ```
   - File: `edit.blade.php`
     ```html
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
                         <input type="text" name="nama_kelas" class="form-control @error('nama_kelas') is-invalid @enderror" value="{{ old('nama_kelas', $kelas['nama_kelas']) }}">
                         @error('nama_kelas')
                             <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
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
     ```
6. **Buat Layout**:
   - File: `resources/views/layouts/app.blade.php`
     ```html
     <!DOCTYPE html>
     <html lang="en">
     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>@yield('title')</title>
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     </head>
     <body>
         <div class="container">
             @yield('content')
         </div>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     </body>
     </html>
     ```

## 5. Troubleshooting
1. **Error: "Undefined array key 'id_kelas'"**:
   - Pastikan backend mengembalikan data dengan kunci `id_kelas`.
   - Periksa method `show` di `KelasController.php`.
2. **Error: "500 Internal Server Error" saat Update**:
   - Periksa log backend (`application/logs/log-<tanggal>.php`).
   - Pastikan method `update` di `Kelas.php` didefinisikan.
3. **Tampilan Tidak Sesuai**:
   - Pastikan Bootstrap dimuat dengan benar di `app.blade.php`.

## 6. Catatan
- Selalu periksa log untuk debugging:
  - Frontend: `storage/logs/laravel.log`
  - Backend: `application/logs/log-<tanggal>.php`
- Pastikan kedua server berjalan sebelum menguji aplikasi.

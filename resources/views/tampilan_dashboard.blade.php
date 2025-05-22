<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Akademik</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
    }

    h1, h2 {
      color: #1E3A8A; /* biru tua akademik */
    }

    .card {
      transition: all 0.3s ease;
    }

    .card:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    table thead {
      background-color: #F9FAFB;
    }

    table th, table td {
      padding: 0.75rem;
      border-bottom: 1px solid #E5E7EB;
    }

    a {
      transition: color 0.2s;
    }

    a:hover {
      color: #1D4ED8;
      text-decoration: underline;
    }
  </style>
</head>
<body class="p-6">

  <h1 class="text-4xl font-bold mb-6 text-blue-900">Dashboard Akademik</h1>

  <!-- Statistik -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 shadow-md rounded-2xl card border border-blue-100">
      <h2 class="text-lg font-semibold">Jumlah Dosen</h2>
      <p id="jumlah-dosen" class="text-2xl font-bold text-blue-600">...</p>
    </div>
    <div class="bg-white p-4 shadow-md rounded-2xl card border border-green-100">
      <h2 class="text-lg font-semibold">Jumlah Mahasiswa</h2>
      <p id="jumlah-mahasiswa" class="text-2xl font-bold text-green-600">...</p>
    </div>
    <div class="bg-white p-4 shadow-md rounded-2xl card border border-indigo-100">
      <h2 class="text-lg font-semibold">Program Studi</h2>
      <p id="jumlah-prodi" class="text-2xl font-bold text-indigo-600">...</p>
    </div>
  </div>

  <!-- Data Tables -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Dosen -->
    <div class="bg-white p-4 shadow-md rounded-2xl card border border-blue-100">
      <h2 class="text-xl font-bold mb-4">Data Dosen Terbaru</h2>
      <table class="w-full text-left text-sm">
        <thead>
          <tr>
            <th class="font-semibold">Nama</th>
            <th class="font-semibold">NIDN</th>
            <th class="font-semibold">Prodi</th>
          </tr>
        </thead>
        <tbody id="dosen-table-body">
          <tr><td colspan="3" class="text-gray-500">Memuat data...</td></tr>
        </tbody>
      </table>
      <a href="/dosen" class="text-blue-500 mt-2 inline-block">Lihat Semua</a>
    </div>

    <!-- Mahasiswa -->
    <div class="bg-white p-4 shadow-md rounded-2xl card border border-green-100">
      <h2 class="text-xl font-bold mb-4">Data Mahasiswa Terbaru</h2>
      <table class="w-full text-left text-sm">
        <thead>
          <tr>
            <th class="font-semibold">Nama</th>
            <th class="font-semibold">NIM</th>
            <th class="font-semibold">Prodi</th>
          </tr>
        </thead>
        <tbody id="mahasiswa-table-body">
          <tr><td colspan="3" class="text-gray-500">Memuat data...</td></tr>
        </tbody>
      </table>
      <a href="/mahasiswa" class="text-blue-500 mt-2 inline-block">Lihat Semua</a>
    </div>
  </div>

  <!-- JavaScript -->
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Fetch statistik
      fetch("/api/statistik")
        .then(res => res.json())
        .then(data => {
          document.getElementById("jumlah-dosen").textContent = data.jumlah_dosen;
          document.getElementById("jumlah-mahasiswa").textContent = data.jumlah_mahasiswa;
          document.getElementById("jumlah-prodi").textContent = data.jumlah_prodi;
        }).catch(() => {
          document.getElementById("jumlah-dosen").textContent = "-";
          document.getElementById("jumlah-mahasiswa").textContent = "-";
          document.getElementById("jumlah-prodi").textContent = "-";
        });

      // Fetch dosen
      fetch("/api/dosen?limit=5")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("dosen-table-body");
          tbody.innerHTML = data.map(d => `
            <tr>
              <td>${d.nama}</td>
              <td>${d.nidn}</td>
              <td>${d.prodi}</td>
            </tr>
          `).join('');
        });

      // Fetch mahasiswa
      fetch("/api/mahasiswa?limit=5")
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById("mahasiswa-table-body");
          tbody.innerHTML = data.map(m => `
            <tr>
              <td>${m.nama}</td>
              <td>${m.nim}</td>
              <td>${m.prodi}</td>
            </tr>
          `).join('');
        });
    });
  </script>
</body>
</html>

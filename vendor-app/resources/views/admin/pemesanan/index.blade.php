<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Vendor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            width: 220px;
            position: fixed;
            left: 0;
            top: 0;
            background-color: #343a40;
            color: white;
            padding-top: 1rem;
            z-index: 1000;
        }
        .sidebar.hide {
            left: -220px;
        }
        .sidebar a, .sidebar form button {
            color: white;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            background: none;
            border: none;
            text-align: left;
            width: 100%;
        }
        .sidebar a:hover, .sidebar form button:hover {
            background-color: #495057;
        }
        .topbar {
            height: 60px;
            background-color: #f8f9fa;
            padding: 0 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-left: 220px;
            transition: margin-left 0.3s ease;
        }
        .topbar.collapsed {
            margin-left: 0;
        }
        .content {
            margin-left: 220px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }
        .content.collapsed {
            margin-left: 0;
        }
        .toggle-btn {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -220px;
            }
            .sidebar.show {
                left: 0;
            }
            .topbar, .content {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
@if (session('success') || session('error'))
    <div id="alert-box" class="position-fixed top-50 start-50 translate-middle text-center" style="z-index:1055; min-width: 300px;">
        @if (session('success'))
            <div class="alert alert-success border border-success shadow rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger border border-danger shadow rounded" role="alert">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <script>
        setTimeout(() => {
            const alert = document.getElementById('alert-box');
            if (alert) {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500); // benar-benar hilang setelah fade
            }
        }, 3000);
    </script>
@endif

<div class="topbar d-flex justify-content-between align-items-center" id="topbar">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <h4 class="mb-0">Pemesanan - {{ ucfirst(Auth::user()->bisnis_unit) }}</h4>
</div>

<div class="sidebar" id="sidebar">
    <div class="text-center mb-3">
        <strong>{{ Auth::user()->name }}</strong><br>
        <small class="text-white">{{ ucfirst(Auth::user()->bisnis_unit) }}</small>
    </div>
    <a href="{{ route('unit.dashboard') }}">📊 Dashboard</a>
    <a href="{{ route('unit.transaksi.index') }}">💳 Riwayat Transaksi</a>
    <a href="{{ route('unit.vendor.index') }}">🛒 Buat Pemesanan</a>
    <a href="#">🔔 Notifikasi</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">🚪 Logout</button>
    </form>
</div>

<div class="content" id="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Daftar Vendor</h4>

        <!-- Form Filter -->
        <form method="GET" class="d-flex align-items-center gap-2">
            <label for="kategori" class="mb-0">Filter Kategori:</label>
            <select name="kategori" id="kategori" class="form-select w-auto" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Vendor</th>
                <th>Kategori</th>
                <th>Email</th>
                <th>Whatsapp</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($vendors as $index => $vendor)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $vendor->nama_vendor }}</td>
                    <td>{{ $vendor->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $vendor->email }}</td>
                    <td>{{ $vendor->whatsapp }}</td>
                    <td>
                        <a href="{{ route('unit.pemesanan.create', $vendor->id) }}" class="btn btn-primary btn-sm">
                            🛒 Pesan
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada vendor yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('main-content');
        const topbar = document.getElementById('topbar');
        sidebar.classList.toggle('hide');
        content.classList.toggle('collapsed');
        topbar.classList.toggle('collapsed');
    }
</script>

</body>
</html>
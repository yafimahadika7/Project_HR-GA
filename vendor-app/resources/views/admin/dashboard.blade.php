<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin Unit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            transition: all 0.3s ease;
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
    <div class="topbar d-flex justify-content-between align-items-center" id="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <h4 class="mb-0">Dashboard {{ ucfirst(Auth::user()->bisnis_unit) }}</h4>
    </div>

<!-- Sidebar -->
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

<!-- Content -->
 <div class="content" id="main-content">

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
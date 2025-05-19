<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Super Admin</title>
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

<!-- Sidebar -->
<div class="sidebar">
    <div class="text-center mb-3">
        <strong>{{ Auth::user()->name }}</strong><br>
        <small class="text-white">{{ ucfirst(Auth::user()->bisnis_unit) }}</small>
    </div>
    <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
    <a href="{{ route('admin.users.index') }}">👤 User</a>
    <a href="{{ route('admin.kategori.index') }}">📁 Kategori</a>
    <a href="{{ route('admin.vendor.index') }}">🏷️ Vendor</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">🚪 Logout</button>
    </form>
</div>

<!-- Topbar -->
<div class="topbar">
    <h4>Dashboard Super Admin</h4>
</div>

<!-- Content -->
<div class="content">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    👤 Total User
                    <h3>{{ $totalUser }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    📁 Total Kategori
                    <h3>{{ $totalKategori }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    🏷️ Total Vendor
                    <h3>{{ $totalVendor }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    🏢 Admin Unit
                    <h3>{{ $totalAdmin }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card">
        <div class="card-header">
            📊 Jumlah Vendor per Kategori
        </div>
        <div class="card-body">
            <canvas id="vendorChart" height="100"></canvas>
        </div>
    </div>
</div>

<!-- Chart JS -->
<script>
    const ctx = document.getElementById('vendorChart').getContext('2d');
    const vendorChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($kategoriLabels) !!},
            datasets: [{
                label: 'Jumlah Vendor',
                data: {!! json_encode($kategoriCounts) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision:0
                    }
                }
            }
        }
    });
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Vendor</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <div class="sidebar" id="sidebar">
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
    <div class="topbar" id="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <div class="d-flex align-items-center justify-content-between w-100">
            <span>Manajemen Vendor</span>
        </div>
    </div>

    <!-- Content -->
    <div class="content" id="main-content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filter Kategori -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- Filter Kategori -->
            <form method="GET" class="d-flex align-items-center gap-2 mb-0">
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

            <!-- Tombol Tambah -->
            <a href="{{ route('admin.vendor.create') }}" class="btn btn-success">➕ Tambah Vendor</a>
        </div>

        <!-- Tabel -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Vendor</th>
                    <th>Email</th>
                    <th>Whatsapp</th>
                    <th>Kategori</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vendors as $index => $vendor)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $vendor->nama_vendor }}</td>
                        <td>{{ $vendor->email }}</td>
                        <td>{{ $vendor->whatsapp }}</td>
                        <td>{{ $vendor->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $vendor->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ route('admin.vendor.edit', $vendor->id) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
                            <button onclick="confirmDelete({{ $vendor->id }})" class="btn btn-sm btn-danger">🗑️ Hapus</button>
                            <form id="delete-form-{{ $vendor->id }}" action="{{ route('admin.vendor.destroy', $vendor->id) }}" method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada vendor.</td>
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

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus vendor ini?',
                text: "Data vendor akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</body>
</html>
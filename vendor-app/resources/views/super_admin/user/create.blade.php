<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; margin: 0; padding: 0; overflow-x: hidden; }
        .sidebar { height: 100vh; width: 220px; position: fixed; left: 0; top: 0; background-color: #343a40; color: white; padding-top: 1rem; z-index: 1000; }
        .sidebar a, .sidebar form button { color: white; padding: 10px 20px; display: block; text-decoration: none; background: none; border: none; text-align: left; width: 100%; }
        .sidebar a:hover, .sidebar form button:hover { background-color: #495057; }
        .topbar { height: 60px; background-color: #f8f9fa; padding: 0 1rem; display: flex; align-items: center; justify-content: space-between; margin-left: 220px; }
        .content { margin-left: 220px; padding: 2rem; }
        @media (max-width: 768px) {
            .sidebar { left: -220px; }
            .topbar, .content { margin-left: 0 !important; }
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
    <div class="topbar">
        <h4>Tambah User</h4>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <!-- Content -->
    <div class="content">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label>App Password (Email)</label>
                <input type="text" name="email_app_password" class="form-control" value="{{ old('email_app_password') }}">
            </div>

            <div class="mb-3">
                <label>Bisnis Unit</label>
                <input type="text" name="bisnis_unit" class="form-control" value="{{ old('bisnis_unit') }}">
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">💾 Simpan User</button>
        </form>
    </div>

</body>
</html>
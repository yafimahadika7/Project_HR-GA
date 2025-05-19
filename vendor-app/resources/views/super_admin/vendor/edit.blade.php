<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Vendor</title>
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
        <h4>Edit Vendor</h4>
        <a href="{{ route('admin.vendor.index') }}" class="btn btn-secondary">← Kembali</a>
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

        <form action="{{ route('admin.vendor.update', $vendor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Nama Vendor</label>
                <input type="text" name="nama_vendor" class="form-control" required value="{{ old('nama_vendor', $vendor->nama_vendor) }}">
            </div>

            <div class="mb-3">
                <label>Email Vendor</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email', $vendor->email) }}">
            </div>

            <div class="mb-3">
                <label>No. Whatsapp</label>
                <input type="text" name="whatsapp" class="form-control" required value="{{ old('whatsapp', $vendor->whatsapp) }}">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $vendor->alamat) }}</textarea>
            </div>

            <div class="mb-3">
                <label>Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ $vendor->kategori_id == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Field Pemesanan (Checklist)</label><br>
                @php $fields = $vendor->input_fields ?? []; @endphp
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="input_fields[]" value="nama_barang" id="field1"
                        {{ in_array('nama_barang', $fields) ? 'checked' : '' }}>
                    <label class="form-check-label" for="field1">Nama Barang</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="input_fields[]" value="jumlah" id="field2"
                        {{ in_array('jumlah', $fields) ? 'checked' : '' }}>
                    <label class="form-check-label" for="field2">Jumlah</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="input_fields[]" value="ukuran_baju" id="field3"
                        {{ in_array('ukuran_baju', $fields) ? 'checked' : '' }}>
                    <label class="form-check-label" for="field3">Ukuran Baju (S, M, L, XL)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="input_fields[]" value="ukuran_celana" id="field4"
                        {{ in_array('ukuran_celana', $fields) ? 'checked' : '' }}>
                    <label class="form-check-label" for="field4">Ukuran Celana (Nomor)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="input_fields[]" value="upload_foto" id="field5"
                        {{ in_array('upload_foto', $fields) ? 'checked' : '' }}>
                    <label class="form-check-label" for="field5">Upload Foto</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">💾 Perbarui Vendor</button>
        </form>
    </div>

</body>
</html>
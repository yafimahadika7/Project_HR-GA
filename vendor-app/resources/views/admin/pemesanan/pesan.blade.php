<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; margin: 0; padding: 0; overflow-x: hidden; }
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
        .sidebar.hide { left: -220px; }
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
        .sidebar a:hover, .sidebar form button:hover { background-color: #495057; }
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
        .topbar.collapsed { margin-left: 0; }
        .content {
            margin-left: 220px;
            padding: 2rem;
            transition: margin-left 0.3s ease;
        }
        .content.collapsed { margin-left: 0; }
        .toggle-btn {
            font-size: 24px;
            background: none;
            border: none;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .sidebar { left: -220px; }
            .sidebar.show { left: 0; }
            .topbar, .content { margin-left: 0 !important; }
        }
    </style>
</head>
<body>
<div class="topbar d-flex justify-content-between align-items-center" id="topbar">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <a href="{{ route('unit.vendor.index') }}" class="btn btn-secondary">← Kembali</a>
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

    {{-- ALERT NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    {{-- FORM INPUT ITEM --}}
    <form id="form-pemesanan" action="javascript:void(0);">
        <div id="item-container">
            <div class="item-group border rounded p-3 mb-3">
                <h5>Item 1</h5>
                @if (in_array('nama_barang', $vendor->input_fields))
                    <div class="mb-2">
                        <label>Nama Barang</label>
                        <input type="text" name="items[0][nama_barang]" class="form-control" required>
                    </div>
                @endif
                @if (in_array('jumlah', $vendor->input_fields))
                    <div class="mb-2">
                        <label>Jumlah</label>
                        <input type="number" name="items[0][jumlah]" class="form-control" required>
                    </div>
                @endif
                @if (in_array('ukuran_baju', $vendor->input_fields))
                    <div class="mb-2">
                        <label>Ukuran Baju</label>
                        <select name="items[0][ukuran_baju]" class="form-select">
                            <option value="">Pilih</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                        </select>
                    </div>
                @endif
                @if (in_array('ukuran_celana', $vendor->input_fields))
                    <div class="mb-2">
                        <label>Ukuran Celana</label>
                        <input type="text" name="items[0][ukuran_celana]" class="form-control">
                    </div>
                @endif
                @if (in_array('upload_foto', $vendor->input_fields))
                    <div class="mb-2">
                        <label>Upload Foto</label>
                        <input type="file" name="items[0][upload_foto]" class="form-control">
                    </div>
                @endif
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addItem()">➕ Tambah Item</button>
    </form>

    <div class="d-flex gap-2">
        <button type="button" class="btn btn-success" onclick="kirimWhatsApp()">📱 Kirim via WhatsApp</button>
        <form id="form-email" action="{{ route('unit.unit.pemesanan.kirimEmail', $vendor->id) }}" method="POST">
            @csrf
            <input type="hidden" name="items_json" id="items_json">
            <button type="submit" class="btn btn-primary">📧 Kirim via Email</button>
        </form>
    </div>
</div>

<script>
    let itemIndex = 1;

    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('hide');
        document.getElementById('main-content').classList.toggle('collapsed');
        document.getElementById('topbar').classList.toggle('collapsed');
    }

    function addItem() {
        const container = document.getElementById('item-container');
        const firstItem = container.querySelector('.item-group');
        const clone = firstItem.cloneNode(true);

        clone.querySelector('h5').textContent = `Item ${itemIndex + 1}`;
        clone.querySelectorAll('input, select').forEach(input => {
            const name = input.name;
            input.name = name.replace(/\[\d+\]/, `[${itemIndex}]`);
            if (input.type !== 'file') input.value = '';
        });

        container.appendChild(clone);
        itemIndex++;
    }

    function kirimWhatsApp() {
        const vendorName = @json($vendor->nama_vendor);
        const nomorTujuan = "{{ $vendor->whatsapp }}";
        const namaUser = "{{ Auth::user()->name }}";
        const bisnisUnit = "{{ Auth::user()->bisnis_unit }}";

        let pesan = `Dear Bapak/Ibu PT ${vendorName},\n\n`;
        pesan += `Saya ${namaUser} dari unit ${bisnisUnit}, ingin melakukan pemesanan barang dengan rincian sebagai berikut:\n\n`;

        const items = document.querySelectorAll('.item-group');
        items.forEach((item, index) => {
            pesan += `===== ITEM ${index + 1} =====\n`;

            const namaBarang = item.querySelector(`[name*="[nama_barang]"]`);
            const jumlah = item.querySelector(`[name*="[jumlah]"]`);
            const ukuranBaju = item.querySelector(`[name*="[ukuran_baju]"]`);
            const ukuranCelana = item.querySelector(`[name*="[ukuran_celana]"]`);

            if (namaBarang?.value) pesan += `- Nama Barang: ${namaBarang.value}\n`;
            if (jumlah?.value) pesan += `- Jumlah: ${jumlah.value}\n`;
            if (ukuranBaju?.value) pesan += `- Ukuran Baju: ${ukuranBaju.value}\n`;
            if (ukuranCelana?.value) pesan += `- Ukuran Celana: ${ukuranCelana.value}\n`;

            pesan += `===============\n\n`;
        });

        pesan += `Demikian informasi pemesanan ini kami sampaikan.\n`;
        pesan += `Atas perhatian dan kerja samanya, kami ucapkan terima kasih.\n\n`;
        pesan += `Hormat saya,\n${namaUser}\n${bisnisUnit}`;

        const url = `https://wa.me/62${nomorTujuan}?text=${encodeURIComponent(pesan)}`;
        window.open(url, '_blank');

        setTimeout(() => {
            alert("✅ Pesan berhasil dikirim via WhatsApp.");
            window.location.href = "{{ route('unit.vendor.index') }}";
        }, 1000);
    }

    // Serialisasi item untuk email sebelum kirim
    document.getElementById('form-email').addEventListener('submit', function () {
        const items = [];
        document.querySelectorAll('.item-group').forEach(group => {
            const item = {};
            group.querySelectorAll('input, select').forEach(input => {
                const key = input.name.split('[')[2].replace(']', '');
                item[key] = input.value;
            });
            items.push(item);
        });
        document.getElementById('items_json').value = JSON.stringify(items);
    });

    // Auto-close alert setelah 5 detik
    setTimeout(() => {
        const alertEl = document.querySelector('.alert');
        if (alertEl) alertEl.classList.remove('show');
    }, 5000);
</script>
</body>
</html>
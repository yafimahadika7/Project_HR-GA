<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;

class VendorKontakController extends Controller
{
    public function index(Request $request)
    {
        $kategoriId = $request->query('kategori');
        $vendors = Vendor::with('kategori')
            ->when($kategoriId, function ($query) use ($kategoriId) {
                return $query->where('kategori_id', $kategoriId);
            })
            ->get();

        $kategoris = \App\Models\Kategori::all();

        return view('admin.pemesanan.index', compact('vendors', 'kategoris'));
    }
}
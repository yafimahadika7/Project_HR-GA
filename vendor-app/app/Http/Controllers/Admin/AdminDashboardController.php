<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Vendor;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUser = \App\Models\User::count();
        $totalAdmin = \App\Models\User::where('role', 'admin')->count();
        $totalKategori = \App\Models\Kategori::count();
        $totalVendor = \App\Models\Vendor::count();

        $kategoriLabels = \App\Models\Kategori::pluck('nama_kategori')->toArray();
        $kategoriCounts = \App\Models\Kategori::withCount('vendors')->pluck('vendors_count')->toArray();

        return view('super_admin.dashboard', compact(
            'totalUser', 'totalAdmin', 'totalKategori', 'totalVendor',
            'kategoriLabels', 'kategoriCounts'
        ));
    }

}
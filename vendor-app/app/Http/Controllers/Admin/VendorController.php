<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Kategori;

class VendorController extends Controller
{
    /**
     * Menampilkan daftar semua vendor.
     */
    public function index(Request $request)
    {
        $query = Vendor::with('kategori');

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        $vendors = $query->get();
        $kategoris = Kategori::all();

        return view('super_admin.vendor.index', compact('vendors', 'kategoris'));
    }

    /**
     * Menampilkan form tambah vendor.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('super_admin.vendor.create', compact('kategoris'));
    }

    /**
     * Menyimpan vendor baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_vendor'  => 'required|string|max:255',
            'email'        => 'required|email',
            'whatsapp'     => 'required|string|max:20',
            'alamat'       => 'required|string',
            'kategori_id'  => 'required|exists:kategoris,id',
            'input_fields' => 'nullable|array',
        ]);

        Vendor::create([
            'nama_vendor'  => $request->nama_vendor,
            'email'        => $request->email,
            'whatsapp'     => $request->whatsapp,
            'alamat'       => $request->alamat,
            'kategori_id'  => $request->kategori_id,
            'input_fields' => json_encode($request->input('input_fields', [])),
        ]);

        return redirect()->route('admin.vendor.index')->with('success', 'Vendor berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit vendor.
     */
    public function edit(Vendor $vendor)
    {
        $kategoris = Kategori::all();
        $vendor->input_fields = json_decode($vendor->input_fields, true);

        return view('super_admin.vendor.edit', compact('vendor', 'kategoris'));
    }

    /**
     * Menyimpan perubahan vendor.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'nama_vendor'  => 'required|string|max:255',
            'email'        => 'required|email',
            'whatsapp'     => 'required|string|max:20',
            'alamat'       => 'required|string',
            'kategori_id'  => 'required|exists:kategoris,id',
            'input_fields' => 'nullable|array',
        ]);

        $vendor->update([
            'nama_vendor'  => $request->nama_vendor,
            'email'        => $request->email,
            'whatsapp'     => $request->whatsapp,
            'alamat'       => $request->alamat,
            'kategori_id'  => $request->kategori_id,
            'input_fields' => json_encode($request->input('input_fields', [])),
        ]);

        return redirect()->route('admin.vendor.index')->with('success', 'Vendor berhasil diperbarui.');
    }

    /**
     * Menghapus vendor dari database.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return redirect()->route('admin.vendor.index')->with('success', 'Vendor berhasil dihapus.');
    }
}
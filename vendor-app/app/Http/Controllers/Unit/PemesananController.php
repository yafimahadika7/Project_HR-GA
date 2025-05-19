<?php

namespace App\Http\Controllers\Unit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Mail\PemesananEmail;
use App\Models\User;

class PemesananController extends Controller
{
    // Menampilkan daftar vendor untuk dipesan
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

    // Menampilkan form pemesanan untuk vendor tertentu
    public function create($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->input_fields = json_decode($vendor->input_fields, true);

        return view('admin.pemesanan.pesan', compact('vendor'));
    }

    // Mengirim pemesanan via WhatsApp
    public function sendWhatsapp(Request $request, $vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $user = Auth::user();

        $items = $request->input('items', []);
        $text = "Dear Bapak/Ibu PT {$vendor->nama_vendor},%0A%0A";
        $text .= "Saya {$user->name} dari unit {$user->bisnis_unit}, ingin melakukan pemesanan barang dengan rincian sebagai berikut:%0A%0A";

        foreach ($items as $index => $item) {
            $text .= "===== ITEM " . ($index + 1) . " =====%0A";
            foreach ($item as $key => $value) {
                $keyFormatted = ucwords(str_replace('_', ' ', $key));
                $text .= "- {$keyFormatted}: {$value}%0A";
            }
            $text .= "================%0A%0A";
        }

        $text .= "Demikian informasi pemesanan ini kami sampaikan.%0AAtas perhatian dan kerja samanya, kami ucapkan terima kasih.%0A%0AHormat saya,%0A{$user->name}%0A{$user->bisnis_unit}";

        $whatsappUrl = "https://wa.me/{$vendor->whatsapp}?text=" . urlencode($text);

        return redirect($whatsappUrl);
    }

    // Mengirim pemesanan via Email
    public function kirimEmail(Request $request, Vendor $vendor)
    {
        $request->validate([
            'items_json' => 'required|string',
        ]);

        $items = json_decode($request->items_json, true);
        $user = $request->user();

        if (empty($user->email) || empty($user->email_app_password)) {
            return back()->with('error', 'Email atau App Password belum diatur.');
        }

        // Konfigurasi SMTP dinamis
        Config::set('mail.mailers.smtp_dynamic', [
            'transport' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => 587,
            'encryption' => 'tls',
            'username' => $user->email,
            'password' => $user->email_app_password,
            'timeout' => null,
            'auth_mode' => null,
        ]);

        Config::set('mail.default', 'smtp_dynamic');

        try {
            Mail::to($vendor->email)->send(
                new PemesananEmail(
                    $user->name,
                    $user->bisnis_unit,
                    $vendor->nama_vendor,
                    $items
                )
            );
            return redirect()->route('unit.vendor.index')->with('success', '✅ Email berhasil dikirim.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal mengirim email: ' . $e->getMessage());
        }
    }
}

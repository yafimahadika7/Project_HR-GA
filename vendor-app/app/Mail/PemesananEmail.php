<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PemesananEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $namaUser;
    public $bisnisUnit;
    public $namaVendor;
    public $items;

    /**
     * Create a new message instance.
     */
    public function __construct($namaUser, $bisnisUnit, $namaVendor, $items)
    {
        $this->namaUser = $namaUser;
        $this->bisnisUnit = $bisnisUnit;
        $this->namaVendor = $namaVendor;
        $this->items = $items;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $message = "Dear Bapak/Ibu PT {$this->namaVendor},\n\n";
        $message .= "Saya {$this->namaUser} dari unit {$this->bisnisUnit}, ingin melakukan pemesanan barang dengan rincian sebagai berikut:\n\n";

        foreach ($this->items as $i => $item) {
            $message .= "===== ITEM " . ($i + 1) . " =====\n";
            if (!empty($item['nama_barang'])) {
                $message .= "- Nama Barang: " . $item['nama_barang'] . "\n";
            }
            if (!empty($item['jumlah'])) {
                $message .= "- Jumlah: " . $item['jumlah'] . "\n";
            }
            if (!empty($item['ukuran_baju'])) {
                $message .= "- Ukuran Baju: " . $item['ukuran_baju'] . "\n";
            }
            if (!empty($item['ukuran_celana'])) {
                $message .= "- Ukuran Celana: " . $item['ukuran_celana'] . "\n";
            }
            $message .= "===============\n\n";
        }

        $message .= "Demikian informasi pemesanan ini kami sampaikan.\n";
        $message .= "Atas perhatian dan kerja samanya, kami ucapkan terima kasih.\n\n";
        $message .= "Hormat saya,\n";
        $message .= "{$this->namaUser}\n";
        $message .= "{$this->bisnisUnit}";

        return $this->subject("Pemesanan Barang - {$this->bisnisUnit}")
                    ->text('emails.pemesanan_plain')
                    ->with(['pesan' => $message]);
    }
}
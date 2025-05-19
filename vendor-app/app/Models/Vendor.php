<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'vendors';

    protected $fillable = [
        'nama_vendor',
        'email',
        'whatsapp',
        'alamat',
        'kategori_id',
        'input_fields',
    ];

    protected $casts = [
        'input_fields' => 'array', // otomatis decode JSON ke array saat diakses
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
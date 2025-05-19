<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // Nama tabel (opsional jika sesuai konvensi)
    protected $table = 'kategoris';

    // Field yang bisa diisi (mass assignable)
    protected $fillable = [
        'nama_vendor',
        'email',
        'whatsapp',
        'alamat',
        'kategori_id',
        'input_fields',
    ];

    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'kategori_id');
    }

}
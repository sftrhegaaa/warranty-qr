<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Warranty extends Model
{
   
    use HasFactory;
    protected $table = 'warranties'; // ⬅️ WAJIB (jaga-jaga)


    protected $fillable = [
        'produk_qr_log_id',
        'nama',
        'email',
        'tanggal_lahir',
        'gender',
        'country_code',
        'province',
        'city',
        'district',
        'village',
    ];

    public function produk()
    {
    return $this->belongsTo(
            ProdukQrLog::class,
            'produk_qr_log_id',
            'id'
        );    }
}



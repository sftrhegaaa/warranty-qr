<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProdukQrLog extends Model
{
    use HasFactory;

    protected $table = 'produk_qr_log';

    protected $fillable = [
        'kode_barang',
        'nama_produk',
        'warna',
        'nama_toko',
        'qr',
        'qr_path',
        'status',
        'owner_user_id',
        'activated_at',
        'expired_at',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }


    public function warranties()
    {
        return $this->hasMany(Warranty::class,  'produk_qr_log_id', 'id');
    }

    
}

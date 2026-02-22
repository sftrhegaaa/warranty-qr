<?php

namespace App\Imports;

use App\Models\ProdukQrLog;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class ProdukQrImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
    $namaLengkap = strtoupper(trim($row[0]));

    // Pecah kata
    $parts = explode(' ', $namaLengkap);

    // Ambil kata terakhir sebagai warna
    $warna = array_pop($parts);

    // Gabungkan kembali tanpa warna
    $namaProduk = implode(' ', $parts);

    // Buat slug
    $slug = Str::slug($namaProduk, '-');

    $prefix = "JPA-" . strtoupper($slug) . "-" . $warna;

    // Cari nomor terakhir
    $last = ProdukQrLog::where('kode_barang', 'like', $prefix.'-%')
        ->orderByDesc('kode_barang')
        ->first();

    $lastNumber = $last ? (int) substr($last->kode_barang, -3) : 0;
    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

    $kodeBarang = "{$prefix}-{$nextNumber}";

    return new ProdukQrLog([
        'kode_barang' => $kodeBarang,
        'nama_produk' => $namaProduk,
        'warna'       => $warna,
        'qr'          => url('/qr/' . $kodeBarang),
        'status'      => 'active',
    ]);
}
}

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
        ->orderByDesc('id')
        ->first();

        if ($last) {
        $parts = explode('-', $last->kode_barang);
        $lastNumber = (int) end($parts);
    } else {
        $lastNumber = 0;
    }

    $nextNumber = $lastNumber + 1;
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

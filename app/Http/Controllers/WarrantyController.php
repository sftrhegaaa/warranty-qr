<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukQrLog;
use App\Models\Warranty;

class WarrantyController extends Controller
{
    public function create($kode)
    {
          // 1. Ambil produk QR (wajib ada & aktif)
            $produk = ProdukQrLog::where('kode_barang', $kode)
                ->where('status', 'active')
                ->first();

            if (!$produk) {
                abort(404, 'QR tidak ditemukan atau tidak aktif');
            }

            // 2. Cek apakah sudah pernah dipakai
            $warranty = Warranty::where('produk_qr_log_id', $produk->id)
                ->exists(); // lebih ringan & cepat

            if ($warranty) {
                return view('warranty.already-scan');
            }

            // 3. Kalau belum pernah → boleh isi warranty
            return view('warranty.create', compact('produk'));
    }

    public function store(Request $request, $kode)
    {

        $produk = ProdukQrLog::where('kode_barang', $kode)->firstOrFail();

        $request->validate([
            'nama'          => 'required|string',
            'email'         => 'required|email',
            'tanggal_lahir' => 'required|date',
            'gender'        => 'required|in:L,P',
            'nota'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'country_code'  => 'nullable|string|size:2',
            'province'      => 'nullable|string',
            'city'          => 'nullable|string',
            'district'      => 'nullable|string',
            'village'       => 'nullable|string',
            'address'       => 'nullable|string',
        ]);

        // $notaPath = null;

        // if ($request->hasFile('nota')) {
        //     $notaPath = $request->file('nota')->store('nota', 'public');
        // }

        Warranty::create([
            'produk_qr_log_id' => $produk->id,
            'nama'             => $request->nama,
            'email'            => $request->email,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'gender'           => $request->gender,
            'country_code' => $request->country_code,
            'province'     => $request->province,
            'city'         => $request->city,
            'district'     => $request->district,
            'village'      => $request->village,
            'address'      => $request->address,
            'state'        => $request->state,
            'global_city'  => $request->global_city,
            'nota'    => $request->file('nota')->store('nota', 'public'),
            ]);



        return redirect()->route('warranty.verified', $produk->kode_barang);

            //  dd('MASUK STORE', $request->all(), $kode);

    }

    public function verified($kode)
    {
        $produk = ProdukQrLog::where('kode_barang', $kode)->firstOrFail();

        $warranty = Warranty::where('produk_qr_log_id', $produk->id)
            ->latest()
            ->firstOrFail();

        return view('warranty.verified', compact('produk', 'warranty'));
    }

}

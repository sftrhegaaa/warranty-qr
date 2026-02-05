<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warranty;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

use Illuminate\Http\Request;

class HistoryWarrantyController extends Controller
{
     public function index()
    {
        return view('admin.warranty.index');
    }

    public function data()
    {
        $data = Warranty::with('produk')->latest();

    return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('kode_barang', fn ($w) => $w->produk->kode_barang ?? '-')
        ->addColumn('nama_produk', fn ($w) => $w->produk->nama_produk ?? '-')
        ->addColumn('warna', fn ($w) => $w->produk->warna ?? '-')
        ->addColumn('expired_at', function ($w) {
            return $w->created_at
                ? $w->created_at->copy()->addMonths(6)->format('d M Y')
                : '-';
        // ->addColumn('expired_at', function ($w) {
        //     if (!$w->created_at) return '-';

        //     $bulan = $this->getGaransiBulan($w);
 
        //     return $w->created_at
        //         ->copy()
        //         ->addMonths($bulan)
        //         ->format('d M Y');


        })
        ->addColumn('status', function ($w) {
            if (!$w->created_at) {
                return '<span class="badge bg-secondary">UNKNOWN</span>';
            }

            $expired = $w->created_at->copy()->addMonths(6);
            // $bulan = $this->getGaransiBulan($w);
            // $expired = $w->created_at->copy()->addMonths($bulan);

            return now()->lte($expired)
                ? '<span class="badge bg-success">ACTIVE</span>'
                : '<span class="badge bg-danger">EXPIRED</span>';
        })
        ->rawColumns(['status'])
        ->make(true);
    }

    // private function getGaransiBulan($warranty)
    // {
    //     $kode = strtoupper($warranty->produk->kode_barang ?? '');

    //     // kalau ada kata RGB → 3 bulan
    //     return str_contains($kode, 'RGB') ? 3 : 6;
    // }

}

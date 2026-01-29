<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warranty;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class HistoryUserController extends Controller
{
   public function index()
    {
         $warranties = Warranty::latest()->get();

    // ambil data EMSIFA SEKALI (hemat)
    $provinces = Http::get(url('/api/indo/provinces'))->json();

    foreach ($warranties as $w) {

        // default alamat lama
        $w->alamat_admin = $w->alamat;

        if ($w->country_code === 'ID' && $w->province) {

            $prov = collect($provinces)
                ->firstWhere('id', $w->province);

            $regencies = Http::get(
                url("/api/indo/regencies/{$w->province}")
            )->json();

            $city = collect($regencies)
                ->firstWhere('id', $w->city);

            $districts = Http::get(
                url("/api/indo/districts/{$w->city}")
            )->json();

            $district = collect($districts)
                ->firstWhere('id', $w->district);

            $villages = Http::get(
                url("/api/indo/villages/{$w->district}")
            )->json();

            $village = collect($villages)
                ->firstWhere('id', $w->village);

            $w->alamat_admin =
                "{$w->alamat}<br>" .
                "{$prov['name']}, {$city['name']}<br>" .
                "Kec. {$district['name']}, Kel. {$village['name']}";
        }
    }

        // $warranties = Warranty::with('produk')
        //     ->latest()
        //     ->get();

        return view('admin.user-history.index', compact('warranties'));
    }
}
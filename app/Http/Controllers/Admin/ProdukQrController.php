<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdukQrLog;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProdukQrImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\GenerateQrJob;


class ProdukQrController extends Controller
{
    public function index(Request $request)
    {
        $produk = ProdukQrLog::orderBy('id', 'desc')->get();
        $lastId = $request->query('last_id');

        $start = $request->query('start');
        $end   = $request->query('end');


        return view('admin.produk-qr.index', compact('produk', 'lastId', 'start', 'end'));
    }

     public function create()
    {
        return view('admin.produk-qr.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'warna' => 'required|string',
            'nama_toko' => 'nullable|string|max:255',

        ]);

        // slug dari nama produk
        $slug = Str::upper(
            Str::slug($request->nama_produk, '-')
        );

        $warna = Str::upper($request->warna);

        // prefix brand (opsional)
        $prefix = 'JPA';

        // hitung urutan
        $count = ProdukQrLog::where('kode_barang', 'like', "{$prefix}-{$slug}-{$warna}-%")->count() + 1;

        $kodeBarang = sprintf(
            '%s-%s-%s-%03d',
            $prefix,
            $slug,
            $warna,
            $count
        );

        // URL QR
        $qrUrl = url('/warranty/' . $kodeBarang);

        ProdukQrLog::create([
            'kode_barang' => $kodeBarang,
            'nama_produk' => $request->nama_produk,
            'warna' => $warna,
            'nama_toko' => $request->nama_toko,
            'qr' => $qrUrl,
            'status' => 'active',
        ]);
        

        return redirect()->route('admin.produk_qr.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }


    public function edit(ProdukQrLog $produk)
    {
        return view('admin.produk-qr.edit', compact('produk'));
    }


    public function update(Request $request, ProdukQrLog $produk)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'warna' => 'required|string',
            'nama_toko' => 'nullable|string|max:255',
        ]);


        $produk->update([
            'nama_produk' => $request->nama_produk,
            'warna' =>  Str::upper($request->warna),
            'nama_toko' => $request->nama_toko,
        ]);

        return redirect()->route('admin.produk_qr.index')
            ->with('success', 'Produk berhasil diupdate');
    }
    public function destroy(ProdukQrLog $produk)
    {
        $produk->delete();

        return redirect()->route('admin.produk_qr.index')
            ->with('success', 'Produk berhasil dihapus');
    }


    //     /* GENERATE QR CODE */
   public function downloadSvg($id)
    {
        $produk = ProdukQrLog::findOrFail($id);

        // 1️⃣ Generate QR SVG
        $qrSvg = QrCode::format('svg')
            ->size(600)
            ->margin(2)
            ->generate($produk->qr);

        // 2️⃣ Hapus XML declaration & tag <svg>
        $qrSvg = preg_replace('/<\?xml.*?\?>/i', '', $qrSvg);
        $qrSvg = preg_replace('/<svg[^>]*>|<\/svg>/', '', $qrSvg);

        // 3️⃣ Canvas settings
        $canvasWidth  = 1000;
        $canvasHeight = 650;
        $qrSize       = 600;

        $qrX = ($canvasWidth - $qrSize) / 2;
        $qrY = 40;
        $textY = $qrY + $qrSize + 2;

        // 4️⃣ Gabungkan QR + teks
        $svg = '
        <svg xmlns="http://www.w3.org/2000/svg"
            width="'.$canvasWidth.'"
            height="'.$canvasHeight.'"
            viewBox="0 0 '.$canvasWidth.' '.$canvasHeight.'">

            <rect width="100%" height="100%" fill="#fff"/>

            <g transform="translate('.$qrX.','.$qrY.')">
                '.$qrSvg.'
            </g>

            <text x="'.($canvasWidth / 2).'"
                y="'.$textY.'"
                text-anchor="middle"
                font-size="22"
                font-family="Arial"
                fill="#000">
                '.$produk->kode_barang.'
            </text>

        </svg>';

        // 5️⃣ RETURN HARUS PALING AKHIR
        return response($svg)
            ->header('Content-Type', 'image/svg+xml')
            ->header(
                'Content-Disposition',
                'attachment; filename="QR-'.$produk->kode_barang.'.svg"'
            );
    }

    
    public function generateQr($id)
    {
       $produk = ProdukQrLog::findOrFail($id);

        $filename = "stiker-{$produk->kode_barang}.png";
        $path = "qr/" . $filename;

        Storage::disk('public')->makeDirectory('qr');

        $png = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($produk->qr);

        Storage::disk('public')->put($path, $png);

        $produk->update([
            'qr_path' => $path
        ]);

        return true;
    }


    public function import(Request $request)
        {
            ini_set('memory_limit', '512M');

            set_time_limit(300);

             $lastId = ProdukQrLog::max('id') ?? 0;

            Excel::import(new ProdukQrImport, $request->file('file'));


            $newData = ProdukQrLog::where('id', '>', $lastId)->get();

            foreach ($newData as $item) {
                $this->generateQr($item->id);
            }
            return redirect()
                ->back()
                ->with('success', 'Data berhasil diimport')
                ->with('last_id', $lastId);
                
        }


    public function downloadA4Pdf($start, $end)
{
    $data = ProdukQrLog::whereBetween('id', [$start + 1, $end])
        ->orderBy('id')
        ->get();

    if ($data->isEmpty()) {
        return back()->with('error', 'Data tidak ditemukan.');
    }

    foreach ($data as $item) {

        $filename = "stiker-{$item->kode_barang}.png";
        $path = "qr/" . $filename;

        // 🔥 kalau belum ada file, generate
        if (!Storage::disk('public')->exists($path)) {

            Storage::disk('public')->makeDirectory('qr');

            $png = QrCode::format('png')
                ->size(300)
                ->margin(1)
                ->generate(url('/warranty/' . $item->kode_barang));

            Storage::disk('public')->put($path, $png);

            $item->update([
                'qr_path' => $path
            ]);
        }
    }

   $customPaper = [0, 0, 85.04, 56.69]; // 30mm x 20mm

    $pdf = PDF::loadView('admin.produk-qr.stiker-a4', [
        'stickers' => $data
    ]);

    $pdf->setPaper($customPaper, 'portrait');

    return $pdf->download('STIKER-A4.pdf');
}

    public function downloadA4Pdf1($lastId)
    {

        $data = ProdukQrLog::where('id', '>', $lastId)->get();


        if ($data->isEmpty()) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        
        foreach ($data as $item) {

            $filename = "stiker-{$item->kode_barang}.png";
            $path = "qr/" . $filename;

            // 🔥 cek kalau belum ada file
            if (!Storage::disk('public')->exists($path)) {

                Storage::disk('public')->makeDirectory('qr');

                $png = QrCode::format('png')
                    ->size(300)
                    ->margin(1)
                    ->generate(url('/warranty/' . $item->kode_barang));

                Storage::disk('public')->put($path, $png);

                $item->update([
                    'qr_path' => $path
                ]);
            }
        }

        $pdf = PDF::loadView('admin.produk-qr.stiker-a4', [
            'stickers' => $data
        ])->setPaper('a4', 'portrait');

        return $pdf->download('STIKER-A4.pdf');
    }


//  public function importBatch(Request $request)
// {
//     try {

//         $rows = $request->input('data');

//         if (!$rows) {
//             return response()->json(['success' => false]);
//         }

//         foreach ($rows as $row) {

//             $namaLengkap = strtoupper(trim($row['nama_produk'] ?? ''));
//             if (!$namaLengkap) continue;

//             $parts = explode(' ', $namaLengkap);
//             $warna = array_pop($parts);
//             $namaProduk = implode(' ', $parts);

//             $slug = strtoupper(Str::slug($namaProduk, '-'));

//             // 🔥 Insert dulu tanpa kode_barang
//             $produk = ProdukQrLog::create([
//                 'kode_barang' => 'TEMP',
//                 'nama_produk' => $namaProduk,
//                 'warna'       => $warna,
//                 'qr'          => '',
//                 'status'      => 'active',
//             ]);

//             // 🔥 Pakai ID sebagai nomor unik
//             $kodeBarang = "JPA-{$slug}-{$warna}-{$produk->id}";

//             $produk->update([
//                 'kode_barang' => $kodeBarang,
//                 'qr' => url('/warranty/' . $kodeBarang),
//             ]);
//         }

//         return response()->json(['success' => true]);

//     } catch (\Throwable $e) {

//         return response()->json([
//             'success' => false,
//             'error' => $e->getMessage()
//         ]);
//     }
// }

public function importBatch(Request $request)
{
     try {

        $rows = $request->input('data');

        if (!$rows) {
            return response()->json(['success' => false]);
        }

        // 🔥 Ambil ID sebelum seluruh import dimulai
        $startId = $request->input('start_id');

        if (!$startId) {
            $startId = ProdukQrLog::max('id') ?? 0;
        }

        foreach ($rows as $row) {

            $namaLengkap = strtoupper(trim($row['nama_produk'] ?? ''));
            if (!$namaLengkap) continue;

            $parts = explode(' ', $namaLengkap);
            $warna = array_pop($parts);
            $namaProduk = implode(' ', $parts);
            $slug = strtoupper(Str::slug($namaProduk, '-'));

            $produk = ProdukQrLog::create([
                'kode_barang' => 'TEMP',
                'nama_produk' => $namaProduk,
                'warna'       => $warna,
                'qr'          => '',
                'status'      => 'active',
            ]);

            $kodeBarang = "JPA-{$slug}-{$warna}-{$produk->id}";

            $produk->update([
                'kode_barang' => $kodeBarang,
                'qr' => url('/warranty/' . $kodeBarang),
            ]);
        }

        $endId = ProdukQrLog::max('id');

        return response()->json([
            'success' => true,
            'start_id' => $startId,
            'end_id' => $endId
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
    public function importFinish( $start, $end)
    {
        

        return redirect()->route('admin.produk_qr.index')->with('success', "Import selesai untuk ID {$start} sampai {$end}");
    }
    
}

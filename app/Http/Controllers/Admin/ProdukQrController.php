<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProdukQrLog;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;


class ProdukQrController extends Controller
{
    public function index()
    {
        $produk = ProdukQrLog::orderBy('id', 'desc')->get();

        return view('admin.produk-qr.index', compact('produk'));
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
        ]);

        $slug = Str::upper(Str::slug($request->nama_produk, '-'));
        $warna = Str::upper($request->warna);
        $prefix = 'JPA';

        $kodeBarang = "{$prefix}-{$slug}-{$warna}-001";
        $qrUrl = url('/qr/' . $kodeBarang);

        $produk->update([
            'kode_barang' => $kodeBarang,
            'nama_produk' => $request->nama_produk,
            'warna' => $warna,
            'qr' => $qrUrl,
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
            ->size(500)
            ->margin(2)
            ->generate($produk->qr);

        // 2️⃣ Hapus XML declaration & tag <svg>
        $qrSvg = preg_replace('/<\?xml.*?\?>/i', '', $qrSvg);
        $qrSvg = preg_replace('/<svg[^>]*>|<\/svg>/', '', $qrSvg);

        // 3️⃣ Canvas settings
        $canvasWidth  = 1000;
        $canvasHeight = 650;
        $qrSize       = 500;

        $qrX = ($canvasWidth - $qrSize) / 2;
        $qrY = 40;
        $textY = $qrY + $qrSize + 40;

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

        $filename = "QR-{$produk->kode_barang}.svg";
        $path = "qr/" . $filename;

        Storage::disk('public')->makeDirectory('qr');

        // generate QR SVG
        $svg = QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate($produk->qr);

        // tambahkan teks di bawah QR
        $text = $produk->kode_barang;

        $svg = str_replace(
            '</svg>',
            '
            <text x="50%" y="330"
                text-anchor="middle"
                font-size="16"
                font-family="Arial"
                fill="black">'
                . $text .
            '</text>
            </svg>',
            $svg
        );
        $qrSvg = QrCode::format('svg')
            ->size(500)
            ->margin(2)
            ->generate($produk->qr);

        // 🔥 HAPUS XML declaration
        $qrSvg = preg_replace('/<\?xml.*?\?>/i', '', $qrSvg);

        // 🔥 HAPUS tag <svg> pembungkus QR
        $qrSvg = preg_replace('/<\/*svg[^>]*>/', '', $qrSvg);
        $canvasWidth  = 1000;
        $canvasHeight = 650;
        $qrSize       = 500;
        $paddingTop   = 20;

        // posisi QR di tengah horizontal
        $qrX = ($canvasWidth - $qrSize) / 2;
        $qrY = $paddingTop;

        // posisi teks
        $textY = $qrY + $qrSize + 40;

        $svg =
        '<svg xmlns="http://www.w3.org/2000/svg"
            width="'.$canvasWidth.'"
            height="'.$canvasHeight.'"
            viewBox="0 0 '.$canvasWidth.' '.$canvasHeight.'">

            <rect width="100%" height="100%" fill="#fff"/>

            <g transform="translate('.$qrX.','.$qrY.')">
                '.$qrSvg.'
            </g>

            <text x="'.($canvasWidth / 2).'" y="'.$textY.'"
                text-anchor="middle"
                dominant-baseline="hanging"
                font-size="20"
                font-family="Arial"
                fill="#000">
                '.$produk->kode_barang.'
            </text>

        </svg>';





        Storage::disk('public')->put($path, $svg);

        $produk->update([
            'qr_path' => $path
        ]);

        return back()->with('success', 'QR berhasil dibuat');
    }





}

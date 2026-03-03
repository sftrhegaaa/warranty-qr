<?php

namespace App\Jobs;

use App\Models\ProdukQrLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;


class GenerateQrJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {

      $produk = ProdukQrLog::find($this->id);

        if (!$produk) return;

        $qrImage = QrCode::format('png')
            ->size(300)
            ->generate($produk->kode);

        $fileName = 'qr/'.$produk->kode.'.png';

        Storage::disk('public')->put($fileName, $qrImage);

        $produk->update([
            'qr_path' => $fileName
        ]);
        // $produk = ProdukQrLog::find($this->id);

        // if ($produk) {
        //     app()->make(\App\Http\Controllers\Admin\ProdukQrController::class)
        //         ->generateQr($produk->id);
        // }
    }
}
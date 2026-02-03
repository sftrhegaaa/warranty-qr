<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DownloadStikerController extends Controller
{
    public function download($kode)
    {
        $qr = QrCode::format('svg')
        ->size(110)
        ->margin(1)
        ->generate($kode);

    // bersihin wrapper svg QR
    $qr = preg_replace('/<\?xml.*?\?>/', '', $qr);
    $qr = preg_replace('/<\/?svg[^>]*>/', '', $qr);

    $logo = $this->logoBase64();

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg"
     width="600" height="240"
     viewBox="0 0 600 240">

  <defs>
    <linearGradient id="bgGrad" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#ffffff"/>
      <stop offset="100%" stop-color="#f0f0f0"/>
    </linearGradient>
  </defs>

  <!-- BACKGROUND -->
  <rect x="4" y="4" width="592" height="232"
        rx="16" ry="16"
        fill="url(#bgGrad)"
        stroke="#d6d6d6"
        stroke-width="2"/>

  <!-- LOGO -->
  <image href="$logo"
         x="28" y="26"
         width="90" height="36"
         preserveAspectRatio="xMidYMid meet"/>

  <!-- HEADER -->
  <text x="135" y="50"
        font-size="14"
        font-family="Montserrat, Arial, sans-serif"
        font-weight="600"
        fill="#111">
    GMA PRODUCT SERIES
  </text>

  <!-- TITLE -->
  <text x="28" y="95"
        font-size="22"
        font-family="Montserrat, Arial, sans-serif"
        font-weight="800"
        fill="#000">
    WARRANTY REGISTRATION
  </text>

  <!-- SUBTITLE -->
  <text x="28" y="125"
        font-size="12"
        font-family="Montserrat, Arial, sans-serif"
        font-weight="400"
        fill="#555">
    Scan QR code to verify authenticity
  </text>

  <text x="28" y="142"
        font-size="12"
        font-family="Montserrat, Arial, sans-serif"
        font-weight="400"
        fill="#555">
    and activate warranty
  </text>

  <!-- QR PANEL -->
  <rect x="420" y="24"
        width="150" height="150"
        rx="12" ry="12"
        fill="#ffffff"
        stroke="#cfcfcf"
        stroke-width="1.5"/>

  <!-- QR -->
  <g transform="translate(445,45)">
    $qr
  </g>

  <!-- CODE -->
  <foreignObject x="420" y="180" width="150" height="36">
    <div xmlns="http://www.w3.org/1999/xhtml"
         style="
           font-family: Montserrat, Arial, sans-serif;
           font-size: 9px;
           font-weight: 600;
           text-align: center;
           line-height: 1.2;
           word-break: break-word;
           max-height: 2.4em;
           overflow: hidden;
         ">
      {$kode}
    </div>
  </foreignObject>

</svg>
SVG;

    return response($svg, 200, [
        'Content-Type' => 'image/svg+xml',
        'Content-Disposition' => "attachment; filename=stiker-$kode.svg"
    ]);
    }

    private function logoBase64()
    {
        $path = public_path('assets/LOGO-GMA.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/'.$type.';base64,'.base64_encode($data);
    }
}

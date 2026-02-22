<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DownloadStikerController extends Controller
{
    public function download($kode)
    {
        $qrUrl = url('/warranty/' . $kode);
        $qr = QrCode::format('svg')
        ->size(200)
        ->margin(0)
        ->generate($qrUrl);

    // bersihin wrapper svg QR
    $qr = preg_replace('/<\?xml.*?\?>/', '', $qr);
    $qr = preg_replace('/<\/?svg[^>]*>/', '', $qr);

    $logo = $this->logoBase64();

  $kodeFormatted = implode('<br />', str_split($kode, 22));
  
$normalized = str_replace('-', '- ', $kode);
$wrapped = wordwrap($normalized, 26, "\n", false);
$lines = explode("\n", $wrapped);

$line1 = trim($lines[0] ?? '');
$line2 = trim($lines[1] ?? '');
$line3 = trim($lines[2] ?? '');

  


  $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg"
     width="850" height="320"
     viewBox="0 0 850 320">

  <defs>
    <linearGradient id="bgDark" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#000000"/>
      <stop offset="100%" stop-color="#101417"/>
    </linearGradient>
  </defs>

  <!-- OUTER BLACK FRAME -->
  <rect x="0" y="0"
        width="850" height="320"
        rx="36"
        fill="#000000"
        stroke="#FFFFFF"
        stroke-width="8"/>

  <!-- INNER DARK CARD (FRAME EFFECT) -->
  <rect x="6" y="6"
        width="838"
        height="308"
        rx="30"
        fill="url(#bgDark)"/>

  <!-- LOGO -->
  <image href="$logo"
         x="48" y="40"
         width="200" height="60"/>

  <!-- BADGE -->
  <g transform="translate(300,45)">
    <rect width="180" height="34"
          rx="17"
          fill="#000"
          stroke="#555"
          stroke-width="1"/>
    <circle cx="20" cy="17" r="5" fill="#ff2d2d"/>
    <text x="40" y="22"
          font-size="12"
          font-family="Montserrat, Arial"
          font-weight="600"
          fill="#ffffff">
      THIS IS COMPULSARY
    </text>
  </g>

  <!-- TITLE -->
  <text x="48" y="135"
        font-size="30"
        font-family="Montserrat, Arial"
        font-weight="800"
        fill="#ffffff">
    <tspan x="48" dy="0">Scan QR Code to fill</tspan>
    <tspan x="48" dy="36">in the warranty</tspan>
    <tspan x="48" dy="36">registration form.</tspan>
  </text>

  <!-- SUBTEXT -->
  <text x="48" y="240"
        font-size="16"
        font-family="Montserrat, Arial"
        fill="#bdbdbd">
    Please carefully read the warranty terms and conditions.
  </text>

  <!-- QR WHITE CARD -->
  <rect x="590" y="23"
        width="250"
        height="280"
        rx="28"
        fill="#f4f4f4"/>

  <!-- QR -->
  <g transform="translate(615 50)">
    $qr
  </g>

  <!-- CODE -->
  <text x="705" y="272"
        font-size="10"
        font-family="Montserrat, Arial"
        font-weight="600"
        fill="#000"
        text-anchor="middle">
    <tspan x="720" dy="0">$line1</tspan>
    <tspan x="720" dy="12">$line2</tspan>
    <tspan x="720" dy="12">$line3</tspan>
  </text>

</svg>
SVG;


    return response($svg, 200, [
        'Content-Type' => 'image/svg+xml',
        'Content-Disposition' => "attachment; filename=stiker-$kode.svg"
    ]);
    }

    private function logoBase64()
    {
        $path = public_path('assets/JPA-01.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);

        return 'data:image/'.$type.';base64,'.base64_encode($data);
    }
}

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
        ->size(160)
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
     width="900" height="320"
     viewBox="0 0 900 320">

     <defs>
      <linearGradient id="bgDark" x1="0" y1="0" x2="1" y2="1">
        <stop offset="0%" stop-color="#0a0a0a"/>
        <stop offset="100%" stop-color="#1f2326"/>
      </linearGradient>

      <filter id="cardShadow">
        <feDropShadow dx="0" dy="4"
                      stdDeviation="10"
                      flood-color="#000"
                      flood-opacity="0.35"/>
      </filter>
    </defs>


  <!-- GREY PREVIEW BACKGROUND (ROUNDED) -->
  <rect x="0" y="0"
        width="900" height="320"
        rx="34"
        fill="#ffffff"/>

  <!-- WHITE BORDER -->
  <rect x="2" y="2"
        width="896" height="316"
        rx="30"
        fill="none"
        stroke="#ffffff"
        stroke-width="2"/>


  <!-- DARK CARD -->
<rect x="6" y="6"
      width="888" height="308"
      rx="26"
      fill="url(#bgDark)"
      filter="url(#cardShadow)"/>

  <!-- LOGO -->
  <image href="$logo"
       x="48" y="34"
       width="220" height="66"/>


  <!-- BADGE -->
  <g transform="translate(300,42)">
    <rect width="170" height="32" rx="16" fill="#0f0f0f" stroke="#2a2a2a"/>
    <circle cx="18" cy="16" r="5" fill="#ff2d2d"/>
    <text x="36" y="21"
          font-size="12"
          font-family="Montserrat, Arial"
          font-weight="600"
          fill="#ffffff">
      THIS IS COMPULSARY
    </text>
  </g>

    <!-- TITLE (CONNECTED, NOT CUT) -->
  <text x="48" y="120"
        font-size="28"
        font-family="Montserrat, Arial"
        font-weight="800"
        fill="#ffffff">
    <tspan x="48" dy="0">Scan QR Code to fill</tspan>
    <tspan x="48" dy="38">in the warranty registration form.</tspan>
  </text>


    <!-- SUBTEXT -->
    <text x="48" y="240"
          font-size="15"
          font-family="Montserrat, Arial"
          fill="#bdbdbd">
      Please carefully read the warranty terms and conditions.
    </text>

 <!-- QR CARD -->
<rect x="630" y="10"
      width="260" height="300"
      rx="26"
      fill="#ffffff"/>


<!-- QR CODE (CENTERED & BIGGER) -->
<g transform="translate(688 74)">
  $qr
</g>

   <!-- CODE (FINAL POSITION) -->
<text x="772" y="250"
      font-size="10"
      font-family="Montserrat, Arial"
      font-weight="600"
      fill="#000"
      text-anchor="middle">
  <tspan x="772" dy="0">$line1</tspan>
  <tspan x="772" dy="11">$line2</tspan>
  <tspan x="772" dy="11">$line3</tspan>
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

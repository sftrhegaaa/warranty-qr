<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    size: 40mm 30mm;
    margin: 0;
}

body {
    margin: 0;
    font-family: DejaVu Sans, sans-serif;
}

.label {
    width: 40mm;
    height: 30mm;
    page-break-inside: avoid;
    overflow: hidden;
    text-align: center;
}

.qr {
    width: 21mm; 
    height: auto;
    display: block;
    margin: 0.6mm auto 0 auto;
}

.kode {
     font-size: 4.3pt;
    font-weight: 700;
    margin-top: 0.4mm;
    line-height: 1.0;
    max-height: 6mm;
    overflow: hidden;
    text-align: center;
    word-break: break-word;

    transform: translateX(-0.5mm); 
}
</style>
</head>

<body>

@foreach($stickers as $sticker)

<div class="label">
    {{-- <img class="qr" src="{{ asset('storage/'.$sticker->qr_path) }}"> --}}
    <img class="qr" src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $sticker->qr_path))) }}">
    @php
    $kode = strtoupper($sticker->kode_barang);
    $parts = explode('-', $kode);

    $half = ceil(count($parts)/2);

    $line1 = implode('-', array_slice($parts, 0, $half));
    $line2 = implode('-', array_slice($parts, $half));
    @endphp

    <div class="kode">
        {{ $line1 }}<br>
        {{ $line2 }}
    </div>
</div>

@endforeach

</body>
</html>
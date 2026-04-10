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
    page-break-after: always;
    page-break-inside: avoid;
    overflow: hidden;
    text-align: center;
}

.qr {
    width: 23mm;   /* 🔥 ukuran paling aman */
    height: 23mm;
    display: block;
    margin: 0.6mm auto 0 auto;
}

.kode {
     font-size: 4.8pt;        /* kecilin dikit dari 5.3pt */
    font-weight: 700;
    margin-top: 0.4mm;
    line-height: 1.05;
    letter-spacing: 0.4px;

    text-align: center;
    word-break: break-word;

    transform: translateX(-0.5mm); 
}
</style>
</head>

<body>

@foreach($stickers as $sticker)

<div class="label">
    <img class="qr"
         src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/'.$sticker->qr_path))) }}">
    @php
    $kode = strtoupper($sticker->kode_barang);
    $split = explode('-', $kode);
    $last = array_pop($split);
@endphp

<div class="kode">
    {{ implode('-', $split) }}-<br>
    {{ $last }}
</div>
</div>

@endforeach

</body>
</html>
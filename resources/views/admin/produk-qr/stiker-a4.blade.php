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
    font-size: 5.4pt;
    font-weight: 700;
    margin-top: 0.5mm;
    line-height: 1;
    letter-spacing: 0.5px;   /* biar lebih rapi */
    word-break: break-word;
    text-align: center;
}
</style>
</head>

<body>

@foreach($stickers as $sticker)

<div class="label">
    <img class="qr"
         src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/'.$sticker->qr_path))) }}">
    <div class="kode">
        {{strtoupper(str_replace('-', '-', $sticker->kode_barang)) }}
    </div>
</div>

@endforeach

</body>
</html>
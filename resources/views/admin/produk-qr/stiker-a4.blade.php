<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page {
    size: 30mm 20mm;
    margin: 0;
}

body {
    margin: 0;
    padding: 0;
    font-family: DejaVu Sans, sans-serif;
}

.label {
    width: 30mm;
    height: 20mm;
    text-align: center;
    page-break-after: always;
}

.qr {
    width: 13mm;
    height: 13mm;
    margin-top: 1mm;
}

.kode {
    font-size: 5pt;
    font-weight: bold;
    line-height: 1;
    margin-top: 0.5mm;

}
</style>
</head>

<body>

@foreach($stickers as $sticker)

<div class="label">
    <img class="qr"
         src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/'.$sticker->qr_path))) }}">
    <div class="kode">
        {{ $sticker->kode_barang }}
    </div>
</div>

@endforeach

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Warranty Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f7f7f7;
        }
        .card {
            border-radius: 12px;
        }
        .logo {
            max-width: 140px;
        }
        .status-active {
            color: #198754;
            font-weight: 600;
        }
        .status-expired {
            color: #dc3545;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-4">
    
    <div class="card shadow-sm">
        <div class="card-body p-4">
             <div class="text-center">
                        <img src="{{ asset('assets/LOGO-GMA.png') }}" class="logo mb-2" alt="GMA Logo">
            </div>
            <div class="row g-4">

                {{-- LEFT --}}
                <div class="col-md-6 text-center text-md-start">

                    <h5 class="fw-bold text-center mb-3">WARRANTY TERMS</h5>

                    <ol class="small text-start">
                        <li>The warranty period for RGB lights is valid for 3 (three) months from the date of purchase.</li>
                        <li>The warranty period for original single-color lights (Red / Blue / White) is valid for 6 (six) months from the date of purchase.</li>
                        <li>The warranty applies only if all required data is filled in completely.</li>
                        <li>Attach the purchase receipt with the shop date and stamp.</li>
                        <li>Warranty is void if damage occurs due to installation errors by the user.</li>
                        <li>Warranty is void if cable cutting or splicing is done incorrectly.</li>
                    </ol>

                    {{-- LOGO UNDER TERMS --}}
                    <div class="text-center mt-4">
                        <img src="{{ asset('assets/LOGO-JPA.png') }}" class="logo mb-2" alt="GMA Logo">
                        <div class="fw-semibold small">Warranty Verification</div>
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="col-md-6">

                    <h5 class="fw-bold text-success mb-3 text-center text-md-start">
                        Produk Terverifikasi
                    </h5>

                    <p class="mb-2">
                        <strong>Kode:</strong><br>
                        {{ $produk->kode_barang }}
                    </p>

                    <p class="mb-2">
                        <strong>Produk:</strong><br>
                        {{ $produk->nama_produk }}
                    </p>

                    <p class="mb-2">
                        <strong>Warna:</strong> {{ $produk->warna }}
                    </p>

                    <p class="mb-0">
                        <strong>Status:</strong>
                        <span class="status-active">ACTIVE</span>
                    </p>

                </div>

            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <div class="text-center mt-4">
        <small class="text-muted">
            © {{ date('Y') }} Warranty System by GMA Product Series
        </small>
    </div>

</div>

</body>
</html>

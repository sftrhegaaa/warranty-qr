<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Warranty</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

     <style>
        .btn-brand {
            background-color: #feea00;
            color: #000;
            font-weight: 600;
            border: none;
        }
        .btn-brand:hover {
            background-color: #e6d500;
            color: #000;
        }

        .address-form {
            max-width: 420px;
            margin: auto;
        }

        .address-form .title {
            text-align: center;
            margin-bottom: 20px;
        }

        .address-form .form-control {
            border-radius: 30px;
            padding: 12px 18px;
            margin-bottom: 12px;
        }

    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-12">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    
                    {{-- LOGO --}}
                    <div class="text-center mb-3">
                        <img 
                            src="{{ asset('assets/LOGO-GMA.png') }}" 
                            alt="Logo Brand" 
                            style="max-height:70px"
                        >
                    </div>

                    {{-- HEADER --}}
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">Warranty Registration</h4>
                        <p class="text-muted small mb-0">
                            Please complete the data to activate the product warranty
                        </p>
                    </div>

                    {{-- INFO PRODUK --}}
                    {{-- <div class="alert alert-success small">
                        <strong>Produk:</strong><br>
                        {{ $produk->nama_produk }}<br><br>

                        <strong>Kode Produk:</strong><br>
                        {{ $produk->kode_barang }}
                    </div> --}}

                    {{-- ALERT SUCCESS --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                    </div>
                    @endif
                    {{-- FORM --}}
                    <form method="POST" action="{{ route('warranty.store', $produk->kode_barang) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                       

                        <div class="row">
                            

                            <div class="col mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="tanggal_lahir" class="form-control" required>
                            </div>
                        </div>

                        {{-- <div class="mb-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Pilih Gender</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div> --}}
                        <div class="row">
                            

                            <div class="col mb-3">
                                <label class="form-label">Upload Nota</label>
                                <input type="file" name="nota" id="notaInput" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                        <div class="row">

                            <div class="mb-3">
                                <label class="form-label">Address Information</label>
    
                                <!-- COUNTRY -->
                                <select id="country" name="country_code" class="form-control">
                                    <option value="">Select Country</option>
                                </select>
    
                                <!-- INDONESIA ADDRESS -->
                                <div class="mb-3" id="indonesia-address" style="display:none;">
    
                                    <select id="province" name="province" class="form-control mb-3 mt-3">
                                        <option value="">Province</option>
                                    </select>
    
                                    <select id="regency" name="city" class="form-control mb-3" disabled>
                                        <option value="">Regency / City</option>
                                    </select>
    
                                    <select id="district" name="district" class="form-control mb-3" disabled>
                                        <option value="">District</option>
                                    </select>
    
                                    <select id="village" name="village" class="form-control mb-3" disabled>
                                        <option value="">Village</option>
                                    </select>
    
                                </div>
    
    
                                <!-- GLOBAL ADDRESS -->
                                <div class="mb-3" id="global-address" style="display:none;">
                                    <input type="text" name="state" class="form-control mb-3 mt-3" placeholder="State / Region">
                                    <input type="text" name="global_city" class="form-control mb-3" placeholder="City">
                                </div>
                                
                                <div class="col mb-3">
                                    <label class="form-label mt-3">Address</label>
                                    <textarea class="form-control" name="address" rows="3" placeholder="Address Detail"></textarea>
                                </div>

                            </div>
                        </div>


                        <button type="submit" class="btn btn-brand w-100">
                            Register Warranty
                        </button>
                    </form>

                </div>
            </div>

            <div class="text-center mt-3">
                <small class="text-muted">
                    © {{ date('Y') }} Warranty System by GMA Product Series
                </small>
            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const country = document.getElementById('country');
        const indoBox = document.getElementById('indonesia-address');
        const globalBox = document.getElementById('global-address');

        const province = document.getElementById('province');
        const regency = document.getElementById('regency');
        const district = document.getElementById('district');
        const village = document.getElementById('village');

        /*
        |--------------------------------------------------------------------------
        | Helper fetch JSON
        |--------------------------------------------------------------------------
        */
        async function fetchJson(url) {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${url}`);
            }

            return response.json();
        }

        /*
        |--------------------------------------------------------------------------
        | Helper membuat option
        |--------------------------------------------------------------------------
        */
        function appendOption(select, value, text) {
            const option = document.createElement('option');

            option.value = value;
            option.textContent = text;

            select.appendChild(option);
        }

        /*
        |--------------------------------------------------------------------------
        | Reset wilayah Indonesia
        |--------------------------------------------------------------------------
        */
        function resetIndonesiaAddress() {
            province.innerHTML =
                '<option value="">Select Province</option>';

            regency.innerHTML =
                '<option value="">Select Regency / City</option>';

            district.innerHTML =
                '<option value="">Select District</option>';

            village.innerHTML =
                '<option value="">Select Village</option>';

            regency.disabled = true;
            district.disabled = true;
            village.disabled = true;
        }

        /*
        |--------------------------------------------------------------------------
        | Load countries
        |--------------------------------------------------------------------------
        */
        async function loadCountries() {
            country.disabled = true;
            country.innerHTML =
                '<option value="">Loading countries...</option>';

            try {
                const result = await fetchJson('/api/countries');

                if (!Array.isArray(result.data)) {
                    throw new Error('Format response countries tidak valid');
                }

                country.innerHTML =
                    '<option value="">Select Country</option>';

                result.data.forEach(item => {
                    appendOption(
                        country,
                        item.code,
                        item.name
                    );
                });
            } catch (error) {
                console.error('Gagal load countries:', error);

                country.innerHTML =
                    '<option value="">Failed to load countries</option>';
            } finally {
                country.disabled = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Load provinces
        |--------------------------------------------------------------------------
        */
        async function loadProvinces() {
            resetIndonesiaAddress();

            province.disabled = true;
            province.innerHTML =
                '<option value="">Loading provinces...</option>';

            try {
                const data = await fetchJson('/api/indo/provinces');

                if (!Array.isArray(data)) {
                    throw new Error('Format response provinces tidak valid');
                }

                province.innerHTML =
                    '<option value="">Select Province</option>';

                data.forEach(item => {
                    appendOption(
                        province,
                        item.id,
                        item.name
                    );
                });
            } catch (error) {
                console.error('Gagal load provinces:', error);

                province.innerHTML =
                    '<option value="">Failed to load provinces</option>';
            } finally {
                province.disabled = false;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Country change
        |--------------------------------------------------------------------------
        */
        country.addEventListener('change', function () {
            resetIndonesiaAddress();

            if (this.value === 'ID') {
                indoBox.style.display = 'block';
                globalBox.style.display = 'none';

                loadProvinces();
                return;
            }

            if (this.value !== '') {
                indoBox.style.display = 'none';
                globalBox.style.display = 'block';
                return;
            }

            indoBox.style.display = 'none';
            globalBox.style.display = 'none';
        });

        /*
        |--------------------------------------------------------------------------
        | Province -> Regency / City
        |--------------------------------------------------------------------------
        */
        province.addEventListener('change', async function () {
            regency.innerHTML =
                '<option value="">Select Regency / City</option>';

            district.innerHTML =
                '<option value="">Select District</option>';

            village.innerHTML =
                '<option value="">Select Village</option>';

            regency.disabled = true;
            district.disabled = true;
            village.disabled = true;

            if (!this.value) {
                return;
            }

            regency.innerHTML =
                '<option value="">Loading regencies...</option>';

            try {
                const data = await fetchJson(
                    `/api/indo/regencies/${encodeURIComponent(this.value)}`
                );

                if (!Array.isArray(data)) {
                    throw new Error('Format response regencies tidak valid');
                }

                regency.innerHTML =
                    '<option value="">Select Regency / City</option>';

                data.forEach(item => {
                    appendOption(
                        regency,
                        item.id,
                        item.name
                    );
                });

                regency.disabled = false;
            } catch (error) {
                console.error('Gagal load regencies:', error);

                regency.innerHTML =
                    '<option value="">Failed to load regencies</option>';
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Regency -> District
        |--------------------------------------------------------------------------
        */
        regency.addEventListener('change', async function () {
            district.innerHTML =
                '<option value="">Select District</option>';

            village.innerHTML =
                '<option value="">Select Village</option>';

            district.disabled = true;
            village.disabled = true;

            if (!this.value) {
                return;
            }

            district.innerHTML =
                '<option value="">Loading districts...</option>';

            try {
                const data = await fetchJson(
                    `/api/indo/districts/${encodeURIComponent(this.value)}`
                );

                if (!Array.isArray(data)) {
                    throw new Error('Format response districts tidak valid');
                }

                district.innerHTML =
                    '<option value="">Select District</option>';

                data.forEach(item => {
                    appendOption(
                        district,
                        item.id,
                        item.name
                    );
                });

                district.disabled = false;
            } catch (error) {
                console.error('Gagal load districts:', error);

                district.innerHTML =
                    '<option value="">Failed to load districts</option>';
            }
        });

        /*
        |--------------------------------------------------------------------------
        | District -> Village
        |--------------------------------------------------------------------------
        */
        district.addEventListener('change', async function () {
            village.innerHTML =
                '<option value="">Select Village</option>';

            village.disabled = true;

            if (!this.value) {
                return;
            }

            village.innerHTML =
                '<option value="">Loading villages...</option>';

            try {
                const data = await fetchJson(
                    `/api/indo/villages/${encodeURIComponent(this.value)}`
                );

                if (!Array.isArray(data)) {
                    throw new Error('Format response villages tidak valid');
                }

                village.innerHTML =
                    '<option value="">Select Village</option>';

                data.forEach(item => {
                    appendOption(
                        village,
                        item.id,
                        item.name
                    );
                });

                village.disabled = false;
            } catch (error) {
                console.error('Gagal load villages:', error);

                village.innerHTML =
                    '<option value="">Failed to load villages</option>';
            }
        });

        loadCountries();
    });
</script>
<script>
document.getElementById("notaInput").addEventListener("change", function (event) {

    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.readAsDataURL(file);

    reader.onload = function (e) {

        const img = new Image();
        img.src = e.target.result;

        img.onload = function () {

            const canvas = document.createElement("canvas");
            const ctx = canvas.getContext("2d");

            let width = img.width;
            let height = img.height;

            const MAX_WIDTH = 1280;

            if (width > MAX_WIDTH) {
                height = height * (MAX_WIDTH / width);
                width = MAX_WIDTH;
            }

            canvas.width = width;
            canvas.height = height;

            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(function(blob){

                const compressedFile = new File(
                    [blob],
                    file.name,
                    {type: "image/jpeg"}
                );

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);

                document.getElementById("notaInput").files = dataTransfer.files;

                console.log("Compressed size:", (compressedFile.size/1024).toFixed(0), "KB");

            }, "image/jpeg", 0.7); // kualitas 70%
        }
    }
});
</script>
</body>
</html>



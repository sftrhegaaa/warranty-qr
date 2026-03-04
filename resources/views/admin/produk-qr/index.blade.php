@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-3">
        <h3 class="mb-0 me-3">Data Produk QR</h3>
        {{-- <form action="{{ route('admin.produk_qr.import') }}" method="POST" enctype="multipart/form-data"
            class="">
            @csrf
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-success btn-sm">
                Import Excel
            </button>
        </form> --}}
        <div class="ms-auto d-flex gap-2 me-3">
        
       @if(session('last_id'))
            <a href="{{ route('admin.stiker.a4', session('last_id')) }}"
            class="btn btn-success mt-2">
                Download Qr
            </a>
        @endif
        @if(isset($start) && isset($end))
            <a href="{{ route('admin.stiker.a4', ['start' => $start, 'end' => $end]) }}"
                class="btn btn-success mt-2">
                        Download Qr
            </a>  
        @endif

  <!-- Button trigger modal -->
<button type="button" class="btn btn-success btn-sm   " data-bs-toggle="modal" data-bs-target="#exampleModal">
 <i class="fa fa-upload" aria-hidden="true"></i>  Import Excel
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Upload File Excel</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
         <label for="file" class="form-label">Pilih file Excel</label>
        <input type="file" class="form-control" id="fileInput" name="file" accept=".xlsx, .xls" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button onclick="uploadData()" class="btn btn-success btn-sm">Upload</button>
      </div>
    </div>
  </div>
</div>
        </div>



        <a href="{{ route('admin.produk_qr.create') }}" class="btn btn-primary btn-sm">
            + Tambah Produk
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-bordered table-striped  align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Kode Barang</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Warna</th>
                            <th class="text-center">Nama Toko</th>
                            <th class="text-center">QR Code</th>
                            <th class="text-center">Download</th>
                            <th class="text-center">Status</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $p)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}.</td>
                            <td class="text-center">{{ $p->kode_barang }}</td>
                            <td class="text-center">{{ $p->nama_produk }}</td>
                            <td class="text-center">{{ $p->warna }}</td>
                            <td class="text-center">{{ $p->nama_toko }}</td>
                            <td>
                                {!! QrCode::size(50)->generate($p->qr) !!}
                                <div class="mt-2 small fw-bold">
                                    {{ $p->kode_barang }}
                                </div>
                            </td>
                            <td class="text-center">
                                 <a
                                    href="{{ route('admin.produk_qr.svg', $p->id) }}"
                                    class="btn btn-outline-primary btn-sm d-block px-2 py-1" style="font-size:11px">
                                    Download QR
                                </a>
                                <br>
                                 <a
                                    href="{{ route('admin.stiker.download', $p->kode_barang) }}"
                                    class="btn btn-outline-primary btn-sm d-block px-2 py-1" style="font-size:11px">
                                    Download Stiker
                                </a>
                            </td>


                            <td>
                                <span class="badge bg-success">
                                    {{ $p->status ?? 'active' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.produk_qr.edit', $p->id) }}"
                                class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <form action="{{ route('admin.produk_qr.destroy', $p->id) }}"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Yakin hapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">
                                        Hapus
                                    </button>
                                </form>
                            </td>

                        </tr>
                        
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        paging: true,
        searching: true,
        ordering: false,
        info: true,
        lengthMenu: [5, 10, 25, 50, 100],
        pageLength: 10,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "›",
                previous: "‹"
            },
            zeroRecords: "Data tidak ditemukan"
        }
    });
});
</script>
<script>
   async function uploadData() {

    const file = document.getElementById('fileInput').files[0];

    if (!file) {
        alert('Pilih file terlebih dahulu');
        return;
    }

    const reader = new FileReader();

    reader.onload = async function(e) {

        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
        let jsonData = XLSX.utils.sheet_to_json(sheet);

        const chunkSize = 20;

        let startId = null;
        let endId = null;

        for (let i = 0; i < jsonData.length; i += chunkSize) {

            const batch = jsonData.slice(i, i + chunkSize);

            const response = await fetch("{{ route('admin.produk_qr.import_batch') }}", {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    data: batch,
                    start_id: startId   // 🔥 kirim startId sekali
                })
            });

            const result = await response.json();

            if (!result.success) {
                alert("Import gagal");
                return;
            }

            if (startId === null) {
                startId = result.start_id;
            }

            endId = result.end_id;
        }

        alert("Import selesai");

        window.location.href =
            "{{ route('admin.produk_qr.index') }}?start=" + startId + "&end=" + endId;
    };

    reader.readAsArrayBuffer(file);
}
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const url = new URL(window.location);

    if (url.searchParams.has('start') && url.searchParams.has('end')) {

        // hapus parameter tanpa reload
        url.searchParams.delete('start');
        url.searchParams.delete('end');

        window.history.replaceState({}, document.title, url.pathname);
    }

});
</script>

{{-- <script>
    async function uploadData() {

    const file = document.getElementById('fileInput').files[0];

    if (!file) {
        alert('Pilih file dulu');
        return;
    }

    const reader = new FileReader();

    reader.onload = async function(e) {

        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];

        let jsonData = XLSX.utils.sheet_to_json(sheet);

        const chunkSize = 20;

        console.log("TOTAL DATA:", jsonData.length);
        console.log(jsonData[0]);

        for (let i = 0; i < jsonData.length; i += chunkSize) {

            const batch = jsonData.slice(i, i + chunkSize);

            const response = await fetch("{{ route('admin.produk_qr.import_batch') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                     credentials: 'same-origin', // pastikan cookie dikirim
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ data: batch })
            });

             console.log("Batch terkirim:", i);
        }

        alert("Import selesai");
        window.location.reload();
    };

    reader.readAsArrayBuffer(file);
}


</script> --}}
@endpush
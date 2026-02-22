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

            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fa fa-upload" aria-hidden="true"></i> Import Excel </button>
        </div>



        <a href="{{ route('admin.produk_qr.create') }}" class="btn btn-primary btn-sm">
            + Tambah Produk
        </a>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Produk QR dari Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.produk_qr.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Pilih file Excel</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
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
                        @forelse ($produk as $p)
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
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted">
                                Data belum ada
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
$(document).ready(function () {
    $('#usersTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        lengthMenu: [5, 10, 25, 50],
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
@endpush
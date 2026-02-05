@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Data Produk QR</h3>
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
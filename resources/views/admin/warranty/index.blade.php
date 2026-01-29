@extends('admin.layouts.app')

@section('title', 'History Warranty')

@section('content')
<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">History Warranty (User Scan QR)</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive mt-5 mb-5">
            <table class="table table-bordered table-striped" id="warrantyTable">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode Barang</th>
                        <th class="text-center">Nama Produk</th>
                        <th class="text-center">Warna</th>
                        <th class="text-center">Nama User</th>
                        <th class="text-center">Email</th>
                        <th class="text-center" width="120">Expired At</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Tanggal Registrasi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
    $('#warrantyTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,   // ⬅️ penting
        width: '100%', 
        ajax: "{{ route('admin.warranty.data') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center',render: function (data, type, row, meta) 
                { return data + '.'; }
            },
            { data: 'kode_barang', name: 'produk.kode_barang' },
            { data: 'nama_produk', name: 'produk.nama_produk' },
            { data: 'warna', name: 'produk.warna' },
            { data: 'nama', name: 'nama' },
            { data: 'email', name: 'email' },
            { data: 'expired_at', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' ,
                render: function (data) {
                if (!data) return '-';
                const date = new Date(data);
                return date.toLocaleDateString('id-ID');

            }
         },
        ],

        columnDefs: [
            {
                className: 'text-center'
            }
        ]
    });
});
</script>
@endpush

